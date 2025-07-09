<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogCommentController extends Controller
{
    public function store(Request $request, Blog $blog)
    {
        $validationRules = [
            'content' => 'required|string|min:5|max:2000',
        ];

        // Add name and email validation for guests
        if (!Auth::check()) {
            $validationRules['name'] = 'required|string|max:255';
            $validationRules['email'] = 'required|email|max:255';
        }

        $validated = $request->validate($validationRules);

        // Create comment data
        $commentData = [
            'content' => $validated['content'],
            'blog_id' => $blog->id,
        ];

        if (Auth::check()) {
            $commentData['user_id'] = Auth::id();
            $commentData['name'] = Auth::user()->name;
            $commentData['email'] = Auth::user()->email;
        } else {
            $commentData['name'] = $validated['name'];
            $commentData['email'] = $validated['email'];
        }

        BlogComment::create($commentData);

        return back()->with('success', 'Comment posted successfully!');
    }
}
