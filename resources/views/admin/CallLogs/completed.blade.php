@extends('layouts.calllogs')

@section('title', 'Completed Jobs')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Completed Jobs
                    </h5>
                    <p class="text-muted mb-0">Successfully completed IT support jobs</p>
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
                    <form method="GET">
                        <div class="input-group">
                            <select name="date_range" class="form-select" onchange="this.form.submit()">
                                <option value="">All Dates</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="card-body border-bottom">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $stats['total_completed'] }}</h3>
                            <p class="stat-label">Total Completed</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $stats['this_month'] }}</h3>
                            <p class="stat-label">This Month</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">${{ number_format($stats['total_revenue'], 0) }}</h3>
                            <p class="stat-label">Total Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ number_format($stats['avg_duration'], 1) }}h</h3>
                            <p class="stat-label">Avg Duration</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if($callLogs->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No completed jobs found.
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
                                <th>Completed</th>
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
                                        <div class="fw-bold text-success">
                                            <i class="fas fa-user-check me-1"></i>
                                            {{ $job->assignedTo->name }}
                                        </div>
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    @if($job->date_resolved)
                                        {{ $job->date_resolved->format('M d, Y') }}
                                        @if($job->time_finish)
                                            <br><small class="text-muted">{{ $job->time_finish }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">Not recorded</span>
                                    @endif
                                </td>
                                <td>
                                    @if($job->billed_hours)
                                        <strong>{{ $job->billed_hours }}h</strong>
                                    @elseif($job->time_start && $job->time_finish)
                                        <small>{{ $job->time_start }} - {{ $job->time_finish }}</small>
                                    @else
                                        <span class="text-muted">Not recorded</span>
                                    @endif
                                </td>
                                <td class="fw-bold">
                                    ${{ number_format($job->amount_charged, 2) }}
                                    @if($job->billed_hours)
                                        <br><small class="text-muted">${{ number_format($job->amount_charged / $job->billed_hours, 2) }}/hr</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.call-logs.show', $job) }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($job->engineer_comments)
                                        <button class="btn btn-sm btn-outline-info" 
                                                onclick="showComments('{{ addslashes($job->engineer_comments) }}')"
                                                title="View Comments">
                                            <i class="fas fa-comment"></i>
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

<!-- Comments Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="commentsModalLabel">Engineer Comments</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="commentsContent" class="engineer-comments-content">
                    <!-- Comments will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showComments(comments) {
        const commentsContent = document.getElementById('commentsContent');
        commentsContent.textContent = comments || 'No comments available for this job.';
        
        const modal = new bootstrap.Modal(document.getElementById('commentsModal'));
        modal.show();
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
    
    .engineer-comments-content {
        white-space: pre-line;
        line-height: 1.6;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        min-height: 100px;
    }
</style>
@endsection
