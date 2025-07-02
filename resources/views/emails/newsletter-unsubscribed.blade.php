{{-- resources/views/emails/newsletter-unsubscribed.blade.php --}}
@component('mail::message')
# You're Unsubscribed

You have been successfully unsubscribed from our newsletter and will no longer receive updates.

If this was a mistake, you can resubscribe at any time on our website.

Thanks,<br>
{{ config('app.name') }}
@endcomponent