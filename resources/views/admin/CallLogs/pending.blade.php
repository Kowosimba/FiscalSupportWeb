@extends('layouts.calllogs')

@section('title', 'Pending Job Cards')

@section('content')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-card mb-4">
        <div class="page-header-content">
            <div class="header-text">
                <h3 class="page-title">
                    <i class="fa fa-clock me-2"></i>
                    Pending Job Cards
                </h3>
                <p class="page-subtitle">Job cards awaiting action</p>
            </div>
            <div class="page-actions">
                @if(in_array(auth()->user()->role, ['admin', 'accounts']))
                    <a href="{{ route('admin.call-logs.create') }}" class="btn btn-primary btn-enhanced">
                        <i class="fa fa-plus me-2"></i>
                        New Job Card
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon warning">
                    <i class="fa fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $callLogs->total() }}</div>
                    <div class="stat-label">Pending Jobs</div>
                    <div class="stat-footer">
                        <i class="fa fa-exclamation-triangle me-1"></i>
                        Needs attention
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon danger">
                    <i class="fa fa-fire"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $callLogs->where('type', 'emergency')->count() }}</div>
                    <div class="stat-label">Emergency</div>
                    <div class="stat-footer">
                        <i class="fa fa-arrow-up me-1"></i>
                        High priority
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon info">
                    <i class="fa fa-user-slash"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $callLogs->filter(function($job) { return empty($job->engineer); })->count() }}</div>
                    <div class="stat-label">Unassigned</div>
                    <div class="stat-footer">
                        <i class="fa fa-user-plus me-1"></i>
                        Need assignment
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon primary">
                    <i class="fa fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $callLogs->where('date_booked', '>=', now()->startOfDay()->format('Y-m-d'))->count() }}</div>
                    <div class="stat-label">Today</div>
                    <div class="stat-footer">
                        <i class="fa fa-plus me-1"></i>
                        New today
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Cards Table -->
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h5 class="card-title">
                    <i class="fa fa-clock me-2"></i>
                    Pending Job Cards
                </h5>
                <p class="card-subtitle">{{ $callLogs->total() }} pending job cards</p>
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
                            <th><i class="fa fa-calendar me-1"></i>Date Booked</th>
                            <th><i class="fa fa-user-tie me-1"></i>Engineer</th>
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
                                    <div class="job-date">
                                        <i class="fa fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($jobCard->date_booked)->format('M j, Y') }}
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($jobCard->date_booked)->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($jobCard->engineer)
                                        <div class="engineer-info">
                                            <i class="fa fa-user-check me-1"></i>
                                            {{ $jobCard->engineer }}
                                        </div>
                                    @else
                                        <span class="unassigned-badge">
                                            <i class="fa fa-user-slash me-1"></i>
                                            Unassigned
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="amount-charged">
                                        <strong>USD ${{ number_format($jobCard->amount_charged, 2) }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.call-logs.show', $jobCard) }}" class="action-btn view-btn" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        
                                        @if(in_array(auth()->user()->role, ['admin', 'accounts']))
                                            <a href="{{ route('admin.call-logs.edit', $jobCard) }}" class="action-btn edit-btn" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            
                                            @if(!$jobCard->engineer)
                                                <button class="action-btn assign-btn" onclick="assignJobCard({{ $jobCard->id }})" title="Assign Engineer">
                                                    <i class="fa fa-user-plus"></i>
                                                </button>
                                            @endif
                                        @endif
                                        
                                        @if($jobCard->engineer && in_array(auth()->user()->role, ['admin', 'accounts']))
                                            <button class="action-btn" style="background: #EFF6FF; color: #3B82F6;" onclick="updateStatus({{ $jobCard->id }}, 'assigned')" title="Assign Job">
                                                <i class="fa fa-arrow-right"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-content">
                                            <i class="fa fa-check-circle"></i>
                                            <h5 class="empty-title">No pending job cards</h5>
                                            <p class="empty-description">All job cards have been processed or assigned.</p>
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
                    <div class="mb-3">
                        <label for="assignment_notes" class="form-label">Assignment Notes (Optional)</label>
                        <textarea class="form-control form-control-enhanced" id="assignment_notes" name="assignment_notes" rows="3" placeholder="Any specific instructions or notes for the engineer..."></textarea>
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
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;}
    .stat-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border-color); transition: all 0.3s ease;}
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover);}
    .stat-card-body { padding: 1.5rem; display: flex; align-items: center; gap: 1rem;}
    .stat-icon { width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;}
    .stat-icon.primary { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); }
    .stat-icon.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-icon.danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .stat-icon.info { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .stat-icon i { font-size: 1.5rem; color: var(--white);}
    .stat-content { flex: 1;}
    .stat-number { font-size: 2rem; font-weight: 700; color: var(--dark-text); margin: 0; line-height: 1;}
    .stat-label { color: var(--light-text); font-size: 0.9rem; margin: 0.25rem 0; font-weight: 500;}
    .stat-footer { font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; color: var(--primary-green);}
    .content-card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; border: 1px solid var(--border-color);}
    .content-card-header { padding: 1.5rem 2rem; background: linear-gradient(135deg, var(--ultra-light-green) 0%, var(--light-green) 100%); border-bottom: 1px solid var(--border-color);}
    .content-card-header .card-title { font-size: 1.25rem; font-weight: 600; color: var(--primary-green); margin: 0;}
    .content-card-header .card-subtitle { color: var(--light-text); font-size: 0.9rem; margin: 0.25rem 0 0 0;}
    .content-card-body { padding: 2rem;}
    .enhanced-table { width: 100%; border-collapse: separate; border-spacing: 0; margin: 0;}
    .enhanced-table thead th { background: var(--ultra-light-green); color: var(--primary-green); font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 2px solid var(--light-green);}
    .enhanced-table tbody tr { transition: all 0.2s ease; border-bottom: 1px solid var(--border-color);}
    .enhanced-table tbody tr:hover { background: var(--ultra-light-green);}
    .enhanced-table tbody td { padding: 1rem 1.5rem; vertical-align: middle;}
    .job-card-number { font-family: 'Monaco', 'Menlo', monospace; background: var(--light-green); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem; color: var(--primary-green-dark); font-weight: 600; border: 1px solid var(--secondary-green);}
    .fault-description { font-weight: 500; color: var(--dark-text); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
    .company-info { color: var(--medium-text); font-weight: 500; display: flex; align-items: center;}
    .engineer-info { color: var(--primary-green); font-weight: 500; display: flex; align-items: center;}
    .unassigned-badge { background: #FEF2F2; color: #DC2626; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; display: flex; align-items: center;}
    .job-date { color: var(--light-text); font-size: 0.875rem; display: flex; align-items: center;}
    .amount-charged { font-weight: 600; color: var(--primary-green-dark);}
    .action-buttons { display: flex; gap: 0.5rem;}
    .action-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; transition: all 0.2s ease; text-decoration: none; border: none; cursor: pointer;}
    .view-btn { background: var(--light-green); color: var(--primary-green);}
    .view-btn:hover { background: var(--primary-green); color: var(--white); transform: translateY(-1px);}
    .edit-btn { background: #EFF6FF; color: #3B82F6;}
    .edit-btn:hover { background: #3B82F6; color: var(--white); transform: translateY(-1px);}
    .assign-btn { background: #F3E8FF; color: #8B5CF6;}
    .assign-btn:hover { background: #8B5CF6; color: var(--white); transform: translateY(-1px);}
    .empty-state { text-align: center; padding: 3rem 1.5rem;}
    .empty-content i { font-size: 2rem; color: var(--light-text); margin-bottom: 1rem;}
    .empty-title { color: var(--primary-green); font-weight: 600; margin-bottom: 0.5rem;}
    .empty-description { color: var(--light-text); margin: 0;}
    .btn-enhanced { padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; border: none; display: flex; align-items: center; text-decoration: none;}
    .btn-primary.btn-enhanced { background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%); color: var(--white);}
    .btn-primary.btn-enhanced:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover);}
    .btn-secondary.btn-enhanced { background: var(--hover-bg); color: var(--medium-text); border: 2px solid var(--border-color);}
    .btn-secondary.btn-enhanced:hover { background: var(--medium-text); color: var(--white);}
    .form-control-enhanced { border: 2px solid var(--border-color); border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.95rem; transition: all 0.3s ease; background: var(--white);}
    .form-control-enhanced:focus { border-color: var(--primary-green); box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1); outline: none;}
    .form-label { font-weight: 600; color: var(--dark-text); margin-bottom: 0.5rem; display: block;}
    @media (max-width: 768px) {
        .page-header-content { flex-direction: column; align-items: flex-start; gap: 1rem;}
        .stats-grid { grid-template-columns: 1fr;}
        .content-card-header, .content-card-body { padding: 1rem;}
        .enhanced-table th, .enhanced-table td { padding: 0.75rem 1rem;}
    }
</style>

@push('scripts')
<script>
    let currentJobCardId = null;
    
    function assignJobCard(jobCardId) {
        currentJobCardId = jobCardId;
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
