@php
    use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
@endphp
@extends('components.homelayout')

@section('title', $blog->title . ' - Blog')

@section('meta')
    <meta name="description" content="{{ $blog->excerpt_or_content }}">
    <meta name="keywords" content="{{ $blog->category }}, blog, {{ config('app.name') }}">
    <meta property="og:title" content="{{ $blog->title }}">
    <meta property="og:description" content="{{ $blog->excerpt_or_content }}">
    <meta property="og:image" content="{{ $blog->image_url }}">
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
@endsection

@section('home-content')
    <!-- Breadcrumb -->
    <x-breadcrumb>News & Updates/Details</x-breadcrumb>

    <!-- Blog Details -->
    <section class="blog__details-area pt-80 pb-80">
        <div class="container">
            <div class="row gy-60">
                <div class="col-lg-8">
                    <div class="blog__details-wrap">
                        <div class="blog__details-thumb">
                            @if($blog->image_url)
                                <div class="thumb">
                                    <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}">
                                </div>
                            @endif
                            <div class="blog__post-date">
                                {{ $blog->published_at?->format('d') ?? '--' }} 
                                <span>{{ $blog->published_at?->format('M') ?? '--' }}</span>
                            </div>
                        </div>
                        <div class="blog__post-meta">
                            <ul class="list-wrap">
                                <li>
                                    <a href="#">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 8C10.21 8 12 6.21 12 4C12 1.79 10.21 0 8 0C5.79 0 4 1.79 4 4C4 6.21 5.79 8 8 8ZM8 10C5.33 10 0 11.34 0 14V16H16V14C16 11.34 10.67 10 8 10Z" fill="currentColor"/>
                                        </svg>
                                        by {{ $blog->author ?? 'Admin' }}
                                    </a>
                                </li>
                                @if($blog->category)
                                    <li>
                                        <a href="{{ route('blog.category', $blog->category) }}">
                                            <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6.5 0L1.5 5V16H15.5V0H6.5ZM14.5 14H3.5V6H14.5V14ZM6.5 2V5H2.5L6.5 1V2Z" fill="currentColor"/>
                                            </svg>
                                            {{ $blog->category }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="blog__details-content">
                            <h3 class="title mb-3">{{ $blog->title }}</h3>
                            @if($blog->excerpt)
                                <div class="bg-gray-50 border-l-4 border-indigo-500 p-6 mb-8 rounded-r-lg">
                                    <p class="text-lg text-gray-700 italic leading-relaxed">{{ $blog->excerpt }}</p>
                                </div>
                            @endif
                            <div class="prose max-w-none">
                                {!! nl2br(e($blog->content)) !!}
                            </div>
                            <div class="blog__details-content-bottom">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <div class="post-tags">
                                            <h5 class="title">Tags:</h5>
                                            <ul class="list-wrap">
                                                @if($blog->category)
                                                    <li>
                                                        <a href="{{ route('blog.category', $blog->category) }}">{{ $blog->category }}</a>
                                                    </li>
                                                @endif
                                                <li><a href="#">Business</a></li>
                                                <li><a href="#">Consultancy</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="blog-post-share">
                                            <h5 class="title">Share:</h5>
                                            <div class="social-links style2">
                                                <ul class="list-wrap d-flex align-items-center gap-2">
                                                    <li>
                                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                                                           target="_blank" 
                                                           rel="noopener"
                                                           class="bg-green-800 hover:bg-green-700 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors duration-200">
                                                            <i class="fab fa-facebook-f text-sm"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(request()->url()) }}" 
                                                           target="_blank" 
                                                           rel="noopener"
                                                           class="bg-green-800 hover:bg-green-700 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors duration-200">
                                                            <i class="fab fa-twitter text-sm"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                                                           target="_blank" 
                                                           rel="noopener"
                                                           class="bg-green-800 hover:bg-green-700 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors duration-200">
                                                            <i class="fab fa-linkedin-in text-sm"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modern Comments Section -->
                    <div class="comments-section mt-5 pt-5 border-top">
                        <div class="comments-header d-flex align-items-center justify-content-between mb-4">
                            <h3 class="comments-title mb-0">
                                <i class="fas fa-comments me-2 text-green-600"></i>
                                Discussion ({{ $blog->blogComments->count() }})
                            </h3>
                        </div>

                        <!-- Comments List -->
                        <div class="comments-list">
                            @forelse($blog->blogComments as $comment)
                                <div class="comment-item mb-4">
                                    <div class="comment-card border rounded-lg p-4 bg-white shadow-sm">
                                        <div class="comment-header d-flex align-items-start mb-3">
                                            <div class="comment-avatar me-3">
                                                <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-weight: 600;">
                                                    {{ strtoupper(substr($comment->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="comment-meta flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <h5 class="comment-author mb-0 fw-semibold">{{ $comment->name }}</h5>
                                                    @if($comment->user_id)
                                                        <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                                            <i class="fas fa-star me-1 text-green-600"></i>Author
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="comment-date text-muted mb-0" style="font-size: 0.875rem;">
                                                    <i class="far fa-clock me-1 text-green-600"></i>
                                                    {{ $comment->created_at->diffForHumans() }}
                                                    <span class="text-muted ms-2">{{ $comment->created_at->format('M j, Y \a\t g:i a') }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="comment-content">
                                            <p class="mb-0 text-gray-700 lh-base">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="no-comments text-center py-5">
                                    <div class="mb-3">
                                        <i class="far fa-comment-dots text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="text-muted mb-2">No comments yet</h5>
                                    <p class="text-muted">Be the first to share your thoughts!</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Comment Form -->
                        <div class="comment-form-section mt-5 pt-4 border-top">
                            <div class="comment-form-header mb-4">
                                <h4 class="mb-1">
                                    <i class="fas fa-edit me-2 text-green-600"></i>
                                    Leave a Comment
                                </h4>
                                <p class="text-muted mb-0">Share your thoughts and join the conversation</p>
                            </div>
                            
                            <div class="comment-form-card border rounded-lg p-4 bg-light">
                                <form action="{{ route('blog-comments.store', $blog) }}" method="POST" class="comment-form">
                                    @csrf
                                    @guest
                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <label for="name" class="form-label fw-medium">
                                                    <i class="fas fa-user me-1 text-green-600"></i>
                                                    Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       id="name" 
                                                       name="name" 
                                                       class="form-control @error('name') is-invalid @enderror" 
                                                       placeholder="Enter your name"
                                                       value="{{ old('name') }}" 
                                                       required>
                                               @error('name')
                                                    @isset($message)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @endisset
                                                @enderror

                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label fw-medium">
                                                    <i class="fas fa-envelope me-1 text-green-600"></i>
                                                    Email <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" 
                                                       id="email" 
                                                       name="email" 
                                                       class="form-control @error('email') is-invalid @enderror" 
                                                       placeholder="Enter your email"
                                                       value="{{ old('email') }}" 
                                                       required>
                                                @if ($errors->has('email'))
                                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                                @endif
                                                
                                            </div>
                                        </div>
                                    @endguest
                                    
                                    <div class="mb-3">
                                        <label for="content" class="form-label fw-medium">
                                            <i class="fas fa-comment me-1 text-green-600"></i>
                                            Your Comment <span class="text-danger">*</span>
                                        </label>
                                        <textarea id="content" 
                                                  name="content" 
                                                  rows="5" 
                                                  class="form-control @error('content') is-invalid @enderror" 
                                                  placeholder="Share your thoughts..."
                                                  required>{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1 text-green-600"></i>
                                            Please be respectful and constructive in your comments.
                                        </div>
                                    </div>

                                    @if(config('services.recaptcha.enabled'))
                                        <div class="mb-3">
                                            {!! NoCaptcha::display() !!}
                                            @error('g-recaptcha-response')
                                                @isset($message)
                                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                                @endisset
                                            @enderror
                                        </div>
                                    @endif
                                    
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="form-text text-muted">
                                            <i class="fas fa-shield-alt me-1 text-green-600"></i>
                                            Your email will not be published
                                        </div>
                                        <button type="submit" class="btn btn-primary px-4 py-2">
                                            <i class="fas fa-paper-plane me-1"></i>
                                            Post Comment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <aside class="blog-sidebar">
                        <!-- Search Widget -->
                        <div class="blog-widget">
                            <h4 class="widget-title">Search Here</h4>
                            <div class="sidebar-search-form">
                                <form action="{{ route('blog.index') }}">
                                    <input type="text" name="search" placeholder="Enter Keyword" value="{{ request('search') }}">
                                    <button type="submit"><i class="fas fa-search"></i></button>
                                </form>
                            </div>
                        </div>

                        @if(isset($categories) && $categories->count() > 0)
                            <!-- Categories Widget -->
                            <div class="blog-widget">
                                <h4 class="widget-title">Categories</h4>
                                <div class="sidebar-cat-list">
                                    <ul class="list-wrap">
                                        @foreach($categories as $category)
                                            <li>
                                                <a href="{{ route('blog.category', $category) }}">
                                                    {{ $category }}
                                                    <span><i class="fas fa-arrow-right"></i></span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <!-- Recent Posts Widget -->
                        <div class="blog-widget widget-rc-post">
                            <h4 class="widget-title">Recent Posts</h4>
                            <div class="rc-post-wrap">
                                @foreach($recentBlogs as $recent)
                                    <div class="rc-post-item">
                                        @if($recent->image_url)
                                            <div class="thumb">
                                                <a href="{{ route('blog.details', $recent->slug) }}">
                                                    <img src="{{ $recent->image_url }}" alt="{{ $recent->title }}">
                                                </a>
                                            </div>
                                        @endif
                                        <div class="content">
                                            <span class="date">
                                                <i class="far fa-clock"></i>
                                                {{ $recent->published_at?->format('M d, Y') ?? '' }}
                                            </span>
                                            <h4 class="title">
                                                <a href="{{ route('blog.details', $recent->slug) }}">
                                                    {{ Str::limit($recent->title, 50) }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tags Widget -->
                        <div class="blog-widget">
                            <h3 class="widget-title">Popular Tags</h3>
                            <div class="sidebar-tag-list">
                                <ul class="list-wrap">
                                    @foreach($categories as $category)
                                        <li>
                                            <a href="{{ route('blog.category', $category) }}">{{ $category }}</a>
                                        </li>
                                    @endforeach
                                    <li><a href="#">Business</a></li>
                                    <li><a href="#">Consultancy</a></li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <style>
    /* Modern Comments Styling */
    .comments-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 2rem;
        margin-top: 2rem;
    }

    .comments-title {
        color: #1e5f23;
        font-weight: 600;
        font-size: 1.5rem;
    }

    .comment-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef !important;
    }

    .comment-card:hover {
        box-shadow: 0 4px 12px rgba(14, 62, 45, 0.1) !important;
        transform: translateY(-2px);
    }

    .avatar-circle {
        background: linear-gradient(135deg, #106221 0%, #0d6f34 100%) !important;
        font-size: 1.1rem;
        box-shadow: 0 2px 8px rgba(25, 81, 53, 0.3);
    }

    .comment-author {
        color: #2e6950;
        font-size: 1rem;
    }

    .comment-date {
        font-size: 0.85rem;
    }

    .comment-content {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #4a5568;
    }

    .comment-form-card {
        background: white !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .comment-form .form-control {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .comment-form .form-control:focus {
        border-color: #0c6630;
        box-shadow: 0 0 0 3px rgba(17, 119, 68, 0.1);
    }

    .comment-form .btn-primary {
        background: linear-gradient(135deg, #307d2e 0%, #0a491c 100%);
        border: none;
        border-radius: 8px;
        font-weight: 500;
        padding: 12px 24px;
        transition: all 0.2s ease;
    }

    .comment-form .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(117, 224, 147, 0.4);
    }

    .no-comments i {
        opacity: 0.5;
    }

    .form-label {
        color: #244d3b;
        margin-bottom: 0.5rem;
    }

    .form-label i {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .comments-section {
            padding: 1.5rem;
        }
        
        .comment-header {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .comment-meta {
            margin-left: 0 !important;
            margin-top: 0.5rem;
        }

        .blog-post-share {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .blog-post-share .title {
            margin-bottom: 0;
            color: #1e5f23;
            font-size: 16px;
            font-weight: 500;
        }

        .social-links.style2 ul {
            padding-left: 0;
            margin-bottom: 0;
        }

        .social-links.style2 ul li {
            display: inline-block;
            list-style: none;
            margin-right: 0;
        }

        .social-links.style2 ul li a {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .social-links.style2 ul li a:hover {
            transform: translateY(-2px);
        }
    }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection