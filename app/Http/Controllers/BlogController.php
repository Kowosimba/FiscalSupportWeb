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
     * Store a newly created blog post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('blogs', 'public');
            }

            // Generate slug if empty
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['title']);
                
                // Ensure slug is unique
                $originalSlug = $validated['slug'];
                $counter = 1;
                while (Blog::where('slug', $validated['slug'])->exists()) {
                    $validated['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            // Handle status and published_at
            if ($validated['status'] === 'published') {
                if (empty($validated['published_at'])) {
                    $validated['published_at'] = now();
                }
            } else {
                $validated['published_at'] = null;
            }

            // Set default author if not provided
            if (empty($validated['author'])) {
                $validated['author'] = Auth::user()?->name ?? 'Admin';
            }

            // Remove status from validated data as it's not a database field
            unset($validated['status']);

            $blog = Blog::create($validated);

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog post created successfully!');

        } catch (\Exception $e) {
            Log::error('Blog creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['image'])
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create blog post. Please try again.']);
        }
    }

    /**
     * Update the specified blog post
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'remove_image' => 'boolean'
        ]);

        try {
            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image) {
                if ($blog->image) {
                    Storage::disk('public')->delete($blog->image);
                    $validated['image'] = null;
                }
            }

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($blog->image) {
                    Storage::disk('public')->delete($blog->image);
                }
                $validated['image'] = $request->file('image')->store('blogs', 'public');
            }

            // Generate slug if empty
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['title']);
                
                // Ensure slug is unique (excluding current blog)
                $originalSlug = $validated['slug'];
                $counter = 1;
                while (Blog::where('slug', $validated['slug'])->where('id', '!=', $blog->id)->exists()) {
                    $validated['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            // Handle status and published_at
            if ($validated['status'] === 'published') {
                if (empty($validated['published_at'])) {
                    $validated['published_at'] = now();
                }
            } else {
                $validated['published_at'] = null;
            }

            // Remove status from validated data as it's not a database field
            unset($validated['status']);
            unset($validated['remove_image']);

            $blog->update($validated);

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog post updated successfully!');

        } catch (\Exception $e) {
            Log::error('Blog update failed: ' . $e->getMessage(), [
                'blog_id' => $blog->id,
                'user_id' => Auth::id(),
                'request_data' => $request->except(['image'])
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update blog post. Please try again.']);
        }
    }

    /**
     * Display a listing of the blog posts (admin)
     */
    public function index()
    {
        $blogs = Blog::latest()
            ->when(request('search'), function($query) {
                $search = request('search');
                $query->where('title', 'like', '%'.$search.'%')
                      ->orWhere('content', 'like', '%'.$search.'%')
                      ->orWhere('author', 'like', '%'.$search.'%');
            })
            ->paginate(10);

        $publishedCount = Blog::published()->count();
        $draftCount = Blog::drafts()->count();

        return view('admin.Contents.blogposts', compact('blogs', 'publishedCount', 'draftCount'));
    }

    /**
     * Show the form for creating a new blog post
     */
    public function create()
    {
        return view('admin.Contents.blogform');
    }

    /**
     * Show the form for editing the specified blog post
     */
    public function edit(Blog $blog)
    {
        return view('admin.Contents.blogform', compact('blog'));
    }

    /**
     * Display the specified blog post (admin)
     */
    public function show(Blog $blog)
    {
        return view('admin.Contents.blogshow', compact('blog'));
    }

    /**
     * Remove the specified blog post
     */
    public function destroy(Blog $blog)
    {
        try {
            // Delete associated image
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

    // Frontend methods remain the same...
    public function frontIndex()
    {
        $blogs = Blog::published()
            ->when(request('search'), function($query) {
                $search = request('search');
                $query->where('title', 'like', '%'.$search.'%')
                      ->orWhere('content', 'like', '%'.$search.'%')
                      ->orWhere('excerpt', 'like', '%'.$search.'%');
            })
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('home.blog', compact('blogs'));
    }

    public function frontShow($slug)
    {
        try {
            $blog = Blog::published()
                ->with(['blogComments'])
                ->where('slug', $slug)
                ->firstOrFail();

            $recentBlogs = Blog::published()
                ->where('id', '!=', $blog->id)
                ->orderBy('published_at', 'desc')
                ->take(5)
                ->get();

            $relatedBlogs = collect();
            if ($blog->category) {
                $relatedBlogs = Blog::published()
                    ->where('category', $blog->category)
                    ->where('id', '!=', $blog->id)
                    ->take(3)
                    ->get();
            }

            $categories = Blog::published()
                ->whereNotNull('category')
                ->select('category')
                ->distinct()
                ->pluck('category');

            return view('Home.blogdetails', compact('blog', 'recentBlogs', 'relatedBlogs', 'categories'));

        } catch (\Exception $e) {
            abort(404, 'Blog post not found');
        }
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
        
        if (empty($query)) {
            return redirect()->route('blog.index');
        }

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
}
