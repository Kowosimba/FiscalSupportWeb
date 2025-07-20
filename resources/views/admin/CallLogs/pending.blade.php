@extends('layouts.calllogs')

@section('title', 'Pending Jobs')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-clock text-warning me-2"></i>
                        Pending Jobs
                    </h5>
                    <p class="text-muted mb-0">Jobs awaiting action or completion</p>
                </div>
                <div class="d-flex align-items-center">
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
                            <select name="technician" class="form-select" onchange="this.form.submit()">
                                <option value="">All Technicians</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ request('technician') == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    <form method="GET">
                        <div class="input-group">
                            <select name="date_range" class="form-select" onchange="this.form.submit()">
                                <option value="">All Dates</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="last_week" {{ request('date_range') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                                <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if($callLogs->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No pending jobs found.
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
                                <th>Date Booked</th>
                                <th>Technician</th>
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
                                    @else
                                        <span class="badge bg-primary">Normal</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $job->date_booked->format('M d, Y') }}
                                    @if($job->date_booked->diffInHours(now()) > 24)
                                        <span class="badge bg-warning text-dark ms-2">Overdue</span>
                                    @endif
                                </td>
                                <td>
                                    @if($job->assignedTo)
                                        <div class="fw-bold text-success">
                                            <i class="fas fa-user-check me-1"></i>
                                            {{ $job->assignedTo->name }}
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-user-slash me-1"></i>
                                            Unassigned
                                        </span>
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
