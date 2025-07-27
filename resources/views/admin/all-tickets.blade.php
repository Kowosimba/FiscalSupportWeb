@extends('layouts.app')

@section('title', 'All Tickets')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-ticket-alt me-2"></i>
                All Tickets
            </h1>
            <div class="header-meta">
                <span class="badge bg-secondary">{{ $tickets->total() ?? 0 }} total tickets</span>
                <small class="text-muted">Updated: {{ now()->format('g:i a') }}</small>
            </div>
        </div>
        <div class="header-actions">
            <button id="refreshTickets" class="btn btn-sm btn-secondary">
                <i class="fas fa-sync-alt me-1"></i>
                Refresh
            </button>
        </div>
    </div>

    {{-- Success & Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-2">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Compact Filter Card --}}
    <div class="filter-card mb-2">
        <form method="GET" action="{{ route('admin.tickets.all') }}" class="filter-form">
            <div class="filter-row">
                <div class="filter-group search-group">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" id="search"
                            value="{{ request('search') }}"
                            placeholder="Search by subject, company, or email..."
                            class="form-control form-control-sm">
                    </div>
                </div>
                
                <div class="filter-group">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected(request('status') == $status)>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <select name="priority" class="form-select form-select-sm">
                        <option value="">All Priorities</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority }}" @selected(request('priority') == $priority)>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <select name="assigned_to" class="form-select form-select-sm">
                        <option value="">All Technicians</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}" @selected(request('assigned_to') == $tech->id)>
                                {{ $tech->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-sm btn-secondary">
                        <i class="fas fa-filter me-1"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.tickets.all') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Tickets Table --}}
    <div class="content-card">
        <div class="content-card-header">
            <div class="header-content">
                <h4 class="card-title">
                    <i class="fas fa-list me-2"></i>
                    All Tickets Overview
                </h4>
                @if($tickets->count() > 0)
                <p class="card-subtitle mb-0">
                    Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} tickets
                </p>
                @endif
            </div>
            <div class="header-actions">
                <div class="d-flex gap-2">
                    <span class="badge high-priority">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        {{ $tickets->where('priority', 'high')->count() }} High
                    </span>
                    <span class="badge unassigned-count">
                        <i class="fas fa-user-times me-1"></i>
                        {{ $tickets->whereNull('assigned_to')->count() }} Unassigned
                    </span>
                </div>
            </div>
        </div>
        
        <div class="content-card-body">
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Technician</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                        <tr class="{{ $ticket->priority === 'high' ? 'high-priority-row' : '' }}">
                            <td>
                                <span class="ticket-id">#{{ $ticket->id }}</span>
                            </td>
                            <td>
                                <span class="ticket-subject" onclick="window.location.href='{{ route('admin.tickets.show', $ticket->id) }}'"
                                      title="{{ $ticket->subject }}">
                                    {{ Str::limit($ticket->subject, 40) }}
                                </span>
                            </td>
                            <td>
                                <div class="customer-info">
                                    <div class="customer-name">{{ Str::limit($ticket->company_name, 20) }}</div>
                                    @if($ticket->customer_email)
                                        <small class="text-muted">{{ Str::limit($ticket->customer_email, 20) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $priorityConfig = [
                                        'high' => ['class' => 'priority-high', 'icon' => 'fa-exclamation-triangle'],
                                        'medium' => ['class' => 'priority-medium', 'icon' => 'fa-minus-circle'],
                                        'low' => ['class' => 'priority-low', 'icon' => 'fa-circle'],
                                    ];
                                    $config = $priorityConfig[$ticket->priority ?? 'low'] ?? $priorityConfig['low'];
                                @endphp
                                <span class="status-badge {{ $config['class'] }}">
                                    <i class="fas {{ $config['icon'] }} me-1"></i>
                                    {{ ucfirst($ticket->priority ?? 'low') }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusConfig = [
                                        'open' => ['class' => 'status-open', 'icon' => 'fa-door-open'],
                                        'resolved' => ['class' => 'status-resolved', 'icon' => 'fa-check-circle'],
                                        'pending' => ['class' => 'status-pending', 'icon' => 'fa-hourglass-half'],
                                        'in_progress' => ['class' => 'status-in-progress', 'icon' => 'fa-cog'],
                                        'closed' => ['class' => 'status-closed', 'icon' => 'fa-times-circle'],
                                    ];
                                    $sConfig = $statusConfig[$ticket->status] ?? $statusConfig['open'];
                                @endphp
                                <span class="status-badge {{ $sConfig['class'] }}">
                                    <i class="fas {{ $sConfig['icon'] }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="technician-info">
                                    @if($ticket->assignedTechnician)
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2" style="width: 24px; height: 24px; font-size: 0.7rem;">
                                                {{ substr($ticket->assignedTechnician->name, 0, 1) }}
                                            </div>
                                            <span>{{ Str::limit($ticket->assignedTechnician->name, 12) }}</span>
                                        </div>
                                    @else
                                        <span class="unassigned-badge">
                                            <i class="fas fa-user-times me-1"></i>
                                            Unassigned
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="update-time">
                                    <div class="date-main">{{ $ticket->updated_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $ticket->updated_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="window.location.href='{{ route('admin.tickets.show', $ticket->id) }}'" 
                                       class="action-btn view-btn" 
                                       title="View Ticket Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-ticket-alt"></i>
                                    <h6>No Tickets Found</h6>
                                    <p>No tickets match your search criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($tickets->hasPages())
            <div class="pagination-wrapper">
                {{ $tickets->withQueryString()->onEachSide(1)->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Updated CSS Variables for All Tickets */
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

/* Compact Dashboard Styles */
.dashboard-header {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.5rem 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
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
    font-size: 0.7rem;
    color: var(--gray-500);
}

.header-actions {
    display: flex;
    gap: 0.5rem;
}

.header-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
}

.btn-secondary {
    background: var(--secondary);
    border-color: var(--secondary);
    color: white;
}

.btn-secondary:hover {
    background: var(--secondary-dark);
    border-color: var(--secondary-dark);
    color: white;
}

/* Alert Styles */
.alert {
    border-radius: var(--border-radius);
    padding: 0.75rem 1rem;
    font-size: 0.85rem;
}

.alert-success {
    background: #F0FDF4;
    color: #166534;
    border: 1px solid #BBF7D0;
}

.alert-danger {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

/* Compact Filter Card */
.filter-card {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 0.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    margin-bottom: 1rem;
}

.filter-form .filter-row {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 140px;
}

.search-group {
    flex: 2;
    min-width: 200px;
}

.search-input-wrapper {
    position: relative;
}

.search-input-wrapper i {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-500);
    font-size: 0.85rem;
}

.search-input-wrapper .form-control {
    padding-left: 2.25rem;
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.filter-actions .btn-secondary {
    background: var(--secondary);
    border-color: var(--secondary);
}

.filter-actions .btn-secondary:hover {
    background: var(--secondary-dark);
    border-color: var(--secondary-dark);
}

.form-select-sm, .form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
    height: 32px;
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
}

.form-select-sm:focus, .form-control-sm:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.1);
}

/* Content Card */
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

/* Header Action Badges */
.high-priority {
    background: #FEF2F2 !important;
    color: #DC2626 !important;
    border: 1px solid #FECACA;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

.unassigned-count {
    background: #FEF3C7 !important;
    color: #92400E !important;
    border: 1px solid #FDE68A;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

/* Compact Table Styles */
.compact-table {
    width: 100%;
    font-size: 0.85rem;
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
}

.compact-table td {
    padding: 0.5rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--gray-100);
}

.compact-table tr:last-child td {
    border-bottom: none;
}

/* High Priority Row Highlighting */
.high-priority-row {
    background: #FEF2F2 !important;
    border-left: 3px solid #DC2626;
}

.high-priority-row:hover {
    background: #FEE2E2 !important;
}

.ticket-id {
    font-family: 'Monaco', 'Menlo', monospace;
    background: var(--secondary);
    color: var(--white);
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* No Underline Subject - Clickable but looks like text */
.ticket-subject {
    color: var(--gray-800);
    font-weight: 500;
    font-size: 0.85rem;
    transition: var(--transition);
    cursor: pointer;
    text-decoration: none !important;
    border: none;
    background: none;
    padding: 0;
    outline: none;
    display: inline;
}

.ticket-subject:hover {
    color: var(--secondary);
    text-decoration: none !important;
}

.ticket-subject:focus {
    color: var(--secondary);
    text-decoration: none !important;
    outline: none;
}

/* Customer Info */
.customer-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.customer-name {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.85rem;
}

/* Technician Info */
.technician-info {
    font-size: 0.8rem;
}

.technician-info .d-flex {
    align-items: center;
}

.user-avatar {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 0.7rem;
    font-weight: 600;
    box-shadow: var(--shadow-sm);
}

.unassigned-badge {
    display: inline-flex;
    align-items: center;
    color: #DC2626;
    background: #FEF2F2;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    border: 1px solid #FECACA;
}

/* Update Time */
.update-time {
    font-size: 0.8rem;
    color: var(--gray-600);
}

.date-main {
    font-weight: 500;
    color: var(--gray-800);
}

.update-time small {
    display: block;
    margin-top: 0.15rem;
}

/* Badge Styles - Tickets Theme */
.priority-badge, .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}

.priority-high {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

.priority-medium {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
}

.priority-low {
    background: #F0FDF4;
    color: #059669;
    border: 1px solid #BBF7D0;
}

/* Status badges - no blue colors */
.status-open {
    background: #F0FDF4;
    color: #047857;
    border: 1px solid #BBF7D0;
}

.status-pending {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
}

.status-resolved {
    background: #DCFCE7;
    color: #166534;
    border: 1px solid #BBF7D0;
}

.status-in-progress {
    background: #FEF3C7;
    color: #92400E;
    border: 1px solid #FDE68A;
}

.status-closed {
    background: #F3F4F6;
    color: #4B5563;
    border: 1px solid #D1D5DB;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    align-items: center;
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
}

/* Empty State */
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

/* Pagination */
.pagination-wrapper {
    padding: 0.75rem;
    border-top: 1px solid var(--gray-200);
}

/* Override Bootstrap pagination colors */
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

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.5rem;
    }
    
    .header-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .filter-row {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    
    .filter-group, .search-group {
        min-width: 100%;
    }
    
    .filter-actions {
        justify-content: flex-end;
    }
    
    .compact-table {
        font-size: 0.8rem;
    }
    
    .compact-table th,
    .compact-table td {
        padding: 0.4rem 0.5rem;
    }
    
    .content-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .content-card-header .header-actions {
        width: 100%;
        justify-content: flex-start;
    }
}

@media (max-width: 480px) {
    .customer-info,
    .technician-info,
    .update-time {
        font-size: 0.75rem;
    }
    
    .priority-badge,
    .status-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.15rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Refresh button functionality
    const refreshBtn = document.getElementById('refreshTickets');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Refreshing';
            this.disabled = true;
            
            setTimeout(() => {
                window.location.reload();
            }, 800);
        });
    }
    
    // Auto-submit on filter change
    const filterSelects = document.querySelectorAll('select[name="status"], select[name="priority"], select[name="assigned_to"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
    
    // Toast notification function
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.setAttribute('role', 'alert');
        
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };
        
        const colors = {
            'success': 'linear-gradient(135deg, #059669, #047857)',
            'error': 'linear-gradient(135deg, #DC2626, #B91C1C)',
            'warning': 'linear-gradient(135deg, #F59E0B, #D97706)',
            'info': 'linear-gradient(135deg, #6B7280, #4B5563)'
        };
        
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas ${icons[type] || icons['info']} me-1"></i>
                ${message}
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
            background: ${colors[type] || colors['info']};
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 4000);
    }
    
    // Make functions globally available
    window.showToast = showToast;
});

// Add animation keyframes
const style = document.createElement('style');
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
        transition: all 0.2s ease;
    }
    
    .toast-close:hover {
        opacity: 1;
        background: rgba(255,255,255,0.1);
    }
`;
document.head.appendChild(style);
</script>
@endpush

