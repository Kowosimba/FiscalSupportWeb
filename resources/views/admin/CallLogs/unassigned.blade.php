@extends('layouts.app')

@section('title', 'Unassigned Jobs')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-user-times me-2"></i>
                Unassigned Jobs
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary">{{ $callLogs->total() ?? 0 }} unassigned jobs</span>
                <small class="text-muted">Updated: {{ now()->format('g:i a') }}</small>
            </div>
        </div>
        <div class="header-actions">
            @if(in_array(auth()->user()->role ?? 'user', ['admin', 'accounts']))
                <button onclick="window.location.href='{{ route('admin.call-logs.create') }}'" class="btn btn-sm btn-success me-2">
                    <i class="fas fa-plus me-1"></i>
                    New Job
                </button>
            @endif
            <button id="refreshJobs" class="btn btn-sm btn-secondary">
                <i class="fas fa-sync-alt me-1"></i>
                Refresh
            </button>
        </div>
    </div>

    {{-- Success & Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-2">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Compact Filter Card --}}
    <div class="filter-card mb-2">
        <form method="GET" action="{{ route('admin.call-logs.unassigned') }}" class="filter-form" id="filterForm">
            <div class="filter-row">
                <div class="filter-group search-group">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" id="search"
                            value="{{ request('search') }}"
                            placeholder="Search jobs..."
                            class="form-control form-control-sm">
                    </div>
                </div>
                
                <div class="filter-group">
                    <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="normal" @selected(request('type') == 'normal')>Normal</option>
                        <option value="emergency" @selected(request('type') == 'emergency')>Emergency</option>
                 
                    </select>
                </div>
                
                <div class="filter-group">
                    <select name="date_range" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Dates</option>
                        <option value="today" @selected(request('date_range') == 'today')>Today</option>
                        <option value="yesterday" @selected(request('date_range') == 'yesterday')>Yesterday</option>
                        <option value="this_week" @selected(request('date_range') == 'this_week')>This Week</option>
                        <option value="last_week" @selected(request('date_range') == 'last_week')>Last Week</option>
                        <option value="overdue" @selected(request('date_range') == 'overdue')>Overdue</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-sm btn-secondary">
                        <i class="fas fa-filter me-1"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.call-logs.unassigned') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Jobs Table --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-list me-2"></i>
                    Unassigned Jobs Queue
                </h4>
                @if($callLogs->count() > 0)
                <p class="card-subtitle mb-0">
                    Showing {{ $callLogs->firstItem() }} to {{ $callLogs->lastItem() }} of {{ $callLogs->total() }} jobs
                </p>
                @endif
            </div>
            <div class="header-actions">
                <div class="d-flex gap-2">
                    <span class="badge urgent-jobs">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        {{ $callLogs->where('type', 'emergency')->count() }} Emergency
                    </span>
                    <span class="badge overdue-jobs">
                        <i class="fas fa-clock me-1"></i>
                        {{ $callLogs->filter(function($job) { return $job->date_booked && $job->date_booked->lt(now()->subDay()); })->count() }} Overdue
                    </span>
                </div>
            </div>
        </div>
        
        <div class="content-card-body">
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Job ID</th>
                            <th>Job Card</th>
                            <th>Customer</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Date Booked</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($callLogs as $job)
                        <tr class="{{ $job->type === 'emergency' ? 'emergency-row' : '' }}">
                            <td>
                                <span class="ticket-id">#{{ $job->id }}</span>
                            </td>
                            <td>
                                <div class="job-card-info">
                                    <code class="job-card-badge">{{ $job->job_card ?? 'TBD-' . $job->id }}</code>
                                </div>
                            </td>
                            <td>
                                <div class="customer-info">
                                    <div class="customer-name">{{ Str::limit($job->customer_name ?? $job->company_name, 20) }}</div>
                                    @if($job->customer_email)
                                        <small class="text-muted">{{ Str::limit($job->customer_email, 20) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="ticket-subject" onclick="window.location.href='{{ route('admin.call-logs.show', $job->id) }}'"
                                      title="{{ $job->fault_description }}">
                                    {{ Str::limit($job->fault_description ?: 'No description', 30) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $typeConfig = [
                                        'emergency' => ['class' => 'priority-high', 'label' => 'Emergency', 'icon' => 'fa-exclamation-triangle'],
                                        'maintenance' => ['class' => 'status-pending', 'label' => 'Maintenance', 'icon' => 'fa-wrench'],
                                        'repair' => ['class' => 'status-in-progress', 'label' => 'Repair', 'icon' => 'fa-tools'],
                                        'installation' => ['class' => 'priority-medium', 'label' => 'Installation', 'icon' => 'fa-plus-circle'],
                                        'consultation' => ['class' => 'priority-low', 'label' => 'Consultation', 'icon' => 'fa-comment'],
                                        'normal' => ['class' => 'status-open', 'label' => 'Normal', 'icon' => 'fa-clipboard-list'],
                                    ];
                                    $config = $typeConfig[$job->type ?? 'normal'] ?? $typeConfig['normal'];
                                @endphp
                                <span class="status-badge {{ $config['class'] }}">
                                    <i class="fas {{ $config['icon'] }} me-1"></i>
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td>
                                <div class="update-time">
                                    @if($job->date_booked)
                                        <div class="date-main">{{ $job->date_booked->format('M j, Y') }}</div>
                                        <small class="text-muted">{{ $job->date_booked->diffForHumans(null, true, false, 2) }}</small>
                                        @if($job->date_booked->lt(now()->subDay()))
                                            <span class="status-badge priority-high mt-1" style="font-size: 0.6rem; padding: 0.15rem 0.3rem;">
                                                Overdue
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="amount-info">
                                    <span class="amount-charged">
                                        @if(($job->currency ?? 'USD') === 'ZWG')
                                            ZWG {{ number_format($job->amount_charged ?? 0, 0) }}
                                        @else
                                            ${{ number_format($job->amount_charged ?? 0, 2) }}
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td>
                                {{-- In the action buttons section for each job --}}
                                <div class="action-buttons">
                                    <button onclick="window.location.href='{{ route('admin.call-logs.show', $job->id) }}'" 
                                        class="action-btn view-btn" 
                                        title="View Details" type="button" aria-label="View Job #{{ $job->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    {{-- Add edit button for managers --}}
                                    @if(in_array(auth()->user()->role ?? 'user', ['admin', 'manager']))
                                        <button onclick="window.location.href='{{ route('admin.call-logs.edit', $job->id) }}'" 
                                            class="action-btn edit-btn" 
                                            title="Edit Job" type="button" aria-label="Edit Job #{{ $job->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif    
                                    @if(in_array(auth()->user()->role ?? 'user', ['admin', 'manager']))
                                        <button type="button" class="action-btn assign-btn"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#assignModal{{ $job->id }}"
                                            title="Assign Technician" aria-label="Assign Job #{{ $job->id }}">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-user-times"></i>
                                    <h6>No Unassigned Jobs</h6>
                                    <p>All jobs have been assigned or no jobs match your criteria.</p>
                                    @if(in_array(auth()->user()->role ?? 'user', ['admin', 'accounts']))
                                        <button onclick="window.location.href='{{ route('admin.call-logs.create') }}'" 
                                                class="btn btn-success btn-sm mt-2">
                                            <i class="fas fa-plus me-1"></i>
                                            Create New Job
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($callLogs->hasPages())
            <div class="pagination-wrapper">
                {{ $callLogs->withQueryString()->onEachSide(1)->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Assignment Modals --}}
@foreach($callLogs as $job)
@if(in_array(auth()->user()->role ?? 'user', ['admin', 'manager']))
    <div class="modal fade" id="assignModal{{ $job->id }}" tabindex="-1" 
         aria-labelledby="assignModalLabel{{ $job->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--secondary); color: white;">
                    <h5 class="modal-title" id="assignModalLabel{{ $job->id }}">
                        <i class="fas fa-user-plus me-2"></i>
                        Assign Job #{{ $job->id }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('admin.call-logs.assign', $job->id) }}" 
                      onsubmit="return handleJobAssignment(event, {{ $job->id }})">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="engineer{{ $job->id }}" class="form-label fw-semibold">
                                        Select Technician
                                    </label>
                                    <select name="engineer" id="engineer{{ $job->id }}" 
                                            class="form-select @error('engineer') is-invalid @enderror" required>
                                        <option value="" selected disabled>Choose a technician...</option>
                                        @if(isset($technicians))
                                            @foreach($technicians as $tech)
                                                @if(in_array($tech->role, ['admin', 'manager', 'technician']))
                                                    <option value="{{ $tech->id }}">
                                                        {{ $tech->name }}
                                                        @if($tech->email)
                                                            - {{ $tech->email }}
                                                        @endif
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('engineer')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="assignment_notes{{ $job->id }}" class="form-label fw-semibold">
                                        Assignment Notes (Optional)
                                    </label>
                                    <textarea name="assignment_notes" id="assignment_notes{{ $job->id }}" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Add any special instructions or notes for the technician..."></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="job-summary" style="background: var(--gray-50); padding: 1rem; border-radius: var(--border-radius); border: 1px solid var(--gray-200);">
                                    <h6 class="fw-bold mb-3">Job Summary</h6>
                                    <div class="summary-item">
                                        <strong>Job Card:</strong><br>
                                        <code class="job-card-badge">{{ $job->job_card ?? 'TBD-' . $job->id }}</code>
                                    </div>
                                    <div class="summary-item mt-2">
                                        <strong>Customer:</strong><br>
                                        <small>{{ $job->customer_name ?? $job->company_name }}</small>
                                    </div>
                                    <div class="summary-item mt-2">
                                        <strong>Type:</strong><br>
                                        @php
                                            $modalTypeConfig = $typeConfig[$job->type ?? 'normal'] ?? $typeConfig['normal'];
                                        @endphp
                                        <span class="status-badge {{ $modalTypeConfig['class'] }}">
                                            {{ ucfirst($job->type ?? 'normal') }}
                                        </span>
                                    </div>
                                    <div class="summary-item mt-2">
                                        <strong>Amount:</strong><br>
                                        <span style="color: var(--success); font-weight: 600;">
                                            @if(($job->currency ?? 'USD') === 'ZWG')
                                                ZWG {{ number_format($job->amount_charged ?? 0, 0) }}
                                            @else
                                                ${{ number_format($job->amount_charged ?? 0, 2) }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="background: var(--gray-50);">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-secondary btn-assign">
                            <i class="fas fa-user-plus me-2"></i>Assign Technician
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endforeach
@endsection

@push('styles')
<style>
/* Your original CSS is maintained exactly as provided */
:root {
    --primary: #059669;
    --primary-dark: #047857;
    --success: #059669;
    --warning: #F59E0B;
    --danger: #DC2626;
    --secondary: #1c5c3f;
    --secondary-dark: #2e563d;
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

/* Compact Dashboard Styles */
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

.header-meta small {
    font-size: 0.7rem;
    color: var(--gray-500);
}

.header-actions {
    display: flex;
    gap: 0.5rem;
}

.header-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
}

.btn-secondary {
    background: var(--secondary);
    border-color: var(--secondary);
    color: white;
}

.btn-secondary:hover {
    background: var(--secondary-dark);
    border-color: var(--secondary-dark);
    color: white;
}

/* Alert Styles */
.alert {
    border-radius: var(--border-radius);
    padding: 0.75rem 1rem;
    font-size: 0.85rem;
}

.alert-success {
    background: #F0FDF4;
    color: #166534;
    border: 1px solid #BBF7D0;
}

.alert-danger {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

/* Compact Filter Card */
.filter-card {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    margin-bottom: 1rem;
}

.filter-form .filter-row {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 140px;
}

.search-group {
    flex: 2;
    min-width: 200px;
}

.search-input-wrapper {
    position: relative;
}

.search-input-wrapper i {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-500);
    font-size: 0.85rem;
}

.search-input-wrapper .form-control {
    padding-left: 2.25rem;
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.filter-actions .btn-secondary {
    background: var(--secondary);
    border-color: var(--secondary);
}

.filter-actions .btn-secondary:hover {
    background: var(--secondary-dark);
    border-color: var(--secondary-dark);
}

.form-select-sm, .form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
    height: 32px;
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
}

.form-select-sm:focus, .form-control-sm:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
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
    display: flex;
    justify-content: space-between;
    align-items: center;
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

/* Header Action Badges */
.urgent-jobs {
    background: #FEF2F2 !important;
    color: #DC2626 !important;
    border: 1px solid #FECACA;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

.overdue-jobs {
    background: #FEF3C7 !important;
    color: #92400E !important;
    border: 1px solid #FDE68A;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

/* Compact Table Styles */
.compact-table {
    width: 100%;
    font-size: 0.85rem;
    border-collapse: separate;
    border-spacing: 0;
}

.compact-table th {
    background: var(--gray-50);
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid var(--gray-200);
}

.compact-table td {
    padding: 0.5rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--gray-100);
}

.compact-table tr:last-child td {
    border-bottom: none;
}

/* Emergency Row Highlighting */
.emergency-row {
    background: #FEF2F2 !important;
    border-left: 3px solid #DC2626;
}

.emergency-row:hover {
    background: #FEE2E2 !important;
}

.ticket-id {
    font-family: 'Monaco', 'Menlo', monospace;
    background: var(--secondary);
    color: var(--white);
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Job Card Badge */
.job-card-badge {
    background: #F0FDF4 !important;
    color: #166534 !important;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid #BBF7D0;
}

/* No Underline Subject - Clickable but looks like text */
.ticket-subject {
    color: var(--gray-800);
    font-weight: 500;
    font-size: 0.85rem;
    transition: var(--transition);
    cursor: pointer;
    text-decoration: none !important;
    border: none;
    background: none;
    padding: 0;
    outline: none;
    display: inline;
}

.ticket-subject:hover {
    color: var(--secondary);
    text-decoration: none !important;
}

.ticket-subject:focus {
    color: var(--secondary);
    text-decoration: none !important;
    outline: none;
}

/* Customer Info */
.customer-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.customer-name {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.85rem;
}

/* Update Time */
.update-time {
    font-size: 0.8rem;
    color: var(--gray-600);
}

.date-main {
    font-weight: 500;
    color: var(--gray-800);
}

.update-time small {
    display: block;
    margin-top: 0.15rem;
}

/* Amount Info */
.amount-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.amount-charged {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.85rem;
}

/* Badge Styles - Jobs Theme */
.priority-badge, .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}

.priority-high {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

.priority-medium {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
}

.priority-low {
    background: #F0FDF4;
    color: #059669;
    border: 1px solid #BBF7D0;
}

/* Status badges - no blue colors */
.status-open {
    background: #F0FDF4;
    color: #047857;
    border: 1px solid #BBF7D0;
}

.status-pending {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
}

.status-resolved {
    background: #DCFCE7;
    color: #166534;
    border: 1px solid #BBF7D0;
}

.status-in-progress {
    background: #FEF3C7;
    color: #92400E;
    border: 1px solid #FDE68A;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    align-items: center;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 4px;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    background: none;
    padding: 0;
}

.view-btn {
    background: #F3F4F6;
    color: #4B5563;
    border: 1px solid #D1D5DB;
}

.view-btn:hover {
    background: #4B5563;
    color: white;
}

.assign-btn {
    background: #F0FDF4;
    color: #047857;
    border: 1px solid #BBF7D0;
}

.assign-btn:hover {
    background: #047857;
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-content i {
    font-size: 1.5rem;
    color: var(--gray-300);
    margin-bottom: 0.5rem;
}

.empty-content h6 {
    font-size: 0.9rem;
    color: var(--gray-600);
    margin-bottom: 0.25rem;
}

.empty-content p {
    font-size: 0.8rem;
    color: var(--gray-500);
    margin: 0;
}

/* Modal Styles */
.modal-content {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Pagination */
.pagination-wrapper {
    padding: 0.75rem;
    border-top: 1px solid var(--gray-200);
}

/* Override Bootstrap pagination colors */
.pagination .page-link {
    color: var(--secondary);
    border-color: var(--gray-300);
}

.pagination .page-link:hover {
    color: var(--secondary-dark);
    background-color: var(--gray-50);
    border-color: var(--gray-300);
}

.pagination .page-item.active .page-link {
    background-color: var(--secondary);
    border-color: var(--secondary);
    color: white;
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
        justify-content: flex-end;
    }
    
    .filter-row {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    
    .filter-group, .search-group {
        min-width: 100%;
    }
    
    .filter-actions {
        justify-content: flex-end;
    }
    
    .compact-table {
        font-size: 0.8rem;
    }
    
    .compact-table th,
    .compact-table td {
        padding: 0.4rem 0.5rem;
    }
    
    .content-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .content-card-header .header-actions {
        width: 100%;
        justify-content: flex-start;
    }
}

@media (max-width: 480px) {
    .customer-info,
    .update-time {
        font-size: 0.75rem;
    }
    
    .priority-badge,
    .status-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.15rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Refresh button functionality
    const refreshBtn = document.getElementById('refreshJobs');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Refreshing';
            this.disabled = true;
            
            setTimeout(() => {
                window.location.reload();
            }, 800);
        });
    }
    
    // Debounced search input
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let debounceTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    document.getElementById('filterForm').submit();
                }
            }, 500);
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('filterForm').submit();
            }
        });
    }
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Show modal if there are validation errors
    @if($errors->has('engineer') && session('assign_job_id'))
        var assignModal = new bootstrap.Modal(
            document.getElementById('assignModal{{ session('assign_job_id') }}')
        );
        assignModal.show();
    @endif
    
    // Toast notification function
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.setAttribute('role', 'alert');
        
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };
        
        const colors = {
            'success': 'linear-gradient(135deg, #059669, #047857)',
            'error': 'linear-gradient(135deg, #DC2626, #B91C1C)',
            'warning': 'linear-gradient(135deg, #F59E0B, #D97706)',
            'info': 'linear-gradient(135deg, #6B7280, #4B5563)'
        };
        
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas ${icons[type] || icons['info']} me-1"></i>
                ${message}
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
            background: ${colors[type] || colors['info']};
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 4000);
    }
    
    // Make functions globally available
    window.showToast = showToast;
});

function handleJobAssignment(event, jobId) {
    const form = event.target;
    const submitBtn = form.querySelector('.btn-assign');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Assigning...';
    submitBtn.disabled = true;
    
    // Let the form submit naturally
    return true;
}

// Notification function for success/error feedback
function showNotification(message, type = 'success') {
    showToast(type, message);
}

// Add animation keyframes
const style = document.createElement('style');
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
        transition: all 0.2s ease;
    }
    
    .toast-close:hover {
        opacity: 1;
        background: rgba(255,255,255,0.1);
    }
`;
document.head.appendChild(style);
</script>
@endpush
