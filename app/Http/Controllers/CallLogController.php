<?php

namespace App\Http\Controllers;

use App\Mail\JobAssignedNotificationToCustomer;
use App\Mail\JobAssignedToTechnician;
use App\Mail\JobCompletionNotification;
use App\Models\CallLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;

class CallLogController extends Controller
{
    // Job statuses and descriptions
    private const JOB_STATUSES = [
        'pending' => 'Pending',
        'assigned' => 'Assigned',
        'in_progress' => 'In Progress',
        'complete' => 'Complete',
        'cancelled' => 'Cancelled',
    ];

    // Job types and descriptions
    private const JOB_TYPES = [
        'normal' => 'Normal',
        'emergency' => 'Emergency',
        'maintenance' => 'Maintenance',
        'repair' => 'Repair',
        'installation' => 'Installation',
        'consultation' => 'Consultation',
    ];

    private const DEFAULT_PAGINATION = 25;
    private const MAX_ASSIGNMENT_NOTES = 500;
    private const MAX_ENGINEER_COMMENTS = 200;
    private const ENGINEER_OVERLOAD_THRESHOLD = 10;

    /**
     * Display paginated call logs with advanced filtering.
     */
    public function index(Request $request): View
    {
        $query = CallLog::with(['assignedTo:id,name,email', 'approver:id,name'])
            ->select([
                'id',
                'customer_name',
                'company_name',
                'job_card',
                'fault_description',
                'status',
                'type',
                'amount_charged',
                'currency',
                'date_booked',
                'assigned_to',
                'approved_by',
                'created_at',
            ]);

        $this->applyFiltersToQuery($query, $request);

        $sortField = $this->validateSortField($request->get('sort', 'date_booked'));
        $sortDirection = in_array($request->get('direction'), ['asc', 'desc']) ? $request->get('direction') : 'desc';

        $query->orderBy($sortField, $sortDirection);

        $perPage = $this->validatePerPage((int)$request->get('per_page', self::DEFAULT_PAGINATION));

        $callLogs = $query->paginate($perPage)->withQueryString();
        $technicians = $this->getTechnicians();
        $stats = $this->calculateStats($query);

        return view('admin.CallLogs.Index', [
            'callLogs' => $callLogs,
            'technicians' => $technicians,
            'stats' => $stats,
            'statuses' => self::JOB_STATUSES,
            'types' => self::JOB_TYPES,
        ]);
    }

    /**
     * Show the create call log form.
     */
    public function create(): View
    {
        return view('admin.CallLogs.create', [
            'technicians' => $this->getTechnicians(),
            'types' => self::JOB_TYPES,
        ]);
    }

