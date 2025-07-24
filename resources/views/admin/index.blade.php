@extends('layouts.app')

@section('title', 'Dashboard - Tickets Overview')

@section('content')
<div class="dashboard-container">
    {{-- Dashboard Header --}}
    <div class="dashboard-header mb-4">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-chart-line me-3"></i>
                Dashboard Overview
            </h1>
            <p class="dashboard-subtitle">Complete system overview with tickets, jobs, and content management</p>
        </div>
        <div class="header-actions">
            <div class="time-filter">
                <select class="form-select" id="timeFilter">
                    <option value="today">Today</option>
                    <option value="week" selected>This Week</option>
                    <option value="month">This Month</option>
                    <option value="quarter">This Quarter</option>
                </select>
            </div>
            <button class="btn btn-primary" id="refreshDashboard">
                <i class="fas fa-sync-alt me-2"></i>
                Refresh
            </button>
        </div>
    </div>
    {{-- Comprehensive Stats Grid --}}
    <div class="stats-section mb-5">
        <h2 class="section-title">
            <i class="fas fa-chart-bar me-2"></i>
            System Statistics
        </h2>
        
        {{-- Tickets Stats --}}
        <div class="stats-category mb-4">
            <h3 class="category-title">Ticket Management</h3>
            <div class="stats-grid">
                @php
                $ticketStats = [
                    'in_progress' => [
                        'label' => 'In Progress',
                        'icon' => 'fa-play-circle',
                        'color' => 'var(--primary)',
                        'bg' => 'linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%)',
                        'count' => $statusCounts->in_progress ?? 0,
                        'change' => $percentageChanges['in_progress'] ?? 0,
                        'route' => route('admin.tickets.open')
                    ],
                    'resolved' => [
                        'label' => 'Resolved',
                        'icon' => 'fa-check-circle',
                        'color' => 'var(--success)',
                        'bg' => 'linear-gradient(135deg, var(--success) 0%, #059669 100%)',
                        'count' => $statusCounts->resolved ?? 0,
                        'change' => $percentageChanges['resolved'] ?? 0,
                        'route' => route('admin.tickets.solved')
                    ],
                    'pending' => [
                        'label' => 'Pending',
                        'icon' => 'fa-clock',
                        'color' => 'var(--warning)',
                        'bg' => 'linear-gradient(135deg, var(--warning) 0%, #D97706 100%)',
                        'count' => $statusCounts->pending ?? 0,
                        'change' => $percentageChanges['pending'] ?? 0,
                        'route' => route('admin.tickets.pending')
                    ],
                    'unassigned' => [
                        'label' => 'Unassigned',
                        'icon' => 'fa-user-slash',
                        'color' => 'var(--danger)',
                        'bg' => 'linear-gradient(135deg, var(--danger) 0%, #DC2626 100%)',
                        'count' => $statusCounts->unassigned ?? 0,
                        'change' => $percentageChanges['unassigned'] ?? 0,
                        'route' => route('admin.tickets.unassigned')
                    ]
                ];
                @endphp

                @foreach ($ticketStats as $key => $data)
                    @php
                        $change = $data['change'];
                        $isPositive = $change >= 0;
                        $arrowClass = $isPositive ? 'fa-arrow-up' : 'fa-arrow-down';
                        $textClass = $key === 'resolved' ? ($isPositive ? 'text-success' : 'text-warning') : ($isPositive ? 'text-danger' : 'text-success');
                    @endphp
                    <div class="stat-card enhanced-card">
                        <a href="{{ $data['route'] }}" class="stat-link">
                            <div class="stat-card-body">
                                <div class="stat-icon" style="background: {{ $data['bg'] }};">
                                    <i class="fas {{ $data['icon'] }}"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $data['count'] }}</h3>
                                    <p class="stat-label">{{ $data['label'] }} Tickets</p>
                                    <div class="stat-change {{ $textClass }}">
                                        <i class="fas {{ $arrowClass }}"></i>
                                        <span>{{ abs($change) }}% this week</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Jobs Stats --}}
        <div class="stats-category mb-4">
            <h3 class="category-title">Job Management</h3>
            <div class="stats-grid">
                @php
                $jobStats = [
                    'pending_jobs' => [
                        'label' => 'Pending Jobs',
                        'icon' => 'fa-clock',
                        'bg' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)',
                        'count' => $stats['pending_jobs'] ?? 0,
                        'route' => route('admin.call-logs.pending')
                    ],
                    'in_progress_jobs' => [
                        'label' => 'In Progress',
                        'icon' => 'fa-spinner',
                        'bg' => 'linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%)',
                        'count' => $stats['in_progress_jobs'] ?? 0,
                        'route' => route('admin.call-logs.in-progress')
                    ],
                    'completed_jobs' => [
                        'label' => 'Completed',
                        'icon' => 'fa-check-circle',
                        'bg' => 'linear-gradient(135deg, var(--success) 0%, #059669 100%)',
                        'count' => $stats['completed_jobs'] ?? 0,
                        'route' => route('admin.call-logs.completed')
                    ],
                    'total_jobs' => [
                        'label' => 'Total Jobs',
                        'icon' => 'fa-clipboard-list',
                        'bg' => 'linear-gradient(135deg, var(--accent) 0%, #7C3AED 100%)',
                        'count' => $stats['total_jobs'] ?? 0,
                        'route' => route('admin.call-logs.index')
                    ]
                ];
                @endphp

                @foreach ($jobStats as $key => $data)
                    <div class="stat-card enhanced-card">
                        <a href="{{ $data['route'] }}" class="stat-link">
                            <div class="stat-card-body">
                                <div class="stat-icon" style="background: {{ $data['bg'] }};">
                                    <i class="fas {{ $data['icon'] }}"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $data['count'] }}</h3>
                                    <p class="stat-label">{{ $data['label'] }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="content-grid">
        {{-- Recent Tickets Table --}}
        <div class="content-card tickets-overview">
            <div class="content-card-header">
                <div class="header-content">
                    <h4 class="card-title">
                        <i class="fas fa-ticket-alt me-2"></i>
                        Recent Tickets
                    </h4>
                    <p class="card-subtitle">Latest support requests requiring attention</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-external-link-alt me-1"></i>
                        View All
                    </a>
                </div>
            </div>
            <div class="content-card-body">
                <div class="table-responsive">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th class="col-id">ID</th>
                                <th class="col-subject">Subject</th>
                                <th class="col-priority">Priority</th>
                                <th class="col-status">Status</th>
                                <th class="col-updated">Last Update</th>
                                <th class="col-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tickets as $ticket)
                            <tr class="ticket-row" data-priority="{{ $ticket->priority ?? 'low' }}">
                                <td class="col-id">
                                    <span class="ticket-id">#{{ $ticket->id }}</span>
                                </td>
                                <td class="col-subject">
                                    <div class="ticket-subject">
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="subject-link">
                                            {{ Str::limit($ticket->subject, 40) }}
                                        </a>
                                        @if($ticket->priority === 'high')
                                            <span class="priority-indicator high-priority" title="High Priority">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-priority">
                                    <span class="priority-badge priority-{{ $ticket->priority ?? 'low' }}">
                                        @php
                                            $priorityIcons = [
                                                'high' => 'fa-exclamation-triangle',
                                                'medium' => 'fa-minus-circle',
                                                'low' => 'fa-circle'
                                            ];
                                        @endphp
                                        <i class="fas {{ $priorityIcons[$ticket->priority ?? 'low'] }} me-1"></i>
                                        {{ ucfirst($ticket->priority ?? 'low') }}
                                    </span>
                                </td>
                                <td class="col-status">
                                    @php
                                        $statusConfig = [
                                            'in_progress' => ['class' => 'status-in-progress', 'icon' => 'fa-spinner', 'label' => 'In Progress'],
                                            'resolved' => ['class' => 'status-resolved', 'icon' => 'fa-check', 'label' => 'Resolved'],
                                            'pending' => ['class' => 'status-pending', 'icon' => 'fa-clock', 'label' => 'Pending'],
                                            'closed' => ['class' => 'status-closed', 'icon' => 'fa-lock', 'label' => 'Closed']
                                        ];
                                        $config = $statusConfig[$ticket->status] ?? ['class' => 'status-pending', 'icon' => 'fa-question', 'label' => ucfirst($ticket->status)];
                                    @endphp
                                    <span class="status-badge {{ $config['class'] }}">
                                        <i class="fas {{ $config['icon'] }} me-1"></i>
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="col-updated">
                                    <div class="update-info">
                                        <span class="update-time" title="{{ $ticket->updated_at->format('M d, Y H:i') }}">
                                            {{ $ticket->updated_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="col-actions">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
                                           class="action-btn view-btn" 
                                           title="View Ticket">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-inbox"></i>
                                            <h5>No tickets found</h5>
                                            <p>All caught up! No tickets require attention at the moment.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Jobs Overview --}}
        <div class="content-card jobs-overview">
            <div class="content-card-header">
                <div class="header-content">
                    <h4 class="card-title">
                        <i class="fas fa-briefcase me-2"></i>
                        Recent Jobs
                    </h4>
                    <p class="card-subtitle">Latest service jobs and their progress</p>
                </div>
                <div class="header-actions">
                    @if(in_array(auth()->user()->role, ['admin', 'accounts']))
                    <a href="{{ route('admin.call-logs.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>
                        New Job
                    </a>
                    @endif
                    <a href="{{ route('admin.call-logs.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-external-link-alt me-1"></i>
                        View All
                    </a>
                </div>
            </div>
            <div class="content-card-body">
                <div class="table-responsive">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($callLogs as $job)
                            <tr>
                                <td>
                                    <span class="ticket-id">#{{ $job->id }}</span>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-name">{{ $job->customer_name }}</div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $jobStatusConfig = [
                                            'pending' => ['class' => 'status-pending', 'icon' => 'fa-clock', 'label' => 'Pending'],
                                            'assigned' => ['class' => 'status-assigned', 'icon' => 'fa-user-check', 'label' => 'Assigned'],
                                            'in_progress' => ['class' => 'status-in-progress', 'icon' => 'fa-spinner', 'label' => 'In Progress'],
                                            'complete' => ['class' => 'status-resolved', 'icon' => 'fa-check', 'label' => 'Complete'],
                                            'cancelled' => ['class' => 'status-cancelled', 'icon' => 'fa-times', 'label' => 'Cancelled']
                                        ];
                                        $jobConfig = $jobStatusConfig[$job->status] ?? ['class' => 'status-pending', 'icon' => 'fa-question', 'label' => ucfirst($job->status)];
                                    @endphp
                                    <span class="status-badge {{ $jobConfig['class'] }}">
                                        <i class="fas {{ $jobConfig['icon'] }} me-1"></i>
                                        {{ $jobConfig['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="amount-charged">${{ number_format($job->amount_charged, 2) }}</div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.call-logs.show', $job) }}" 
                                           class="action-btn view-btn" 
                                           title="View Job">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-briefcase"></i>
                                            <h5>No jobs found</h5>
                                            <p>No recent jobs to display.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions Panel --}}
    <div class="quick-actions-panel mt-5">
        <h2 class="section-title">
            <i class="fas fa-bolt me-2"></i>
            Quick Actions
        </h2>
        <div class="actions-grid">
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="action-content">
                    <h5>New Ticket</h5>
                    <p>Create a new support ticket</p>
                    @if(in_array(auth()->user()->role, ['admin', 'manager']))
                    <a href="{{ route('admin.tickets.create') }}" class="btn btn-sm btn-primary">Create</a>
                    @else
                    <button class="btn btn-sm btn-secondary" disabled>Not Permitted</button>
                    @endif
                </div>
            </div>
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="action-content">
                    <h5>New Job</h5>
                    <p>Schedule a new service job</p>
                    @if(in_array(auth()->user()->role, ['admin', 'accounts']))
                    <a href="{{ route('admin.call-logs.create') }}" class="btn btn-sm btn-success">Schedule</a>
                    @else
                    <button class="btn btn-sm btn-secondary" disabled>Not Permitted</button>
                    @endif
                </div>
            </div>
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="action-content">
                    <h5>Reports</h5>
                    <p>View system reports</p>
                    @if(in_array(auth()->user()->role, ['admin', 'manager']))
                    <a href="{{ route('admin.call-reports.index') }}" class="btn btn-sm btn-info">View</a>
                    @else
                    <button class="btn btn-sm btn-secondary" disabled>Not Permitted</button>
                    @endif
                </div>
            </div>
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="action-content">
                    <h5>Manage Users</h5>
                    <p>User administration</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-warning">Manage</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Dashboard Styles */
.dashboard-container {
    padding: 0;
    max-width: 100%;
}

/* Dashboard Header */
.dashboard-header {
    background: linear-gradient(135deg, var(--white) 0%, var(--gray-50) 100%);
    border-radius: var(--border-radius-xl);
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
}

.dashboard-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--primary);
    margin: 0;
    display: flex;
    align-items: center;
}

.dashboard-subtitle {
    color: var(--gray-600);
    font-size: 0.9rem;
    margin: 0.5rem 0 0 0;
    font-weight: 500;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}

.time-filter .form-select {
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-300);
    padding: 0.5rem;
    font-weight: 500;
    font-size: 0.9rem;
}

#refreshDashboard {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border: none;
    border-radius: var(--border-radius);
    padding: 0.5rem 1rem;
    font-weight: 600;
    transition: var(--transition);
    font-size: 0.9rem;
}

#refreshDashboard:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

/* Navigation Tabs */
.dashboard-nav-wrapper {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    padding: 0.5rem;
    border: 1px solid var(--gray-200);
    overflow-x: auto;
}

.panel-nav {
    border: none;
    gap: 0.25rem;
    margin: 0;
    flex-wrap: nowrap;
    min-width: max-content;
}

.panel-nav .nav-link {
    border: none;
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius);
    color: var(--gray-600);
    font-weight: 600;
    transition: var(--transition);
    display: flex;
    align-items: center;
    text-decoration: none;
    font-size: 0.85rem;
    white-space: nowrap;
}

