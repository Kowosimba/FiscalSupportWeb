@extends('layouts.tickets')

@section('ticket-content')
<h3>All Users</h3>
@if(session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Assign Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ ucfirst($user->role ?? 'User') }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.users.assign-role', $user) }}" class="d-flex align-items-center">
                                    @csrf
                                    <select name="role" class="form-select form-select-sm me-2" style="width:auto;">
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
@endsection