    /**
     * Store a new call log.
     */
    public function store(Request $request): RedirectResponse
    {
        Log::info('Store method called', ['data' => $request->all()]);

        if (!in_array(Auth::user()->role ?? '', ['admin', 'accounts'])) {
            Log::warning('Unauthorized attempt to create job', ['user_id' => Auth::id()]);
            return redirect()->back()->with('error', 'You do not have permission to create a new job.');
        }

        // Validation
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'zimra_ref' => 'nullable|string|max:255',
            'type' => ['required', Rule::in(array_keys(self::JOB_TYPES))],
            'amount_charged' => 'required|numeric|min:0|max:999999.99',
            'currency' => 'required|in:USD,ZWG',
            'date_booked' => 'required|date|after_or_equal:' . now()->subYear()->format('Y-m-d'),
            'fault_description' => 'required|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'customer_email.required' => 'Customer email is required.',
            'customer_email.email' => 'Please provide a valid email address.',
            'fault_description.required' => 'Please describe the fault or issue.',
            'amount_charged.required' => 'Please specify the amount to be charged.',
            'amount_charged.numeric' => 'Amount must be a valid number.',
            'amount_charged.min' => 'Amount cannot be negative.',
            'currency.required' => 'Please select a currency.',
            'currency.in' => 'Currency must be either USD or ZWG.',
            'type.required' => 'Please select a job type.',
            'assigned_to.exists' => 'Selected engineer does not exist.',
        ]);

        // Set default fields
        $jobData = array_merge($validated, [
            'status' => 'pending',
            'approved_by' => Auth::id(),
            'approved_by_name' => Auth::user()->name,
            'booked_by' => Auth::user()->name,
        ]);

        DB::beginTransaction();

        try {
            $callLog = CallLog::create($jobData);

            if ($callLog->assigned_to) {
                $callLog->load('assignedTo');
                $this->handleStatusChangeNotifications($callLog, 'pending', 'assigned');
            }

            DB::commit();

            Log::info('Job created successfully', [
                'job_id' => $callLog->id,
                'customer' => $callLog->customer_name,
                'type' => $callLog->type,
                'assigned_to' => $callLog->assigned_to,
            ]);

            $redirectUrl = $this->getRedirectUrl($request);
            $assignedEngineerName = $callLog->assignedTo ? ' - Assigned to ' . $callLog->assignedTo->name : '';

            return redirect($redirectUrl)->with('success', 'Job card created successfully! Job ID: #' . $callLog->id . $assignedEngineerName);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to create job', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $jobData,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create job card. Please try again.');
        }
    }

    /**
     * Display a specific call log.
     */
    public function show(CallLog $callLog): View
    {
        $callLog->load(['assignedTo:id,name,email', 'approver:id,name']);

        return view('admin.CallLogs.show', [
            'callLog' => $callLog,
            'canEdit' => $this->canEditJob($callLog),
            'canAssign' => $this->canAssignJob($callLog),
        ]);
    }

    /**
     * Show edit form for existing call log.
     */
    public function edit(CallLog $callLog): View
    {
        if (!$this->canEditJob($callLog)) {
            abort(403, 'You do not have permission to edit this job.');
        }

        return view('admin.CallLogs.edit', [
            'callLog' => $callLog,
            'technicians' => $this->getTechnicians(),
            'statuses' => self::JOB_STATUSES,
            'types' => self::JOB_TYPES,
        ]);
    }

    /**
     * Update existing call log.
     */
