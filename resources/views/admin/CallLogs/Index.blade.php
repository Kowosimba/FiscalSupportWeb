@extends('layouts.calllogs')

@section('title', 'Job Management - Call Logs')

@section('content')

<style>
        /* Dashboard Navigation */
        .dashboard-nav-wrapper {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 0.5rem;
            margin-bottom: 2rem;
        }

        .panel-nav {
            border: none;
            gap: 0.5rem;
        }

        .panel-nav .nav-link {
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            color: var(--light-text);
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .panel-nav .nav-link:hover {
            background: var(--hover-bg);
            color: var(--medium-text);
        }

        .panel-nav .nav-link.active {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
            color: var(--white);
            box-shadow: var(--shadow-hover);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .stat-card-body {
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon i {
            font-size: 1.25rem;
            color: var(--white);
        }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-text);
            margin: 0;
            line-height: 1;
        }

        .stat-label {
            color: var(--light-text);
            font-size: 0.85rem;
            margin: 0.25rem 0;
            font-weight: 500;
        }

        .stat-change {
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .text-success {
            color: var(--primary-green);
        }

        /* Tickets Card */
        .tickets-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .tickets-card-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-green);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .header-content .card-subtitle {
            color: var(--light-text);
            font-size: 0.9rem;
            margin: 0.25rem 0 0 0;
        }

        .tickets-card-header .btn {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
            color: var(--white);
        }

        .tickets-card-header .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-hover);
        }

        .tickets-card-body {
            padding: 0;
        }

        /* Enhanced Table */
        .enhanced-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .enhanced-table thead th {
            background: var(--ultra-light-green);
            color: var(--primary-green);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid var(--light-green);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .enhanced-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--border-color);
        }

        .enhanced-table tbody tr:last-child {
            border-bottom: none;
        }

        .enhanced-table tbody tr:hover {
            background: var(--ultra-light-green);
        }

        .enhanced-table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }

        .ticket-id {
            font-family: 'Monaco', 'Menlo', monospace;
            background: var(--light-green);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            color: var(--primary-green-dark);
            font-weight: 600;
            border: 1px solid var(--secondary-green);
        }

        .ticket-subject {
            font-weight: 500;
            color: var(--dark-text);
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .customer-name {
            color: var(--medium-text);
            font-weight: 500;
        }

        .priority-badge, .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .priority-high {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #fecaca;
        }

        .priority-medium {
            background: #FFFBEB;
            color: #D97706;
            border: 1px solid #fde68a;
        }

        .priority-low {
            background: var(--light-green);
            color: var(--primary-green);
            border: 1px solid var(--accent-green);
        }

        .status-in_progress {
            background: var(--light-green);
            color: var(--primary-green-dark);
            border: 1px solid var(--accent-green);
        }

        .status-resolved {
            background: var(--ultra-light-green);
            color: var(--primary-green);
            border: 1px solid var(--secondary-green);
        }

        .status-pending {
            background: #FFFBEB;
            color: #D97706;
            border: 1px solid #fde68a;
        }

        .update-time {
            color: var(--light-text);
            font-size: 0.875rem;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .view-btn {
            background: var(--light-green);
            color: var(--primary-green);
        }

        .view-btn:hover {
            background: var(--primary-green);
            color: var(--white);
            transform: translateY(-1px);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .empty-content i {
            font-size: 3rem;
            color: var(--light-text);
            margin-bottom: 1rem;
        }

        .empty-content p {
            color: var(--light-text);
            font-size: 1.1rem;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stat-card-body {
                padding: 1rem;
            }

            .tickets-card-header {
                padding: 1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .enhanced-table {
                font-size: 0.875rem;
            }

            .enhanced-table th,
            .enhanced-table td {
                padding: 0.75rem 1rem;
            }
        }
    </style>

{{-- Dashboard Navigation Tabs --}}
<div class="dashboard-nav-wrapper mb-4">
    <ul class="panel-nav nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.index') || request()->routeIs('admin.tickets.*') ? 'active' : '' }}"
               href="{{ route('admin.index') }}">
                <i class="fa fa-tasks me-2"></i>
                Faults Allocation
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('content.*') || request()->routeIs('blogs.*') || request()->routeIs('admin.faqs.*') || request()->routeIs('admin.services.*') || request()->routeIs('admin.subscribers.*') || request()->routeIs('admin.newsletters.*') || request()->routeIs('faq-categories.*') ? 'active' : '' }}"
               href="{{ route('admin.content.index') }}">
                <i class="fa fa-cog me-2"></i>
                Manage Content
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.call-logs.*') ? 'active' : '' }}" 
               href="{{ route('calls.dashboard') }}">
                <i class="fa fa-phone me-2"></i>
                Call Logs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
               href="{{ route('admin.contacts.index') }}">
                <i class="fa fa-users me-2"></i>
                Customer Contacts
            </a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Job Management System</h1>
            <p class="mb-0 text-muted">Manage and track all service requests</p>
        </div>
        @can('create', App\Models\Job::class)
            <a href="{{ route('jobs.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Job
            </a>
        @endcan
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Jobs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    @include('admin.calllogs.partials.filters')

    <!-- Jobs Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Jobs List</h6>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshTable()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportJobs()">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="jobsTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Job Card</th>
                            <th>Customer</th>
                            <th>Fault Description</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Date Booked</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr>
                                <td>
                                    <strong>{{ $job->job_card }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $job->zimra_ref ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $job->customer_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $job->customer_email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="job-description">
                                        {{ Str::limit($job->fault_description, 50) }}
                                        @if(strlen($job->fault_description) > 50)
                                            <a href="#" class="text-primary" onclick="showFullDescription('{{ $job->id }}')">
                                                Read more
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @include('admin.calllogs.partials.priority-badge', ['priority' => $job->priority])
                                </td>
                                <td>
                                    @include('admin.calllogs.partials.status-badge', ['status' => $job->status])
                                </td>
                                <td>
                                    @if($job->assignedTo)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-initial rounded-circle bg-light-primary">
                                                    {{ substr($job->assignedTo->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $job->assignedTo->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $job->assignedTo->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        {{ $job->date_booked->format('M j, Y') }}
                                        <br>
                                        <small class="text-muted">{{ $job->date_booked->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <strong>${{ number_format($job->amount_charged, 2) }}</strong>
                                    @if($job->billed_hours)
                                        <br>
                                        <small class="text-muted">{{ $job->billed_hours }}h</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @can('update', $job)
                                            <a href="{{ route('jobs.edit', $job) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        
                                        @can('assign', $job)
                                            @if(!$job->assigned_to && $job->status === 'pending')
                                                <button class="btn btn-sm btn-outline-success" onclick="assignJob({{ $job->id }})" title="Assign">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
                                            @endif
                                        @endcan
                                        
                                        @can('updateStatus', $job)
                                            @if($job->status === 'assigned')
                                                <button class="btn btn-sm btn-success" onclick="updateStatus({{ $job->id }}, 'in_progress')" title="Start">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            @elseif($job->status === 'in_progress')
                                                <button class="btn btn-sm btn-primary" onclick="updateStatus({{ $job->id }}, 'completed')" title="Complete">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No jobs found</h5>
                                        <p class="text-muted">Try adjusting your filters or create a new job</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $jobs->firstItem() ?? 0 }} to {{ $jobs->lastItem() ?? 0 }} of {{ $jobs->total() }} results
                </div>
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Assignment Modal -->
<div class="modal fade" id="assignJobModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Job to Technician</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="assignJobForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="assigned_to">Select Technician</label>
                        <select class="form-control" id="assigned_to" name="assigned_to" required>
                            <option value="">Choose technician...</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}">{{ $technician->name }} ({{ $technician->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="assignment_notes">Assignment Notes (Optional)</label>
                        <textarea class="form-control" id="assignment_notes" name="assignment_notes" rows="3" placeholder="Any specific instructions or notes for the technician..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Job</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .job-description {
        max-width: 200px;
        word-wrap: break-word;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    
    .avatar-initial {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #6366f1;
    }
    
    .bg-light-primary {
        background-color: rgba(99, 102, 241, 0.1);
    }
    
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        color: #5a5c69;
    }
</style>
@endpush

@push('scripts')
<script>
    let currentJobId = null;
    
    function assignJob(jobId) {
        currentJobId = jobId;
        $('#assignJobModal').modal('show');
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
    
    function showFullDescription(jobId) {
        // Implementation for showing full description in a modal
        console.log('Show full description for job:', jobId);
    }
    
    function refreshTable() {
        location.reload();
    }
    
    function exportJobs() {
        window.location.href = '{{ route("jobs.index") }}?export=true';
    }
    
    $('#assignJobForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: `/jobs/${currentJobId}/assign`,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#assignJobModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Error assigning job: ' + xhr.responseJSON.message);
            }
        });
    });
</script>
@endpush
@endsection
