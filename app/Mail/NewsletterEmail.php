<?php

// app/Mail/NewsletterEmail.php

namespace App\Mail;

use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $subscriber;

    public function __construct(NewsletterCampaign $campaign, NewsletterSubscriber $subscriber)
    {
        $this->campaign = $campaign;
        $this->subscriber = $subscriber;
    }

    public function build()
    {
        return $this->subject($this->campaign->subject)
                   ->markdown('emails.newsletter')
                   ->with([
                       'subject' => $this->campaign->subject,
                       'content' => $this->campaign->content,
                       'unsubscribeLink' => route('newsletter.unsubscribe', $this->subscriber->unsubscribe_token)
                   ]);
    }
}