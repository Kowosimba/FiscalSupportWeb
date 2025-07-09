<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FaqCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    // Auto-generate slug
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
            if (is_null($category->is_active)) {
                $category->is_active = true;
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'category_id');
    }

    public function activeFaqs()
    {
        return $this->hasMany(Faq::class, 'category_id')->where('is_active', true)->orderBy('order');
    }
}
