<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Foodlist;
use App\Model\Addcity;

class FoodListPricing extends Model
{
    use HasFactory;

    protected $table = 'food_list_pricing';

    protected $fillable = [
        'product_id',
        'price',
        'state_id',
        'tax',
        'label',
        'group_id',
    ];

    // A pricing belongs to one product (assuming Product model exists)
    public function product()
    {
        return $this->belongsTo(Foodlist::class, 'product_id');
    }

    // A pricing has many districts (many-to-many)
    public function districts()
    {
        return $this->belongsToMany(Addcity::class, 'food_list_pricing_district', 'product_pricing_id', 'district_id');
    }
}
