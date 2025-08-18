@extends('layouts.app')

@section('title', 'Job Card Details - ' . ($callLog->job_card ?? 'TBD-' . $callLog->id))

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-clipboard-check me-2"></i>
                Job Card Details
            </h1>
            <div class="header-meta">
                <span class="badge bg-primary me-2">
                    <i class="fas fa-hashtag me-1"></i>
                    Job Card: {{ $callLog->job_card ?? 'TBD-' . $callLog->id }}
                </span>
                <span class="badge bg-info me-2">
                    <i class="fas fa-calendar me-1"></i>
                    {{ $callLog->created_at->format('M j, Y') }}
                </span>
                <small class="text-muted">Last updated: {{ $callLog->updated_at->diffForHumans() }}</small>
            </div>
        </div>
        <div class="header-actions">
            <button onclick="window.location.href='{{ route('admin.call-logs.all') }}'" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Jobs
            </button>
            @if(in_array(auth()->user()->role ?? 'user', ['admin', 'manager']) || ($callLog->assigned_to == auth()->id()))
                <button onclick="window.location.href='{{ route('admin.call-logs.edit', $callLog) }}'" class="btn btn-sm btn-primary me-2">
                    <i class="fas fa-edit me-1"></i>
                    Edit
                </button>
            @endif
        </div>
    </div>

    {{-- Status Overview --}}
    <div class="status-overview mb-2">
        <div class="status-item">
            @php
                $statusConfig = [
                    'pending' => ['class' => 'status-pending', 'icon' => 'clock', 'label' => 'Pending'],
                    'assigned' => ['class' => 'status-assigned', 'icon' => 'user-check', 'label' => 'Assigned'],
                    'in_progress' => ['class' => 'status-progress', 'icon' => 'cog', 'label' => 'In Progress'],
                    'complete' => ['class' => 'status-complete', 'icon' => 'check-circle', 'label' => 'Complete'],
                    'cancelled' => ['class' => 'status-cancelled', 'icon' => 'times-circle', 'label' => 'Cancelled']
                ];
                $config = $statusConfig[$callLog->status] ?? ['class' => 'status-default', 'icon' => 'circle', 'label' => 'Unknown'];
            @endphp
            <span class="status-badge {{ $config['class'] }}">
                <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                {{ $config['label'] }}
            </span>
        </div>
        
        <div class="status-item">
            @if($callLog->type === 'emergency')
                <span class="type-badge emergency">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Emergency
                </span>
            @else
                <span class="type-badge normal">
                    <i class="fas fa-clock me-1"></i>
                    {{ ucfirst($callLog->type ?? 'Normal') }}
                </span>
            @endif
        </div>
        
        <div class="status-item">
            <span class="amount-badge">
                <i class="fas fa-dollar-sign me-1"></i>
                @if(isset($callLog->currency) && $callLog->currency === 'ZWG')
                    ZWG {{ number_format($callLog->amount_charged ?? 0, 2) }}
                @else
                    ${{ number_format($callLog->amount_charged ?? 0, 2) }}
                @endif
            </span>
        </div>
    </div>

    <div class="row g-3">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Job Information Card --}}
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>
                            Job Information
                        </h4>
                        <p class="card-subtitle mb-0">
                            Basic job details and customer information
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    {{-- Job Card Prominent Display --}}
                    <div class="job-card-header mb-3">
                        <div class="job-card-display">
                            <div class="job-card-number">
                                <i class="fas fa-id-card me-2"></i>
                                <span class="job-card-label">Job Card:</span>
                                <span class="job-card-value">{{ $callLog->job_card ?? 'TBD-' . $callLog->id }}</span>
                            </div>
                            <div class="job-id">
                                <small class="text-muted">
                                    <i class="fas fa-hashtag me-1"></i>
                                    Job ID: {{ $callLog->id }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Customer Details --}}
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="section-title">
                                    <i class="fas fa-user me-2"></i>
                                    Customer Details
                                </h6>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Customer Name</div>
                                        <div class="info-value">{{ $callLog->customer_name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Email Address</div>
                                        <div class="info-value">
                                            @if($callLog->customer_email)
                                                <a href="mailto:{{ $callLog->customer_email }}" class="contact-link">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    {{ $callLog->customer_email }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Phone Number</div>
                                        <div class="info-value">
                                            @if($callLog->customer_phone)
                                                <a href="tel:{{ $callLog->customer_phone }}" class="contact-link">
                                                    <i class="fas fa-phone me-1"></i>
                                                    {{ $callLog->customer_phone }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($callLog->customer_address)
                                        <div class="info-item">
                                            <div class="info-label">Customer Address</div>
                                            <div class="info-value">{{ $callLog->customer_address }}</div>
                                        </div>
                                    @endif
                                    @if($callLog->zimra_ref)
                                        <div class="info-item">
                                            <div class="info-label">ZIMRA Reference</div>
                                            <div class="info-value">
                                                <span class="reference-badge">
                                                    <i class="fas fa-hashtag me-1"></i>
                                                    {{ $callLog->zimra_ref }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Schedule & Billing --}}
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="section-title">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Schedule & Billing
                                </h6>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Date Booked</div>
                                        <div class="info-value">
                                            @if($callLog->date_booked)
                                                <span class="date-badge">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $callLog->date_booked->format('M j, Y') }}
                                                </span>
                                                <small class="text-muted d-block">{{ $callLog->date_booked->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Date Resolved</div>
                                        <div class="info-value">
                                            @if($callLog->date_resolved)
                                                <span class="date-badge resolved">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{ $callLog->date_resolved->format('M j, Y') }}
                                                </span>
                                                <small class="text-muted d-block">{{ $callLog->date_resolved->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Not resolved yet</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Work Hours</div>
                                        <div class="info-value">
                                            <div class="time-range">
                                                @if($callLog->time_start)
                                                    <span class="time-badge start">
                                                        <i class="fas fa-play me-1"></i>
                                                        {{ $callLog->formatted_time_start ?? $callLog->time_start->format('g:i A') }}
                                                    </span>
                                                @else
                                                    <span class="time-badge placeholder">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Not started
                                                    </span>
                                                @endif
                                                
                                                @if($callLog->time_finish)
                                                    <span class="time-separator">→</span>
                                                    <span class="time-badge finish">
                                                        <i class="fas fa-stop me-1"></i>
                                                        {{ $callLog->formatted_time_finish ?? $callLog->time_finish->format('g:i A') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Billed Hours</div>
                                        <div class="info-value">
                                            @if($callLog->billed_hours)
                                                <span class="billing-badge">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $callLog->billed_hours }}
                                                </span>
                                            @else
                                                <span class="text-muted">Not calculated</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="info-item highlighted">
                                        <div class="info-label">Amount Charged</div>
                                        <div class="info-value">
                                            <span class="amount-display">
                                                @if(isset($callLog->currency) && $callLog->currency === 'ZWG')
                                                    <i class="fas fa-coins me-1"></i>
                                                    ZWG {{ number_format($callLog->amount_charged ?? 0, 2) }}
                                                @else
                                                    <i class="fas fa-dollar-sign me-1"></i>
                                                    ${{ number_format($callLog->amount_charged ?? 0, 2) }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fault Description Card --}}
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-bug me-2"></i>
                            Fault Description
                        </h4>
                        <p class="card-subtitle mb-0">
                            Detailed description of the reported issue
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <div class="fault-description">
                        <p class="fault-text">{{ $callLog->fault_description ?: 'No fault description provided.' }}</p>
                    </div>
                </div>
            </div>

            {{-- Engineer Comments Card --}}
            @if($callLog->engineer_comments)
                <div class="content-card mb-3">
                    <div class="content-card-header">
                        <div class="header-content">
                            <h4 class="card-title">
                                <i class="fas fa-tools me-2"></i>
                                Engineer Comments
                            </h4>
                            <p class="card-subtitle mb-0">
                                Technical notes and resolution details
                            </p>
                        </div>
                    </div>
                    
                    <div class="content-card-body" style="padding: 1rem;">
                        <div class="engineer-comments">
                            <div class="comment-box">
                                <div class="comment-header">
                                    <div class="comment-author">
                                        <div class="author-avatar">
                                            <i class="fas fa-user-cog"></i>
                                        </div>
                                        <div class="author-details">
                                            <div class="author-name">{{ optional($callLog->assignedTo)->name ?? $callLog->engineer ?? 'Engineer' }}</div>
                                            <div class="author-role">Technical Engineer</div>
                                        </div>
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

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Assignment Information Card --}}
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-users me-2"></i>
                            Assignment Information
                        </h4>
                        <p class="card-subtitle mb-0">
                            Job assignment and approval details
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <div class="assignment-info">
                        {{-- Approved By --}}
                        <div class="assignment-item">
                            <div class="assignment-label">Approved By</div>
                            <div class="user-card">
                                <div class="user-avatar approver">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="user-details">
                                    <div class="user-name">{{ optional($callLog->approver)->name ?? $callLog->approved_by ?? 'System' }}</div>
                                    <div class="user-role">Approver</div>
                                </div>
                            </div>
                        </div>

                        {{-- Assigned Engineer --}}
                        <div class="assignment-item">
                            <div class="assignment-label">Assigned Engineer</div>
                            @if($callLog->assignedTo || $callLog->engineer)
                                <div class="user-card">
                                    <div class="user-avatar engineer">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ optional($callLog->assignedTo)->name ?? $callLog->engineer }}</div>
                                        <div class="user-role">Technical Engineer</div>
                                        @if(optional($callLog->assignedTo)->email)
                                            <div class="user-contact">
                                                <a href="mailto:{{ $callLog->assignedTo->email }}" class="contact-link">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    Email Engineer
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

                        {{-- Booked By --}}
                        @if($callLog->booked_by)
                            <div class="assignment-item">
                                <div class="assignment-label">Booked By</div>
                                <div class="user-card">
                                    <div class="user-avatar booker">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $callLog->booked_by }}</div>
                                        <div class="user-role">Job Creator</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="content-card mb-3">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-bolt me-2"></i>
                            Quick Actions
                        </h4>
                        <p class="card-subtitle mb-0">
                            Common job management actions
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <div class="action-buttons">
                        
                        @if($callLog->assigned_to == auth()->id() || $callLog->engineer === auth()->user()->name)
                            @if($callLog->status === 'assigned' || $callLog->status === 'pending')
                                <button class="action-btn info w-100 mb-2" onclick="updateStatus({{ $callLog->id }}, 'in_progress')">
                                    <i class="fas fa-play me-2"></i>
                                    Start Work
                                </button>
                            @endif
                        @endif
                        
                        @if($callLog->status === 'complete' && $callLog->customer_email)
                            <button class="action-btn success w-100 mb-2" onclick="notifyCustomer({{ $callLog->id }})">
                                <i class="fas fa-paper-plane me-2"></i>
                                Notify Customer
                            </button>
                        @elseif($callLog->status === 'complete' && !$callLog->customer_email)
                            <button class="action-btn secondary w-100 mb-2" disabled title="No customer email available">
                                <i class="fas fa-paper-plane me-2"></i>
                                Notify Customer
                            </button>
                        @endif
                    
                    </div>
                </div>
            </div>

            {{-- Job Timeline Card --}}
            <div class="content-card">
                <div class="content-card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-history me-2"></i>
                            Job Timeline
                        </h4>
                        <p class="card-subtitle mb-0">
                            Progress tracking and milestones
                        </p>
                    </div>
                </div>
                
                <div class="content-card-body" style="padding: 1rem;">
                    <div class="timeline">
                        {{-- Job Created --}}
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Job Created</div>
                                <div class="timeline-date">{{ $callLog->created_at->format('M j, Y g:i A') }}</div>
                                <div class="timeline-description">Created by {{ $callLog->booked_by ?? 'System' }}</div>
                            </div>
                        </div>
                        
                        {{-- Engineer Assigned --}}
                        @if($callLog->assignedTo || $callLog->engineer)
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Engineer Assigned</div>
                                    <div class="timeline-date">{{ $callLog->updated_at->format('M j, Y g:i A') }}</div>
                                    <div class="timeline-description">Assigned to {{ optional($callLog->assignedTo)->name ?? $callLog->engineer }}</div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Work Started --}}
                        @if(in_array($callLog->status, ['in_progress', 'complete']))
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Work Started</div>
                                    <div class="timeline-date">
                                        @if($callLog->time_start)
                                            {{ $callLog->date_booked->format('M j, Y') }} at {{ $callLog->formatted_time_start ?? $callLog->time_start->format('g:i A') }}
                                        @else
                                            {{ $callLog->updated_at->format('M j, Y g:i A') }}
                                        @endif
                                    </div>
                                    <div class="timeline-description">Work began on site</div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Work Completed --}}
                        @if($callLog->status === 'complete')
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Job Completed</div>
                                    <div class="timeline-date">
                                        @if($callLog->date_resolved)
                                            {{ $callLog->date_resolved->format('M j, Y') }}
                                            @if($callLog->time_finish)
                                                at {{ $callLog->formatted_time_finish ?? $callLog->time_finish->format('g:i A') }}
                                            @endif
                                        @else
                                            {{ $callLog->updated_at->format('M j, Y g:i A') }}
                                        @endif
                                    </div>
                                    <div class="timeline-description">
                                        Job completed successfully
                                        @if($callLog->billed_hours)
                                            ({{ $callLog->billed_hours }} hours)
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Job Cancelled --}}
                        @if($callLog->status === 'cancelled')
                            <div class="timeline-item cancelled">
                                <div class="timeline-marker">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Job Cancelled</div>
                                    <div class="timeline-date">{{ $callLog->updated_at->format('M j, Y g:i A') }}</div>
                                    <div class="timeline-description">Job was cancelled</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Assignment Modal --}}
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
/* Job Card Details Styles */
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

