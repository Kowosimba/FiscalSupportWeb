@extends('layouts.calllogs')

@section('title', 'Unassigned Jobs')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="page-header mb-3">
        <div class="header-content">
            <h3 class="page-title">
                <i class="fas fa-user-times me-2"></i>
                Unassigned Jobs
            </h3>
            <p class="page-subtitle">Jobs that have not yet been assigned to a technician</p>
        </div>
        <div class="header-stats">
            <div class="stat-pill">
                <i class="fas fa-clipboard-list"></i>
                <span>{{ $callLogs->total() ?? 0 }} Total</span>
            </div>
            <a href="{{ route('admin.call-logs.create') }}" class="btn btn-primary ms-3">
                <i class="fas fa-plus me-2"></i> Create New Job
            </a>
        </div>
    </div>

    {{-- Success & Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Enhanced Filter Card --}}
    <div class="filter-card mb-3">
        <div class="filter-header">
            <h5 class="filter-title">
                <i class="fas fa-sliders-h me-2"></i>
                Filter & Search
            </h5>
        </div>
        <div class="filter-body">
            <form method="GET" action="{{ route('admin.call-logs.unassigned') }}" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="type" class="form-label">Job Type</label>
                        <div class="select-wrapper">
                            <select name="type" id="type" class="enhanced-select">
                                <option value="">All Types</option>
                                <option value="normal" {{ request('type') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="emergency" {{ request('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="repair" {{ request('type') == 'repair' ? 'selected' : '' }}>Repair</option>
                                <option value="installation" {{ request('type') == 'installation' ? 'selected' : '' }}>Installation</option>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_range" class="form-label">Date Range</label>
                        <div class="select-wrapper">
                            <select name="date_range" id="date_range" class="enhanced-select">
                                <option value="">All Dates</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="overdue" {{ request('date_range') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="search" class="form-label">Search Query</label>
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text"
                                   name="search"
                                   id="search"
                                   value="{{ request('search') }}"
                                   class="enhanced-input"
                                   placeholder="Search by customer, job card, or description...">
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.call-logs.unassigned') }}" class="btn btn-outline">
                            <i class="fas fa-times me-2"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Enhanced Jobs Table --}}
    <div class="jobs-table-card">
        <div class="table-header">
            <div class="table-title">
                <i class="fas fa-list me-2"></i>
                Unassigned Jobs List
            </div>
            @if($callLogs->count() > 0)
                <div class="table-meta">
                    Showing {{ $callLogs->firstItem() }} to {{ $callLogs->lastItem() }} of {{ $callLogs->total() }} results
                </div>
            @endif
        </div>
        <div class="table-container">
            <div class="table-responsive">
                <table class="enhanced-jobs-table">
                    <thead>
                        <tr>
                            <th>ID</th>
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
                            <tr class="job-row">
                                <td>
                                    <span class="job-id-badge">#{{ $job->id }}</span>
                                </td>
                                <td>
                                    <div class="job-card">
                                        <code>{{ $job->job_card ?? 'TBD-' . $job->id }}</code>
                                    </div>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-name">{{ $job->customer_name ?? $job->company_name }}</div>
                                        @if($job->customer_email)
                                            <small class="customer-email">{{ $job->customer_email }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="job-description">
                                        {{ Str::limit($job->fault_description, 40) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="type-badge type-{{ $job->type ?? 'normal' }}">
                                        @if($job->type === 'emergency')
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                        @elseif($job->type === 'maintenance')
                                            <i class="fas fa-tools me-1"></i>
                                        @else
                                            <i class="fas fa-clipboard-list me-1"></i>
                                        @endif
                                        {{ ucfirst($job->type ?? 'normal') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="date-info">
                                        <i class="fas fa-calendar me-1"></i>
                                        @if($job->date_booked)
                                            {{ $job->date_booked->format('M j, Y') }}
                                            <small class="d-block text-muted">{{ $job->date_booked->diffForHumans() }}</small>
                                        @else
                                            Not set
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="amount-badge">
                                        ${{ number_format($job->amount_charged ?? 0, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.call-logs.show', $job->id) }}"
                                           class="action-btn view-btn"
                                           title="View Job Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(in_array(auth()->user()->role ?? 'user', ['admin', 'manager']))
                                            <button type="button" class="action-btn assign-btn"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#assignModal{{ $job->id }}"
                                                title="Assign Technician">
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
                                        <i class="fas fa-user-times fa-3x text-muted mb-3"></i>
                                        <h4>No Unassigned Jobs</h4>
                                        <p>There are no unassigned jobs matching your criteria.</p>
                                        <a href="{{ route('admin.call-logs.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create New Job
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($callLogs->hasPages())
            <div class="pagination-wrapper">
                {{ $callLogs->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Assignment Modals for Call Logs -->
    @foreach($callLogs as $job)
        @if(in_array(auth()->user()->role ?? 'user', ['admin', 'manager']))
            <div class="modal fade" id="assignModal{{ $job->id }}" tabindex="-1" 
                 aria-labelledby="assignModalLabel{{ $job->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignModalLabel{{ $job->id }}">
                                <i class="fas fa-user-plus me-2"></i>
                                Assign Job #{{ $job->id }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        
                        <!-- FIXED: Use call-logs route, not tickets route -->
                        <form method="POST" action="{{ route('admin.call-logs.assign', $job->id) }}" 
                              onsubmit="return handleJobAssignment(event, {{ $job->id }})">
                            @csrf
                            
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="engineer{{ $job->id }}" class="form-label fw-semibold">
                                                Select Technician
                                            </label>
                                            <select name="engineer" id="engineer{{ $job->id }}" 
                                                    class="form-select form-select-enhanced" required>
                                                <option value="" selected disabled>Choose a technician...</option>
                                                @foreach($technicians as $tech)
                                                    <option value="{{ $tech->id }}">
                                                        {{ $tech->name }}
                                                        @if($tech->email)
                                                            - {{ $tech->email }}
                                                        @endif
                                                    </option>
                                                @endforeach
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
                                                      class="form-control form-control-enhanced" 
                                                      rows="3" 
                                                      placeholder="Add any special instructions or notes for the technician..."></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="job-summary bg-light p-3 rounded">
                                            <h6 class="fw-bold mb-3">Job Summary</h6>
                                            <div class="summary-item">
                                                <strong>Job Card:</strong><br>
                                                <code>{{ $job->job_card ?? 'TBD-' . $job->id }}</code>
                                            </div>
                                            <div class="summary-item mt-2">
                                                <strong>Customer:</strong><br>
                                                <small>{{ $job->customer_name ?? $job->company_name }}</small>
                                            </div>
                                            <div class="summary-item mt-2">
                                                <strong>Type:</strong><br>
                                                <span class="badge bg-{{ $job->type === 'emergency' ? 'danger' : 'secondary' }}">
                                                    {{ ucfirst($job->type ?? 'normal') }}
                                                </span>
                                            </div>
                                            <div class="summary-item mt-2">
                                                <strong>Amount:</strong><br>
                                                <span class="text-success fw-bold">
                                                    ${{ number_format($job->amount_charged ?? 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-success btn-assign">
                                    <i class="fas fa-user-plus me-2"></i>Assign Technician
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection

@section('styles')
<style>
/* Enhanced styling for the unassigned jobs view */
.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content h3 {
    color: #2d3748;
    font-weight: 600;
    margin: 0;
}

.header-content p {
    color: #6c757d;
    margin: 0.5rem 0 0 0;
}

.stat-pill {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
}

.filter-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    background: #f8f9fa;
    border-radius: 12px 12px 0 0;
}

.filter-title {
    color: #2d3748;
    font-weight: 600;
    margin: 0;
}

.filter-body {
    padding: 1.5rem;
}

.form-row {
    display: flex;
    gap: 1rem;
    align-items: end;
    flex-wrap: wrap;
}

.form-group {
    flex: 1;
    min-width: 200px;
}

.form-group.form-actions {
    flex: auto;
    min-width: auto;
}

.enhanced-select, .enhanced-input {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    background: white;
}

.enhanced-select:focus, .enhanced-input:focus {
    border-color: #22c55e;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    outline: none;
}

.jobs-table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.table-header {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-title {
    font-weight: 600;
    color: #2d3748;
}

.enhanced-jobs-table {
    width: 100%;
    border-collapse: collapse;
}

.enhanced-jobs-table th {
    background: #f8f9fa;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #2d3748;
    border-bottom: 2px solid #e2e8f0;
}

.enhanced-jobs-table td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: middle;
}

.job-row:hover {
    background-color: #f8f9fa;
}

.job-id-badge {
    background: #e3f2fd;
    color: #1565c0;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.875rem;
}

.job-card code {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
}

.customer-name {
    font-weight: 600;
    color: #2d3748;
}

.customer-email {
    color: #6c757d;
    font-size: 0.875rem;
}

.type-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
}

.type-badge.type-emergency {
    background: #fee2e2;
    color: #dc2626;
}

.type-badge.type-maintenance {
    background: #fef3c7;
    color: #d97706;
}

.type-badge.type-normal {
    background: #e5e7eb;
    color: #374151;
}

.amount-badge {
    background: #dcfce7;
    color: #16a34a;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    text-decoration: none;
}

.view-btn {
    background: #dbeafe;
    color: #2563eb;
}

.view-btn:hover {
    background: #bfdbfe;
    color: #1d4ed8;
}

.assign-btn {
    background: #dcfce7;
    color: #16a34a;
}

.assign-btn:hover {
    background: #bbf7d0;
    color: #15803d;
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-content h4 {
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.empty-content p {
    color: #9ca3af;
    margin-bottom: 1.5rem;
}

/* Modal enhancements */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.modal-header {
    background: #f8f9fa;
    border-radius: 12px 12px 0 0;
    border-bottom: 1px solid #e2e8f0;
}

.form-select-enhanced, .form-control-enhanced {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-select-enhanced:focus, .form-control-enhanced:focus {
    border-color: #22c55e;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

.job-summary {
    border: 1px solid #e2e8f0;
}

.summary-item {
    margin-bottom: 0.75rem;
}

.summary-item:last-child {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .form-row {
        flex-direction: column;
    }
    
    .form-group {
        min-width: 100%;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .enhanced-jobs-table th,
    .enhanced-jobs-table td {
        padding: 0.75rem 0.5rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>
@endpush
