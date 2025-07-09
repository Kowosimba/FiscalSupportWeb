@extends('layouts.tickets')

@section('ticket-content')
    {{-- Page Header --}}
    <div class="page-header mb-3">
        <div class="header-content">
            <h3 class="page-title">
                <i class="fa fa-ticket me-2"></i>
                All Tickets
            </h3>
            <p class="page-subtitle">Manage and track all tickets in the system</p>
        </div>
        <div class="header-stats">
            <div class="stat-pill">
                <i class="fa fa-database"></i>
                <span>{{ $tickets->total() ?? 0 }} Total</span>
            </div>
        </div>
    </div>

    {{-- Enhanced Filter Card --}}
    <div class="filter-card mb-3">
        <div class="filter-header">
            <h5 class="filter-title">
                <i class="fa fa-sliders-h me-2"></i>
                Filter & Search
            </h5>
        </div>
        <div class="filter-body">
            <form method="GET" action="{{ route('admin.tickets.all') }}" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <div class="select-wrapper">
                            <select name="status" id="status" class="enhanced-select">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fa fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="priority" class="form-label">Priority Level</label>
                        <div class="select-wrapper">
                            <select name="priority" id="priority" class="enhanced-select">
                                <option value="">All Priorities</option>
                                @foreach($priorities as $priority)
                                    <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                        {{ ucfirst($priority) }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fa fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="technician" class="form-label">Assigned Technician</label>
                        <div class="select-wrapper">
                            <select name="assigned_to" id="technician" class="enhanced-select">
                                <option value="">All Technicians</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ request('assigned_to') == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fa fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="search" class="form-label">Search Query</label>
                        <div class="search-wrapper">
                            <i class="fa fa-search search-icon"></i>
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   value="{{ request('search') }}" 
                                   class="enhanced-input" 
                                   placeholder="Search by subject, company, or email...">
                        </div>
                    </div>
                    
                    <div class="form-group form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-filter me-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.tickets.all') }}" class="btn btn-outline">
                            <i class="fa fa-times me-2"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Enhanced Tickets Table --}}
    <div class="tickets-table-card">
        <div class="table-header">
            <div class="table-title">
                <i class="fa fa-list me-2"></i>
                All Tickets List
            </div>
            @if($tickets->count() > 0)
                <div class="table-meta">
                    Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} results
                </div>
            @endif
        </div>
        
        <div class="table-container">
            <div class="table-responsive">
                <table class="enhanced-tickets-table">
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
                            <tr class="ticket-row">
                                <td>
                                    <span class="ticket-id-badge">#{{ $ticket->id }}</span>
                                </td>
                                <td>
                                    <div class="ticket-subject">
                                        {{ $ticket->subject }}
                                    </div>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <i class="fa fa-building me-1"></i>
                                        {{ $ticket->company_name }}
                                    </div>
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
                                        @if($ticket->status === 'open')
                                            <i class="fa fa-door-open me-1"></i>
                                        @elseif($ticket->status === 'resolved')
                                            <i class="fa fa-check-circle me-1"></i>
                                        @elseif($ticket->status === 'pending')
                                            <i class="fa fa-hourglass-half me-1"></i>
                                        @else
                                            <i class="fa fa-circle me-1"></i>
                                        @endif
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="technician-info">
                                        @if($ticket->assignedTechnician)
                                            <i class="fa fa-user me-1"></i>
                                            {{ $ticket->assignedTechnician->name }}
                                        @else
                                            <span class="unassigned-badge">
                                                <i class="fa fa-user-times me-1"></i>
                                                Unassigned
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="update-time">
                                        <i class="fa fa-clock me-1"></i>
                                        {{ $ticket->updated_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('tickets.show', $ticket->id) }}" 
                                           class="action-btn view-btn" 
                                           title="View Ticket Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-state">
                                    <div class="empty-content">
                                        <i class="fa fa-ticket"></i>
                                        <h4>No Tickets Found</h4>
                                        <p>There are no tickets matching your criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($tickets->hasPages())
            <div class="pagination-wrapper">
                {{ $tickets->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- The CSS from your pending tickets view is already included in the layout --}}
@endsection