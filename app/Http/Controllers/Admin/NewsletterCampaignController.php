<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterEmail;

class NewsletterCampaignController extends Controller
{
    public function index()
    {
        $campaigns = NewsletterCampaign::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.mails.manageletters', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.mails.create-campaign');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $campaign = NewsletterCampaign::create($request->only(['subject', 'content']));

        return redirect()->route('admin.newsletters.index')->with('success', 'Campaign created successfully');
    }

    public function send(NewsletterCampaign $campaign)
{
    $subscribers = NewsletterSubscriber::where('is_active', true)->get();
    
    foreach ($subscribers as $subscriber) {
        Mail::to($subscriber->email)
            ->send(new NewsletterEmail($campaign, $subscriber));
    }

    $campaign->update([
        'sent_at' => now(),
        'sent_count' => $subscribers->count()
    ]);

    return back()->with('success', 'Newsletter sent to '.$subscribers->count().' subscribers');
}
    public function show(NewsletterCampaign $campaign)
    {
        return view('admin.mails.show-campaign', compact('campaign'));
    }
}
