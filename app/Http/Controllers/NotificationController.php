<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Check if the notification belongs to the current user
        if ($notification->notifiable_id !== Auth::id()) {
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
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        
        // Mark as read
        $notification->markAsRead();
        
        // Determine the correct URL based on notification data
        if (isset($notification->data['ticket_id'])) {
            $ticketId = $notification->data['ticket_id'];
            return redirect()->route('admin.tickets.show', $ticketId);
        }
        
        // Handle job notifications
        if (isset($notification->data['job_id'])) {
            $jobId = $notification->data['job_id'];
            return redirect()->route('admin.call-logs.show', $jobId);
        }
        
        // Fallback to the stored URL or notifications index
        $url = $notification->data['url'] ?? route('notifications.index');
        return redirect($url);
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
     * Get recent notifications for dropdown
     */
    public function getRecent()
    {
        $notifications = Auth::user()->notifications()
                             ->take(10)
                             ->get()
                             ->map(function ($notification) {
                                 return [
                                     'id' => $notification->id,
                                     'type' => $notification->data['type'] ?? 'general',
                                     'title' => $notification->data['title'] ?? 'Notification',
                                     'message' => $notification->data['message'] ?? '',
                                     'url' => route('notifications.redirect', $notification->id),
                                     'read_at' => $notification->read_at,
                                     'created_at' => $notification->created_at->diffForHumans(),
                                     'priority' => $notification->data['priority'] ?? 'normal',
                                     'job_card' => $notification->data['job_card'] ?? null,
                                     'customer_name' => $notification->data['customer_name'] ?? null,
                                 ];
                             });

        return response()->json($notifications);
    }

    /**
     * Delete a notification
     */
    public function destroy(DatabaseNotification $notification)
    {
        // Check if the notification belongs to the current user
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $notification->delete();
        
        return response()->json([
            'success' => true, 
            'message' => 'Notification deleted'
        ]);
    }
}