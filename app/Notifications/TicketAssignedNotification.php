<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class TicketAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
        
        // Reduced logging - only log in debug mode
        if (config('app.debug')) {
            Log::info('Ticket assigned notification created', [
                'ticket_id' => $this->ticket->id,
                'ticket_subject' => $this->ticket->subject,
            ]);
        }
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $ticketUrl = $this->getTicketUrl();

        return (new MailMessage)
            ->subject('New Ticket Assigned: #' . $this->ticket->id . ' - ' . $this->ticket->subject)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been assigned a new support ticket.')
            ->line('**Ticket Details:**')
            ->line('• Ticket ID: #' . $this->ticket->id)
            ->line('• Company: ' . ($this->ticket->company_name ?? 'Not specified'))
            ->line('• Service: ' . ($this->ticket->service ?? 'Not specified'))
            ->line('• Priority: ' . ucfirst($this->ticket->priority ?? 'low'))
            ->line('• Subject: ' . $this->ticket->subject)
            ->action('View Ticket Details', $ticketUrl)
            ->line('Please review and respond to this ticket as soon as possible.')
            ->line('Thank you for using our support system!');
    }

    /**
     * Get the array representation of the notification for database storage.
     * Keep this lean and focused on essential data for the notification system.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'ticket_assigned',
            'ticket_id' => $this->ticket->id,
            'title' => 'New Ticket Assigned',
            'message' => 'You have been assigned ticket #' . $this->ticket->id . ': ' . $this->ticket->subject,
            'action_text' => 'View Ticket',
            'action_url' => $this->getTicketUrl(),
            'ticket' => [
                'id' => $this->ticket->id,
                'subject' => $this->ticket->subject,
                'company' => $this->ticket->company_name,
                'priority' => $this->ticket->priority ?? 'low',
                'status' => $this->ticket->status ?? 'pending',
            ],
            'icon' => 'fas fa-ticket-alt',
            'color' => $this->getPriorityColor($this->ticket->priority ?? 'low'),
        ];
    }

    /**
     * Get the ticket URL with streamlined route checking.
     */
    private function getTicketUrl(): string
    {
        // Primary route to check
        $primaryRoute = 'admin.tickets.show';
        
        if (Route::has($primaryRoute)) {
            try {
                return route($primaryRoute, $this->ticket->id);
            } catch (\Exception $e) {
                Log::warning('Failed to generate primary ticket route', [
                    'route' => $primaryRoute,
                    'ticket_id' => $this->ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Fallback routes
        $fallbackRoutes = ['tickets.show', 'admin.ticket.show', 'support.tickets.show'];
        
        foreach ($fallbackRoutes as $routeName) {
            if (Route::has($routeName)) {
                try {
                    return route($routeName, $this->ticket->id);
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        // Final fallback
        return config('app.url') . '/admin/tickets/' . $this->ticket->id;
    }

    /**
     * Get priority color for UI styling.
     */
    private function getPriorityColor(string $priority): string
    {
        return match($priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Handle notification failure.
     */
    public function failed(\Exception $exception): void
    {
        Log::error('Ticket assigned notification failed', [
            'ticket_id' => $this->ticket->id,
            'error' => $exception->getMessage(),
            'trace' => config('app.debug') ? $exception->getTraceAsString() : null,
        ]);
    }
}