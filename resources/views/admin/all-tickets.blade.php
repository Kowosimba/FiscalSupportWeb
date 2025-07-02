@extends('layouts.tickets')

@section('ticket-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">All Tickets</h3>
</div>

<!-- Filters Card -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.tickets.all') }}" class="filter-bar">
            <div class="filter-group">
                <label for="status" class="form-label">Status:</label>
                <select name="status" id="status" class="filter-select">
                    <option value="">All</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="priority" class="form-label">Priority:</label>
                <select name="priority" id="priority" class="filter-select">
                    <option value="">All</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                            {{ ucfirst($priority) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="technician" class="form-label">Technician:</label>
                <select name="assigned_to" id="technician" class="form-select">
                    <option value="">All</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" {{ request('assigned_to') == $tech->id ? 'selected' : '' }}>
                            {{ $tech->name }}
                        </option>
                    @endforeach
                </select>


            </div>

            <div class="filter-group flex-grow-1">
                <label for="search" class="form-label">Search:</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="search-input" placeholder="Subject, company, email...">
            </div>

            <div class="filter-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('admin.tickets.all') }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tickets Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Customer</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Technician</th>
                        <th>Last Update</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td>{{ Str::limit($ticket->subject, 30) }}</td>
                            <td>{{ $ticket->company_name }}</td>
                            <td>
                                <span class="badge 
                                    @if($ticket->priority === 'high') bg-danger
                                    @elseif($ticket->priority === 'medium') bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ ucfirst($ticket->priority ?? 'low') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($ticket->status === 'open') bg-primary
                                    @elseif($ticket->status === 'resolved') bg-success
                                    @elseif($ticket->status === 'pending') bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td>
                                {{ $ticket->assignedTechnician->name ?? 'Unassigned' }}
                            </td>
                            <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $tickets->withQueryString()->links() }}
        </div>
    </div>
</div>

<style>
    /* Filter Bar Styles */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 150px;
    }

    .filter-group label {
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
        color: #495057;
    }

    .filter-select, .search-input {
        padding: 0.5rem 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .search-input {
        min-width: 200px;
        width: 100%;
    }

    /* Badge Styles */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group {
            width: 100%;
        }
    }

    /* Pagination Styles */
    .pagination {
        margin-bottom: 0;
    }

    .page-item.active .page-link {
        background-color: #044207;
        border-color: #00280e;
    }

    .page-link {
        color: #010502dc;
    }
</style>

@endsection