@extends('layouts.calllogs')

@section('title', 'In Progress Jobs')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-play-circle text-primary me-2"></i>
                        In Progress Jobs
                    </h5>
                    <p class="text-muted mb-0">Job cards currently being worked on</p>
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
                            <select name="engineer" class="form-select" onchange="this.form.submit()">
                                <option value="">All Engineers</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ request('engineer') == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
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
        
        <div class="card-body">
            @if($callLogs->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No jobs in progress found.
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
                                <th>Engineer</th>
                                <th>Date Started</th>
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
                                    @if($job->assignedTo)
                                        <div class="fw-bold text-primary">
                                            <i class="fas fa-user-check me-1"></i>
                                            {{ $job->assignedTo->name }}
                                        </div>
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $job->date_booked->format('M d, Y') }}
                                    @if($job->time_start)
                                        <br><small class="text-info">Started: {{ $job->time_start }}</small>
                                    @endif
                                    @if($job->date_booked->diffInDays() > 3)
                                        <span class="badge bg-warning text-dark ms-2">Overdue</span>
                                    @endif
                                </td>
                                <td>
                                    @if($job->billed_hours)
                                        <strong>{{ $job->billed_hours }}h</strong>
                                    @elseif($job->time_start)
                                        <span class="text-info">{{ \Carbon\Carbon::parse($job->time_start)->diffForHumans() }}</span>
                                    @else
                                        <span class="text-muted">Not started</span>
                                    @endif
                                </td>
                                <td class="fw-bold">
                                    ${{ number_format($job->amount_charged, 2) }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.call-logs.show', $job) }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($job->assignedTo && $job->assignedTo->id == auth()->user()->id)
                                        <a href="{{ route('admin.call-logs.edit', $job) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="updateStatus({{ $job->id }}, 'complete')"
                                                title="Mark as Complete">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $callLogs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateStatus(jobCardId, newStatus) {
        if (!confirm('Are you sure you want to mark this job as complete?')) {
            return;
        }
        
        fetch(`/admin/call-logs/${jobCardId}/status`, {
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
@endsection

@section('styles')
<style>
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
</style>
@endsection
