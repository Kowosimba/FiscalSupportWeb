@extends('layouts.tickets')

@section('ticket-content')
    {{-- Dashboard Navigation Tabs --}}
<ul class="panel-nav nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" 
           href="{{ route('admin.index') }}">
           Faults Allocation
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('content.index') ? 'active' : '' }}" 
           href="{{ route('content.index') }}">
           Manage Content
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Call Logs</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Customer Contacts</a>
    </li>
</ul>

    {{-- Info Boxes --}}
    <div class="info-box-container">
        <div class="info-box">
            <div class="info-box-icon" style="color: #3B82F6; background: rgba(59,130,246,0.08);">
                <i class="fa fa-clipboard"></i>
            </div>
            <div class="info-box-content">
                <span class="info-box-text">In Progress Tickets</span>
                <span class="info-box-number">{{ $statusCounts->in_progress ?? 0 }}</span>
                <div class="info-box-footer text-success">
                    <i class="fa fa-arrow-up"></i> 12% this week
                </div>
            </div>
        </div>
        <div class="info-box">
            <div class="info-box-icon" style="color: #10B981; background: rgba(16,185,129,0.08);">
                <i class="fa fa-check-circle"></i>
            </div>
            <div class="info-box-content">
                <span class="info-box-text">Solved Tickets</span>
                <span class="info-box-number">{{ $statusCounts->resolved ?? 0 }}</span>
                <div class="info-box-footer text-success">
                    <i class="fa fa-arrow-up"></i> 5% this week
                </div>
            </div>
        </div>
        <div class="info-box">
            <div class="info-box-icon" style="color: #F59E0B; background: rgba(245,158,11,0.08);">
                <i class="fa fa-clock"></i>
            </div>
            <div class="info-box-content">
                <span class="info-box-text">Pending Tickets</span>
                <span class="info-box-number">{{ $statusCounts->pending ?? 0 }}</span>
                <div class="info-box-footer text-danger">
                    <i class="fa fa-arrow-down"></i> 3% this week
                </div>
            </div>
        </div>
        <div class="info-box">
            <div class="info-box-icon" style="color: #8B5CF6; background: rgba(139,92,246,0.08);">
                <i class="fa fa-list"></i>
            </div>
            <div class="info-box-content">
                <span class="info-box-text">Unassigned</span>
                <span class="info-box-number">{{ $statusCounts->unassigned ?? 0 }}</span>
                <div class="info-box-footer text-success">
                    <i class="fa fa-arrow-up"></i> 2% this week
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Tickets Table --}}
    <div class="card" style="box-shadow: var(--shadow); border-radius: 0.5rem; overflow: hidden;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: #fff;">
            <h5 class="mb-0">Recent Tickets</h5>
            <a href="{{ route('admin.tickets.all')}}" class="btn btn-primary btn-sm">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 table-hover">
                    <thead style="background: #F3F4F6;">
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
                            <span class="badge
                                @if($ticket->status === 'in_progress') bg-primary
                                @elseif($ticket->status === 'resolved') bg-success
                                @elseif($ticket->status === 'pending') bg-warning text-dark
                                @else bg-secondary @endif">
                                {{ $ticket->status === 'in_progress' ? 'In Progress' : ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-outline-primary btn-sm" title="View"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
@endsection
