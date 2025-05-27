<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    //
    protected $table = 'vehicle';



    /**
     * set relationship to city.
     */
    public function Deliverypartner_detail()
    {
        return $this->hasMany('App\Model\Deliverypartner_detail','id','vehicle_name');
    }

}
