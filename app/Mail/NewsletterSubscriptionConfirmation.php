<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscriptionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;
    public $website_url;
    public $company_name;

    public function __construct(NewsletterSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
        $this->website_url = config('app.url'); // or your actual website URL
        $this->company_name = 'Fiscal Support Services'; // or fetch from config/database
    }

    public function build()
    {
        return $this->subject('Newsletter Subscription Confirmation')
            ->markdown('emails.newsletter-subscription-confirmation')
            ->with([
                'subscriber' => $this->subscriber,
                'website_url' => $this->website_url,
                'company_name' => $this->company_name,
            ])
            ->attach(public_path('assets/img/logo/logo-v2.png'));
    }
}
