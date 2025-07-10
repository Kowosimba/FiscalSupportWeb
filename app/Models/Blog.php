<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author',
        'category',
        'image',
        'excerpt',
        'content',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Boot method for auto-generating slug
    protected static function booted()
    {
        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });

        static::updating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    // =============================================================================
    // RELATIONSHIPS
    // =============================================================================

    /**
     * Get all comments for this blog post
     */
    public function blogComments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id')
                    ->latest();
    }

    /**
     * Get comments count
     */
    public function commentsCount()
    {
        return $this->blogComments()->count();
    }

    // =============================================================================
    // QUERY SCOPES
    // =============================================================================

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeDrafts($query)
    {
        return $query->whereNull('published_at')
                    ->orWhere('published_at', '>', now());
    }

    public function scopeScheduled($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '>', now());
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

   
public function scopeOrdered($query)
{
    return $query->orderBy('published_at', 'desc');
}


    public function scopeRecent($query, $limit = 5)
    {
        return $query->published()->orderBy('published_at', 'desc')->limit($limit);
    }

    public function scopeWithComments($query)
    {
        return $query->with(['blogComments']);
    }

    // =============================================================================
    // ACCESSORS & ATTRIBUTES
    // =============================================================================

    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at?->format('d M, Y');
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at?->format('M d, Y H:i');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return asset('assets/img/blog/blog-default.jpg');
    }

    public function getReadingTimeAttribute()
    {
        if (!$this->content) {
            return '0 min read';
        }

        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = max(1, ceil($wordCount / 200)); // Minimum 1 minute
        
        return $readingTime . ' min read';
    }

    public function getExcerptOrContentAttribute()
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }
        
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getIsPublishedAttribute()
    {
        return $this->published_at && $this->published_at <= now();
    }

    public function getStatusAttribute()
    {
        if (!$this->published_at) {
            return 'Draft';
        }
        
        if ($this->published_at > now()) {
            return 'Scheduled';
        }
        
        return 'Published';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'Published' => '<span class="badge bg-success">Published</span>',
            'Scheduled' => '<span class="badge bg-warning">Scheduled</span>',
            'Draft' => '<span class="badge bg-secondary">Draft</span>',
            default => '<span class="badge bg-light">Unknown</span>',
        };
    }

    public function getHumanPublishedDateAttribute()
    {
        return $this->published_at ? Carbon::parse($this->published_at)->diffForHumans() : null;
    }

    public function getWordCountAttribute()
    {
        return str_word_count(strip_tags($this->content ?? ''));
    }

    public function getCommentsCountAttribute()
    {
        return $this->blogComments()->count();
    }

    public function getMetaTitleAttribute()
    {
        return $this->attributes['meta_title'] ?? $this->title;
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->attributes['meta_description'] ?? $this->excerpt_or_content;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // =============================================================================
    // METHODS
    // =============================================================================

    public function isPublished()
    {
        return $this->is_published;
    }

    public function isDraft()
    {
        return !$this->published_at;
    }

    public function isScheduled()
    {
        return $this->published_at && $this->published_at > now();
    }

    public function publish()
    {
        $this->update(['published_at' => now()]);
        return $this;
    }

    public function unpublish()
    {
        $this->update(['published_at' => null]);
        return $this;
    }

    public function scheduleFor(Carbon $date)
    {
        $this->update(['published_at' => $date]);
        return $this;
    }

    public function getUrl()
    {
        return route('blog.details', $this->slug);
    }

    public function hasComments()
    {
        return $this->blogComments()->exists();
    }

    public function getLatestComments($limit = 5)
    {
        return $this->blogComments()->limit($limit)->get();
    }

    
}
