@extends('layouts.admin')

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

    {{-- Navigation Tabs --}}
    <div class="dashboard-nav-wrapper mb-4">
        <ul class="panel-nav nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.index') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.tickets.index') }}">
                    <i class="fas fa-ticket-alt me-2"></i>
                    All Tickets
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.call-logs.index') }}">
                    <i class="fas fa-briefcase me-2"></i>
                    Job Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.contacts.index') }}">
                    <i class="fas fa-users me-2"></i>
                    Customers
                </a>
            </li>
        </ul>
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
                                <div class="stat-trend">
                                    <div class="trend-line" data-trend="{{ $change }}"></div>
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

        {{-- Content Stats --}}
        <div class="stats-category">
            <h3 class="category-title">Content Management</h3>
            <div class="stats-grid">
                @php
                $contentStats = [
                    'blogs' => [
                        'label' => 'Blog Posts',
                        'icon' => 'fa-newspaper',
                        'bg' => 'linear-gradient(135deg, #10B981 0%, #059669 100%)',
                        'count' => $blogCount ?? 0,
                        'route' => route('admin.blogs.index')
                    ],
                    'faqs' => [
                        'label' => 'Active FAQs',
                        'icon' => 'fa-question-circle',
                        'bg' => 'linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%)',
                        'count' => $activeFaqCount ?? 0,
                        'route' => route('admin.faqs.index')
                    ],
                    'services' => [
                        'label' => 'Services',
                        'icon' => 'fa-cogs',
                        'bg' => 'linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%)',
                        'count' => $serviceCount ?? 0,
                        'route' => route('admin.services.index')
                    ],
                    'subscribers' => [
                        'label' => 'Subscribers',
                        'icon' => 'fa-users',
                        'bg' => 'linear-gradient(135deg, #06B6D4 0%, #0891B2 100%)',
                        'count' => $subscriberCount ?? 0,
                        'route' => route('admin.subscribers.index')
                    ]
                ];
                @endphp

                @foreach ($contentStats as $key => $data)
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
                    <div class="filter-dropdown">
                        <select class="form-select form-select-sm" id="ticketFilter">
                            <option value="all">All Tickets</option>
                            <option value="urgent">Urgent Only</option>
                            <option value="unassigned">Unassigned</option>
                            <option value="mine">My Tickets</option>
                        </select>
                    </div>
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
                                <th class="col-customer">Customer</th>
                                <th class="col-priority">Priority</th>
                                <th class="col-status">Status</th>
                                <th class="col-assignee">Assignee</th>
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
                                <td class="col-customer">
                                    <div class="customer-info">
                                        <div class="customer-name">{{ $ticket->company_name }}</div>
                                        @if($ticket->customer_email)
                                            <div class="customer-email">{{ Str::limit($ticket->customer_email, 25) }}</div>
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
                                <td class="col-assignee">
                                    <div class="assignee-info">
                                        @if($ticket->assigned_to)
                                            <div class="assignee-avatar">
                                                {{ strtoupper(substr($ticket->assignedTo->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <span class="assignee-name">{{ $ticket->assignedTo->name ?? 'Unknown' }}</span>
                                        @else
                                            <span class="unassigned-label">
                                                <i class="fas fa-user-slash me-1"></i>
                                                Unassigned
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-updated">
                                    <div class="update-info">
                                        <span class="update-time" title="{{ $ticket->updated_at->format('M d, Y H:i') }}">
                                            {{ $ticket->updated_at->diffForHumans() }}
                                        </span>
                                        <div class="update-indicator">
                                            @if($ticket->updated_at->diffInHours() < 1)
                                                <span class="indicator recent"></span>
                                            @elseif($ticket->updated_at->diffInDays() > 7)
                                                <span class="indicator old"></span>
                                            @else
                                                <span class="indicator normal"></span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="col-actions">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
                                           class="action-btn view-btn" 
                                           title="View Ticket">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(in_array(auth()->user()->role, ['admin', 'technician']))
                                            <button class="action-btn edit-btn" 
                                                    title="Quick Edit" 
                                                    data-ticket-id="{{ $ticket->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="empty-state">
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
                
                @if($tickets->hasPages())
                    <div class="pagination-wrapper">
                        {{ $tickets->links() }}
                    </div>
                @endif
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
                    <a href="{{ route('admin.call-logs.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>
                        New Job
                    </a>
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
                                <th>Description</th>
                                <th>Status</th>
                                <th>Technician</th>
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
                                        @if($job->customer_email)
                                            <div class="customer-email">{{ Str::limit($job->customer_email, 25) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="job-description" title="{{ $job->fault_description }}">
                                        {{ Str::limit($job->fault_description, 40) }}
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
                                    <div class="assignee-info">
                                        @if($job->assignedTo)
                                            <div class="assignee-avatar">
                                                {{ strtoupper(substr($job->assignedTo->name, 0, 1)) }}
                                            </div>
                                            <span class="assignee-name">{{ $job->assignedTo->name }}</span>
                                        @else
                                            <span class="unassigned-label">
                                                <i class="fas fa-user-slash me-1"></i>
                                                Unassigned
                                            </span>
                                        @endif
                                    </div>
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
                                    <td colspan="7" class="empty-state">
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
                    <a href="{{ route('admin.tickets.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
            </div>
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="action-content">
                    <h5>New Job</h5>
                    <p>Schedule a new service job</p>
                    <a href="{{ route('admin.call-logs.create') }}" class="btn btn-sm btn-success">Schedule</a>
                </div>
            </div>
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="action-content">
                    <h5>Reports</h5>
                    <p>View system reports</p>
                    <a href="{{ route('admin.call-reports.index') }}" class="btn btn-sm btn-info">View</a>
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
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary);
    margin: 0;
    display: flex;
    align-items: center;
}

.dashboard-subtitle {
    color: var(--gray-600);
    font-size: 1.1rem;
    margin: 0.5rem 0 0 0;
    font-weight: 500;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.time-filter .form-select {
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-300);
    padding: 0.5rem 1rem;
    font-weight: 500;
}

#refreshDashboard {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border: none;
    border-radius: var(--border-radius);
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: var(--transition);
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
    padding: 0.75rem;
    border: 1px solid var(--gray-200);
}

.panel-nav {
    border: none;
    gap: 0.5rem;
    margin: 0;
}

.panel-nav .nav-link {
    border: none;
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    color: var(--gray-600);
    font-weight: 600;
    transition: var(--transition);
    display: flex;
    align-items: center;
    text-decoration: none;
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
    margin-bottom: 3rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.stats-category {
    margin-bottom: 2rem;
}

.category-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--gray-200);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.stat-card.enhanced-card {
    background: var(--white);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    border: 1px solid var(--gray-200);
    transition: var(--transition);
    position: relative;
}

.stat-card.enhanced-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-light);
}

.stat-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.stat-card-body {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--border-radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: var(--shadow-sm);
}

.stat-icon i {
    font-size: 1.5rem;
    color: var(--white);
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2.25rem;
    font-weight: 800;
    color: var(--gray-900);
    margin: 0;
    line-height: 1;
}

.stat-label {
    color: var(--gray-600);
    font-size: 0.95rem;
    margin: 0.5rem 0;
    font-weight: 600;
}

.stat-change {
    font-size: 0.85rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stat-trend {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 40px;
    height: 20px;
}

.trend-line {
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, transparent 40%, var(--success) 50%, transparent 60%);
    border-radius: 2px;
    opacity: 0.3;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

.content-card {
    background: var(--white);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    border: 1px solid var(--gray-200);
    height: fit-content;
}

.content-card-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary);
    margin: 0;
    display: flex;
    align-items: center;
}

.card-subtitle {
    color: var(--gray-600);
    font-size: 0.9rem;
    margin: 0.5rem 0 0 0;
    font-weight: 500;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.filter-dropdown .form-select {
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-300);
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    min-width: 120px;
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
}

.enhanced-table thead th {
    background: var(--gray-50);
    color: var(--primary);
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem 1.5rem;
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
    transform: scale(1.01);
}

.enhanced-table tbody td {
    padding: 1rem 1.5rem;
    vertical-align: middle;
}

/* Ticket Specific Styles */
.ticket-id {
    font-family: 'Monaco', 'Menlo', monospace;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    padding: 0.375rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 700;
    box-shadow: var(--shadow-sm);
}

.subject-link {
    color: var(--gray-900);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.subject-link:hover {
    color: var(--primary);
}

.priority-indicator.high-priority {
    color: var(--danger);
    margin-left: 0.5rem;
    animation: pulse 2s infinite;
}

.customer-info {
    display: flex;
    flex-direction: column;
}

.customer-name {
    font-weight: 600;
    color: var(--gray-900);
    font-size: 0.9rem;
}

.customer-email {
    font-size: 0.8rem;
    color: var(--gray-500);
    margin-top: 0.25rem;
}

.priority-badge, .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    box-shadow: var(--shadow-sm);
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

/* Assignee Info */
.assignee-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.assignee-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
    box-shadow: var(--shadow-sm);
}

.assignee-name {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.9rem;
}

.unassigned-label {
    color: var(--gray-500);
    font-style: italic;
    font-size: 0.9rem;
}

/* Update Info */
.update-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.update-time {
    color: var(--gray-600);
    font-size: 0.85rem;
    font-weight: 500;
}

.update-indicator {
    display: flex;
    align-items: center;
}

.indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.indicator.recent {
    background: var(--success);
    animation: pulse 2s infinite;
}

.indicator.normal {
    background: var(--info);
}

.indicator.old {
    background: var(--warning);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
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

.edit-btn {
    background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
    color: var(--warning);
    border: 1px solid #FDE68A;
}

.edit-btn:hover {
    background: var(--warning);
    color: var(--white);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Job Specific Styles */
.job-description {
    color: var(--gray-700);
    font-size: 0.9rem;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.amount-charged {
    font-weight: 700;
    color: var(--success);
    font-size: 1rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-content i {
    font-size: 4rem;
    color: var(--gray-400);
    margin-bottom: 1rem;
}

.empty-content h5 {
    color: var(--gray-600);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-content p {
    color: var(--gray-500);
    font-size: 1rem;
    margin: 0;
}

/* Quick Actions Panel */
.quick-actions-panel {
    background: var(--white);
    border-radius: var(--border-radius-xl);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.action-card {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    border: 2px solid var(--gray-200);
    transition: var(--transition);
    text-align: center;
}

.action-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.action-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    box-shadow: var(--shadow-md);
}

.action-icon i {
    color: var(--white);
    font-size: 1.5rem;
}

.action-content h5 {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--gray-900);
}

.action-content p {
    font-size: 0.9rem;
    color: var(--gray-600);
    margin-bottom: 1rem;
}

/* Pagination */
.pagination-wrapper {
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--gray-200);
    display: flex;
    justify-content: center;
    background: var(--gray-50);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .content-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.5rem;
    }

    .header-actions {
        width: 100%;
        justify-content: flex-start;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .content-card-header {
        padding: 1rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .header-actions {
        width: 100%;
        justify-content: flex-start;
    }

    .enhanced-table {
        font-size: 0.8rem;
    }

    .enhanced-table th,
    .enhanced-table td {
        padding: 0.75rem 1rem;
    }

    .actions-grid {
        grid-template-columns: 1fr;
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

/* Print Styles */
@media print {
    .dashboard-header,
    .dashboard-nav-wrapper,
    .quick-actions-panel,
    .action-buttons {
        display: none;
    }
    
    .content-card {
        box-shadow: none;
        border: 1px solid var(--gray-300);
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

    // Ticket Filter
    const ticketFilter = document.getElementById('ticketFilter');
    if (ticketFilter) {
        ticketFilter.addEventListener('change', function() {
            const filterValue = this.value;
            const rows = document.querySelectorAll('.ticket-row');
            
            rows.forEach(row => {
                if (filterValue === 'all') {
                    row.style.display = '';
                } else if (filterValue === 'urgent') {
                    row.style.display = row.dataset.priority === 'high' ? '' : 'none';
                } else if (filterValue === 'unassigned') {
                    row.style.display = row.querySelector('.unassigned-label') ? '' : 'none';
                }
            });
        });
    }

    // Quick edit functionality
    const editBtns = document.querySelectorAll('.edit-btn');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Add quick edit modal functionality here
            console.log('Quick edit for ticket:', this.dataset.ticketId);
        });
    });

    // Add loading states for action buttons
    const actionBtns = document.querySelectorAll('.action-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (!this.href) return;
            
            const icon = this.querySelector('i');
            const originalClass = icon.className;
            icon.className = 'fas fa-spinner fa-spin';
            
            setTimeout(() => {
                icon.className = originalClass;
            }, 2000);
        });
    });

    // Auto-refresh stats every 5 minutes
    setInterval(() => {
        console.log('Auto-refreshing dashboard stats...');
        // Add AJAX call to refresh stats here
    }, 300000);
});
</script>
@endsection
