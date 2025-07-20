@extends('layouts.calllogs')

@section('title', 'Job Card Details - ' . $callLog->job_card)

@section('content')

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header-card mb-4">
        <div class="page-header-content">
            <div class="header-text">
                <h3 class="page-title">
                    <i class="fa fa-clipboard me-2"></i>
                    Job Card Details
                </h3>
                <p class="page-subtitle">{{ $callLog->job_card }}</p>
            </div>
            <div class="header-actions d-flex gap-2">
                <a href="{{ route('admin.call-logs.index') }}" class="btn btn-outline-secondary btn-enhanced">
                    <i class="fa fa-arrow-left me-2"></i>
                    Back to Job Cards
                </a>
                @if((auth()->user()->role === 'engineer' && $callLog->engineer === auth()->user()->name) || in_array(auth()->user()->role, ['admin', 'accounts']))
                    <a href="{{ route('admin.call-logs.edit', $callLog) }}" class="btn btn-primary btn-enhanced">
                        <i class="fa fa-edit me-2"></i>
                        Edit Job Card
                    </a>
                @endif
                <button class="btn btn-outline-secondary btn-enhanced" onclick="printJobCard()">
                    <i class="fa fa-print me-2"></i>
                    Print
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Job Information -->
        <div class="col-lg-8">
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">
                            <i class="fa fa-info-circle me-2"></i>
                            Job Information
                        </h5>
                        <div class="d-flex gap-2">
                            @include('admin.calllogs.partials.status-badge', ['status' => $callLog->status])
                            
                        </div>
                    </div>
                </div>
                <div class="content-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="details-table">
                                <tbody>
                                    <tr>
                                        <th width="35%">Job Card Number:</th>
                                        <td><strong class="job-card-number">{{ $callLog->job_card }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>ZIMRA Reference:</th>
                                        <td>{{ $callLog->zimra_ref ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Job Type:</th>
                                        <td>
                                            @include('admin.calllogs.partials.type-badge', ['type' => $callLog->type])
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Date Booked:</th>
                                        <td>{{ \Carbon\Carbon::parse($callLog->date_booked)->format('M j, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date Resolved:</th>
                                        <td>{{ $callLog->date_resolved ? \Carbon\Carbon::parse($callLog->date_resolved)->format('M j, Y') : 'Not resolved' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount Charged:</th>
                                        <td><strong class="text-success">USD ${{ number_format($callLog->amount_charged, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="details-table">
                                <tbody>
                                    <tr>
                                        <th width="35%">Approved By:</th>
                                        <td>{{ $callLog->approved_by ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Assigned Engineer:</th>
                                        <td>{{ $callLog->engineer ?? 'Not assigned' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time Start:</th>
                                        <td>{{ $callLog->time_start ?? 'Not started' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time Finish:</th>
                                        <td>{{ $callLog->time_finish ?? 'Not finished' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Billed Hours:</th>
                                        <td>{{ $callLog->billed_hours ? $callLog->billed_hours . ' hours' : 'Not calculated' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Booked By:</th>
                                        <td>{{ $callLog->booked_by ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fault Description -->
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-exclamation-circle me-2"></i>
                        Fault Description
                    </h5>
                </div>
                <div class="content-card-body">
                    <p class="mb-0">{{ $callLog->fault_description ?: 'No fault description provided.' }}</p>
                </div>
            </div>

            <!-- Engineer Comments -->
            @if($callLog->engineer_comments)
                <div class="content-card mb-4">
                    <div class="content-card-header">
                        <h5 class="card-title">
                            <i class="fa fa-tools me-2"></i>
                            Engineer Comments
                        </h5>
                    </div>
                    <div class="content-card-body">
                        <p class="mb-0">{{ $callLog->engineer_comments }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Company Information & Actions -->
        <div class="col-lg-4">
            <!-- Company Information -->
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-building me-2"></i>
                        Company Information
                    </h5>
                </div>
                <div class="content-card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="company-avatar">
                            <div class="avatar-initial">
                                {{ substr($callLog->company_name, 0, 2) }}
                            </div>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">{{ $callLog->company_name }}</h6>
                            <small class="text-muted">Client Company</small>
                        </div>
                    </div>
                    
                    @if($callLog->zimra_ref)
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fa fa-file-alt text-muted me-2"></i>
                                <span>ZIMRA: {{ $callLog->zimra_ref }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Job Actions -->
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-cog me-2"></i>
                        Job Actions
                    </h5>
                </div>
                <div class="content-card-body">
                    @if(in_array(auth()->user()->role, ['admin', 'accounts']))
                        @if(!$callLog->engineer && $callLog->status !== 'complete')
                            <button class="btn btn-success btn-enhanced w-100 mb-2" onclick="assignJobCard({{ $callLog->id }})">
                                <i class="fa fa-user-plus me-2"></i>
                                Assign Engineer
                            </button>
                        @endif
                    @endif
                    
                    @if($callLog->engineer === auth()->user()->name)
                        @if($callLog->status === 'assigned')
                            <button class="btn btn-info btn-enhanced w-100 mb-2" onclick="updateStatus({{ $callLog->id }}, 'in_progress')">
                                <i class="fa fa-play me-2"></i>
                                Start Work
                            </button>
                        @elseif($callLog->status === 'in_progress')
                            <button class="btn btn-primary btn-enhanced w-100 mb-2" onclick="updateStatus({{ $callLog->id }}, 'complete')">
                                <i class="fa fa-check me-2"></i>
                                Mark as Complete
                            </button>
                        @endif
                    @endif
                    
                    <button class="btn btn-outline-secondary btn-enhanced w-100 mb-2" onclick="sendUpdate()">
                        <i class="fa fa-paper-plane me-2"></i>
                        Send Update to Client
                    </button>
                    
                    @if(auth()->user()->role === 'admin')
                        <button class="btn btn-outline-info btn-enhanced w-100" onclick="downloadReport()">
                            <i class="fa fa-download me-2"></i>
                            Download Report
                        </button>
                    @endif
                </div>
            </div>

            <!-- Job Timeline -->
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="card-title">
                        <i class="fa fa-history me-2"></i>
                        Job Timeline
                    </h5>
                </div>
                <div class="content-card-body">
                    <div class="timeline">
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Job Created</h6>
                                <p class="text-muted mb-1">{{ \Carbon\Carbon::parse($callLog->date_booked)->format('M j, Y') }}</p>
                                <small>By {{ $callLog->approved_by ?? 'System' }}</small>
                            </div>
                        </div>
                        
                        @if($callLog->engineer && $callLog->status !== 'pending')
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Engineer Assigned</h6>
                                    <p class="text-muted mb-1">{{ \Carbon\Carbon::parse($callLog->updated_at)->format('M j, Y') }}</p>
                                    <small>To {{ $callLog->engineer }}</small>
                                </div>
                            </div>
                        @endif
                        
                        @if($callLog->status === 'in_progress' || $callLog->status === 'complete')
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Work Started</h6>
                                    <p class="text-muted mb-1">{{ $callLog->time_start ?? 'Time not recorded' }}</p>
                                    <small>Work began</small>
                                </div>
                            </div>
                        @endif
                        
                        @if($callLog->status === 'complete')
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Job Completed</h6>
                                    <p class="text-muted mb-1">{{ $callLog->date_resolved ? \Carbon\Carbon::parse($callLog->date_resolved)->format('M j, Y') : 'Date not recorded' }}</p>
                                    <small>{{ $callLog->time_finish ? 'Finished at ' . $callLog->time_finish : 'Job completed' }}</small>
                                </div>
                            </div>
                        @endif

                        @if($callLog->status === 'cancelled')
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Job Cancelled</h6>
                                    <p class="text-muted mb-1">{{ \Carbon\Carbon::parse($callLog->updated_at)->format('M j, Y') }}</p>
                                    <small>Job was cancelled</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assignment Modal -->
<div class="modal fade" id="assignJobCardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Job to Engineer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="assignJobCardForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="engineer" class="form-label">Select Engineer</label>
                        <select class="form-control form-control-enhanced" id="engineer" name="engineer" required>
                            <option value="">Choose engineer...</option>
                            <option value="Benson">Benson</option>
                            <option value="Malvine">Malvine</option>
                            <option value="Mukai">Mukai</option>
                            <option value="Tapera">Tapera</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-enhanced" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-enhanced">Assign Job</button>
                </div>
            </form>
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
    .content-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border-color);}
    .content-card-header { padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%); border-bottom: 1px solid var(--border-color);}
    .content-card-header .card-title { font-size: 1.25rem; font-weight: 600; color: var(--primary-green); margin: 0;}
    .content-card-body { padding: 2rem;}
    .details-table { width: 100%; border-collapse: collapse;}
    .details-table th { padding: 0.75rem 0; color: var(--medium-text); font-weight: 600; border-bottom: 1px solid var(--border-color);}
    .details-table td { padding: 0.75rem 0; color: var(--dark-text); border-bottom: 1px solid var(--border-color);}
    .job-card-number { font-family: 'Monaco', 'Menlo', monospace; background: var(--light-green); padding: 0.25rem 0.5rem; border-radius: 4px; color: var(--primary-green-dark); font-weight: 600; border: 1px solid var(--secondary-green);}
    .company-avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); display: flex; align-items: center; justify-content: center;}
    .avatar-initial { color: var(--white); font-weight: 600; text-transform: uppercase; font-size: 1.1rem;}
    .contact-info { display: flex; flex-direction: column; gap: 0.75rem;}
    .contact-item { display: flex; align-items: center;}
    .contact-item i { width: 20px; flex-shrink: 0;}
    .btn-enhanced { padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; border: none; display: flex; align-items: center; justify-content: center; text-decoration: none;}
    .btn-primary.btn-enhanced { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); color: var(--white);}
    .btn-primary.btn-enhanced:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover);}
    .btn-success.btn-enhanced { background: linear-gradient(135deg, var(--success-green) 0%, var(--primary-green) 100%); color: var(--white);}
    .btn-success.btn-enhanced:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover);}
    .btn-info.btn-enhanced { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: var(--white);}
    .btn-info.btn-enhanced:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover);}
    .btn-outline-secondary.btn-enhanced { border: 2px solid var(--border-color); color: var(--medium-text); background: transparent;}
    .btn-outline-secondary.btn-enhanced:hover { background: var(--medium-text); color: var(--white);}
    .btn-outline-info.btn-enhanced { border: 2px solid #3b82f6; color: #3b82f6; background: transparent;}
    .btn-outline-info.btn-enhanced:hover { background: #3b82f6; color: var(--white);}
    .timeline { position: relative; padding-left: 30px;}
    .timeline::before { content: ''; position: absolute; left: 15px; top: 0; bottom: 0; width: 2px; background-color: var(--border-color);}
    .timeline-item { position: relative; margin-bottom: 20px;}
    .timeline-marker { position: absolute; left: -22px; width: 12px; height: 12px; border-radius: 50%; border: 3px solid var(--white); box-shadow: 0 0 0 3px var(--border-color);}
    .timeline-item.active .timeline-marker { box-shadow: 0 0 0 3px var(--primary-green);}
    .timeline-content h6 { font-size: 0.9rem; margin-bottom: 4px; color: var(--dark-text);}
    .timeline-content p { font-size: 0.8rem; margin-bottom: 2px;}
    .timeline-content small { font-size: 0.75rem; color: var(--light-text);}
    .bg-success { background-color: var(--success-green) !important;}
    .bg-info { background-color: #3b82f6 !important;}
    .bg-warning { background-color: #f59e0b !important;}
    .bg-danger { background-color: #dc2626 !important;}
    .text-success { color: var(--success-green) !important;}
    .header-actions { display: flex; gap: 0.5rem;}
    .form-control-enhanced { border: 2px solid var(--border-color); border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.95rem; transition: all 0.3s ease; background: var(--white);}
    .form-control-enhanced:focus { border-color: var(--primary-green); box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1); outline: none;}
    .form-label { font-weight: 600; color: var(--dark-text); margin-bottom: 0.5rem; display: block;}
    @media (max-width: 768px) {
        .page-header-content { flex-direction: column; align-items: flex-start; gap: 1rem;}
        .header-actions { flex-direction: column; width: 100%;}
        .content-card-header, .content-card-body { padding: 1rem;}
        .details-table th, .details-table td { padding: 0.5rem 0;}
    }
    @media print {
        .header-actions, .content-card-header .badge { display: none;}
    }
</style>

@push('scripts')
<script>
    let currentJobCardId = {{ $callLog->id }};
    
    function assignJobCard(jobCardId) {
        const modal = new bootstrap.Modal(document.getElementById('assignJobCardModal'));
        modal.show();
    }
    
    function updateStatus(jobCardId, newStatus) {
        if (!confirm('Are you sure you want to update this job status?')) {
            return;
        }
        
        fetch(`{{ route('admin.call-logs.index') }}/${jobCardId}/status`, {
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
    
    function sendUpdate() {
        alert('Client update sent successfully!');
    }
    
    function downloadReport() {
        window.location.href = `{{ route('admin.call-logs.export') }}?job_card={{ $callLog->job_card }}`;
    }
    
    function printJobCard() {
        window.print();
    }
    
    document.getElementById('assignJobCardForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(`{{ route('admin.call-logs.index') }}/${currentJobCardId}/assign`, {
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('assignJobCardModal'));
                modal.hide();
                location.reload();
            } else {
                alert('Error assigning job');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error assigning job');
        });
    });
</script>
@endpush
@endsection
