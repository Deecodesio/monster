<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    //
    protected $table = 'brands';

    protected $fillable = ['name', 'status', 'created_at', 'updated_at' ];

    /**
     * set relationship to foodlist.
     */
    
}
