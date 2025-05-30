<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Addcity;

class FoodListPricingDistrict extends Model
{
    use HasFactory;

    protected $table = 'food_list_pricing_district';

    protected $fillable = [
        'product_pricing_id',
        'district_id',
    ];

    public $timestamps = false;

    public function pricing()
    {
        return $this->belongsTo(FoodListPricing::class, 'product_pricing_id');
    }

    public function district()
    {
        return $this->belongsTo(Addcity::class);
    }
}
