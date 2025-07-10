@extends('layouts.calllogs')

@section('title', 'In Progress Job Cards')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-card mb-4">
        <div class="page-header-content">
            <div class="header-text">
                <h3 class="page-title">
                    <i class="fa fa-play-circle me-2"></i>
                    In Progress Job Cards
                </h3>
                <p class="page-subtitle">Job cards currently being worked on</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-outline-secondary btn-enhanced" onclick="refreshPage()">
                    <i class="fa fa-sync-alt me-2"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon info">
                    <i class="fa fa-cog"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $callLogs->total() }}</div>
                    <div class="stat-label">In Progress</div>
                    <div class="stat-footer">
                        <i class="fa fa-play me-1"></i>
                        Active jobs
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon warning">
                    <i class="fa fa-fire"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $callLogs->where('type', 'emergency')->count() }}</div>
                    <div class="stat-label">Emergency</div>
                    <div class="stat-footer">
                        <i class="fa fa-exclamation-triangle me-1"></i>
                        High priority
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon success">
                    <i class="fa fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $callLogs->where('date_booked', '>=', now()->startOfDay()->format('Y-m-d'))->count() }}</div>
                    <div class="stat-label">Started Today</div>
                    <div class="stat-footer">
                        <i class="fa fa-calendar me-1"></i>
                        Today's work
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon primary">
                    <i class="fa fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $callLogs->whereNotNull('engineer')->where('engineer', '!=', '')->groupBy('engineer')->count() }}</div>
                    <div class="stat-label">Active Engineers</div>
                    <div class="stat-footer">
                        <i class="fa fa-user-check me-1"></i>
                        Working now
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
                    <i class="fa fa-play-circle me-2"></i>
                    In Progress Job Cards
                </h5>
                <p class="card-subtitle">{{ $callLogs->total() }} jobs currently in progress</p>
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
                            <th><i class="fa fa-user-tie me-1"></i>Engineer</th>
                            <th><i class="fa fa-calendar me-1"></i>Date Started</th>
                            <th><i class="fa fa-clock me-1"></i>Duration</th>
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
                                    @if($jobCard->engineer)
                                        <div class="engineer-info">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-initial rounded-circle bg-light-primary">
                                                        {{ substr($jobCard->engineer, 0, 1) }}
                                                    </div>
                                                </div>
                                                <strong>{{ $jobCard->engineer }}</strong>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="job-date">
                                        <i class="fa fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($jobCard->date_booked)->format('M j, Y') }}
                                        @if($jobCard->time_start)
                                            <br>
                                            <small class="text-info">Started: {{ $jobCard->time_start }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="job-duration">
                                        @if($jobCard->billed_hours)
                                            <i class="fa fa-clock me-1"></i>
                                            {{ $jobCard->billed_hours }}h
                                        @elseif($jobCard->time_start)
                                            <i class="fa fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($jobCard->time_start)->diffForHumans() }}
                                        @else
                                            <span class="text-muted">Not started</span>
                                        @endif
                                    </div>
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
                                        @if($jobCard->engineer === auth()->user()->name)
                                            <a href="{{ route('admin.call-logs.edit', $jobCard) }}" class="action-btn edit-btn" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button class="action-btn" style="background: var(--ultra-light-green); color: var(--primary-green);" onclick="updateStatus({{ $jobCard->id }}, 'complete')" title="Complete Job">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-content">
                                            <i class="fa fa-check-circle"></i>
                                            <h5 class="empty-title">No jobs in progress</h5>
                                            <p class="empty-description">All jobs are either pending or completed.</p>
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

@push('scripts')
<script>
    function updateStatus(jobCardId, newStatus) {
        if (!confirm('Are you sure you want to mark this job as complete?')) {
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
    
    function refreshPage() {
        location.reload();
    }
</script>
@endpush
@endsection
