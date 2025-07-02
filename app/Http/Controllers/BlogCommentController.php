<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\RecaptchaRule; // Assuming RecaptchaRule is in App\Rules namespace

class BlogCommentController extends Controller
{
    public function store(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string|min:5|max:2000',
            // Added g-recaptcha-response validation here 
        ]);

        $comment = new BlogComment($validated);
        $comment->blog_id = $blog->id;

        if (Auth::check()) {
            $comment->user_id = Auth::id();
            $comment->approved = true; // Comments from authenticated users are auto-approved
        } else {
            $comment->approved = false; // Comments from guests require manual approval
        }

        $comment->save();

        return back()->with('success', 'Comment submitted! ' .
            (Auth::check() ? '' : 'It will appear after approval.'));
    }
}
