<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Blog;

class HomeController extends Controller
{
    //
    
    public function index()
{
    $services = Service::orderBy('sort_order')->get();
    $latestBlogs = Blog::orderBy('published_at', 'desc')->take(2)->get();
    return view("Home.index", compact('services', 'latestBlogs'));
}
    public function about()
    {
        return view("Home.about");
    }
    public function contact()
    {
        return view("Home.contactus");
    }
    public function faqs()
    {
        return view("Home.faqs");
    }
    public function blog()
    {
        return view("Home.blog");
    }
    public function pricing()
    {
        return view("Home.pricing");
    }
    public function services()
    {
        $services = Service::orderBy('sort_order')->get();
        return view("Home.services");
    }
    public function team()
    {
        return view("Home.team");
    }

    
}
