<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\NewsletterSubscriptionConfirmation;
use App\Mail\NewsletterUnsubscribed;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|max:255'
            ]);

            // Check if email already exists
            $existingSubscriber = NewsletterSubscriber::where('email', $request->email)->first();
            
            if ($existingSubscriber) {
                if ($existingSubscriber->is_active) {
                    return back()->with('info', 'This email is already subscribed to our newsletter.');
                } else {
                    // Reactivate subscription
                    $existingSubscriber->update([
                        'is_active' => true,
                        'subscribed_at' => now()
                    ]);
                    
                    Mail::to($existingSubscriber->email)->queue(new NewsletterSubscriptionConfirmation($existingSubscriber));
                    
                    return back()->with('success', 'Welcome back! Your subscription has been reactivated.');
                }
            }

            // Create new subscription
            $token = Str::uuid()->toString();

            $subscriber = NewsletterSubscriber::create([
                'email' => $request->email,
                'is_active' => true,
                'unsubscribe_token' => $token,
                'subscribed_at' => now(),
            ]);

            // Send confirmation email
            Mail::to($subscriber->email)->queue(new NewsletterSubscriptionConfirmation($subscriber));

            Log::info('New newsletter subscription', ['email' => $subscriber->email]);

            return back()->with('success', 'Thank you for subscribing! Please check your email for confirmation.');

        } catch (\Exception $e) {
            Log::error('Newsletter subscription failed', [
                'email' => $request->email ?? 'unknown',
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function unsubscribe($token)
    {
        try {
            $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

            if ($subscriber->is_active) {
                $subscriber->update(['is_active' => false]);
                
                // Send confirmation of unsubscription
                Mail::to($subscriber->email)->send(new NewsletterUnsubscribed($subscriber));
                
                Log::info('Newsletter unsubscription', ['email' => $subscriber->email]);
                
                return view('newsletter.unsubscribed')->with('email', $subscriber->email);
            }

            return view('newsletter.already-unsubscribed')->with('email', $subscriber->email);

        } catch (\Exception $e) {
            Log::error('Newsletter unsubscription failed', [
                'token' => $token,
                'error' => $e->getMessage()
            ]);

            abort(404, 'Invalid unsubscribe link.');
        }
    }
}
