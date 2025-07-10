@extends('layouts.contents')

@section('title', 'Completed Job Cards')

@section('content')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-card mb-4">
        <div class="page-header-content">
            <div class="header-text">
                <h3 class="page-title">
                    <i class="fa fa-check-circle me-2"></i>
                    Completed Job Cards
                </h3>
                <p class="page-subtitle">Successfully completed IT support jobs</p>
            </div>
            <div class="page-actions">
                @if(auth()->user()->role === 'admin')
                    <button class="btn btn-outline-secondary btn-enhanced" onclick="exportCompleted()">
                        <i class="fa fa-download me-2"></i>
                        Export Report
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon success">
                    <i class="fa fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $callLogs->total() }}</div>
                    <div class="stat-label">Total Completed</div>
                    <div class="stat-footer">
                        <i class="fa fa-trophy me-1"></i>
                        All time
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon info">
                    <i class="fa fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $callLogs->where('date_resolved', '>=', now()->startOfMonth()->format('Y-m-d'))->count() }}</div>
                    <div class="stat-label">This Month</div>
                    <div class="stat-footer">
                        <i class="fa fa-calendar me-1"></i>
                        {{ now()->format('M Y') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon warning">
                    <i class="fa fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">USD ${{ number_format($callLogs->sum('amount_charged'), 0) }}</div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-footer">
                        <i class="fa fa-chart-line me-1"></i>
                        From completed jobs
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon primary">
                    <i class="fa fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($callLogs->avg('billed_hours') ?? 0, 1) }}h</div>
                    <div class="stat-label">Avg Duration</div>
                    <div class="stat-footer">
                        <i class="fa fa-stopwatch me-1"></i>
                        Per job
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h5 class="card-title">
                <i class="fa fa-filter me-2"></i>
                Filter Completed Job Cards
            </h5>
        </div>
        <div class="content-card-body">
            <form method="GET" action="{{ route('admin.call-logs.completed') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Engineer</label>
                            <select class="form-control form-control-enhanced" name="engineer">
                                <option value="">All Engineers</option>
                                <option value="Benson" {{ request('engineer') == 'Benson' ? 'selected' : '' }}>Benson</option>
                                <option value="Malvine" {{ request('engineer') == 'Malvine' ? 'selected' : '' }}>Malvine</option>
                                <option value="Mukai" {{ request('engineer') == 'Mukai' ? 'selected' : '' }}>Mukai</option>
                                <option value="Tapera" {{ request('engineer') == 'Tapera' ? 'selected' : '' }}>Tapera</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Job Type</label>
                            <select class="form-control form-control-enhanced" name="type">
                                <option value="">All Types</option>
                                <option value="normal" {{ request('type') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="repair" {{ request('type') == 'repair' ? 'selected' : '' }}>Repair</option>
                                <option value="installation" {{ request('type') == 'installation' ? 'selected' : '' }}>Installation</option>
                                <option value="consultation" {{ request('type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                <option value="emergency" {{ request('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Date Range</label>
                            <select class="form-control form-control-enhanced" name="date_range">
                                <option value="">All Dates</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-enhanced">
                                    <i class="fa fa-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.call-logs.completed') }}" class="btn btn-outline-secondary btn-enhanced">
                                    <i class="fa fa-times me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Job Cards Table -->
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h5 class="card-title">
                    <i class="fa fa-check-circle me-2"></i>
                    Completed Job Cards
                </h5>
                <p class="card-subtitle">{{ $callLogs->total() }} completed job cards</p>
            </div>
        </div>
        
        <div class="content-card-body">
            <div class="table-responsive">
                <table class="enhanced-table">
                    <thead>
                        <tr>
                            <th><i class="fa fa-hashtag me-1"></i>Job Card</th>
                            <th><i class="fa fa-building me-1"></i>Company</th>
                            <th><i class="fa fa-exclamation-circle me-1"></i>Fault Description</th>
                            <th><i class="fa fa-cogs me-1"></i>Type</th>
                            <th><i class="fa fa-user-tie me-1"></i>Engineer</th>
                            <th><i class="fa fa-calendar me-1"></i>Completed</th>
                            <th><i class="fa fa-clock me-1"></i>Duration</th>
                            <th><i class="fa fa-dollar-sign me-1"></i>Amount</th>
                            <th><i class="fa fa-cog me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($callLogs as $jobCard)
                            <tr class="job-row">
                                <td>
                                    <span class="job-card-number">{{ $jobCard->job_card }}</span>
                                    @if($jobCard->zimra_ref)
                                        <br>
                                        <small class="text-muted">ZIMRA: {{ $jobCard->zimra_ref }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="company-info">
                                        <i class="fa fa-building me-1"></i>
                                        <strong>{{ $jobCard->company_name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="fault-description">
                                        {{ Str::limit($jobCard->fault_description ?: 'No description provided', 50) }}
                                    </div>
                                </td>
                                <td>
                                    @include('admin.calllogs.partials.type-badge', ['type' => $jobCard->type])
                                </td>
                                <td>
                                    @if($jobCard->engineer)
                                        <div class="engineer-info">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-initial rounded-circle bg-light-primary">
                                                        {{ substr($jobCard->engineer, 0, 1) }}
                                                    </div>
                                                </div>
                                                <strong>{{ $jobCard->engineer }}</strong>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="completion-date">
                                        <i class="fa fa-calendar me-1"></i>
                                        {{ $jobCard->date_resolved ? \Carbon\Carbon::parse($jobCard->date_resolved)->format('M j, Y') : 'Not recorded' }}
                                        @if($jobCard->time_finish)
                                            <br>
                                            <small class="text-muted">{{ $jobCard->time_finish }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="job-duration">
                                        @if($jobCard->billed_hours)
                                            <i class="fa fa-clock me-1"></i>
                                            <strong>{{ $jobCard->billed_hours }}h</strong>
                                        @elseif($jobCard->time_start && $jobCard->time_finish)
                                            <i class="fa fa-clock me-1"></i>
                                            {{ $jobCard->time_start }} - {{ $jobCard->time_finish }}
                                        @else
                                            <span class="text-muted">Not recorded</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="amount-charged">
                                        <strong>USD ${{ number_format($jobCard->amount_charged, 2) }}</strong>
                                        @if($jobCard->billed_hours)
                                            <br>
                                            <small class="text-muted">
                                                ${{ number_format($jobCard->amount_charged / $jobCard->billed_hours, 2) }}/hr
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.call-logs.show', $jobCard) }}" class="action-btn view-btn" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        
                                        @if(auth()->user()->role === 'admin')
                                            <button class="action-btn print-btn" onclick="printJobCard({{ $jobCard->id }})" title="Print Job Card">
                                                <i class="fa fa-print"></i>
                                            </button>
                                        @endif
                                        
                                        <button class="action-btn success-btn" onclick="showComments('{{ $jobCard->engineer_comments }}')" title="View Comments">
                                            <i class="fa fa-comment"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-content">
                                            <i class="fa fa-check-circle"></i>
                                            <h5 class="empty-title">No completed job cards</h5>
                                            <p class="empty-description">No job cards have been completed yet.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($callLogs->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $callLogs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Comments Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Engineer Comments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="commentsContent" class="engineer-comments-content">
                    <!-- Comments will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-enhanced" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-nav-wrapper { background: var(--white); border-radius: 12px; box-shadow: var(--shadow); padding: 0.5rem; margin-bottom: 2rem;}
    .panel-nav { border: none; gap: 0.5rem; }
    .panel-nav .nav-link { border: none; padding: 0.75rem 1.5rem; border-radius: 8px; color: var(--light-text); font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; }
    .panel-nav .nav-link:hover { background: var(--hover-bg); color: var(--medium-text);}
    .panel-nav .nav-link.active { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); color: var(--white); box-shadow: var(--shadow-hover);}
    .page-header-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); border: 1px solid var(--border-color); overflow: hidden;}
    .page-header-content { padding: 2rem; background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%); display: flex; justify-content: space-between; align-items: center;}
    .page-title { font-size: 1.5rem; font-weight: 600; color: var(--primary-green-dark); margin: 0;}
    .page-subtitle { color: var(--light-text); margin: 0.5rem 0 0 0; font-size: 0.95rem;}
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;}
    .stat-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border-color); transition: all 0.3s ease;}
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover);}
    .stat-card-body { padding: 1.5rem; display: flex; align-items: center; gap: 1rem;}
    .stat-icon { width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;}
    .stat-icon.primary { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); }
    .stat-icon.success { background: linear-gradient(135deg, var(--success-green) 0%, var(--primary-green) 100%); }
    .stat-icon.info { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .stat-icon.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-icon i { font-size: 1.5rem; color: var(--white);}
    .stat-content { flex: 1;}
    .stat-number { font-size: 2rem; font-weight: 700; color: var(--dark-text); margin: 0; line-height: 1;}
    .stat-label { color: var(--light-text); font-size: 0.9rem; margin: 0.25rem 0; font-weight: 500;}
    .stat-footer { font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.25rem; color: var(--primary-green);}
    .content-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border-color);}
    .content-card-header { padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%); border-bottom: 1px solid var(--border-color);}
    .content-card-header .card-title { font-size: 1.25rem; font-weight: 600; color: var(--primary-green); margin: 0;}
    .card-subtitle { color: var(--light-text); font-size: 0.9rem; margin: 0.25rem 0 0 0;}
    .content-card-body { padding: 2rem;}
    .form-group { margin-bottom: 1.5rem;}
    .form-label { font-weight: 600; color: var(--dark-text); margin-bottom: 0.5rem; display: block;}
    .form-control-enhanced { border: 2px solid var(--border-color); border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.95rem; transition: all 0.3s ease; background: var(--white);}
    .form-control-enhanced:focus { border-color: var(--primary-green); box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1); outline: none;}
    .btn-enhanced { padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; border: none; display: flex; align-items: center; text-decoration: none;}
    .btn-primary.btn-enhanced { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); color: var(--white);}
    .btn-primary.btn-enhanced:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover);}
    .btn-secondary.btn-enhanced { background: var(--hover-bg); color: var(--medium-text); border: 2px solid var(--border-color);}
    .btn-secondary.btn-enhanced:hover { background: var(--medium-text); color: var(--white);}
    .btn-outline-secondary.btn-enhanced { border: 2px solid var(--border-color); color: var(--medium-text); background: transparent;}
    .btn-outline-secondary.btn-enhanced:hover { background: var(--medium-text); color: var(--white);}
    .enhanced-table { width: 100%; border-collapse: separate; border-spacing: 0; margin: 0;}
    .enhanced-table thead th { background: var(--ultra-light-green); color: var(--primary-green); font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 2px solid var(--light-green);}
    .enhanced-table tbody tr { transition: all 0.2s ease; border-bottom: 1px solid var(--border-color);}
    .enhanced-table tbody tr:hover { background: var(--ultra-light-green);}
    .enhanced-table tbody td { padding: 1rem 1.5rem; vertical-align: middle;}
    .job-card-number { font-family: 'Monaco', 'Menlo', monospace; background: var(--light-green); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem; color: var(--primary-green-dark); font-weight: 600; border: 1px solid var(--secondary-green);}
    .fault-description { font-weight: 500; color: var(--dark-text); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
    .company-info { color: var(--medium-text); font-weight: 500; display: flex; align-items: center;}
    .engineer-info { color: var(--primary-green); font-weight: 500;}
    .completion-date, .job-duration { color: var(--light-text); font-size: 0.875rem; display: flex; align-items: center;}
    .amount-charged { font-weight: 600; color: var(--primary-green-dark);}
    .action-buttons { display: flex; gap: 0.5rem;}
    .action-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; transition: all 0.2s ease; text-decoration: none; border: none; cursor: pointer;}
    .view-btn { background: var(--light-green); color: var(--primary-green);}
    .view-btn:hover { background: var(--primary-green); color: var(--white); transform: translateY(-1px);}
    .print-btn { background: #EFF6FF; color: #3B82F6;}
    .print-btn:hover { background: #3B82F6; color: var(--white); transform: translateY(-1px);}
    .success-btn { background: var(--ultra-light-green); color: var(--success-green);}
    .success-btn:hover { background: var(--success-green); color: var(--white); transform: translateY(-1px);}
    .empty-state { text-align: center; padding: 3rem 1.5rem;}
    .empty-content i { font-size: 2rem; color: var(--light-text); margin-bottom: 1rem;}
    .empty-title { color: var(--primary-green); font-weight: 600; margin-bottom: 0.5rem;}
    .empty-description { color: var(--light-text); margin: 0;}
    .avatar-sm { width: 32px; height: 32px;}
    .avatar-initial { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 600; color: var(--primary-green);}
    .bg-light-primary { background-color: var(--ultra-light-green);}
    .engineer-comments-content { white-space: pre-line; line-height: 1.6; padding: 1rem; background: var(--ultra-light-green); border-radius: 8px; min-height: 100px;}
    @media (max-width: 768px) {
        .page-header-content { flex-direction: column; align-items: flex-start; gap: 1rem;}
        .stats-grid { grid-template-columns: 1fr;}
        .content-card-header, .content-card-body { padding: 1rem;}
        .enhanced-table th, .enhanced-table td { padding: 0.75rem 1rem;}
    }
</style>

@push('scripts')
<script>
    function exportCompleted() {
        const params = new URLSearchParams(window.location.search);
        params.set('format', 'csv');
        params.set('status', 'complete');
        window.location.href = `{{ route('admin.call-logs.export') }}?${params.toString()}`;
    }
    
    function printJobCard(jobCardId) {
        window.open(`{{ route('admin.call-logs.index') }}/${jobCardId}/print`, '_blank');
    }
    
    function showComments(comments) {
        const commentsContent = document.getElementById('commentsContent');
        commentsContent.textContent = comments || 'No comments available for this job.';
        
        const modal = new bootstrap.Modal(document.getElementById('commentsModal'));
        modal.show();
    }
</script>
@endpush
@endsection
