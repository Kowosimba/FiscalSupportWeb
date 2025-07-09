<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
{
    return ['mail', 'database'];
}

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Ticket Assigned: ' . $this->ticket->subject)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been assigned a new ticket:')
            ->line('Ticket ID: ' . $this->ticket->id)
            ->line('Company: ' . $this->ticket->company_name)
            ->line('Service: ' . $this->ticket->service)
            ->line('Priority: ' . ucfirst($this->ticket->priority))
            ->action('View Ticket', route('tickets.show', $this->ticket->id))
            ->line('Thank you for using our helpdesk system!');
    }

    public function toArray($notifiable)
{
    return [
        'type' => 'ticket_assigned',
        'ticket_id' => $this->ticket->id,
        'subject' => $this->ticket->subject,
        'message' => 'You have been assigned a new ticket: ' . $this->ticket->subject,
        'url' => route('tickets.show', $this->ticket->id),
        'priority' => $this->ticket->priority,
        'company' => $this->ticket->company_name,
        'created_at' => now()->toDateTimeString()
    ];
}

    
}