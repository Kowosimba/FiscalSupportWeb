<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerTicketResolvedNotification extends Notification
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
        ->subject('Your Ticket #' . $ticket->id . ' has been resolved')
        ->greeting('Hello ' . $ticket->company_name . ',')
        ->line('Your support ticket has been marked as resolved.')
        ->action('Reopen Ticket', url(route('tickets.reopen', $ticket->id)))
        ->line('If your issue is not resolved, you can reopen the ticket using the link above.');
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
