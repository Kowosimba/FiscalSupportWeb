@extends('layouts.tickets')

@section('ticket-content')
<div class="container">
    <h3 class="mb-4">Tickets Assigned to Me</h3>
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tickets as $ticket)
                <tr>
                    <td>#{{ $ticket->id }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>
                        <span class="badge-priority badge-{{ $ticket->priority }}">
                            <i class="fas fa-flag"></i>
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-custom badge-{{ str_replace([' ', '_'], '-', strtolower($ticket->status)) }}">
                            <span class="status-dot dot-{{ str_replace([' ', '_'], '-', strtolower($ticket->status)) }}"></span>
                            {{ ucfirst($ticket->status) }}
                        </span>
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
                    <td colspan="6" class="text-center text-muted">No tickets assigned to you.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $tickets->links() }}
</div>
@endsection
