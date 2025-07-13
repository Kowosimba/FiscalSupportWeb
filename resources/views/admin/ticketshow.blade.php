@extends('layouts.tickets')

@section('ticket-content')
    {{-- Page Header --}}
    <div class="page-header mb-3">
        <div class="header-content">
            <h3 class="page-title">
                <i class="fa fa-ticket-alt me-2"></i>
                Ticket Details
            </h3>
            <p class="page-subtitle">View and manage support ticket #{{ $ticket->id }}</p>
        </div>
        <div class="header-stats">
            <div class="stat-pill">
                <i class="fa fa-clock me-1"></i>
                <span>{{ $ticket->created_at->diffForHumans() }}</span>
            </div>
            <a href="{{ route('admin.tickets.mine') }}" class="btn btn-outline ms-3">
                <i class="fas fa-arrow-left me-2"></i> Back to Tickets
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Ticket Details Card -->
            <div class="tickets-table-card mb-4">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fa fa-info-circle me-2"></i>
                        Ticket Information
                    </div>
                    <div class="status-badge-container">
                        <span class="status-badge status-{{ $ticket->status }}">
                            @if($ticket->status === 'pending')
                                <i class="fa fa-hourglass-half me-1"></i>
                            @elseif($ticket->status === 'in_progress')
                                <i class="fa fa-spinner me-1"></i>
                            @else
                                <i class="fa fa-check-circle me-1"></i>
                            @endif
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                        <span class="priority-badge priority-{{ $ticket->priority ?? 'low' }}">
                            @if($ticket->priority === 'high')
                                <i class="fa fa-exclamation-triangle me-1"></i>
                            @elseif($ticket->priority === 'medium')
                                <i class="fa fa-minus-circle me-1"></i>
                            @else
                                <i class="fa fa-circle me-1"></i>
                            @endif
                            {{ ucfirst($ticket->priority ?? 'low') }}
                        </span>
                    </div>
                </div>
                <div class="table-container p-3">
                    <h4 class="ticket-subject">{{ $ticket->subject }}</h4>
                    
                    <div class="ticket-meta mb-3">
                        <div class="meta-item">
                            <i class="fa fa-building me-1"></i>
                            {{ $ticket->company_name ?? 'No company specified' }}
                        </div>
                        <div class="meta-item">
                            <i class="fa fa-clock me-1"></i>
                            Created: {{ $ticket->created_at->format('M j, Y g:i A') }}
                        </div>
                        <div class="meta-item">
                            <i class="fa fa-sync-alt me-1"></i>
                            Updated: {{ $ticket->updated_at->diffForHumans() }}
                        </div>
                    </div>

                    <div class="message-content mb-3">
                        {!! nl2br(e($ticket->message)) !!}
                    </div>
                    
                    @if($ticket->attachment)
                        <div class="attachment-box">
                            <i class="fa fa-paperclip me-2"></i>
                            <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="text-link">
                                Download Attachment
                            </a>
                        </div>
                    @endif

                    @if(auth()->id() === $ticket->assigned_to)
                        <form action="{{ route('admin.tickets.updateStatusPriority', $ticket->id) }}" method="POST" class="mt-4">
                            @csrf
                            @method('PATCH')
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <label for="priority" class="form-label">Priority</label>
                                    <select name="priority" id="priority" class="enhanced-select">
                                        <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="enhanced-select">
                                        <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Update</button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Comments Section -->
            <div class="tickets-table-card">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fa fa-comments me-2"></i>
                        Discussion
                    </div>
                    <div class="comment-count">
                        {{ $ticket->comments->count() }} {{ Str::plural('comment', $ticket->comments->count()) }}
                    </div>
                </div>
                <div class="table-container p-3">
                    @forelse($ticket->comments as $comment)
                        <div class="comment py-3">
                            <div class="comment-header mb-2">
                                <div class="comment-author">
                                    <i class="fa fa-user-circle me-2"></i>
                                    {{ $comment->user->name ?? 'System' }}
                                </div>
                                <div class="comment-time">
                                    {{ $comment->created_at->format('M j, Y g:i A') }}
                                    <span class="time-ago">({{ $comment->created_at->diffForHumans() }})</span>
                                </div>
                            </div>
                            <div class="comment-body">
                                {{ $comment->body }}
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="comment-divider">
                        @endif
                    @empty
                        <div class="empty-comments">
                            <i class="fa fa-comment-slash"></i>
                            <p>No comments yet. Be the first to add one!</p>
                        </div>
                    @endforelse
                    
                    <form action="{{ route('admin.tickets.addComment', $ticket->id) }}" method="POST" class="comment-form mt-4">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="comment-body" class="form-label">Add Comment</label>
                            <textarea name="body" id="comment-body" class="enhanced-input" rows="3" placeholder="Type your comment here..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane me-2"></i> Post Comment
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <div class="tickets-table-card mb-4">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fa fa-user me-2"></i>
                        Customer Details
                    </div>
                </div>
                <div class="table-container p-3">
                    <div class="info-group">
                        <div class="info-label">Company</div>
                        <div class="info-value">
                            <i class="fa fa-building me-1"></i>
                            {{ $ticket->company_name ?? 'Not specified' }}
                        </div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <i class="fa fa-envelope me-1"></i>
                            @if($ticket->email)
                                <a href="mailto:{{ $ticket->email }}" class="text-link">{{ $ticket->email }}</a>
                            @else
                                Not specified
                            @endif
                        </div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Contact</div>
                        <div class="info-value">
                            <i class="fa fa-phone me-1"></i>
                            {{ $ticket->contact_details ?? 'Not specified' }}
                        </div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Technician</div>
                        <div class="info-value">
                            @if($ticket->assigned_to_user)
                                <i class="fa fa-user-shield me-1"></i>
                                {{ $ticket->assigned_to_user->name }}
                            @else
                                <span class="unassigned-badge">
                                    <i class="fa fa-user-times me-1"></i>
                                    Unassigned
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            @if(auth()->user()->can('assign tickets') && !$ticket->assigned_to)
                <div class="tickets-table-card">
                    <div class="table-header">
                        <div class="table-title">
                            <i class="fa fa-user-cog me-2"></i>
                            Assign Technician
                        </div>
                    </div>


                    {{-- Add SweetAlert2 CDN in your layout or this view --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        timer: 2500,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

                    <div class="table-container p-3">
                        <form method="POST" action="{{ route('admin.tickets.assign', $ticket->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label for="assigned_to" class="form-label">Select Technician</label>
                                <select name="assigned_to" id="assigned_to" class="enhanced-select" required>
                                    <option value="" disabled selected>Select technician...</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-user-plus me-2"></i> Assign
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Activity Log section has been removed! --}}
        </div>
    </div>

   
@endsection
