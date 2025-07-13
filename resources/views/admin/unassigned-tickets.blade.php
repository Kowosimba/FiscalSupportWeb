@extends('layouts.tickets')

@section('ticket-content')
    {{-- Page Header --}}
    <div class="page-header mb-3">
        <div class="header-content">
            <h3 class="page-title">
                <i class="fa fa-user-times me-2"></i>
                Unassigned Tickets
            </h3>
            <p class="page-subtitle">Tickets that have not yet been assigned to a technician</p>
        </div>
        <div class="header-stats">
            <div class="stat-pill">
                <i class="fa fa-ticket-alt"></i>
                <span>{{ $tickets->total() ?? 0 }} Total</span>
            </div>
            <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary ms-3">
                <i class="fas fa-plus me-2"></i> Create New Ticket
            </a>
        </div>
    </div>

    {{-- Success & Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Enhanced Filter Card --}}
    <div class="filter-card mb-3">
        <div class="filter-header">
            <h5 class="filter-title">
                <i class="fa fa-sliders-h me-2"></i>
                Filter & Search
            </h5>
        </div>
        <div class="filter-body">
            <form method="GET" action="{{ route('admin.tickets.unassigned') }}" class="filter-form">
                <div class="form-row">
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
                        <a href="{{ route('admin.tickets.unassigned') }}" class="btn btn-outline">
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
                Unassigned Tickets List
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
                            <th>Created</th>
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
                                        {{ Str::limit($ticket->subject, 30) }}
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
                                            <i class="fa fa-folder-open me-1"></i>
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
                                    <span class="update-time">
                                        <i class="fa fa-clock me-1"></i>
                                        {{ $ticket->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}"
                                           class="action-btn view-btn"
                                           title="View Ticket Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @can('assign tickets')
                                            <button type="button" class="action-btn assign-btn"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#assignModal{{ $ticket->id }}"
                                                title="Assign Technician">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <div class="empty-content">
                                        <i class="fa fa-user-times"></i>
                                        <h4>No Unassigned Tickets</h4>
                                        <p>There are no unassigned tickets matching your criteria.</p>
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

    <!-- Assignment Modals -->
    @foreach($tickets as $ticket)
        @can('assign tickets')
            <div class="modal fade" id="assignModal{{ $ticket->id }}" tabindex="-1" 
                 aria-labelledby="assignModalLabel{{ $ticket->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignModalLabel{{ $ticket->id }}">
                                Assign Ticket #{{ $ticket->id }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.tickets.assign', $ticket->id) }}"
>
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="assigned_to{{ $ticket->id }}" class="form-label">Select Technician</label>
                                    <select name="assigned_to" id="assigned_to{{ $ticket->id }}" class="form-select" required>
                                        <option value="" selected disabled>Select technician...</option>
                                        @foreach($technicians as $tech)
                                            <option value="{{ $tech->id }}" {{ old('assigned_to') == $tech->id ? 'selected' : '' }}>
                                                {{ $tech->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Assign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    @endforeach

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Show modal if there are validation errors
        @if($errors->has('assigned_to') && session('assign_ticket_id'))
            var assignModal = new bootstrap.Modal(
                document.getElementById('assignModal{{ session('assign_ticket_id') }}')
            );
            assignModal.show();
        @endif
    });
    </script>

    <style>
        /* Additional styles for assign button */
        .assign-btn {
            background: #d1fae5;
            color: #047857;
            border: none;
            margin-left: 5px;
        }
        
        .assign-btn:hover {
            background: #a7f3d0;
            color: #065f46;
        }
        
        /* Modal styling */
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            border-radius: 10px 10px 0 0;
        }
        
        .modal-title {
            color: #065f46;
            font-weight: 600;
        }
    </style>
@endsection