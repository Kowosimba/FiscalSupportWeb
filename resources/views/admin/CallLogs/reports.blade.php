@extends('layouts.app')

@section('title', 'Job Card Reports')

@section('content')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-card mb-4">
        <div class="page-header-content d-flex justify-content-between align-items-center flex-wrap">
            <div class="header-text">
                <h3 class="page-title mb-1">
                    <i class="fa fa-chart-bar me-2"></i>
                    Job Card Reports
                </h3>
                <p class="page-subtitle mb-0 text-muted">Analytics and reporting dashboard for IT support services</p>
            </div>
            <div class="page-actions mt-2 mt-md-0">
                @if(auth()->user()->role === 'admin')
                    <button type="button" class="btn btn-primary btn-enhanced" data-bs-toggle="modal" data-bs-target="#generateReportModal" aria-label="Generate Job Card Report">
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
            <h5 class="card-title mb-0">
                <i class="fa fa-filter me-2"></i>
                Report Filters
            </h5>
        </div>
        <div class="content-card-body">
            <form method="GET" action="{{ route('admin.call-logs.reports') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-2">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" id="date_from" name="date_from" class="form-control form-control-enhanced" value="{{ $dateFrom }}" max="{{ date('Y-m-d') }}">
                </div>

                <div class="col-12 col-md-2">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" id="date_to" name="date_to" class="form-control form-control-enhanced" value="{{ $dateTo }}" max="{{ date('Y-m-d') }}">
                </div>

                <div class="col-12 col-md-2">
                    <label for="engineer" class="form-label">Engineer</label>
                    <select id="engineer" name="engineer" class="form-control form-control-enhanced" aria-label="Select engineer filter">
                        <option value="">All Engineers</option>
                        @foreach(['Benson', 'Malvine', 'Mukai', 'Tapera'] as $eng)
                            <option value="{{ $eng }}" {{ $engineer == $eng ? 'selected' : '' }}>{{ $eng }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control form-control-enhanced" aria-label="Select status filter">
                        <option value="">All Status</option>
                        @foreach(['pending', 'assigned', 'in_progress', 'complete', 'cancelled'] as $stat)
                            <option value="{{ $stat }}" {{ $status == $stat ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $stat)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label for="type" class="form-label">Job Type</label>
                    <select id="type" name="type" class="form-control form-control-enhanced" aria-label="Select job type filter">
                        <option value="">All Types</option>
                        @foreach(['normal', 'emergency'] as $typeOption)
                            <option value="{{ $typeOption }}" {{ $type == $typeOption ? 'selected' : '' }}>{{ ucfirst($typeOption) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2 d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-enhanced" aria-label="Update report filters">
                        <i class="fa fa-search me-1"></i>Update Report
                    </button>
                    <a href="{{ route('admin.call-logs.reports') }}" class="btn btn-outline-secondary btn-enhanced" aria-label="Reset filters">
                        <i class="fa fa-times me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-4">
        @php
            $statCards = [
                ['icon'=>'clipboard', 'label'=>'Total Jobs', 'value'=>$stats['total_jobs'] ?? 0, 'footer'=>'Selected period', 'iconClass'=>'primary'],
                ['icon'=>'check-circle', 'label'=>'Completed', 'value'=>$stats['completed_jobs'] ?? 0, 'footer'=>($stats['total_jobs'] ?? 0) > 0 ? round(($stats['completed_jobs'] ?? 0)/max($stats['total_jobs'],1)*100,1).'%' : '0% completion rate', 'iconClass'=>'success'],
                ['icon'=>'dollar-sign', 'label'=>'Total Revenue', 'value'=>"USD $".number_format($stats['total_revenue'] ?? 0, 2), 'footer'=>'From completed jobs', 'iconClass'=>'info'],
                ['icon'=>'clock', 'label'=>'Avg Completion Time', 'value'=>($stats['avg_completion_time'] ?? 0).'h', 'footer'=>'Per completed job', 'iconClass'=>'warning'],
                ['icon'=>'tools', 'label'=>'Total Billed Hours', 'value'=>($stats['total_billed_hours'] ?? 0).'h', 'footer'=>'Engineering time', 'iconClass'=>'purple'],
                ['icon'=>'exclamation-triangle', 'label'=>'Emergency Jobs', 'value'=>$stats['emergency_jobs'] ?? 0, 'footer'=>'High priority', 'iconClass'=>'danger'],
            ];
        @endphp
        @foreach($statCards as $card)
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-icon {{ $card['iconClass'] }}">
                        <i class="fa fa-{{ $card['icon'] }}"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $card['value'] }}</div>
                        <div class="stat-label">{{ $card['label'] }}</div>
                        <div class="stat-footer">
                            <i class="fa fa-calendar me-1"></i>
                            {!! $card['footer'] !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Engineer Performance & Daily Statistics -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-users me-2"></i>
                        Engineer Performance
                    </h5>
                    <p class="card-subtitle mb-0">Performance metrics by engineer</p>
                </div>
                <div class="content-card-body p-0">
                    <div class="table-responsive">
                        <table class="enhanced-table mb-0">
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
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-sm">
                                                    <div class="avatar-initial rounded-circle bg-light-primary" title="{{ $engineerName }}">
                                                        {{ strtoupper($engineerName[0]) }}
                                                    </div>
                                                </div>
                                                {{ $engineerName }}
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

        <div class="col-lg-6">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-chart-line me-2"></i>
                        Daily Statistics
                    </h5>
                    <p class="card-subtitle mb-0">Daily job volume and completion</p>
                </div>
                <div class="content-card-body p-0">
                    <div class="table-responsive">
                        <table class="enhanced-table mb-0">
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
                                        <td>{{ \Carbon\Carbon::parse($date)->format('M j, Y') }}</td>
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
    <div class="row g-4 mt-4">
        <div class="col-lg-6">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-cogs me-2"></i>
                        Job Type Distribution
                    </h5>
                </div>
                <div class="content-card-body p-0">
                    <div class="table-responsive">
                        <table class="enhanced-table mb-0">
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
                                        <td>@include('admin.calllogs.partials.type-badge', ['type' => $type])</td>
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
                    <p class="card-subtitle mb-0">Companies with most service requests</p>
                </div>
                <div class="content-card-body p-0">
                    <div class="table-responsive">
                        <table class="enhanced-table mb-0">
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
                                        <td><strong>{{ $company }}</strong></td>
                                        <td>{{ $companyData['total'] }}</td>
                                        <td>{{ $companyData['completed'] }}</td>
                                        <td>USD ${{ number_format($companyData['revenue'], 2) }}</td>
                                        <td>{{ $companyData['last_service'] ? \Carbon\Carbon::parse($companyData['last_service'])->format('M j, Y') : 'N/A' }}</td>
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
<div class="modal fade" id="generateReportModal" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="generateReportForm" method="POST" action="{{ route('admin.call-logs.export') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="generateReportModalLabel">Generate Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Report Type --}}
                    <div class="mb-3">
                        <label for="report_type" class="form-label">Report Type</label>
                        <select id="report_type" name="report_type" class="form-control form-control-enhanced" required>
                            <option value="" disabled selected>Select report type...</option>
                            <option value="summary">Summary Report</option>
                            <option value="detailed">Detailed Report</option>
                            <option value="engineer">Engineer Performance</option>
                            <option value="company">Company Analysis</option>
                            <option value="job_type">Job Type Analysis</option>
                            <option value="financial">Financial Report</option>
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="report_date_from" class="form-label">Date From</label>
                            <input type="date" id="report_date_from" name="date_from" class="form-control form-control-enhanced" value="{{ $dateFrom }}" required max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="report_date_to" class="form-label">Date To</label>
                            <input type="date" id="report_date_to" name="date_to" class="form-control form-control-enhanced" value="{{ $dateTo }}" required max="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="report_engineer" class="form-label">Engineer (Optional)</label>
                            <select id="report_engineer" name="engineer" class="form-control form-control-enhanced" aria-label="Select engineer filter">
                                <option value="">All Engineers</option>
                                @foreach(['Benson', 'Malvine', 'Mukai', 'Tapera'] as $eng)
                                    <option value="{{ $eng }}">{{ $eng }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="report_status" class="form-label">Status (Optional)</label>
                            <select id="report_status" name="status" class="form-control form-control-enhanced" aria-label="Select status filter">
                                <option value="">All Status</option>
                                @foreach(['pending', 'assigned', 'in_progress', 'complete', 'cancelled'] as $stat)
                                    <option value="{{ $stat }}">{{ ucfirst(str_replace('_', ' ', $stat)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="report_type_filter" class="form-label">Job Type (Optional)</label>
                            <select id="report_type_filter" name="type" class="form-control form-control-enhanced" aria-label="Select job type filter">
                                <option value="">All Types</option>
                                @foreach(['normal', 'maintenance', 'repair', 'installation', 'consultation', 'emergency'] as $typeOption)
                                    <option value="{{ $typeOption }}">{{ ucfirst($typeOption) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="report_format" class="form-label">Format</label>
                            <select id="report_format" name="format" class="form-control form-control-enhanced" required>
                                <option value="" disabled selected>Select format...</option>
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>
                </div> <!-- modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-enhanced" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-enhanced">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
</div>

@push('styles')
<style>
    /* Keep your existing styles or add more specific styling adjustments here */
    .form-control-enhanced {
        border-radius: 8px;
        border: 2px solid var(--border-color, #ccc);
        padding: 0.5rem 1rem;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-control-enhanced:focus {
        border-color: var(--primary-green, #22c55e);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
        outline: none;
    }
    .btn-enhanced {
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    .btn-enhanced:hover, .btn-enhanced:focus {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3);
        outline: none;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    .stat-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color, #ddd);
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover, 0 10px 25px rgba(34, 197, 94, 0.2));
    }
    .stat-icon {
        font-size: 2rem;
        padding: 1rem;
        border-radius: 12px;
        color: var(--white);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    /* Define your color variables or use existing CSS variables */
    .stat-icon.primary { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
    .stat-icon.success { background: linear-gradient(135deg, #4ade80 0%, #16a34a 100%); }
    .stat-icon.info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .stat-icon.warning { background: linear-gradient(135deg, #fbbf24 0%, #ca8a04 100%); }
    .stat-icon.purple { background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%); }
    .stat-icon.danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
</style>
@endpush

@push('scripts')
@if(auth()->user()->role === 'admin')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const generateReportForm = document.getElementById('generateReportForm');
    if (!generateReportForm) return;

    generateReportForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route("admin.call-logs.export") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData,
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;

            const fileExt = document.getElementById('report_format').value || 'pdf';
            a.download = `job_cards_report.${fileExt}`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);

            bootstrap.Modal.getInstance(document.getElementById('generateReportModal')).hide();
        })
        .catch(err => {
            console.error('Error generating report:', err);
            alert('An error occurred while generating the report. Please try again.');
        });
    });
});
</script>
@endif
@endpush

@endsection
