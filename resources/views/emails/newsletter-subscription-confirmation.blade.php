{{-- resources/views/emails/newsletter-subscription-confirmation.blade.php --}}
@component('mail::message')
# Thank You for Subscribing!

We're excited to have you join our community! You'll now receive our latest updates, news, and exclusive offers directly to your inbox.

As a subscriber, you'll be the first to know about:
- Industry insights and trends
- Special promotions and offers
- Helpful financial tips
- Company news and updates

@component('mail::button', ['url' => url('/'), 'color' => 'primary'])
Visit Our Website
@endcomponent

If you didn't request this subscription, please ignore this email or [unsubscribe here]({{ $unsubscribe_url }}).

---

Best regards,  
**The Team at Fiscal Support Services**
@endcomponent
