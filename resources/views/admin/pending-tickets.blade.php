@extends('layouts.app')

@section('title', 'Pending Tickets')

@section('content')
<div class="dashboard-container">
    {{-- Compact Header --}}
    <div class="dashboard-header mb-2">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-hourglass-half me-2"></i>
                Pending Tickets
            </h1>
            <div class="header-meta">
                <span class="badge bg-warning">{{ $tickets->total() ?? 0 }} pending</span>
                <small class="text-muted">Updated: {{ now()->format('g:i a') }}</small>
            </div>
        </div>
        <div class="header-actions">
            <button id="refreshTickets" class="btn btn-sm btn-warning">
                <i class="fas fa-sync-alt me-1"></i>
                Refresh
            </button>
        </div>
    </div>

    {{-- Compact Filter Card --}}
    <div class="filter-card mb-2">
        <form method="GET" action="{{ route('admin.tickets.pending') }}" class="filter-form">
            <div class="filter-row">
                <div class="filter-group">
                    <select name="priority" id="priority" class="form-select form-select-sm">
                        <option value="">All Priorities</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority }}" @selected(request('priority') == $priority)>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <select name="technician" id="technician" class="form-select form-select-sm">
                        <option value="">All Technicians</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->name }}" @selected(request('technician') == $tech->name)>
                                {{ $tech->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group search-group">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" id="search"
                            value="{{ request('search') }}"
                            placeholder="Search tickets..."
                            class="form-control form-control-sm">
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fas fa-filter me-1"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.tickets.pending') }}" class="btn btn-sm btn-outline-secondary">
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
                    Pending Tickets List
                </h4>
                @if($tickets->count() > 0)
                <p class="card-subtitle mb-0">
                    Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} tickets
                </p>
                @endif
            </div>
        </div>
        
        <div class="content-card-body">
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>ID</th>
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
                        <tr>
                            <td>
                                <span class="ticket-id">#{{ $ticket->id }}</span>
                            </td>
                            <td>
                                <span class="ticket-subject" onclick="window.location.href='{{ route('admin.tickets.show', $ticket->id) }}'">
                                    {{ Str::limit($ticket->subject, 35) }}
                                </span>
                            </td>
                            <td>
                                <div class="customer-info">
                                    <i class="fas fa-building me-1 text-muted"></i>
                                    <span>{{ Str::limit($ticket->company_name ?? 'No Company', 20) }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $priorityClasses = [
                                        'high' => 'priority-high',
                                        'medium' => 'priority-medium', 
                                        'low' => 'priority-low'
                                    ];
                                    $priorityIcons = [
                                        'high' => 'fa-exclamation-triangle',
                                        'medium' => 'fa-minus-circle',
                                        'low' => 'fa-circle'
                                    ];
                                    $pri = $ticket->priority ?? 'low';
                                    $priorityClass = $priorityClasses[$pri];
                                    $priorityIcon = $priorityIcons[$pri];
                                @endphp
                                <span class="priority-badge {{ $priorityClass }}">
                                    <i class="fas {{ $priorityIcon }} me-1"></i>
                                    {{ ucfirst($pri) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-pending">
                                    <i class="fas fa-hourglass-half me-1"></i>
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="technician-info">
                                    @if($ticket->assignedTo)
                                        <div class="assigned-tech">
                                            <i class="fas fa-user-check me-1 text-success"></i>
                                            <span>{{ $ticket->assignedTo->name }}</span>
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
                                    <i class="fas fa-clock me-1 text-muted"></i>
                                    <time datetime="{{ $ticket->updated_at->toIso8601String() }}" 
                                          title="{{ $ticket->updated_at->format('M j, Y g:i a') }}">
                                        {{ $ticket->updated_at->diffForHumans() }}
                                    </time>
                                </div>
                            </td>
                            <td>
                                <button onclick="window.location.href='{{ route('admin.tickets.show', $ticket->id) }}'"
                                   class="action-btn view-btn"
                                   title="View Ticket Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-hourglass-half"></i>
                                    <h6>No Pending Tickets</h6>
                                    <p>There are no pending tickets matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($tickets->hasPages())
            <div class="pagination-wrapper">
                {{ $tickets->onEachSide(1)->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Updated CSS Variables */
:root {
    --primary: #059669;
    --primary-dark: #047857;
    --success: #059669;
    --warning: #F59E0B;
    --danger: #DC2626;
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
    background: var(--success);
    border-radius: 4px;
}

.header-meta small {
    font-size: 0.7rem;
    color: var(--gray-500);
}

.header-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    height: 28px;
    background: var(--success);
    border-color: var(--success);
}

.header-actions .btn-sm:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
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

.filter-actions .btn-success {
    background: var(--success);
    border-color: var(--success);
}

.filter-actions .btn-success:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
}

.form-select-sm, .form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
    height: 32px;
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
}

.form-select-sm:focus, .form-control-sm:focus {
    border-color: var(--success);
    box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.1);
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

.ticket-id {
    font-family: 'Monaco', 'Menlo', monospace;
    background: var(--success);
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
    color: var(--success);
    text-decoration: none !important;
}

.ticket-subject:focus {
    color: var(--success);
    text-decoration: none !important;
    outline: none;
}

/* Customer Info */
.customer-info {
    display: flex;
    align-items: center;
    font-size: 0.8rem;
    color: var(--gray-600);
}

.customer-info span {
    color: var(--gray-800);
    font-weight: 500;
}

/* Technician Info */
.technician-info {
    font-size: 0.8rem;
}

.assigned-tech {
    display: flex;
    align-items: center;
    color: var(--gray-800);
    font-weight: 500;
}

.unassigned-badge {
    display: inline-flex;
    align-items: center;
    color: var(--gray-500);
    font-style: italic;
}

/* Update Time */
.update-time {
    display: flex;
    align-items: center;
    font-size: 0.8rem;
    color: var(--gray-600);
}

/* Badge Styles - No Blue Colors */
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

/* Status badge - replaced blue with green */
.status-open {
    background: #F0FDF4;
    color: #047857;
    border: 1px solid #BBF7D0;
}

/* Action Buttons */
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
    background: #F0FDF4;
    color: #059669;
    border: 1px solid #BBF7D0;
}

.view-btn:hover {
    background: #059669;
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
    color: var(--success);
    border-color: var(--gray-300);
}

.pagination .page-link:hover {
    color: var(--primary-dark);
    background-color: var(--gray-50);
    border-color: var(--gray-300);
}

.pagination .page-item.active .page-link {
    background-color: var(--success);
    border-color: var(--success);
    color: white;
}

/* Remove any remaining blue colors */
a, .btn-primary {
    color: var(--success) !important;
}

.btn-primary {
    background-color: var(--success) !important;
    border-color: var(--success) !important;
}

.btn-primary:hover {
    background-color: var(--primary-dark) !important;
    border-color: var(--primary-dark) !important;
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
    const filterSelects = document.querySelectorAll('#priority, #technician');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
    
    // Toast notification function
    function showToast(message, type = 'info') {
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
            'info': 'linear-gradient(135deg, #F59E0B, #D97706)' // Changed from blue to orange
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
});
</script>
@endpush
