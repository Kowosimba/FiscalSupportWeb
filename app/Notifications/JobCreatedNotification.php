<?php

namespace App\Notifications;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobCreatedNotification extends Notification
{
    use Queueable;

    protected $callLog;
    protected $createdBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(CallLog $callLog, User $createdBy)
    {
        $this->callLog = $callLog;
        $this->createdBy = $createdBy;
    }

    /**
     * Get the notification's delivery channels.
     * Only database channel - no email notifications
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'job_created',
            'title' => 'New Job Card Created',
            'message' => "A new job card has been created for {$this->callLog->customer_name}. Job requires assignment to an engineer.",
            'job_id' => $this->callLog->id,
            'job_card' => $this->callLog->job_card ?? 'TBD-' . $this->callLog->id,
            'customer_name' => $this->callLog->customer_name,
            'customer_email' => $this->callLog->customer_email,
            'fault_description' => $this->callLog->fault_description,
            'amount_charged' => $this->callLog->amount_charged,
            'currency' => $this->callLog->currency,
            'job_type' => $this->callLog->type,
            'date_booked' => $this->callLog->date_booked ? $this->callLog->date_booked->format('Y-m-d') : null,
            'created_by' => $this->createdBy->name,
            'created_by_id' => $this->createdBy->id,
            'priority' => $this->callLog->type === 'emergency' ? 'high' : 'normal',
            'requires_assignment' => empty($this->callLog->assigned_to),
            'url' => route('admin.call-logs.show', $this->callLog->id),
            'action_url' => empty($this->callLog->assigned_to) ? route('admin.call-logs.unassigned') : route('admin.call-logs.show', $this->callLog->id),
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}