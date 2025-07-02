<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'author',
        'category',
        'image',
        'excerpt',
        'content',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Accessor for formatted published date
    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d M, Y') : null;
    }

    // Accessor for detailed formatted published date
    public function getFormattedPublishedAtAttribute()
    {
        if (!$this->published_at) {
            return null;
        }

        $date = $this->published_at instanceof Carbon
            ? $this->published_at
            : Carbon::parse($this->published_at);

        return $date->format('M d, Y H:i');
    }

    // Scope for published blogs
public function scopePublished($query)
{
    return $query->whereNotNull('published_at')
                ->where('published_at', '<=', Carbon::now());
}


    // Scope for drafts
    public function scopeDrafts($query)
    {
        return $query->whereNull('published_at')
                    ->orWhere('published_at', '>', Carbon::now());
    }

    // Scope for by category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Get the URL for the blog image
    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('assets/img/blog/blog-default.jpg');
    }

    // Get reading time estimate
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200); // Average reading speed: 200 words per minute
        return $readingTime . ' min read';
    }

    // Get excerpt with fallback
    public function getExcerptOrContentAttribute()
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }
        
        return \Illuminate\Support\Str::limit(strip_tags($this->content), 150);
    }

    // Check if blog is published
    public function getIsPublishedAttribute()
    {
        return $this->published_at && $this->published_at <= Carbon::now();
    }

    // Get status for admin
    public function getStatusAttribute()
    {
        if (!$this->published_at) {
            return 'Draft';
        }
        
        if ($this->published_at > Carbon::now()) {
            return 'Scheduled';
        }
        
        return 'Published';
    }

    // Get human readable published date
    public function getHumanPublishedDateAttribute()
    {
        if (!$this->published_at) {
            return null;
        }

        $date = $this->published_at instanceof Carbon
            ? $this->published_at
            : Carbon::parse($this->published_at);

        return $date->diffForHumans();
    }

    // In App\Models\Blog.php

public function blogComments()
{
    return $this->hasMany(BlogComment::class, 'blog_id');
}

}

