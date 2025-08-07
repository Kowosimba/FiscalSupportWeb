@extends('layouts.app')

@section('title', 'All Users')

@section('content')
<div class="container my-5">
    <h3 class="mb-4">All Users</h3>

    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 5%;">#</th>
                            <th scope="col" style="width: 20%;">Name</th>
                            <th scope="col" style="width: 25%;">Email</th>
                            <th scope="col" style="width: 15%;">Current Role</th>
                            <th scope="col" style="width: 10%;">Status</th> <!-- New column -->
                            <th scope="col" style="width: 25%;">Assign Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-success text-uppercase px-2 py-1" style="font-size: 0.85rem;">
                                    {{ ucfirst($user->role ?? 'User') }}
                                </span>
                            </td>
                            <td>
                                @if(auth()->user()->id !== $user->id)
                                    <form method="POST" action="{{ route('admin.users.toggle-activation', $user) }}">
                                        @csrf
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            @if($user->is_active)
                                                <span class="badge bg-success text-uppercase px-2 py-1" style="font-size: 0.85rem;">
                                                    Active
                                                </span>
                                                <button type="submit" class="btn btn-sm btn-danger">Deactivate</button>
                                            @else
                                                <span class="badge bg-secondary text-uppercase px-2 py-1" style="font-size: 0.85rem;">
                                                    Inactive
                                                </span>
                                                <button type="submit" class="btn btn-sm btn-primary">Activate</button>
                                            @endif
                                        </div>
                                    </form>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.users.assign-role', $user) }}" class="d-flex align-items-center gap-2 flex-wrap">
                                    @csrf
                                    <select name="role" class="form-select form-select-sm" aria-label="Select role for {{ $user->name }}" style="min-width: 120px;">
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}" {{ ($user->role ?? 'user') === $role ? 'selected' : '' }}>
                                                {{ ucfirst($role) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Set</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table tbody tr:hover {
    background-color: #f3f6fb;
}
.badge.bg-success {
    background: linear-gradient(90deg, #16a34a, #22c55e);
    color: #fff;
}
.btn-primary.btn-sm, .btn-danger.btn-sm {
    min-width: 60px;
}
</style>
@endpush
