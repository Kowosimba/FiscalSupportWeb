{{-- resources/views/emails/newsletter-subscription-confirmation.blade.php --}}
@component('mail::message')
# Thank You for Subscribing!

You have successfully subscribed to our newsletter. 

If you didn't request this subscription, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent


