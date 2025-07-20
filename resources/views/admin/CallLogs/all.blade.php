@extends('layouts.calllogs')

@section('title', 'All Job Cards')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="header-info">
            <h1><i class="fas fa-list-alt me-2"></i>All Job Cards</h1>
            <p class="text-muted">Comprehensive view of all job cards with advanced filtering</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn btn-success btn-enhanced" onclick="exportData()">
                <i class="fas fa-file-excel me-2"></i>
                Export to Excel
            </button>
            @if(in_array(auth()->user()->role, ['admin', 'accounts']))
                <a href="{{ route('admin.call-logs.create') }}" class="btn btn-primary btn-enhanced">
                    <i class="fas fa-plus me-2"></i>
                    New Job Card
                </a>
            @endif
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card stat-primary">
                <div class="stat-content">
                    <h3>{{ number_format($stats['total']) }}</h3>
                    <p>Total Jobs</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-warning">
                <div class="stat-content">
                    <h3>{{ number_format($stats['pending']) }}</h3>
                    <p>Pending</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-info">
                <div class="stat-content">
                    <h3>{{ number_format($stats['in_progress']) }}</h3>
                    <p>In Progress</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-play"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-success">
                <div class="stat-content">
                    <h3>${{ number_format($stats['total_revenue'], 2) }}</h3>
                    <p>Total Revenue</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="card filter-card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Advanced Filters
                <button class="btn btn-sm btn-outline-secondary float-end" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </h5>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form method="GET" id="filterForm">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search jobs...">
                        </div>
                        
                        <!-- Status -->
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Type -->
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="type">
                                <option value="">All Types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Technician -->
                        <div class="col-md-4">
                            <label class="form-label">Technician</label>
                            <select class="form-select" name="technician">
                                <option value="">All Technicians</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ request('technician') == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Date Range -->
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <select class="form-select" name="date_range">
                                <option value="">All Dates</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="last_week" {{ request('date_range') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                                <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                                <option value="this_year" {{ request('date_range') == 'this_year' ? 'selected' : '' }}>This Year</option>
                            </select>
                        </div>
                        
                        <!-- Custom Date Range -->
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        
                        <!-- Amount Range -->
                        <div class="col-md-1">
                            <label class="form-label">Min $</label>
                            <input type="number" class="form-control" name="min_amount" value="{{ request('min_amount') }}" placeholder="0">
                        </div>
                        
                        <div class="col-md-1">
                            <label class="form-label">Max $</label>
                            <input type="number" class="form-control" name="max_amount" value="{{ request('max_amount') }}" placeholder="∞">
                        </div>
                        
                        <div class="col-md-1">
                            <label class="form-label">Per Page</label>
                            <select class="form-select" name="per_page">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        
                        <!-- Filter Actions -->
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Apply Filters
                                </button>
                                <a href="{{ route('admin.call-logs.all') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Clear Filters
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Job Cards Table -->
    <div class="card">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2 text-primary"></i>
                    Job Cards ({{ $callLogs->total() }} total)
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <small class="text-muted">
                        Showing {{ $callLogs->firstItem() ?? 0 }} to {{ $callLogs->lastItem() ?? 0 }} of {{ $callLogs->total() }}
                    </small>
                    <!-- Sort Options -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-sort me-1"></i>Sort
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'date_booked', 'direction' => 'desc']) }}">Latest First</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'date_booked', 'direction' => 'asc']) }}">Oldest First</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'customer_name', 'direction' => 'asc']) }}">Customer A-Z</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'amount_charged', 'direction' => 'desc']) }}">Highest Amount</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => 'asc']) }}">Status</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>ID</th>
                            <th>Job Card</th>
                            <th>Customer</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Technician</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($callLogs as $job)
                            <tr data-job-id="{{ $job->id }}">
                                <td>
                                    <span class="badge bg-light text-dark">#{{ $job->id }}</span>
                                </td>
                                <td>
                                    <code class="job-card-number">
                                        {{ $job->job_card ?? 'TBD-' . $job->id }}
                                    </code>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $job->customer_name }}</div>
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
                                    <span class="badge status-{{ $job->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge type-{{ $job->type }}">
                                        {{ ucfirst($job->type) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $job->assignedTo->name ?? 'Unassigned' }}
                                </td>
                                <td>
                                    <div>{{ $job->date_booked->format('M j, Y') }}</div>
                                    <small class="text-muted">{{ $job->date_booked->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <strong class="text-success">${{ number_format($job->amount_charged, 2) }}</strong>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.call-logs.show', $job) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(in_array(auth()->user()->role, ['admin', 'manager']) || $job->assigned_to == auth()->id())
                                            <a href="{{ route('admin.call-logs.edit', $job) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No job cards found</h5>
                                        <p class="text-muted">Try adjusting your filters or search criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($callLogs->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-info">
                        <small class="text-muted">
                            Showing {{ $callLogs->firstItem() }} to {{ $callLogs->lastItem() }} 
                            of {{ $callLogs->total() }} results
                        </small>
                    </div>
                    <div class="pagination-wrapper">
                        {{ $callLogs->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-success mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Exporting data...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.stat-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
}

.stat-content p {
    margin: 0;
    color: #6c757d;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-primary .stat-icon { background: #007bff; }
.stat-warning .stat-icon { background: #ffc107; }
.stat-info .stat-icon { background: #17a2b8; }
.stat-success .stat-icon { background: #28a745; }

.filter-card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.btn-enhanced {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.2s;
}

.btn-enhanced:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.job-card-number {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
}

.empty-state {
    padding: 3rem;
}

@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
}
</style>
@endsection

@section('scripts')
<script>
function exportData() {
    // Show loading modal
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    loadingModal.show();
    
    // Get current filter parameters
    const urlParams = new URLSearchParams(window.location.search);
    const exportUrl = '{{ route("admin.call-logs.export") }}?' + urlParams.toString();
    
    // Create hidden link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Hide loading modal after a delay
    setTimeout(() => {
        loadingModal.hide();
    }, 2000);
}

// Auto-submit form when filters change
document.querySelectorAll('select[name="status"], select[name="type"], select[name="technician"], select[name="date_range"], select[name="per_page"]').forEach(function(select) {
    select.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// Clear individual filters
function clearFilter(filterName) {
    const url = new URL(window.location);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}
</script>
@endsection
