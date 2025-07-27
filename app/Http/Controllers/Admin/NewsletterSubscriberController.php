<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use Illuminate\Validation\Rule;

class NewsletterSubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.mails.managesubscribers', compact('subscribers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('newsletter_subscribers', 'email')
            ],
            'name' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        // Set default active status if not provided
        $validated['is_active'] = $request->has('is_active') ? true : false;

        try {
            NewsletterSubscriber::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subscriber added successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Subscriber added successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error adding subscriber. Please try again.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Error adding subscriber. Please try again.');
        }
    }

    /**
     * Toggle subscriber status
     */
    public function toggle(NewsletterSubscriber $subscriber)
    {
        try {
            $subscriber->update([
                'is_active' => !$subscriber->is_active
            ]);

            $status = $subscriber->is_active ? 'activated' : 'deactivated';

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Subscriber {$status} successfully!"
                ]);
            }

            return redirect()->back()->with('success', "Subscriber {$status} successfully!");
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating subscriber status.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Error updating subscriber status.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsletterSubscriber $subscriber)
    {
        try {
            $subscriber->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subscriber removed successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Subscriber removed successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting subscriber.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Error deleting subscriber.');
        }
    }
}
