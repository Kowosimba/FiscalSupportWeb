{{-- resources/views/newsletter/already-unsubscribed.blade.php --}}
@extends('layouts.emaillayout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Already Unsubscribed</div>

                <div class="card-body">
                    <p>The email address <strong>{{ $email }}</strong> is already unsubscribed from our newsletter.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection