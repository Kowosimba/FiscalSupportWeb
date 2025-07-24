<?php

namespace App\Http\Controllers;

use App\Notifications\JobAssigned;
use App\Notifications\JobStatusUpdated;
use App\Notifications\JobCompleted;
use Illuminate\Routing\Controller;
use App\Models\CallLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobAssignedToTechnician;
use App\Mail\JobAssignedNotificationToCustomer;
use App\Mail\JobCompletionNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CallLogController extends Controller
{
    // Enhanced constants with descriptions
    private const JOB_STATUSES = [
        'pending' => 'Pending',
        'assigned' => 'Assigned',
        'in_progress' => 'In Progress', 
        'completed' => 'Completed',
        'cancelled' => 'Cancelled'
    ];

    private const JOB_TYPES = [
        'normal' => 'Normal',
        'emergency' => 'Emergency',
        'maintenance' => 'Maintenance',
        'repair' => 'Repair',
        'installation' => 'Installation',
        'consultation' => 'Consultation'
    ];

    private const CACHE_TTL = 300; // 5 minutes cache
    private const DEFAULT_PAGINATION = 25;
    private const MAX_ASSIGNMENT_NOTES = 500;
    private const MAX_ENGINEER_COMMENTS = 200;

    /**
     * Display paginated call logs with advanced filtering and caching
     */
    public function index(Request $request): View
    {
        $cacheKey = 'call_logs_' . md5(serialize($request->all()));
        
        $data = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($request) {
            $query = CallLog::with(['assignedTo:id,name,email', 'approver:id,name'])
                ->select(['id', 'customer_name', 'company_name', 'job_card', 'fault_description', 
                         'status', 'type', 'amount_charged', 'date_booked', 'assigned_to', 'approved_by', 'created_at']);

            $this->applyFiltersToQuery($query, $request);
            
            // Enhanced sorting with validation
            $sortField = $this->validateSortField($request->get('sort', 'date_booked'));
            $sortDirection = in_array($request->get('direction'), ['asc', 'desc']) 
                ? $request->get('direction') 
                : 'desc';
            
            $query->orderBy($sortField, $sortDirection);

            $perPage = $this->validatePerPage($request->get('per_page', self::DEFAULT_PAGINATION));
            
            return [
                'callLogs' => $query->paginate($perPage)->withQueryString(),
                'technicians' => $this->getCachedTechnicians(),
                'stats' => $this->calculateStats($query)
            ];
        });

        return view('admin.calllogs.index', array_merge($data, [
            'statuses' => self::JOB_STATUSES,
            'types' => self::JOB_TYPES
        ]));
    }

    /**
     * Show create form with cached technicians
     */
    public function create(): View
    {
        return view('admin.calllogs.create', [
            'technicians' => $this->getCachedTechnicians(),
            'types' => self::JOB_TYPES
        ]);
    }

    /**
     * Store new call log with enhanced validation and error handling
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCallLogData($request);
        
        if (!in_array(auth::user()->role, ['admin', 'accounts'])) {
        return redirect()->back()
            ->with('toastr', [
                'type' => 'error',
                'message' => 'You do not have permission to create a new job'
            ]);
    }

        // Set system defaults
        $validated = array_merge($validated, [
            'status' => 'pending',
            'approved_by' => Auth::id(),
            'approved_by_name' => Auth::user()->name,
            'booked_by' => Auth::user()->name,
            'date_booked' => $validated['date_booked'] ?? now()->format('Y-m-d')
        ]);

        DB::beginTransaction();
        
        try {
            $callLog = CallLog::create($validated);
            
            // Clear related caches
            $this->clearCallLogCaches();
            
            DB::commit();
            
            return redirect()
                ->route('admin.call-logs.show', $callLog)
                ->with('toastr_success', 'Job created successfully! Job ID: ' . $callLog->id);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create job', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $validated
            ]);
            
            return back()
                ->withInput()
                ->with('toastr_error', 'Failed to create job. Please try again.');
        }
    }

    /**
     * Display specific call log with relationships
     */
    public function show(CallLog $callLog): View
    {
        $callLog->load(['assignedTo:id,name,email', 'approver:id,name']);
        
        return view('admin.calllogs.show', [
            'callLog' => $callLog,
            'canEdit' => $this->canEditJob($callLog),
            'canAssign' => $this->canAssignJob($callLog)
        ]);
    }

    /**
     * Show edit form with cached data
     */
    public function edit(CallLog $callLog): View
    {
        if (!$this->canEditJob($callLog)) {
            abort(403, 'You do not have permission to edit this job.');
        }

        return view('admin.calllogs.edit', [
            'callLog' => $callLog,
            'technicians' => $this->getCachedTechnicians(),
            'statuses' => self::JOB_STATUSES,
            'types' => self::JOB_TYPES
        ]);
    }

    /**
     * Update call log with comprehensive validation
     */
    public function update(Request $request, CallLog $callLog): RedirectResponse
    {
        if (!$this->canEditJob($callLog)) {
            return redirect()
                ->route('admin.call-logs.show', $callLog)
                ->with('toastr_error', 'Unauthorized to edit this job.');
        }

        $validated = $this->validateCallLogData($request, $callLog->id);
        
        // Handle completion requirements
        if ($request->status === 'completed') {
            $completionValidation = $this->validateJobCompletion($request);
            if ($completionValidation !== true) {
                return back()
                    ->withInput()
                    ->with('toastr_error', $completionValidation);
            }
            
            $validated['date_resolved'] = $validated['date_resolved'] ?? now()->format('Y-m-d');
        }

        DB::beginTransaction();
        
        try {
            $oldStatus = $callLog->status;
            $callLog->update($validated);
            
            // Handle status change notifications
            if ($oldStatus !== $validated['status']) {
                $this->handleStatusChangeNotifications($callLog, $oldStatus, $validated['status']);
            }
            
            $this->clearCallLogCaches();
            
            DB::commit();
            
            return redirect()
                ->route('admin.call-logs.show', $callLog)
                ->with('toastr_success', 'Job updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update job', [
                'error' => $e->getMessage(),
                'job_id' => $callLog->id,
                'user_id' => Auth::id()
            ]);

            return back()
                ->withInput()
                ->with('toastr_error', 'Failed to update job. Please try again.');
        }
    }

    /**
     * Delete call log with soft delete support
     */
    public function destroy(CallLog $callLog): RedirectResponse
    {
        if (!$this->canDeleteJob($callLog)) {
            return back()->with('toastr_error', 'Unauthorized to delete this job.');
        }

        try {
            $jobId = $callLog->id;
            $callLog->delete();
            
            $this->clearCallLogCaches();
            
            return redirect()
                ->route('admin.call-logs.index')
                ->with('toastr_success', "Job #{$jobId} deleted successfully.");
                
        } catch (\Exception $e) {
            Log::error('Failed to delete job', [
                'error' => $e->getMessage(),
                'job_id' => $callLog->id,
                'user_id' => Auth::id()
            ]);
            
            return back()->with('toastr_error', 'Failed to delete job. Please try again.');
        }
    }

    /**
     * Enhanced dashboard with cached statistics
     */
    public function dashboard(): View
    {
        $stats = Cache::remember('dashboard_stats', self::CACHE_TTL, function () {
            return [
                'total_jobs' => CallLog::count(),
                'pending_jobs' => CallLog::where('status', 'pending')->count(),
                'in_progress_jobs' => CallLog::where('status', 'in_progress')->count(),
                'completed_jobs' => CallLog::where('status', 'completed')->count(),
                'emergency_jobs' => CallLog::where('type', 'emergency')->count(),
                'total_revenue' => CallLog::where('status', 'completed')->sum('amount_charged'),
                'monthly_revenue' => CallLog::where('status', 'completed')
                    ->whereMonth('date_resolved', now()->month)
                    ->whereYear('date_resolved', now()->year)
                    ->sum('amount_charged')
            ];
        });

        $recentJobs = Cache::remember('recent_jobs', 60, function () {
            return CallLog::with(['assignedTo:id,name'])
                ->select(['id', 'customer_name', 'company_name', 'status', 'type', 'date_booked', 'assigned_to'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        });

        $technicianStats = Cache::remember('technician_stats', self::CACHE_TTL, function () {
            return User::where('role', 'technician')
                ->withCount([
                    'assignedJobs as pending_jobs' => fn($query) => $query->where('status', 'pending'),
                    'assignedJobs as in_progress_jobs' => fn($query) => $query->where('status', 'in_progress'),
                    'assignedJobs as completed_jobs' => fn($query) => $query->where('status', 'completed')
                ])
                ->get();
        });

        return view('admin.calllogs.dashboard', compact('stats', 'recentJobs', 'technicianStats'));
    }

    /**
     * Optimized job assignment with better error handling
     */
    public function assign(Request $request, CallLog $callLog): JsonResponse
    {
        if (!$this->canAssignJob($callLog)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to assign this job.',
                'toastr_type' => 'error'
            ], 403);
        }

        $validated = $request->validate([
            'engineer' => 'required|exists:users,id',
            'assignment_notes' => 'nullable|string|max:' . self::MAX_ASSIGNMENT_NOTES
        ]);

        DB::beginTransaction();
        
        try {
            $engineer = User::findOrFail($validated['engineer']);
            
            // Check if engineer is available (not overloaded)
            if ($this->isEngineerOverloaded($engineer)) {
                return response()->json([
                    'success' => false,
                    'message' => "Engineer {$engineer->name} has too many active assignments.",
                    'toastr_type' => 'warning'
                ]);
            }
            
            $callLog->update([
                'assigned_to' => $engineer->id,
                'status' => 'assigned',
                'engineer_comments' => $validated['assignment_notes'],
                'assigned_at' => now()
            ]);

            // Send notifications asynchronously
            $this->sendJobAssignmentNotifications($callLog, $engineer);
            
            $this->clearCallLogCaches();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Job #{$callLog->id} assigned to {$engineer->name} successfully!",
                'toastr_type' => 'success',
                'engineer_name' => $engineer->name,
                'job_id' => $callLog->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Job assignment failed', [
                'job_id' => $callLog->id,
                'engineer_id' => $validated['engineer'] ?? null,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Assignment failed. Please try again.',
                'toastr_type' => 'error'
            ], 500);
        }
    }

    /**
     * Update job status via AJAX with enhanced validation
     */
    public function updateStatus(Request $request, CallLog $callLog): JsonResponse
    {
        if (!$this->canUpdateJobStatus($callLog)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this job status.',
                'toastr_type' => 'error'
            ], 403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(self::JOB_STATUSES))],
            'time_start' => 'nullable|date_format:H:i',
            'time_finish' => 'nullable|date_format:H:i',
            'date_resolved' => 'nullable|date',
            'engineer_comments' => 'nullable|string|max:' . self::MAX_ENGINEER_COMMENTS
        ]);

        // Validate status transition
        if (!$this->isValidStatusTransition($callLog->status, $validated['status'])) {
            return response()->json([
                'success' => false,
                'message' => "Invalid status transition from {$callLog->status} to {$validated['status']}.",
                'toastr_type' => 'warning'
            ]);
        }

        DB::beginTransaction();
        
        try {
            $updateData = ['status' => $validated['status']];

            // Handle status-specific updates
            switch ($validated['status']) {
                case 'in_progress':
                    if ($request->filled('time_start')) {
                        $updateData['time_start'] = $validated['time_start'];
                    }
                    break;
                    
                case 'completed':
                    $updateData['date_resolved'] = $validated['date_resolved'] ?? now()->format('Y-m-d');
                    if ($request->filled('time_finish')) {
                        $updateData['time_finish'] = $validated['time_finish'];
                    }
                    break;
            }

            if ($request->filled('engineer_comments')) {
                $updateData['engineer_comments'] = $validated['engineer_comments'];
            }

            $oldStatus = $callLog->status;
            $callLog->update($updateData);
            
            // Handle notifications for status changes
            $this->handleStatusChangeNotifications($callLog, $oldStatus, $validated['status']);
            
            $this->clearCallLogCaches();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'toastr_type' => 'success',
                'new_status' => $validated['status'],
                'status_label' => self::JOB_STATUSES[$validated['status']]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update job status', [
                'error' => $e->getMessage(),
                'job_id' => $callLog->id,
                'new_status' => $validated['status'],
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Status update failed. Please try again.',
                'toastr_type' => 'error'
            ], 500);
        }
    }

    /**
     * Enhanced export with progress tracking
     */
    public function export(Request $request)
    {
        try {
            $query = CallLog::with(['assignedTo:id,name', 'approver:id,name']);
            $this->applyFiltersToQuery($query, $request);

            $callLogs = $query->orderBy('date_booked', 'desc')->get();

            if ($callLogs->isEmpty()) {
                return back()->with('toastr_warning', 'No jobs found matching your criteria.');
            }

            $filename = 'job_cards_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

            return (new FastExcel($callLogs))->download($filename, function ($job) {
                return [
                    'Job ID' => $job->id,
                    'Job Card' => $job->job_card ?: 'TBD-' . $job->id,
                    'Customer Name' => $job->customer_name ?: $job->company_name,
                    'Customer Email' => $job->customer_email ?: 'N/A',
                    'Customer Phone' => $job->customer_phone ?: 'N/A',
                    'Company Name' => $job->company_name ?: 'N/A',
                    'Fault Description' => $job->fault_description,
                    'ZIMRA Reference' => $job->zimra_ref ?: 'N/A',
                    'Date Booked' => optional($job->date_booked)->format('Y-m-d') ?: 'N/A',
                    'Date Resolved' => optional($job->date_resolved)->format('Y-m-d') ?: 'N/A',
                    'Time Start' => $job->time_start ?: 'N/A',
                    'Time Finish' => $job->time_finish ?: 'N/A',
                    'Job Type' => ucfirst($job->type ?: 'normal'),
                    'Status' => ucfirst($job->status ?: 'pending'),
                    'Billed Hours' => $job->billed_hours ?: 0,
                    'Amount Charged' => number_format($job->amount_charged ?: 0, 2),
                    'Assigned Technician' => optional($job->assignedTo)->name ?: 'Unassigned',
                    'Approved By' => optional($job->approver)->name ?: 'N/A',
                    'Engineer Comments' => $job->engineer_comments ?: 'N/A',
                    'Booked By' => $job->booked_by ?: 'N/A',
                    'Created At' => $job->created_at->format('Y-m-d H:i:s'),
                    'Updated At' => $job->updated_at->format('Y-m-d H:i:s')
                ];
            });

        } catch (\Exception $e) {
            Log::error('Export failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return back()->with('toastr_error', 'Export failed. Please try again.');
        }
    }

    /**
     * Send job completion notification to customer
     */
    public function notifyCustomer(Request $request, CallLog $callLog): JsonResponse
    {
        if (!$this->canNotifyCustomer($callLog)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to send notifications for this job.',
                'toastr_type' => 'error'
            ], 403);
        }

        if (!$callLog->customer_email) {
            return response()->json([
                'success' => false,
                'message' => 'No customer email address found for this job.',
                'toastr_type' => 'warning'
            ]);
        }

        if ($callLog->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Job must be completed before sending notification.',
                'toastr_type' => 'warning'
            ]);
        }

        try {
            Mail::to($callLog->customer_email)->send(new JobCompletionNotification($callLog));

            // Log the notification
            Log::info('Job completion notification sent', [
                'job_id' => $callLog->id,
                'customer_email' => $callLog->customer_email,
                'sent_by' => Auth::user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => "Completion notification sent to {$callLog->customer_email} successfully!",
                'toastr_type' => 'success'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send customer notification', [
                'job_id' => $callLog->id,
                'customer_email' => $callLog->customer_email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification. Please try again.',
                'toastr_type' => 'error'
            ], 500);
        }
    }

    // Specialized view methods with optimized queries
    public function unassigned(Request $request): View
    {
        return $this->buildFilteredView($request, [
            'assigned_to' => null
        ], 'admin.calllogs.unassigned', 'Unassigned Jobs');
    }

    public function assigned(Request $request): View
    {
        return $this->buildFilteredView($request, [
            'status' => 'assigned'
        ], 'admin.calllogs.assigned', 'Assigned Jobs');
    }

    public function pending(Request $request): View
    {
        return $this->buildFilteredView($request, [
            'status' => 'pending'
        ], 'admin.calllogs.pending', 'Pending Jobs');
    }

    public function inProgress(Request $request): View
    {
        return $this->buildFilteredView($request, [
            'status' => 'in_progress'
        ], 'admin.calllogs.in-progress', 'In Progress Jobs');
    }

    public function completed(Request $request): View
    {
        return $this->buildFilteredView($request, [
            'status' => 'completed'
        ], 'admin.calllogs.completed', 'Completed Jobs');
    }

    public function cancelled(Request $request): View
    {
        return $this->buildFilteredView($request, [
            'status' => 'cancelled'
        ], 'admin.calllogs.cancelled', 'Cancelled Jobs');
    }

    public function myJobs(Request $request): View
    {
        return $this->buildFilteredView($request, [
            'assigned_to' => Auth::id()
        ], 'admin.calllogs.my-jobs', 'My Jobs');
    }

    public function all(Request $request): View
    {
        return $this->buildFilteredView($request, [], 'admin.calllogs.all', 'All Jobs');
    }

    // Private helper methods

    private function buildFilteredView(Request $request, array $baseFilters, string $view, string $title): View
    {
        $query = CallLog::with(['assignedTo:id,name,email', 'approver:id,name']);
        
        // Apply base filters
        foreach ($baseFilters as $field => $value) {
            if ($value === null) {
                $query->whereNull($field);
            } else {
                $query->where($field, $value);
            }
        }
        
        $this->applyFiltersToQuery($query, $request);
        
        $callLogs = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $stats = $this->calculateFilteredStats($baseFilters);
        
        return view($view, [
            'callLogs' => $callLogs,
            'technicians' => $this->getCachedTechnicians(),
            'stats' => $stats,
            'title' => $title
        ]);
    }

    private function validateCallLogData(Request $request, ?int $excludeId = null): array
    {
        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'fault_description' => 'required|string|max:1000',
            'zimra_ref' => 'nullable|string|max:255',
            'type' => ['required', Rule::in(array_keys(self::JOB_TYPES))],
            'amount_charged' => 'required|numeric|min:0|max:999999.99',
            'date_booked' => 'required|date|after_or_equal:' . now()->subYear()->format('Y-m-d'),
            'assigned_to' => 'nullable|exists:users,id'
        ];

        if ($request->status === 'completed') {
            $rules['job_card'] = 'required|string|max:50|unique:call_logs,job_card' . ($excludeId ? ",{$excludeId}" : '');
            $rules['time_start'] = 'required|date_format:H:i';
            $rules['time_finish'] = 'required|date_format:H:i|after:time_start';
            $rules['billed_hours'] = 'required|string|max:10';
            $rules['date_resolved'] = 'required|date|after_or_equal:date_booked';
            $rules['engineer_comments'] = 'required|string|min:10|max:' . self::MAX_ENGINEER_COMMENTS;
        }

        return $request->validate($rules, [
            'customer_name.required' => 'Customer name is required.',
            'fault_description.required' => 'Please describe the fault or issue.',
            'amount_charged.required' => 'Please specify the amount to be charged.',
            'time_finish.after' => 'Finish time must be after start time.',
            'engineer_comments.min' => 'Please provide detailed comments (minimum 10 characters).',
        ]);
    }

    private function validateJobCompletion(Request $request): bool|string
    {
        if (!$request->filled('job_card')) {
            return 'Job card number is required for completed jobs.';
        }

        if ($request->filled('time_start') && $request->filled('time_finish')) {
            if ($request->time_finish <= $request->time_start) {
                return 'Finish time must be after start time.';
            }
        }

        return true;
    }

    private function validateSortField(string $field): string
    {
        $allowedSorts = [
            'date_booked', 'customer_name', 'status', 'amount_charged', 
            'created_at', 'type', 'assigned_to'
        ];
        
        return in_array($field, $allowedSorts) ? $field : 'date_booked';
    }

    private function validatePerPage(int $perPage): int
    {
        $allowedPerPage = [10, 25, 50, 100];
        return in_array($perPage, $allowedPerPage) ? $perPage : self::DEFAULT_PAGINATION;
    }

    private function getCachedTechnicians()
    {
        return Cache::remember('technicians_list', self::CACHE_TTL, function () {
            return User::whereIn('role', ['technician', 'manager'])
                ->select(['id', 'name', 'email'])
                ->orderBy('name')
                ->get();
        });
    }

    private function calculateStats($query): array
    {
        $baseQuery = clone $query;
        
        return [
            'total' => $baseQuery->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'total_revenue' => (clone $baseQuery)->where('status', 'completed')->sum('amount_charged'),
        ];
    }

    private function calculateFilteredStats(array $baseFilters): array
    {
        $query = CallLog::query();
        
        foreach ($baseFilters as $field => $value) {
            if ($value === null) {
                $query->whereNull($field);
            } else {
                $query->where($field, $value);
            }
        }

        return [
            'total' => $query->count(),
            'emergency' => (clone $query)->where('type', 'emergency')->count(),
            'overdue' => (clone $query)->where('date_booked', '<', now())
                ->whereNotIn('status', ['completed', 'cancelled'])->count(),
        ];
    }

    private function applyFiltersToQuery($query, Request $request): void
    {
        // Search filter with optimized LIKE queries
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('job_card', 'like', "%{$search}%")
                  ->orWhere('fault_description', 'like', "%{$search}%")
                  ->orWhere('zimra_ref', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Technician filter
        if ($request->filled('technician') || $request->filled('engineer')) {
            $technicianId = $request->technician ?: $request->engineer;
            $query->where('assigned_to', $technicianId);
        }
        
        // Date range filter
        if ($request->filled('date_range')) {
            $this->applyDateFilter($query, $request->date_range);
        }
        
        // Custom date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date_booked', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }
        
        // Amount filters
        if ($request->filled('min_amount')) {
            $query->where('amount_charged', '>=', $request->min_amount);
        }
        
        if ($request->filled('max_amount')) {
            $query->where('amount_charged', '<=', $request->max_amount);
        }
    }

    private function applyDateFilter($query, string $dateRange): void
    {
        $now = now();
        
        $ranges = [
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'yesterday' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
            'this_week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'last_week' => [$now->copy()->subWeek()->startOfWeek(), $now->copy()->subWeek()->endOfWeek()],
            'this_month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'last_month' => [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()],
            'this_year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'last_year' => [$now->copy()->subYear()->startOfYear(), $now->copy()->subYear()->endOfYear()]
        ];

        if (isset($ranges[$dateRange])) {
            [$start, $end] = $ranges[$dateRange];
            $query->whereBetween('date_booked', [$start, $end]);
        }
    }

    private function canEditJob(CallLog $callLog): bool
    {
        $user = Auth::user();
        
        return in_array($user->role, ['admin', 'manager']) || 
               $callLog->assigned_to === $user->id ||
               $callLog->approved_by === $user->id;
    }

    private function canAssignJob(CallLog $callLog): bool
    {
        return in_array(Auth::user()->role, ['admin', 'manager']);
    }

    private function canDeleteJob(CallLog $callLog): bool
    {
        return in_array(Auth::user()->role, ['admin', 'manager']);
    }

    private function canUpdateJobStatus(CallLog $callLog): bool
    {
        $user = Auth::user();
        
        return in_array($user->role, ['admin', 'manager']) || 
               $callLog->assigned_to === $user->id;
    }

    private function canNotifyCustomer(CallLog $callLog): bool
    {
        $user = Auth::user();
        
        return in_array($user->role, ['admin', 'manager']) || 
               $callLog->assigned_to === $user->id;
    }

    private function isValidStatusTransition(string $currentStatus, string $newStatus): bool
    {
        $validTransitions = [
            'pending' => ['assigned', 'cancelled'],
            'assigned' => ['in_progress', 'cancelled', 'pending'],
            'in_progress' => ['completed', 'assigned'],
            'completed' => [], // Completed jobs cannot be changed
            'cancelled' => ['pending'] // Cancelled jobs can be reactivated
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }

    private function isEngineerOverloaded(User $engineer): bool
    {
        $activeJobsCount = CallLog::where('assigned_to', $engineer->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->count();

        return $activeJobsCount >= 10; // Configurable threshold
    }

    private function sendJobAssignmentNotifications(CallLog $callLog, User $engineer): void
    {
        try {
            // Send to technician
            if ($engineer->email) {
                Mail::to($engineer->email)->send(new JobAssignedToTechnician($callLog, $engineer));
            }

            // Send to customer if email exists
            if ($callLog->customer_email) {
                Mail::to($callLog->customer_email)->send(new JobAssignedNotificationToCustomer($callLog, $engineer));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send assignment notifications', [
                'job_id' => $callLog->id,
                'engineer_id' => $engineer->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function handleStatusChangeNotifications(CallLog $callLog, string $oldStatus, string $newStatus): void
    {
        // Only send notifications for significant status changes
        $notifiableChanges = [
            'assigned' => 'in_progress',
            'in_progress' => 'completed'
        ];

        if (isset($notifiableChanges[$oldStatus]) && $notifiableChanges[$oldStatus] === $newStatus) {
            try {
                if ($callLog->customer_email) {
                    // Send appropriate notification based on new status
                    if ($newStatus === 'completed') {
                        Mail::to($callLog->customer_email)->send(new JobCompletionNotification($callLog));
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to send status change notification', [
                    'job_id' => $callLog->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    private function clearCallLogCaches(): void
    {
        $keys = [
            'call_logs_*',
            'dashboard_stats',
            'recent_jobs',
            'technician_stats',
            'technicians_list'
        ];

        foreach ($keys as $pattern) {
            if (str_contains($pattern, '*')) {
                // Clear pattern-based cache keys
                Cache::tags(['call_logs'])->flush();
            } else {
                Cache::forget($pattern);
            }
        }
    }
}
