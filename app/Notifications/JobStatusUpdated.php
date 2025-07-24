<?php

namespace App\Notifications;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $callLog;
    public $oldStatus;
    public $newStatus;
    public $updatedBy;

    public function __construct(CallLog $callLog, string $oldStatus, string $newStatus, User $updatedBy)
    {
        $this->callLog = $callLog;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->updatedBy = $updatedBy;
    }

    public function via($notifiable)
    {
        return ['database']; // ONLY database notifications
    }

    public function toDatabase($notifiable)
    {
        $statusMessages = [
            'in_progress' => 'Work has started',
            'complete' => 'Job has been completed',
            'cancelled' => 'Job was cancelled',
            'pending' => 'Job is now pending'
        ];

        $message = $statusMessages[$this->newStatus] ?? "Status changed to {$this->newStatus}";

        $jobCard = $this->callLog->job_card ? $this->callLog->job_card : $this->callLog->id;

        return [
            'type' => 'job_status_updated',
            'title' => 'Job Status Update',
            'message' => "Job #{$jobCard}: {$message}",
            'job_id' => $this->callLog->id,
            'job_card' => $this->callLog->job_card ?? 'TBD-' . $this->callLog->id,
            'customer_name' => $this->callLog->customer_name ?? $this->callLog->company_name,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'updated_by' => $this->updatedBy->name,
            'url' => route('admin.call-logs.show', $this->callLog->id),
            'priority' => $this->newStatus === 'complete' ? 'high' : 'normal',
            'created_at' => now()
        ];
    }
}
