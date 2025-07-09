<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        // Use model scopes for cleaner code
        $blogCount = Blog::count();
        $publishedBlogCount = Blog::published()->count();
        $draftBlogCount = Blog::drafts()->count();
        
        $recentBlogs = Blog::latest()->take(5)->get();

        // FAQ statistics (if you have FAQ functionality)
        $activeFaqCount = 0;
        $faqCategories = collect();
        
        if (class_exists('App\Models\FaqCategory')) {
            $activeFaqCount = FaqCategory::withCount(['activeFaqs'])->get()->sum('active_faqs_count');
            $faqCategories = FaqCategory::withCount('activeFaqs')->orderBy('order')->take(5)->get();
        }

        return view('admin.Contents.index', [
            'blogCount' => $blogCount,
            'publishedBlogCount' => $publishedBlogCount,
            'draftBlogCount' => $draftBlogCount,
            'recentBlogs' => $recentBlogs,
            'activeFaqCount' => $activeFaqCount,
            'faqCategories' => $faqCategories,
        ]);
    }
}