.panel-nav .nav-link:hover {
    background: var(--gray-100);
    color: var(--primary);
    transform: translateY(-1px);
}

.panel-nav .nav-link.active {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    box-shadow: var(--shadow-md);
}

/* Stats Section */
.stats-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.stats-category {
    margin-bottom: 1.5rem;
}

.category-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--gray-200);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.stat-card.enhanced-card {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    border: 1px solid var(--gray-200);
    transition: var(--transition);
    position: relative;
}

.stat-card.enhanced-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-light);
}

.stat-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.stat-card-body {
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: var(--shadow-sm);
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
    font-weight: 800;
    color: var(--gray-900);
    margin: 0;
    line-height: 1;
}

.stat-label {
    color: var(--gray-600);
    font-size: 0.85rem;
    margin: 0.25rem 0;
    font-weight: 600;
}

.stat-change {
    font-size: 0.8rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.content-card {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    border: 1px solid var(--gray-200);
    height: fit-content;
}

.content-card-header {
    padding: 1rem;
    background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 0.75rem;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary);
    margin: 0;
    display: flex;
    align-items: center;
}

.card-subtitle {
    color: var(--gray-600);
    font-size: 0.8rem;
    margin: 0.25rem 0 0 0;
    font-weight: 500;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
    border-radius: var(--border-radius);
}

.content-card-body {
    padding: 0;
}

/* Enhanced Table */
.enhanced-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
    font-size: 0.85rem;
}

.enhanced-table thead th {
    background: var(--gray-50);
    color: var(--primary);
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.75rem 1rem;
    border-bottom: 2px solid var(--gray-200);
    position: sticky;
    top: 0;
    z-index: 10;
}

.enhanced-table tbody tr {
    transition: var(--transition);
    border-bottom: 1px solid var(--gray-200);
}

.enhanced-table tbody tr:last-child {
    border-bottom: none;
}

.enhanced-table tbody tr:hover {
    background: var(--gray-50);
}

.enhanced-table tbody td {
    padding: 0.75rem 1rem;
    vertical-align: middle;
}

/* Ticket Specific Styles */
.ticket-id {
    font-family: 'Monaco', 'Menlo', monospace;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius);
    font-size: 0.75rem;
    font-weight: 700;
    box-shadow: var(--shadow-sm);
    display: inline-block;
}

.subject-link {
    color: var(--gray-900);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    font-size: 0.85rem;
}

.subject-link:hover {
    color: var(--primary);
}

.priority-indicator.high-priority {
    color: var(--danger);
    margin-left: 0.25rem;
    animation: pulse 2s infinite;
}

/* Priority Badges */
.priority-badge, .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.5rem;
    border-radius: var(--border-radius);
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    box-shadow: var(--shadow-sm);
    white-space: nowrap;
}

