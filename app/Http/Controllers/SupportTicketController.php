<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Comment;
use App\Models\CallLog;
use App\Models\Faq;
use App\Models\Blog;
use App\Models\NewsletterSubscriber;
use App\Models\Service;
use App\Models\CustomerContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Notification;
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

    // No role or auth checks here since it's public-facing

    try {
        $attachmentPath = $request->hasFile('attachment')
            ? $this->storeAttachment($request->file('attachment'))
            : null;

        $ticket = Ticket::create([
            'company_name' => $validatedData['name'],
            'contact_details' => $validatedData['contact_details'] ?? null,
            'email' => $validatedData['email'],
            'subject' => $validatedData['service'],
            'message' => $validatedData['message'],
            'service' => $validatedData['service'],
            'attachment' => $attachmentPath,
            'status' => 'pending',
            'priority' => $request->input('priority', 'low'),
        ]);

        // Notify customer that ticket was created
        Notification::route('mail', $ticket->email)
            ->notify(new CustomerTicketCreatedNotification($ticket));

        // Notify all managers about the new ticket that needs assignment
        $managers = User::where('role', 'manager')->get();
        foreach ($managers as $manager) {
            $manager->notify(new \App\Notifications\NewTicketForAssignmentNotification($ticket));
        }

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

    /**
     * Store attachment for tickets.
     */

    /**
     * Admin-side ticket creation (authenticated users).
     * Only authenticated admins/managers or staff should use this.
     */
   public function adminStore(Request $request)
    {
        $request->validate([
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

        try {
            $data = $request->only([
                'company_name',
                'contact_details',
                'email',
                'service',
                'subject',
                'message',
                'priority',
                'assigned_to',
            ]);

            if ($request->hasFile('attachment')) {
                $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
            }

            $data['status'] = 'in_progress';

            $ticket = Ticket::create($data);

            if (!empty($ticket->assigned_to)) {
                $technician = User::find($ticket->assigned_to);
                if ($technician) {
                    $technician->notify(new TicketAssignedNotification($ticket));
                }
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ticket created successfully!',
                    'ticket' => [
                        'id' => $ticket->id,
                        'subject' => $ticket->subject,
                        'status' => $ticket->status,
                        'priority' => $ticket->priority,
                    ],
                ]);
            }

            return redirect()->route('admin.tickets.unassigned')->with('success', 'Ticket #' . $ticket->id . ' created successfully!');
        } catch (\Exception $e) {
            Log::error('Admin ticket creation failed: ' . $e->getMessage());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating ticket. Please try again.',
                ], 500);
            }

            return redirect()->back()->with('error', 'Error creating ticket. Please try again.')->withInput();
        }
    }

    protected function storeAttachment($file): string
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('attachments', $filename, 'public');
    }

    public function index(Request $request)
{
    // Get period from request (fall back to 'week' as default)
    $period = $request->input('period', 'week');

    // Calculate date ranges based on period
    $now = Carbon::now();
    $startDate = match ($period) {
        'today' => $now->copy()->startOfDay(),
        'week'  => $now->copy()->startOfWeek(),
        'month' => $now->copy()->startOfMonth(),
        'quarter' => $now->copy()->firstOfQuarter(),
        default => $now->copy()->startOfWeek(),
    };

    // For percentage changes: always compare with previous period
    $previousStartDate = $startDate->copy()->sub(1, str_replace('this_', '', $period));
    $previousEndDate = $startDate->copy()->subSecond();

    // ==================== TICKETS DATA ====================
    $statusCounts = (object) [
        'in_progress' => 0,
        'resolved' => 0,
        'pending' => 0,
        'unassigned' => 0,
    ];
    $percentageChanges = [
        'in_progress' => 0,
        'resolved' => 0,
        'pending' => 0,
        'unassigned' => 0,
    ];
    $tickets = collect();

    if (Schema::hasTable('tickets') || Schema::hasTable('support_tickets')) {
        $ticketTable = Schema::hasTable('tickets') ? 'tickets' : 'support_tickets';

        // For period filter: filter tickets by $startDate
        $ticketsQuery = Ticket::query();
        if (Schema::hasColumn($ticketTable, 'created_at')) {
            $ticketsQuery->where('created_at', '>=', $startDate);
        }
        if (Schema::hasColumn($ticketTable, 'assigned_to')) {
            $ticketsQuery->with(['assignedTo']);
        }
        // For dashboard, get only 5 most recent
        $tickets = $ticketsQuery->orderBy('updated_at', 'desc')->take(5)->get();

        // Get current period status counts (stats cards)
        if (Schema::hasColumn($ticketTable, 'status')) {
            $currentTicketCounts = Ticket::select('status', DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('status')
                ->pluck('count', 'status');

            $statusCounts = (object) array_merge([
                'in_progress' => $currentTicketCounts->get('in_progress', 0),
                'resolved'    => $currentTicketCounts->get('resolved', 0),
                'pending'     => $currentTicketCounts->get('pending', 0),
                // Unassigned logic
                'unassigned'  => Schema::hasColumn($ticketTable, 'assigned_to') 
                    ? Ticket::whereNull('assigned_to')->where('created_at', '>=', $startDate)->count()
                    : 0,
            ]);
        }

        // Get previous period status counts (for % change calculation)
        if (Schema::hasColumn($ticketTable, 'status') && Schema::hasColumn($ticketTable, 'created_at')) {
            $previousTicketCounts = Ticket::select('status', DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
                ->groupBy('status')
                ->pluck('count', 'status');
        }

        // Calculate percentage changes
        foreach (['in_progress', 'resolved', 'pending', 'unassigned'] as $status) {
            $current = $statusCounts->$status;
            $previous = isset($previousTicketCounts) ? ($previousTicketCounts->get($status, 0)) : 0;
            if ($previous > 0) {
                $percentageChanges[$status] = round((($current - $previous) / $previous) * 100, 1);
            } else {
                $percentageChanges[$status] = $current > 0 ? 100 : 0;
            }
        }
    }

    // ==================== JOBS DATA ====================
    $jobStats = [
        'pending_jobs' => 0,
        'in_progress_jobs' => 0,
        'completed_jobs' => 0,
        'total_jobs' => 0,
    ];
    $callLogs = collect();

    if (Schema::hasTable('call_logs')) {
        // For the dashboard, get only 5 most recent, filtered by period
        $callLogs = CallLog::query()
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Stats for cards (all-time, or filter by period)
        $jobStats = [
            'pending_jobs'   => CallLog::where('status', 'pending')->where('created_at', '>=', $startDate)->count(),
            'in_progress_jobs' => CallLog::where('status', 'in_progress')->where('created_at', '>=', $startDate)->count(),
            'completed_jobs' => CallLog::where('status', 'completed')->where('created_at', '>=', $startDate)->count(),
            'total_jobs'     => CallLog::where('created_at', '>=', $startDate)->count(),
        ];
    }

    // ==================== RETURN TO VIEW ====================
    return view('admin.index', [
        'period' => $period,
        'startDate' => $startDate, // Optional: display in view
        'statusCounts' => $statusCounts,
        'percentageChanges' => $percentageChanges,
        'tickets' => $tickets,
        'callLogs' => $callLogs,
        'stats' => array_merge(
            ['total_jobs' => $jobStats['total_jobs'] ?? 0],
            $jobStats
        ),
    ]);
}


    /**
     * Calculate average ticket resolution time in hours
     */
    private function calculateAverageResolutionTime()
    {
        $resolvedTickets = Ticket::where('status', 'resolved')
            ->whereNotNull('resolved_at')
            ->get();

        if ($resolvedTickets->isEmpty()) {
            return 0;
        }

        $totalHours = 0;
        foreach ($resolvedTickets as $ticket) {
            $createdAt = Carbon::parse($ticket->created_at);
            $resolvedAt = Carbon::parse($ticket->resolved_at);
            $totalHours += $createdAt->diffInHours($resolvedAt);
        }

        return round($totalHours / $resolvedTickets->count(), 1);
    }

    /**
     * Calculate customer satisfaction rate (placeholder - implement based on your rating system)
     */
    private function calculateCustomerSatisfactionRate()
    {
        // This is a placeholder. Implement based on your actual rating/feedback system
        $totalRatings = Ticket::where('status', 'resolved')
            ->whereNotNull('customer_rating')
            ->count();

        if ($totalRatings === 0) {
            return 0;
        }

        $positiveRatings = Ticket::where('status', 'resolved')
            ->where('customer_rating', '>=', 4) // Assuming 5-star rating system
            ->count();

        return round(($positiveRatings / $totalRatings) * 100, 1);
    }

    /**
     * Calculate technician utilization rate
     */
    private function calculateTechnicianUtilization()
    {
        $totalTechnicians = User::where('role', 'technician')->count();
        
        if ($totalTechnicians === 0) {
            return 0;
        }

        $activeTechnicians = User::where('role', 'technician')
            ->whereHas('assignedTickets', function($query) {
                $query->whereIn('status', ['in_progress', 'pending']);
            })
            ->count();

        return round(($activeTechnicians / $totalTechnicians) * 100, 1);
    }

    // Removed duplicate allTickets method to resolve duplicate symbol declaration error.

    /**
     * Show tickets by status
     * (Removed duplicate openTickets method to resolve redeclaration error)
     */
    // public function openTickets()
    // {
    //     $tickets = SupportTicket::with(['assignedTo', 'customer'])
    //         ->where('status', 'in_progress')
    //         ->orderBy('updated_at', 'desc')
    //         ->paginate(15);

    //     return view('admin.tickets.by-status', compact('tickets'))
    //         ->with('pageTitle', 'In Progress Tickets')
    //         ->with('status', 'in_progress');
    // }

    // Removed duplicate solvedTickets method to resolve redeclaration error.

    // Removed duplicate pendingTickets method to resolve redeclaration error.

    // Removed duplicate unassignedTickets method to resolve duplicate symbol declaration error.

    // Removed duplicate myTickets method to resolve duplicate symbol declaration error.


   public function allTickets(Request $request)
{
    $query = Ticket::with(['assignedTo:id,name']) // Changed from 'assignedTechnician'
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
    // Load the correct relationships that exist in your model
    $ticket->load([
        'assignedTo:id,name' // Use 'assignedTo' instead of 'assignedTechnician'
    ]);

    // If you have comments, uncomment this after adding the relationship
    // $ticket->load([
    //     'comments' => function($query) {
    //         $query->latest()->limit(10)->with(['user:id,name']);
    //     }
    // ]);

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
