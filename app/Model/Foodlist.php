<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Foodlist extends Model
{
    //
    protected $table = 'food_list';

    protected $with = ['Category', 'Menu', 'Add_ons'];

    /**
    * set relationship to Add_ons.
    *
    */
    public function Add_ons()
    {
        return $this->belongsToMany('App\Model\Add_ons','foodlist_addons', 'foodlist_id', 'addons_id');
    }


    /**
    * set relationship to FoodQuantity.
    *
    */
    public function FoodQuantity()
    {
        return $this->belongsToMany('App\Model\FoodQuantity', 'foodlist_foodquantity', 'foodlist_id', 'foodquantity_id')->withPivot("price","size_description",'is_default');
    }



    /**
    * set relationship to category.
    *
    */
    public function Category()
    {
        return $this->belongsTo('App\Model\Category','category_id','id');
    }


    /**
    * set relationship to menu.
    *
    */
    public function Menu()
    {
        return $this->belongsTo('App\Model\Menu','menu_id','id');
    }


    /**
    * set relationship to menu.
    *
    */
    public function Requestdetail()
    {
        return $this->belongsTo('App\Model\Requestdetail','food_id','id');
    }

     public function pricings()
    {
        return $this->hasMany(FoodListPricing::class, 'product_id');
    }

}
