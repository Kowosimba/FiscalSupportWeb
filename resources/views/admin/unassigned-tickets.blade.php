@extends('layouts.tickets')

@section('ticket-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Unassigned Tickets</h3>
    <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Create New Ticket
    </a>
</div>

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

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.tickets.unassigned') }}" class="filter-bar">
            <div class="filter-group">
                <label for="priority">Priority:</label>
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
                <label for="status">Status:</label>
                <select name="status" id="status" class="filter-select">
                    <option value="">All</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group flex-grow-1">
                <label for="search">Search:</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="search-input" placeholder="Subject, company, email...">
            </div>

            <div class="filter-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('admin.tickets.unassigned') }}" class="btn btn-outline-secondary">
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
                        <th>Created</th>
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
                            <td>{{ $ticket->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="d-flex gap-2 align-items-center">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                   @can('assign tickets')
                                <button type="button" class="btn btn-sm btn-outline-success"
                                    data-bs-toggle="modal" data-bs-target="#assignModal{{ $ticket->id }}"
                                    title="Assign Technician">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                                <!-- Assignment Modal -->
                                <div class="modal fade" id="assignModal{{ $ticket->id }}" tabindex="-1" 
                                     aria-labelledby="assignModalLabel{{ $ticket->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('tickets.assign', $ticket->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="assignModalLabel{{ $ticket->id }}">Assign Ticket #{{ $ticket->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <select name="assigned_to" class="form-select" required>
                                                            <option value="" selected disabled>Select technician...</option>
                                                            @foreach($technicians as $tech)
                                                                <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                                            @endforeach
                                                        </select>
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

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No unassigned tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-center">
            {{ $tickets->withQueryString()->links() }}
        </div>
    </div>
</div>

<style>
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
    .filter-select, .search-input {
        padding: 0.5rem 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }
    @media (max-width: 768px) {
        .filter-bar {
            flex-direction: column;
        }
        .filter-group {
            width: 100%;
        }
    }
    
    /* Modal styles - fixed */
    .modal-dialog {
        margin: 1.75rem auto;
    }
    
    .modal-content {
        border-radius: 0.3rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    /* Ensure modal is properly centered and not oversized */
    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 3.5rem);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Debug form submission
        document.querySelectorAll('[id^="assignModal"] form').forEach(form => {
            form.addEventListener('submit', function(e) {
                console.log('Form submitted:', this.action);
                // You can add more debug info here
            });
        });

        // Show modal if errors exist for any ticket
        @if($errors->has('assigned_to'))
            @foreach($tickets as $ticket)
                @if($errors->any())
                    console.log('Showing modal for ticket {{ $ticket->id }} due to errors');
                    var assignModal = new bootstrap.Modal(
                        document.getElementById('assignModal{{ $ticket->id }}')
                    );
                    assignModal.show();
                @endif
            @endforeach
        @endif
    });
</script>
@endsection