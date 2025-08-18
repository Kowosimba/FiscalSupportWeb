<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NotificationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a paginated list of all notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(DatabaseNotification $notification)
    {
        $userId = Auth::id();

        // Check if the notification belongs to the current user
        if ($notification->notifiable_id !== $userId) {
            Log::warning('Unauthorized notification access attempt', [
                'user_id' => $userId,
                'notification_id' => $notification->id,
                'notification_owner' => $notification->notifiable_id
            ]);
            abort(403, 'Unauthorized');
        }

        $notification->markAsRead();
        
        return response()->json([
            'success' => true, 
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return response()->json([
            'success' => true, 
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Redirect to the appropriate page based on notification type
     */
    public function redirect($notificationId)
    {
        $userId = Auth::id();

        try {
            $notification = Auth::user()->notifications()->findOrFail($notificationId);
            
            // Mark as read
            $notification->markAsRead();
            
            // Determine the correct URL based on notification data and type
            $redirectUrl = $this->determineRedirectUrl($notification);
            
            return redirect($redirectUrl);
            
        } catch (\Exception $e) {
            Log::error('Error in notification redirect', [
                'user_id' => $userId,
                'notification_id' => $notificationId,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('notifications.index')->with('error', 'Notification not found or inaccessible.');
        }
    }

    /**
     * Determine redirect URL based on notification type and data
     */
    private function determineRedirectUrl($notification): string
    {
        $data = $notification->data;
        $type = $notification->type;

        // Handle ticket notifications
        if (str_contains($type, 'TicketAssigned') || isset($data['ticket_id']) || $data['type'] === 'ticket_assigned') {
            $ticketId = $data['ticket_id'] ?? $data['ticket']['id'] ?? null;
            if ($ticketId) {
                return route('admin.tickets.show', $ticketId);
            }
        }

        // Handle job/call log notifications
        if (isset($data['job_id'])) {
            return route('admin.call-logs.show', $data['job_id']);
        }

        // Check for stored action URL (from our improved notification)
        if (isset($data['action_url'])) {
            return $data['action_url'];
        }

        // Legacy URL field
        if (isset($data['url'])) {
            return $data['url'];
        }

        // Fallback to notifications index
        return route('notifications.index');
    }

    /**
     * Get unread notification count for API/AJAX requests
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications for dropdown/tickets panel
     */
    public function getRecent()
    {
        try {
            $rawNotifications = Auth::user()->notifications()->take(10)->get();

            $notifications = $rawNotifications->map(function ($notification) {
                return $this->mapNotificationForFrontend($notification);
            });

            return response()->json($notifications);
            
        } catch (\Exception $e) {
            Log::error('Error retrieving recent notifications', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([], 500);
        }
    }

    /**
     * Get ticket-specific notifications for tickets panel
     */
    public function getTicketNotifications()
    {
        try {
            // Get only ticket-related notifications
            $ticketNotifications = Auth::user()->notifications()
                ->where(function($query) {
                    $query->where('type', 'like', '%Ticket%')
                          ->orWhereJsonContains('data->type', 'ticket_assigned')
                          ->orWhereJsonContains('data->ticket_id', '!=', null);
                })
                ->take(5)
                ->get();

            $notifications = $ticketNotifications->map(function ($notification) {
                return $this->mapTicketNotificationForPanel($notification);
            });

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'count' => $notifications->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error retrieving ticket notifications', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'notifications' => [],
                'count' => 0
            ], 500);
        }
    }

    /**
     * Map notification data for frontend display
     */
    private function mapNotificationForFrontend($notification): array
    {
        $data = $notification->data;
        $type = $this->getNotificationType($notification);

        return [
            'id' => $notification->id,
            'type' => $type,
            'title' => $data['title'] ?? $this->getDefaultTitle($type),
            'message' => $data['message'] ?? '',
            'url' => route('notifications.redirect', $notification->id),
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at->diffForHumans(),
            'priority' => $data['priority'] ?? $data['ticket']['priority'] ?? 'normal',
            'icon' => $data['icon'] ?? $this->getDefaultIcon($type),
            'color' => $data['color'] ?? $this->getDefaultColor($type),
            'is_unread' => is_null($notification->read_at),
            // Additional context data
            'ticket_id' => $data['ticket_id'] ?? $data['ticket']['id'] ?? null,
            'job_card' => $data['job_card'] ?? null,
            'customer_name' => $data['customer_name'] ?? $data['ticket']['company'] ?? null,
        ];
    }

    /**
     * Map ticket notification specifically for tickets panel
     */
    private function mapTicketNotificationForPanel($notification): array
    {
        $data = $notification->data;
        $ticketData = $data['ticket'] ?? [];

        return [
            'id' => $notification->id,
            'ticket_id' => $data['ticket_id'] ?? $ticketData['id'] ?? null,
            'title' => $data['title'] ?? 'Ticket Notification',
            'message' => $data['message'] ?? '',
            'subject' => $ticketData['subject'] ?? 'No subject',
            'company' => $ticketData['company'] ?? 'Not specified',
            'priority' => $ticketData['priority'] ?? 'low',
            'status' => $ticketData['status'] ?? 'pending',
            'url' => route('notifications.redirect', $notification->id),
            'created_at' => $notification->created_at->diffForHumans(),
            'is_unread' => is_null($notification->read_at),
            'icon' => $data['icon'] ?? 'fas fa-ticket-alt',
            'color' => $data['color'] ?? $this->getPriorityColor($ticketData['priority'] ?? 'low'),
        ];
    }

    /**
     * Get notification type from notification object
     */
    private function getNotificationType($notification): string
    {
        if (isset($notification->data['type'])) {
            return $notification->data['type'];
        }

        $type = $notification->type;
        if (str_contains($type, 'TicketAssigned')) {
            return 'ticket_assigned';
        }
        if (str_contains($type, 'Ticket')) {
            return 'ticket';
        }
        if (str_contains($type, 'Job')) {
            return 'job';
        }

        return 'general';
    }

    /**
     * Get default title based on notification type
     */
    private function getDefaultTitle(string $type): string
    {
        return match($type) {
            'ticket_assigned' => 'New Ticket Assigned',
            'ticket' => 'Ticket Notification',
            'job' => 'Job Notification',
            default => 'Notification'
        };
    }

    /**
     * Get default icon based on notification type
     */
    private function getDefaultIcon(string $type): string
    {
        return match($type) {
            'ticket_assigned', 'ticket' => 'fas fa-ticket-alt',
            'job' => 'fas fa-briefcase',
            default => 'fas fa-bell'
        };
    }

    /**
     * Get default color based on notification type
     */
    private function getDefaultColor(string $type): string
    {
        return match($type) {
            'ticket_assigned' => 'primary',
            'ticket' => 'info',
            'job' => 'success',
            default => 'secondary'
        };
    }

    /**
     * Get priority color for tickets
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
     * Delete a notification
     */
    public function destroy(DatabaseNotification $notification)
    {
        $userId = Auth::id();

        // Check if the notification belongs to the current user
        if ($notification->notifiable_id !== $userId) {
            Log::warning('Unauthorized notification deletion attempt', [
                'user_id' => $userId,
                'notification_id' => $notification->id,
                'notification_owner' => $notification->notifiable_id
            ]);
            abort(403, 'Unauthorized');
        }

        $notification->delete();
        
        return response()->json([
            'success' => true, 
            'message' => 'Notification deleted'
        ]);
    }
}