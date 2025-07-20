@extends('layouts.calllogs')

@section('title', 'Call Logs Dashboard')

@section('content')
{{-- Dashboard Navigation Tabs --}}
<div class="dashboard-nav-wrapper mb-4">
    <ul class="panel-nav nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.index') || request()->routeIs('admin.tickets.*') ? 'active' : '' }}"
               href="{{ route('admin.index') }}">
                <i class="fa fa-tasks me-2"></i> Faults Allocation
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
               href="{{ route('admin.contacts.index') }}">
                <i class="fa fa-users me-2"></i> Customer Contacts
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.call-logs.*') ? 'active' : '' }}" 
               href="{{ route('admin.call-logs.index') }}">
                <i class="fa fa-phone me-2"></i> Call Logs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('content.*') || request()->routeIs('blogs.*') || request()->routeIs('admin.faqs.*') || request()->routeIs('admin.services.*') || request()->routeIs('admin.subscribers.*') || request()->routeIs('admin.newsletters.*') || request()->routeIs('faq-categories.*') ? 'active' : '' }}"
               href="{{ route('admin.content.index') }}">
                <i class="fa fa-cog me-2"></i> Manage Content
            </a>
        </li>
    </ul>
</div>

{{-- Info Boxes --}}
@php
$statuses = [
    'pending' => [
        'label' => 'Pending Jobs', 
        'icon' => 'fa-clock', 
        'bg' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)',
        'count' => $stats['pending_jobs'] ?? 0
    ],
    'in_progress' => [
        'label' => 'In Progress Jobs', 
        'icon' => 'fa-spinner', 
        'bg' => 'linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%)',
        'count' => $stats['in_progress_jobs'] ?? 0
    ],
    'completed' => [
        'label' => 'Completed Jobs', 
        'icon' => 'fa-check-circle', 
        'bg' => 'linear-gradient(135deg, var(--primary-green-dark) 0%, #0F3D0F 100%)',
        'count' => $stats['completed_jobs'] ?? 0
    ],
    'total' => [
        'label' => 'Total Jobs', 
        'icon' => 'fa-clipboard-list', 
        'bg' => 'linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%)',
        'count' => $stats['total_jobs'] ?? 0
    ],
];
@endphp

