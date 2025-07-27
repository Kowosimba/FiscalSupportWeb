@extends('layouts.app')

@section('title', 'Ticket Details - ' . $ticket->subject)

@section('content')
<div class="dashboard-container">
    {{-- Page Header --}}
    <div class="dashboard-header mb-4">
        <div class="header-content">
            <h1 class="dashboard-title mb-0">
                <i class="fas fa-ticket-alt me-2"></i>
                Ticket #{{ $ticket->id }} Details
            </h1>
            <p class="dashboard-subtitle mb-0">View and manage support ticket</p>
            <div class="period-indicator mt-1">
                <span class="badge bg-primary">Created: {{ $ticket->created_at->format('M j, Y') }}</span>
                <small class="text-muted ms-2">Last updated: {{ $ticket->updated_at->diffForHumans() }}</small>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.tickets.mine') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Tickets
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            {{-- Ticket Details Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i> Ticket Information
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="status-badge status-{{ $ticket->status }}">
                            @if($ticket->status === 'pending')
                                <i class="fas fa-clock me-1"></i>
                            @elseif($ticket->status === 'in_progress')
                                <i class="fas fa-spinner fa-spin me-1"></i>
                            @elseif($ticket->status === 'resolved')
                                <i class="fas fa-check-circle me-1"></i>
                            @else
                                <i class="fas fa-info-circle me-1"></i>
                            @endif
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                        <span class="priority-badge priority-{{ $ticket->priority ?? 'low' }}">
                            @if($ticket->priority === 'high')
                                <i class="fas fa-exclamation-triangle me-1"></i>
                            @elseif($ticket->priority === 'medium')
                                <i class="fas fa-minus-circle me-1"></i>
                            @else
                                <i class="fas fa-circle me-1"></i>
                            @endif
                            {{ ucfirst($ticket->priority ?? 'low') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="fw-bold mb-3">{{ $ticket->subject }}</h4>

                    <div class="ticket-meta d-flex flex-wrap gap-3 mb-3 text-muted small">
                        <div><i class="fas fa-building me-1"></i> {{ $ticket->company_name ?? 'No company specified' }}</div>
                        <div><i class="fas fa-calendar me-1"></i> Created: {{ $ticket->created_at->format('M j, Y g:i A') }}</div>
                    </div>

                    <div class="message-content mb-4" style="white-space: pre-line;">
                        {!! e($ticket->message) !!}
                    </div>

                    @if($ticket->attachment)
                        <div class="attachment-box mb-4">
                            <i class="fas fa-paperclip me-2"></i>
                            <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="text-primary text-decoration-none fw-semibold">
                                Download Attachment
                            </a>
                        </div>
                    @endif

                    @if(auth()->id() === $ticket->assigned_to)
                        <form action="{{ route('admin.tickets.updateStatusPriority', $ticket->id) }}" method="POST" class="mt-4">
                            @csrf
                            @method('PATCH')
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label for="priority" class="form-label fw-semibold">Priority</label>
                                    <select name="priority" id="priority" class="form-select">
                                        <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label for="status" class="form-label fw-semibold">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Engineer Comments Section --}}
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-comments me-2"></i> Engineer Comments ({{ $ticket->comments->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($ticket->comments as $comment)
                        <div class="comment mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar small">
                                        @if($comment->user)
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    <span class="fw-semibold">{{ $comment->user->name ?? 'System' }}</span>
                                </div>
                                <small class="text-muted" title="{{ $comment->created_at->format('M j, Y g:i A') }}">
                                    {{ $comment->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div class="comment-body text-dark" style="white-space: pre-line;">
                                {{ $comment->body }}
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-comment-slash fa-2x mb-3"></i>
                            <p class="mb-0">No engineer comments yet. Be the first to add one!</p>
                        </div>
                    @endforelse

                    {{-- Add Comment Form --}}
                    <form action="{{ route('admin.tickets.addComment', $ticket->id) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="mb-3">
                            <label for="comment-body" class="form-label fw-semibold">Add Comment</label>
                            <textarea 
                                name="body" 
                                id="comment-body" 
                                class="form-control" 
                                rows="3" 
                                placeholder="Type your comment here..." 
                                required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i> Post Comment
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar Column --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i> Customer Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="fw-semibold text-muted mb-1">Company</div>
                        <div><i class="fas fa-building me-1"></i> {{ $ticket->company_name ?? 'Not specified' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-semibold text-muted mb-1">Email</div>
                        <div>
                            <i class="fas fa-envelope me-1"></i>
                            @if($ticket->email)
                                <a href="mailto:{{ $ticket->email }}" class="text-primary text-decoration-none">{{ $ticket->email }}</a>
                            @else
                                Not specified
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-semibold text-muted mb-1">Contact</div>
                        <div><i class="fas fa-phone me-1"></i> {{ $ticket->contact_details ?? 'Not specified' }}</div>
                    </div>
                    <div>
                        <div class="fw-semibold text-muted mb-1">Technician</div>
                        <div>
                            @if($ticket->assigned_to_user)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar small">
                                        {{ strtoupper(substr($ticket->assigned_to_user->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $ticket->assigned_to_user->name }}</span>
                                </div>
                            @else
                                <span class="badge bg-danger text-white">
                                    <i class="fas fa-user-times me-1"></i> Unassigned
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->can('assign tickets') && !$ticket->assigned_to)
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-cog me-2"></i> Assign Technician
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.tickets.assign', $ticket->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label fw-semibold">Select Technician</label>
                                <select name="assigned_to" id="assigned_to" class="form-select" required>
                                    <option value="" disabled selected>Select technician...</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus me-2"></i> Assign
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Custom styles to match the dashboard layout */
    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
    
    .user-avatar.small {
        width: 24px;
        height: 24px;
        font-size: 0.8rem;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-pending {
        background: var(--warning);
        color: var(--white);
    }
    
    .status-in_progress {
        background: var(--info);
        color: var(--white);
    }
    
    .status-resolved {
        background: var(--success);
        color: var(--white);
    }
    
    .priority-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .priority-high {
        background: var(--danger);
        color: var(--white);
    }
    
    .priority-medium {
        background: var(--warning);
        color: var(--white);
    }
    
    .priority-low {
        background: var(--gray-500);
        color: var(--white);
    }
    
    .message-content {
        line-height: 1.6;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: var(--border-radius);
    }
    
    .attachment-box {
        padding: 0.75rem;
        background: var(--gray-50);
        border-radius: var(--border-radius);
        display: inline-flex;
        align-items: center;
    }
    
    .comment {
        padding: 1rem;
        background: var(--gray-50);
        border-radius: var(--border-radius);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions with toast notifications
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
            }
        });
    });
    
    // Show toast notifications for session messages
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
    
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas ${getToastIcon(type)} me-2"></i>
                <span>${message}</span>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 16px;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideIn 0.3s ease;
            background: ${getToastColor(type)};
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 4000);
    }

    function getToastIcon(type) {
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };
        return icons[type] || icons['info'];
    }

    function getToastColor(type) {
        const colors = {
            'success': 'linear-gradient(135deg, var(--success), #047857)',
            'error': 'linear-gradient(135deg, var(--danger), #B91C1C)',
            'warning': 'linear-gradient(135deg, var(--warning), #D97706)',
            'info': 'linear-gradient(135deg, var(--info), #0284C7)'
        };
        return colors[type] || colors['info'];
    }
});

// Add slideIn animation styles
if (!document.getElementById('toastStyles')) {
    const style = document.createElement('style');
    style.id = 'toastStyles';
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .toast-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            opacity: 0.8;
        }
        .toast-close:hover {
            opacity: 1;
            background: rgba(255,255,255,0.1);
        }
    `;
    document.head.appendChild(style);
}
</script>
@endpush
@endsection