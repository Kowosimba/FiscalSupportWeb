<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    use AuthorizesRequests;
    
    public function store(Request $request)
    {
        // Validate the form data first
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'service' => 'required|string|max:255',
            'contact_details' => 'nullable|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,jpg,png',
        ]);

        try {
            // Handle file upload if present
            $attachmentPath = $request->hasFile('attachment') 
                ? $this->storeAttachment($request->file('attachment')) 
                : null;

            // Create and save the ticket
            $ticket = Ticket::create([
                'company_name' => $validatedData['name'],
                'contact_details' => $validatedData['contact_details'],
                'email' => $validatedData['email'],
                'subject' => $validatedData['service'],
                'message' => $validatedData['message'],
                'service' => $validatedData['service'],
                'attachment' => $attachmentPath,
                'status' => 'pending',
                'priority' => $request->input('priority', 'low'), // Default to low if not provided
            ]);

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
     * Store the uploaded attachment
     */
    protected function storeAttachment($file): string
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('attachments', $filename, 'public');
    }

    // In SupportTicketController (add this method)
public function index(Request $request)
{
    // Get ticket status counts
    $statusCounts = Ticket::selectRaw('
        COUNT(*) as total,
        SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = "in_progress" THEN 1 ELSE 0 END) as in_progress,
        SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END) as resolved,
        SUM(CASE WHEN assigned_to IS NULL THEN 1 ELSE 0 END) as unassigned
    ')->first();

    // Build query for tickets with optional filters
    $query = Ticket::query()->with('assignedTechnician');

    // Optional filtering by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Optional filtering by priority
    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    // Optional filtering by assigned technician (user ID)
    if ($request->filled('assigned_to')) {
    $query->where('assigned_to', $request->assigned_to);
}


    // Optional search by subject, company_name, or email
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('subject', 'like', "%{$searchTerm}%")
              ->orWhere('company_name', 'like', "%{$searchTerm}%")
              ->orWhere('email', 'like', "%{$searchTerm}%");
        });
    }
    $technicians = User::whereIn('id', Ticket::pluck('assigned_to')->unique()->filter())->get();


    // Get latest 10 tickets without pagination (to match original behavior)
    $tickets = $query->latest()->take(10)->get();

    // Pass data to the admin dashboard view
    return view('admin.index', compact('statusCounts', 'tickets'));
}

public function allTickets(Request $request)
{
    $query = Ticket::query()->with('assignedTechnician');

    // Filtering
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }
    // Change this to filter by assigned_to (ID)
    if ($request->filled('assigned_to')) {
        $query->where('assigned_to', $request->assigned_to);
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

    // For filter dropdowns
    $technicians = User::whereIn('id', Ticket::pluck('assigned_to')->unique()->filter())->get();
    $statuses = ['in_progress', 'pending', 'resolved'];
    $priorities = ['low', 'medium', 'high'];

    return view('admin.all-tickets', compact('tickets', 'technicians', 'statuses', 'priorities'));
}

public function show(Ticket $ticket)
{
    $ticket->load(['comments.user', 'assigned_to_user']);
    return view('admin.ticketshow', compact('ticket'));
}



public function openTickets(Request $request)
{
    $query = Ticket::query()->where('status', 'in_progress')->with('assignedTechnician');

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }
    if ($request->filled('technician')) {
        $query->whereHas('assignedTechnician', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->technician . '%');
        });
    }
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('subject', 'like', "%$search%")
              ->orWhere('company_name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    $tickets = $query->latest()->paginate(15)->withQueryString();

    $technicians = \App\Models\User::whereIn('id', Ticket::pluck('assigned_to')->unique())->get();
    $priorities = ['low', 'medium', 'high'];

    return view('admin.open-tickets', compact('tickets', 'technicians', 'priorities'));
}
public function solvedTickets(Request $request)
{
    $query = Ticket::query()->where('status', 'resolved')->with('assignedTechnician');

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    if ($request->filled('technician')) {
        $query->whereHas('assignedTechnician', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->technician . '%');
        });
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('subject', 'like', "%$search%")
              ->orWhere('company_name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    $tickets = $query->latest()->paginate(15)->withQueryString();

    // Fetch technicians assigned to tickets for filter dropdown
    $technicianIds = Ticket::pluck('assigned_to')->unique()->filter();
    $technicians = User::whereIn('id', $technicianIds)->get();

    $priorities = ['low', 'medium', 'high'];

    return view('admin.solved-tickets', compact('tickets', 'technicians', 'priorities'));
}

