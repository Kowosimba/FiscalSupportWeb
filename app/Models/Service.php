<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'content', 'image', 
        'is_featured', 'sort_order', 'process_steps',
        'meta_title', 'meta_description', 'meta_keywords'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'process_steps' => 'array'
    ];

    // Relationship to resources
    public function resources()
    {
        return $this->hasMany(ServiceResource::class);
    }

    protected static function booted()
    {
        static::creating(function ($service) {
            $service->slug = $service->slug ?: Str::slug($service->title);
        });

        static::updating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }
}
