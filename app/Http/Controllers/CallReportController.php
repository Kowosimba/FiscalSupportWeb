<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;


class CallReportController extends Controller
{

        protected $middleware = ['auth'];
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $technician = $request->get('technician');
        $status = $request->get('status');

        // Build query
        $query = Job::with(['approvedBy', 'assignedTo'])
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        if ($technician) {
            $query->where('assigned_to', $technician);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $jobs = $query->orderBy('created_at', 'desc')->get();

        // Generate statistics
        $stats = [
            'total_calls' => $jobs->count(),
            'completed_calls' => $jobs->where('status', 'completed')->count(),
            'pending_calls' => $jobs->where('status', 'pending')->count(),
            'in_progress_calls' => $jobs->where('status', 'in_progress')->count(),
            'total_revenue' => $jobs->where('status', 'completed')->sum('amount_charged'),
            'total_hours' => $jobs->where('status', 'completed')->sum('billed_hours'),
            'avg_resolution_time' => $this->calculateAverageResolutionTime($jobs->where('status', 'completed')),
        ];

        // Performance by technician
        $technicianStats = $jobs->groupBy('assigned_to')
            ->map(function ($techJobs) {
                return [
                    'total' => $techJobs->count(),
                    'completed' => $techJobs->where('status', 'completed')->count(),
                    'revenue' => $techJobs->where('status', 'completed')->sum('amount_charged'),
                    'hours' => $techJobs->where('status', 'completed')->sum('billed_hours'),
                ];
            });

        // Daily statistics
        $dailyStats = $jobs->groupBy(function ($job) {
            return $job->created_at->format('Y-m-d');
        })->map(function ($dayJobs) {
            return [
                'total' => $dayJobs->count(),
                'completed' => $dayJobs->where('status', 'completed')->count(),
                'revenue' => $dayJobs->where('status', 'completed')->sum('amount_charged'),
            ];
        });

        $technicians = User::where('role', 'technician')
                          ->orWhere('role', 'manager')
                          ->orderBy('name')
                          ->get();

        return view('admin.calllogs.reports', compact(
            'jobs', 'stats', 'technicianStats', 'dailyStats', 'technicians',
            'dateFrom', 'dateTo', 'technician', 'status'
        ));
    }

    /**
     * Handle report generation (stub for custom logic).
     */
    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:summary,detailed,technician,revenue',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'technician' => 'nullable|exists:users,id',
            'format' => 'required|in:pdf,excel,csv'
        ]);

        // Generate report based on type and format (implement as needed)
        return response()->json(['success' => true, 'message' => 'Report generated successfully']);
    }

    /**
     * Handle report export (stub for custom logic).
     */
    public function export(Request $request)
    {
        // Implement export logic as needed
        return response()->json(['success' => true, 'message' => 'Export completed']);
    }

    /**
     * Calculate the average resolution time (in hours) for completed jobs.
     */
    private function calculateAverageResolutionTime($completedJobs)
    {
        if ($completedJobs->isEmpty()) {
            return 0;
        }

        $totalMinutes = $completedJobs->sum(function ($job) {
            if ($job->created_at && $job->completed_at) {
                return $job->created_at->diffInMinutes($job->completed_at);
            }
            return 0;
        });

        return round($totalMinutes / $completedJobs->count() / 60, 2); // Return in hours
    }
}