<?php
// app/Http/Controllers/JobController.php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobAssignedNotification;
use App\Mail\JobStatusUpdateNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class JobController extends Controller
{
    use AuthorizesRequests;

    /**
     * The middleware to be applied to this controller.
     *
     * @var array
     */
    protected $middleware = ['auth'];

    public function index(Request $request)
    {
        $query = Job::with(['approvedBy', 'assignedTo']);

        // Apply filters based on user role
        $user = Auth::user();

        if ($user->role === 'technician') {
            $query->where('assigned_to', $user->id);
        }

        // Apply search and filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('job_card', 'like', "%{$search}%")
                    ->orWhere('fault_description', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = $this->getJobStats($user);

        // Get technicians for filter dropdown
        $technicians = User::where('role', 'technician')
            ->orWhere('role', 'manager')
            ->orderBy('name')
            ->get();

        return view('admin.calllogs.index', compact('jobs', 'stats', 'technicians'));
    }

    public function create()
    {
        $this->authorize('create', Job::class);

        return view('admin.calllogs.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Job::class);

        $validated = $request->validate([
            'fault_description' => 'required|string|max:2000',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'amount_charged' => 'required|numeric|min:0',
            'type' => 'required|in:maintenance,repair,installation,consultation,emergency',
            'priority' => 'required|in:low,medium,high,urgent',
            'zimra_ref' => 'nullable|string|max:100'
        ]);

        $validated['approved_by'] = Auth::id();
        $validated['status'] = 'pending';

        $job = Job::create($validated);

        return redirect()->route('jobs.index')
            ->with('success', 'Job created successfully! Job Card: ' . $job->job_card);
    }

    public function show(Job $job)
    {
        $this->authorize('view', $job);

        $job->load(['approvedBy', 'assignedTo']);

        return view('admin.calllogs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $this->authorize('update', $job);

        $technicians = User::where('role', 'technician')
            ->orWhere('role', 'manager')
            ->orderBy('name')
            ->get();

        return view('admin.calllogs.edit', compact('job', 'technicians'));
    }

    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);

        $user = Auth::user();
        $rules = [];

        // Different validation rules based on user role
        if (in_array($user->role, ['manager', 'accountant', 'admin'])) {
            $rules = [
                'fault_description' => 'required|string|max:2000',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_address' => 'nullable|string|max:500',
                'amount_charged' => 'required|numeric|min:0',
                'type' => 'required|in:maintenance,repair,installation,consultation,emergency',
                'priority' => 'required|in:low,medium,high,urgent',
                'zimra_ref' => 'nullable|string|max:100',
                'assigned_to' => 'nullable|exists:users,id'
            ];
        } elseif ($user->role === 'technician') {
            $rules = [
                'engineer_comments' => 'nullable|string|max:2000',
                'billed_hours' => 'nullable|numeric|min:0',
                'status' => 'required|in:assigned,in_progress,completed'
            ];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($job, $validated, $user) {
            $oldStatus = $job->status;
            $oldAssignedTo = $job->assigned_to;

            // Handle status changes
            if (isset($validated['status']) && $validated['status'] !== $oldStatus) {
                $this->handleStatusChange($job, $validated['status']);
            }

            // Handle assignment changes
            if (isset($validated['assigned_to']) && $validated['assigned_to'] !== $oldAssignedTo) {
                $this->handleAssignment($job, $validated['assigned_to']);
            }

            $job->update($validated);

            // Send notifications
            if (isset($validated['assigned_to']) && $validated['assigned_to'] !== $oldAssignedTo) {
                $this->sendAssignmentNotifications($job);
            }

            if (isset($validated['status']) && $validated['status'] !== $oldStatus) {
                $this->sendStatusUpdateNotifications($job, $oldStatus);
            }
        });

        return redirect()->route('jobs.index')
            ->with('success', 'Job updated successfully!');
    }

    public function assign(Request $request, Job $job)
    {
        $this->authorize('assign', $job);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        DB::transaction(function () use ($job, $validated) {
            $job->update([
                'assigned_to' => $validated['assigned_to'],
                'status' => 'assigned',
                'assigned_at' => now()
            ]);

            $this->sendAssignmentNotifications($job);
        });

        return redirect()->back()
            ->with('success', 'Job assigned successfully!');
    }

    public function updateStatus(Request $request, Job $job)
    {
        $this->authorize('updateStatus', $job);

        $validated = $request->validate([
            'status' => 'required|in:in_progress,completed'
        ]);

        $oldStatus = $job->status;

        DB::transaction(function () use ($job, $validated) {
            $this->handleStatusChange($job, $validated['status']);
            $job->update(['status' => $validated['status']]);
        });

        $this->sendStatusUpdateNotifications($job, $oldStatus);

        return redirect()->back()
            ->with('success', 'Job status updated successfully!');
    }

    private function handleStatusChange(Job $job, string $newStatus)
    {
        switch ($newStatus) {
            case 'in_progress':
                if (!$job->started_at) {
                    $job->started_at = now();
                    $job->time_start = now();
                }
                break;

            case 'completed':
                if (!$job->completed_at) {
                    $job->completed_at = now();
                    $job->time_finish = now();
                    $job->date_resolved = now();

                    // Calculate billed hours if not set
                    if (!$job->billed_hours && $job->time_start) {
                        $start = \Carbon\Carbon::parse($job->time_start);
                        $minutes = $start->diffInMinutes(now());
                        $job->billed_hours = round($minutes / 60, 2);
                    }
                }
                break;
        }
    }

    private function handleAssignment(Job $job, $technicianId)
    {
        $job->assigned_to = $technicianId;
        $job->assigned_at = now();

        if ($job->status === 'pending') {
            $job->status = 'assigned';
        }
    }

    private function sendAssignmentNotifications(Job $job)
    {
        if ($job->assignedTo) {
            // Notify technician
            Mail::to($job->assignedTo->email)
                ->send(new JobAssignedNotification($job, 'technician'));

            // Notify customer
            Mail::to($job->customer_email)
                ->send(new JobAssignedNotification($job, 'customer'));
        }
    }

    private function sendStatusUpdateNotifications(Job $job, string $oldStatus)
    {
        // Notify customer of status changes
        Mail::to($job->customer_email)
            ->send(new JobStatusUpdateNotification($job, $oldStatus));

        // Notify approver if job is completed
        if ($job->status === 'completed') {
            Mail::to($job->approvedBy->email)
                ->send(new JobStatusUpdateNotification($job, $oldStatus));
        }
    }

    private function getJobStats($user)
    {
        // Clone the base query for each count to avoid interference from previous `where` clauses.
        $baseQuery = Job::query();
        if ($user->role === 'technician') {
            $baseQuery->where('assigned_to', $user->id);
        }

        return [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'assigned' => (clone $baseQuery)->where('status', 'assigned')->count()
        ];
    }

    // New methods added below

    public function myCalls(Request $request)
    {
        $user = Auth::user();

        $query = Job::with(['approvedBy', 'assignedTo'])
            ->where('assigned_to', $user->id);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(15);
        $stats = $this->getJobStats($user);

        return view('admin.calllogs.my-calls', compact('jobs', 'stats'));
    }

    public function inProgress(Request $request)
    {
        $user = Auth::user();

        $query = Job::with(['approvedBy', 'assignedTo'])
            ->where('status', 'in_progress');

        if ($user->role === 'technician') {
            $query->where('assigned_to', $user->id);
        }

        $jobs = $query->orderBy('updated_at', 'desc')->paginate(15);
        $stats = $this->getJobStats($user);

        return view('admin.calllogs.in-progress', compact('jobs', 'stats'));
    }

    public function resolved(Request $request)
    {
        $user = Auth::user();

        $query = Job::with(['approvedBy', 'assignedTo'])
            ->where('status', 'completed');

        if ($user->role === 'technician') {
            $query->where('assigned_to', $user->id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('completed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('completed_at', '<=', $request->date_to);
        }

        $jobs = $query->orderBy('completed_at', 'desc')->paginate(15);
        $stats = $this->getJobStats($user);

        return view('admin.calllogs.resolved', compact('jobs', 'stats'));
    }

    public function pending(Request $request)
    {
        $user = Auth::user();

        $query = Job::with(['approvedBy', 'assignedTo'])
            ->where('status', 'pending');

        if ($user->role === 'technician') {
            $query->where('assigned_to', $user->id);
        }

        $jobs = $query->orderBy('created_at', 'asc')->paginate(15);
        $stats = $this->getJobStats($user);
        $technicians = User::where('role', 'technician')
            ->orWhere('role', 'manager')
            ->orderBy('name')
            ->get();

        return view('admin.calllogs.pending', compact('jobs', 'stats', 'technicians'));
    }

    public function unassigned(Request $request)
    {
        $this->authorize('viewAny', Job::class);

        $query = Job::with(['approvedBy'])
            ->whereNull('assigned_to')
            ->where('status', 'pending');

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $jobs = $query->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        $stats = $this->getJobStats(Auth::user());
        $technicians = User::where('role', 'technician')
            ->orWhere('role', 'manager')
            ->orderBy('name')
            ->get();

        return view('admin.calllogs.unassigned', compact('jobs', 'stats', 'technicians'));
    }
}