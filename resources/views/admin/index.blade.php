@extends('layouts.app')

@section('title', 'Dashboard - System Overview')

@section('content')

<style>
    /* Enhanced View All Button Styles */
.header-actions .btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
    white-space: nowrap;
}

/* View All Button with text */
.header-actions .btn-xs.d-flex {
    width: auto;
    min-width: 80px;
    padding: 0.25rem 0.75rem;
    gap: 0.375rem;
}

/* Primary View All Button */
.header-actions .btn-primary.btn-xs {
    background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
    color: white;
    border: 1px solid #2563EB;
}

.header-actions .btn-primary.btn-xs:hover {
    background: linear-gradient(135deg, #1D4ED8 0%, #1E40AF 100%);
    border-color: #1E40AF;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

.header-actions .btn-primary.btn-xs:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
}

/* Success Create Button */
.header-actions .btn-success.btn-xs {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    border: 1px solid #047857;
}

.header-actions .btn-success.btn-xs:hover {
    background: linear-gradient(135deg, #047857 0%, #065F46 100%);
    border-color: #065F46;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
}

/* Button Icons */
.header-actions .btn-xs i {
    font-size: 0.7rem;
    line-height: 1;
}

/* Hover Animation */
.header-actions .btn-xs::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.header-actions .btn-xs:hover::before {
    left: 100%;
}

/* Focus States for Accessibility */
.header-actions .btn-xs:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.header-actions .btn-success.btn-xs:focus {
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .header-actions .btn-xs.d-flex span {
        display: none;
    }
    
    .header-actions .btn-xs.d-flex {
        width: 28px;
        min-width: 28px;
        padding: 0.25rem 0.5rem;
    }
    
    .header-actions .btn-xs.d-flex .me-1 {
        margin-right: 0 !important;
    }
}

@media (max-width: 480px) {
    .header-actions {
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .header-actions .btn-xs {
        font-size: 0.7rem;
        height: 26px;
    }
}

/* Loading State */
.header-actions .btn-xs.loading {
    pointer-events: none;
    opacity: 0.7;
}

.header-actions .btn-xs.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Tooltip Enhancement */
.header-actions .btn-xs[title]:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    white-space: nowrap;
    z-index: 1000;
    margin-bottom: 0.25rem;
    opacity: 0;
    animation: fadeIn 0.2s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateX(-50%) translateY(2px); }
    to { opacity: 1; transform: translateX(-50%) translateY(0); }
}

/* Button Group Spacing */
.header-actions {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

/* Enhanced Primary Button with Better Visibility */
.header-actions .btn-primary.btn-xs {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    border-color: #047857;
    font-weight: 700;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.header-actions .btn-primary.btn-xs:hover {
    background: linear-gradient(135deg, #047857 0%, #065F46 100%);
    border-color: #065F46;
    box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
}

/* Success Button Enhancement */
.header-actions .btn-success.btn-xs {
    background: linear-gradient(135deg, #16A34A 0%, #15803D 100%);
    border-color: #15803D;
}

.header-actions .btn-success.btn-xs:hover {
    background: linear-gradient(135deg, #15803D 0%, #166534 100%);
    border-color: #166534;
    box-shadow: 0 4px 8px rgba(22, 163, 74, 0.3);
}

</style>
<div class="dashboard-container">
    {{-- Enhanced Dashboard Header with Period Integration --}}
    <div class="dashboard-header mb-3 d-flex flex-wrap justify-content-between align-items-center">
        <div class="header-content">
            <h1 class="dashboard-title mb-0">
                <i class="fas fa-chart-line me-2"></i>
                Dashboard Overview
            </h1>
            <p class="dashboard-subtitle mb-0">System overview with tickets, jobs, and content management</p>
            <div class="period-indicator mt-1">
                <span class="badge bg-primary">{{ ucfirst($period ?? 'week') }} View</span>
                <small class="text-muted ms-2">Last updated: {{ now()->format('H:i') }}</small>
            </div>
        </div>
        <div class="header-actions d-flex flex-wrap gap-2 align-items-center">
            <div class="time-filter d-flex flex-nowrap align-items-center">
                <label for="timeFilter" class="filter-label mb-0 me-2">Period</label>
                <select class="form-select" id="timeFilter" style="width: auto;">
                    <option value="today" {{ ($period ?? 'week') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ ($period ?? 'week') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ ($period ?? 'week') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="quarter" {{ ($period ?? 'week') == 'quarter' ? 'selected' : '' }}>This Quarter</option>
                </select>
            </div>
    
        </div>
    </div>

    {{-- Optimized Stats Grid --}}
    <div class="stats-section mb-3">
        <h2 class="section-title">
            <i class="fas fa-chart-bar me-2"></i>
            Statistics
        </h2>
        
        {{-- Tickets Stats --}}
        <div class="stats-category mb-3">
            <h3 class="category-title">Tickets</h3>
            <div class="stats-grid">
                @php
                    $ticketStats = [
                        'in_progress' => [
                            'label' => 'In Progress',
                            'icon' => 'fa-play-circle',
                            'bg' => 'linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%)',
                            'count' => $statusCounts->in_progress ?? 0,
                            'change' => $percentageChanges['in_progress'] ?? 0,
                            'route' => route('admin.tickets.open')
                        ],
                        'resolved' => [
                            'label' => 'Resolved',
                            'icon' => 'fa-check-circle',
                            'bg' => 'linear-gradient(135deg, #059669 0%, #047857 100%)',
                            'count' => $statusCounts->resolved ?? 0,
                            'change' => $percentageChanges['resolved'] ?? 0,
                            'route' => route('admin.tickets.solved')
                        ],
                        'pending' => [
                            'label' => 'Pending',
                            'icon' => 'fa-clock',
                            'bg' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)',
                            'count' => $statusCounts->pending ?? 0,
                            'change' => $percentageChanges['pending'] ?? 0,
                            'route' => route('admin.tickets.pending')
                        ],
                        'unassigned' => [
                            'label' => 'Unassigned',
                            'icon' => 'fa-user-slash',
                            'bg' => 'linear-gradient(135deg, #DC2626 0%, #B91C1C 100%)',
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
                    <div class="stat-card">
                        <a href="{{ $data['route'] }}" class="stat-link">
                            <div class="stat-card-body">
                                <div class="stat-icon" style="background: {{ $data['bg'] }};">
                                    <i class="fas {{ $data['icon'] }}"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $data['count'] }}</h3>
                                    <p class="stat-label">{{ $data['label'] }}</p>
                                    <div class="stat-change {{ $textClass }}">
                                        <i class="fas {{ $arrowClass }}"></i>
                                        <span>{{ abs($change) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Jobs Stats --}}
        <div class="stats-category mb-3">
            <h3 class="category-title">Jobs</h3>
            <div class="stats-grid">
                @php
                    $jobStats = [
                        'pending_jobs' => [
                            'label' => 'Pending',
                            'icon' => 'fa-clock',
                            'bg' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)',
                            'count' => $stats['pending_jobs'] ?? 0,
                            'route' => route('admin.call-logs.pending')
                        ],
                        'in_progress_jobs' => [
                            'label' => 'In Progress',
                            'icon' => 'fa-spinner',
                            'bg' => 'linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%)',
                            'count' => $stats['in_progress_jobs'] ?? 0,
                            'route' => route('admin.call-logs.in-progress')
                        ],
                        'completed_jobs' => [
                            'label' => 'Completed',
                            'icon' => 'fa-check-circle',
                            'bg' => 'linear-gradient(135deg, #059669 0%, #047857 100%)',
                            'count' => $stats['completed_jobs'] ?? 0,
                            'route' => route('admin.call-logs.completed')
                        ],
                        'total_jobs' => [
                            'label' => 'Total',
                            'icon' => 'fa-clipboard-list',
                            'bg' => 'linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%)',
                            'count' => $stats['total_jobs'] ?? 0,
                            'route' => route('admin.index')
                        ]
                    ];
                @endphp

                @foreach ($jobStats as $key => $data)
                    <div class="stat-card">
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

    {{-- Enhanced Content Grid --}}
    <div class="content-grid">
        {{-- Recent Tickets Table --}}
        <div class="content-card">
            <div class="content-card-header">
                <div class="header-content">
                    <h4 class="card-title">
                        <i class="fas fa-ticket-alt me-2"></i>
                        Recent Tickets
                    </h4>
                    <p class="card-subtitle mb-0">Latest 5 tickets from {{ ucfirst($period ?? 'week') }}</p>
                </div>
                <div class="header-actions d-flex align-items-center gap-2">
                    @can('create tickets')
                    <a href="{{ route('admin.tickets.create') }}" class="btn btn-success btn-xs" title="Create New Ticket">
                        <i class="fas fa-plus"></i>
                    </a>
                    @endcan
                    <a href="{{ route('admin.tickets.all') }}" class="btn btn-primary btn-xs d-flex align-items-center">
                        <i class="fas fa-list me-1"></i>
                        <span>View All</span>
                    </a>
                </div>
            </div>

            <div class="content-card-body">
                <div class="table-responsive">
                    <table class="compact-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tickets as $ticket)
                            <tr>
                                <td>
                                    <span class="ticket-id">#{{ $ticket->id }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="subject-link">
                                        {{ Str::limit($ticket->subject, 25) }}
                                    </a>
                                    <div class="ticket-meta">
                                        <small class="text-muted">{{ $ticket->company_name ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $priorityConfig = [
                                            'high' => ['class' => 'priority-high', 'icon' => 'fa-exclamation-triangle'],
                                            'medium' => ['class' => 'priority-medium', 'icon' => 'fa-minus-circle'],
                                            'low' => ['class' => 'priority-low', 'icon' => 'fa-circle']
                                        ];
                                        $pConfig = $priorityConfig[$ticket->priority ?? 'low'];
                                    @endphp
                                    <span class="priority-badge {{ $pConfig['class'] }}">
                                        <i class="fas {{ $pConfig['icon'] }}"></i>
                                        {{ ucfirst($ticket->priority ?? 'low') }}
                                    </span>
                                </td>
                                <td>
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
                                        <i class="fas {{ $config['icon'] }}"></i>
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td>
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
                                    <td colspan="5" class="empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-inbox"></i>
                                            <h6>No tickets found</h6>
                                            <p>No tickets for the selected period.</p>
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
        <div class="content-card">
            <div class="content-card-header">
                <div class="header-content">
                    <h4 class="card-title">
                        <i class="fas fa-briefcase me-2"></i>
                        Recent Jobs
                    </h4>
                    <p class="card-subtitle mb-0">Latest 5 jobs from {{ ucfirst($period ?? 'week') }}</p>
                </div>
                <div class="header-actions d-flex align-items-center gap-2">
                    @can('create jobs')
                    <a href="{{ route('admin.call-logs.create') }}" class="btn btn-success btn-xs">
                        <i class="fas fa-plus"></i>
                    </a>
                    @endcan
                    <a href="{{ route('admin.call-logs.all') }}" class="btn btn-primary btn-xs d-flex align-items-center">
                        <i class="fas fa-list me-1"></i>
                        <span>View All</span>
                    </a>
                </div>
            </div>
            <div class="content-card-body">
                <div class="table-responsive">
                    <table class="compact-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
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
                                        <div class="customer-name">{{ Str::limit($job->customer_name, 20) }}</div>
                                        @if($job->company_name)
                                        <small class="text-muted">{{ Str::limit($job->company_name, 20) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="amount-charged">${{ number_format($job->amount_charged, 2) }}</span>
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
                                        <i class="fas {{ $jobConfig['icon'] }}"></i>
                                        {{ $jobConfig['label'] }}
                                    </span>
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
                                            <h6>No jobs found</h6>
                                            <p>No jobs for the selected period.</p>
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

    {{-- Enhanced Quick Actions Panel --}}
   {{-- Restored Quick Actions Panel with Original Permissions --}}
<div class="quick-actions-panel mt-3">
    <h2 class="section-title">
        <i class="fas fa-bolt me-2"></i>
        Quick Actions
    </h2>
    <div class="actions-grid">
        @php
            $quickActions = [
                [
                    'key' => 'ticket',
                    'title' => 'New Ticket',
                    'description' => 'Create support ticket',
                    'icon' => 'fa-plus',
                    'icon_class' => 'ticket-icon',
                    'route' => route('admin.tickets.create'),
                    'btn_class' => 'btn-primary',
                    'btn_text' => 'Create',
                    'permission' => in_array(auth()->user()->role, ['admin', 'manager'])
                ],
                [
                    'key' => 'job',
                    'title' => 'New Job',
                    'description' => 'Schedule service job',
                    'icon' => 'fa-briefcase',
                    'icon_class' => 'job-icon',
                    'route' => route('admin.call-logs.create'),
                    'btn_class' => 'btn-success',
                    'btn_text' => 'Schedule',
                    'permission' => in_array(auth()->user()->role, ['admin', 'accounts'])
                ],
                [
                    'key' => 'blog',
                    'title' => 'New Blog',
                    'description' => 'Create blog post',
                    'icon' => 'fa-pen-alt',
                    'icon_class' => 'blog-icon',
                    'route' => route('admin.blogs.create'),
                    'btn_class' => 'btn-info',
                    'btn_text' => 'Write',
                    'permission' => true
                ],
                [
                    'key' => 'reports',
                    'title' => 'Reports',
                    'description' => 'View system reports',
                    'icon' => 'fa-chart-line',
                    'icon_class' => 'reports-icon',
                    'route' => route('admin.call-reports.index'),
                    'btn_class' => 'btn-warning',
                    'btn_text' => 'View',
                    'permission' => in_array(auth()->user()->role, ['admin', 'manager'])
                ]
            ];
        @endphp

        @foreach($quickActions as $action)
        <div class="action-card" data-permission="{{ $action['permission'] ? 'allowed' : 'denied' }}" data-action="{{ $action['key'] }}">
            <div class="action-icon {{ $action['icon_class'] }}">
                <i class="fas {{ $action['icon'] }}"></i>
            </div>
            <div class="action-content">
                <h6 class="action-title">{{ $action['title'] }}</h6>
                <p class="action-description">{{ $action['description'] }}</p>
                @if($action['permission'])
                    <a href="{{ $action['route'] }}" class="btn {{ $action['btn_class'] }} btn-sm action-btn">{{ $action['btn_text'] }}</a>
                @else
                    <button class="btn btn-secondary btn-sm action-btn" disabled>No Access</button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

</div>

@push('styles')
<style>
/* Enhanced Compact Dashboard Styles */
:root {
    --primary: #059669;
    --success: #059669;
    --warning: #F59E0B;
    --danger: #DC2626;
    --info: #0EA5E9;
    --white: #FFFFFF;
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --gray-800: #1F2937;
    --border-radius: 8px;
    --transition: all 0.2s ease;
}

/* Enhanced Refresh Button and Period Selector Styles */

/* Time Filter Container */
.time-filter {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
    position: relative;
}

.filter-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-700);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0;
}

/* Enhanced Period Selector */
.time-filter .form-select {
    border-radius: var(--border-radius);
    border: 2px solid var(--gray-300);
    padding: 0.5rem 2rem 0.5rem 0.75rem;
    font-size: 0.85rem;
    font-weight: 600;
    min-width: 150px;
    height: 36px;
    background: linear-gradient(135deg, var(--white) 0%, var(--gray-50) 100%);
    color: var(--gray-800);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    position: relative;
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
}

.time-filter .form-select:hover {
    border-color: var(--primary);
    box-shadow: 0 4px 8px rgba(5, 150, 105, 0.15);
    transform: translateY(-1px);
}

.time-filter .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1), 0 4px 8px rgba(5, 150, 105, 0.15);
    outline: none;
    transform: translateY(-1px);
}

.time-filter .form-select:active {
    transform: translateY(0);
}

/* Loading state for selector */
.time-filter .form-select.loading {
    opacity: 0.7;
    pointer-events: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'%3e%3ccircle cx='12' cy='12' r='10' stroke='%236b7280' stroke-width='4' fill='none' opacity='0.25'/%3e%3cpath fill='%236b7280' d='M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z'/%3e%3c/svg%3e");
    animation: spin 1s linear infinite;
    background-position: right 0.5rem center;
}

/* Enhanced Refresh Button */
.btn-sm#refreshDashboard {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    font-weight: 700;
    height: 36px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 2px solid var(--primary);
    border-radius: var(--border-radius);
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: var(--white);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);
    position: relative;
    overflow: hidden;
    cursor: pointer;
    text-decoration: none;
    min-width: 110px;
    justify-content: center;
}

/* Refresh button hover effect */
.btn-sm#refreshDashboard:hover {
    background: linear-gradient(135deg, var(--primary-dark) 0%, #065F46 100%);
    border-color: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(5, 150, 105, 0.3);
}

.btn-sm#refreshDashboard:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);
}

/* Refresh button focus state */
.btn-sm#refreshDashboard:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2), 0 6px 12px rgba(5, 150, 105, 0.3);
}

/* Refresh button loading state */
.btn-sm#refreshDashboard.loading,
.btn-sm#refreshDashboard:disabled {
    opacity: 0.8;
    cursor: not-allowed;
    pointer-events: none;
    background: linear-gradient(135deg, var(--gray-400) 0%, var(--gray-500) 100%);
    border-color: var(--gray-400);
    transform: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Spinning icon animation */
.btn-sm#refreshDashboard .fa-spinner {
    animation: spin 1s linear infinite;
}

.btn-sm#refreshDashboard .fa-sync-alt {
    transition: transform 0.3s ease;
}

