@extends('layouts.calllogs')

@section('title', 'My Job Cards')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle text-primary me-2"></i>
                        My Job Cards
                    </h5>
                    <p class="text-muted mb-0">Job cards assigned to you</p>
                </div>
                <div class="d-flex align-items-center">
                    <form method="GET" class="me-3">
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search jobs..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    <form method="GET" class="me-3">
                        <div class="input-group">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Complete</option>
                            </select>
                        </div>
                    </form>
                    <form method="GET" class="me-3">
                        <div class="input-group">
                            <select name="type" class="form-select" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                <option value="normal" {{ request('type') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="repair" {{ request('type') == 'repair' ? 'selected' : '' }}>Repair</option>
                                <option value="installation" {{ request('type') == 'installation' ? 'selected' : '' }}>Installation</option>
                                <option value="consultation" {{ request('type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                <option value="emergency" {{ request('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                        </div>
                    </form>
                    <form method="GET" class="me-3">
                        <div class="input-group">
                            <select name="date_range" class="form-select" onchange="this.form.submit()">
                                <option value="">All Dates</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                <option value="overdue" {{ request('date_range') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>
                    </form>
                    <button class="btn btn-outline-secondary" onclick="refreshPage()">
                        <i class="fas fa-sync-alt me-2"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="card-body border-bottom">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $stats['pending'] }}</h3>
                            <p class="stat-label">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $stats['in_progress'] }}</h3>
                            <p class="stat-label">In Progress</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $stats['complete'] }}</h3>
                            <p class="stat-label">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if($callLogs->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No job cards found matching your criteria.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job ID</th>
                                <th>Company</th>
                                <th>Fault Description</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date Booked</th>
                                <th>Duration</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($callLogs as $job)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark">#{{ $job->id }}</span>
                                    @if($job->zimra_ref)
                                        <br><small class="text-muted">ZIMRA: {{ $job->zimra_ref }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $job->company_name }}</div>
                                    <small class="text-muted">{{ $job->customer_name }}</small>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $job->fault_description }}">
                                        {{ $job->fault_description ?: 'No description' }}
                                    </div>
                                </td>
                                <td>
                                    @if($job->type == 'emergency')
                                        <span class="badge bg-danger">Emergency</span>
                                    @elseif($job->type == 'maintenance')
                                        <span class="badge bg-warning">Maintenance</span>
                                    @elseif($job->type == 'repair')
                                        <span class="badge bg-info">Repair</span>
                                    @elseif($job->type == 'installation')
                                        <span class="badge bg-purple">Installation</span>
                                    @elseif($job->type == 'consultation')
                                        <span class="badge bg-success">Consultation</span>
                                    @else
                                        <span class="badge bg-primary">Normal</span>
                                    @endif
                                </td>
                                <td>
                                    @if($job->status === 'in_progress')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-spinner me-1"></i>In Progress
                                        </span>
                                    @elseif($job->status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                    @elseif($job->status === 'complete')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Complete
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $job->date_booked->format('M d, Y') }}
                                    @if($job->date_booked->diffInDays() > 7)
                                        <br><span class="badge bg-warning text-dark">Overdue</span>
                                    @endif
                                    @if($job->date_resolved)
                                        <br><small class="text-success">Resolved: {{ $job->date_resolved->format('M d') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($job->billed_hours)
                                        <strong>{{ $job->billed_hours }}h</strong>
                                    @elseif($job->time_start && $job->time_finish)
                                        <small>{{ $job->time_start }} - {{ $job->time_finish }}</small>
                                    @elseif($job->time_start)
                                        <small class="text-info">Started: {{ $job->time_start }}</small>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td class="fw-bold">
                                    ${{ number_format($job->amount_charged, 2) }}
                                    @if($job->billed_hours)
                                        <br><small class="text-muted">${{ number_format($job->amount_charged / $job->billed_hours, 2) }}/hr</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.call-logs.show', $job) }}" 
                                           class="btn btn-sm btn-outline-secondary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.call-logs.edit', $job) }}" 
                                           class="btn btn-sm btn-outline-info" title="Edit Job">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($job->status === 'pending')
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="updateStatus({{ $job->id }}, 'in_progress')"
                                                    title="Start Work">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        @elseif($job->status === 'in_progress')
                                            <button class="btn btn-sm btn-outline-success" 
                                                    onclick="updateStatus({{ $job->id }}, 'complete')"
                                                    title="Complete Job">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $callLogs->firstItem() }} to {{ $callLogs->lastItem() }} of {{ $callLogs->total() }} results
                    </div>
                    {{ $callLogs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateStatus(jobId, newStatus) {
        const statusMessages = {
            'in_progress': 'start this job',
            'complete': 'mark this job as complete',
            'pending': 'move this job to pending'
        };
        
        if (!confirm(`Are you sure you want to ${statusMessages[newStatus] || 'update this job'}?`)) {
            return;
        }
        
        fetch(`/admin/call-logs/${jobId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                status: newStatus,
                time_start: newStatus === 'in_progress' ? new Date().toTimeString().slice(0, 5) : null,
                time_finish: newStatus === 'complete' ? new Date().toTimeString().slice(0, 5) : null,
                date_resolved: newStatus === 'complete' ? new Date().toISOString().split('T')[0] : null
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message || 'Status updated successfully');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast('error', data.message || 'Error updating status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Error updating job status');
        });
    }
    
    function refreshPage() {
        location.reload();
    }
    
    function showToast(type, message) {
        const toastHtml = `<div class="toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>`;
        
        $('body').append(toastHtml);
        $('.toast').last().toast('show');
        
        setTimeout(() => {
            $('.toast').last().toast('hide').remove();
        }, 3000);
    }
</script>
@endsection

@section('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        color: #333;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }
    
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .badge {
        font-size: 0.85em;
    }
    
    .bg-purple {
        background-color: #8B5CF6 !important;
    }
    
    .btn-group .btn {
        margin-right: 0;
    }
</style>
@endsection
