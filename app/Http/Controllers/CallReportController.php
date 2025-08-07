<?php

namespace App\Http\Controllers;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\Controller;

class CallReportController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
       
    }

    /**
     * Display the call logs reports dashboard.
     */
public function index(Request $request)
    {
        // Get filter parameters
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $engineer = $request->get('engineer');
        $status = $request->get('status');
        $type = $request->get('type');

        // Build query
        $query = CallLog::with(['loggedBy', 'handler'])
            ->whereBetween('call_time', [$dateFrom, $dateTo . ' 23:59:59']);

        if ($engineer) {
            $query->where('handled_by', $engineer);
        }

        if ($status) {
            $query->where('call_status', $status);
        }

        if ($type) {
            $query->where('call_type', $type);
        }

        $callLogs = $query->orderBy('call_time', 'desc')->get();

        // Generate statistics
        $stats = [
            'total_jobs' => $callLogs->count(),
            'completed_jobs' => $callLogs->where('call_status', 'resolved')->count(),
            'total_revenue' => $callLogs->where('call_status', 'resolved')->sum('estimated_cost'),
            'avg_completion_time' => $this->calculateAverageResolutionTime($callLogs->where('call_status', 'resolved')),
            'total_billed_hours' => $callLogs->sum('call_duration_minutes') / 60,
            'emergency_jobs' => $callLogs->where('call_type', 'emergency')->count(),
        ];

        $engineerStats = $callLogs->groupBy('handled_by')
            ->map(function ($engineerCalls) {
                return [
                    'total' => $engineerCalls->count(),
                    'completed' => $engineerCalls->where('call_status', 'resolved')->count(),
                    'in_progress' => $engineerCalls->where('call_status', 'in_progress')->count(),
                    'revenue' => $engineerCalls->where('call_status', 'resolved')->sum('estimated_cost'),
                    'billed_hours' => $engineerCalls->sum('call_duration_minutes') / 60,
                ];
            });

        $dailyStats = $callLogs->groupBy(function ($call) {
            return $call->call_time->format('Y-m-d');
        })->map(function ($dayCalls) {
            return [
                'total' => $dayCalls->count(),
                'completed' => $dayCalls->where('call_status', 'resolved')->count(),
                'in_progress' => $dayCalls->where('call_status', 'in_progress')->count(),
                'revenue' => $dayCalls->where('call_status', 'resolved')->sum('estimated_cost'),
                'billed_hours' => $dayCalls->sum('call_duration_minutes') / 60,
            ];
        });

        $jobTypeStats = $callLogs->groupBy('call_type')
            ->map(function ($typeCalls) use ($callLogs) {
                return [
                    'count' => $typeCalls->count(),
                    'percentage' => round(($typeCalls->count() / $callLogs->count()) * 100, 1),
                    'avg_hours' => $typeCalls->avg('call_duration_minutes') / 60,
                    'revenue' => $typeCalls->where('call_status', 'resolved')->sum('estimated_cost'),
                ];
            });

        $companyStats = $callLogs->groupBy('customer_name')
            ->map(function ($companyCalls) {
                return [
                    'total' => $companyCalls->count(),
                    'completed' => $companyCalls->where('call_status', 'resolved')->count(),
                    'revenue' => $companyCalls->where('call_status', 'resolved')->sum('estimated_cost'),
                    'last_service' => $companyCalls->max('call_time'),
                ];
            });

        $engineers = User::whereIn('role', ['technician', 'admin'])
            ->orderBy('name')
            ->get();

        return view('admin.call_logs.reports', compact(
            'callLogs', 'stats', 'engineerStats', 'dailyStats',
            'jobTypeStats', 'companyStats', 'engineers',
            'dateFrom', 'dateTo', 'engineer', 'status', 'type'
        ));
    }

    /**
     * Handle report generation.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:summary,detailed,handler,satisfaction,call_type,priority',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'handler' => 'nullable|exists:users,id',
            'call_status' => 'nullable|in:answered,missed,voicemail,in_progress,resolved,follow_up_required',
            'call_type' => 'nullable|in:incoming,outgoing,follow_up,emergency',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'format' => 'required|in:pdf,excel,csv'
        ]);

        try {
            $callLogs = $this->buildReportQuery($validated)->get();

            return match ($validated['format']) {
                'csv' => $this->exportToCsv($callLogs, $validated['report_type']),
                'excel' => $this->exportToExcel($callLogs, $validated['report_type']),
                'pdf' => $this->exportToPdf($callLogs, $validated['report_type']),
                default => response()->json(['error' => 'Invalid format'], 400)
            };

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Report generation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle report export.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $callLogs = CallLog::with(['loggedBy', 'handler'])
            ->whereBetween('call_time', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderBy('call_time', 'desc')
            ->get();

        return match ($format) {
            'csv' => $this->exportToCsv($callLogs, 'detailed'),
            'excel' => $this->exportToExcel($callLogs, 'detailed'),
            'pdf' => $this->exportToPdf($callLogs, 'detailed'),
            default => back()->with('error', 'Invalid export format')
        };
    }

    /**
     * Build query for reports
     */
    private function buildReportQuery(array $filters)
    {
        $query = CallLog::with(['loggedBy', 'handler'])
            ->whereBetween('call_time', [
                $filters['date_from'], 
                $filters['date_to'] . ' 23:59:59'
            ]);

        if (!empty($filters['handler'])) {
            $query->where('handled_by', $filters['handler']);
        }

        if (!empty($filters['call_status'])) {
            $query->where('call_status', $filters['call_status']);
        }

        if (!empty($filters['call_type'])) {
            $query->where('call_type', $filters['call_type']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        return $query->orderBy('call_time', 'desc');
    }

    /**
     * Generate statistics
     */
    private function generateStatistics($callLogs)
    {
        return [
            'total_calls' => $callLogs->count(),
            'answered_calls' => $callLogs->where('call_status', 'answered')->count(),
            'missed_calls' => $callLogs->where('call_status', 'missed')->count(),
            'resolved_calls' => $callLogs->where('call_status', 'resolved')->count(),
            'in_progress_calls' => $callLogs->where('call_status', 'in_progress')->count(),
            'follow_up_required' => $callLogs->where('follow_up_required', 'yes')->count(),
            'total_estimated_cost' => $callLogs->where('call_status', 'resolved')->sum('estimated_cost'),
            'total_call_minutes' => $callLogs->sum('call_duration_minutes'),
            'avg_call_duration' => $this->calculateAverageCallDuration($callLogs),
            'avg_resolution_time' => $this->calculateAverageResolutionTime($callLogs->where('call_status', 'resolved')),
            'satisfaction_avg' => $this->calculateAverageSatisfaction($callLogs),
        ];
    }

    /**
     * Generate handler statistics
     */
    private function generateHandlerStats($callLogs)
    {
        return $callLogs->groupBy('handled_by')
            ->map(function ($handlerCalls) {
                return [
                    'total' => $handlerCalls->count(),
                    'answered' => $handlerCalls->where('call_status', 'answered')->count(),
                    'resolved' => $handlerCalls->where('call_status', 'resolved')->count(),
                    'missed' => $handlerCalls->where('call_status', 'missed')->count(),
                    'estimated_revenue' => $handlerCalls->where('call_status', 'resolved')->sum('estimated_cost'),
                    'total_minutes' => $handlerCalls->sum('call_duration_minutes'),
                    'avg_satisfaction' => $handlerCalls->where('satisfaction_rating', '>', 0)->avg('satisfaction_rating'),
                ];
            });
    }

    /**
     * Generate daily statistics
     */
    private function generateDailyStats($callLogs)
    {
        return $callLogs->groupBy(function ($call) {
            return $call->call_time ? $call->call_time->format('Y-m-d') : now()->format('Y-m-d');
        })->map(function ($dayCalls) {
            return [
                'total' => $dayCalls->count(),
                'answered' => $dayCalls->where('call_status', 'answered')->count(),
                'missed' => $dayCalls->where('call_status', 'missed')->count(),
                'resolved' => $dayCalls->where('call_status', 'resolved')->count(),
                'estimated_revenue' => $dayCalls->where('call_status', 'resolved')->sum('estimated_cost'),
                'total_minutes' => $dayCalls->sum('call_duration_minutes'),
            ];
        });
    }

    /**
     * Generate call type statistics
     */
    private function generateCallTypeStats($callLogs)
    {
        if ($callLogs->isEmpty()) {
            return collect();
        }

        return $callLogs->groupBy('call_type')
            ->map(function ($typeCalls) use ($callLogs) {
                return [
                    'count' => $typeCalls->count(),
                    'percentage' => round(($typeCalls->count() / $callLogs->count()) * 100, 1),
                    'avg_duration' => $typeCalls->avg('call_duration_minutes') ?? 0,
                ];
            });
    }

    /**
     * Generate priority statistics
     */
    private function generatePriorityStats($callLogs)
    {
        if ($callLogs->isEmpty()) {
            return collect();
        }

        return $callLogs->groupBy('priority')
            ->map(function ($priorityCalls) use ($callLogs) {
                return [
                    'count' => $priorityCalls->count(),
                    'percentage' => round(($priorityCalls->count() / $callLogs->count()) * 100, 1),
                    'avg_resolution_time' => $this->calculateAverageResolutionTime($priorityCalls->where('call_status', 'resolved')),
                ];
            });
    }

    /**
     * Calculate the average call duration in minutes.
     */
    private function calculateAverageCallDuration($callLogs)
    {
        if ($callLogs->isEmpty()) {
            return 0;
        }

        $totalMinutes = $callLogs->sum('call_duration_minutes');
        return $totalMinutes > 0 ? round($totalMinutes / $callLogs->count(), 2) : 0;
    }

    /**
     * Calculate the average resolution time (in hours) for resolved calls.
     */
    private function calculateAverageResolutionTime($resolvedCalls)
    {
        if ($resolvedCalls->isEmpty()) {
            return 0;
        }

        $totalMinutes = $resolvedCalls->sum(function ($call) {
            if ($call->call_time && $call->call_completed_at) {
                return $call->call_time->diffInMinutes($call->call_completed_at);
            }
            return 0;
        });

        return $totalMinutes > 0 ? round($totalMinutes / $resolvedCalls->count() / 60, 2) : 0;
    }

    /**
     * Calculate average satisfaction rating.
     */
    private function calculateAverageSatisfaction($callLogs)
    {
        $ratedCalls = $callLogs->where('satisfaction_rating', '>', 0);
        
        if ($ratedCalls->isEmpty()) {
            return 0;
        }

        return round($ratedCalls->avg('satisfaction_rating'), 2);
    }

    /**
     * Export to CSV format.
     */
    private function exportToCsv($callLogs, $reportType)
    {
        $filename = 'call_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($callLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Call Reference', 'Customer Name', 'Phone', 'Subject', 'Call Type',
                'Status', 'Priority', 'Call Time', 'Duration (min)', 'Handler',
                'Estimated Cost', 'Satisfaction Rating', 'Follow-up Required'
            ]);

            // CSV Data
            foreach ($callLogs as $call) {
                fputcsv($file, [
                    $call->call_reference ?? 'N/A',
                    $call->customer_name ?? 'N/A',
                    $call->customer_phone ?? 'N/A',
                    $call->caller_subject ?? 'N/A',
                    ucfirst($call->call_type ?? 'N/A'),
                    ucfirst($call->call_status ?? 'N/A'),
                    ucfirst($call->priority ?? 'N/A'),
                    $call->call_time ? $call->call_time->format('Y-m-d H:i:s') : 'N/A',
                    $call->call_duration_minutes ?? 0,
                    optional($call->handler)->name ?? 'Unassigned',
                    $call->estimated_cost ?? 0,
                    $call->satisfaction_rating ?? 'N/A',
                    ucfirst($call->follow_up_required ?? 'no')
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export to Excel format (placeholder).
     */
    private function exportToExcel($callLogs, $reportType)
    {
        // TODO: Implement Excel export using Laravel Excel package
        return response()->json(['message' => 'Excel export functionality to be implemented']);
    }

    /**
     * Export to PDF format (placeholder).
     */
    private function exportToPdf($callLogs, $reportType)
    {
        // TODO: Implement PDF export using DomPDF or similar
        return response()->json(['message' => 'PDF export functionality to be implemented']);
    }
}