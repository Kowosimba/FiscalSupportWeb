@extends('layouts.tickets')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Notifications</h5>
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
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
                    <a href="{{ route('notifications.redirect', $notification->id) }}" 
                    class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'list-group-item-danger' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="fas fa-ticket-alt text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-1">
                                            {{ $notification->data['message'] ?? 'New notification' }}
                                            @if(isset($notification->data['ticket_id']))
                                                <span class="badge bg-danger ms-2">Ticket #{{ $notification->data['ticket_id'] }}</span>
                                            @endif
                                        </p>
                                        <small class="text-muted">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @if(!$notification->read_at)
                                <div class="flex-shrink-0">
                                    <span class="badge bg-danger">New</span>
                                </div>
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
