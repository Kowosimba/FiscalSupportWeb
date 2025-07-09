@extends('layouts.tickets')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Notifications</h5>
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary">
                Mark All as Read
            </button>
        </form>
    </div>
    <div class="card-body">
        @if($notifications->isEmpty())
            <p class="text-muted">No notifications found.</p>
        @else
            <div class="list-group">
                @foreach($notifications as $notification)
                    <a href="{{ $notification->data['url'] ?? '#' }}" 
                    class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1">
                                    {{ $notification->data['message'] ?? 'New notification' }}
                                    @if(isset($notification->data['ticket_id']))
                                        (Ticket #{{ $notification->data['ticket_id'] }})
                                    @endif
                                </p>
                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-decoration-none">
                                        <i class="fas fa-check"></i> Mark as read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection