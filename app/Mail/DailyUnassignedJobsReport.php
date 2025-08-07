<?php

namespace App\Mail;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class DailyUnassignedJobsReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $unassignedCount;
    public $emergencyCount;
    public $overdueCount;
    public $totalPendingValue;
    public $manager;
    public $recentJobs;
    public $reportDate;
    public $priorityBreakdown;
    public $typeBreakdown;

    public function __construct(User $manager)
    {
        $this->manager = $manager;
        $this->reportDate = Carbon::now()->format('l, F j, Y');
        
        // Get unassigned jobs statistics
        $this->calculateStatistics();
        
        // Get recent unassigned jobs (last 3 days)
        $this->recentJobs = CallLog::whereNull('assigned_to')
            ->whereNotIn('status', ['complete', 'cancelled'])
            ->where('created_at', '>=', Carbon::now()->subDays(3))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'customer_name' => $job->customer_name,
                    'type' => ucfirst($job->type),
                    'amount' => number_format($job->amount_charged, 2),
                    'currency' => $job->currency,
                    'date_booked' => Carbon::parse($job->date_booked)->format('M j'),
                    'days_pending' => Carbon::parse($job->created_at)->diffInDays(Carbon::now()),
                    'is_overdue' => Carbon::parse($job->date_booked)->lt(Carbon::today()),
                    'is_emergency' => $job->type === 'emergency'
                ];
            });
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->unassignedCount > 0 
            ? "⚠️ Daily Report: {$this->unassignedCount} Unassigned Jobs Require Attention"
            : "✅ Daily Report: All Jobs Assigned";

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.daily-unassigned-report',
            with: [
                'dashboardUrl' => route('admin.call-logs.unassigned'),
                'allJobsUrl' => route('admin.call-logs.all'),
                'createJobUrl' => route('admin.call-logs.create'),
                'companyName' => config('app.name', 'FiscalTech Solutions'),
                'reportType' => $this->unassignedCount > 0 ? 'warning' : 'success'
            ]
        );
    }

    /**
     * Calculate all statistics for the report
     */
    private function calculateStatistics(): void
    {
        $baseQuery = CallLog::whereNull('assigned_to')
            ->whereNotIn('status', ['complete', 'cancelled']);

        $this->unassignedCount = $baseQuery->count();
        
        $this->emergencyCount = (clone $baseQuery)
            ->where('type', 'emergency')
            ->count();
            
        $this->overdueCount = (clone $baseQuery)
            ->where('date_booked', '<', Carbon::today())
            ->count();
            
        $this->totalPendingValue = (clone $baseQuery)
            ->sum('amount_charged');

        // Priority breakdown
        $this->priorityBreakdown = [
            'High' => (clone $baseQuery)->where('type', 'emergency')->count(),
            'Normal' => (clone $baseQuery)->whereNotIn('type', ['emergency'])->count(),
        ];

        // Type breakdown
        $this->typeBreakdown = (clone $baseQuery)
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->map(function ($count, $type) {
                return [
                    'type' => ucfirst($type),
                    'count' => $count
                ];
            })
            ->toArray();
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
