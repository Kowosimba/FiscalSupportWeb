<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\CallLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Exports\CallLogsExport;
use Maatwebsite\Excel\Facades\Excel;

class CallLogController extends Controller
{
    // Middleware removed - will be added back later

    public function index(Request $request)
    {
        $query = CallLog::query();

        // Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('job_card', 'like', "%{$search}%")
                  ->orWhere('fault_description', 'like', "%{$search}%")
                  ->orWhere('zimra_ref', 'like', "%{$search}%");
            });
        }

        // Status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Technician
        if ($technician = $request->input('technician')) {
            $query->where('engineer', $technician);
        }

        // Date Range
        if ($dateRange = $request->input('date_range')) {
            switch($dateRange) {
                case 'today':
                    $query->whereDate('date_booked', today());
                    break;
                case 'this_week':
                    $query->whereBetween('date_booked', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('date_booked', now()->month)->whereYear('date_booked', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('date_booked', now()->subMonth()->month)
                          ->whereYear('date_booked', now()->subMonth()->year);
                    break;
                case 'this_year':
                    $query->whereYear('date_booked', now()->year);
                    break;
            }
        }

        $callLogs = $query->orderBy('date_booked', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total_jobs' => CallLog::count(),
            'pending_jobs' => CallLog::where('status', 'pending')->count(),
            'in_progress_jobs' => CallLog::where('status', 'in_progress')->count(),
            'completed_jobs' => CallLog::where('status', 'complete')->count(),
        ];

        $technicians = User::whereIn('role', ['technician', 'manager'])
            ->select(['id', 'name'])
            ->get();

        return view('admin.calllogs.Index', compact('callLogs', 'stats', 'technicians'));
    }

public function create()
{
    $user = auth::user();
    
    // For admin/accounts users, we don't need to fetch any additional data
    return view('admin.calllogs.create');
}

public function store(Request $request)
{
    $user = auth::user();
    
    $rules = [
        'customer_name' => 'required|string|max:255',
        'customer_email' => 'nullable|email|max:255',
        'customer_phone' => 'nullable|string|max:20',
        'fault_description' => 'required|string',
        'zimra_ref' => 'nullable|string|max:255',
        'type' => 'required|in:normal,emergency',
        'amount_charged' => 'required|numeric|min:0',
        'date_booked' => 'required|date',
    ];

    $validated = $request->validate($rules);

    // Set default values
    $validated['status'] = 'pending';
    $validated['approved_by'] = $user->id;
    $validated['approved_by_name'] = $user->name; // Add the user's name
    $validated['date_booked'] = $validated['date_booked'] ?? now()->format('Y-m-d');
    $validated['job_card'] = null;

    CallLog::create($validated);

    return redirect()->route('admin.call-logs.Index')
        ->with('success', 'Job created successfully.');
}

    public function show(CallLog $callLog)
    {
        return view('admin.calllogs.show', compact('callLog'));
    }

    public function edit(CallLog $callLog)
    {
        // All role checks removed
        return view('admin.calllogs.edit', compact('callLog'));
    }

    public function update(Request $request, CallLog $callLog)
    {
        $data = $request->validate([
            'job_card' => 'required|string|unique:call_logs,job_card,' . $callLog->id,
            'company_name' => 'required|string|max:255',
            'fault_description' => 'nullable|string',
            'zimra_ref' => 'nullable|string|max:255',
            'date_booked' => 'required|date',
            'date_resolved' => 'nullable|date|after_or_equal:date_booked',
            'time_start' => 'nullable|date_format:H:i',
            'time_finish' => 'nullable|date_format:H:i|after:time_start',
            'type' => 'required|in:normal,maintenance,repair,installation,consultation,emergency',
            'billed_hours' => 'nullable|numeric|min:0',
            'amount_charged' => 'required|numeric|min:0',
            'status' => 'required|in:pending,assigned,in_progress,complete,cancelled',
            'approved_by' => 'required|string|max:255',
            'engineer' => 'nullable|string|max:255',
            'engineer_comments' => 'nullable|string',
            'booked_by' => 'nullable|string|max:255'
        ]);

        $callLog->update($data);

        return redirect()->route('admin.call-logs.Index')
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(CallLog $callLog)
    {
        $callLog->delete();

        return redirect()->route('admin.call-logs.Index')
            ->with('success', 'Job deleted successfully.');
    }

    public function dashboard()
    {
        $callLogs = CallLog::orderBy('date_booked', 'desc')->paginate(15);
        
        $stats = [
            'total_jobs' => CallLog::count(),
            'pending_jobs' => CallLog::where('status', 'pending')->count(),
            'in_progress_jobs' => CallLog::where('status', 'in_progress')->count(),
            'completed_jobs' => CallLog::where('status', 'complete')->count(),
            'emergency_jobs' => CallLog::where('type', 'emergency')->count(),
        ];

        return view('admin.calllogs.Index', compact('callLogs', 'stats'));
    }

  public function myJobs(Request $request)
    {
        // Base query: only jobs assigned to current user
        $query = CallLog::where('assigned_to', auth::user()->id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('fault_description', 'like', "%{$search}%")
                  ->orWhere('job_card', 'like', "%{$search}%");
            });
        }

        // Filter: status (only pending, in_progress, complete)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter: job type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter: date range
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('date_booked', Carbon::today());
                    break;
                case 'this_week':
                    $query->whereBetween('date_booked', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('date_booked', Carbon::now()->month)
                          ->whereYear('date_booked', Carbon::now()->year);
                    break;
                case 'overdue':
                    $query->where('date_booked', '<=', Carbon::now()->subDays(7))
                          ->whereNotIn('status', ['complete']);
                    break;
            }
        }

        // Paginate
        $callLogs = $query->orderByRaw("FIELD(status, 'pending', 'in_progress', 'complete')")
                         ->orderBy('date_booked', 'desc')
                         ->paginate(20);

        // Calculate statistics for only 3 statuses
        $stats = [
            'pending' => CallLog::where('assigned_to', auth::user()->id)->where('status', 'pending')->count(),
            'in_progress' => CallLog::where('assigned_to', auth::user()->id)->where('status', 'in_progress')->count(),
            'complete' => CallLog::where('assigned_to', auth::user()->id)->where('status', 'complete')->count(),
        ];

        return view('admin.calllogs.my-jobs', compact('callLogs', 'stats'));
    }



    public function inProgress(Request $request)
    {
        // Base query: only in progress jobs
        $query = CallLog::with('assignedTo')
                        ->where('status', 'in_progress');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('fault_description', 'like', "%{$search}%")
                  ->orWhere('job_card', 'like', "%{$search}%");
            });
        }

        // Filter: engineer (assigned_to user id)
        if ($request->filled('engineer')) {
            $query->where('assigned_to', $request->engineer);
        }

        // Filter: job type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter: date range
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('date_booked', Carbon::today());
                    break;
                case 'this_week':
                    $query->whereBetween('date_booked', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('date_booked', Carbon::now()->month)
                          ->whereYear('date_booked', Carbon::now()->year);
                    break;
                case 'overdue':
                    $query->where('date_booked', '<=', Carbon::now()->subDays(3));
                    break;
            }
        }

        // Paginate
        $callLogs = $query->orderBy('date_booked', 'desc')->paginate(20);

        // Get technicians for filter
        $technicians = User::where('role', 'technician')
                           ->orderBy('name')
                           ->get();

        return view('admin.calllogs.in-progress', compact('callLogs', 'technicians'));
    }

     public function completed(Request $request)
    {
        // Base query: only completed jobs
        $query = CallLog::with('assignedTo')
                        ->where('status', 'complete');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('fault_description', 'like', "%{$search}%")
                  ->orWhere('job_card', 'like', "%{$search}%");
            });
        }

        // Filter: engineer (assigned_to user id)
        if ($request->filled('engineer')) {
            $query->where('assigned_to', $request->engineer);
        }

        // Filter: job type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter: date range
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('date_resolved', Carbon::today());
                    break;
                case 'this_week':
                    $query->whereBetween('date_resolved', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('date_resolved', Carbon::now()->month)
                          ->whereYear('date_resolved', Carbon::now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('date_resolved', Carbon::now()->subMonth()->month)
                          ->whereYear('date_resolved', Carbon::now()->subMonth()->year);
                    break;
            }
        }

        // Paginate
        $callLogs = $query->orderBy('date_resolved', 'desc')->paginate(20);

        // Calculate dynamic statistics
        $stats = [
            'total_completed' => CallLog::where('status', 'complete')->count(),
            'this_month' => CallLog::where('status', 'complete')
                                  ->whereMonth('date_resolved', Carbon::now()->month)
                                  ->whereYear('date_resolved', Carbon::now()->year)
                                  ->count(),
            'total_revenue' => CallLog::where('status', 'complete')->sum('amount_charged'),
            'avg_duration' => CallLog::where('status', 'complete')
                                    ->whereNotNull('billed_hours')
                                    ->avg('billed_hours') ?? 0,
        ];

        // Get technicians for filter
        $technicians = User::where('role', 'technician')
                           ->orderBy('name')
                           ->get();

        return view('admin.calllogs.completed', compact('callLogs', 'stats', 'technicians'));
    }