public function update(Request $request, CallLog $callLog): RedirectResponse
{
    if (!$this->canEditJob($callLog)) {
        return redirect()->route('admin.call-logs.show', $callLog)->with('error', 'Unauthorized to edit this job.');
    }

    $user = auth::user();
    $isEngineerUpdate = in_array($user->role, ['technician', 'manager']) &&
                        !in_array($user->role, ['admin', 'accounts']) &&
                        $callLog->assigned_to === $user->id;

    // Base rules
    $rules = [];

    if (!$isEngineerUpdate) {
        // Admin/Account full edit fields required
        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'type' => ['required', Rule::in(array_keys(self::JOB_TYPES))],
            'amount' => 'sometimes|numeric|min:0',
            'amount_charged' => 'required|numeric|min:0',
            'currency' => 'required|in:USD,ZWG',
            'date_booked' => 'required|date|after_or_equal:' . now()->subYear()->format('Y-m-d'),
            'assigned_to' => 'nullable|exists:users,id',
            'status' => ['required', Rule::in(array_keys(self::JOB_STATUSES))],
            'fault_description' => 'required|string|max:1000',
        ];
    } else {
        // Engineer limited fields allowed
        $rules = [
            'job_card' => ['nullable', 'string', 'max:50', Rule::unique('call_logs')->ignore($callLog->id)],
            'status' => ['required', Rule::in(array_keys(self::JOB_STATUSES))],
            'date_resolved' => 'nullable|date|after_or_equal:date_booked',
            'engineer_comments' => 'nullable|string|max:200',
            'time_start' => 'nullable|date_format:H:i',
            'time_finish' => 'nullable|date_format:H:i|after:time_start',
            'billed_hours' => 'nullable|string|max:10',
        ];
    }

    // Additional requirements if status is complete
    if ($request->input('status') === 'complete') {
        $rules['job_card'] = ['required', 'string', 'max:50', Rule::unique('call_logs')->ignore($callLog->id)];
        $rules['date_resolved'] = 'required|date|after_or_equal:date_booked';
        $rules['engineer_comments'] = 'required|string|min:10|max:200';

        if ($isEngineerUpdate) {
            $rules['time_start'] = 'required|date_format:H:i';
            $rules['time_finish'] = 'required|date_format:H:i|after:time_start';
            $rules['billed_hours'] = 'required|string|max:10';
        }
    }

    $validated = $request->validate($rules, [
        'customer_name.required' => 'Customer name is required.',
        'customer_email.required' => 'Customer email is required.',
        'fault_description.required' => 'Fault description is required.',
        'amount_charged.required' => 'Amount is required.',
        'amount_charged.numeric' => 'Amount must be numeric.',
        'amount_charged.min' => 'Amount must be at least 0.',
        'currency.required' => 'Currency is required.',
        'currency.in' => 'Currency must be USD or ZWG.',
        'date_booked.required' => 'Date booked is required.',
        'assigned_to.exists' => 'Selected engineer does not exist.',
        'status.required' => 'Status is required.',
        'job_card.required' => 'Job card is required when completing.',
        'job_card.unique' => 'Job card must be unique.',
        'date_resolved.required' => 'Date resolved required when completing.',
        'date_resolved.after_or_equal' => 'Date resolved cannot be before date booked.',
        'engineer_comments.required' => 'Engineer comments required when completing.',
        'engineer_comments.min' => 'Engineer comments must be at least 10 characters.',
        'time_start.required' => 'Start time required when completing.',
        'time_finish.required' => 'Finish time required when completing.',
        'time_finish.after' => 'Finish time must be after start time.',
        'billed_hours.required' => 'Billed hours required when completing.',
    ]);

    // Prevent engineer from modifying other fields
    if ($isEngineerUpdate) {
        $validated = array_merge($validated, [
            'customer_name' => $callLog->customer_name,
            'customer_email' => $callLog->customer_email,
            'type' => $callLog->type,
            'amount_charged' => $callLog->amount_charged,
            'currency' => $callLog->currency,
            'date_booked' => $callLog->date_booked,
            'assigned_to' => $callLog->assigned_to,
            'fault_description' => $callLog->fault_description,
        ]);
    }

    if ($request->input('status') === 'complete') {
        if (empty($validated['date_resolved'])) {
            $validated['date_resolved'] = now()->toDateString();
        }
        if (empty($validated['job_card'])) {
            $validated['job_card'] = 'TBD-' . $callLog->id;
        }
    }

    DB::beginTransaction();

    try {
        $oldStatus = $callLog->status;
        $callLog->update($validated);

        if ($oldStatus !== $validated['status']) {
            $this->handleStatusChangeNotifications($callLog, $oldStatus, $validated['status']);
        }

        DB::commit();

        $message = $isEngineerUpdate ? 'Job progress updated successfully!' : 'Job updated successfully!';
        return redirect()->route('admin.call-logs.show', $callLog)->with('success', $message);

    } catch (\Exception $ex) {
        DB::rollBack();

        Log::error('Failed to update job', [
            'job' => $callLog->id,
            'error' => $ex->getMessage(),
            'trace' => $ex->getTraceAsString(),
        ]);

        return back()->withInput()->with('error', 'Failed to update job. Please try again.');
    }
}




    /**
     * Delete (soft delete) a call log.
     */
    public function destroy(CallLog $callLog): RedirectResponse
    {
        if (!$this->canDeleteJob($callLog)) {
            return redirect()->back()->with('error', 'Unauthorized to delete this job.');
        }

        try {
            $jobId = $callLog->id;
            $callLog->delete();

            return redirect()->route('admin.call-logs.index')->with('success', "Job #{$jobId} deleted successfully.");
        } catch (\Exception $e) {
            Log::error('Failed to delete job', [
                'error' => $e->getMessage(),
                'job_id' => $callLog->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error', 'Failed to delete job. Please try again.');
        }
    }

    /**
     * Display dashboard with stats.
     */
    public function dashboard(): View
    {
        $stats = [
            'total_jobs' => CallLog::count(),
            'pending_jobs' => CallLog::where('status', 'pending')->count(),
            'in_progress_jobs' => CallLog::where('status', 'in_progress')->count(),
            'completed_jobs' => CallLog::where('status', 'complete')->count(),
            'emergency_jobs' => CallLog::where('type', 'emergency')->count(),
            'total_revenue' => CallLog::where('status', 'complete')->sum('amount_charged'),
            'monthly_revenue' => CallLog::where('status', 'complete')
                ->whereMonth('date_resolved', now()->month)
                ->whereYear('date_resolved', now()->year)
                ->sum('amount_charged'),
        ];

        $recentJobs = CallLog::with(['assignedTo:id,name'])
            ->select(['id', 'customer_name', 'company_name', 'status', 'type', 'date_booked', 'assigned_to'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $technicianStats = User::whereIn('role', ['technician', 'manager'])
            ->withCount([
                'assignedJobs as pending_jobs' => fn($q) => $q->where('status', 'pending'),
                'assignedJobs as in_progress_jobs' => fn($q) => $q->where('status', 'in_progress'),
                'assignedJobs as completed_jobs' => fn($q) => $q->where('status', 'complete'),
            ])
            ->get();

        return view('admin.CallLogs.dashboard', compact('stats', 'recentJobs', 'technicianStats'));
    }

    /**
     * Assign job to engineer with validation.
     */
    public function assign(Request $request, CallLog $callLog): RedirectResponse
    {
        if (!$this->canAssignJob($callLog)) {
            return redirect()->back()->with('error', 'Unauthorized to assign this job.');
        }

        $validated = $request->validate([
            'engineer' => 'required|exists:users,id',
            'assignment_notes' => 'nullable|string|max:' . self::MAX_ASSIGNMENT_NOTES,
        ]);

        DB::beginTransaction();

        try {
            $engineer = User::findOrFail($validated['engineer']);

            if ($this->isEngineerOverloaded($engineer)) {
                return redirect()->back()->with('error', "Engineer {$engineer->name} has too many active assignments.");
            }

            $callLog->update([
                'assigned_to' => $engineer->id,
                'status' => 'assigned',
                'engineer_comments' => $validated['assignment_notes'] ?? null,
                'assigned_at' => now(),
            ]);

            $this->sendJobAssignmentNotifications($callLog, $engineer);

            DB::commit();

            Log::info('Job assigned successfully', [
                'job_id' => $callLog->id,
                'engineer_id' => $engineer->id,
                'assigned_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', "Job #{$callLog->id} assigned to {$engineer->name} successfully!");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Job assignment failed', [
                'job_id' => $callLog->id,
                'engineer_id' => $validated['engineer'] ?? null,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error', 'Assignment failed. Please try again.');
        }
    }

    /**
     * Update job status via AJAX with validation.
     */
    public function updateStatus(Request $request, CallLog $callLog): JsonResponse
    {
        if (!$this->canUpdateJobStatus($callLog)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this job status.',
            ], 403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(self::JOB_STATUSES))],
            'time_start' => 'nullable|date_format:H:i',
            'time_finish' => 'nullable|date_format:H:i',
            'date_resolved' => 'nullable|date',
            'engineer_comments' => 'nullable|string|max:' . self::MAX_ENGINEER_COMMENTS,
        ]);

        if (!$this->isValidStatusTransition($callLog->status, $validated['status'])) {
            return response()->json([
                'success' => false,
                'message' => "Invalid status transition from {$callLog->status} to {$validated['status']}.",
            ]);
        }

        DB::beginTransaction();

        try {
            $updateData = ['status' => $validated['status']];

            if ($validated['status'] === 'in_progress' && $request->filled('time_start')) {
                $updateData['time_start'] = $validated['time_start'];
            }

            if ($validated['status'] === 'complete') {
                $updateData['date_resolved'] = $validated['date_resolved'] ?? now()->format('Y-m-d');
                if ($request->filled('time_finish')) {
                    $updateData['time_finish'] = $validated['time_finish'];
                }
            }

            if ($request->filled('engineer_comments')) {
                $updateData['engineer_comments'] = $validated['engineer_comments'];
            }

            $oldStatus = $callLog->status;
            $callLog->update($updateData);

            $this->handleStatusChangeNotifications($callLog, $oldStatus, $validated['status']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'new_status' => $validated['status'],
                'status_label' => self::JOB_STATUSES[$validated['status']],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update job status', [
                'error' => $e->getMessage(),
                'job_id' => $callLog->id,
                'new_status' => $validated['status'],
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Status update failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Export filtered call logs to Excel.
     */
    public function export(Request $request)
    {
        try {
            $query = CallLog::with(['assignedTo:id,name', 'approver:id,name']);
            $this->applyFiltersToQuery($query, $request);

            $callLogs = $query->orderBy('date_booked', 'desc')->get();

            if ($callLogs->isEmpty()) {
                return redirect()->back()->with('warning', 'No jobs found matching your criteria.');
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
                    'Currency' => $job->currency ?: 'USD',
                    'Assigned Technician' => optional($job->assignedTo)->name ?: 'Unassigned',
                    'Approved By' => optional($job->approver)->name ?: 'N/A',
                    'Engineer Comments' => $job->engineer_comments ?: 'N/A',
                    'Booked By' => $job->booked_by ?: 'N/A',
                    'Created At' => $job->created_at->format('Y-m-d H:i:s'),
                    'Updated At' => $job->updated_at->format('Y-m-d H:i:s'),
                ];
            });
        } catch (\Exception $e) {
            Log::error('Export failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error', 'Export failed. Please try again.');
        }
    }

    /**
     * Send job completion notification email to customer.
     */
    public function notifyCustomer(Request $request, CallLog $callLog): JsonResponse
    {
        if (!$this->canNotifyCustomer($callLog)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to send notifications for this job.',
            ], 403);
        }

        if (empty($callLog->customer_email)) {
            return response()->json([
                'success' => false,
                'message' => 'No customer email address found for this job.',
            ]);
        }

        if ($callLog->status !== 'complete') {
            return response()->json([
                'success' => false,
                'message' => 'Job must be completed before sending notification.',
            ]);
        }

        try {
            Mail::to($callLog->customer_email)->send(new JobCompletionNotification($callLog));

            Log::info('Job completion notification sent', [
                'job_id' => $callLog->id,
                'customer_email' => $callLog->customer_email,
                'sent_by' => Auth::user()->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Completion notification sent to {$callLog->customer_email} successfully!",
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send customer notification', [
                'job_id' => $callLog->id,
                'customer_email' => $callLog->customer_email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification. Please try again.',
            ], 500);
        }
    }

    // ----------------- Specialized Views -------------------

    /**
     * Display unassigned jobs with filtering.
     */
    public function unassigned(Request $request): View
    {
        $query = CallLog::query()->whereNull('assigned_to');

        // Apply filters from request
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('fault_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('date_range')) {
            $now = now();
            switch ($request->input('date_range')) {
                case 'today':
                    $query->whereDate('date_booked', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('date_booked', $now->subDay()->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('date_booked', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'last_week':
                    $query->whereBetween('date_booked', [$now->subWeek()->startOfWeek(), $now->subWeek()->endOfWeek()]);
                    break;
                case 'overdue':
                    $query->whereNotNull('date_booked')
                          ->where('date_booked', '<', now()->subDay());
                    break;
            }
        }

        $query->orderByDesc('date_booked')->orderByDesc('id');
        $query->with(['assignedTo']);

        $callLogs = $query->paginate(15)->withQueryString();
        $technicians = $this->getTechnicians();

        $stats = [
            'total_unassigned' => $callLogs->total(),
            'urgent_count' => $callLogs->where('type', 'emergency')->count(),
            'overdue_count' => $callLogs->filter(function ($job) {
                return $job->date_booked && $job->date_booked->lt(now()->subDay());
            })->count(),
        ];

        return view('admin.CallLogs.unassigned', [
            'callLogs' => $callLogs,
            'technicians' => $technicians,
            'stats' => $stats,
            'filters' => $request->only(['search', 'type', 'date_range']),
            'pageTitle' => 'Unassigned Jobs',
        ]);
    }

    public function assigned(Request $request): View
    {
        return $this->buildFilteredView($request, ['status' => 'assigned'], 'admin.CallLogs.assigned', 'Assigned Jobs');
    }

    public function pending(Request $request): View
    {
        return $this->buildFilteredView($request, ['status' => 'pending'], 'admin.CallLogs.pending', 'Pending Jobs');
    }

    public function inProgress(Request $request): View
    {
        return $this->buildFilteredView($request, ['status' => 'in_progress'], 'admin.CallLogs.in-progress', 'In Progress Jobs');
    }

    public function completed(Request $request): View
    {
        try {
            $query = CallLog::where('status', 'complete');

            // Apply filters for date range
            if ($request->filled('date_range')) {
                switch ($request->date_range) {
                    case 'today':
                        $query->whereDate('date_resolved', today());
                        break;
                    case 'this_week':
                        $query->whereBetween('date_resolved', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'this_month':
                        $query->whereMonth('date_resolved', now()->month)
                              ->whereYear('date_resolved', now()->year);
                        break;
                    case 'last_month':
                        $query->whereMonth('date_resolved', now()->subMonth()->month)
                              ->whereYear('date_resolved', now()->subMonth()->year);
                        break;
                    case 'last_3_months':
                        $query->where('date_resolved', '>=', now()->subMonths(3));
                        break;
                    case 'this_year':
                        $query->whereYear('date_resolved', now()->year);
                        break;
                }
            }

            // Additional filters
            if ($request->filled('engineer')) {
                $query->where('assigned_to', $request->engineer);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%")
                      ->orWhere('fault_description', 'like', "%{$search}%")
                      ->orWhere('job_card', 'like', "%{$search}%");
                });
            }

            $callLogs = $query->with(['assignedTo', 'approver'])
                              ->orderBy('date_resolved', 'desc')
                              ->paginate(15)
                              ->appends($request->query());

            $technicians = User::whereIn('role', ['technician', 'manager', 'admin', 'accounts'])
                               ->orderBy('name')
                               ->get();

            $baseQuery = CallLog::where('status', 'complete');
            $totalRevenue = $baseQuery->sum('amount_charged') ?? 0;
            $avgRevenue = $baseQuery->avg('amount_charged') ?? 0;

            $completedJobs = $baseQuery->whereNotNull('billed_hours')
                                      ->where('billed_hours', '!=', '')
                                      ->get();

            $numericHours = $completedJobs->map(function($job) {
                $hours = $job->billed_hours;
                if (str_contains($hours, '%')) {
                    return 0.1;
                }
                return is_numeric($hours) ? (float)$hours : 0;
            })->filter(function($hour) {
                return $hour > 0;
            });

            $avgDuration = $numericHours->avg() ?? 0;

            $stats = [
                'total_completed' => $baseQuery->count(),
                'this_month' => $baseQuery->whereMonth('date_resolved', now()->month)
                                         ->whereYear('date_resolved', now()->year)
                                         ->count(),
                'total_revenue' => $totalRevenue,
                'avg_duration' => round($avgDuration, 2),
            ];

            return view('admin.CallLogs.completed', compact('callLogs', 'stats', 'technicians'));

        } catch (\Exception $e) {
            Log::error('Error loading completed jobs: ' . $e->getMessage());

            return view('admin.CallLogs.completed', [
                'callLogs' => CallLog::where('status', 'complete')->paginate(15),
                'stats' => [
                    'total_completed' => 0,
                    'this_month' => 0,
                    'total_revenue' => 0,
                    'avg_duration' => 0,
                ],
                'technicians' => collect(),
                'error' => 'Error loading completed jobs. Please try again.',
            ]);
        }
    }

    public function cancelled(Request $request): View
    {
        return $this->buildFilteredView($request, ['status' => 'cancelled'], 'admin.CallLogs.cancelled', 'Cancelled Jobs');
    }

    public function myJobs(Request $request): View
    {
        $userId = Auth::id();
        $stats = [
            'pending' => CallLog::where('assigned_to', $userId)->where('status', 'pending')->count(),
            'in_progress' => CallLog::where('assigned_to', $userId)->where('status', 'in_progress')->count(),
            'completed' => CallLog::where('assigned_to', $userId)->where('status', 'complete')->count(),
        ];

        $view = $this->buildFilteredView($request, ['assigned_to' => $userId], 'admin.CallLogs.my-jobs', 'My Jobs');

        return $view->with('stats', $stats);
    }

    public function all(Request $request): View
    {
        $query = CallLog::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('id', $search)
                  ->orWhere('fault_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('technician')) {
            $query->where('assigned_to', $request->input('technician'));
        }

        if ($request->filled('date_range')) {
            $dateRange = $request->input('date_range');
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('date_booked', today());
                    break;
                case 'this_week':
                    $query->whereBetween('date_booked', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('date_booked', now()->month)
                          ->whereYear('date_booked', now()->year);
                    break;
            }
        }

        $callLogs = $query->with('assignedTo')->orderBy('date_booked', 'desc')->paginate(15);

        $statsQuery = clone $query;

        $stats = [
            'total' => $statsQuery->count(),
            'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $statsQuery)->where('status', 'in_progress')->count(),
            'total_revenue_usd' => (clone $statsQuery)
                ->where('currency', 'USD')
                ->sum('amount_charged'),
            'total_revenue_zwg' => (clone $statsQuery)
                ->where('currency', 'ZWG')
                ->sum('amount_charged'),
        ];

        $technicians = $this->getTechnicians();

        return view('admin.CallLogs.alljobs', [
            'callLogs' => $callLogs,
            'stats' => $stats,
            'technicians' => $technicians,
            'filters' => $request->only(['search', 'status', 'technician', 'date_range']),
            'pageTitle' => 'All Jobs'
        ]);
    }

    // ---------------------- PRIVATE HELPERS ---------------------- //

    /**
     * Builds a filtered view with base filters and applies request filters.
     */
    private function buildFilteredView(Request $request, array $baseFilters, string $viewPath, string $title): View
    {
        $query = CallLog::with(['assignedTo:id,name,email', 'approver:id,name']);

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

        return view($viewPath, [
            'callLogs' => $callLogs,
            'technicians' => $this->getTechnicians(),
            'stats' => $stats,
            'title' => $title,
        ]);
    }

    private function applyFiltersToQuery($query, Request $request): void
    {
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('job_card', 'like', "%{$search}%")
                    ->orWhere('fault_description', 'like', "%{$search}%")
                    ->orWhere('zimra_ref', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('technician') || $request->filled('engineer')) {
            $technicianId = $request->technician ?: $request->engineer;
            $query->where('assigned_to', $technicianId);
        }
        if ($request->filled('date_range')) {
            $this->applyDateFilter($query, $request->date_range);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date_booked', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }
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
            'last_year' => [$now->copy()->subYear()->startOfYear(), $now->copy()->subYear()->endOfYear()],
        ];

        if (isset($ranges[$dateRange])) {
            [$start, $end] = $ranges[$dateRange];
            $query->whereBetween('date_booked', [$start, $end]);
        }
    }

    private function validateSortField(string $field): string
    {
        $allowedSorts = ['date_booked', 'customer_name', 'status', 'amount_charged', 'created_at', 'type', 'assigned_to'];

        return in_array($field, $allowedSorts, true) ? $field : 'date_booked';
    }

    private function validatePerPage(int $perPage): int
    {
        $allowedPerPage = [10, 25, 50, 100];

        return in_array($perPage, $allowedPerPage, true) ? $perPage : self::DEFAULT_PAGINATION;
    }

    private function getTechnicians()
    {
        return User::whereIn('role', ['technician', 'manager', 'admin', 'accounts'])
            ->select(['id', 'name', 'email', 'role'])
            ->orderBy('name')
            ->get();
    }

    private function calculateStats($query): array
    {
        $baseQuery = clone $query;

        return [
            'total' => $baseQuery->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $baseQuery)->where('status', 'complete')->count(),
            'total_revenue' => (clone $baseQuery)->where('status', 'complete')->sum('amount_charged'),
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
            'overdue' => (clone $query)
                ->where('date_booked', '<', now())
                ->whereNotIn('status', ['complete', 'cancelled'])
                ->count(),
        ];
    }

    private function canEditJob(CallLog $callLog): bool
    {
        $user = Auth::user();

        return in_array($user->role ?? '', ['admin', 'manager'], true) ||
            $callLog->assigned_to === $user->id ||
            $callLog->approved_by === $user->id;
    }

    private function canAssignJob(CallLog $callLog): bool
    {
        return in_array(Auth::user()->role ?? '', ['admin', 'manager'], true);
    }

    private function canDeleteJob(CallLog $callLog): bool
    {
        return in_array(Auth::user()->role ?? '', ['admin', 'manager'], true);
    }

    private function canUpdateJobStatus(CallLog $callLog): bool
    {
        $user = Auth::user();

        return in_array($user->role ?? '', ['admin', 'manager'], true) ||
            $callLog->assigned_to === $user->id;
    }

    private function canNotifyCustomer(CallLog $callLog): bool
    {
        $user = Auth::user();

        return in_array($user->role ?? '', ['admin', 'manager'], true) ||
            $callLog->assigned_to === $user->id;
    }

    private function isValidStatusTransition(string $currentStatus, string $newStatus): bool
    {
        $validTransitions = [
            'pending' => ['assigned', 'cancelled'],
            'assigned' => ['in_progress', 'cancelled', 'pending'],
            'in_progress' => ['complete', 'assigned'],
            'complete' => [],
            'cancelled' => ['pending'],
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? [], true);
    }

    private function isEngineerOverloaded(User $engineer): bool
    {
        $activeJobsCount = CallLog::where('assigned_to', $engineer->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->count();

        return $activeJobsCount >= self::ENGINEER_OVERLOAD_THRESHOLD;
    }

    private function sendJobAssignmentNotifications(CallLog $callLog, User $engineer): void
    {
        try {
            if ($engineer->email) {
                Mail::to($engineer->email)->send(new JobAssignedToTechnician($callLog, $engineer));
            }

            if ($callLog->customer_email) {
                Mail::to($callLog->customer_email)->send(new JobAssignedNotificationToCustomer($callLog, $engineer));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send assignment notifications', [
                'job_id' => $callLog->id,
                'engineer_id' => $engineer->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function handleStatusChangeNotifications(CallLog $callLog, string $oldStatus, string $newStatus): void
    {
        $significantTransitions = ['assigned' => 'in_progress', 'in_progress' => 'complete'];

        if (isset($significantTransitions[$oldStatus]) && $significantTransitions[$oldStatus] === $newStatus) {
            try {
                if ($callLog->customer_email && $newStatus === 'complete') {
                    Mail::to($callLog->customer_email)->send(new JobCompletionNotification($callLog));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send status change notification', [
                    'job_id' => $callLog->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Determine the appropriate redirect URL after creating a job
     */
    private function getRedirectUrl(Request $request): string
    {
        if ($request->has('redirect_to') && $request->redirect_to) {
            return $request->redirect_to;
        }

        $referer = $request->headers->get('referer');
        if ($referer && $this->isValidReferer($referer)) {
            if (str_contains($referer, '/create')) {
                return route('admin.call-logs.all');
            }
            return $referer;
        }

        if (session()->has('intended_url')) {
            $intendedUrl = session()->pull('intended_url');
            return $intendedUrl;
        }

        return route('admin.call-logs.all');
    }

    /**
     * Check if the referer URL is valid and from our application
     */
    private function isValidReferer(string $referer): bool
    {
        $appUrl = rtrim(config('app.url'), '/');
        $requestHost = request()->getHost();

        if (!str_starts_with($referer, $appUrl) && !str_contains($referer, $requestHost)) {
            return false;
        }

        $excludePatterns = [
            '/logout',
            '/login',
            'javascript:',
            'data:',
            'vbscript:',
        ];

        foreach ($excludePatterns as $pattern) {
            if (str_contains(strtolower($referer), $pattern)) {
                return false;
            }
        }

        return true;
    }

}
