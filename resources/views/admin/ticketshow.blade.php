@extends('layouts.app')

@section('title', 'Ticket Details - ' . $ticket->subject)

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-ticket-alt me-2"></i>
                Ticket #{{ $ticket->id }} Details
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary me-2">
                    <i class="fas fa-hashtag me-1"></i>
                    {{ $ticket->id }}
                </span>
                <span class="badge bg-info me-2">
                    <i class="fas fa-calendar me-1"></i>
                    {{ $ticket->created_at->format('M j, Y') }}
                </span>
                <small class="text-muted">Last updated: {{ $ticket->updated_at->diffForHumans() }}</small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.tickets.mine') }}'" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Tickets
            </button>
        </div>
    </div>

    {{-- Status Overview --}}
    <div class="status-overview mb-2">
        <div class="status-item">
            @php
                $statusConfig = [
                    'pending' => ['class' => 'status-pending', 'icon' => 'clock', 'label' => 'Pending'],
                    'in_progress' => ['class' => 'status-progress', 'icon' => 'cog', 'label' => 'In Progress'],
                    'resolved' => ['class' => 'status-complete', 'icon' => 'check-circle', 'label' => 'Resolved'],
                    'closed' => ['class' => 'status-cancelled', 'icon' => 'times-circle', 'label' => 'Closed']
                ];
                $config = $statusConfig[$ticket->status] ?? ['class' => 'status-default', 'icon' => 'circle', 'label' => ucfirst(str_replace('_', ' ', $ticket->status))];
            @endphp
            <span class="status-badge {{ $config['class'] }}">
                <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                {{ $config['label'] }}
            </span>
        </div>
        
        <div class="status-item">
            @php
                $priorityConfig = [
                    'high' => ['class' => 'emergency', 'icon' => 'exclamation-triangle', 'label' => 'High Priority'],
                    'medium' => ['class' => 'normal', 'icon' => 'minus-circle', 'label' => 'Medium Priority'],
                    'low' => ['class' => 'normal', 'icon' => 'circle', 'label' => 'Low Priority']
                ];
                $pConfig = $priorityConfig[$ticket->priority ?? 'low'] ?? ['class' => 'normal', 'icon' => 'circle', 'label' => ucfirst($ticket->priority ?? 'low')];
            @endphp
            <span class="type-badge {{ $pConfig['class'] }}">
                <i class="fas fa-{{ $pConfig['icon'] }} me-1"></i>
                {{ $pConfig['label'] }}
            </span>
        </div>
        
        <div class="status-item">
            <span class="amount-badge">
                <i class="fas fa-comments me-1"></i>
                {{ $ticket->comments->count() }} Comments
            </span>
        </div>
    </div>

    <div class="row g-3">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Ticket Details Card --}}
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>
                            Ticket Information
                        </h4>
                        <p class="card-subtitle mb-0">
                            Support request details and basic information
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="info-section">
                                <div class="info-grid">
                                    <div class="info-item highlighted">
                                        <div class="info-label">Subject</div>
                                        <div class="info-value">
                                            <h4 class="fw-bold mb-0">{{ $ticket->subject }}</h4>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <div class="info-label">Company</div>
                                                <div class="info-value">
                                                    @if($ticket->company_name)
                                                        <span class="reference-badge">
                                                            <i class="fas fa-building me-1"></i>
                                                            {{ $ticket->company_name }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">No company specified</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <div class="info-label">Created Date</div>
                                                <div class="info-value">
                                                    <span class="date-badge">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $ticket->created_at->format('M j, Y g:i A') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($ticket->attachment)
                                        <div class="info-item">
                                            <div class="info-label">Attachment</div>
                                            <div class="info-value">
                                                <div class="attachment-box">
                                                    <i class="fas fa-paperclip me-2"></i>
                                                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="contact-link fw-semibold">
                                                        Download Attachment
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Message Content Card --}}
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-envelope-open me-2"></i>
                            Message Content
                        </h4>
                        <p class="card-subtitle mb-0">
                            Original support request message
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <div class="fault-description">
                        <p class="fault-text">{!! e($ticket->message) !!}</p>
                    </div>
                </div>
            </div>

            {{-- Status Update Form (only if assigned to current user) --}}