.btn-sm#refreshDashboard:hover .fa-sync-alt {
    transform: rotate(180deg);
}

/* Shimmer effect for refresh button */
.btn-sm#refreshDashboard::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.6s;
    z-index: 1;
}

.btn-sm#refreshDashboard:hover::before {
    left: 100%;
}

/* Button text overlay for shimmer */
.btn-sm#refreshDashboard > * {
    position: relative;
    z-index: 2;
}

/* Header Actions Container Enhancement */
.header-actions {
    display: flex;
    align-items: flex-end;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Period Indicator Badge Enhancement */
.period-indicator {
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.period-indicator .badge {
    font-size: 0.7rem;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: var(--white);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    box-shadow: 0 1px 3px rgba(5, 150, 105, 0.3);
    border: none;
}

/* Last Updated Text */
.period-indicator small {
    color: var(--gray-500);
    font-size: 0.75rem;
    font-weight: 500;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .header-actions {
        width: 100%;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
    }
    
    .time-filter .form-select {
        min-width: 130px;
        font-size: 0.8rem;
    }
    
    .btn-sm#refreshDashboard {
        min-width: 100px;
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .filter-label {
        font-size: 0.7rem;
    }
}

@media (max-width: 480px) {
    .header-actions {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    
    .time-filter {
        flex-direction: row;
        align-items: center;
        gap: 0.5rem;
    }
    
    .time-filter .form-select {
        min-width: 120px;
        flex: 1;
    }
    
    .btn-sm#refreshDashboard {
        width: 100%;
        justify-content: center;
    }
}

/* Success Animation for Refresh */
@keyframes successPulse {
    0% { box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2); }
    50% { box-shadow: 0 6px 12px rgba(5, 150, 105, 0.4); }
    100% { box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2); }
}

