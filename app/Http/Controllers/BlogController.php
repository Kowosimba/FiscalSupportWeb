<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Store a newly created blog post.
     */
    public function store(Request $request)
    {
        $validated = $this->validateBlogRequest($request);

        try {
            $validated = $this->processImage($request, $validated);
            $validated = $this->processSlug($request, $validated);
            $validated = $this->processStatusAndPublishedAt($validated);
            $validated['author'] = $validated['author'] ?? Auth::user()?->name ?? 'Admin';

            Blog::create($validated);

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog post created successfully!');
        } catch (\Exception $e) {
            Log::error('Blog creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['image'])
            ]);
            return back()->withInput()->withErrors(['error' => 'Failed to create blog post. Please try again.']);
        }
    }

    /**
     * Update the specified blog post.
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $this->validateBlogRequest($request, $blog);

        try {
            $validated = $this->processImage($request, $validated, $blog);
            $validated = $this->processSlug($request, $validated, $blog);
            $validated = $this->processStatusAndPublishedAt($validated);
            $validated['author'] = $validated['author'] ?? $blog->author;

            if ($request->has('remove_image') && $request->remove_image && $blog->image) {
                Storage::disk('public')->delete($blog->image);
                $validated['image'] = null;
            }

            $blog->update($validated);

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog post updated successfully!');
        } catch (\Exception $e) {
            Log::error('Blog update failed: ' . $e->getMessage(), [
                'blog_id' => $blog->id,
                'user_id' => Auth::id(),
                'request_data' => $request->except(['image'])
            ]);
            return back()->withInput()->withErrors(['error' => 'Failed to update blog post. Please try again.']);
        }
    }

    /**
     * Display a listing of the blog posts (admin).
     */
    public function index()
    {
        $blogs = Blog::latest()
            ->when(request('search'), function($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%'.request('search').'%')
                      ->orWhere('content', 'like', '%'.request('search').'%')
                      ->orWhere('author', 'like', '%'.request('search').'%');
                });
            })
            ->paginate(10);

        $publishedCount = Blog::published()->count();
        $draftCount = Blog::drafts()->count();

        return view('admin.Contents.blogposts', compact('blogs', 'publishedCount', 'draftCount'));
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create()
    {
        return view('admin.Contents.blogform');
    }

    /**
     * Show the form for editing the specified blog post.
     */
    public function edit(Blog $blog)
    {
        return view('admin.Contents.blogform', compact('blog'));
    }

    /**
     * Remove the specified blog post.
     */
    public function destroy(Blog $blog)
    {
        try {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $blog->delete();
            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog post deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Blog deletion failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete blog post. Please try again.']);
        }
    }

    // --- Frontend Methods ---

    public function frontIndex()
    {
        $blogs = Blog::published()
            ->when(request('search'), function($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%'.request('search').'%')
                      ->orWhere('content', 'like', '%'.request('search').'%')
                      ->orWhere('excerpt', 'like', '%'.request('search').'%');
                });
            })
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('Home.blog', compact('blogs'));
    }

    public function frontShow($slug)
    {
        $blog = Blog::published()
            ->with(['blogComments'])
            ->where('slug', $slug)
            ->firstOrFail();

        $recentBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $relatedBlogs = $blog->category
            ? Blog::published()
                ->where('category', $blog->category)
                ->where('id', '!=', $blog->id)
                ->take(3)
                ->get()
            : collect();

        $categories = Blog::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('Home.blogdetails', compact('blog', 'recentBlogs', 'relatedBlogs', 'categories'));
    }

    public function byCategory($category)
    {
        $blogs = Blog::published()
            ->where('category', $category)
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('Home.blog', compact('blogs', 'category'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        if (empty($query)) return redirect()->route('blog.index');

        $blogs = Blog::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', '%'.$query.'%')
                  ->orWhere('content', 'like', '%'.$query.'%')
                  ->orWhere('excerpt', 'like', '%'.$query.'%');
            })
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('Home.blog', compact('blogs', 'query'));
    }

    // --- Helper Methods ---

    protected function validateBlogRequest(Request $request, Blog $blog = null)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug' . ($blog ? ",{$blog->id}" : ''),
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
        ];

        if ($blog) {
            $rules['remove_image'] = 'boolean';
        }

        $validated = $request->validate($rules);

        // Remove status and remove_image from validated data as they are not database fields
        unset($validated['status']);
        if (isset($validated['remove_image'])) unset($validated['remove_image']);

        return $validated;
    }

    protected function processImage(Request $request, array $validated, Blog $blog = null)
    {
        if ($request->hasFile('image')) {
            if ($blog && $blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }
        return $validated;
    }

    protected function processSlug(Request $request, array $validated, Blog $blog = null)
    {
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            $originalSlug = $validated['slug'];
            $counter = 1;

            $query = Blog::where('slug', $validated['slug']);
            if ($blog) $query->where('id', '!=', $blog->id);

            while ($query->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
                $query = Blog::where('slug', $validated['slug']);
                if ($blog) $query->where('id', '!=', $blog->id);
            }
        }
        return $validated;
    }

    protected function processStatusAndPublishedAt(array $validated)
    {
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }
        return $validated;
    }
}
