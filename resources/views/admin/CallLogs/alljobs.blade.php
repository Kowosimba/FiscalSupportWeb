@extends('layouts.calllogs')

@section('title', 'Job Cards')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-card-checklist text-primary me-2"></i>Job Cards
            </h1>
            <p class="text-muted mb-0">Manage and track all service requests</p>
        </div>
        
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-success" id="exportBtn">
                <i class="bi bi-download me-1"></i>Export
            </button>
            @if(in_array(auth()->user()->role ?? 'user', ['admin', 'accounts']))
                <a href="{{ route('admin.call-logs.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>New Job
                </a>
            @endif
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 bg-light h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="h4 mb-0 fw-bold">{{ number_format($stats['total'] ?? 0) }}</div>
                        <div class="text-muted small">Total Jobs</div>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-collection fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 bg-warning-subtle h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="h4 mb-0 fw-bold">{{ number_format($stats['pending'] ?? 0) }}</div>
                        <div class="text-muted small">Pending</div>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-clock fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 bg-info-subtle h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="h4 mb-0 fw-bold">{{ number_format($stats['in_progress'] ?? 0) }}</div>
                        <div class="text-muted small">In Progress</div>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-gear fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 bg-success-subtle h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="h4 mb-0 fw-bold">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                        <div class="text-muted small">Revenue</div>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-currency-dollar fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" id="filterForm" action="{{ route('admin.call-logs.all') }}">
                <div class="row g-3 align-items-end">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label class="form-label text-muted small mb-1">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" 
                                   name="search" value="{{ request('search') }}" 
                                   placeholder="Search jobs...">
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="col-md-2">
                        <label class="form-label text-muted small mb-1">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All</option>
                            @if(isset($statuses))
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <!-- Technician -->
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">Technician</label>
                        <select class="form-select" name="technician">
                            <option value="">All Technicians</option>
                            @if(isset($technicians))
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ request('technician') == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <!-- Date Range -->
                    <div class="col-md-2">
                        <label class="form-label text-muted small mb-1">Period</label>
                        <select class="form-select" name="date_range">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                        </select>
                    </div>
                    
                    <!-- Actions -->
                    <div class="col-md-1">
                        <div class="d-flex gap-1">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-funnel"></i>
                            </button>
                            <a href="{{ route('admin.call-logs.all') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    Jobs <span class="badge bg-light text-dark ms-2">{{ $callLogs->total() ?? 0 }}</span>
                </h6>
                @if(($callLogs->total() ?? 0) > 0)
                    <small class="text-muted">
                        {{ $callLogs->firstItem() ?? 0 }}-{{ $callLogs->lastItem() ?? 0 }} of {{ $callLogs->total() }}
                    </small>
                @endif
            </div>
        </div>
        
        <div class="card-body p-0">
            @if(($callLogs->count() ?? 0) > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold text-muted ps-3">Job</th>
                                <th class="border-0 fw-semibold text-muted">Customer</th>
                                <th class="border-0 fw-semibold text-muted">Issue</th>
                                <th class="border-0 fw-semibold text-muted">Status</th>
                                <th class="border-0 fw-semibold text-muted">Assigned</th>
                                <th class="border-0 fw-semibold text-muted">Date</th>
                                <th class="border-0 fw-semibold text-muted">Amount</th>
                                <th class="border-0 fw-semibold text-muted pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($callLogs as $job)
                                <tr class="align-middle">
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-subtle rounded me-2 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-card-text text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">#{{ $job->id }}</div>
                                                <small class="text-muted">{{ $job->job_card ?? 'TBD-' . $job->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ $job->customer_name }}</div>
                                            @if($job->customer_email)
                                                <small class="text-muted">{{ $job->customer_email }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $job->fault_description }}">
                                            {{ $job->fault_description }}
                                        </div>
                                    </td>
                                    
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'pending' => ['class' => 'warning', 'icon' => 'clock'],
                                                'assigned' => ['class' => 'info', 'icon' => 'person-check'],
                                                'in_progress' => ['class' => 'primary', 'icon' => 'gear'],
                                                'complete' => ['class' => 'success', 'icon' => 'check-circle'],
                                                'cancelled' => ['class' => 'danger', 'icon' => 'x-circle']
                                            ];
                                            $config = $statusConfig[$job->status] ?? ['class' => 'secondary', 'icon' => 'circle'];
                                        @endphp
                                        <span class="badge bg-{{ $config['class'] }}-subtle text-{{ $config['class'] }} border border-{{ $config['class'] }}-subtle">
                                            <i class="bi bi-{{ $config['icon'] }} me-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        @if($job->assignedTo)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-person text-white small"></i>
                                                </div>
                                                <span class="small">{{ $job->assignedTo->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted small">Unassigned</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <div class="small">
                                            <div class="fw-semibold">{{ $job->date_booked ? $job->date_booked->format('M j') : 'N/A' }}</div>
                                            <div class="text-muted">{{ $job->date_booked ? $job->date_booked->diffForHumans() : '' }}</div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <span class="fw-bold text-success">
                                            ${{ number_format($job->amount_charged ?? 0, 2) }}
                                        </span>
                                    </td>
                                    
                                    <td class="pe-3">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.call-logs.show', $job) }}" 
                                               class="btn btn-sm btn-ghost-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(in_array(auth()->user()->role ?? 'user', ['admin', 'manager']) || 
                                                ($job->assigned_to == auth()->id()))
                                                <a href="{{ route('admin.call-logs.edit', $job) }}" 
                                                   class="btn btn-sm btn-ghost-secondary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                    </div>
                    <h6 class="text-muted">No jobs found</h6>
                    <p class="text-muted small mb-3">Try adjusting your search or filters</p>
                    <a href="{{ route('admin.call-logs.all') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                    </a>
                </div>
            @endif
        </div>
        
        @if($callLogs->hasPages())
            <div class="card-footer bg-light border-0">
                <div class="d-flex justify-content-center">
                    {{ $callLogs->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Export Loading Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-success mb-3" style="width: 3rem; height: 3rem;"></div>
                <h6 class="mb-2">Preparing Export</h6>
                <p class="text-muted small mb-0">This will take a moment...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root {
    --bs-body-bg: #f8fafc;
    --bs-border-color: #e2e8f0;
}

body {
    background-color: var(--bs-body-bg);
}

.card {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 0.75rem;
}

.card-header {
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
}

.table th {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    background-color: #f8fafc !important;
    padding: 1rem 0.75rem;
}

.table td {
    padding: 1rem 0.75rem;
    border-color: #f1f5f9;
}

.table tbody tr:hover {
    background-color: #f8fafc;
}

.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.avatar-xs {
    width: 1.5rem;
    height: 1.5rem;
}

.btn-ghost-primary {
    color: #3b82f6;
    border: none;
    background: transparent;
}

.btn-ghost-primary:hover {
    background-color: #eff6ff;
    color: #2563eb;
}

.btn-ghost-secondary {
    color: #64748b;
    border: none;
    background: transparent;
}

.btn-ghost-secondary:hover {
    background-color: #f1f5f9;
    color: #475569;
}

.input-group-text {
    border-color: #d1d5db;
}

.form-control {
    border-color: #d1d5db;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
}

.form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
}

.badge {
    font-weight: 500;
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Export functionality
    const exportBtn = document.getElementById('exportBtn');
    const exportModal = new bootstrap.Modal(document.getElementById('exportModal'));
    
    exportBtn.addEventListener('click', function() {
        exportModal.show();
        
        // Get current filter parameters
        const urlParams = new URLSearchParams(window.location.search);
        const exportUrl = '{{ route("admin.call-logs.export") }}?' + urlParams.toString();
        
        // Create temporary link and trigger download
        const link = document.createElement('a');
        link.href = exportUrl;
        link.download = 'job-cards-' + new Date().toISOString().split('T')[0] + '.xlsx';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Hide modal after delay
        setTimeout(() => {
            exportModal.hide();
        }, 1500);
    });
    
    // Auto-submit filters
    const filterSelects = document.querySelectorAll('select[name="status"], select[name="technician"], select[name="date_range"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    });
    
    // Search on Enter
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('filterForm').submit();
            }
        });
    }
});
</script>
@endsection