@extends('layouts.calllogs')

@section('title', 'Job Card Reports')

@section('content')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-card mb-4">
        <div class="page-header-content">
            <div class="header-text">
                <h3 class="page-title">
                    <i class="fa fa-chart-bar me-2"></i>
                    Job Card Reports
                </h3>
                <p class="page-subtitle">Analytics and reporting dashboard for IT support services</p>
            </div>
            <div class="page-actions">
                @if(auth()->user()->role === 'admin')
                    <button class="btn btn-primary btn-enhanced" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                        <i class="fa fa-file-export me-2"></i>
                        Generate Report
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h5 class="card-title">
                <i class="fa fa-filter me-2"></i>
                Report Filters
            </h5>
        </div>
        <div class="content-card-body">
            <form method="GET" action="{{ route('admin.call-logs.reports') }}">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control form-control-enhanced" name="date_from" value="{{ $dateFrom }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control form-control-enhanced" name="date_to" value="{{ $dateTo }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Engineer</label>
                            <select class="form-control form-control-enhanced" name="engineer">
                                <option value="">All Engineers</option>
                                <option value="Benson" {{ $engineer == 'Benson' ? 'selected' : '' }}>Benson</option>
                                <option value="Malvine" {{ $engineer == 'Malvine' ? 'selected' : '' }}>Malvine</option>
                                <option value="Mukai" {{ $engineer == 'Mukai' ? 'selected' : '' }}>Mukai</option>
                                <option value="Tapera" {{ $engineer == 'Tapera' ? 'selected' : '' }}>Tapera</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control form-control-enhanced" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="assigned" {{ $status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="complete" {{ $status == 'complete' ? 'selected' : '' }}>Complete</option>
                                <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Job Type</label>
                            <select class="form-control form-control-enhanced" name="type">
                                <option value="">All Types</option>
                                <option value="normal" {{ $type == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="maintenance" {{ $type == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="repair" {{ $type == 'repair' ? 'selected' : '' }}>Repair</option>
                                <option value="installation" {{ $type == 'installation' ? 'selected' : '' }}>Installation</option>
                                <option value="consultation" {{ $type == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                <option value="emergency" {{ $type == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex flex-column gap-2">
                                <button type="submit" class="btn btn-primary btn-enhanced">
                                    <i class="fa fa-search me-1"></i>Update Report
                                </button>
                                <a href="{{ route('admin.call-logs.reports') }}" class="btn btn-outline-secondary btn-enhanced">
                                    <i class="fa fa-times me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon primary">
                    <i class="fa fa-clipboard"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_jobs'] ?? 0 }}</div>
                    <div class="stat-label">Total Jobs</div>
                    <div class="stat-footer">
                        <i class="fa fa-calendar me-1"></i>
                        Selected period
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon success">
                    <i class="fa fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['completed_jobs'] ?? 0 }}</div>
                    <div class="stat-label">Completed</div>
                    <div class="stat-footer">
                        <i class="fa fa-percentage me-1"></i>
                        {{ ($stats['total_jobs'] ?? 0) > 0 ? round((($stats['completed_jobs'] ?? 0) / ($stats['total_jobs'] ?? 1)) * 100, 1) : 0 }}% completion rate
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon info">
                    <i class="fa fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">USD ${{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
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
                <div class="stat-icon warning">
                    <i class="fa fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['avg_completion_time'] ?? 0 }}h</div>
                    <div class="stat-label">Avg Completion Time</div>
                    <div class="stat-footer">
                        <i class="fa fa-stopwatch me-1"></i>
                        Per completed job
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon purple">
                    <i class="fa fa-tools"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_billed_hours'] ?? 0 }}h</div>
                    <div class="stat-label">Total Billed Hours</div>
                    <div class="stat-footer">
                        <i class="fa fa-clock me-1"></i>
                        Engineering time
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon danger">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['emergency_jobs'] ?? 0 }}</div>
                    <div class="stat-label">Emergency Jobs</div>
                    <div class="stat-footer">
                        <i class="fa fa-fire me-1"></i>
                        High priority
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Engineer Performance -->
        <div class="col-lg-6">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-users me-2"></i>
                        Engineer Performance
                    </h5>
                    <p class="card-subtitle">Performance metrics by engineer</p>
                </div>
                <div class="content-card-body">
                    <div class="table-responsive">
                        <table class="enhanced-table">
                            <thead>
                                <tr>
                                    <th>Engineer</th>
                                    <th>Total Jobs</th>
                                    <th>Completed</th>
                                    <th>In Progress</th>
                                    <th>Revenue</th>
                                    <th>Billed Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($engineerStats as $engineerName => $engineerData)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-initial rounded-circle bg-light-primary">
                                                        {{ substr($engineerName, 0, 1) }}
                                                    </div>
                                                </div>
                                                <strong>{{ $engineerName }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $engineerData['total'] }}</td>
                                        <td>{{ $engineerData['completed'] }}</td>
                                        <td>{{ $engineerData['in_progress'] }}</td>
                                        <td>USD ${{ number_format($engineerData['revenue'], 2) }}</td>
                                        <td>{{ number_format($engineerData['billed_hours'], 1) }}h</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Statistics -->
        <div class="col-lg-6">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-chart-line me-2"></i>
                        Daily Statistics
                    </h5>
                    <p class="card-subtitle">Daily job volume and completion</p>
                </div>
                <div class="content-card-body">
                    <div class="table-responsive">
                        <table class="enhanced-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Total Jobs</th>
                                    <th>Completed</th>
                                    <th>In Progress</th>
                                    <th>Revenue</th>
                                    <th>Billed Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyStats->sortKeysDesc() as $date => $dayStats)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($date)->format('M j, Y') }}</td>
                                        <td>{{ $dayStats['total'] }}</td>
                                        <td>{{ $dayStats['completed'] }}</td>
                                        <td>{{ $dayStats['in_progress'] }}</td>
                                        <td>USD ${{ number_format($dayStats['revenue'], 2) }}</td>
                                        <td>{{ number_format($dayStats['billed_hours'], 1) }}h</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Type & Company Distribution -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-cogs me-2"></i>
                        Job Type Distribution
                    </h5>
                </div>
                <div class="content-card-body">
                    <div class="table-responsive">
                        <table class="enhanced-table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                    <th>Avg Hours</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobTypeStats as $type => $typeData)
                                    <tr>
                                        <td>
                                            @include('admin.calllogs.partials.type-badge', ['type' => $type])
                                        </td>
                                        <td>{{ $typeData['count'] }}</td>
                                        <td>{{ $typeData['percentage'] }}%</td>
                                        <td>{{ number_format($typeData['avg_hours'], 1) }}h</td>
                                        <td>USD ${{ number_format($typeData['revenue'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-building me-2"></i>
                        Top Companies
                    </h5>
                    <p class="card-subtitle">Companies with most service requests</p>
                </div>
                <div class="content-card-body">
                    <div class="table-responsive">
                        <table class="enhanced-table">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>Jobs</th>
                                    <th>Completed</th>
                                    <th>Revenue</th>
                                    <th>Last Service</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($companyStats as $company => $companyData)
                                    <tr>
                                        <td>
                                            <strong>{{ $company }}</strong>
                                        </td>
                                        <td>{{ $companyData['total'] }}</td>
                                        <td>{{ $companyData['completed'] }}</td>
                                        <td>USD ${{ number_format($companyData['revenue'], 2) }}</td>
                                        <td>{{ $companyData['last_service'] ? Carbon\Carbon::parse($companyData['last_service'])->format('M j, Y') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Report Modal -->
@if(auth()->user()->role === 'admin')
<div class="modal fade" id="generateReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="generateReportForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="report_type" class="form-label">Report Type</label>
                        <select class="form-control form-control-enhanced" id="report_type" name="report_type" required>
                            <option value="">Select report type...</option>
                            <option value="summary">Summary Report</option>
                            <option value="detailed">Detailed Report</option>
                            <option value="engineer">Engineer Performance</option>
                            <option value="company">Company Analysis</option>
                            <option value="job_type">Job Type Analysis</option>
                            <option value="financial">Financial Report</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="report_date_from" class="form-label">Date From</label>
                                <input type="date" class="form-control form-control-enhanced" id="report_date_from" name="date_from" value="{{ $dateFrom }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="report_date_to" class="form-label">Date To</label>
                                <input type="date" class="form-control form-control-enhanced" id="report_date_to" name="date_to" value="{{ $dateTo }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="report_engineer" class="form-label">Engineer (Optional)</label>
                        <select class="form-control form-control-enhanced" id="report_engineer" name="engineer">
                            <option value="">All Engineers</option>
                            <option value="Benson">Benson</option>
                            <option value="Malvine">Malvine</option>
                            <option value="Mukai">Mukai</option>
                            <option value="Tapera">Tapera</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="report_status" class="form-label">Status (Optional)</label>
                                <select class="form-control form-control-enhanced" id="report_status" name="status">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="assigned">Assigned</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="complete">Complete</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="report_type_filter" class="form-label">Job Type (Optional)</label>
                                <select class="form-control form-control-enhanced" id="report_type_filter" name="type">
                                    <option value="">All Types</option>
                                    <option value="normal">Normal</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="repair">Repair</option>
                                    <option value="installation">Installation</option>
                                    <option value="consultation">Consultation</option>
                                    <option value="emergency">Emergency</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="report_format" class="form-label">Format</label>
                        <select class="form-control form-control-enhanced" id="report_format" name="format" required>
                            <option value="">Select format...</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-enhanced" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-enhanced">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

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
    .content-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 2rem;}
    .content-card-header { padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%); border-bottom: 1px solid var(--border-color);}
    .content-card-header .card-title { font-size: 1.25rem; font-weight: 600; color: var(--primary-green); margin: 0;}
    .card-subtitle { color: var(--light-text); font-size: 0.9rem; margin: 0.25rem 0 0 0;}
    .content-card-body { padding: 2rem;}
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;}
    .stat-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border-color); transition: all 0.3s ease;}
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover);}
    .stat-card-body { padding: 1.5rem; display: flex; align-items: center; gap: 1rem;}
    .stat-icon { width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;}
    .stat-icon.primary { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); }
    .stat-icon.success { background: linear-gradient(135deg, var(--success-green) 0%, var(--primary-green) 100%); }
    .stat-icon.info { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .stat-icon.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .stat-icon.danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .stat-icon i { font-size: 1.5rem; color: var(--white);}
    .stat-content { flex: 1;}
    .stat-number { font-size: 2rem; font-weight: 700; color: var(--dark-text); margin: 0; line-height: 1;}
    .stat-label { color: var(--light-text); font-size: 0.9rem; margin: 0.25rem 0; font-weight: 500;}
    .stat-footer { font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.25rem; color: var(--primary-green);}
    .form-group { margin-bottom: 1.5rem;}
    .form-label { font-weight: 600; color: var(--dark-text); margin-bottom: 0.5rem; display: block;}
    .form-control-enhanced { border: 2px solid var(--border-color); border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.95rem; transition: all 0.3s ease; background: var(--white);}
    .form-control-enhanced:focus { border-color: var(--primary-green); box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1); outline: none;}
    .btn-enhanced { padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; border: none; display: flex; align-items: center; text-decoration: none;}
    .btn-primary.btn-enhanced { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); color: var(--white);}
    .btn-primary.btn-enhanced:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover);}
    .btn-outline-secondary.btn-enhanced { border: 2px solid var(--border-color); color: var(--medium-text); background: transparent;}
    .btn-outline-secondary.btn-enhanced:hover { background: var(--medium-text); color: var(--white);}
    .enhanced-table { width: 100%; border-collapse: separate; border-spacing: 0; margin: 0;}
    .enhanced-table thead th { background: var(--ultra-light-green); color: var(--primary-green); font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 2px solid var(--light-green);}
    .enhanced-table tbody tr { transition: all 0.2s ease; border-bottom: 1px solid var(--border-color);}
    .enhanced-table tbody tr:hover { background: var(--ultra-light-green);}
    .enhanced-table tbody td { padding: 1rem 1.5rem; vertical-align: middle;}
    .avatar-sm { width: 32px; height: 32px;}
    .avatar-initial { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 600; color: var(--primary-green);}
    .bg-light-primary { background-color: var(--ultra-light-green);}
    @media (max-width: 768px) {
        .page-header-content { flex-direction: column; align-items: flex-start; gap: 1rem;}
        .stats-grid { grid-template-columns: 1fr;}
        .content-card-header, .content-card-body { padding: 1rem;}
    }
</style>

@push('scripts')
<script>
    @if(auth()->user()->role === 'admin')
    document.getElementById('generateReportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("admin.call-logs.export") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Network response was not ok');
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'job_cards_report.' + document.getElementById('report_format').value;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            bootstrap.Modal.getInstance(document.getElementById('generateReportModal')).hide();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error generating report');
        });
    });
    @endif
</script>
@endpush
@endsection