@php
    $currentUserId = (int)auth()->id();
    $assignedToId = (int)$ticket->assigned_to;
    $isAssigned = $currentUserId === $assignedToId || 
                  auth()->id() == $ticket->assigned_to ||
                  (auth()->user()->hasRole('admin') ?? false);
@endphp

@if($isAssigned)
    <div class="content-card mb-3">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-edit me-2"></i>
                    Update Ticket
                </h4>
                <p class="card-subtitle mb-0">
                    Modify ticket status and priority
                </p>
            </div>
        </div>
        
        <div class="content-card-body" style="padding: 1rem;">
            <form action="{{ route('admin.tickets.updateStatusPriority', $ticket->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <div class="info-item">
                            <div class="info-label">Priority</div>
                            <select name="priority" id="priority" class="form-select">
                                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="info-item">
                            <div class="info-label">Status</div>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="action-btn primary">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif

            {{-- Engineer Comments Section --}}
            <div class="content-card">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-comments me-2"></i>
                            Engineer Comments
                        </h4>
                        <p class="card-subtitle mb-0">
                            Technical notes and communication history ({{ $ticket->comments->count() }})
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    @forelse($ticket->comments as $comment)
                        <div class="engineer-comments {{ !$loop->last ? 'mb-3' : '' }}">
                            <div class="comment-box">
                                <div class="comment-header">
                                    <div class="comment-author">
                                        <div class="author-avatar">
                                            @if($comment->user)
                                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                            @else
                                                <i class="fas fa-user-cog"></i>
                                            @endif
                                        </div>
                                        <div class="author-details">
                                            <div class="author-name">{{ $comment->user->name ?? 'System' }}</div>
                                            <div class="author-role">Support Engineer</div>
                                        </div>
                                    </div>
                                    <div class="comment-date">
                                        <i class="fas fa-clock me-1"></i>
                                        <small title="{{ $comment->created_at->format('M j, Y g:i A') }}">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    {{ $comment->body }}
                                </div>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="my-3" style="border-color: var(--gray-200);">
                        @endif
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-comment-slash fa-2x mb-3"></i>
                            <p class="mb-0">No engineer comments yet. Be the first to add one!</p>
                        </div>
                    @endforelse

                    {{-- Add Comment Form --}}
                    <form action="{{ route('admin.tickets.addComment', $ticket->id) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="info-item">
                            <div class="info-label">Add Comment</div>
                            <textarea 
                                name="body" 
                                id="comment-body" 
                                class="form-control" 
                                rows="3" 
                                placeholder="Type your comment here..." 
                                required></textarea>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="action-btn primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                Post Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Customer Details Card --}}
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-user me-2"></i>
                            Customer Details
                        </h4>
                        <p class="card-subtitle mb-0">
                            Contact information and company details
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <div class="assignment-info">
                        <div class="assignment-item">
                            <div class="assignment-label">Company</div>
                            <div class="user-card">
                                <div class="user-avatar booker">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="user-details">
                                    <div class="user-name">{{ $ticket->company_name ?? 'Not specified' }}</div>
                                    <div class="user-role">Organization</div>
                                </div>
                            </div>
                        </div>

                        <div class="assignment-item">
                            <div class="assignment-label">Email</div>
                            @if($ticket->email)
                                <div class="user-card">
                                    <div class="user-avatar approver">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $ticket->email }}</div>
                                        <div class="user-role">Contact Email</div>
                                        <div class="user-contact">
                                            <a href="mailto:{{ $ticket->email }}" class="contact-link">
                                                <i class="fas fa-paper-plane me-1"></i>
                                                Send Email
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="unassigned-notice">
                                    <i class="fas fa-envelope-slash me-2"></i>
                                    Not specified
                                </div>
                            @endif
                        </div>

                        <div class="assignment-item">
                            <div class="assignment-label">Contact</div>
                            @if($ticket->contact_details)
                                <div class="user-card">
                                    <div class="user-avatar engineer">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $ticket->contact_details }}</div>
                                        <div class="user-role">Phone Details</div>
                                    </div>
                                </div>
                            @else
                                <div class="unassigned-notice">
                                    <i class="fas fa-phone-slash me-2"></i>
                                    Not specified
                                </div>
                            @endif
                        </div>

                        <div class="assignment-item">
                            <div class="assignment-label">Technician</div>
                            @php
                                // Try multiple possible relationship names
                                $assignedTechnician = null;
                                if (isset($ticket->assigned_to_user) && $ticket->assigned_to_user) {
                                    $assignedTechnician = $ticket->assigned_to_user;
                                } elseif (isset($ticket->assignedTo) && $ticket->assignedTo) {
                                    $assignedTechnician = $ticket->assignedTo;
                                } elseif (isset($ticket->assignedUser) && $ticket->assignedUser) {
                                    $assignedTechnician = $ticket->assignedUser;
                                } elseif ($ticket->assigned_to) {
                                    // If we have assigned_to ID but no relationship loaded, try to get user
                                    $assignedTechnician = \App\Models\User::find($ticket->assigned_to);
                                }
                            @endphp
                            
                            @if($assignedTechnician)
                                <div class="user-card">
                                    <div class="user-avatar engineer">
                                        {{ strtoupper(substr($assignedTechnician->name, 0, 1)) }}
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $assignedTechnician->name }}</div>
                                        <div class="user-role">Assigned Technician</div>
                                        @if($assignedTechnician->email)
                                            <div class="user-contact">
                                                <a href="mailto:{{ $assignedTechnician->email }}" class="contact-link">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    Contact Technician
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @elseif($ticket->assigned_to)
                                <div class="user-card">
                                    <div class="user-avatar engineer">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">Technician ID: {{ $ticket->assigned_to }}</div>
                                        <div class="user-role">Assigned (User details not loaded)</div>
                                    </div>
                                </div>
                            @else
                                <div class="unassigned-notice">
                                    <i class="fas fa-user-times me-2"></i>
                                    Unassigned
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Assign Technician Card (if applicable) --}}
            @if(auth()->user()->can('assign tickets') && !$ticket->assigned_to)
                <div class="content-card mb-3">
                    <div class="content-card-header">
                        <div class="header-content">
                            <h4 class="card-title">
                                <i class="fas fa-user-cog me-2"></i>
                                Assign Technician
                            </h4>
                            <p class="card-subtitle mb-0">
                                Assign this ticket to a support technician
                            </p>
                        </div>
                    </div>
                    
                    <div class="content-card-body" style="padding: 1rem;">
                        <form method="POST" action="{{ route('admin.tickets.assign', $ticket->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="info-item">
                                <div class="info-label">Select Technician</div>
                                <select name="assigned_to" id="assigned_to" class="form-select" required>
                                    <option value="" disabled selected>Select technician...</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="action-btn primary w-100">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Assign
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Quick Actions Card --}}
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-bolt me-2"></i>
                            Quick Actions
                        </h4>
                        <p class="card-subtitle mb-0">
                            Common ticket management actions
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <div class="action-buttons">
                        @if($ticket->email)
                            <button class="action-btn info w-100 mb-2" onclick="window.location.href='mailto:{{ $ticket->email }}'">
                                <i class="fas fa-envelope me-2"></i>
                                Email Customer
                            </button>
                        @endif
                        
                        @if($ticket->contact_details)
                            <button class="action-btn success w-100 mb-2" onclick="window.location.href='tel:{{ $ticket->contact_details }}'">
                                <i class="fas fa-phone me-2"></i>
                                Call Customer
                            </button>
                        @endif
                    
                        
                        @if($ticket->attachment)
                            <button class="action-btn outline w-100" onclick="window.open('{{ asset('storage/' . $ticket->attachment) }}', '_blank')">
                                <i class="fas fa-download me-2"></i>
                                Download Attachment
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Ticket Timeline Card --}}
            <div class="content-card">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-history me-2"></i>
                            Ticket Timeline
                        </h4>
                        <p class="card-subtitle mb-0">
                            Progress tracking and activity history
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <div class="timeline">
                        {{-- Ticket Created --}}
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Ticket Created</div>
                                <div class="timeline-date">{{ $ticket->created_at->format('M j, Y g:i A') }}</div>
                                <div class="timeline-description">Support request submitted</div>
                            </div>
                        </div>
                        
                        {{-- Technician Assigned --}}
                        @if($assignedTechnician)
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Technician Assigned</div>
                                    <div class="timeline-date">{{ $ticket->updated_at->format('M j, Y g:i A') }}</div>
                                    <div class="timeline-description">Assigned to {{ $assignedTechnician->name }}</div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Work Started --}}
                        @if(in_array($ticket->status, ['in_progress', 'resolved']))
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Work Started</div>
                                    <div class="timeline-date">{{ $ticket->updated_at->format('M j, Y g:i A') }}</div>
                                    <div class="timeline-description">Ticket marked as in progress</div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Comments Added --}}
                        @if($ticket->comments->count() > 0)
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-comment"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Comments Added</div>
                                    <div class="timeline-date">{{ $ticket->comments->last()->created_at->format('M j, Y g:i A') }}</div>
                                    <div class="timeline-description">{{ $ticket->comments->count() }} engineer comment(s)</div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Ticket Resolved --}}
                        @if($ticket->status === 'resolved')
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Ticket Resolved</div>
                                    <div class="timeline-date">{{ $ticket->updated_at->format('M j, Y g:i A') }}</div>
                                    <div class="timeline-description">Support request completed</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@push('styles')
