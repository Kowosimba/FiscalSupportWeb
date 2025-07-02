@extends('components.homelayout')

@section('title', 'Blog - ' . config('app.name'))

@section('home-content')

<x-breadcrumb>News & Updates</x-breadcrumb>

<!-- Blog Grid Section -->
<section class="blog-grid-area pt-80 pb-80">
    <div class="container">
        @if($blogs->count() > 0)
            <div class="row gy-4">
                @foreach($blogs as $blog)
                    <div class="col-xl-4 col-md-6">
                        <div class="blog-card">
                            <div class="blog-card__thumb">
                                <a href="{{ route('blog.details', $blog->slug) }}">
                                    <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="img-fluid">
                                </a>
                                <div class="blog-card__date">
                                    <span>{{ $blog->published_at->format('d') }}</span>
                                    {{ $blog->published_at->format('M') }}
                                </div>
                            </div>
                            <div class="blog-card__content">
                                <div class="blog-card__meta">
                                    <div class="blog-card__meta-left">
                                        @if($blog->category)
                                            <a href="{{ route('blog.category', $blog->category) }}" class="blog-card__category">
                                                {{ $blog->category }}
                                            </a>
                                        @endif
                                        @if($blog->author)
                                            <span class="blog-card__author">
                                                By {{ $blog->author }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="blog-card__reading-time">
                                        <i class="far fa-clock"></i> {{ $blog->reading_time }}
                                    </span>
                                </div>
                                <h3 class="blog-card__title">
                                    <a href="{{ route('blog.details', $blog->slug) }}">{{ $blog->title }}</a>
                                </h3>
                                <p class="blog-card__excerpt">{{ Str::limit($blog->excerpt_or_content, 120) }}</p>
                                <a href="{{ route('blog.details', $blog->slug) }}" class="blog-card__link">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            
        @else
            <div class="empty-state text-center py-60">
                <i class="fas fa-newspaper empty-state__icon"></i>
                <h3 class="empty-state__title">No Blog Posts Yet</h3>
                <p class="empty-state__text">Check back later for new content.</p>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .blog-grid-area {
        padding: 0;
        margin-top: 40px; /* Space between breadcrumb and content */
        margin-bottom: 60px; /* Space between content and footer */
    }
    
    .blog-card {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        margin-bottom: 30px; /* Increased spacing between cards */
    }
    
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .blog-card__thumb {
        position: relative;
        overflow: hidden;
    }
    
    .blog-card__thumb img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .blog-card:hover .blog-card__thumb img {
        transform: scale(1.05);
    }
    
    .blog-card__date {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #14792c;
        color: #fff;
        text-align: center;
        padding: 6px 10px;
        border-radius: 4px;
        font-weight: 600;
        line-height: 1.2;
        font-size: 14px;
    }
    
    .blog-card__date span {
        display: block;
        font-size: 18px;
        font-weight: 700;
    }
    
    .blog-card__content {
        padding: 25px; /* Increased internal padding */
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .blog-card__meta {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px; /* Increased spacing */
        font-size: 13px;
    }
    
    .blog-card__meta-left {
        display: flex;
        flex-direction: column;
        gap: 8px; /* Space between category and author */
    }
    
    .blog-card__category {
        background: #e8f5e9;
        color: #277e3b;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 500;
        transition: all 0.3s;
        display: inline-block;
        width: fit-content;
    }
    
    .blog-card__category:hover {
        background: #15782c;
        color: #fff;
    }
    
    .blog-card__author {
        color: #6c757d;
        font-size: 12px;
        font-style: italic;
        font-weight: 500;
    }
    
    .blog-card__reading-time {
        color: #6c757d;
        align-self: flex-start;
    }
    
    .blog-card__title {
        font-size: 18px; /* Slightly larger title */
        margin-bottom: 15px; /* Increased spacing */
        line-height: 1.4;
        font-weight: 600;
    }
    
    .blog-card__title a {
        color: #212529;
        transition: color 0.3s;
    }
    
    .blog-card__title a:hover {
        color: #04741f;
    }
    
    .blog-card__excerpt {
        color: #6c757d;
        margin-bottom: 20px; /* Increased spacing */
        flex: 1;
        font-size: 14px;
        line-height: 1.6; /* Better readability */
    }
    
    .blog-card__link {
        color: #02681a;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        font-size: 14px;
        margin-top: auto; /* Push to bottom */
    }
    
    .blog-card__link i {
        margin-left: 5px;
        transition: transform 0.3s;
        font-size: 12px;
    }
    
    .blog-card__link:hover i {
        transform: translateX(3px);
    }
    
    .empty-state {
        background: #fff;
        padding: 50px 30px; /* Increased padding */
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin: 40px 0; /* Added vertical margins */
    }
    
    .empty-state__icon {
        font-size: 48px; /* Larger icon */
        color: #07621c;
        margin-bottom: 20px; /* Increased spacing */
    }
    
    .empty-state__title {
        font-size: 22px; /* Larger title */
        margin-bottom: 12px; /* Increased spacing */
        color: #212529;
        font-weight: 600;
    }
    
    .empty-state__text {
        color: #6c757d;
        font-size: 16px; /* Larger text */
    }
    
    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 50px; /* Increased top margin */
        margin-bottom: 30px; /* Added bottom margin */
    }
    
    .pagination li {
        margin: 0 3px;
    }
    
    .pagination li a, 
    .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px; /* Slightly larger */
        height: 40px;
        border-radius: 6px;
        background: #f8f9fa;
        color: #074716;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .pagination li.active span,
    .pagination li a:hover {
        background: #115d22;
        color: #fff;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .blog-grid-area {
            margin-top: 30px;
            margin-bottom: 40px;
        }
        
        .blog-card {
            margin-bottom: 25px;
        }
        
        .blog-card__content {
            padding: 20px;
        }
        
        .blog-card__meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .blog-card__reading-time {
            align-self: flex-start;
        }
        
        .empty-state {
            padding: 40px 20px;
            margin: 30px 0;
        }
        
        .pagination {
            margin-top: 40px;
            margin-bottom: 20px;
        }
    }
</style>
@endpush