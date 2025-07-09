@extends('layouts.calllogs')

@section('title', 'My Calls')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">My Calls</h1>
            <p class="text-muted">Calls assigned to you</p>
        </div>
        <div class="page-actions">
            <button class="btn btn-outline-primary" onclick="refreshPage()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Assigned</div>
                <div class="stat-footer">
                    <i class="fas fa-user"></i> Your calls
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pending</div>
                <div class="stat-footer">
                    <i class="fas fa-arrow-up"></i> Needs attention
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-tools"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['in_progress'] }}</div>
                <div class="stat-label">In Progress</div>
                <div class="stat-footer">
                    <i class="fas fa-play"></i> Active work
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['completed'] }}</div>
                <div class="stat-label">Completed</div>
                <div class="stat-footer">
                    <i class="fas fa-trophy"></i> This month
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card mb-4">
        <div class="filter-header">
            <h6 class="filter-title">
                <i class="fas fa-filter me-2"></i>Filter My Calls
            </h6>
        </div>
        <div class="filter-body">
            <form method="GET" action="{{ route('calls.my-calls') }}">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="select-wrapper">
                            <select class="enhanced-select" name="status">
                                <option value="">All Status</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>

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
                        <a href="{{ route('calls.my-calls') }}" class="btn btn-outline">
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
                    <i class="fas fa-list me-2"></i>My Assigned Calls
                </h6>
                <p class="table-meta">{{ $jobs->total() }} calls found</p>
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
                            <th>Status</th>
                            <th>Date Assigned</th>
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
                                    @include('admin.calllogs.partials.status-badge', ['status' => $job->status])
                                </td>
                                <td>
                                    <div class="call-duration">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $job->assigned_at ? $job->assigned_at->format('M j, Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('jobs.show', $job) }}" class="action-btn view-btn" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($job->status === 'assigned')
                                            <button class="action-btn btn-success" onclick="updateStatus({{ $job->id }}, 'in_progress')" title="Start Work">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        @elseif($job->status === 'in_progress')
                                            <button class="action-btn btn-primary" onclick="updateStatus({{ $job->id }}, 'completed')" title="Mark Complete">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-inbox"></i>
                                            <h4>No calls assigned</h4>
                                            <p>You don't have any calls assigned to you at the moment.</p>
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

@push('scripts')
<script>
    function updateStatus(jobId, newStatus) {
        if (!confirm('Are you sure you want to update this job status?')) {
            return;
        }
        
        fetch(`/jobs/${jobId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating job status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating job status');
        });
    }
    
    function refreshPage() {
        location.reload();
    }
</script>
@endpush
@endsection