public function pendingTickets(Request $request)
{
    $query = Ticket::query()->where('status', 'pending')->with('assignedTechnician');

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    if ($request->filled('technician')) {
        $query->whereHas('assignedTechnician', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->technician . '%');
        });
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('subject', 'like', "%$search%")
              ->orWhere('company_name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    $tickets = $query->latest()->paginate(15)->withQueryString();

    // Fetch technicians assigned to tickets for filter dropdown
    $technicianIds = Ticket::pluck('assigned_to')->unique()->filter();
    $technicians = User::whereIn('id', $technicianIds)->get();

    $priorities = ['low', 'medium', 'high'];

    return view('admin.pending-tickets', compact('tickets', 'technicians', 'priorities'));
}
public function unassignedTickets(Request $request)
{
    $query = Ticket::whereNull('assigned_to')
                ->with('assignedTechnician');

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

    // Fetch technicians for assign dropdown
    $technicians = User::where('role', 'technician')->get();

    $statuses = ['pending', 'in_progress', 'resolved', 'closed'];
    $priorities = ['low', 'medium', 'high'];

    return view('admin.unassigned-tickets', compact(
        'tickets', 
        'technicians', 
        'statuses', 
        'priorities'
    ));
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
        $path = $request->file('attachment')->store('attachments', 'public');
        $validated['attachment'] = $path;
    }

    $validated['status'] = 'in_progress'; // Default status

    $ticket = \App\Models\Ticket::create($validated);

    // If AJAX, return JSON
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    }

    // Otherwise, redirect (for non-AJAX fallback)
    return redirect()->route('admin.tickets.unassigned')
        ->with('message', 'Ticket created successfully!');
}

public function create()
{
    $technicians = User::where('role', 'technician')->get();
    return view('admin.adminticket', compact('technicians'));
}
public function assignTechnician(Request $request, Ticket $ticket)
{
    // Authorization: only allow if user is admin or manager
    $this->authorize('assign tickets');

    $request->validate([
        'assigned_to' => 'required|exists:users,id',
    ]);

    $ticket->assigned_to = $request->assigned_to;
    $ticket->save();

    return redirect()->route('admin.tickets.unassigned')->with('success', 'Ticket assigned successfully!');
}

public function myTickets()
{
    $userId = optional(Auth::user())->id;

    if (!$userId) {
        return redirect()->route('login')->with('error', 'You must be logged in to view your tickets.');
    }

    $tickets = Ticket::where('assigned_to', $userId)
        ->latest()
        ->paginate(10);

    return view('admin.my-tickets', compact('tickets'));
}

public function updatePriority(Request $request, $id)
{
    $ticket = Ticket::findOrFail($id);
    // Optionally, check if the user is assigned to this ticket
    $ticket->priority = $request->input('priority');
    $ticket->save();
    return back()->with('message', 'Priority updated.');
}

public function updateStatus(Request $request, $id)
{
    $ticket = Ticket::findOrFail($id);
    // Optionally, check if the user is assigned to this ticket
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
        'user_id' => \Illuminate\Support\Facades\Auth::id(),
        'body' => $request->body,
    ]);

    return back()->with('message', 'Comment added!');
}

public function updateStatusPriority(Request $request, Ticket $ticket)
    {
        // Only allow the assigned technician to update
        if (optional(Auth::user())->id !== $ticket->assigned_to) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        $ticket->update($validated);

        return back()->with('success', 'Ticket updated successfully.');
    }



}

