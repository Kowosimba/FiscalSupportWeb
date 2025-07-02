{{-- resources/views/admin/mails/manageletters.blade.php --}}
@extends('layouts.contents')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Newsletter Campaigns</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.newsletters.create') }}" class="btn btn-primary">Create New</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Sent On</th>
                                <th>Recipients</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaigns as $campaign)
                            <tr>
                                <td>{{ $campaign->subject }}</td>
                                <td>
                                    <span class="badge badge-{{ $campaign->sent_at ? 'success' : 'warning' }}">
                                        {{ $campaign->sent_at ? 'Sent' : 'Draft' }}
                                    </span>
                                </td>
                                <td>{{ $campaign->sent_at ? $campaign->sent_at->format('M d, Y H:i') : 'Not sent yet' }}</td>
                                <td>{{ $campaign->sent_count }}</td>
                                <td>
                                    <a href="{{ route('admin.newsletters.show', $campaign) }}" class="btn btn-info btn-sm">View</a>
                                    @if(!$campaign->sent_at)
                                    <form action="{{ route('admin.newsletters.send', $campaign) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Send this newsletter to all subscribers?')">Send</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $campaigns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection