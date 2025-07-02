{{-- resources/views/admin/mails/managesubscribers.blade.php --}}
@extends('layouts.contents')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Newsletter Subscribers</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Subscribed On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $subscriber)
                            <tr>
                                <td>{{ $subscriber->email }}</td>
                                <td>
                                    <span class="badge badge-{{ $subscriber->is_active ? 'success' : 'danger' }}">
                                        {{ $subscriber->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $subscriber->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $subscribers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection