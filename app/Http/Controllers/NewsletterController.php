<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterSubscriptionConfirmation;
use App\Mail\NewsletterUnsubscribed;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email'
        ]);

        $subscriber = NewsletterSubscriber::create([
            'email' => $request->email,
            'is_active' => true
        ]);

        // Send confirmation email
        Mail::to($subscriber->email)->send(new NewsletterSubscriptionConfirmation($subscriber));

        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }

    public function unsubscribe($token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

        if ($subscriber->is_active) {
            $subscriber->update(['is_active' => false]);
            
            // Send confirmation of unsubscription
            Mail::to($subscriber->email)->send(new NewsletterUnsubscribed($subscriber));
            
            return view('newsletter.unsubscribed')->with('email', $subscriber->email);
        }

        return view('newsletter.already-unsubscribed')->with('email', $subscriber->email);
    }
}