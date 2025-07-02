<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    // Admin Blog Methods
    
    /**
     * Display a listing of the blog posts (admin)
     */
    public function index()
{
    $blogs = Blog::latest()
        ->when(request('search'), function($query) {
            $query->where('title', 'like', '%'.request('search').'%')
                  ->orWhere('content', 'like', '%'.request('search').'%');
        })
        ->paginate(10);

    // Count published posts (where published_at is not null and in the past)
    $publishedCount = Blog::whereNotNull('published_at')
                        ->where('published_at', '<=', now())
                        ->count();

    // Count draft posts (where published_at is null or in the future)
    $draftCount = Blog::where(function($query) {
                        $query->whereNull('published_at')
                              ->orWhere('published_at', '>', now());
                    })
                    ->count();

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
     * Store a newly created blog post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        // Generate slug if empty
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        Blog::create($validated);

        return redirect()->route('blogs.index')
            ->with('success', 'Blog post created successfully.');
    }

    /**
     * Show the form for editing the specified blog post
     */
    public function edit(Blog $blog)
    {
        return view('admin.Contents.blogform', compact('blog'));
    }

    /**
     * Update the specified blog post
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        // Handle image upload
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
        }

        $blog->update($validated);

        return redirect()->route('blogs.index')
            ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog post
     */
    public function destroy(Blog $blog)
    {
        // Delete associated image
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()->route('blogs.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    // Frontend Blog Methods
    
    /**
     * Display a listing of the blog posts (frontend)
     */
   public function frontIndex()
{
    $blogs = Blog::published()
        ->when(request('search'), function($query) {
            $query->where('title', 'like', '%'.request('search').'%')
                  ->orWhere('content', 'like', '%'.request('search').'%');
        })
        ->orderBy('published_at', 'desc')
        ->paginate(6);

    // Debugging - uncomment to check data
     //dd($blogs);

    return view('home.blog', compact('blogs'));
}

    /**
     * Display the specified blog post (frontend)
     */
public function frontShow($slug)
{
    try {
        // Find the blog post
        $blog = Blog::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Get recent blogs excluding current one
        $recentBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        // Get related blogs by category
        $relatedBlogs = [];
        if ($blog->category) {
            $relatedBlogs = Blog::published()
                ->where('category', $blog->category)
                ->where('id', '!=', $blog->id)
                ->orderBy('published_at', 'desc')
                ->take(3)
                ->get();
        }

        // Get all unique categories for the sidebar
        $categories = Blog::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

        // Log for debugging
        Log::info('Blog details accessed', [
            'slug' => $slug,
            'blog_id' => $blog->id,
            'title' => $blog->title
        ]);

        return view('Home.blogdetails', compact('blog', 'recentBlogs', 'relatedBlogs', 'categories'));

    } catch (\Exception $e) {
        Log::error('Error loading blog details', [
            'slug' => $slug,
            'error' => $e->getMessage()
        ]);
        
        abort(404, 'Blog post not found');
    }
}


    /**
     * Get blogs by category (frontend)
     */
    public function byCategory($category)
    {
        $blogs = Blog::published()
            ->where('category', $category)
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('Home.blog', compact('blogs', 'category'));
    }

    /**
     * Search blogs (frontend)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
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

    public function show(Blog $blog)
{
    return view('admin.Contents.blogshow', compact('blog'));
}
}