<style>
/* Ticket Details Styles - Job Card Theme Applied */
:root {
    --primary: #059669;
    --primary-dark: #047857;
    --success: #059669;
    --warning: #F59E0B;
    --danger: #DC2626;
    --secondary: #6B7280;
    --secondary-dark: #4B5563;
    --info: #0EA5E9;
    --white: #FFFFFF;
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-300: #D1D5DB;
    --gray-400: #9CA3AF;
    --gray-500: #6B7280;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --gray-800: #1F2937;
    --border-radius: 8px;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --transition: all 0.2s ease;
}

.dashboard-container {
    padding: 0.5rem;
    max-width: 100%;
}

.dashboard-header {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.5rem 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.dashboard-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.header-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.125rem;
    flex-wrap: wrap;
}

.header-meta .badge {
    font-size: 0.65rem;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
}

.bg-secondary {
    background: var(--secondary) !important;
    color: white;
}

.bg-info {
    background: var(--info) !important;
    color: white;
}

.header-meta small {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.header-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
}

/* Status Overview */
.status-overview {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    background: var(--white);
    padding: 0.75rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.status-item {
    flex: 1;
    min-width: 200px;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
}

.status-pending {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
}

.status-progress {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
}

.status-complete {
    background: #F0FDF4;
    color: #047857;
    border: 1px solid #BBF7D0;
}

.status-cancelled {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

/* Type Badges */
.type-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
}

.type-badge.emergency {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

.type-badge.normal {
    background: #F3F4F6;
    color: #4B5563;
    border: 1px solid #D1D5DB;
}

/* Amount Badge */
.amount-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    background: #F0FDF4;
    color: #047857;
    border: 1px solid #BBF7D0;
    white-space: nowrap;
}

/* Content Cards */
.content-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.content-card-header {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--gray-200);
}

