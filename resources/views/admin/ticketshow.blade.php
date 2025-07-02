@extends('layouts.tickets')

@section('ticket-content')
<style>
    /* Simplified and focused CSS for this view */
    :root {
        --primary: #2a9d8f;
        --primary-light: rgba(42, 157, 143, 0.1);
        --secondary: #6c757d;
        --success: #2ecc71;
        --warning: #e9c46a;
        --danger: #e76f51;
        --info: #04490c;
        --light-bg: #f8f9fa;
        --dark-text: #212529;
        --medium-text: #495057;
    }

    .ticket-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .ticket-header {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid var(--primary);
    }

    .ticket-id {
        color: var(--primary);
        font-weight: 600;
    }

    .ticket-subject {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 10px 0;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .badge-priority {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .timestamp {
        font-size: 0.85rem;
        color: var(--medium-text);
    }

    .ticket-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .card-title {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .message-content {
        background: rgba(42, 157, 143, 0.05);
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .attachment-box {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: rgba(42, 157, 143, 0.05);
        border-radius: 8px;
        margin-top: 15px;
    }

    .info-box {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .info-label {
        font-size: 0.85rem;
        color: var(--medium-text);
        margin-bottom: 5px;
    }

    .info-value {
        font-weight: 500;
    }

    .comment {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .comment-author {
        font-weight: 600;
    }

    .comment-time {
        font-size: 0.8rem;
        color: var(--medium-text);
    }

    @media (max-width: 768px) {
        .ticket-subject {
            font-size: 1.3rem;
        }
        
        .ticket-container {
            padding: 15px;
        }
    }

    .customer-column {
    max-width: 350px;
}

.info-box {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 12px;
}

.info-box:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 0.85rem;
}

.info-value {
    font-weight: 600;
    color: #212529;
}

</style>

<div class="ticket-container">
    <!-- Back button -->
    <div class="mb-3">
        <a href="{{ route('tickets.mine') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to My Tickets
        </a>
    </div>

    <!-- Ticket Header -->
    <div class="ticket-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <span class="ticket-id">#{{ $ticket->id }}</span>
                <h1 class="ticket-subject">{{ $ticket->subject }}</h1>
                <div class="d-flex gap-2 mb-2">
                    <span class="badge-status 
                        @if($ticket->status === 'pending') bg-warning text-dark
                        @elseif($ticket->status === 'in_progress') bg-info text-white
                        @elseif($ticket->status === 'resolved') bg-success text-white
                        @else bg-secondary text-white @endif">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                    <span class="badge-priority 
                        @if($ticket->priority === 'high') bg-danger text-white
                        @elseif($ticket->priority === 'medium') bg-warning text-dark
                        @else bg-success text-white @endif">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </div>
            </div>
            <div class="text-md-end">
                <div class="timestamp">
                    <i class="far fa-clock me-1"></i>
                    Created: {{ $ticket->created_at->format('M d, Y H:i') }}
                </div>
                <div class="timestamp">
                    <i class="fas fa-sync-alt me-1"></i>
                    Updated: {{ $ticket->updated_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Ticket Details -->
            <div class="ticket-card">
                <h5 class="card-title">
                    <i class="fas fa-info-circle"></i> Ticket Details
                </h5>
                <div class="message-content">
                    {{ $ticket->message }}
                </div>
                
                @if($ticket->attachment)
                    <div class="attachment-box">
                        <i class="fas fa-paperclip"></i>
                        <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank">
                            Download Attachment
                        </a>
                    </div>
                @endif

                @if(auth()->id() === $ticket->assigned_to)
                    <form action="{{ route('tickets.updateStatusPriority', $ticket->id) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <div class="row g-2">
                            <div class="col-md-5">
                                <label for="priority" class="form-label">Priority</label>
                                <select name="priority" id="priority" class="form-select">
                                    <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100">Update</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Comments Section -->
            <div class="ticket-card">
                <h5 class="card-title">
                    <i class="fas fa-comments"></i> Comments
                </h5>
                
                @forelse($ticket->comments as $comment)
                    <div class="comment">
                        <div class="d-flex justify-content-between">
                            <span class="comment-author">{{ $comment->user->name ?? 'Unknown User' }}</span>
                            <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mb-0 mt-1">{{ $comment->body }}</p>
                    </div>
                @empty
                    <p class="text-muted">No comments yet.</p>
                @endforelse
                
                <form action="{{ route('tickets.addComment', $ticket->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="mb-2">
                        <textarea name="body" class="form-control" rows="3" placeholder="Add a comment..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post Comment</button>
                </form>
            </div>
        </div>

        <!-- Sidebar Column -->
        <<div class="customer-column p-3 bg-light rounded shadow-sm">
    <h5 class="mb-4 text-primary"><i class="fas fa-user"></i> Customer Information</h5>

    <div class="info-box d-flex align-items-center mb-3">
        <i class="fas fa-building fa-lg text-secondary me-3"></i>
        <div>
            <div class="info-label fw-bold text-muted">Company Name</div>
            <div class="info-value fs-5">{{ $ticket->company_name ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="info-box d-flex align-items-center mb-3">
        <i class="fas fa-envelope fa-lg text-secondary me-3"></i>
        <div>
            <div class="info-label fw-bold text-muted">Email</div>
            <div class="info-value fs-5">{{ $ticket->email ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="info-box d-flex align-items-center mb-3">
        <i class="fas fa-phone fa-lg text-secondary me-3"></i>
        <div>
            <div class="info-label fw-bold text-muted">Contact Details</div>
            <div class="info-value fs-5">{{ $ticket->contact_details ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="info-box d-flex align-items-center mb-3">
        <i class="fas fa-user-cog fa-lg text-secondary me-3"></i>
        <div>
            <div class="info-label fw-bold text-muted">Assigned Technician</div>
            <div class="info-value fs-5">
                @if($ticket->assigned_to_user)
                    {{ $ticket->assigned_to_user->name }}
                @else
                    <span class="text-muted fst-italic">Not assigned</span>
                @endif
            </div>
        </div>
    </div>
</div>


            
            @if(auth()->user()->can('assign tickets') && !$ticket->assigned_to)
                <div class="info-box">
                    <form method="POST" action="{{ route('tickets.assign', $ticket->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="assigned_to" class="form-label">Assign Technician</label>
                            <select name="assigned_to" id="assigned_to" class="form-select" required>
                                <option value="" disabled selected>Select technician</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Assign</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection