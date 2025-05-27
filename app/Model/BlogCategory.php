<?php

namespace App\Model;

use App\Model\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_categories', 'category_id', 'blog_id');
    }
}