.card-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.card-subtitle {
    color: var(--gray-500);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.content-card-body {
    padding: 0;
}

/* Info Sections */
.info-section {
    height: 100%;
}

.info-grid {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item.highlighted {
    background: var(--gray-50);
    padding: 0.75rem;
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-200);
}

.info-label {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.info-value {
    font-size: 0.875rem;
    color: var(--gray-800);
    font-weight: 500;
}

/* Reference Badge */
.reference-badge {
    background: var(--gray-100);
    color: var(--gray-700);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

/* Date Badge */
.date-badge {
    background: var(--gray-100);
    color: var(--gray-700);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

/* Attachment Box */
.attachment-box {
    padding: 0.75rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
    display: inline-flex;
    align-items: center;
    border: 1px solid var(--gray-200);
}

/* Contact Links */
.contact-link {
    color: var(--info);
    text-decoration: none;
    font-size: 0.875rem;
    transition: var(--transition);
}

.contact-link:hover {
    color: var(--primary);
    text-decoration: underline;
}

/* Fault Description (Message Content) */
.fault-description {
    background: var(--gray-50);
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-200);
}

.fault-text {
    padding: 1.25rem;
    line-height: 1.6;
    color: var(--gray-800);
    margin: 0;
    border-left: 4px solid var(--primary);
    background: var(--white);
    border-radius: 0 var(--border-radius) var(--border-radius) 0;
    white-space: pre-line;
}

/* Engineer Comments */
.engineer-comments {
    margin-bottom: 0;
}

.comment-box {
    background: var(--gray-50);
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-200);
    overflow: hidden;
}

.comment-header {
    background: var(--white);
    padding: 1rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.comment-author {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.author-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
}

.author-details {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.875rem;
}

.author-role {
    color: var(--gray-500);
    font-size: 0.75rem;
}

.comment-date {
    color: var(--gray-500);
    font-size: 0.75rem;
}

.comment-content {
    padding: 1.25rem;
    color: var(--gray-800);
    line-height: 1.6;
    white-space: pre-line;
}

/* Assignment Info */
.assignment-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.assignment-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.assignment-label {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.user-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-200);
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    flex-shrink: 0;
    font-weight: 600;
}

.user-avatar.approver {
    background: linear-gradient(135deg, var(--success) 0%, var(--primary-dark) 100%);
}

.user-avatar.engineer {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.user-avatar.booker {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.user-details {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.875rem;
    word-break: break-word;
}

.user-role {
    color: var(--gray-500);
    font-size: 0.75rem;
}

.user-contact {
    margin-top: 0.25rem;
}

.unassigned-notice {
    background: #FFFBEB;
    color: #92400E;
    padding: 0.75rem;
    border-radius: var(--border-radius);
    text-align: center;
    font-weight: 500;
    font-size: 0.875rem;
    border: 1px solid #FDE68A;
}

/* Empty State */
.empty-state {
    text-align: center;
    color: var(--gray-500);
    padding: 2rem 0;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.action-btn.primary {
    background: var(--primary);
    color: white;
}

.action-btn.primary:hover {
    background: var(--primary-dark);
    color: white;
}

.action-btn.info {
    background: var(--info);
    color: white;
}

.action-btn.info:hover {
    background: #0284C7;
    color: white;
}

.action-btn.success {
    background: var(--success);
    color: white;
}

.action-btn.success:hover {
    background: var(--primary-dark);
    color: white;
}

.action-btn.warning {
    background: var(--warning);
    color: white;
}

.action-btn.warning:hover {
    background: #D97706;
    color: white;
}

.action-btn.outline {
    background: transparent;
    color: var(--secondary);
    border: 1px solid var(--secondary);
}

.action-btn.outline:hover {
    background: var(--secondary);
    color: white;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--gray-200);
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: var(--white);
    border: 3px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    color: var(--gray-400);
}

.timeline-item.completed .timeline-marker {
    background: var(--success);
    border-color: var(--success);
    color: white;
}

.timeline-content {
    background: var(--gray-50);
    padding: 1rem;
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-200);
}

.timeline-title {
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.timeline-date {
    color: var(--gray-600);
    margin-bottom: 0.25rem;
    font-size: 0.75rem;
}

.timeline-description {
    color: var(--gray-500);
    font-size: 0.75rem;
}

/* Form Elements */
.form-control,
.form-select {
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    transition: var(--transition);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.5rem;
    }
    
    .header-actions {
        width: 100%;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .status-overview {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .status-item {
        min-width: auto;
    }
    
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline::before {
        left: 0.75rem;
    }
    
    .timeline-marker {
        left: -1.5rem;
        width: 1.5rem;
        height: 1.5rem;
        font-size: 0.65rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions with loading states
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