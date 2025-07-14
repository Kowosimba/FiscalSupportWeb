<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\CustomerTicketResolvedNotification;
use App\Notifications\CustomerTicketCreatedNotification;
use App\Notifications\TicketAssignedNotification;

class SupportTicketController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'service' => 'required|string|max:255',
            'contact_details' => 'nullable|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,jpg,png',
        ]);

        try {
            $attachmentPath = $request->hasFile('attachment') 
                ? $this->storeAttachment($request->file('attachment')) 
                : null;

            $ticket = Ticket::create([
                'company_name' => $validatedData['name'],
                'contact_details' => $validatedData['contact_details'],
                'email' => $validatedData['email'],
                'subject' => $validatedData['service'],
                'message' => $validatedData['message'],
                'service' => $validatedData['service'],
                'attachment' => $attachmentPath,
                'status' => 'pending',
                'priority' => $request->input('priority', 'low'),
            ]);

            // Delay notification for UX
            $ticket->notify((new CustomerTicketCreatedNotification($ticket))->delay(now()->addSeconds(10)));

            return redirect()->back()
                ->with('message', 'Your ticket has been submitted successfully! Ticket ID: ' . $ticket->id);

        } catch (\Exception $e) {
            Log::error('Ticket submission failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->except('attachment')
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit ticket. Please try again.');
        }
    }

    protected function storeAttachment($file): string
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('attachments', $filename, 'public');
    }

    public function index(Request $request)
    {
        try {
            $now = Carbon::now();
            $currentWeekStart = $now->copy()->startOfWeek();
            $currentWeekEnd = $now;
            $previousWeekStart = $now->copy()->subWeek()->startOfWeek();
            $previousWeekEnd = $now->copy()->subWeek()->endOfWeek();

            // Cache expensive queries
            $currentCounts = Cache::remember('current_counts_' . $currentWeekStart->format('Y-m-d'), 3600, function() use ($currentWeekStart, $currentWeekEnd) {
                return $this->getStatusCountsByDateRange($currentWeekStart, $currentWeekEnd) ?? (object)[
                    'total' => 0,
                    'pending' => 0,
                    'in_progress' => 0,
                    'resolved' => 0,
                    'unassigned' => 0
                ];
            });

            $previousCounts = Cache::remember('previous_counts_' . $previousWeekStart->format('Y-m-d'), 3600, function() use ($previousWeekStart, $previousWeekEnd) {
                return $this->getStatusCountsByDateRange($previousWeekStart, $previousWeekEnd) ?? (object)[
                    'total' => 0,
                    'pending' => 0,
                    'in_progress' => 0,
                    'resolved' => 0,
                    'unassigned' => 0
                ];
            });

            $percentageChanges = [];
            foreach (['pending', 'in_progress', 'resolved', 'unassigned'] as $status) {
                $current = $currentCounts->$status ?? 0;
                $previous = $previousCounts->$status ?? 0;
                $percentageChanges[$status] = $previous == 0 ? ($current > 0 ? 100 : 0) : round((($current - $previous) / $previous * 100));
            }

            // Cache status counts
            $statusCounts = Cache::remember('ticket_status_counts', 3600, function() {
                return Ticket::select([
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending"),
                    DB::raw("SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress"),
                    DB::raw("SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved"),
                    DB::raw("SUM(CASE WHEN assigned_to IS NULL THEN 1 ELSE 0 END) as unassigned")
                ])->first();
            });

            // Main tickets query
            $query = Ticket::with(['assignedTechnician:id,name'])
                ->select(['id', 'subject', 'company_name', 'priority', 'status', 'updated_at', 'assigned_to']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }
            if ($request->filled('assigned_to')) {
                $query->where('assigned_to', $request->assigned_to);
            }
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('subject', 'like', "%{$searchTerm}%")
                      ->orWhere('company_name', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%");
                });
            }

            // Active technicians
            $technicians = User::whereIn('id', function($query) {
                    $query->select('assigned_to')->from('tickets')->whereNotNull('assigned_to');
                })
                ->select(['id', 'name'])
                ->get();

            $tickets = $query->latest()->paginate(10);
            $priorities = ['low', 'medium', 'high'];

            return view('admin.index', compact('statusCounts', 'percentageChanges', 'tickets', 'technicians', 'priorities'));

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return back()->with('error', 'Error loading dashboard. Please try again.');
        }
    }

    public function allTickets(Request $request)
    {
        $query = Ticket::with(['assignedTechnician:id,name'])
            ->select(['id', 'subject', 'company_name', 'priority', 'status', 'updated_at', 'assigned_to']);

        $this->applyFilters($query, $request);

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $technicians = User::whereIn('id', function($query) {
                $query->select('assigned_to')->from('tickets')->whereNotNull('assigned_to');
            })
            ->select(['id', 'name'])
            ->get();

        $statuses = ['in_progress', 'pending', 'resolved'];
        $priorities = ['low', 'medium', 'high'];

        return view('admin.all-tickets', compact('tickets', 'technicians', 'statuses', 'priorities'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load([
            'comments' => function($query) {
                $query->latest()->limit(10)->with(['user:id,name']);
            },
            'assignedTechnician:id,name'
        ]);

        $technicians = User::where('role', 'technician')
            ->select(['id', 'name'])
            ->get();

        return view('admin.ticketshow', compact('ticket', 'technicians'));
    }

    public function openTickets(Request $request)
    {
        $query = Ticket::where('status', 'in_progress')
            ->with(['assignedTechnician:id,name'])
            ->select(['id', 'subject', 'company_name', 'priority', 'status', 'updated_at', 'assigned_to']);

        $this->applyFilters($query, $request);

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $technicians = User::whereIn('id', function($query) {
                $query->select('assigned_to')->from('tickets')->whereNotNull('assigned_to');
            })
            ->select(['id', 'name'])
            ->get();

        $priorities = ['low', 'medium', 'high'];

        return view('admin.open-tickets', compact('tickets', 'technicians', 'priorities'));
    }

    public function solvedTickets(Request $request)
    {
        $query = Ticket::where('status', 'resolved')
            ->with(['assignedTechnician:id,name'])
            ->select(['id', 'subject', 'company_name', 'priority', 'status', 'updated_at', 'assigned_to']);

        $this->applyFilters($query, $request);

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $technicians = User::whereIn('id', function($query) {
                $query->select('assigned_to')->from('tickets')->whereNotNull('assigned_to');
            })
            ->select(['id', 'name'])
            ->get();

        $priorities = ['low', 'medium', 'high'];

        return view('admin.solved-tickets', compact('tickets', 'technicians', 'priorities'));
    }

    public function pendingTickets(Request $request)
    {
        $query = Ticket::where('status', 'pending')
            ->with(['assignedTechnician:id,name'])
            ->select(['id', 'subject', 'company_name', 'priority', 'status', 'updated_at', 'assigned_to']);

        $this->applyFilters($query, $request);

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $technicians = User::whereIn('id', function($query) {
                $query->select('assigned_to')->from('tickets')->whereNotNull('assigned_to');
            })
            ->select(['id', 'name'])
            ->get();

        $priorities = ['low', 'medium', 'high'];

        return view('admin.pending-tickets', compact('tickets', 'technicians', 'priorities'));
    }

    public function unassignedTickets(Request $request)
    {
        $query = Ticket::whereNull('assigned_to')
            ->with(['assignedTechnician:id,name'])
            ->select(['id', 'subject', 'company_name', 'priority', 'status', 'updated_at', 'assigned_to']);

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                  ->orWhere('company_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $technicians = User::where('role', 'technician')
            ->select(['id', 'name'])
            ->get();

        $statuses = ['pending', 'in_progress', 'resolved', 'closed'];
        $priorities = ['low', 'medium', 'high'];

        return view('admin.unassigned-tickets', compact('tickets', 'technicians', 'statuses', 'priorities'));
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_details' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'service' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $validated['status'] = 'in_progress';

        $ticket = Ticket::create($validated);

        if ($ticket->assigned_to) {
            $technician = User::find($ticket->assigned_to);
            $technician->notify(new TicketAssignedNotification($ticket));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'ticket' => $ticket
            ]);
        }

        return redirect()->route('admin.tickets.unassigned')
            ->with('message', 'Ticket created successfully!');
    }

    public function create()
    {
        $technicians = User::where('role', 'technician')
            ->select(['id', 'name'])
            ->get();
        return view('admin.adminticket', compact('technicians'));
    }

    public function assignTechnician(Request $request, Ticket $ticket)
    {
        $this->authorize('assign tickets');

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $previousTechnician = $ticket->assigned_to;
        $ticket->assigned_to = $request->assigned_to;
        $ticket->save();

        if ($previousTechnician != $ticket->assigned_to) {
            $technician = User::find($ticket->assigned_to);
            $technician->notify(new TicketAssignedNotification($ticket));
        }

        return redirect()->route('admin.tickets.unassigned')->with('success', 'Ticket assigned successfully!');
    }

    public function myTickets(Request $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your tickets.');
        }

        $query = Ticket::where('assigned_to', $userId)
            ->select(['id', 'subject', 'priority', 'status', 'updated_at']);

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $query->where('subject', 'like', "%{$request->search}%");
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        $priorities = ['low', 'medium', 'high'];

        return view('admin.my-tickets', compact('tickets', 'priorities'));
    }

    public function updatePriority(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->priority = $request->input('priority');
        $ticket->save();
        return back()->with('message', 'Priority updated.');
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->input('status');
        $ticket->save();
        return back()->with('message', 'Status updated.');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        return back()->with('message', 'Comment added!');
    }

    public function updateStatusPriority(Request $request, Ticket $ticket)
{
    if (Auth::id() !== $ticket->assigned_to) {
        abort(403, 'Unauthorized action.');
    }

    $validated = $request->validate([
        'priority' => 'required|in:low,medium,high',
        'status' => 'required|in:pending,in_progress,resolved',
    ]);

    $currentStatus = $ticket->status;
    $newStatus = $validated['status'];

    $allowedTransitions = [
        'pending' => ['in_progress'],
        'in_progress' => ['resolved'],
        'resolved' => ['in_progress'],
    ];

    if ($newStatus === 'resolved') {
        $ticket->notify(new CustomerTicketResolvedNotification($ticket));
    }

    if (!in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
        return back()->with('error', 'Invalid status transition!');
    }

    $ticket->update($validated);

    return back()->with('success', 'Ticket updated successfully.');
}


    protected function getStatusCountsByDateRange($startDate, $endDate)
    {
        return Ticket::whereBetween('created_at', [$startDate, $endDate])
            ->select([
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress"),
                DB::raw("SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved"),
                DB::raw("SUM(CASE WHEN assigned_to IS NULL THEN 1 ELSE 0 END) as unassigned")
            ])
            ->first();
    }

    public function reopen(Ticket $ticket)
    {
        if ($ticket->status !== 'resolved') {
            return back()->with('error', 'Only resolved tickets can be reopened.');
        }
        $ticket->status = 'in_progress';
        $ticket->save();
        return back()->with('message', 'Ticket reopened successfully.');
    }

    protected function applyFilters($query, $request)
    {
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', "%{$request->search}%")
                  ->orWhere('company_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
    }
}