<div class="stats-grid mb-5">
    @foreach ($statuses as $key => $data)
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon" style="background: {{ $data['bg'] }};">
                    <i class="fa {{ $data['icon'] }}"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $data['count'] }}</h3>
                    <p class="stat-label">{{ $data['label'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Recent Jobs Table --}}
<div class="tickets-card">
    <div class="tickets-card-header">
        <div class="header-content">
            <h5 class="card-title">
                <i class="fa fa-history me-2"></i>
                Recent Job Cards
            </h5>
            <p class="card-subtitle">Latest service jobs and their current status</p>
        </div>
        <div class="header-actions">
            @if(in_array(auth()->user()->role, ['admin', 'accounts']))
                <a href="{{ route('admin.call-logs.create') }}" class="btn btn-success me-2">
                    <i class="fa fa-plus me-2"></i>
                    New Job
                </a>
            @endif
            <a href="{{ route('admin.call-logs.all') }}" class="btn btn-primary">
                <i class="fa fa-external-link-alt me-2"></i>
                View All
            </a>
        </div>
    </div>
    <div class="tickets-card-body">
        <div class="table-responsive">
            <table class="enhanced-table">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-customer">Customer</th>
                        <th class="col-description">Description</th>
                        <th class="col-status">Status</th>
                        <th class="col-technician">Technician</th>
                        <th class="col-amount">Amount</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($callLogs as $job)
                    <tr>
                        <td class="col-id">
                            <span class="ticket-id">#{{ $job->id }}</span>
                        </td>
                        <td class="col-customer">
                            <div class="customer-name">{{ $job->customer_name }}</div>
                            @if($job->customer_email)
                                <div class="customer-email text-truncate" title="{{ $job->customer_email }}">
                                    {{ $job->customer_email }}
                                </div>
                            @endif
                        </td>
                        <td class="col-description">
                            <div class="job-description text-truncate" title="{{ $job->fault_description }}">
                                {{ $job->fault_description }}
                            </div>
                        </td>
                        <td class="col-status">
                            @php
                                $statusMappings = [
                                    'pending' => ['class' => 'status-pending', 'icon' => 'fa-clock', 'label' => 'Pending'],
                                    'assigned' => ['class' => 'status-assigned', 'icon' => 'fa-user-check', 'label' => 'Assigned'],
                                    'in_progress' => ['class' => 'status-in_progress', 'icon' => 'fa-spinner', 'label' => 'In Progress'],
                                    'complete' => ['class' => 'status-resolved', 'icon' => 'fa-check', 'label' => 'Complete'],
                                    'cancelled' => ['class' => 'status-cancelled', 'icon' => 'fa-times', 'label' => 'Cancelled']
                                ];
                                $statusConfig = $statusMappings[$job->status] ?? ['class' => 'status-pending', 'icon' => 'fa-question', 'label' => ucfirst($job->status)];
                            @endphp
                            <span class="status-badge {{ $statusConfig['class'] }}">
                                <i class="fa {{ $statusConfig['icon'] }} me-1"></i>
                                {{ $statusConfig['label'] }}
                            </span>
                        </td>
                        <td class="col-technician">
                            <div class="technician-name text-truncate" title="{{ $job->assignedTo->name ?? 'Unassigned' }}">
                                {{ $job->assignedTo->name ?? 'Unassigned' }}
                            </div>
                        </td>
                        <td class="col-amount">
                            <div class="amount-charged">${{ number_format($job->amount_charged, 2) }}</div>
                        </td>
                        <td class="col-actions">
                            <a href="{{ route('admin.call-logs.show', $job) }}"
                               class="action-btn view-btn"
                               title="View Job">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <div class="empty-content">
                                    <i class="fa fa-clipboard-list"></i>
                                    <p>No job cards found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($callLogs->hasPages())
            <div class="pagination-wrapper">
                {{ $callLogs->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    :root {
        --primary-green: #055317;
        --primary-green-dark: #1e7e34;
        --light-green: #d4edda;
        --ultra-light-green: #f8f9fa;
        --secondary-green: #20c997;
        --accent-green: #17a2b8;
        --white: #ffffff;
        --dark-text: #212529;
        --medium-text: #495057;
        --light-text: #6c757d;
        --border-color: #dee2e6;
        --hover-bg: #f8f9fa;
        --shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --shadow-hover: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

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

    .header-actions {
        display: flex;
        gap: 0.5rem;
    }

    .tickets-card-header .btn {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        color: var(--white);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent-green) 0%, #114223 100%);
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

    .customer-name {
        font-weight: 500;
        color: var(--dark-text);
    }

    .customer-email {
        font-size: 0.8rem;
        color: var(--light-text);
        margin-top: 0.2rem;
    }

    .job-description {
        color: var(--medium-text);
        font-size: 0.9rem;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .technician-name {
        color: var(--medium-text);
        font-weight: 500;
    }

    .amount-charged {
        font-weight: 600;
        color: var(--primary-green);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-pending {
        background: #FFFBEB;
        color: #D97706;
        border: 1px solid #fde68a;
    }

    .status-assigned {
        background: #EFF6FF;
        color: #2563EB;
        border: 1px solid #DBEAFE;
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

    .status-cancelled {
        background: #FEF2F2;
        color: #DC2626;
        border: 1px solid #fecaca;
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

    .pagination-wrapper {
        padding: 1rem 2rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: center;
    }

    /* Table Responsive */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Column Widths */
    .col-id { width: 80px; }
    .col-customer { width: 180px; }
    .col-description { width: 220px; }
    .col-status { width: 140px; }
    .col-technician { width: 150px; }
    .col-amount { width: 100px; }
    .col-actions { width: 80px; }

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

        .header-actions {
            width: 100%;
            justify-content: flex-start;
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
@endsection