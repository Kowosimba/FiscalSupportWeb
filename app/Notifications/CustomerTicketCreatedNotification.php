<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerTicketCreatedNotification extends Notification
{
    use Queueable;

    protected $ticket;

    /**
     * Create a new notification instance.
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
        ->subject('Your Support Ticket #' . $ticket->id . ' has been received')
        ->greeting('Hello ' . $ticket->company_name . ',')
        ->line('Your ticket has been submitted successfully. Ticket ID: ' . $ticket->id)
        ->line('A technician will be in touch to assist you shortly.')
        ->line('Thank you for contacting support!');
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
