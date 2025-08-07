@component('mail::message')
# Welcome!

Hello {{ $subscriber->email }},

Thank you for subscribing to our newsletter! We're excited to have you join our community of valued customers and partners.

You'll now receive our latest updates, news, and exclusive offers directly to your inbox.

## What to Expect

As a subscriber, you'll be the first to know about:
- **Industry insights** and fiscal compliance trends
- **Special promotions** and exclusive offers
- **Helpful financial tips** and best practices
- **Company news** and service updates
- **Technical guides** and tutorials

@component('mail::button', ['url' => $website_url, 'color' => 'primary'])
Visit Our Website
@endcomponent

## Stay Connected

Follow us on social media for even more updates:
- Facebook: [Fiscal Support Services](https://www.facebook.com/p/FIscal-Support-Services-100032786814929/)
- LinkedIn: [Company Page](https://zw.linkedin.com/company/fiscalsupport)
---

**Subscription Details:**
- Email: {{ $subscriber->email }}
- Subscribed: {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('F j, Y \a\t g:i A') : 'Not specified' }}
- Status: Active

If you didn't request this subscription or want to unsubscribe, please ignore this email.

Thanks,<br>
**The Team at {{ $company_name }}**

@endcomponent