.btn-sm#refreshDashboard.success {
    animation: successPulse 0.6s ease-in-out;
    background: linear-gradient(135deg, #16A34A 0%, #15803D 100%);
    border-color: #15803D;
}

/* Loading dots animation for period change */
.time-filter.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    right: 2rem;
    width: 8px;
    height: 8px;
    background: var(--primary);
    border-radius: 50%;
    animation: loadingDots 1.4s infinite ease-in-out;
    transform: translateY(-50%);
}

@keyframes loadingDots {
    0%, 80%, 100% {
        transform: translateY(-50%) scale(0);
        opacity: 0.5;
    }
    40% {
        transform: translateY(-50%) scale(1);
        opacity: 1;
    }
}

/* Spin animation */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Focus visible for better accessibility */
.time-filter .form-select:focus-visible,
.btn-sm#refreshDashboard:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .time-filter .form-select {
        border-width: 3px;
    }
    
    .btn-sm#refreshDashboard {
        border-width: 3px;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .time-filter .form-select,
    .btn-sm#refreshDashboard {
        transition: none;
    }
    
    .btn-sm#refreshDashboard::before {
        display: none;
    }
    
    .btn-sm#refreshDashboard .fa-sync-alt {
        transition: none;
    }
    
    .btn-sm#refreshDashboard:hover .fa-sync-alt {
        transform: none;
    }
}


.dashboard-container { padding: 0; max-width: 100%; }

/* Dashboard Header */
.dashboard-header {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 1rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--gray-200);
    gap: 1rem;
}

.dashboard-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary);
    margin: 0;
    display: flex;
    align-items: center;
}

.dashboard-subtitle {
    color: var(--gray-600);
    font-size: 0.85rem;
    margin: 0.25rem 0 0 0;
    font-weight: 500;
}

.period-indicator .badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.time-filter { display: flex; flex-direction: column; gap: 0.25rem; }

.filter-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-700);
}

.time-filter .form-select {
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-300);
    padding: 0.375rem 0.75rem;
    font-size: 0.85rem;
    min-width: 140px;
    height: 32px;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
    height: 32px;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
    width: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Stats Section */
.stats-section { margin-bottom: 1.5rem; }

.section-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.stats-category { margin-bottom: 1.5rem; }

.category-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.75rem;
    padding-bottom: 0.25rem;
    border-bottom: 1px solid var(--gray-200);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
}

.stat-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid var(--gray-200);
    transition: var(--transition);
}

.stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12); }

.stat-link { text-decoration: none; color: inherit; display: block; }

.stat-card-body {
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stat-icon i { font-size: 1.25rem; color: var(--white); }

.stat-content { flex: 1; }

.stat-number {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--gray-800);
    margin: 0;
    line-height: 1;
}

