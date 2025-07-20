@extends('layouts.calllogs')

@section('title', 'Unassigned Jobs')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock text-warning me-2"></i>
                        Unassigned Jobs
                    </h5>
                    <p class="text-muted mb-0">Jobs waiting to be assigned to technicians</p>
                </div>
                <div class="d-flex align-items-center">
                    <form method="GET" class="me-3">
                        <div class="input-group">
                            <select name="type" class="form-select" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                <option value="normal" {{ request('type') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="emergency" {{ request('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
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
                                <option value="overdue" {{ request('date_range') == 'overdue' ? 'selected' : '' }}>Overdue</option>
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
                    No unassigned jobs found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job ID</th>
                                <th>Customer</th>
                                <th>Fault Description</th>
                                <th>Type</th>
                                <th>Date Booked</th>
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
                                    <div class="fw-bold">{{ $job->customer_name }}</div>
                                    <small class="text-muted">{{ $job->customer_email }}</small>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $job->fault_description }}">
                                        {{ $job->fault_description }}
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
                                <td class="fw-bold">
                                    ${{ number_format($job->amount_charged, 2) }}
                                </td>
                                <td>
                                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'manager')
                                        <button class="btn btn-sm btn-outline-primary assign-btn" 
                                                data-job-id="{{ $job->id }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#assignModal">
                                            <i class="fas fa-user-plus me-1"></i>
                                            Assign
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.call-logs.show', $job) }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
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

<!-- Assign Technician Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="assignModalLabel">Assign Technician</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="job_id" id="job_id">
                    
                    <div class="mb-3">
                        <label for="technician" class="form-label">Select Technician</label>
                        <select class="form-select" id="technician" name="engineer" required>
                            <option value="">Select Technician</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="assignment_notes" class="form-label">Assignment Notes</label>
                        <textarea class="form-control" id="assignment_notes" name="assignment_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Job</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle assign button click
        $('.assign-btn').click(function() {
            const jobId = $(this).data('job-id');
            $('#job_id').val(jobId);
            $('#assignForm').attr('action', `/admin/call-logs/${jobId}/assign`);
        });

        // Handle form submission
        $('#assignForm').submit(function(e) {
            e.preventDefault();
            
            const form = $(this);
            const url = form.attr('action');
            const method = 'POST';
            const data = form.serialize();
            
            $.ajax({
                url: url,
                type: method,
                data: data,
                success: function(response) {
                    if(response.success) {
                        $('#assignModal').modal('hide');
                        showToast('success', response.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'An error occurred';
                    showToast('error', error);
                }
            });
        });

        function showToast(type, message) {
            const toast = `<div class="toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;
            
            $('.toast-container').append(toast);
            $('.toast').toast('show');
            
            setTimeout(() => {
                $('.toast').toast('hide').remove();
            }, 3000);
        }
    });
</script>
@endsection

@section('styles')
<style>
    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1100;
    }
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .badge {
        font-size: 0.85em;
    }
</style>
@endsection