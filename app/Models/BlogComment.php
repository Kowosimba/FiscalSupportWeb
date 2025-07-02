<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $table = 'blog_comments';

    protected $fillable = [
        'blog_id',
        'user_id',
        'name',
        'email',
        'content',
        'approved'
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/Blog.php
    public function blogComments()
    {
        return $this->hasMany(BlogComment::class)->where('approved', true);
    }

    public function scopeApproved($query)
{
    return $query->where('approved', true);
}
}