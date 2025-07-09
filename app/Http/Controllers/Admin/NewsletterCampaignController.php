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
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        NewsletterCampaign::create($data);

        return redirect()->route('admin.newsletters.index')->with('success', 'Campaign saved as draft!');
    }

    /**
     * Route Model Binding: $newsletter (NOT $campaign)
     * This matches the {newsletter} parameter in your resource route.
     */
    public function edit(NewsletterCampaign $newsletter)
    {
        // Pass as $campaign for blade compatibility
        return view('admin.mails.create-campaign', ['campaign' => $newsletter]);
    }

    public function update(Request $request, NewsletterCampaign $newsletter)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $newsletter->update($data);

        return redirect()->route('admin.newsletters.index')->with('success', 'Campaign updated!');
    }

    public function show(NewsletterCampaign $newsletter)
    {
        return view('admin.mails.show-campaign', ['campaign' => $newsletter]);
    }

    public function send(NewsletterCampaign $newsletter)
    {
        $subscribers = NewsletterSubscriber::where('is_active', true)->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->send(new NewsletterEmail($newsletter, $subscriber));
        }

        $newsletter->update([
            'sent_at' => now(),
            'sent_count' => $subscribers->count()
        ]);

        return back()->with('success', 'Newsletter sent to ' . $subscribers->count() . ' subscribers');
    }
}
