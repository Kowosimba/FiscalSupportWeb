@extends('layouts.app')

@section('title', 'Job Details - ' . $job->job_card)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Job Details</h1>
            <p class="mb-0 text-muted">{{ $job->job_card }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Jobs
            </a>
            @can('update', $job)
                <a href="{{ route('jobs.edit', $job) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Job
                </a>
            @endcan
            <button class="btn btn-info" onclick="printJob()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Job Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Job Information</h6>
                    <div>
                        @include('admin.calllogs.partials.status-badge', ['status' => $job->status])
                        @include('admin.calllogs.partials.priority-badge', ['priority' => $job->priority])
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th width="30%">Job Card:</th>
                                        <td><strong>{{ $job->job_card }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>ZIMRA Reference:</th>
                                        <td>{{ $job->zimra_ref ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Job Type:</th>
                                        <td>
                                            <span class="badge badge-secondary">{{ ucfirst($job->type) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Date Booked:</th>
                                        <td>{{ $job->date_booked->format('M j, Y - H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date Resolved:</th>
                                        <td>{{ $job->date_resolved ? $job->date_resolved->format('M j, Y - H:i') : 'Not resolved' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount Charged:</th>
                                        <td><strong class="text-success">${{ number_format($job->amount_charged, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th width="30%">Approved By:</th>
                                        <td>{{ $job->approvedBy->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Assigned To:</th>
                                        <td>{{ $job->assignedTo->name ?? 'Not assigned' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time Started:</th>
                                        <td>{{ $job->time_start ? $job->time_start->format('M j, Y - H:i') : 'Not started' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time Finished:</th>
                                        <td>{{ $job->time_finish ? $job->time_finish->format('M j, Y - H:i') : 'Not finished' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Billed Hours:</th>
                                        <td>{{ $job->billed_hours ? $job->billed_hours . ' hours' : 'Not calculated' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Duration:</th>
                                        <td>
                                            @if($job->duration)
                                                {{ floor($job->duration / 60) }}h {{ $job->duration % 60 }}m
                                            @else
                                                Not calculated
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fault Description -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Fault Description</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $job->fault_description }}</p>
                </div>
            </div>

            <!-- Engineer Comments -->
            @if($job->engineer_comments)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Engineer Comments</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $job->engineer_comments }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Customer Information & Actions -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg me-3">
                            <div class="avatar-initial rounded-circle bg-primary">
                                {{ substr($job->customer_name, 0, 2) }}
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $job->customer_name }}</h6>
                            <small class="text-muted">Customer</small>
                        </div>
                    </div>
                    
                    <div class="contact-info">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope text-muted me-2"></i>
                            <a href="mailto:{{ $job->customer_email }}">{{ $job->customer_email }}</a>
                        </div>
                        @if($job->customer_phone)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-phone text-muted me-2"></i>
                                <a href="tel:{{ $job->customer_phone }}">{{ $job->customer_phone }}</a>
                            </div>
                        @endif
                        @if($job->customer_address)
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-muted me-2 mt-1"></i>
                                <span>{{ $job->customer_address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Job Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Job Actions</h6>
                </div>
                <div class="card-body">
                    @can('assign', $job)
                        @if(!$job->assigned_to && $job->status === 'pending')
                            <button class="btn btn-success btn-block mb-2" onclick="assignJob({{ $job->id }})">
                                <i class="fas fa-user-plus"></i> Assign to Technician
                            </button>
                        @endif
                    @endcan
                    
                    @can('updateStatus', $job)
                        @if($job->status === 'assigned')
                            <button class="btn btn-info btn-block mb-2" onclick="updateStatus({{ $job->id }}, 'in_progress')">
                                <i class="fas fa-play"></i> Start Job
                            </button>
                        @elseif($job->status === 'in_progress')
                            <button class="btn btn-primary btn-block mb-2" onclick="updateStatus({{ $job->id }}, 'completed')">
                                <i class="fas fa-check"></i> Mark as Completed
                            </button>
                        @endif
                    @endcan
                    
                    <button class="btn btn-outline-secondary btn-block mb-2" onclick="sendUpdate()">
                        <i class="fas fa-paper-plane"></i> Send Update to Customer
                    </button>
                    
                    <button class="btn btn-outline-info btn-block" onclick="downloadReport()">
                        <i class="fas fa-download"></i> Download Report
                    </button>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Job Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Job Created</h6>
                                <p class="text-muted mb-1">{{ $job->created_at->format('M j, Y - H:i') }}</p>
                                <small>By {{ $job->approvedBy->name }}</small>
                            </div>
                        </div>
                        
                        @if($job->assigned_at)
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Job Assigned</h6>
                                    <p class="text-muted mb-1">{{ $job->assigned_at->format('M j, Y - H:i') }}</p>
                                    <small>To {{ $job->assignedTo->name }}</small>
                                </div>
                            </div>
                        @endif
                        
                        @if($job->started_at)
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Job Started</h6>
                                    <p class="text-muted mb-1">{{ $job->started_at->format('M j, Y - H:i') }}</p>
                                    <small>Work began</small>
                                </div>
                            </div>
                        @endif
                        
                        @if($job->completed_at)
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Job Completed</h6>
                                    <p class="text-muted mb-1">{{ $job->completed_at->format('M j, Y - H:i') }}</p>
                                    <small>Work finished</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .avatar-lg {
        width: 48px;
        height: 48px;
    }
    
    .avatar-initial {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
    }
    
    .contact-info i {
        width: 20px;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e3e6f0;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -22px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #e3e6f0;
    }
    
    .timeline-item.active .timeline-marker {
        box-shadow: 0 0 0 3px #4e73df;
    }
    
    .timeline-content h6 {
        font-size: 0.9rem;
        margin-bottom: 4px;
    }
    
    .timeline-content p {
        font-size: 0.8rem;
        margin-bottom: 2px;
    }
    
    .timeline-content small {
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    @media print {
        .btn-group, .card-header .badge {
            display: none;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function assignJob(jobId) {
        // Implementation for assignment modal
        console.log('Assign job:', jobId);
    }
    
    function updateStatus(jobId, newStatus) {
        if (!confirm('Are you sure you want to update this job status?')) {
            return;
        }
        
        $.ajax({
            url: `/jobs/${jobId}/status`,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Error updating job status: ' + xhr.responseJSON.message);
            }
        });
    }
    
    function sendUpdate() {
        alert('Customer update sent successfully!');
    }
    
    function downloadReport() {
        window.location.href = `/jobs/{{ $job->id }}/report`;
    }
    
    function printJob() {
        window.print();
    }
</script>
@endpush
@endsection
