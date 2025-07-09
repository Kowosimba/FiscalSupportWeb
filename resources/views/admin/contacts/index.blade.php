@extends('layouts.tickets')

@section('ticket-content')
<div class="dashboard-nav-wrapper mb-4">
    <ul class="panel-nav nav nav-tabs">
        {{-- ...other nav items... --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
               href="{{ route('admin.contacts.index') }}">
                <i class="fa fa-users me-2"></i> Customer Contacts
            </a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <div class="page-header-card mb-4">
        <div class="page-header-content d-flex justify-content-between align-items-center">
            <h3 class="page-title">
                <i class="fa fa-address-book me-2"></i> Customer Contacts
            </h3>
            <a href="{{ route('admin.contacts.create') }}" class="btn btn-primary btn-enhanced">
                <i class="fa fa-plus me-2"></i> Add Contact
            </a>
        </div>
    </div>

    {{-- Search Form --}}
    <div class="content-card mb-4">
        <div class="content-card-body">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control form-control-enhanced"
                        placeholder="Search by name, email, phone, company, or position...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-enhanced">
                        <i class="fa fa-search me-2"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-enhanced ms-2">
                            <i class="fa fa-times me-2"></i>Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h5 class="card-title"><i class="fa fa-list me-2"></i>Contacts List</h5>
        </div>
        <div class="content-card-body">
            <div class="table-responsive">
                <table class="enhanced-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->phone }}</td>
                            <td>{{ $contact->company }}</td>
                            <td>{{ $contact->position }}</td>
                            <td>
                                <span class="status-badge status-{{ $contact->is_active ? 'active' : 'inactive' }}">
                                    <i class="fa fa-{{ $contact->is_active ? 'check-circle' : 'times-circle' }} me-1"></i>
                                    {{ $contact->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.contacts.edit', $contact) }}" class="action-btn edit-btn" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn" onclick="return confirm('Delete this contact?')" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <div class="empty-content">
                                    <i class="fa fa-inbox"></i>
                                    <h5 class="empty-title">No contacts found</h5>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $contacts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
