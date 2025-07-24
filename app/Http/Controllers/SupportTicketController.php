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

        if (!in_array(auth::user()->role, ['admin', 'manager'])) {
        return redirect()->back()
            ->with('toastr', [
                'type' => 'error',
                'message' => 'You do not have permission to create a new ticket'
            ]);
    }

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
        // Calculate date ranges for percentage changes
        $currentWeekStart = Carbon::now()->startOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        // ==================== TICKETS DATA ====================
        
        try {
            // Check if tickets table exists and has required columns
            if (Schema::hasTable('tickets') || Schema::hasTable('support_tickets')) {
                $ticketTable = Schema::hasTable('tickets') ? 'tickets' : 'support_tickets';
                
                // Get ticket status counts for current week
                $currentTicketCounts = collect();
                $lastWeekTicketCounts = collect();
                
                if (Schema::hasColumn($ticketTable, 'status')) {
                    $currentTicketCounts = Ticket::select('status', DB::raw('count(*) as count'))
                        ->where('created_at', '>=', $currentWeekStart)
                        ->groupBy('status')
                        ->pluck('count', 'status');

                    $lastWeekTicketCounts = Ticket::select('status', DB::raw('count(*) as count'))
                        ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
                        ->groupBy('status')
                        ->pluck('count', 'status');
                }

                // Calculate status counts with defaults
                $statusCounts = (object) [
                    'in_progress' => Schema::hasColumn($ticketTable, 'status') ? 
                        Ticket::where('status', 'in_progress')->count() : 0,
                    'resolved' => Schema::hasColumn($ticketTable, 'status') ? 
                        Ticket::where('status', 'resolved')->count() : 0,
                    'pending' => Schema::hasColumn($ticketTable, 'status') ? 
                        Ticket::where('status', 'pending')->count() : 0,
                    'unassigned' => Schema::hasColumn($ticketTable, 'assigned_to') ? 
                        Ticket::whereNull('assigned_to')->count() : 0,
                ];

                // Calculate percentage changes
                $percentageChanges = [];
                foreach (['in_progress', 'resolved', 'pending', 'unassigned'] as $status) {
                    $current = $currentTicketCounts->get($status, 0);
                    $previous = $lastWeekTicketCounts->get($status, 0);
                    
                    if ($previous > 0) {
                        $percentageChanges[$status] = round((($current - $previous) / $previous) * 100, 1);
                    } else {
                        $percentageChanges[$status] = $current > 0 ? 100 : 0;
                    }
                }

                // Get recent tickets with relationships
                $ticketsQuery = Ticket::query();
                if (Schema::hasColumn($ticketTable, 'assigned_to')) {
                    $ticketsQuery->with(['assignedTo']);
                }
                $tickets = $ticketsQuery->orderBy('updated_at', 'desc')->paginate(10);
                
            } else {
                // Default values if table doesn't exist
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
                $tickets = new \Illuminate\Pagination\LengthAwarePaginator(
                    collect(), 0, 10, 1, ['path' => request()->url()]
                );
            }
        } catch (\Exception $e) {
            // Fallback values
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
            $tickets = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), 0, 10, 1, ['path' => request()->url()]
            );
        }

        // ==================== JOBS/CALL LOGS DATA ====================
        
        try {
            if (Schema::hasTable('call_logs')) {
                // Get job statistics
                $stats = [
                    'total_jobs' => CallLog::count(),
                    'pending_jobs' => Schema::hasColumn('call_logs', 'status') ? 
                        CallLog::where('status', 'pending')->count() : 0,
                    'in_progress_jobs' => Schema::hasColumn('call_logs', 'status') ? 
                        CallLog::where('status', 'in_progress')->count() : 0,
                    'completed_jobs' => Schema::hasColumn('call_logs', 'status') ? 
                        CallLog::where('status', 'complete')->count() : 0,
                    'assigned_jobs' => Schema::hasColumn('call_logs', 'status') ? 
                        CallLog::where('status', 'assigned')->count() : 0,
                    'cancelled_jobs' => Schema::hasColumn('call_logs', 'status') ? 
                        CallLog::where('status', 'cancelled')->count() : 0,
                ];

                // Get recent call logs with relationships
                $callLogsQuery = CallLog::query();
                if (Schema::hasColumn('call_logs', 'assigned_to')) {
                    $callLogsQuery->with(['assignedTo']);
                }
                $callLogs = $callLogsQuery->orderBy('created_at', 'desc')->limit(10)->get();
            } else {
                $stats = [
                    'total_jobs' => 0,
                    'pending_jobs' => 0,
                    'in_progress_jobs' => 0,
                    'completed_jobs' => 0,
                    'assigned_jobs' => 0,
                    'cancelled_jobs' => 0,
                ];
                $callLogs = collect();
            }
        } catch (\Exception $e) {
            $stats = [
                'total_jobs' => 0,
                'pending_jobs' => 0,
                'in_progress_jobs' => 0,
                'completed_jobs' => 0,
                'assigned_jobs' => 0,
                'cancelled_jobs' => 0,
            ];
            $callLogs = collect();
        }

        // ==================== CONTENT MANAGEMENT DATA ====================
        
        try {
            if (Schema::hasTable('blogs')) {
                // Blog statistics - check for different possible column names
                $blogCount = Blog::count();
                
                // Check for status column variations
                if (Schema::hasColumn('blogs', 'status')) {
                    $publishedBlogCount = Blog::where('status', 'published')->count();
                } elseif (Schema::hasColumn('blogs', 'is_published')) {
                    $publishedBlogCount = Blog::where('is_published', true)->count();
                } elseif (Schema::hasColumn('blogs', 'published_at')) {
                    $publishedBlogCount = Blog::whereNotNull('published_at')
                        ->where('published_at', '<=', now())
                        ->count();
                } else {
                    $publishedBlogCount = $blogCount; // Assume all are published if no status field
                }

                // Get recent blogs with author relationship
                $blogsQuery = Blog::query();
                if (Schema::hasColumn('blogs', 'author_id')) {
                    $blogsQuery->with('author');
                }
                $recentBlogs = $blogsQuery->orderBy('created_at', 'desc')->limit(10)->get();
            } else {
                $blogCount = 0;
                $publishedBlogCount = 0;
                $recentBlogs = collect();
            }
        } catch (\Exception $e) {
            $blogCount = 0;
            $publishedBlogCount = 0;
            $recentBlogs = collect();
        }

        try {
            if (Schema::hasTable('faqs')) {
                // FAQ statistics  
                $totalFaqCount = Faq::count();
                $activeFaqCount = Schema::hasColumn('faqs', 'is_active') ? 
                    Faq::where('is_active', true)->count() : $totalFaqCount;
            } else {
                $activeFaqCount = 0;
                $totalFaqCount = 0;
            }
        } catch (\Exception $e) {
            $activeFaqCount = 0;
            $totalFaqCount = 0;
        }

        try {
            if (Schema::hasTable('services')) {
                // Service statistics
                $serviceCount = Service::count();
                $activeServiceCount = Schema::hasColumn('services', 'is_active') ? 
                    Service::where('is_active', true)->count() : $serviceCount;
            } else {
                $serviceCount = 0;
                $activeServiceCount = 0;
            }
        } catch (\Exception $e) {
            $serviceCount = 0;
            $activeServiceCount = 0;
        }

        try {
            if (Schema::hasTable('newsletter_subscribers')) {
                // Newsletter subscriber statistics
                $totalSubscriberCount = NewsletterSubscriber::count();
                $subscriberCount = Schema::hasColumn('newsletter_subscribers', 'is_active') ? 
                    NewsletterSubscriber::where('is_active', true)->count() : $totalSubscriberCount;
            } else {
                $subscriberCount = 0;
                $totalSubscriberCount = 0;
            }
        } catch (\Exception $e) {
            $subscriberCount = 0;
            $totalSubscriberCount = 0;
        }

        try {
            if (Schema::hasTable('customer_contacts')) {
                // Customer contact statistics
                $contactCount = CustomerContact::count();
                $recentContactCount = CustomerContact::where('created_at', '>=', Carbon::now()->subDays(7))->count();
            } else {
                $contactCount = 0;
                $recentContactCount = 0;
            }
        } catch (\Exception $e) {
            $contactCount = 0;
            $recentContactCount = 0;
        }

        try {
            if (Schema::hasTable('users')) {
                // User statistics
                $userCount = User::count();
                $activeUserCount = Schema::hasColumn('users', 'is_active') ? 
                    User::where('is_active', true)->count() : $userCount;
            } else {
                $userCount = 0;
                $activeUserCount = 0;
            }
        } catch (\Exception $e) {
            $userCount = 0;
            $activeUserCount = 0;
        }

        // ==================== ADDITIONAL DASHBOARD DATA ====================
        
        // Recent activity summary
        $recentActivity = [
            'new_tickets_today' => 0,
            'completed_jobs_today' => 0,
            'new_blogs_this_week' => 0,
            'new_subscribers_this_week' => 0,
        ];

        try {
            if (Schema::hasTable('tickets') || Schema::hasTable('support_tickets')) {
                $recentActivity['new_tickets_today'] = Ticket::whereDate('created_at', today())->count();
            }
            if (Schema::hasTable('call_logs') && Schema::hasColumn('call_logs', 'status')) {
                $recentActivity['completed_jobs_today'] = CallLog::where('status', 'complete')
                    ->whereDate('updated_at', today())->count();
            }
            if (Schema::hasTable('blogs')) {
                $recentActivity['new_blogs_this_week'] = Blog::where('created_at', '>=', $currentWeekStart)->count();
            }
            if (Schema::hasTable('newsletter_subscribers')) {
                $recentActivity['new_subscribers_this_week'] = NewsletterSubscriber::where('created_at', '>=', $currentWeekStart)->count();
            }
        } catch (\Exception $e) {
            // Keep default values
        }

        // Priority ticket counts
        $priorityCounts = [
            'high' => 0,
            'medium' => 0,
            'low' => 0,
        ];

        try {
            $ticketTable = Schema::hasTable('tickets') ? 'tickets' : 'support_tickets';
            if (Schema::hasTable($ticketTable) && Schema::hasColumn($ticketTable, 'priority')) {
                $priorityCounts = [
                    'high' => Ticket::where('priority', 'high')->count(),
                    'medium' => Ticket::where('priority', 'medium')->count(),
                    'low' => Ticket::where('priority', 'low')->count(),
                ];
            }
        } catch (\Exception $e) {
            // Keep default values
        }

        return view('admin.index', compact(
            // Ticket data
            'statusCounts',
            'percentageChanges', 
            'tickets',
            'priorityCounts',
            
            // Job/Call log data
            'stats',
            'callLogs',
            
            // Content management data
            'blogCount',
            'publishedBlogCount',
            'recentBlogs',
            'activeFaqCount',
            'totalFaqCount',
            'serviceCount',
            'activeServiceCount',
            'subscriberCount',
            'totalSubscriberCount',
            
            // Additional data
            'contactCount',
            'recentContactCount',
            'userCount',
            'activeUserCount',
            'recentActivity'
        ));
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
