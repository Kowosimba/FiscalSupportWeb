@extends('layouts.calllogs')

@section('title', 'Job Card Details - ' . ($callLog->job_card ?? 'TBD-' . $callLog->id))

@section('content')

<style>
    :root {
    --primary-green: #22c55e;
    --primary-green-dark: #16a34a;
    --success-green: #10b981;
    --light-green: #dcfce7;
    --ultra-light-green: #f0fdf4;
    --secondary-green: #a7f3d0;
    --white: #ffffff;
    --light-gray: #f8fafc;
    --border-color: #e2e8f0;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-hover: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

</style>
<div class="container-fluid">
    <!-- Header -->
    <div class="page-header-card mb-4">
        <div class="page-header-content">
            <div class="header-text">
                <h3 class="page-title">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Job Card Details
                </h3>
                <p class="page-subtitle">{{ $callLog->job_card ?? 'TBD-' . $callLog->id }}</p>
                <div class="status-indicator mt-2">
                    @include('admin.calllogs.partials.status-badge', ['status' => $callLog->status])
                    @include('admin.calllogs.partials.type-badge', ['type' => $callLog->type])
                </div>
            </div>
            <div class="header-actions">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.call-logs.all') }}" class="btn btn-outline-secondary btn-enhanced">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Jobs
                    </a>
                    @if(in_array(auth()->user()->role ?? 'user', ['admin', 'manager']) || 
                        ($callLog->assigned_to == auth()->id()))
                        <a href="{{ route('admin.call-logs.edit', $callLog) }}" class="btn btn-primary btn-enhanced">
                            <i class="fas fa-edit me-2"></i>
                            Edit
                        </a>
                    @endif
                    <div class="dropdown">
                        <button class="btn btn-outline-info btn-enhanced dropdown-toggle" type="button" 
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
    </div>

    <div class="row">
        <!-- Main Job Information -->
        <div class="col-xl-8 col-lg-7">
            <!-- Basic Job Info -->
            <div class="info-card mb-4">
                <div class="info-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>
                            Job Information
                        </h5>
                        <div class="job-priority">
                            @if($callLog->type === 'emergency')
                                <span class="priority-badge emergency">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Emergency
                                </span>
                            @else
                                <span class="priority-badge normal">
                                    <i class="fas fa-clock"></i>
                                    {{ ucfirst($callLog->type ?? 'Normal') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="info-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="section-title">Job Details</h6>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">Job Card Number:</span>
                                        <span class="info-value job-card-badge">
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
                                                <a href="mailto:{{ $callLog->customer_email }}" class="email-link">
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
                                                <a href="tel:{{ $callLog->customer_phone }}" class="phone-link">
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
                                                <span class="date-badge">
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
                                                <span class="date-badge resolved">
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
                                                <span class="time-badge">
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
                                                <span class="time-badge finish">
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
                                                <span class="hours-badge">
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
                                            <span class="amount-badge">
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
            <div class="info-card mb-4">
                <div class="info-card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bug me-2"></i>
                        Fault Description
                    </h5>
                </div>
                <div class="info-card-body">
                    <div class="fault-description">
                        <p class="mb-0">{{ $callLog->fault_description ?: 'No fault description provided.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Engineer Comments -->
            @if($callLog->engineer_comments)
                <div class="info-card mb-4">
                    <div class="info-card-header">
                        <h5 class="card-title">
                            <i class="fas fa-tools me-2"></i>
                            Engineer Comments
                        </h5>
                    </div>
                    <div class="info-card-body">
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
            <div class="info-card mb-4">
                <div class="info-card-header">
                    <h5 class="card-title">
                        <i class="fas fa-users me-2"></i>
                        Assignment Information
                    </h5>
                </div>
                <div class="info-card-body">
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
                                                <a href="mailto:{{ $callLog->assignedTo->email }}" class="contact-link">
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

            <!-- In your show view, update the quick actions section -->
<div class="info-card mb-4">
    <div class="info-card-header">
        <h5 class="card-title">
            <i class="fas fa-bolt me-2"></i>
            Quick Actions
        </h5>
    </div>
    <div class="info-card-body">
        <div class="action-buttons">
            @if(in_array(auth()->user()->role, ['admin', 'manager']))
                @if(!$callLog->assignedTo && !$callLog->engineer && $callLog->status !== 'complete')
                    <button class="btn btn-success btn-action w-100 mb-3" onclick="assignJobCard({{ $callLog->id }})">
                        <i class="fas fa-user-plus me-2"></i>
                        Assign Engineer
                    </button>
                @endif
            @endif
            
            @if($callLog->assigned_to == auth()->id() || $callLog->engineer === auth()->user()->name)
                @if($callLog->status === 'assigned' || $callLog->status === 'pending')
                    <button class="btn btn-info btn-action w-100 mb-3" onclick="updateStatus({{ $callLog->id }}, 'in_progress')">
                        <i class="fas fa-play me-2"></i>
                        Start Work
                    </button>
                @endif
            @endif
            
            <!-- Updated Notify Customer Button -->
            @if($callLog->status === 'complete' && $callLog->customer_email)
                <button class="btn btn-outline-success btn-action w-100 mb-3" onclick="notifyCustomer({{ $callLog->id }})">
                    <i class="fas fa-paper-plane me-2"></i>
                    Notify Customer
                </button>
            @elseif($callLog->status === 'complete' && !$callLog->customer_email)
                <button class="btn btn-outline-secondary btn-action w-100 mb-3" disabled title="No customer email available">
                    <i class="fas fa-paper-plane me-2"></i>
                    Notify Customer
                </button>
            @endif
            
            @if(auth()->user()->role === 'admin')
                <button class="btn btn-outline-info btn-action w-100" onclick="downloadReport()">
                    <i class="fas fa-download me-2"></i>
                    Generate Report
                </button>
            @endif
        </div>
    </div>
</div>


            <!-- Job Timeline -->
            <div class="info-card">
                <div class="info-card-header">
                    <h5 class="card-title">
                        <i class="fas fa-history me-2"></i>
                        Job Timeline
                    </h5>
                </div>
                <div class="info-card-body">
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
                                <select class="form-select form-select-enhanced" id="engineer" name="engineer" required>
                                    <option value="">Choose an engineer...</option>
                                    @foreach(\App\Models\User::where('role', 'technician')->orWhere('role', 'manager')->get() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="assignment_notes" class="form-label">Assignment Notes (Optional)</label>
                                <textarea class="form-control form-control-enhanced" 
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

@section('styles')
<style>
:root {
    --primary-green: #22c55e;
    --primary-green-dark: #16a34a;
    --success-green: #10b981;
    --light-green: #dcfce7;
    --ultra-light-green: #f0fdf4;
    --secondary-green: #a7f3d0;
    --white: #ffffff;
    --light-gray: #f8fafc;
    --border-color: #e2e8f0;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-hover: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

/* Page Header */
.page-header-card {
    background: linear-gradient(135deg, var(--white) 0%, var(--light-gray) 100%);
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.page-header-content {
    padding: 2.5rem;
    background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
}

.header-text {
    flex: 1;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
}

.page-subtitle {
    color: var(--text-secondary);
    margin: 0;
    font-size: 1.1rem;
    font-weight: 500;
}

.status-indicator {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.header-actions {
    flex-shrink: 0;
}

/* Info Cards */
.info-card {
    background: var(--white);
    border-radius: 16px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: all 0.3s ease;
}

.info-card:hover {
    box-shadow: var(--shadow-lg);
}

.info-card-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%);
    border-bottom: 1px solid var(--border-color);
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--primary-green-dark);
    margin: 0;
    display: flex;
    align-items: center;
}

.info-card-body {
    padding: 2rem;
}

/* Job Priority */
.job-priority {
    display: flex;
    align-items: center;
}

.priority-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.priority-badge.emergency {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
    border: 1px solid #fca5a5;
}

.priority-badge.normal {
    background: linear-gradient(135deg, var(--light-green) 0%, var(--secondary-green) 100%);
    color: var(--primary-green-dark);
    border: 1px solid var(--secondary-green);
}

/* Info Sections */
.info-section {
    height: 100%;
}

.section-title {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--border-color);
}

.info-grid {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-item.amount-highlight {
    background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%);
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid var(--secondary-green);
}

.info-label {
    font-weight: 600;
    color: var(--text-secondary);
    flex-shrink: 0;
    margin-right: 1rem;
    min-width: 120px;
}

.info-value {
    color: var(--text-primary);
    text-align: right;
    flex: 1;
}

/* Badges */
.job-card-badge {
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
    color: var(--white);
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-weight: 600;
    font-size: 0.875rem;
}

.date-badge, .time-badge, .hours-badge {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-weight: 500;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
}

.date-badge.resolved {
    background: linear-gradient(135deg, var(--light-green) 0%, var(--secondary-green) 100%);
    color: var(--primary-green-dark);
}

.time-badge.finish {
    background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
    color: #c53030;
}

.amount-badge {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #047857;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.125rem;
    display: inline-flex;
    align-items: center;
}

/* Links */
.email-link, .phone-link {
    color: var(--primary-green);
    text-decoration: none;
    font-weight: 500;
}

.email-link:hover, .phone-link:hover {
    color: var(--primary-green-dark);
    text-decoration: underline;
}

.contact-link {
    color: var(--primary-green);
    text-decoration: none;
    font-size: 0.875rem;
}

.contact-link:hover {
    color: var(--primary-green-dark);
}

/* Fault Description */
.fault-description p {
    background: var(--light-gray);
    padding: 1.5rem;
    border-radius: 8px;
    border-left: 4px solid var(--primary-green);
    line-height: 1.6;
    font-size: 1rem;
    color: var(--text-primary);
}

/* Engineer Comments */
.comment-box {
    background: var(--light-gray);
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--border-color);
}

.comment-header {
    background: var(--white);
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.comment-author {
    font-weight: 600;
    color: var(--text-primary);
}

.comment-date {
    color: var(--text-muted);
    font-size: 0.875rem;
}

.comment-content {
    padding: 1.5rem;
    color: var(--text-primary);
    line-height: 1.6;
}

/* Assignment Info */
.assignment-info {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.assignment-label {
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 0.75rem;
    display: block;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: var(--light-gray);
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 1.25rem;
}

.user-avatar.engineer {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.user-avatar.booker {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.user-details {
    flex: 1;
}

.user-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.user-role {
    color: var(--text-muted);
    font-size: 0.875rem;
}

.user-contact {
    margin-top: 0.5rem;
}

.unassigned-notice {
    background: #fef3cd;
    color: #b45309;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
    font-weight: 500;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.btn-action {
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
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
    background: var(--border-color);
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
    border: 3px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    color: var(--text-muted);
}

.timeline-item.completed .timeline-marker {
    background: var(--primary-green);
    border-color: var(--primary-green);
    color: var(--white);
}

.timeline-item.cancelled .timeline-marker {
    background: #dc2626;
    border-color: #dc2626;
    color: var(--white);
}

.timeline-content {
    background: var(--light-gray);
    padding: 1.25rem;
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.timeline-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.timeline-date {
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.timeline-description {
    color: var(--text-muted);
    font-size: 0.875rem;
}

/* Enhanced Form Elements */
.form-select-enhanced, .form-control-enhanced {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-select-enhanced:focus, .form-control-enhanced:focus {
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    outline: none;
}

/* Assignment Summary */
.assignment-summary {
    background: var(--light-gray);
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.assignment-summary h6 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 1rem;
}

.summary-item {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.summary-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

/* Enhanced Buttons */
.btn-enhanced {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    gap: 0.5rem;
}

.btn-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-primary.btn-enhanced {
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
    color: var(--white);
}

.btn-success.btn-enhanced {
    background: linear-gradient(135deg, var(--success-green) 0%, var(--primary-green) 100%);
    color: var(--white);
}

.btn-info.btn-enhanced {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: var(--white);
}

.btn-outline-secondary.btn-enhanced {
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    background: transparent;
}

.btn-outline-secondary.btn-enhanced:hover {
    background: var(--text-secondary);
    color: var(--white);
}

.btn-outline-info.btn-enhanced {
    border: 2px solid #3b82f6;
    color: #3b82f6;
    background: transparent;
}

.btn-outline-info.btn-enhanced:hover {
    background: #3b82f6;
    color: var(--white);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .page-header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1.5rem;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .header-actions .btn-group {
        width: 100%;
        flex-wrap: wrap;
    }
    
    .header-actions .btn {
        flex: 1;
        min-width: 0;
    }
}

@media (max-width: 768px) {
    .page-header-content {
        padding: 1.5rem;
    }
    
    .info-card-header,
    .info-card-body {
        padding: 1.5rem;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .info-label {
        min-width: auto;
    }
    
    .info-value {
        text-align: left;
    }
    
    .user-info {
        flex-direction: column;
        text-align: center;
    }
    
    .timeline {
        padding-left: 2rem;
    }
    
    .timeline::before {
        left: 1rem;
    }
    
    .timeline-marker {
        left: -1.25rem;
        width: 2rem;
        height: 2rem;
    }
}

@media print {
    .header-actions,
    .btn-enhanced,
    .action-buttons {
        display: none !important;
    }
    
    .info-card {
        box-shadow: none;
        border: 1px solid #ccc;
        page-break-inside: avoid;
        margin-bottom: 1rem;
    }
    
    .page-header-card {
        box-shadow: none;
        border: 1px solid #ccc;
    }
}
</style>
@endsection

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
    
    fetch(`{{ route('admin.call-logs.index') }}/${jobCardId}/status`, {
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
            // Show success message
            showNotification('Job status updated successfully!', 'success');
            // Reload page after short delay
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error updating job status: ' + (data.message || 'Unknown error'), 'error');
            // Reset button
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating job status: ' + error.message, 'error');
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function sendUpdate() {
    // Show confirmation
    if (!confirm('Send status update to customer via email?')) {
        return;
    }
    
    showNotification('Customer notification sent successfully!', 'success');
    
    // TODO: Implement actual email sending
    // This would typically make an AJAX call to send the email
}

function downloadReport() {
    const currentUrl = new URL(window.location.href);
    const exportUrl = '{{ route("admin.call-logs.export") }}?job_id={{ $callLog->id }}';
    
    // Create temporary download link
    const link = document.createElement('a');
    link.href = exportUrl;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('Report download started!', 'success');
}

function printJobCard() {
    window.print();
}

function exportToPdf() {
    showNotification('PDF export feature coming soon!', 'info');
}

function deleteJob() {
    if (!confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
        return;
    }
    
    if (!confirm('This will permanently delete the job card and all associated data. Are you absolutely sure?')) {
        return;
    }
    
    // TODO: Implement delete functionality
    showNotification('Delete functionality not yet implemented', 'warning');
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
    
    fetch(`{{ route('admin.call-logs.index') }}/${currentJobCardId}/assign`, {
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
            showNotification('Engineer assigned successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error assigning engineer: ' + (data.message || 'Unknown error'), 'error');
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error assigning engineer: ' + error.message, 'error');
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
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
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Initialize tooltips if Bootstrap is available
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>

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
            showNotification('Job status updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error updating job status: ' + (data.message || 'Unknown error'), 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating job status: ' + error.message, 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function sendUpdate() {
    if (!confirm('Send status update to customer via email?')) {
        return;
    }
    showNotification('Customer notification sent successfully!', 'success');
}

function downloadReport() {
    const exportUrl = '{{ route("admin.call-logs.export") }}?job_id={{ $callLog->id }}';
    
    const link = document.createElement('a');
    link.href = exportUrl;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('Report download started!', 'success');
}

function printJobCard() {
    window.print();
}

function exportToPdf() {
    showNotification('PDF export feature coming soon!', 'info');
}

function deleteJob() {
    if (!confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
        return;
    }
    
    if (!confirm('This will permanently delete the job card and all associated data. Are you absolutely sure?')) {
        return;
    }
    
    showNotification('Delete functionality not yet implemented', 'warning');
}

// Fixed assignment form handler
document.getElementById('assignJobCardForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Assigning...';
    submitBtn.disabled = true;
    
    // Use the correct route for assignment
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
            showNotification('Engineer assigned successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error assigning engineer: ' + (data.message || 'Unknown error'), 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error assigning engineer: ' + error.message, 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Notification function
function showNotification(message, type = 'info') {
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

// Add this function to your existing JavaScript
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
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Failed to send notification', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error sending notification: ' + error.message, 'error');
    })
    .finally(() => {
        // Reset button state
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

</script>


@endpush

@endpush
