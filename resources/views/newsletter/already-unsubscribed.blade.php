{{-- resources/views/newsletter/unsubscribed.blade.php --}}
@extends('layouts.emaillayout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Unsubscription Successful</div>

                <div class="card-body">
                    <p>The email address <strong>{{ $email }}</strong> has been successfully unsubscribed from our newsletter.</p>
                    <p>You will no longer receive emails from us.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

