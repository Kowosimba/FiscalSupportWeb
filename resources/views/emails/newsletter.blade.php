@component('mail::message')
# {{ $subject }}

{!! $content !!}

@component('mail::button', ['url' => $unsubscribeLink])
Unsubscribe
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent