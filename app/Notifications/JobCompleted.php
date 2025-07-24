<?php

namespace App\Notifications;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public $callLog;
    public $completedBy;

    public function __construct(CallLog $callLog, User $completedBy)
    {
        $this->callLog = $callLog;
        $this->completedBy = $completedBy;
    }

    public function via($notifiable)
    {
        return ['database']; // ONLY database notifications
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'job_completed',
            'title' => 'Job Completed',
            'message' => 'Job #' . ($this->callLog->job_card ?? $this->callLog->id) . ' for ' . ($this->callLog->customer_name ?? $this->callLog->company_name) . ' has been completed',
            'job_id' => $this->callLog->id,
            'job_card' => $this->callLog->job_card ?? 'TBD-' . $this->callLog->id,
            'customer_name' => $this->callLog->customer_name ?? $this->callLog->company_name,
            'amount' => $this->callLog->amount_charged ?? 0,
            'billed_hours' => $this->callLog->billed_hours,
            'completed_by' => $this->completedBy->name,
            'completion_date' => $this->callLog->date_resolved ?? now(),
            'url' => route('admin.call-logs.show', $this->callLog->id),
            'priority' => 'high',
            'created_at' => now()
        ];
    }
}
