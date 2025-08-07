@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Notifications</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($notifications->where('read_at', null)->count() > 0)
                <button class="btn btn-primary" id="markAllRead">
                    <i class="fas fa-check-double me-1"></i>
                    Mark All Read ({{ $notifications->where('read_at', null)->count() }})
                </button>
            @endif
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        @foreach($notifications as $notification)
                            <div class="notification-item-full {{ $notification->read_at ? '' : 'unread' }}" 
                                 data-notification-id="{{ $notification->id }}">
                                <div class="d-flex align-items-start p-4 border-bottom">
                                    <div class="notification-icon me-3">
                                        @switch($notification->data['type'] ?? 'general')
                                            @case('ticket')
                                                <i class="fas fa-ticket-alt text-warning"></i>
                                                @break
                                            @case('job')
                                                <i class="fas fa-briefcase text-primary"></i>
                                                @break
                                            @case('system')
                                                <i class="fas fa-cog text-info"></i>
                                                @break
                                            @default
                                                <i class="fas fa-bell text-secondary"></i>
                                        @endswitch
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0 fw-bold">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </h6>
                                            <div class="d-flex align-items-center gap-2">
                                                @if(($notification->data['priority'] ?? 'normal') !== 'normal')
                                                    <span class="badge bg-{{ $notification->data['priority'] === 'high' ? 'danger' : 'warning' }}">
                                                        {{ ucfirst($notification->data['priority']) }}
                                                    </span>
                                                @endif
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        
                                        <p class="mb-2 text-muted">{{ $notification->data['message'] ?? '' }}</p>
                                        
                                        @if(isset($notification->data['job_card']))
                                            <div class="mb-2">
                                                <strong>Job:</strong> {{ $notification->data['job_card'] }}
                                                @if(isset($notification->data['customer_name']))
                                                    | <strong>Customer:</strong> {{ $notification->data['customer_name'] }}
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex gap-2 mt-3">
                                            @if(isset($notification->data['url']) || isset($notification->data['ticket_id']) || isset($notification->data['job_id']))
                                                <a href="{{ route('notifications.redirect', $notification->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    View Details
                                                </a>
                                            @endif
                                            
                                            @if(!$notification->read_at)
                                                <button class="btn btn-sm btn-outline-success mark-read-btn" 
                                                        data-notification-id="{{ $notification->id }}">
                                                    <i class="fas fa-check me-1"></i>
                                                    Mark as Read
                                                </button>
                                            @endif
                                            
                                            <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                    data-notification-id="{{ $notification->id }}">
                                                <i class="fas fa-trash me-1"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @if(!$notification->read_at)
                                        <div class="unread-indicator"></div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Pagination -->
                        <div class="p-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No notifications found</h5>
                            <p class="text-muted">You'll see new notifications here when they arrive.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .notification-item-full {
        transition: all 0.3s ease;
    }
    
    .notification-item-full.unread {
        background: rgba(5, 150, 105, 0.05);
        border-left: 4px solid var(--primary);
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--gray-100);
        font-size: 1.2rem;
    }
    
    .unread-indicator {
        width: 12px;
        height: 12px;
        background: var(--primary);
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 0.5rem;
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 0.875rem;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
        color: #6b7280;
    }
    
    .breadcrumb-item a {
        color: #059669;
        text-decoration: none;
    }
    
    .breadcrumb-item a:hover {
        text-decoration: underline;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark individual notification as read
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.notificationId;
            markAsRead(notificationId, this);
        });
    });
    
    // Mark all notifications as read
    const markAllReadBtn = document.getElementById('markAllRead');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function() {
            markAllAsRead();
        });
    }
    
    // Delete notifications
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.notificationId;
            deleteNotification(notificationId, this);
        });
    });
    
    function markAsRead(notificationId, button) {
        fetch(`{{ route('notifications.index') }}/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notificationItem = button.closest('.notification-item-full');
                notificationItem.classList.remove('unread');
                notificationItem.querySelector('.unread-indicator')?.remove();
                button.remove();
                toastr.success('Notification marked as read');
                
                // Update the mark all read button
                updateMarkAllReadButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Failed to mark notification as read');
        });
    }
    
    function markAllAsRead() {
        fetch('{{ route('notifications.mark-all-read') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove unread styling from all notifications
                document.querySelectorAll('.notification-item-full.unread').forEach(item => {
                    item.classList.remove('unread');
                    item.querySelector('.unread-indicator')?.remove();
                });
                
                // Remove all mark as read buttons
                document.querySelectorAll('.mark-read-btn').forEach(btn => btn.remove());
                
                // Hide mark all read button
                const markAllReadBtn = document.getElementById('markAllRead');
                if (markAllReadBtn) {
                    markAllReadBtn.style.display = 'none';
                }
                
                toastr.success('All notifications marked as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Failed to mark all notifications as read');
        });
    }
    
    function deleteNotification(notificationId, button) {
        if (confirm('Are you sure you want to delete this notification?')) {
            fetch(`{{ route('notifications.index') }}/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notificationItem = button.closest('.notification-item-full');
                    notificationItem.remove();
                    toastr.success('Notification deleted');
                    
                    // Update the mark all read button
                    updateMarkAllReadButton();
                    
                    // Check if no notifications left
                    if (document.querySelectorAll('.notification-item-full').length === 0) {
                        location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Failed to delete notification');
            });
        }
    }
    
    function updateMarkAllReadButton() {
        const unreadCount = document.querySelectorAll('.notification-item-full.unread').length;
        const markAllReadBtn = document.getElementById('markAllRead');
        
        if (markAllReadBtn) {
            if (unreadCount > 0) {
                markAllReadBtn.innerHTML = `<i class="fas fa-check-double me-1"></i>Mark All Read (${unreadCount})`;
                markAllReadBtn.style.display = 'block';
            } else {
                markAllReadBtn.style.display = 'none';
            }
        }
    }
});
</script>
@endpush