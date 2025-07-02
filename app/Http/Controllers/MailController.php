<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{
    public function sendMail(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);
        
        // Sanitize inputs 
        $data = [
            'name' => strip_tags($validated['name']),
            'email' => filter_var($validated['email'], FILTER_SANITIZE_EMAIL),
            'message' => strip_tags($validated['message']),
        ];
        
        $to = config('mail.contact_address', 'supporthre2@fiscalsupportservices.com');
        $subject = "New Contact Form Submission from " . $data['name'];
        
        try {
            Mail::to($to)->send(new ContactMail($data, $subject));
            return back()->with('message', 'Thank you for reaching out to us! We will get back to you as soon as possible.');
        } catch (\Exception $e) {
            Log::error('Failed to send contact email: ' . $e->getMessage());
            return back()->with('error', 'Sorry, there was a problem sending your message. Please try again later.')
                         ->withInput();
        }
    }
}