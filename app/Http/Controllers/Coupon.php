<?php

namespace App\Http\Controllers;


use Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Log;
use App\Http\Controllers\ReturnData;
use App\Http\Controllers\Restaurant;
use App\Http\Controllers\Customer;


class Coupon extends Controller {

	public static function apply (string $code, int $userID, int $restaurantID, array $items) {
		
		$Restaurant = Restaurant::find($restaurantID);
		if (!$Restaurant->flag) {
			return new ReturnData(false, 'Restaurant not found');
		}

		$Coupon = self::findByCode($code,$restaurantID);
		if (!$Coupon->flag) {
			return new ReturnData(false, 'Coupon code not found');
		}

		
       // if ($Coupon->restaurant_id && $Coupon->restaurant_id != $restaurantID) {
       //     return new ReturnData(false, 'Coupon code does not matches this restaurant');
       // }


		
		$usedCount = $Coupon->usedCount();
		if ($usedCount >= $Coupon->total_use) {
			return new ReturnData(false, 'This coupon does not available anymore');
		}

		$usedCountOfCustomer = $Coupon->usedCountOfCustomer($userID);
		if ($usedCountOfCustomer >= $Coupon->use_per_customer) {
			return new ReturnData(false, 'You have exceeded the maximum usage limit of this coupon');
		}

		$itemAmount  = $Coupon->itemTotal($restaurantID, $items);
		if ($itemAmount <= 0) {
			return new ReturnData(false, 'This coupon does not applicable for selected product(s)');
		}
//check min amount
$minAmount = $Coupon->minAmount($itemAmount);
if ($minAmount > 0) {
			return new ReturnData(false, 'Add RS.'.$minAmount.' worth of items to avail this offer');
		}

		$offerAmount = $Coupon->calcOfferAmount($itemAmount);
		

		return new ReturnData (true, 'OK', $offerAmount);
		
	}

	public static function findByCode ($code,$restaurantID) {

$row = DB::table('coupon_code')
->where('code', $code)->where('status', 1)
->whereDate('available_from', '<=', Carbon::now())->whereDate('valid_till', '>=', Carbon::now())
->orderBy('id', 'desc')
->get();


$newObj = new Coupon;
if (count($row) <= 0) {

$newObj->flag = false;
return $newObj;
}
else{

foreach($row as $row1){
	if($row1->restaurant_id == null){

		$newObj->flag = true;
		foreach ($row1 as $column => $value) {
			$newObj->{$column} = $value;
		}
		return $newObj;
	}
	else{

		if($row1->restaurant_id == $restaurantID){
			$newObj->flag = true;
			foreach ($row1 as $column => $value) {
				$newObj->{$column} = $value;
			}
			return $newObj;
		}
	}
}
$newObj->flag = false;

return $newObj;
}


// if($row->restaurant_id != null){
// 	if($row->restaurant_id != $restaurantID){
// 			$newObj->flag = false;
// 			return $newObj;
// 	}
// }



	}

	public function eligibleItems ($restaurantID, $items) {

		$Restaurant = Restaurant::find($restaurantID);

		$eligibleItems = [];

		if ($this->menu_id) {
			foreach ($items as $item) {
				$item = (object) $item;
				if (Restaurant::isFoodMatchesMenu($item->FoodID, $this->menu_id)) {
					$eligibleItems[] = $item;
				}
			}
		} else {
			$eligibleItems = $items;
		}

		return $eligibleItems;
	}

	public function itemTotal ($restaurantID, $items) {
		
		$Restaurant 	= Restaurant::find($restaurantID);
		$eligibleItems 	= $this->eligibleItems($restaurantID, $items);

		$itemTotal = 0;
		foreach ($eligibleItems as $item) {
			$itemTotal += $Restaurant->itemTotal((object) $item);
		}
		return $itemTotal;
	}

	public function usedCount () {
		return DB::table('requests')
			->where('coupon_code', $this->code)
			->whereNotIn('status', [9, 10, 11, -1, -2,12,13,14])->count();
	}

	public function usedCountOfCustomer ($userID) {
		return DB::table('requests')
		->where('coupon_code', $this->code)
		->where('user_id', $userID)
		->whereNotIn('status', [9, 10, 11, -1, -2,12,13,14])->count();
	}

	public function calcOfferAmount ($itemAmount) {
		
//check min amount 
				

		if ($this->offer_type == 1) {
			return $itemAmount > $this->value ? $this->value : $itemAmount;
		}

		$offerAmount = xPercentageOfY($this->value, $itemAmount);
		
		if ($offerAmount >= $itemAmount) {
			return $itemAmount;
		}

		return $offerAmount > $this->max_amount ? $this->max_amount : $offerAmount;
	}
	public function minAmount ($itemAmount) {

		if($this->min_amount < $itemAmount){
			return 0;
		}else{$v= $this->min_amount - $itemAmount;
			//return 'Add RS.'.$v.' to get this offer';
			return $v;
		}
	}

}