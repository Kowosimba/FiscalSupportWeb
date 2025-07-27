@extends('layouts.app')

@section('title', 'Job Card Details - ' . ($callLog->job_card ?? 'TBD-' . $callLog->id))

@section('content')
<div class="dashboard-container">
    {{-- Page Header --}}
    <div class="dashboard-header mb-4">
        <div class="header-content">
            <h1 class="dashboard-title mb-0">
                <i class="fas fa-clipboard-check me-2"></i>
                Job Card Details
            </h1>
            <p class="dashboard-subtitle mb-0">{{ $callLog->job_card ?? 'TBD-' . $callLog->id }}</p>
            <div class="period-indicator mt-1">
                <span class="badge bg-primary">Created: {{ $callLog->created_at->format('M j, Y') }}</span>
                <small class="text-muted ms-2">Last updated: {{ $callLog->updated_at->diffForHumans() }}</small>
            </div>
            <div class="status-indicator mt-2">
                @include('admin.calllogs.partials.status-badge', ['status' => $callLog->status])
                @include('admin.calllogs.partials.type-badge', ['type' => $callLog->type])
            </div>
        </div>
        <div class="header-actions">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.call-logs.all') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Jobs
                </a>
                @if(in_array(auth()->user()->role ?? 'user', ['admin', 'manager']) || 
                    ($callLog->assigned_to == auth()->id()))
                    <a href="{{ route('admin.call-logs.edit', $callLog) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit me-2"></i>
                        Edit
                    </a>
                @endif
                <div class="dropdown">
                    <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button" 
                            data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-h me-2"></i>
                        Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#" onclick="printJobCard()">
                                <i class="fas fa-print me-2"></i>Print Job Card
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="exportToPdf()">
                                <i class="fas fa-file-pdf me-2"></i>Export to PDF
                            </a>
                        </li>
                        @if(in_array(auth()->user()->role ?? 'user', ['admin']))
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="deleteJob()">
                                    <i class="fas fa-trash me-2"></i>Delete Job
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Job Information -->
        <div class="col-xl-8 col-lg-7">
            <!-- Basic Job Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Job Information
                    </h5>
                    <div class="job-priority">
                        @if($callLog->type === 'emergency')
                            <span class="badge bg-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Emergency
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-clock me-1"></i>
                                {{ ucfirst($callLog->type ?? 'Normal') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="section-title">Job Details</h6>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">Job Card Number:</span>
                                        <span class="info-value badge bg-primary">
                                            {{ $callLog->job_card ?? 'TBD-' . $callLog->id }}
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Customer Name:</span>
                                        <span class="info-value">{{ $callLog->customer_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Customer Email:</span>
                                        <span class="info-value">
                                            @if($callLog->customer_email)
                                                <a href="mailto:{{ $callLog->customer_email }}" class="text-primary">
                                                    {{ $callLog->customer_email }}
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Customer Phone:</span>
                                        <span class="info-value">
                                            @if($callLog->customer_phone)
                                                <a href="tel:{{ $callLog->customer_phone }}" class="text-primary">
                                                    {{ $callLog->customer_phone }}
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">ZIMRA Reference:</span>
                                        <span class="info-value">{{ $callLog->zimra_ref ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="section-title">Schedule & Billing</h6>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">Date Booked:</span>
                                        <span class="info-value">
                                            @if($callLog->date_booked)
                                                <span class="badge bg-info">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $callLog->date_booked->format('M j, Y') }}
                                                </span>
                                                <small class="text-muted d-block">
                                                    {{ $callLog->date_booked->diffForHumans() }}
                                                </small>
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Date Resolved:</span>
                                        <span class="info-value">
                                            @if($callLog->date_resolved)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{ $callLog->date_resolved->format('M j, Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">Not resolved yet</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Time Start:</span>
                                        <span class="info-value">
                                            @if($callLog->time_start)
                                                <span class="badge bg-info">
                                                    <i class="fas fa-play me-1"></i>
                                                    {{ $callLog->time_start->format('g:i A') }}
                                                </span>
                                            @else
                                                <span class="text-muted">Not started</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Time Finish:</span>
                                        <span class="info-value">
                                            @if($callLog->time_finish)
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-stop me-1"></i>
                                                    {{ $callLog->time_finish->format('g:i A') }}
                                                </span>
                                            @else
                                                <span class="text-muted">Not finished</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Billed Hours:</span>
                                        <span class="info-value">
                                            @if($callLog->billed_hours)
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $callLog->billed_hours }} hours
                                                </span>
                                            @else
                                                <span class="text-muted">Not calculated</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="info-item amount-highlight">
                                        <span class="info-label">Amount Charged:</span>
                                        <span class="info-value">
                                            <span class="badge bg-success">
                                                <i class="fas fa-dollar-sign me-1"></i>
                                                ${{ number_format($callLog->amount_charged ?? 0, 2) }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fault Description -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bug me-2"></i>
                        Fault Description
                    </h5>
                </div>
                <div class="card-body">
                    <div class="fault-description">
                        <p class="mb-0">{{ $callLog->fault_description ?: 'No fault description provided.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Engineer Comments -->
            @if($callLog->engineer_comments)
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-tools me-2"></i>
                            Engineer Comments
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="engineer-comments">
                            <div class="comment-box">
                                <div class="comment-header">
                                    <div class="comment-author">
                                        <i class="fas fa-user-cog me-2"></i>
                                        {{ optional($callLog->assignedTo)->name ?? $callLog->engineer ?? 'Engineer' }}
                                    </div>
                                    <div class="comment-date">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $callLog->updated_at->format('M j, Y g:i A') }}
                                    </div>
                                </div>
                                <div class="comment-content">
                                    {{ $callLog->engineer_comments }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-5">
            <!-- Assignment Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-users me-2"></i>
                        Assignment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="assignment-info">
                        <div class="assigned-to mb-3">
                            <label class="assignment-label">Approved By:</label>
                            <div class="user-info">
                                <div class="user-avatar">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="user-details">
                                    <div class="user-name">
                                        {{ optional($callLog->approver)->name ?? $callLog->approved_by ?? 'System' }}
                                    </div>
                                    <div class="user-role">Approver</div>
                                </div>
                            </div>
                        </div>

                        <div class="assigned-to mb-3">
                            <label class="assignment-label">Assigned Engineer:</label>
                            @if($callLog->assignedTo || $callLog->engineer)
                                <div class="user-info">
                                    <div class="user-avatar engineer">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">
                                            {{ optional($callLog->assignedTo)->name ?? $callLog->engineer }}
                                        </div>
                                        <div class="user-role">Engineer</div>
                                        @if(optional($callLog->assignedTo)->email)
                                            <div class="user-contact">
                                                <a href="mailto:{{ $callLog->assignedTo->email }}" class="text-primary">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    Email
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="unassigned-notice">
                                    <i class="fas fa-user-slash me-2"></i>
                                    Not assigned yet
                                </div>
                            @endif
                        </div>

                        @if($callLog->booked_by)
                            <div class="assigned-to">
                                <label class="assignment-label">Booked By:</label>
                                <div class="user-info">
                                    <div class="user-avatar booker">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $callLog->booked_by }}</div>
                                        <div class="user-role">Creator</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="action-buttons">
                        @if(in_array(auth()->user()->role, ['admin', 'manager']))
                            @if(!$callLog->assignedTo && !$callLog->engineer && $callLog->status !== 'complete')
                                <button class="btn btn-success btn-sm w-100 mb-3" onclick="assignJobCard({{ $callLog->id }})">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Assign Engineer
                                </button>
                            @endif
                        @endif
                        
                        @if($callLog->assigned_to == auth()->id() || $callLog->engineer === auth()->user()->name)
                            @if($callLog->status === 'assigned' || $callLog->status === 'pending')
                                <button class="btn btn-info btn-sm w-100 mb-3" onclick="updateStatus({{ $callLog->id }}, 'in_progress')">
                                    <i class="fas fa-play me-2"></i>
                                    Start Work
                                </button>
                            @endif
                        @endif
                        
                        @if($callLog->status === 'complete' && $callLog->customer_email)
                            <button class="btn btn-outline-success btn-sm w-100 mb-3" onclick="notifyCustomer({{ $callLog->id }})">
                                <i class="fas fa-paper-plane me-2"></i>
                                Notify Customer
                            </button>
                        @elseif($callLog->status === 'complete' && !$callLog->customer_email)
                            <button class="btn btn-outline-secondary btn-sm w-100 mb-3" disabled title="No customer email available">
                                <i class="fas fa-paper-plane me-2"></i>
                                Notify Customer
                            </button>
                        @endif
                        
                        @if(auth()->user()->role === 'admin')
                            <button class="btn btn-outline-info btn-sm w-100" onclick="downloadReport()">
                                <i class="fas fa-download me-2"></i>
                                Generate Report
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Job Timeline -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-history me-2"></i>
                        Job Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Job Created -->
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Job Created</h6>
                                <p class="timeline-date">
                                    {{ $callLog->created_at->format('M j, Y g:i A') }}
                                </p>
                                <small class="timeline-description">
                                    Created by {{ $callLog->booked_by ?? 'System' }}
                                </small>
                            </div>
                        </div>
                        
                        <!-- Engineer Assigned -->
                        @if($callLog->assignedTo || $callLog->engineer)
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Engineer Assigned</h6>
                                    <p class="timeline-date">
                                        {{ $callLog->updated_at->format('M j, Y g:i A') }}
                                    </p>
                                    <small class="timeline-description">
                                        Assigned to {{ optional($callLog->assignedTo)->name ?? $callLog->engineer }}
                                    </small>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Work Started -->
                        @if(in_array($callLog->status, ['in_progress', 'complete']))
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Work Started</h6>
                                    <p class="timeline-date">
                                        @if($callLog->time_start)
                                            {{ $callLog->date_booked->format('M j, Y') }} at {{ $callLog->time_start->format('g:i A') }}
                                        @else
                                            {{ $callLog->updated_at->format('M j, Y g:i A') }}
                                        @endif
                                    </p>
                                    <small class="timeline-description">Work began on site</small>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Work Completed -->
                        @if($callLog->status === 'complete')
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Job Completed</h6>
                                    <p class="timeline-date">
                                        @if($callLog->date_resolved)
                                            {{ $callLog->date_resolved->format('M j, Y') }}
                                            @if($callLog->time_finish)
                                                at {{ $callLog->time_finish->format('g:i A') }}
                                            @endif
                                        @else
                                            {{ $callLog->updated_at->format('M j, Y g:i A') }}
                                        @endif
                                    </p>
                                    <small class="timeline-description">
                                        Job completed successfully
                                        @if($callLog->billed_hours)
                                            ({{ $callLog->billed_hours }} hours)
                                        @endif
                                    </small>
                                </div>
                            </div>
                        @endif

                        <!-- Job Cancelled -->
                        @if($callLog->status === 'cancelled')
                            <div class="timeline-item cancelled">
                                <div class="timeline-marker">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Job Cancelled</h6>
                                    <p class="timeline-date">
                                        {{ $callLog->updated_at->format('M j, Y g:i A') }}
                                    </p>
                                    <small class="timeline-description">Job was cancelled</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assignment Modal -->
<div class="modal fade" id="assignJobCardModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>
                    Assign Engineer to Job
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="assignJobCardForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="engineer" class="form-label">Select Engineer</label>
                                <select class="form-select" id="engineer" name="engineer" required>
                                    <option value="">Choose an engineer...</option>
                                    @foreach(\App\Models\User::where('role', 'technician')->orWhere('role', 'manager')->get() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="assignment_notes" class="form-label">Assignment Notes (Optional)</label>
                                <textarea class="form-control" 
                                          id="assignment_notes" 
                                          name="assignment_notes" 
                                          rows="3" 
                                          placeholder="Add any special instructions or notes..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="assignment-summary">
                                <h6>Job Summary</h6>
                                <div class="summary-item">
                                    <strong>Job Card:</strong><br>
                                    {{ $callLog->job_card ?? 'TBD-' . $callLog->id }}
                                </div>
                                <div class="summary-item">
                                    <strong>Customer:</strong><br>
                                    {{ $callLog->customer_name ?? 'N/A' }}
                                </div>
                                <div class="summary-item">
                                    <strong>Type:</strong><br>
                                    <span class="badge bg-{{ $callLog->type === 'emergency' ? 'danger' : 'secondary' }}">
                                        {{ ucfirst($callLog->type ?? 'normal') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        Assign Engineer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

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
    
    .user-avatar.engineer {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }
    
    .user-avatar.booker {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .unassigned-notice {
        background: #fef3cd;
        color: #b45309;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 500;
    }
    
    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 3rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 1.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--gray-200);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -2.75rem;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: var(--white);
        border: 3px solid var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        color: var(--gray-400);
    }
    
    .timeline-item.completed .timeline-marker {
        background: var(--success);
        border-color: var(--success);
        color: var(--white);
    }
    
    .timeline-item.cancelled .timeline-marker {
        background: var(--danger);
        border-color: var(--danger);
        color: var(--white);
    }
    
    .timeline-content {
        background: var(--gray-50);
        padding: 1.25rem;
        border-radius: 8px;
        border: 1px solid var(--gray-200);
    }
    
    .timeline-title {
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }
    
    .timeline-date {
        color: var(--gray-600);
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }
    
    .timeline-description {
        color: var(--gray-500);
        font-size: 0.875rem;
    }
    
    /* Fault Description */
    .fault-description p {
        background: var(--gray-50);
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid var(--primary);
        line-height: 1.6;
        font-size: 1rem;
        color: var(--gray-800);
    }
    
    /* Comment Box */
    .comment-box {
        background: var(--gray-50);
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--gray-200);
    }
    
    .comment-header {
        background: var(--white);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .comment-author {
        font-weight: 600;
        color: var(--gray-800);
    }
    
    .comment-date {
        color: var(--gray-500);
        font-size: 0.875rem;
    }
    
    .comment-content {
        padding: 1.5rem;
        color: var(--gray-800);
        line-height: 1.6;
    }
    
    /* Assignment Summary */
    .assignment-summary {
        background: var(--gray-50);
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid var(--gray-200);
    }
    
    .assignment-summary h6 {
        color: var(--gray-800);
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .summary-item {
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .summary-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    /* Amount Highlight */
    .amount-highlight {
        background: var(--gray-50);
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid var(--gray-200);
    }
</style>
@endpush

@push('scripts')
<script>
let currentJobCardId = {{ $callLog->id }};

function assignJobCard(jobCardId) {
    const modal = new bootstrap.Modal(document.getElementById('assignJobCardModal'));
    modal.show();
}

function updateStatus(jobCardId, newStatus) {
    if (!confirm(`Are you sure you want to change the status to "${newStatus.replace('_', ' ')}"?`)) {
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
    button.disabled = true;
    
    fetch(`{{ url('admin/call-logs') }}/${jobCardId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            status: newStatus,
            time_start: newStatus === 'in_progress' ? new Date().toTimeString().slice(0,5) : null,
            date_resolved: newStatus === 'complete' ? new Date().toISOString().split('T')[0] : null,
            time_finish: newStatus === 'complete' ? new Date().toTimeString().slice(0,5) : null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Job status updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error updating job status: ' + (data.message || 'Unknown error'), 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating job status: ' + error.message, 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function notifyCustomer(jobId) {
    if (!confirm('Send completion notification to the customer?')) {
        return;
    }
    
    const button = event.target;
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
    button.disabled = true;
    
    fetch(`{{ url('admin/call-logs') }}/${jobId}/notify-customer`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Failed to send notification', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error sending notification: ' + error.message, 'error');
    })
    .finally(() => {
        // Reset button state
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function downloadReport() {
    const exportUrl = '{{ route("admin.call-logs.export") }}?job_id={{ $callLog->id }}';
    
    const link = document.createElement('a');
    link.href = exportUrl;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showToast('Report download started!', 'success');
}

function printJobCard() {
    window.print();
}

function exportToPdf() {
    showToast('PDF export feature coming soon!', 'info');
}

function deleteJob() {
    if (!confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
        return;
    }
    
    if (!confirm('This will permanently delete the job card and all associated data. Are you absolutely sure?')) {
        return;
    }
    
    showToast('Delete functionality not yet implemented', 'warning');
}

// Assignment form handler
document.getElementById('assignJobCardForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Assigning...';
    submitBtn.disabled = true;
    
    fetch(`{{ url('admin/call-logs') }}/${currentJobCardId}/assign`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('assignJobCardModal'));
            modal.hide();
            showToast('Engineer assigned successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error assigning engineer: ' + (data.message || 'Unknown error'), 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error assigning engineer: ' + error.message, 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Notification function
function showToast(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'} alert-dismissible fade show notification`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 500px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endpush