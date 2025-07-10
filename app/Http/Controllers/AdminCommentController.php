<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index()
    {
        $comments = BlogComment::with(['blog', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function edit(BlogComment $comment)
    {
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, BlogComment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:5|max:2000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $comment->update($validated);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment updated successfully!');
    }

    public function destroy(BlogComment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
