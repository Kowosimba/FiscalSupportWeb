<?php

namespace App\Notifications;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class JobAssigned extends Notification
{
    use Queueable;

    protected $callLog;
    protected $assignedBy;

    public function __construct(CallLog $callLog, User $assignedBy)
    {
        $this->callLog = $callLog;
        $this->assignedBy = $assignedBy;
        
        Log::info('JobAssignedNotification constructed', [
            'job_id' => $callLog->id,
            'assigned_by' => $assignedBy->id,
            'timestamp' => '2025-08-14 13:27:28'
        ]);
    }

    public function via($notifiable)
    {
        Log::info('JobAssignedNotification via method called', [
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'timestamp' => '2025-08-14 13:27:28'
        ]);
        
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $data = [
            'type' => 'job_assigned',
            'title' => 'New Job Assignment',
            'message' => "You have been assigned to job card {$this->getJobCardNumber()} for customer {$this->callLog->customer_name}",
            'job_id' => $this->callLog->id,
            'job_card' => $this->getJobCardNumber(),
            'customer_name' => $this->callLog->customer_name,
            'assigned_by' => $this->assignedBy->name,
            'priority' => $this->callLog->type === 'emergency' ? 'high' : 'normal',
            'url' => route('admin.call-logs.show', $this->callLog->id),
            'timestamp' => '2025-08-14 13:27:28',
        ];
        
        Log::info('JobAssignedNotification toDatabase method called', [
            'notifiable_id' => $notifiable->id,
            'data' => $data,
            'timestamp' => '2025-08-14 13:27:28'
        ]);
        
        return $data;
    }

    private function getJobCardNumber(): string
    {
        return $this->callLog->job_card ?? 'TBD-' . $this->callLog->id;
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}