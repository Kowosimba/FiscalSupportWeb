<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class NewTicketForAssignmentNotification extends Notification
{
    use Queueable;

    protected $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $ticket = $this->ticket;
        
        return (new MailMessage)
            ->subject('New Support Ticket Requires Assignment - #' . $ticket->id)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new support ticket has been submitted and requires technician assignment.')
            ->line('')
            ->line('**Ticket Details:**')
            ->line('**Ticket ID:** #' . $ticket->id)
            ->line('**Subject:** ' . $ticket->subject)
            ->line('**Company:** ' . ($ticket->company_name ?? 'Not specified'))
            ->line('**Email:** ' . $ticket->email)
            ->line('**Priority:** ' . ucfirst($ticket->priority))
            ->line('**Status:** ' . ucfirst($ticket->status))
            ->line('**Submitted:** ' . $ticket->created_at->format('M j, Y g:i A'))
            ->line('')
            ->line('**Message Preview:**')
            ->line('"' . \Str::limit($ticket->message, 150) . '"')
            ->line('')
            ->action('Assign Technician', url('/admin/tickets/unassigned'))
            ->line('Please log into the admin panel to assign this ticket to an appropriate technician.')
            ->line('Time-sensitive tickets should be assigned as soon as possible to maintain our service level agreements.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_subject' => $this->ticket->subject,
            'company_name' => $this->ticket->company_name,
            'priority' => $this->ticket->priority,
            'created_at' => $this->ticket->created_at,
        ];
    }
}