public function pending(Request $request)
{
    // Base query: only pending
    $query = CallLog::with('assignedTo')
                    ->where('status', 'pending');

    // Filter: job type
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    // Filter: technician (assigned_to user id)
    if ($request->filled('technician')) {
        $query->where('assigned_to', $request->technician);
    }

    // Filter: date range
    if ($request->filled('date_range')) {
        switch ($request->date_range) {
            case 'today':
                $query->whereDate('date_booked', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('date_booked', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('date_booked', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'last_week':
                $query->whereBetween('date_booked', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ]);
                break;
            case 'this_month':
                $query->whereMonth('date_booked', Carbon::now()->month);
                break;
        }
    }

    // Paginate
    $callLogs = $query->orderBy('date_booked','desc')->paginate(20);

    // Get technicians for filter
    $technicians = User::where('role','technician')
                       ->orderBy('name')
                       ->get();

    return view('admin.calllogs.pending', compact('callLogs', 'technicians'));
}




public function unassigned(Request $request)
{
    // Start with unassigned jobs
    $query = CallLog::whereNull('assigned_to')->with('approver');
    
    // Filter by job type if specified
    if ($type = $request->input('type')) {
        $query->where('type', $type);
    }
    
    // Filter by date range if specified
    if ($dateRange = $request->input('date_range')) {
        switch($dateRange) {
            case 'today':
                $query->whereDate('date_booked', today());
                break;
            case 'yesterday':
                $query->whereDate('date_booked', Carbon::yesterday()->toDateString());
                break;
            case 'this_week':
                $query->whereBetween('date_booked', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'overdue':
                $query->where('date_booked', '<=', now()->subDays(1)->format('Y-m-d'));
                break;
        }
    }
    
    // Filter by amount range if specified
    if ($amountRange = $request->input('amount_range')) {
        switch($amountRange) {
            case 'low':
                $query->where('amount_charged', '<', 100);
                break;
            case 'medium':
                $query->whereBetween('amount_charged', [100, 500]);
                break;
            case 'high':
                $query->where('amount_charged', '>', 500);
                break;
        }
    }
    
    // Get paginated results (15 per page by default)
    $callLogs = $query->orderBy('date_booked', 'desc')
                     ->paginate(15)
                     ->withQueryString();
    
    // Get available technicians for assignment dropdown
    $technicians = User::whereIn('role', ['technician', 'manager'])
        ->select(['id', 'name'])
        ->orderBy('name')
        ->get();

    // Calculate some stats for the view
    $stats = [
        'total' => $callLogs->total(),
        'emergency' => $callLogs->getCollection()->where('type', 'emergency')->count(),
        'overdue' => $callLogs->getCollection()->filter(function($job) {
            return $job->date_booked->diffInHours(now()) > 24;
        })->count(),
        'high_value' => $callLogs->getCollection()->where('amount_charged', '>', 500)->count(),
    ];

    return view('admin.calllogs.unassigned', compact('callLogs', 'technicians', 'stats'));
}



    public function assigned()
    {
        $callLogs = CallLog::where('status', 'assigned')
            ->orderBy('date_booked', 'desc')
            ->paginate(15);
        
        return view('admin.calllogs.assigned', compact('callLogs'));
    }

    public function cancelled()
    {
        $callLogs = CallLog::where('status', 'cancelled')
            ->orderBy('date_booked', 'desc')
            ->paginate(15);
        
        return view('admin.calllogs.cancelled', compact('callLogs'));
    }

    public function assign(Request $request, CallLog $callLog)
{
    $request->validate([
        'engineer' => 'required|exists:users,id',
        'assignment_notes' => 'nullable|string|max:500'
    ]);

    try {
        $engineer = User::findOrFail($request->engineer);
        
        $callLog->update([
            'assigned_to' => $engineer->id,
            'engineer' => $engineer->name,
            'status' => 'assigned',
            'engineer_comments' => $request->assignment_notes ?? null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job successfully assigned to ' . $engineer->name
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to assign job: ' . $e->getMessage()
        ], 500);
    }
}

    public function updateStatus(Request $request, CallLog $callLog)
    {
        // Ensure the user can only update their own jobs
        if ($callLog->assigned_to !== auth::user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,complete'
        ]);

        $updateData = ['status' => $request->status];

        // Auto-set timestamps based on status
        if ($request->status === 'in_progress' && $request->time_start) {
            $updateData['time_start'] = $request->time_start;
        }

        if ($request->status === 'complete') {
            $updateData['date_resolved'] = $request->date_resolved ?? now();
            $updateData['time_finish'] = $request->time_finish ?? now()->format('H:i');
        }

        $callLog->update($updateData);

        return response()->json(['success' => true, 'message' => 'Job status updated successfully']);
    }

    public function complete(CallLog $callLog)
    {
        $callLog->update([
            'status' => 'complete',
            'date_resolved' => now()->format('Y-m-d')
        ]);

        return redirect()->back()->with('success', 'Job marked as complete.');
    }

    public function reports(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $engineer = $request->get('engineer');
        $status = $request->get('status');
        $type = $request->get('type');

        $query = CallLog::whereBetween('date_booked', [$dateFrom, $dateTo]);

        if ($engineer) {
            $query->where('engineer', $engineer);
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($type) {
            $query->where('type', $type);
        }

        $callLogs = $query->get();

        $stats = [
            'total_jobs' => $callLogs->count(),
            'completed_jobs' => $callLogs->where('status', 'complete')->count(),
            'total_revenue' => $callLogs->where('status', 'complete')->sum('amount_charged'),
            'total_billed_hours' => $callLogs->sum('billed_hours'),
            'emergency_jobs' => $callLogs->where('type', 'emergency')->count(),
            'avg_completion_time' => $callLogs->where('status', 'complete')->avg('billed_hours'),
        ];

        $engineerStats = $callLogs->groupBy('engineer')->map(function ($jobs, $engineer) {
            return [
                'total' => $jobs->count(),
                'completed' => $jobs->where('status', 'complete')->count(),
                'in_progress' => $jobs->where('status', 'in_progress')->count(),
                'revenue' => $jobs->where('status', 'complete')->sum('amount_charged'),
                'billed_hours' => $jobs->sum('billed_hours'),
            ];
        });

        $dailyStats = $callLogs->groupBy(function ($job) {
            return $job->date_booked;
        })->map(function ($jobs, $date) {
            return [
                'total' => $jobs->count(),
                'completed' => $jobs->where('status', 'complete')->count(),
                'in_progress' => $jobs->where('status', 'in_progress')->count(),
                'revenue' => $jobs->where('status', 'complete')->sum('amount_charged'),
                'billed_hours' => $jobs->sum('billed_hours'),
            ];
        });

        $jobTypeStats = $callLogs->groupBy('type')->map(function ($jobs, $type) use ($callLogs) {
            return [
                'count' => $jobs->count(),
                'percentage' => round(($jobs->count() / $callLogs->count()) * 100, 1),
                'avg_hours' => $jobs->avg('billed_hours') ?: 0,
                'revenue' => $jobs->where('status', 'complete')->sum('amount_charged'),
            ];
        });

        $companyStats = $callLogs->groupBy('company_name')->map(function ($jobs, $company) {
            return [
                'total' => $jobs->count(),
                'completed' => $jobs->where('status', 'complete')->count(),
                'revenue' => $jobs->where('status', 'complete')->sum('amount_charged'),
                'last_service' => $jobs->max('date_booked'),
            ];
        })->sortByDesc('total')->take(10);

        return view('admin.calllogs.reports', compact(
            'stats', 'engineerStats', 'dailyStats', 'jobTypeStats', 'companyStats',
            'dateFrom', 'dateTo', 'engineer', 'status', 'type'
        ));
    }

   

    private function exportToCsv($callLogs)
    {
        $filename = 'job_cards_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($callLogs) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Job Card', 'Company Name', 'Fault Description', 'ZIMRA Ref',
                'Date Booked', 'Date Resolved', 'Time Start', 'Time Finish',
                'Type', 'Billed Hours', 'Amount Charged', 'Status',
                'Approved By', 'Engineer', 'Engineer Comments', 'Booked By'
            ]);

            foreach ($callLogs as $job) {
                fputcsv($file, [
                    $job->job_card,
                    $job->company_name,
                    $job->fault_description,
                    $job->zimra_ref,
                    $job->date_booked,
                    $job->date_resolved,
                    $job->time_start,
                    $job->time_finish,
                    ucfirst($job->type),
                    $job->billed_hours ?? 0,
                    $job->amount_charged ?? 0,
                    ucfirst($job->status),
                    $job->approved_by,
                    $job->engineer ?? 'Unassigned',
                    $job->engineer_comments,
                    $job->booked_by
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Add this method to your CallLogController
public function all(Request $request)
{
    $query = CallLog::with(['assignedTo', 'approver']);
    
    // Search functionality
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('customer_name', 'like', "%{$search}%")
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
    if ($request->filled('technician')) {
        $query->where('assigned_to', $request->technician);
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
    
    // Amount range filter
    if ($request->filled('min_amount')) {
        $query->where('amount_charged', '>=', $request->min_amount);
    }
    
    if ($request->filled('max_amount')) {
        $query->where('amount_charged', '<=', $request->max_amount);
    }
    
    // Sort options
    $sortField = $request->get('sort', 'date_booked');
    $sortDirection = $request->get('direction', 'desc');
    
    $allowedSorts = ['date_booked', 'customer_name', 'status', 'amount_charged', 'created_at'];
    if (in_array($sortField, $allowedSorts)) {
        $query->orderBy($sortField, $sortDirection);
    }
    
    // Pagination
    $perPage = $request->get('per_page', 25);
    $allowedPerPage = [10, 25, 50, 100];
    if (!in_array($perPage, $allowedPerPage)) {
        $perPage = 25;
    }
    
    $callLogs = $query->paginate($perPage)->withQueryString();
    
    // Get filter options
    $technicians = User::whereIn('role', ['technician', 'manager'])
        ->select(['id', 'name'])
        ->orderBy('name')
        ->get();
        
    $statuses = ['pending', 'assigned', 'in_progress', 'complete', 'cancelled'];
    $types = ['normal', 'emergency', 'maintenance', 'repair', 'installation', 'consultation'];
    
    // Statistics for the current filter
    $stats = [
        'total' => $query->count(),
        'pending' => (clone $query)->where('status', 'pending')->count(),
        'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
        'completed' => (clone $query)->where('status', 'complete')->count(),
        'total_revenue' => (clone $query)->where('status', 'complete')->sum('amount_charged'),
    ];

    return view('admin.calllogs.all', compact(
        'callLogs', 'technicians', 'statuses', 'types', 'stats'
    ));

    
}

private function applyDateFilter($query, $dateRange)
{
    $now = now();
    
    switch ($dateRange) {
        case 'today':
            $query->whereDate('date_booked', $now->toDateString());
            break;
        case 'yesterday':
            $query->whereDate('date_booked', $now->subDay()->toDateString());
            break;
        case 'this_week':
            $query->whereBetween('date_booked', [
                $now->startOfWeek()->toDateString(),
                $now->endOfWeek()->toDateString()
            ]);
            break;
        case 'last_week':
            $query->whereBetween('date_booked', [
                $now->subWeek()->startOfWeek()->toDateString(),
                $now->subWeek()->endOfWeek()->toDateString()
            ]);
            break;
        case 'this_month':
            $query->whereMonth('date_booked', $now->month)
                  ->whereYear('date_booked', $now->year);
            break;
        case 'last_month':
            $lastMonth = $now->copy()->subMonth();
            $query->whereMonth('date_booked', $lastMonth->month)
                  ->whereYear('date_booked', $lastMonth->year);
            break;
        case 'this_year':
            $query->whereYear('date_booked', $now->year);
            break;
        case 'last_year':
            $query->whereYear('date_booked', $now->subYear()->year);
            break;
    }
 }

 public function export(Request $request)
{
    try {
        // Apply the same filters as the main view
        $query = CallLog::with(['assignedTo', 'approver']);
        
        // Apply all filters from request
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('job_card', 'like', "%{$search}%")
                  ->orWhere('fault_description', 'like', "%{$search}%")
                  ->orWhere('zimra_ref', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('technician')) {
            $query->where('assigned_to', $request->technician);
        }
        
        if ($request->filled('date_range')) {
            $this->applyDateFilter($query, $request->date_range);
        }
        
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date_booked', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }
        
        // Get all filtered records
        $callLogs = $query->orderBy('date_booked', 'desc')->get();
        
        // Generate filename with timestamp
        $filename = 'job_cards_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        
        return Excel::download(new CallLogsExport($callLogs), $filename);
        
    } catch (\Exception $e) {
        return back()->with('error', 'Export failed: ' . $e->getMessage());
    }
}

    
}
