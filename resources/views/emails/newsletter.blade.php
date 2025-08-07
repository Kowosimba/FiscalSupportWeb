@component('mail::message')
# {{ $subject }}

{!! $content !!}

@component('mail::button', ['url' => $unsubscribeLink])
Unsubscribe
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent