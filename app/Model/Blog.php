<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'excerpt',
        'published_at',
        'category_id' // Add category_id to fillable
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_categories', 'blog_id', 'category_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
