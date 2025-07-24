<?php

namespace App\Mail;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
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

    public function __construct(User $manager)
    {
        $this->manager = $manager;
        
        // Get unassigned jobs statistics
        $this->unassignedCount = CallLog::whereNull('assigned_to')
            ->whereNotIn('status', ['complete', 'cancelled'])
            ->count();
            
        $this->emergencyCount = CallLog::whereNull('assigned_to')
            ->where('type', 'emergency')
            ->whereNotIn('status', ['complete', 'cancelled'])
            ->count();
            
        $this->overdueCount = CallLog::whereNull('assigned_to')
            ->where('date_booked', '<', Carbon::today())
            ->whereNotIn('status', ['complete', 'cancelled'])
            ->count();
            
        $this->totalPendingValue = CallLog::whereNull('assigned_to')
            ->whereNotIn('status', ['complete', 'cancelled'])
            ->sum('amount_charged');
            
        // Get recent unassigned jobs (last 3 days)
        $this->recentJobs = CallLog::whereNull('assigned_to')
            ->whereNotIn('status', ['complete', 'cancelled'])
            ->where('created_at', '>=', Carbon::now()->subDays(3))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function build()
    {
        $subject = $this->unassignedCount > 0 
            ? "⚠️ Daily Report: {$this->unassignedCount} Unassigned Jobs Require Attention"
            : "✅ Daily Report: All Jobs Assigned";

        return $this->subject($subject)
                    ->view('emails.daily-unassigned-report')
                    ->with([
                        'dashboardUrl' => route('admin.call-logs.unassigned'),
                        'allJobsUrl' => route('admin.call-logs.all'),
                        'companyName' => config('app.name', 'FiscalTech Solutions')
                    ]);
    }
}