/* Job Card Header Display */
.job-card-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 1rem;
    border-radius: var(--border-radius);
    margin-bottom: 1rem;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15);
}

.job-card-display {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.job-card-number {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.job-card-label {
    opacity: 0.9;
}

.job-card-value {
    background: rgba(255, 255, 255, 0.15);
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.job-id {
    opacity: 0.8;
}

/* Update the header badge to primary color */
.bg-primary {
    background: var(--primary) !important;
    color: white;
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

.status-assigned {
    background: #F0F9FF;
    color: #0284C7;
    border: 1px solid #BAE6FD;
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

/* Content Card */
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

.section-title {
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--gray-200);
}

.section-title i {
    color: var(--secondary);
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
}

.info-value {
    font-size: 0.875rem;
    color: var(--gray-800);
    font-weight: 500;
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

/* Reference Badge */
.reference-badge {
    background: var(--gray-100);
    color: var(--gray-700);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    font-family: monospace;
}

/* Date Badges */
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

.date-badge.resolved {
    background: #F0FDF4;
    color: #047857;
}

/* Time Range */
.time-range {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.time-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.time-badge.start {
    background: #EFF6FF;
    color: #1D4ED8;
}

.time-badge.finish {
    background: #FEF2F2;
    color: #DC2626;
}

.time-badge.placeholder {
    background: var(--gray-100);
    color: var(--gray-500);
}

.time-separator {
    color: var(--gray-400);
    font-weight: 600;
}

/* Billing Badge */
.billing-badge {
    background: var(--gray-100);
    color: var(--gray-700);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

/* Amount Display */
.amount-display {
    background: #F0FDF4;
    color: #047857;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    border: 1px solid #BBF7D0;
}

/* Fault Description */
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
}

/* Engineer Comments */
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
}

/* Assignment Information */
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

.action-btn.secondary {
    background: var(--gray-300);
    color: var(--gray-600);
    cursor: not-allowed;
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

.timeline-item.cancelled .timeline-marker {
    background: var(--danger);
    border-color: var(--danger);
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

/* Assignment Summary */
.assignment-summary {
    background: var(--gray-50);
    padding: 1rem;
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-200);
}

.assignment-summary h6 {
    color: var(--gray-800);
    font-weight: 600;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
}

.summary-item {
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--gray-200);
}

.summary-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

/* Modal Styles */
.modal-content {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.modal-header {
    border-bottom: 1px solid var(--gray-200);
}

.modal-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.875rem;
}

.form-control,
.form-select {
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    transition: var(--transition);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
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
    
    .job-card-display {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .status-overview {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .status-item {
        min-width: auto;
    }
    
    .info-grid {
        gap: 0.5rem;
    }
    
    .time-range {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
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

@media (max-width: 480px) {
    .action-buttons .action-btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
    
    .header-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .assignment-summary {
        padding: 0.75rem;
    }
    
    .job-card-number {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
        font-size: 1rem;
    }
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

// Notification function using toastr if available, otherwise custom
function showToast(message, type = 'info') {
    if (typeof toastr !== 'undefined') {
        const toastrType = type === 'error' ? 'error' : type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info';
        toastr[toastrType](message, type.charAt(0).toUpperCase() + type.slice(1) + '!', {
            closeButton: true,
            progressBar: true,
            timeOut: 5000,
            extendedTimeOut: 2000,
            positionClass: 'toast-top-right'
        });
    } else {
        // Fallback notification
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