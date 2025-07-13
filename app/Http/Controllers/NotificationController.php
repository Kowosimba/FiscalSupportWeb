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
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get notifications paginated (10 per page)
        $notifications = Auth::user()->notifications;

        // Manually paginate the Eloquent Collection
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $pagedNotifications = $notifications->forPage($currentPage, $perPage);
        $notifications = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedNotifications,
            $notifications->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a single notification as read.
     *
     * @param  string  $id  Notification ID
     * @return \Illuminate\Http\RedirectResponse
     */
     public function markAsRead(DatabaseNotification $notification)
    {
        $this->authorize('update', $notification);
        $notification->markAsRead();
        return back()->with('success', 'Notification marked as read');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read');
    }


    public function redirect($notificationId)
{
    $notification = auth::user()->notifications()->findOrFail($notificationId);
    
    // Mark as readw
    $notification->markAsRead();
    
    // Determine the correct URL based on notification data
    if (isset($notification->data['ticket_id'])) {
        $ticketId = $notification->data['ticket_id'];
        return redirect()->route('admin.tickets.show', $ticketId);
    }
    
    // Fallback to the stored URL or notifications index
    $url = $notification->data['url'] ?? route('notifications.index');
    return redirect($url);
}

}
