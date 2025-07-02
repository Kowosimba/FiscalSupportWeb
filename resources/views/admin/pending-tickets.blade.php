@extends('layouts.tickets')

@section('ticket-content')
<h3>Pending Tickets</h3>

<div class="card mb-4">
    <div class="card-body pb-0">
        <form method="GET" action="{{ route('admin.tickets.pending') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="priority" class="form-label">Priority</label>
                <select name="priority" id="priority" class="filter-select">
                    <option value="">All</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                            {{ ucfirst($priority) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="technician" class="form-label">Technician</label>
                <select name="technician" id="technician" class="form-select">
                    <option value="">All</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->name }}" {{ request('technician') == $tech->name ? 'selected' : '' }}>
                            {{ $tech->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Subject, company, email...">
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('admin.tickets.pending') }}" class="btn btn-outline-secondary ms-2">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

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
                            <td>{{ $ticket->subject }}</td>
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
                                <span class="badge status-pending">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $ticket->assignedTechnician->name ?? 'Unassigned' }}
                            </td>
                            <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-outline-primary btn-sm" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No pending tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection
