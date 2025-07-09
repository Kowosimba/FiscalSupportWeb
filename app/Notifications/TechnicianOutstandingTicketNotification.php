<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TechnicianOutstandingTicketNotification extends Notification
{
    use Queueable;

    /**
     * The ticket instance.
     *
     * @var mixed
     */
    protected $ticket;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $ticket
     * @return void
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
{
    return ['mail'];
}

public function toMail($notifiable)
{
    $ticket = $this->ticket;
    return (new \Illuminate\Notifications\Messages\MailMessage)
        ->subject('Outstanding Ticket Reminder: #' . $ticket->id)
        ->greeting('Hello ' . $notifiable->name . ',')
        ->line('You have an outstanding ticket assigned to you for more than 24 hours.')
        ->line('Ticket Subject: ' . $ticket->subject)
        ->action('View Ticket', url(route('tickets.view', $ticket->id)))
        ->line('The priority has been escalated to HIGH.');
}


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
