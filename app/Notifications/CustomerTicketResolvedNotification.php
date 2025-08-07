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
            ->greeting('Hello ' . ($ticket->company_name ?? 'Valued Customer') . ',')
            ->line('We are pleased to inform you that your support ticket has been marked as resolved.')
            ->line('')
            ->line('**Ticket Details:**')
            ->line('**Ticket ID:** #' . $ticket->id)
            ->line('**Subject:** ' . $ticket->subject)
            ->line('**Resolved Date:** ' . now()->format('M j, Y g:i A'))
            ->line('')
            ->line('We hope this resolution meets your expectations and resolves your issue completely.')
            ->line('If the resolution was not satisfactory or if you encounter any additional issues, please feel free to submit a new support ticket through our system.')
            ->line('')
            ->line('Thank you for using our support services. We appreciate your business and look forward to serving you again.');
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
            'resolved_at' => now(),
        ];
    }
}