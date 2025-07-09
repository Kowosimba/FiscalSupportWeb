@extends('layouts.calllogs')

@section('title', 'Call Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Call Reports</h1>
            <p class="text-muted">Analytics and reporting dashboard</p>
        </div>
        <div class="page-actions">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                <i class="fas fa-file-export"></i> Generate Report
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card mb-4">
        <div class="filter-header">
            <h6 class="filter-title">
                <i class="fas fa-filter me-2"></i>Report Filters
            </h6>
        </div>
        <div class="filter-body">
            <form method="GET" action="{{ route('calls.reports') }}">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date From</label>
                        <input type="date" class="enhanced-input" name="date_from" value="{{ $dateFrom }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Date To</label>
                        <input type="date" class="enhanced-input" name="date_to" value="{{ $dateTo }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Technician</label>
                        <div class="select-wrapper">
                            <select class="enhanced-select" name="technician">
                                <option value="">All Technicians</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ $technician == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="select-wrapper">
                            <select class="enhanced-select" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Update Report
                        </button>
                        <a href="{{ route('calls.reports') }}" class="btn btn-outline">
                            <i class="fas fa-times me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-phone-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['total_calls'] }}</div>
                <div class="stat-label">Total Calls</div>
                <div class="stat-footer">
                    <i class="fas fa-calendar"></i> Selected period
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['completed_calls'] }}</div>
                <div class="stat-label">Completed</div>
                <div class="stat-footer">
                    <i class="fas fa-percentage"></i> {{ $stats['total_calls'] > 0 ? round(($stats['completed_calls'] / $stats['total_calls']) * 100, 1) : 0 }}% completion rate
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">${{ number_format($stats['total_revenue'], 2) }}</div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-footer">
                    <i class="fas fa-chart-line"></i> From completed calls
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['avg_resolution_time'] }}h</div>
                <div class="stat-label">Avg Resolution Time</div>
                <div class="stat-footer">
                    <i class="fas fa-stopwatch"></i> Per completed call
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Technician Performance -->
        <div class="col-lg-6">
            <div class="calls-card">
                <div class="calls-card-header">
                    <div class="header-content">
                        <h6 class="card-title">
                            <i class="fas fa-users me-2"></i>Technician Performance
                        </h6>
                        <p class="card-subtitle">Performance metrics by technician</p>
                    </div>
                </div>
                <div class="calls-card-body">
                    <div class="table-responsive">
                        <table class="enhanced-table">
                            <thead>
                                <tr>
                                    <th>Technician</th>
                                    <th>Total</th>
                                    <th>Completed</th>
                                    <th>Revenue</th>
                                    <th>Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($technicianStats as $techId => $techStats)
                                    @php
                                        $technician = $technicians->find($techId);
                                    @endphp
                                    @if($technician)
                                        <tr>
                                            <td>{{ $technician->name }}</td>
                                            <td>{{ $techStats['total'] }}</td>
                                            <td>{{ $techStats['completed'] }}</td>
                                            <td>${{ number_format($techStats['revenue'], 2) }}</td>
                                            <td>{{ $techStats['hours'] }}h</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Statistics -->
        <div class="col-lg-6">
            <div class="calls-card">
                <div class="calls-card-header">
                    <div class="header-content">
                        <h6 class="card-title">
                            <i class="fas fa-chart-line me-2"></i>Daily Statistics
                        </h6>
                        <p class="card-subtitle">Daily call volume and completion</p>
                    </div>
                </div>
                <div class="calls-card-body">
                    <div class="table-responsive">
                        <table class="enhanced-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Total Calls</th>
                                    <th>Completed</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyStats->sortKeysDesc() as $date => $dayStats)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($date)->format('M j, Y') }}</td>
                                        <td>{{ $dayStats['total'] }}</td>
                                        <td>{{ $dayStats['completed'] }}</td>
                                        <td>${{ number_format($dayStats['revenue'], 2) }}</td>
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
                        <select class="form-select" id="report_type" name="report_type" required>
                            <option value="">Select report type...</option>
                            <option value="summary">Summary Report</option>
                            <option value="detailed">Detailed Report</option>
                            <option value="technician">Technician Performance</option>
                            <option value="revenue">Revenue Report</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="report_date_from" class="form-label">Date From</label>
                                <input type="date" class="form-control" id="report_date_from" name="date_from" value="{{ $dateFrom }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="report_date_to" class="form-label">Date To</label>
                                <input type="date" class="form-control" id="report_date_to" name="date_to" value="{{ $dateTo }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="report_technician" class="form-label">Technician (Optional)</label>
                        <select class="form-select" id="report_technician" name="technician">
                            <option value="">All Technicians</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="report_format" class="form-label">Format</label>
                        <select class="form-select" id="report_format" name="format" required>
                            <option value="">Select format...</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('generateReportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("calls.reports.generate") }}', {
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
                bootstrap.Modal.getInstance(document.getElementById('generateReportModal')).hide();
                alert('Report generated successfully!');
            } else {
                alert('Error generating report');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error generating report');
        });
    });
</script>
@endpush
@endsection
