<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessCategory extends Model
{
    use HasFactory;

    // Table name (optional if following Laravel convention)
    protected $table = 'business_category';

    // Allow mass assignment on these fields
    protected $fillable = [
        'category_name',
        'secondary_categoryname',
        'image',
        'business_id',
        'status',
        'cloudflare_imageid',
    ];
}
