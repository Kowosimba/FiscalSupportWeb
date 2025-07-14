<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\CallLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CallLogController extends Controller
{
    public function __construct()
    {
        // Apply authentication to all methods
        $this->middleware('auth');
        
        // Only admin and accounts can create/store job cards
        $this->middleware('role:admin,accounts')->only(['create', 'store']);
        
        // Only admin can delete and access reports
        $this->middleware('role:admin')->only(['destroy', 'reports', 'export']);
        
        // Engineers can update their assigned jobs
        $this->middleware('role:admin,accounts,engineer')->only(['edit', 'update', 'assign', 'updateStatus', 'complete']);
    }

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

    return view('admin.calllogs.index', compact('callLogs', 'stats', 'technicians'));
}


    public function create()
    {
        return view('admin.calllogs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'job_card' => 'nullable|string|unique:call_logs,job_card',
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

        CallLog::create($data);

        return redirect()->route('admin.call-logs.index')
            ->with('success', 'Job card created successfully.');
    }

    public function show(CallLog $callLog)
    {
        return view('admin.calllogs.show', compact('callLog'));
    }

    public function edit(CallLog $callLog)
    {
        // Check if user can edit this job card
        if (Auth::user()->role === 'engineer' && $callLog->engineer !== Auth::user()->name) {
            return redirect()->back()->with('error', 'You can only edit job cards assigned to you.');
        }
        
        if (!in_array(Auth::user()->role, ['admin', 'accounts', 'engineer'])) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        
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

        return redirect()->route('admin.call-logs.index')
            ->with('success', 'Job card updated successfully.');
    }

    public function destroy(CallLog $callLog)
    {
        $callLog->delete();
        
        return redirect()->route('admin.call-logs.index')
            ->with('success', 'Job card deleted successfully.');
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
        
        return view('admin.calllogs.index', compact('callLogs', 'stats'));
    }

   public function myJobs(Request $request)
{
    // Get current user
    $user = Auth::user();
    
    // Check if user is a technician
    if (!$user->hasRole('technician')) {
        return redirect()->route('admin.call-logs.index')
            ->with('error', 'Access denied. Only technicians can view their jobs.');
    }
    
    // Query jobs assigned to this user
    $query = CallLog::where('assigned_to', $user->id);
    
    // Apply filters if provided
    if ($status = $request->input('status')) {
        $query->where('status', $status);
    }
    
    if ($type = $request->input('type')) {
        $query->where('type', $type);
    }
    
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
        }
    }
    
    $callLogs = $query->orderBy('date_booked', 'desc')->paginate(15)->withQueryString();
    
    // Calculate statistics for this technician
    $stats = [
        'total' => CallLog::where('assigned_to', $user->id)->count(),
        'assigned' => CallLog::where('assigned_to', $user->id)->where('status', 'assigned')->count(),
        'in_progress' => CallLog::where('assigned_to', $user->id)->where('status', 'in_progress')->count(),
        'complete' => CallLog::where('assigned_to', $user->id)
            ->where('status', 'complete')
            ->whereMonth('date_resolved', now()->month)
            ->count(),
    ];
    
    return view('admin.calllogs.my-jobs', compact('callLogs', 'stats'));
}


    public function inProgress()
    {
        $callLogs = CallLog::where('status', 'in_progress')
            ->orderBy('date_booked', 'desc')
            ->paginate(15);
        
        return view('admin.calllogs.in-progress', compact('callLogs'));
    }

    public function completed()
    {
        $callLogs = CallLog::where('status', 'complete')
            ->orderBy('date_resolved', 'desc')
            ->paginate(15);
        
        return view('admin.calllogs.completed', compact('callLogs'));
    }

   public function pending()
{
    $callLogs = CallLog::where('status', 'pending')->paginate(15);
    
    // Calculate statistics using Collection methods
    $stats = [
        'total_pending' => $callLogs->total(),
        'unassigned' => $callLogs->getCollection()->filter(function($job) {
            return empty($job->engineer);
        })->count(),
        'emergency' => $callLogs->getCollection()->where('type', 'emergency')->count(),
        'overdue' => $callLogs->getCollection()->filter(function($job) {
            return \Carbon\Carbon::parse($job->date_booked)->diffInHours() > 24;
        })->count(),
    ];
    
    return view('admin.calllogs.pending', compact('callLogs', 'stats'));
}

public function unassigned(Request $request)
{
    // Query for jobs with no assigned technician
    $query = CallLog::whereNull('assigned_to');
    
    // Apply filters
    if ($type = $request->input('type')) {
        $query->where('type', $type);
    }
    
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
    
    $callLogs = $query->orderBy('date_booked', 'desc')->paginate(15)->withQueryString();
    
    return view('admin.calllogs.unassigned', compact('callLogs'));
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
            'engineer' => 'required|string|max:255'
        ]);

        $callLog->update([
            'engineer' => $request->engineer,
            'status' => 'assigned'
        ]);

        return response()->json(['success' => true, 'message' => 'Job assigned successfully.']);
    }

  public function updateStatus(Request $request, CallLog $callLog)
{
    $request->validate([
        'status' => 'required|in:pending,assigned,in_progress,complete,cancelled'
    ]);

    if ($request->status === 'complete') {
        $requiredFields = [
            'job_card', 'company_name', 'fault_description', 'type',
            'amount_charged', 'approved_by', 'customer_name', 'customer_email'
        ];

        foreach ($requiredFields as $field) {
            if (empty($callLog->$field)) {
                return response()->json([
                    'success' => false,
                    'message' => 'All required fields must be filled before marking as complete.'
                ], 422);
            }
        }
    }

    $updateData = ['status' => $request->status];

    if ($request->status === 'complete') {
        $updateData['date_resolved'] = now()->format('Y-m-d');
    }

    $callLog->update($updateData);

    return response()->json(['success' => true, 'message' => 'Job status updated successfully.']);
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

        // Engineer statistics
        $engineerStats = $callLogs->groupBy('engineer')->map(function ($jobs, $engineer) {
            return [
                'total' => $jobs->count(),
                'completed' => $jobs->where('status', 'complete')->count(),
                'in_progress' => $jobs->where('status', 'in_progress')->count(),
                'revenue' => $jobs->where('status', 'complete')->sum('amount_charged'),
                'billed_hours' => $jobs->sum('billed_hours'),
            ];
        });

        // Daily statistics
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

        // Job type statistics
        $jobTypeStats = $callLogs->groupBy('type')->map(function ($jobs, $type) use ($callLogs) {
            return [
                'count' => $jobs->count(),
                'percentage' => round(($jobs->count() / $callLogs->count()) * 100, 1),
                'avg_hours' => $jobs->avg('billed_hours') ?: 0,
                'revenue' => $jobs->where('status', 'complete')->sum('amount_charged'),
            ];
        });

        // Company statistics
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

    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $callLogs = CallLog::whereBetween('date_booked', [$dateFrom, $dateTo])
            ->orderBy('date_booked', 'desc')
            ->get();

        if ($format === 'csv') {
            return $this->exportToCsv($callLogs);
        }

        return redirect()->back()->with('error', 'Invalid export format');
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
}
