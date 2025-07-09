@extends('layouts.calllogs')

@section('title', 'Pending Calls')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Pending Calls</h1>
            <p class="text-muted">Calls awaiting action</p>
        </div>
        <div class="page-actions">
            @can('create', App\Models\Job::class)
                <a href="{{ route('calls.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Call
                </a>
            @endcan
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $jobs->total() }}</div>
                <div class="stat-label">Pending Calls</div>
                <div class="stat-footer">
                    <i class="fas fa-exclamation-triangle"></i> Needs attention
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
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
            <div class="stat-icon purple">
                <i class="fas fa-user-slash"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $jobs->whereNull('assigned_to')->count() }}</div>
                <div class="stat-label">Unassigned</div>
                <div class="stat-footer">
                    <i class="fas fa-user-plus"></i> Need assignment
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $jobs->where('created_at', '>=', now()->startOfDay())->count() }}</div>
                <div class="stat-label">Today</div>
                <div class="stat-footer">
                    <i class="fas fa-plus"></i> New today
                </div>
            </div>
        </div>
    </div>

    <!-- Calls Table -->
    <div class="calls-table-card">
        <div class="table-header">
            <div class="header-content">
                <h6 class="table-title">
                    <i class="fas fa-clock me-2"></i>Pending Calls
                </h6>
                <p class="table-meta">{{ $jobs->total() }} pending calls</p>
            </div>
        </div>
        
        <div class="table-container">
            <div class="table-responsive">
                <table class="enhanced-calls-table">
                    <thead>
                        <tr>
                            <th>Call ID</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Priority</th>
                            <th>Created</th>
                            <th>Assigned To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr class="call-row">
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
                                        <br>
                                        <small class="text-muted">{{ $job->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($job->assignedTo)
                                        <div class="technician-info">
                                            <i class="fas fa-user-check me-1"></i>
                                            {{ $job->assignedTo->name }}
                                        </div>
                                    @else
                                        <span class="unassigned-badge">
                                            <i class="fas fa-user-slash me-1"></i>
                                            Unassigned
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('jobs.show', $job) }}" class="action-btn view-btn" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('assign', $job)
                                            @if(!$job->assigned_to)
                                                <button class="action-btn btn-success" onclick="assignJob({{ $job->id }})" title="Assign Technician">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-check-circle"></i>
                                            <h4>No pending calls</h4>
                                            <p>All calls have been processed or assigned.</p>
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

@push('scripts')
<script>
    let currentJobId = null;
    const assignModal = new bootstrap.Modal(document.getElementById('assignJobModal'));
    
    function assignJob(jobId) {
        currentJobId = jobId;
        assignModal.show();
    }
    
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
</script>
@endpush
@endsection
