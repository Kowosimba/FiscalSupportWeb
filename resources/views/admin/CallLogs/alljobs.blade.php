@extends('layouts.app')

@section('title', 'Job Cards')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-clipboard-list me-2"></i>
                Job Cards
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary me-2">
                    <i class="fas fa-tasks me-1"></i>
                    {{ number_format($callLogs->total() ?? 0) }} Jobs
                </span>
                <small class="text-muted">Manage and track all service requests</small>
            </div>
        </div>
        <div class="header-actions">
            <button type="button" class="btn btn-sm btn-outline-info me-2" id="exportBtn">
                <i class="fas fa-download me-1"></i>
                Export
            </button>
            @if(in_array(auth()->user()->role ?? 'user', ['admin', 'accounts']))
                <button onclick="window.location.href='{{ route('admin.call-logs.create') }}'" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>
                    New Job
                </button>
            @endif
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="stats-grid mb-2">
        <div class="stat-card total">
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['total'] ?? 0) }}</div>
                <div class="stat-label">Total Jobs</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
        
        <div class="stat-card pending">
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['pending'] ?? 0) }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        
        <div class="stat-card progress">
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['in_progress'] ?? 0) }}</div>
                <div class="stat-label">In Progress</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-cog fa-spin"></i>
            </div>
        </div>
        
        <div class="stat-card revenue">
            <div class="stat-content">
                <div class="stat-value" style="white-space: normal; word-break: break-word; overflow-wrap: anywhere; line-height: 1.2;">
                    ${{ number_format($stats['total_revenue_usd'] ?? 0, 2) }} USD <br>
                    ZWG {{ number_format($stats['total_revenue_zwg'] ?? 0, 0) }}
                </div>
                <div class="stat-label">Revenue</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>
    
    {{-- Filters Card --}}
    <div class="content-card mb-2">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-filter me-2"></i>
                    Filters & Search
                </h4>
            </div>
            <div class="header-actions">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetFilters()">
                    <i class="fas fa-times me-1"></i>
                    Clear
                </button>
            </div>
        </div>
        
        <div class="content-card-body" style="padding: 0.75rem;">
            <form method="GET" id="filterForm" action="{{ route('admin.call-logs.all') }}">
                <div class="row g-2">
                    {{-- Search --}}
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-search me-1"></i>
                                Search Jobs
                            </label>
                            <div class="input-wrapper">
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Search by customer, job ID, or description...">
                                <div class="input-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>
                   </div>
                    
                    {{-- Status --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-flag me-1"></i>
                                Status
                            </label>
                            <div class="select-wrapper">
                                <select class="form-select" name="status" onchange="this.form.submit()">
                                    <option value="">All Statuses</option>
                                    @php
                                        $statuses = ['pending' => 'Pending', 'assigned' => 'Assigned', 'in_progress' => 'In Progress', 'complete' => 'Complete', 'cancelled' => 'Cancelled'];
                                        $selectedStatus = request('status');
                                    @endphp
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ $selectedStatus == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <div class="select-icon">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Technician --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-cog me-1"></i>
                                Technician
                            </label>
                            <div class="select-wrapper">
                                <select name="engineer" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Engineers</option>
                        @php
                            $selectedEngineer = request('engineer');
                            // Display engineers with specified roles: admin, accounts, manager, technician
                        @endphp
                        @foreach($technicians as $tech)
                            @if(in_array($tech->role, ['admin', 'accounts', 'manager', 'technician']))
                                <option value="{{ $tech->id }}" @selected($selectedEngineer == $tech->id)>{{ $tech->name }}</option>
                            @endif
                        @endforeach
                    </select>
                                <div class="select-icon">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Date Range (Period) --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar me-1"></i>
                                Period
                            </label>
                            <div class="select-wrapper">
                                <select class="form-select" name="date_range" onchange="this.form.submit()">
                                    @php
                                        $periods = ['' => 'All Time', 'today' => 'Today', 'this_week' => 'This Week', 'this_month' => 'This Month'];
                                        $selectedPeriod = request('date_range');
                                    @endphp
                                    @foreach($periods as $key => $label)
                                        <option value="{{ $key }}" {{ $selectedPeriod == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <div class="select-icon">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Jobs Table --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-list me-2"></i>
                    Job Cards
                </h4>
                @if(($callLogs->total() ?? 0) > 0)
                <p class="card-subtitle mb-0">
                    Showing {{ $callLogs->firstItem() ?? 0 }} to {{ $callLogs->lastItem() ?? 0 }} of {{ $callLogs->total() }} jobs
                </p>
                @endif
            </div>
            <div class="header-actions">
                <div class="d-flex gap-2">
                    <span class="badge pending-badge">
                        <i class="fas fa-clock me-1"></i>
                        {{ number_format($stats['pending'] ?? 0) }} Pending
                    </span>
                    <span class="badge progress-badge">
                        <i class="fas fa-cog me-1"></i>
                        {{ number_format($stats['in_progress'] ?? 0) }} Active
                    </span>
                </div>
            </div>
        </div>
        
        <div class="content-card-body">
            @if(($callLogs->count() ?? 0) > 0)
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th width="12%">Job Details</th>
                            <th width="18%">Customer</th>
                            <th width="20%">Issue</th>
                            <th width="10%">Status</th>
                            <th width="15%">Assigned To</th>
                            <th width="12%">Date</th>
                            <th width="10%">Amount</th>
                            <th width="8%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($callLogs as $job)
                        <tr class="job-row {{ strtolower($job->status) }}-row">
                            <td>
                                <div class="job-info">
                                    <div class="job-id">
                                        <span class="job-number">#{{ $job->id }}</span>
                                        @if($job->type === 'emergency')
                                            <span class="emergency-badge" title="Urgent Job">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                URGENT
                                            </span>
                                        @endif
                                    </div>
                                    <div class="job-code">{{ $job->job_card ?? 'TBD-' . $job->id }}</div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="customer-info">
                                    <div class="customer-name">{{ $job->customer_name }}</div>
                                    @if($job->customer_email)
                                        <div class="customer-email">{{ $job->customer_email }}</div>
                                    @endif
                                    @if($job->customer_phone)
                                        <div class="customer-phone">{{ $job->customer_phone }}</div>
                                    @endif
                                </div>
                            </td>
                            
                            <td>
                                <div class="issue-description" title="{{ $job->fault_description }}">
                                    {{ Str::limit($job->fault_description, 60) }}
                                </div>
                                @if($job->zimra_ref)
                                    <div class="zimra-ref">
                                        <i class="fas fa-hashtag me-1"></i>
                                        {{ $job->zimra_ref }}
                                    </div>
                                @endif
                            </td>
                            
                            <td>
                                @php
                                    $statusConfig = [
                                        'pending' => ['class' => 'status-pending', 'icon' => 'clock'],
                                        'assigned' => ['class' => 'status-assigned', 'icon' => 'user-check'],
                                        'in_progress' => ['class' => 'status-progress', 'icon' => 'cog'],
                                        'complete' => ['class' => 'status-complete', 'icon' => 'check-circle'],
                                        'cancelled' => ['class' => 'status-cancelled', 'icon' => 'times-circle']
                                    ];
                                    $config = $statusConfig[$job->status] ?? ['class' => 'status-default', 'icon' => 'circle'];
                                @endphp
                                <span class="status-badge {{ $config['class'] }}">
                                    <i class="fas fa-{{ $config['icon'] }} me-1 {{ $job->status === 'in_progress' ? 'fa-spin' : '' }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                </span>
                            </td>
                            
                            <td>
                                @if($job->assignedTo)
                                    <div class="technician-info">
                                        <div class="technician-avatar {{ $job->status === 'in_progress' ? 'active' : '' }}" title="Assigned Technician">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="technician-details">
                                            <div class="technician-name">{{ $job->assignedTo->name }}</div>
                                            @if($job->status === 'in_progress')
                                                <div class="work-indicator" title="Currently Working">
                                                    <i class="fas fa-circle text-success blink"></i>
                                                    Working
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="unassigned-badge">
                                        <i class="fas fa-user-slash me-1"></i>
                                        Unassigned
                                    </span>
                                @endif
                            </td>
                            
                            <td>
                                <div class="date-info" title="{{ $job->date_booked ? $job->date_booked->toDayDateTimeString() : '' }}">
                                    @if($job->date_booked)
                                        <div class="date-main">{{ $job->date_booked->format('M j') }}</div>
                                        <div class="date-relative">
                                            {{ $job->date_booked->diffForHumans(null, true, false, 2) }}
                                        </div>
                                    @else
                                        <span class="text-muted">No date</span>
                                    @endif
                                </div>
                            </td>
                            
                            <td>
                                <div class="amount-info">
                                    <span class="amount-value">
                                        @if(($job->currency ?? 'USD') === 'ZWG')
                                            ZWG {{ number_format($job->amount_charged ?? 0, 0) }}
                                        @else
                                            ${{ number_format($job->amount_charged ?? 0, 2) }}
                                        @endif
                                    </span>
                                    <div class="amount-status {{ $job->amount_charged > 0 ? 'paid' : 'free' }}">
                                        {{ $job->amount_charged > 0 ? 'Billed' : 'Free' }}
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="action-buttons">
                                    <button onclick="window.location.href='{{ route('admin.call-logs.show', $job) }}'" 
                                            class="action-btn view-btn" 
                                            title="View Job" type="button" aria-label="View Job #{{ $job->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    @if(in_array(auth()->user()->role ?? 'user', ['admin', 'manager']) || ($job->assigned_to == auth()->id()))
                                        <button onclick="window.location.href='{{ route('admin.call-logs.edit', $job) }}'" 
                                                class="action-btn edit-btn" 
                                                title="Edit Job" type="button" aria-label="Edit Job #{{ $job->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <div class="empty-content">
                    <i class="fas fa-clipboard-list"></i>
                    <h6>No Job Cards Found</h6>
                    <p>No jobs match your current filters or search criteria.</p>
                    <button onclick="resetFilters()" class="btn btn-secondary btn-sm mt-2">
                        <i class="fas fa-refresh me-1"></i>
                        Reset Filters
                    </button>
                </div>
            </div>
            @endif
        </div>
        
        @if($callLogs->hasPages())
        <div class="pagination-wrapper">
            {{ $callLogs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Export Loading Modal --}}
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-success mb-3" style="width: 3rem; height: 3rem;"></div>
                <h6 class="mb-2">Preparing Export</h6>
                <p class="text-muted small mb-0">This will take a moment...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root {
    --primary: #059669;
    --primary-dark: #047857;
    --success: #059669;
    --warning: #F59E0B;
    --danger: #DC2626;
    --secondary: #6B7280;
    --secondary-dark: #4B5563;
    --info: #0EA5E9;
    --white: #FFFFFF;
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-300: #D1D5DB;
    --gray-400: #9CA3AF;
    --gray-500: #6B7280;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --gray-800: #1F2937;
    --border-radius: 8px;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --transition: all 0.2s ease;
}

.dashboard-header {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.5rem 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.header-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.125rem;
}

.header-meta .badge {
    font-size: 0.65rem;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
}

.bg-secondary {
    background: var(--secondary) !important;
    color: white;
}

.header-meta small {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.header-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
}

.stat-card {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 1rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: var(--transition);
    min-height: 90px;
    height: 100%;
    box-sizing: border-box;
}

.stat-card .stat-content {
    flex: 1;
    min-width: 0;
}

.stat-card .stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
    white-space: nowrap;
}

.stat-card .stat-label {
    font-size: 0.8rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-card .stat-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    margin-left: 1rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

/* Card-specific colors */
.stat-card.total .stat-icon {
    background: #EFF6FF;
    color: #1D4ED8;
}
.stat-card.pending .stat-icon {
    background: #FFFBEB;
    color: #D97706;
}
.stat-card.progress .stat-icon {
    background: #F0F9FF;
    color: #0284C7;
}
.stat-card.revenue .stat-icon {
    background: #F0FDF4;
    color: #059669;
}

/* Icon spin only */
.stat-card.progress .stat-icon .fa-cog {
    animation: fa-spin 2s infinite linear;
}

@keyframes fa-spin {
    0% { transform: rotate(0deg);}
    100% { transform: rotate(359deg);}
}

.content-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.content-card-header {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
}

.card-subtitle {
    color: var(--gray-500);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.content-card-body {
    padding: 0;
}

.pending-badge {
    background: #FFFBEB !important;
    color: #D97706 !important;
    border: 1px solid #FDE68A;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

.progress-badge {
    background: #F0F9FF !important;
    color: #0284C7 !important;
    border: 1px solid #BAE6FD;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

.form-group {
    margin-bottom: 0.75rem;
}

.form-label {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.4rem;
    display: flex;
    align-items: center;
    font-size: 0.8rem;
}

.form-label i {
    color: var(--secondary);
    width: 16px;
}

.input-wrapper {
    position: relative;
}

.input-wrapper .form-control {
    padding-left: 2.75rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    transition: var(--transition);
    height: 36px;
}

.input-wrapper .form-control:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
}

.input-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    pointer-events: none;
    font-size: 0.85rem;
}

.select-wrapper {
    position: relative;
}

.select-wrapper .form-select {
    appearance: none;
    padding-right: 2.5rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    transition: var(--transition);
    height: 36px;
}

.select-wrapper .form-select:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
}

.select-icon {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    pointer-events: none;
    font-size: 0.75rem;
}

.compact-table {
    width: 100%;
    font-size: 0.8rem;
    border-collapse: separate;
    border-spacing: 0;
}

.compact-table th {
    background: var(--gray-50);
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid var(--gray-200);
    white-space: nowrap;
}

.compact-table td {
    padding: 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--gray-100);
}

.compact-table tr:last-child td {
    border-bottom: none;
}

.job-row {
    transition: var(--transition);
    cursor: pointer;
}

.job-row:hover {
    background: var(--gray-50);
    transform: translateY(-1px);
}

.pending-row {
    background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%) !important;
    border-left: 4px solid #F59E0B;
}

.in_progress-row {
    background: linear-gradient(135deg, #F0F9FF 0%, #DBEAFE 100%) !important;
    border-left: 4px solid #3B82F6;
    animation: pulse-glow 2s infinite;
}

.complete-row {
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%) !important;
    border-left: 4px solid #10B981;
    opacity: 0.9;
}

@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.3); }
    50% { box-shadow: 0 0 15px rgba(59, 130, 246, 0.5); }
}

.job-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.job-id {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.job-number {
    font-weight: 700;
    color: var(--gray-800);
    font-size: 0.85rem;
}

.emergency-badge {
    background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
    color: #DC2626;
    padding: 0.1rem 0.3rem;
    border-radius: 3px;
    font-size: 0.6rem;
    font-weight: 600;
    border: 1px solid #FECACA;
    animation: blink 1.5s infinite;
}

.job-code {
    color: var(--gray-500);
    font-size: 0.75rem;
    font-family: monospace;
    background: var(--gray-100);
    padding: 0.1rem 0.3rem;
    border-radius: 3px;
}

.customer-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.customer-name {
    font-weight: 500;
    color: var(--gray-800);
    font-size: 0.85rem;
}

.customer-email,
.customer-phone {
    color: var(--gray-500);
    font-size: 0.7rem;
}

.issue-description {
    color: var(--gray-700);
    line-height: 1.4;
    font-size: 0.8rem;
}

.zimra-ref {
    color: var(--gray-500);
    font-size: 0.7rem;
    margin-top: 0.25rem;
    font-family: monospace;
    background: var(--gray-100);
    padding: 0.1rem 0.3rem;
    border-radius: 3px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
    white-space: nowrap;
    transition: var(--transition);
}

.status-pending {
    background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
    color: #D97706;
    border: 1px solid #FDE68A;
}

.status-assigned {
    background: linear-gradient(135deg, #F0F9FF 0%, #DBEAFE 100%);
    color: #0284C7;
    border: 1px solid #BAE6FD;
}

.status-progress {
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
    animation: status-pulse 2s infinite;
}

.status-complete {
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    color: #047857;
    border: 1px solid #BBF7D0;
}

.status-cancelled {
    background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
    color: #DC2626;
    border: 1px solid #FECACA;
}

@keyframes status-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.technician-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.technician-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-600);
    font-size: 0.8rem;
    transition: var(--transition);
}

.technician-avatar.active {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    color: white;
    animation: active-pulse 2s infinite;
}

@keyframes active-pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    50% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
}

.technician-details {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}

.technician-name {
    font-size: 0.8rem;
    color: var(--gray-700);
    font-weight: 500;
}

.work-indicator {
    font-size: 0.65rem;
    color: var(--success);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.blink {
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}

.unassigned-badge {
    color: var(--gray-500);
    font-size: 0.75rem;
    font-style: italic;
}

.date-info {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}

.date-main {
    font-weight: 500;
    color: var(--gray-800);
    font-size: 0.8rem;
}

.date-relative {
    color: var(--gray-500);
    font-size: 0.65rem;
}

.amount-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.amount-value {
    font-weight: 700;
    color: var(--success);
    font-size: 0.8rem;
}

.amount-status {
    font-size: 0.6rem;
    padding: 0.1rem 0.3rem;
    border-radius: 3px;
    font-weight: 600;
    text-transform: uppercase;
}

.amount-status.paid {
    background: #F0FDF4;
    color: #047857;
}

.amount-status.free {
    background: #F3F4F6;
    color: #6B7280;
}

.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 4px;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    background: none;
    padding: 0;
}

.view-btn {
    background: #F3F4F6;
    color: #4B5563;
    border: 1px solid #D1D5DB;
}