.stat-label {
    color: var(--gray-600);
    font-size: 0.8rem;
    margin: 0.25rem 0;
    font-weight: 600;
}

.stat-change {
    font-size: 0.7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

@media (min-width: 768px) {
    .content-grid { grid-template-columns: 1fr 1fr; }
}

.content-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid var(--gray-200);
}

.content-card-header {
    padding: 0.75rem 1rem;
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.5rem;
}

.card-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--primary);
    margin: 0;
    display: flex;
    align-items: center;
}

.card-subtitle {
    font-size: 0.75rem;
    color: var(--gray-600);
    margin: 0;
}

/* Table Styles */
.compact-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
    font-size: 0.8rem;
}

.compact-table thead th {
    background: var(--gray-100);
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid var(--gray-200);
}

.compact-table tbody tr {
    transition: var(--transition);
    border-bottom: 1px solid var(--gray-200);
}

.compact-table tbody tr:hover { background: var(--gray-50); }

.compact-table tbody td {
    padding: 0.5rem 0.75rem;
    vertical-align: middle;
}

/* Enhanced Elements */
.ticket-id {
    font-family: 'Monaco', 'Menlo', monospace;
    background: var(--primary);
    color: var(--white);
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-block;
}

.subject-link {
    color: var(--gray-800);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    font-size: 0.8rem;
}

