{{-- resources/views/admin/mails/show-campaign.blade.php --}}
@extends('layouts.contents')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Newsletter: {{ $campaign->subject }}</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge badge-{{ $campaign->sent_at ? 'success' : 'warning' }}">
                            {{ $campaign->sent_at ? 'Sent' : 'Draft' }}
                        </span>
                    </div>
                    @if($campaign->sent_at)
                    <div class="mb-3">
                        <strong>Sent On:</strong> {{ $campaign->sent_at->format('M d, Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>Recipients:</strong> {{ $campaign->sent_count }}
                    </div>
                    @endif
                    <div class="mb-3">
                        <strong>Content:</strong>
                        <div class="border p-3 mt-2">
                            {!! $campaign->content !!}
                        </div>
                    </div>
                    @if(!$campaign->sent_at)
                    <form action="{{ route('admin.newsletters.send', $campaign) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Send this newsletter to all subscribers?')">Send Now</button>
                    </form>
                    @endif
                    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection