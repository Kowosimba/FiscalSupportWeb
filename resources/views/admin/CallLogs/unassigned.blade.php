@extends('layouts.calllogs')

@section('title', 'Unassigned Calls')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Unassigned Calls</h1>
            <p class="text-muted">Calls waiting for technician assignment</p>
        </div>
        <div class="page-actions">
            <button class="btn btn-primary" onclick="bulkAssign()" id="bulkAssignBtn" disabled>
                <i class="fas fa-users"></i> Bulk Assign
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-user-slash"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $jobs->total() }}</div>
                <div class="stat-label">Unassigned</div>
                <div class="stat-footer">
                    <i class="fas fa-exclamation-triangle"></i> Need assignment
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-fire"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $jobs->where('priority', 'urgent')->count() }}</div>
                <div class="stat-label">Urgent</div>
                <div class="stat-footer">
                    <i class="fas fa-arrow-up"></i> High priority
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $jobs->where('created_at', '<=', now()->subHours(24))->count() }}</div>
                <div class="stat-label">Overdue</div>
                <div class="stat-footer">
                    <i class="fas fa-exclamation-circle"></i> >24 hours
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $technicians->count() }}</div>
                <div class="stat-label">Available Techs</div>
                <div class="stat-footer">
                    <i class="fas fa-user-check"></i> Ready to assign
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card mb-4">
        <div class="filter-header">
            <h6 class="filter-title">
                <i class="fas fa-filter me-2"></i>Filter Unassigned Calls
            </h6>
        </div>
        <div class="filter-body">
            <form method="GET" action="{{ route('calls.unassigned') }}">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Priority</label>
                        <div class="select-wrapper">
                            <select class="enhanced-select" name="priority">
                                <option value="">All Priorities</option>
                                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('calls.unassigned') }}" class="btn btn-outline">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Calls Table -->
    <div class="calls-table-card">
        <div class="table-header">
            <div class="header-content">
                <h6 class="table-title">
                    <i class="fas fa-user-slash me-2"></i>Unassigned Calls
                </h6>
                <p class="table-meta">{{ $jobs->total() }} unassigned calls</p>
            </div>
            <div>
                <label class="form-check-label">
                    <input type="checkbox" id="selectAll" class="form-check-input"> Select All
                </label>
            </div>
        </div>
        
        <div class="table-container">
            <div class="table-responsive">
                <table class="enhanced-calls-table">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAllHeader" class="form-check-input">
                            </th>
                            <th>Call ID</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Priority</th>
                            <th>Created</th>
                            <th>Age</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr class="call-row">
                                <td>
                                    <input type="checkbox" class="form-check-input job-checkbox" value="{{ $job->id }}">
                                </td>
                                <td>
                                    <span class="call-id-badge">{{ $job->job_card }}</span>
                                </td>
                                <td>
                                    <div class="call-subject">{{ $job->fault_description }}</div>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $job->customer_name }}
                                    </div>
                                </td>
                                <td>
                                    @include('admin.calllogs.partials.priority-badge', ['priority' => $job->priority])
                                </td>
                                <td>
                                    <div class="call-duration">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $job->created_at->format('M j, Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="call-duration">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $job->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('jobs.show', $job) }}" class="action-btn view-btn" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="action-btn btn-success" onclick="assignJob({{ $job->id }})" title="Assign Technician">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-check-circle"></i>
                                            <h4>All calls assigned</h4>
                                            <p>Great! All calls have been assigned to technicians.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($jobs->hasPages())
            <div class="pagination-wrapper">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Assignment Modal -->
<div class="modal fade" id="assignJobModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Job to Technician</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="assignJobForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Select Technician</label>
                        <select class="form-select" id="assigned_to" name="assigned_to" required>
                            <option value="">Choose technician...</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}">{{ $technician->name }} ({{ $technician->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Job</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Assignment Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Assign Jobs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkAssignForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bulk_assigned_to" class="form-label">Select Technician</label>
                        <select class="form-select" id="bulk_assigned_to" name="assigned_to" required>
                            <option value="">Choose technician...</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}">{{ $technician->name }} ({{ $technician->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="selectedCount">0</span> jobs will be assigned to the selected technician.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Selected Jobs</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentJobId = null;
    const assignModal = new bootstrap.Modal(document.getElementById('assignJobModal'));
    const bulkAssignModal = new bootstrap.Modal(document.getElementById('bulkAssignModal'));
    
    // Checkbox handling
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.job-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkAssignButton();
    });
    
    document.querySelectorAll('.job-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkAssignButton);
    });
    
    function updateBulkAssignButton() {
        const selectedJobs = document.querySelectorAll('.job-checkbox:checked');
        const bulkAssignBtn = document.getElementById('bulkAssignBtn');
        
        if (selectedJobs.length > 0) {
            bulkAssignBtn.disabled = false;
            bulkAssignBtn.innerHTML = `<i class="fas fa-users"></i> Bulk Assign (${selectedJobs.length})`;
        } else {
            bulkAssignBtn.disabled = true;
            bulkAssignBtn.innerHTML = '<i class="fas fa-users"></i> Bulk Assign';
        }
        
        document.getElementById('selectedCount').textContent = selectedJobs.length;
    }
    
    function assignJob(jobId) {
        currentJobId = jobId;
        assignModal.show();
    }
    
    function bulkAssign() {
        const selectedJobs = document.querySelectorAll('.job-checkbox:checked');
        if (selectedJobs.length === 0) {
            alert('Please select at least one job to assign.');
            return;
        }
        bulkAssignModal.show();
    }
    
    // Single assignment
    document.getElementById('assignJobForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(`/jobs/${currentJobId}/assign`, {
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
                assignModal.hide();
                location.reload();
            } else {
                alert('Error assigning job');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error assigning job');
        });
    });
    
    // Bulk assignment
    document.getElementById('bulkAssignForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const selectedJobs = Array.from(document.querySelectorAll('.job-checkbox:checked')).map(cb => cb.value);
        const assignedTo = document.getElementById('bulk_assigned_to').value;
        
        if (selectedJobs.length === 0) {
            alert('No jobs selected');
            return;
        }
        
        Promise.all(selectedJobs.map(jobId => {
            return fetch(`/jobs/${jobId}/assign`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ assigned_to: assignedTo })
            });
        }))
        .then(responses => {
            bulkAssignModal.hide();
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error during bulk assignment');
        });
    });
</script>
@endpush
@endsection