/* Priority Badges */
.priority-high {
    background: linear-gradient(135deg, #FEF2F2, #FEE2E2);
    color: var(--danger);
    border: 1px solid #FECACA;
}

.priority-medium {
    background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
    color: var(--warning);
    border: 1px solid #FDE68A;
}

.priority-low {
    background: linear-gradient(135deg, #F0FDF4, #DCFCE7);
    color: var(--success);
    border: 1px solid #BBF7D0;
}

/* Status Badges */
.status-in-progress {
    background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
    color: var(--info);
    border: 1px solid #BFDBFE;
}

.status-resolved {
    background: linear-gradient(135deg, #F0FDF4, #DCFCE7);
    color: var(--success);
    border: 1px solid #BBF7D0;
}

.status-pending {
    background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
    color: var(--warning);
    border: 1px solid #FDE68A;
}

.status-closed {
    background: linear-gradient(135deg, #F9FAFB, #F3F4F6);
    color: var(--gray-600);
    border: 1px solid var(--gray-300);
}

.status-assigned {
    background: linear-gradient(135deg, #F0F9FF, #E0F2FE);
    color: #0891B2;
    border: 1px solid #BAE6FD;
}

.status-cancelled {
    background: linear-gradient(135deg, #FEF2F2, #FEE2E2);
    color: var(--danger);
    border: 1px solid #FECACA;
}

/* Update Info */
.update-info {
    display: flex;
    align-items: center;
}

.update-time {
    color: var(--gray-600);
    font-size: 0.8rem;
    font-weight: 500;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: var(--border-radius);
    transition: var(--transition);
    text-decoration: none;
    border: none;
    cursor: pointer;
    box-shadow: var(--shadow-sm);
}

.view-btn {
    background: linear-gradient(135deg, #F0FDF4, #DCFCE7);
    color: var(--success);
    border: 1px solid #BBF7D0;
}

.view-btn:hover {
    background: var(--success);
    color: var(--white);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Job Specific Styles */
.job-description {
    color: var(--gray-700);
    font-size: 0.8rem;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.amount-charged {
    font-weight: 700;
    color: var(--success);
    font-size: 0.9rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-content i {
    font-size: 3rem;
    color: var(--gray-400);
    margin-bottom: 0.75rem;
}

.empty-content h5 {
    color: var(--gray-600);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.empty-content p {
    color: var(--gray-500);
    font-size: 0.9rem;
    margin: 0;
}

/* Quick Actions Panel */
.quick-actions-panel {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.action-card {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 1.25rem;
    border: 2px solid var(--gray-200);
    transition: var(--transition);
    text-align: center;
}

.action-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    box-shadow: var(--shadow-md);
}

.action-icon i {
    color: var(--white);
    font-size: 1.25rem;
}

.action-content h5 {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--gray-900);
}

.action-content p {
    font-size: 0.8rem;
    color: var(--gray-600);
    margin-bottom: 0.75rem;
}

.action-content .btn {
    padding: 0.25rem 0.75rem;
    font-size: 0.8rem;
    border-radius: var(--border-radius);
}

/* Responsive Design */
@media (min-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    
    .dashboard-header {
        flex-wrap: nowrap;
    }
    
    .header-actions {
        margin-top: 0;
    }
}

@media (min-width: 768px) {
    .dashboard-title {
        font-size: 1.75rem;
    }
    
    .dashboard-subtitle {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .panel-nav .nav-link {
        font-size: 0.75rem;
        padding: 0.5rem;
    }
    
    .stat-card-body {
        padding: 1rem;
        gap: 0.75rem;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .stat-label {
        font-size: 0.8rem;
    }
    
    .action-card {
        padding: 1rem;
    }
}

/* Animation Keyframes */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Refresh Dashboard
    const refreshBtn = document.getElementById('refreshDashboard');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    }

    // Auto-refresh stats every 5 minutes
    setInterval(() => {
        console.log('Auto-refreshing dashboard stats...');
        // Add AJAX call to refresh stats here
    }, 300000);
});
</script>
@endsection