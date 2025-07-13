@extends('layouts.calllogs')

@section('title', 'Calls Management - Dashboard')

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
        margin-bottom: 2rem;
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
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon.primary { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); }
    .stat-icon.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-icon.info { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .stat-icon.success { background: linear-gradient(135deg, var(--success-green) 0%, var(--primary-green) 100%); }
    .stat-icon.danger { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); }

    .stat-icon i {
        font-size: 1.5rem;
        color: var(--white);
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-text);
        margin: 0;
        line-height: 1;
    }

    .stat-label {
        color: var(--light-text);
        font-size: 0.9rem;
        margin: 0.25rem 0;
        font-weight: 500;
    }

    .stat-change {
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: var(--primary-green);
    }

    /* Job Cards Table */
    .job-cards-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: var(--shadow);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .job-cards-card-header {
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

    .job-cards-card-header .btn-enhanced {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
        color: var(--white);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .job-cards-card-header .btn-enhanced:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-hover);
    }

    .job-cards-card-body {
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

    .job-card-number {
        font-family: 'Monaco', 'Menlo', monospace;
        background: var(--light-green);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
        color: var(--primary-green-dark);
        font-weight: 600;
        border: 1px solid var(--secondary-green);
    }

    .company-name {
        font-weight: 500;
        color: var(--dark-text);
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .fault-description {
        color: var(--medium-text);
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .engineer-name {
        color: var(--medium-text);
        font-weight: 500;
    }

    .job-date {
        color: var(--light-text);
        font-size: 0.875rem;
    }

    .amount-charged {
        font-weight: 600;
        color: var(--primary-green-dark);
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
        border: none;
        cursor: pointer;
        margin-right: 0.25rem;
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

    .edit-btn {
        background: #EFF6FF;
        color: #3B82F6;
    }

    .edit-btn:hover {
        background: #3B82F6;
        color: var(--white);
        transform: translateY(-1px);
    }

    .assign-btn {
        background: #F3E8FF;
        color: #8B5CF6;
    }

    .assign-btn:hover {
        background: #8B5CF6;
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

    .empty-content h5 {
        color: var(--primary-green);
        margin-bottom: 0.5rem;
    }

    .empty-content p {
        color: var(--light-text);
        font-size: 1.1rem;
        margin: 0;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: var(--white);
        padding: 1.5rem 2rem;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
    }

    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary-green-dark);
        margin: 0;
    }

    .page-header p {
        color: var(--light-text);
        margin: 0.25rem 0 0 0;
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

        .job-cards-card-header {
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

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>

{{-- Dashboard Navigation Tabs --}}
<div class="dashboard-nav-wrapper mb-4">
    <ul class="panel-nav nav nav-tabs">
        {{-- Faults Allocation --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.index') || request()->routeIs('admin.tickets.*') ? 'active' : '' }}"
               href="{{ route('admin.index') }}">
                <i class="fa fa-tasks me-2"></i>
                Faults Allocation
            </a>
        </li>
        {{-- Customer Contacts --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
               href="{{ route('admin.contacts.index') }}">
                <i class="fa fa-users me-2"></i>
                Customer Contacts
            </a>
        </li>
        {{-- Call Logs --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.call-logs.*') ? 'active' : '' }}" 
               href="{{ route('admin.call-logs.index') }}">
                <i class="fa fa-phone me-2"></i>
                Call Logs
            </a>
        </li>
        {{-- Content Management --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('content.*') || request()->routeIs('blogs.*') || request()->routeIs('admin.faqs.*') || request()->routeIs('admin.services.*') || request()->routeIs('admin.subscribers.*') || request()->routeIs('admin.newsletters.*') || request()->routeIs('faq-categories.*') ? 'active' : '' }}"
               href="{{ route('admin.content.index') }}">
                <i class="fa fa-cog me-2"></i>
                Manage Content
            </a>
        </li>
    </ul>
</div>


<div class="container-fluid">
    <!-- Header Section -->
    <div class="page-header">
        <div>
            <h1>Billed Calls Management</h1>
            <p>Manage and track all IT support job cards and services</p>
        </div>
        @if(in_array(auth()->user()->role, ['admin', 'accounts']))
            <a href="{{ route('admin.call-logs.create') }}" class="btn btn-primary btn-enhanced">
                <i class="fa fa-plus me-2"></i>
                Create New Job Card
            </a>
        @endif
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon primary">
                    <i class="fa fa-clipboard"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_jobs'] ?? 0 }}</div>
                    <div class="stat-label">Total Jobs</div>
                    <div class="stat-change">
                        <i class="fa fa-arrow-up"></i>
                        +12% from last month
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
                    <div class="stat-number">{{ $stats['pending_jobs'] ?? 0 }}</div>
                    <div class="stat-label">Pending Jobs</div>
                    <div class="stat-change">
                        <i class="fa fa-arrow-down"></i>
                        -5% from last month
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon info">
                    <i class="fa fa-cog"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['in_progress_jobs'] ?? 0 }}</div>
                    <div class="stat-label">In Progress</div>
                    <div class="stat-change">
                        <i class="fa fa-arrow-up"></i>
                        +8% from last month
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
                    <div class="stat-change">
                        <i class="fa fa-arrow-up"></i>
                        +15% from last month
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
                    <div class="stat-label">Emergency</div>
                    <div class="stat-change">
                        <i class="fa fa-arrow-up"></i>
                        +3% from last month
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    @include('admin.calllogs.partials.filters')

    <!-- Job Cards Table -->
    <div class="job-cards-card">
        <div class="job-cards-card-header">
            <div class="header-content">
                <h5 class="card-title">
                    <i class="fa fa-list me-2"></i>
                    Job Cards
                </h5>
                <p class="card-subtitle">Recent IT support jobs and services</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-enhanced" onclick="refreshTable()">
                    <i class="fa fa-sync-alt"></i>
                    Refresh
                </button>
                @if(auth()->user()->role === 'admin')
                    <button type="button" class="btn btn-enhanced" onclick="exportJobCards()">
                        <i class="fa fa-download"></i>
                        Export
                    </button>
                @endif
            </div>
        </div>
        <div class="job-cards-card-body">
            <div class="table-responsive">
                <table class="enhanced-table" id="jobCardsTable">
                    <thead>
                        <tr>
                            <th><i class="fa fa-hashtag me-1"></i>Job Card</th>
                            <th><i class="fa fa-building me-1"></i>Company</th>
                            <th><i class="fa fa-exclamation-circle me-1"></i>Fault Description</th>
                            <th><i class="fa fa-cogs me-1"></i>Type</th>
                            <th><i class="fa fa-check-circle me-1"></i>Status</th>
                            <th><i class="fa fa-user-tie me-1"></i>Engineer</th>
                            <th><i class="fa fa-calendar me-1"></i>Date Booked</th>
                            <th><i class="fa fa-dollar-sign me-1"></i>Amount</th>
                            <th><i class="fa fa-cog me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($callLogs as $jobCard)
                            <tr>
                                <td>
                                    <span class="job-card-number">{{ $jobCard->job_card }}</span>
                                    @if($jobCard->zimra_ref)
                                        <br>
                                        <small class="text-muted">ZIMRA: {{ $jobCard->zimra_ref }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="company-name" title="{{ $jobCard->company_name }}">
                                        {{ $jobCard->company_name }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fault-description" title="{{ $jobCard->fault_description }}">
                                        {{ Str::limit($jobCard->fault_description, 50) ?: 'No description' }}
                                    </div>
                                </td>
                                <td>
                                    @include('admin.calllogs.partials.type-badge', ['type' => $jobCard->type])
                                </td>
                                <td>
                                    @include('admin.calllogs.partials.status-badge', ['status' => $jobCard->status])
                                </td>
                                <td>
                                    @if($jobCard->engineer)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-initial rounded-circle bg-light-primary">
                                                    {{ substr($jobCard->engineer, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <strong class="engineer-name">{{ $jobCard->engineer }}</strong>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="job-date">
                                        {{ \Carbon\Carbon::parse($jobCard->date_booked)->format('M j, Y') }}
                                        @if($jobCard->date_resolved)
                                            <br>
                                            <small class="text-success">Resolved: {{ \Carbon\Carbon::parse($jobCard->date_resolved)->format('M j') }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="amount-charged">
                                        USD ${{ number_format($jobCard->amount_charged, 2) }}
                                    </div>
                                    @if($jobCard->billed_hours)
                                        <br>
                                        <small class="text-muted">{{ $jobCard->billed_hours }}h</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.call-logs.show', $jobCard) }}" class="action-btn view-btn" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        
                                        @if((auth()->user()->role === 'engineer' && $jobCard->engineer === auth()->user()->name) || in_array(auth()->user()->role, ['admin', 'accounts']))
                                            <a href="{{ route('admin.call-logs.edit', $jobCard) }}" class="action-btn edit-btn" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                        
                                        @if(in_array(auth()->user()->role, ['admin', 'accounts']) && !$jobCard->engineer && $jobCard->status !== 'complete')
                                            <button class="action-btn assign-btn" onclick="assignJobCard({{ $jobCard->id }})" title="Assign Engineer">
                                                <i class="fa fa-user-plus"></i>
                                            </button>
                                        @endif
                                        
                                        @if($jobCard->status === 'assigned' && $jobCard->engineer === auth()->user()->name)
                                            <button class="action-btn" style="background: #EFF6FF; color: #3B82F6;" onclick="updateStatus({{ $jobCard->id }}, 'in_progress')" title="Start Work">
                                                <i class="fa fa-play"></i>
                                            </button>
                                        @elseif($jobCard->status === 'in_progress' && $jobCard->engineer === auth()->user()->name)
                                            <button class="action-btn" style="background: var(--ultra-light-green); color: var(--primary-green);" onclick="updateStatus({{ $jobCard->id }}, 'complete')" title="Complete Job">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="empty-state">
                                    <div class="empty-content">
                                        <i class="fa fa-clipboard"></i>
                                        <h5>No job cards found</h5>
                                        <p>Try adjusting your filters or create a new job card</p>
                                        @if(in_array(auth()->user()->role, ['admin', 'accounts']))
                                            <a href="{{ route('admin.call-logs.create') }}" class="btn btn-primary btn-enhanced mt-2">
                                                <i class="fa fa-plus me-2"></i>
                                                Create First Job Card
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4 px-3 pb-3">
                <div class="text-muted">
                    Showing {{ $callLogs->firstItem() ?? 0 }} to {{ $callLogs->lastItem() ?? 0 }} of {{ $callLogs->total() }} results
                </div>
                {{ $callLogs->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Assignment Modal -->
<div class="modal fade" id="assignJobCardModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Job to Engineer</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="assignJobCardForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="engineer">Select Engineer</label>
                        <select class="form-control" id="engineer" name="engineer" required>
                            <option value="">Choose engineer...</option>
                            <option value="Benson">Benson</option>
                            <option value="Malvine">Malvine</option>
                            <option value="Mukai">Mukai</option>
                            <option value="Tapera">Tapera</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="assignment_notes">Assignment Notes (Optional)</label>
                        <textarea class="form-control" id="assignment_notes" name="assignment_notes" rows="3" placeholder="Any specific instructions or notes for the engineer..."></textarea>
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
        color: var(--primary-green);
    }
    
    .bg-light-primary {
        background-color: var(--ultra-light-green);
    }
</style>
@endpush

@push('scripts')
<script>
    let currentJobCardId = null;
    
    function assignJobCard(jobCardId) {
        currentJobCardId = jobCardId;
        $('#assignJobCardModal').modal('show');
    }
    
    function updateStatus(jobCardId, newStatus) {
        if (!confirm('Are you sure you want to update this job status?')) {
            return;
        }
        
        $.ajax({
            url: `{{ route('admin.call-logs.index') }}/${jobCardId}/status`,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Error updating job status: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    }
    
    function refreshTable() {
        location.reload();
    }
    
    function exportJobCards() {
        window.location.href = '{{ route("admin.call-logs.export") }}?format=csv';
    }
    
    $('#assignJobCardForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: `{{ route('admin.call-logs.index') }}/${currentJobCardId}/assign`,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#assignJobCardModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Error assigning job: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });
</script>
@endpush
@endsection
