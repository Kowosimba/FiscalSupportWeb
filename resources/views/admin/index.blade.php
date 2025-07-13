@extends('layouts.tickets')

@section('ticket-content')
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


    {{-- Info Boxes --}}
    @php
    $statuses = [
        'in_progress' => ['label' => 'In Progress Tickets', 'icon' => 'fa-clipboard-list', 'color' => 'var(--primary-green)', 'bg' => 'linear-gradient(135deg, var(--primary-green) 0%, var(--primary-green-dark) 100%)'],
        'resolved' => ['label' => 'Solved Tickets', 'icon' => 'fa-check-circle', 'color' => 'var(--primary-green-dark)', 'bg' => 'linear-gradient(135deg, var(--primary-green-dark) 0%, #0F3D0F 100%)'],
        'pending' => ['label' => 'Pending Tickets', 'icon' => 'fa-clock', 'color' => '#F59E0B', 'bg' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)'],
        'unassigned' => ['label' => 'Unassigned', 'icon' => 'fa-list-ul', 'color' => '#8B5CF6', 'bg' => 'linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%)'],
    ];
    @endphp

    <div class="stats-grid mb-5">
        @foreach ($statuses as $key => $data)
            @php
                $count = $statusCounts->$key ?? 0;
                $change = $percentageChanges[$key] ?? 0;
                $isPositive = $change >= 0;
                $arrowClass = $isPositive ? 'fa-arrow-up' : 'fa-arrow-down';
                $textClass = $isPositive ? 'text-success' : 'text-danger';
            @endphp
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="stat-icon" style="background: {{ $data['bg'] }};">
                        <i class="fa {{ $data['icon'] }}"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $count }}</h3>
                        <p class="stat-label">{{ $data['label'] }}</p>
                        <div class="stat-change {{ $textClass }}">
                            <i class="fa {{ $arrowClass }}"></i>
                            <span>{{ abs($change) }}% this week</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Recent Tickets Table --}}
    <div class="tickets-card">
        <div class="tickets-card-header">
            <div class="header-content">
                <h5 class="card-title">
                    <i class="fa fa-ticket-alt me-2"></i>
                    Recent Tickets
                </h5>
                <p class="card-subtitle">Latest support requests and their status</p>
            </div>
            <a href="{{ route('admin.tickets.all')}}" class="btn btn-primary">
                <i class="fa fa-external-link-alt me-2"></i>
                View All
            </a>
        </div>
        <div class="tickets-card-body">
            <div class="table-responsive">
                <table class="enhanced-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Last Update</th>
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
                                <div class="ticket-subject">{{ $ticket->subject }}</div>
                            </td>
                            <td>
                                <div class="customer-name">{{ $ticket->company_name }}</div>
                            </td>
                            <td>
                                <span class="priority-badge priority-{{ $ticket->priority ?? 'low' }}">
                                    @if($ticket->priority === 'high')
                                        <i class="fa fa-exclamation-triangle me-1"></i>
                                    @elseif($ticket->priority === 'medium')
                                        <i class="fa fa-minus-circle me-1"></i>
                                    @else
                                        <i class="fa fa-circle me-1"></i>
                                    @endif
                                    {{ ucfirst($ticket->priority ?? 'low') }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $ticket->status }}">
                                    @if($ticket->status === 'in_progress')
                                        <i class="fa fa-spinner me-1"></i>
                                        In Progress
                                    @elseif($ticket->status === 'resolved')
                                        <i class="fa fa-check me-1"></i>
                                        Resolved
                                    @elseif($ticket->status === 'pending')
                                        <i class="fa fa-clock me-1"></i>
                                        Pending
                                    @else
                                        <i class="fa fa-question me-1"></i>
                                        {{ ucfirst($ticket->status) }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="update-time">{{ $ticket->updated_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}"
                                   class="action-btn view-btn"
                                   title="View Ticket">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <div class="empty-content">
                                        <i class="fa fa-inbox"></i>
                                        <p>No tickets found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
@endsection