.subject-link:hover { color: var(--primary); }

.ticket-meta {
    margin-top: 0.25rem;
}

.customer-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.customer-name {
    font-weight: 600;
    color: var(--gray-800);
}

.amount-charged {
    font-weight: 700;
    color: var(--success);
    font-size: 0.9rem;
}

/* Badge Styles */
.priority-badge, .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
}

.priority-high { background: #FEF2F2; color: var(--danger); border: 1px solid #FECACA; }
.priority-medium { background: #FFFBEB; color: var(--warning); border: 1px solid #FDE68A; }
.priority-low { background: #F0FDF4; color: var(--success); border: 1px solid #BBF7D0; }

.status-in-progress { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
.status-resolved { background: #F0FDF4; color: var(--success); border: 1px solid #BBF7D0; }
.status-pending { background: #FFFBEB; color: var(--warning); border: 1px solid #FDE68A; }
.status-assigned { background: #F0F9FF; color: #0891B2; border: 1px solid #BAE6FD; }
.status-cancelled { background: #FEF2F2; color: var(--danger); border: 1px solid #FECACA; }
.status-closed { background: #F9FAFB; color: #6B7280; border: 1px solid #E5E7EB; }

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 4px;
    transition: var(--transition);
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.view-btn {
    background: #F0FDF4;
    color: var(--success);
    border: 1px solid #BBF7D0;
}

.view-btn:hover { background: var(--success); color: var(--white); }

/* Quick Actions */
.quick-actions-panel {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--gray-200);
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.action-card {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 1rem;
    border: 1px solid var(--gray-200);
    transition: var(--transition);
    text-align: center;
    cursor: pointer;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    border-color: var(--primary);
}

.action-card[data-permission="denied"]:hover {
    border-color: var(--gray-300);
    transform: none;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.action-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.ticket-icon { background: linear-gradient(135deg, var(--primary), #047857); }
.job-icon { background: linear-gradient(135deg, var(--success), #047857); }
.blog-icon { background: linear-gradient(135deg, var(--info), #0284C7); }
.reports-icon { background: linear-gradient(135deg, var(--warning), #D97706); }

.action-icon i { color: var(--white); font-size: 1rem; }

.action-content .action-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--gray-800);
}

.action-content .action-description {
    font-size: 0.75rem;
    color: var(--gray-600);
    margin-bottom: 0.75rem;
    line-height: 1.3;
}

.action-content .action-btn {
    width: auto;
    height: auto;
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 4px;
}

/* Empty State */
.empty-state { text-align: center; padding: 1.5rem; }

.empty-content i {
    font-size: 1.5rem;
    color: var(--gray-300);
    margin-bottom: 0.5rem;
}

.empty-content h6 {
    color: var(--gray-600);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.empty-content p {
    color: var(--gray-600);
    font-size: 0.8rem;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
    .header-actions { width: 100%; justify-content: space-between; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .content-grid { grid-template-columns: 1fr; }
    .actions-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 480px) {
    .stats-grid { grid-template-columns: 1fr; }
    .actions-grid { grid-template-columns: 1fr; }
}

/* Color utilities */
.text-success { color: var(--success); }
.text-warning { color: var(--warning); }
.text-danger { color: var(--danger); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshBtn = document.getElementById('refreshDashboard');
    const timeFilter = document.getElementById('timeFilter');
    
    // Handle refresh button
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Refreshing...';
            this.disabled = true;
            
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
                showToast('Dashboard refreshed successfully!', 'success');
                
                // Update last updated time
                const lastUpdated = document.querySelector('.period-indicator small');
                if (lastUpdated) {
                    lastUpdated.textContent = `Last updated: ${new Date().toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'})}`;
                }
            }, 1500);
        });
    }

    // Handle time filter change with immediate URL update
    if (timeFilter) {
        timeFilter.addEventListener('change', function() {
            const period = this.value;
            const url = new URL(window.location);
            url.searchParams.set('period', period);
            
            // Add loading state
            this.disabled = true;
            this.style.opacity = '0.7';
            
            // Navigate to new URL
            window.location.href = url.toString();
        });
    }

    // Handle permission-denied actions
    const actionCards = document.querySelectorAll('.action-card[data-permission="denied"]');
    actionCards.forEach(card => {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            const action = this.dataset.action;
            showToast(`Access denied! You don't have permission to ${action}.`, 'error');
        });
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas ${getToastIcon(type)} me-2"></i>
                <span>${message}</span>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 16px;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideIn 0.3s ease;
            background: ${getToastColor(type)};
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 4000);
    }

    function getToastIcon(type) {
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };
        return icons[type] || icons['info'];
    }

    function getToastColor(type) {
        const colors = {
            'success': 'linear-gradient(135deg, #059669, #047857)',
            'error': 'linear-gradient(135deg, #DC2626, #B91C1C)',
            'warning': 'linear-gradient(135deg, #F59E0B, #D97706)',
            'info': 'linear-gradient(135deg, #0EA5E9, #0284C7)'
        };
        return colors[type] || colors['info'];
    }
});

// Add slideIn animation styles
if (!document.getElementById('toastStyles')) {
    const style = document.createElement('style');
    style.id = 'toastStyles';
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .toast-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            opacity: 0.8;
        }
        .toast-close:hover {
            opacity: 1;
            background: rgba(255,255,255,0.1);
        }
    `;
    document.head.appendChild(style);
}
</script>
@endpush
@endsection
