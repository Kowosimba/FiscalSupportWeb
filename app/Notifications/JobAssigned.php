<?php

namespace App\Notifications;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public $callLog;
    public $assignedBy;

    public function __construct(CallLog $callLog, User $assignedBy)
    {
        $this->callLog = $callLog;
        $this->assignedBy = $assignedBy;
    }

    public function via($notifiable)
    {
        return ['database']; // ONLY database notifications
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'job_assigned',
            'title' => 'New Job Assignment',
            'message' => 'You have been assigned job #' . ($this->callLog->job_card ?? $this->callLog->id) . ' for ' . ($this->callLog->customer_name ?? $this->callLog->company_name),
            'job_id' => $this->callLog->id,
            'job_card' => $this->callLog->job_card ?? 'TBD-' . $this->callLog->id,
            'customer_name' => $this->callLog->customer_name ?? $this->callLog->company_name,
            'job_type' => $this->callLog->type ?? 'normal',
            'amount' => $this->callLog->amount_charged ?? 0,
            'assigned_by' => $this->assignedBy->name,
            'url' => route('admin.call-logs.show', $this->callLog->id),
            'priority' => $this->callLog->type === 'emergency' ? 'high' : 'normal',
            'created_at' => now()
        ];
    }
}