.view-btn:hover {
    background: #4B5563;
    color: white;
    transform: scale(1.1);
}

.edit-btn {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
}

.edit-btn:hover {
    background: #1D4ED8;
    color: white;
    transform: scale(1.1);
}

.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-content i {
    font-size: 1.5rem;
    color: var(--gray-300);
    margin-bottom: 0.5rem;
}

.empty-content h6 {
    font-size: 0.9rem;
    color: var(--gray-600);
    margin-bottom: 0.25rem;
}

.empty-content p {
    font-size: 0.8rem;
    color: var(--gray-500);
    margin: 0;
}

.pagination-wrapper {
    padding: 0.75rem;
    border-top: 1px solid var(--gray-200);
}

.pagination .page-link {
    color: var(--secondary);
    border-color: var(--gray-300);
}

.pagination .page-link:hover {
    color: var(--secondary-dark);
    background-color: var(--gray-50);
    border-color: var(--gray-300);
}

.pagination .page-item.active .page-link {
    background-color: var(--secondary);
    border-color: var(--secondary);
    color: white;
}

.modal-content {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.5rem;
    }
    
    .header-actions {
        width: 100%;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }
    
    .compact-table {
        font-size: 0.75rem;
    }
    
    .compact-table th,
    .compact-table td {
        padding: 0.4rem 0.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.15rem;
    }
    
    .job-info,
    .customer-info {
        min-width: 120px;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-value {
        font-size: 1.25rem;
    }
    
    .issue-description {
        font-size: 0.75rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const exportBtn = document.getElementById('exportBtn');
    const exportModal = new bootstrap.Modal(document.getElementById('exportModal'));

    exportBtn.addEventListener('click', function() {
        exportModal.show();

        const urlParams = new URLSearchParams(window.location.search);
        const exportUrl = '{{ route("admin.call-logs.export") }}?' + urlParams.toString();

        const link = document.createElement('a');
        link.href = exportUrl;
        link.download = 'job-cards-' + new Date().toISOString().split('T')[0] + '.xlsx';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        setTimeout(() => {
            exportModal.hide();
        }, 1500);
    });

    document.querySelectorAll('.job-row').forEach(row => {
        row.addEventListener('click', function(e) {
            if (!e.target.closest('.action-buttons')) {
                const btnView = this.querySelector('.view-btn');
                if (btnView) btnView.click();
            }
        });
    });

    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let debounceTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    document.getElementById('filterForm').submit();
                }
            }, 500);
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('filterForm').submit();
            }
        });
    }
});

function resetFilters() {
    const url = new URL(window.location.href);
    url.search = '';
    window.location.href = url.toString();
}
</script>
@endpush
