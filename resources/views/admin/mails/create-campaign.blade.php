{{-- resources/views/admin/mails/create-campaign.blade.php --}}
@extends('layouts.contents')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Newsletter Campaign</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.newsletters.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" class="form-control" rows="10" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Campaign</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection