<?php

namespace App\Http\Controllers;


use Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Log;
use App\Http\Controllers\ReturnData;

class Restaurant extends Controller
{

    static $table = 'restaurants';
    static $table_food = 'food_list';
    static $table_size = 'foodlist_foodquantity';
    static $table_addon = 'foodlist_addons';
    static $table_addonMaster = 'add_ons';
    static $table_menu = 'menu';

    public function __constructor() {}

    public static function find(int $id)
    {

        $newObj = new Restaurant;
        $newObj->RestaurantID = $id;
        $newObj->IsTaxInclusive = (int) env('IS_TAX_INCLUSIVE');


        $row = DB::table(self::$table)->where('id', $id)->first();
        if (!$row) {
            $newObj->flag = false;
            return $newObj;
        }

        $newObj->flag = true;

        foreach ($row as $column => $value) {

            $newObj->{$column} = $value;
        }

        return $newObj;
    }

    public static function isExist(int $id)
    {

        return (bool) DB::table(self::$table)->where('id', $id)->first();
    }

    public static function isOpenAt(int $id, string $date)
    {
        return self::isOpen($id, $date);
    }

    public static function isOpen(int $id, $date = null)
    {
        // if (env('IS_RESTAURANT_OPEN')) {
        //     return 1; 
        // }

        $restaurant = DB::table('restaurants')->where('id', $id)->first();

        if (!$restaurant) {
            return 0;
        }

        if (!$date && !$restaurant->is_open) {
            return 0;
        }

        $holidays = explode(',', $restaurant->holidays);

        $is_open = 0;

        $time2Check = $date ?? date('Y-m-d H:i:s');

        $current_time = date('H:i:s', strtotime($time2Check));
        $date = date("Y-m-d", strtotime($time2Check));
        $weekday = date('w', strtotime($time2Check));

        $weekday = $weekday == 0 ? 7 : $weekday;


        if (in_array($weekday, $holidays)) {
            return 0;
        }

        $opening_var = 'opening_time_' . $weekday;
        $closing_var = 'closing_time_' . $weekday;

        $opening_second_var = 'opening_second_time_' . $weekday;
        $closing_second_var = 'closing_second_time_' . $weekday;

        if ($restaurant->status == 0) {
            return 0;
        }

        if (
            !isset($restaurant->$opening_var) || empty($restaurant->$opening_var) ||
            !isset($restaurant->$closing_var) || empty($restaurant->$closing_var)
        ) {
            return 0;
        }

        if (
            (strtotime($restaurant->$opening_var) <= strtotime($current_time)) &&
            (strtotime($restaurant->$closing_var) >= strtotime($current_time))
        ) {
            $is_open = 1;
        }

        if ($restaurant->$opening_var == $restaurant->$closing_var) {
            $is_open = 1;
        }

        if (
            isset($restaurant->$opening_second_var) && !empty($restaurant->$opening_second_var) &&
            isset($restaurant->$closing_second_var) && !empty($restaurant->$closing_second_var)
        ) {

            if ($restaurant->$opening_second_var == $restaurant->$closing_second_var) {
                $is_open = 1;
            }

            if (
                (strtotime($restaurant->$opening_second_var) <= strtotime($current_time)) &&
                (strtotime($restaurant->$closing_second_var) >= strtotime($current_time))
            ) {
                $is_open = 1;
            }
        }

        return $is_open;
    }

    public static function isAvailableNow(int $id)
    {

        if (!self::isExist($id)) {
            return new ReturnData(false, 'Store not found');
        }

        if (!self::isOpen($id)) {
            return new ReturnData(false, 'Store is not available now');
        }

        return new ReturnData(true, 'Store OK');
    }

    public function isProductSetsAvailable($items)
    {

        if (empty($items) || !is_array($items)) {
            return new ReturnData(false, 'Item list is empty');
        }

        $foodIDs    = array_column($items, 'FoodID');
        $food       = $this->isItemsExist($foodIDs);
        if (!$food->flag) {
            return $food;
        }

        foreach ($items as $eachItem) {

            if ($eachItem->Quantity <= 0) {
                return new ReturnData(false, 'Quantity should be greater than 0');
            }

            // $size = $this->isSizeExist($eachItem->FoodID, $eachItem->SizeID);

            // if ($eachItem->SizeID && !$size->flag) {
            //     return $size;
            // }

            // foreach ($eachItem as $eachAddonID) {
            //     $addon = $this->isAddonsExist($eachItem->FoodID, $eachAddonID);
            //     if (!$addon->flag) {
            //         return $addon;
            //     }
            // }
        }

        return new ReturnData(true, 'Items OK');
    }

    public function isItemsExist(array $foodIDs): ReturnData
    {

        // $foundItems = DB::table(self::$table_food)->select('id')
        //     ->where('restaurant_id', $this->RestaurantID)->whereIn('id', $foodIDs)->pluck('id')->toArray();

        $foundItems = DB::table(self::$table_food)
            ->select('id')
            ->whereIn('id', $foodIDs)
            ->pluck('id')
            ->toArray();

        $notFoundItems = array_diff($foodIDs, $foundItems);

        if ($notFoundItems) {
            return new ReturnData(false, 'Food IDs not found: ' . implode(',', $notFoundItems));
        }

        return new ReturnData(true, 'Food OK');
    }

    public function isAddonsExist(int $foodID, int $addonID): ReturnData
    {

        $addon = DB::table(self::$table_addon)->select('id')
            ->where('foodlist_id', $foodID)->where('addons_id', $addonID)->first();

        return isset($addon->id) ? new ReturnData(true, 'Addon OK') : new ReturnData(false, "Addon ID not found ($foodID.$addonID)");
    }

    public function isSizeExist(int $foodID, ?int $sizeID): ReturnData
    {

        $size = DB::table(self::$table_size)->select('id')
            ->where('foodlist_id', $foodID)->where('foodquantity_id', $sizeID)->first();

        return isset($size->id) ? new ReturnData(true, 'Size OK') : new ReturnData(false, "Size ID not found ($foodID.$sizeID)");
    }

    private function getField($field)
    {
        $row = DB::table(self::$table)->select($field)->where('id', $this->RestaurantID)->first();
        return $row->$field ?? null;
    }

    // private function getFoodField(int $id, $field)
    // {
    //     $row = DB::table(self::$table_food)->select($field)->where('id', $id)->first();
    //     return $row->$field ?? null;
    // }

     private function getFoodField(int $id, $field)
    {
        // If the requested field is NOT 'price', proceed as usual
        if ($field !== 'price') {
            $row = DB::table(self::$table_food)->select($field)->where('id', $id)->first();
            return $row->$field ?? null;
        }

        // Handle special logic for 'price' field

        // Step 1: Get the restaurant's city (district_id) using RestaurantID
        $restaurant = DB::table(self::$table)->select('city')->where('id', $this->RestaurantID)->first();
        if (!$restaurant) return null;

        $districtId = $restaurant->city;

        // Step 2: Get all matching product_pricing_id from food_list_pricing_district where district_id matches
        $pricingDistricts = DB::table('food_list_pricing_district')
            ->select('product_pricing_id')
            ->where('district_id', $districtId)
            ->get();

        if ($pricingDistricts->isEmpty()) return null;

        $pricingIds = $pricingDistricts->pluck('product_pricing_id')->toArray();

        // Step 3: Get price from food_list_pricing where id IN product_pricing_id AND product_id = $id
        $priceRow = DB::table('food_list_pricing')
            ->select('price')
            ->whereIn('id', $pricingIds)
            ->where('product_id', $id)
            ->first();

        return $priceRow->price ?? null;
    }

    private function getSizeFields($foodID, $sizeID, $fields)
    {
        $row = DB::table(self::$table_size)->select($fields)->where('foodlist_id', $foodID)->where('foodquantity_id', $sizeID)->first();
        return $row;
    }

    private function getAddonField(int $id, $field)
    {
        $row = DB::table(self::$table_addonMaster)->select($field)->where('id', $id)->first();
        return $row->$field ?? null;
    }

    public function isDeliveryMethodAllowed(string $method): bool
    {

        $deliveryMethods = $this->getField('delivery_type');
        $deliveryMethods = json_decode($deliveryMethods);

        $methodNumber = 0;

        switch ($method) {
            case 'delivery':
                $methodNumber = 1;
                break;
            case 'takeaway':
                $methodNumber = 2;
                break;
            case 'dining':
                $methodNumber = 3;
                break;
        }

        return in_array($methodNumber, $deliveryMethods);
    }

    public function itemTotal($Item): float
    {

        $foodPrice      = $this->foodPrice($Item->FoodID);
        $packagingPrice = $this->foodPackagingPrice($Item->FoodID);
        $tax            = $this->foodTax($Item->FoodID);
        $sizePrice      = $this->foodSizePrice($Item->FoodID, $Item->SizeID);
        $addonsPrice    = $this->foodAddonsPrice($Item->FoodID, $Item->AddonIDs, $Item->addons_quantity);

        $eachPrice = $sizePrice > 0 ? $sizePrice : $foodPrice;
        $eachPrice += $addonsPrice;
        if (!$this->IsTaxInclusive) {
            // $eachPrice += $tax;
            //// Add tax properly
        }

        // $eachPrice += $packagingPrice;

        $itemTotal = $eachPrice * $Item->Quantity;

        return $itemTotal;
    }

    public function foodPrice($foodID): float
    {

        return $this->getFoodField($foodID, 'price');
    }

    public function foodPackagingPrice($foodID): float
    {

        return $this->getFoodField($foodID, 'packaging_charge');
    }

    public function foodTax($foodID): float
    {

        return $this->getFoodField($foodID, 'tax');
    }

    public function foodSizePrice($foodID, $sizeID): float
    {

        $row = $this->getSizeFields($foodID, $sizeID, ['price', 'is_default']);

        if (!isset($row->is_default)) {
            return 0;
        }

        return $row->price;
    }

    public function addonsDetailsOfItem($Item)
    {

        $addonsDetails = [];

        foreach ($Item->AddonIDs as $addonID) {

            $row = DB::table(self::$table_addonMaster)->where('id', $addonID)->first();

            $addonsDetails[] = $row;
        }
        for ($i = 0; $i < count($addonsDetails); $i++) {
            $addonsDetails[$i]->quantity = $Item->addons_quantity[$i];
        }

        return $addonsDetails;
    }

    public function foodAddonsPrice($foodID, $addonIDs, $qty): float
    {

        $price = 0;
        for ($i = 0; $i < count($addonIDs); $i++) {
            $addon = DB::table(self::$table_addonMaster)->where('id', $addonIDs[$i])->first();
            if ($qty[$i] > 1) {
                $price += $addon->price * $qty[$i];
            } else {
                $price += $addon->price;
            }
        }
        return $price;
    }

    public static function nearbyRestaurantIDs($lat, $lng)
    {

        $restaurants = DB::table(self::$table)->where('status', 1)->select('id', 'lat', 'lng', 'GeofenceRadius')->get();
        $availableRestIDs = [];

        foreach ($restaurants as $restaurant) {
            $geofenceRadius = (float) ($restaurant->GeofenceRadius ?? DEFAULT_RADIUS);
            $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

            if ($distance <= $geofenceRadius) {
                $availableRestIDs[] = $restaurant->id;
            }
        }

        return $availableRestIDs;
    }

    public static function isFoodMatchesMenu($foodID, $menuID)
    {
        $flag = DB::table(self::$table_food)->where('id', $foodID)->where('menu_id', $menuID)->exists();
        return $flag;
    }

    public static function nearDistance($lat, $lng)
    {

        $restaurants = DB::table(self::$table)->where('status', 1)->select('id', 'lat', 'lng', 'GeofenceRadius')->get();
        $availableRestIDs = [];
        foreach ($restaurants as $restaurant) {
            $geofenceRadius = DEFAULT_RES_RADIUS;
            $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

            if ($distance <= $geofenceRadius) {
                $availableRestIDs[] = $restaurant->id;
            }
        }

        return $availableRestIDs;
    }
    public static function nearDistance_business_rest($lat, $lng, $id)
    {
        $business = DB::table('business_type')->where('status', 1)->where('id', $id)->get();

        $availableRestIDs = [];
        // foreach ($business as $b) {

        $restaurants = DB::table(self::$table)->where('status', 1)->select('id', 'lat', 'lng', 'GeofenceRadius')->get();



        foreach ($restaurants as $restaurant) {
            $geofenceRadius = $restaurant->GeofenceRadius;

            $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

            if ($distance <= $geofenceRadius) {
                $availableRestIDs[] = [
                    'id' => $restaurant->id,
                    'distance' => $distance
                ];
            }
        }
        // }
        $price = array_column($availableRestIDs, 'distance');

        array_multisort($price, SORT_ASC, $availableRestIDs);

        $rt[] = $availableRestIDs[0]['id'];
        return $rt;
    }
    public static function nearDistance_business($lat, $lng, $id)
    {
        // $business = DB::table('business_type')->where('status', 1)->where('layout_id',$id)->get();

        $restaurants = DB::table(self::$table)->where('status', 1)->where('business_type', $id)->select('id', 'lat', 'lng', 'GeofenceRadius')->get();

        $availableRestIDs = [];
        foreach ($restaurants as $restaurant) {
            $geofenceRadius = DEFAULT_RES_RADIUS;
            $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

            if ($distance <= $geofenceRadius) {
                $availableRestIDs[] = $restaurant->id;
            }
        }


        return $availableRestIDs;
    }
    public static function nearDistance_business_store($lat, $lng, $id)
    {

        $business = DB::table('business_type')->where('status', 1)->where('layout_id', '!=', $id)->get();

        // $restaurants = DB::table(self::$table)->where('status', 1)->where('business_type','!=',$id)->select('id', 'lat', 'lng', 'GeofenceRadius','business_type')->get();
        $availableRestIDs = [];
        foreach ($business as $b) {
            $restaurants = DB::table(self::$table)->where('status', 1)->where('business_type', $b->id)->select('id', 'lat', 'lng', 'GeofenceRadius', 'business_type')->get();

            foreach ($restaurants as $restaurant) {

                if (isset($business1->layout_id) && $business1->layout_id == 3) {
                } else {
                    $geofenceRadius = DEFAULT_RES_RADIUS;
                    $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

                    if ($distance <= $geofenceRadius) {
                        $availableRestIDs[] = $restaurant->id;
                    }
                }
            }
        }

        return $availableRestIDs;
    }
    public static function nearbrandDistance($lat, $lng)
    {

        $restaurants = DB::table(self::$table)->where('status', 1)->select('id', 'lat', 'lng', 'GeofenceRadius')->get();
        $availableRestIDs = [];

        foreach ($restaurants as $restaurant) {
            $geofenceRadius = DEFAULT_RADIUS;
            $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

            if ($distance <= $geofenceRadius) {
                $availableRestIDs[] = $restaurant->id;
            }
        }

        return $availableRestIDs;
    }
    public static function nearbrandDistanceBusiness($lat, $lng, $id)
    {

        $restaurants = DB::table(self::$table)->where('status', 1)->where('business_type', $id)->select('id', 'lat', 'lng', 'GeofenceRadius')->get();
        $availableRestIDs = [];

        foreach ($restaurants as $restaurant) {
            $geofenceRadius = DEFAULT_RADIUS;
            $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

            if ($distance <= $geofenceRadius) {
                $availableRestIDs[] = $restaurant->id;
            }
        }

        return $availableRestIDs;
    }

    public static function getrestdistance($lat, $lng, $rest_id)
    {
        $restaurants = DB::table(self::$table)->where('id', $rest_id)->where('status', 1)->select('id', 'lat', 'lng', 'GeofenceRadius')->first();
        $distance = vincentyGreatCircleDistance($lat, $lng, $restaurants->lat, $restaurants->lng);
        return $distance;
    }
    public static function moreDistance_business($lat, $lng, $id)
    {

        $restaurants = DB::table(self::$table)->where('status', 1)->where('business_type', $id)->select('id', 'lat', 'lng', 'GeofenceRadius')->get();

        $availableRestIDs = [];
        $availabledistance = array();

        foreach ($restaurants as $restaurant) {

            $geofenceRadius = (float) ($restaurant->GeofenceRadius ?? DEFAULT_RADIUS);
            $more = DEFAULT_RES_RADIUS + 0.01;

            $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

            $restaurant->distance = $distance;

            if ($distance >= $more && $distance <= $geofenceRadius) {

                $availabledistance[] = $restaurant;
                $availableRestIDs[] = $restaurant->id;
            }
        }

        $data = collect($availabledistance)->sortBy('distance')->toArray();
        //print_r($data);


        return $data;
    }
    public static function moreDistance_business_rest($lat, $lng, $id)
    {

        $business = DB::table('business_type')->where('status', 1)->where('layout_id', $id)->get();



        $availableRestIDs = [];
        $availabledistance = array();

        foreach ($business as $b) {
            $restaurants = DB::table(self::$table)->where('status', 1)->where('business_type', $b->id)->select('id', 'lat', 'lng', 'GeofenceRadius')->get();

            foreach ($restaurants as $restaurant) {

                $geofenceRadius = (float) ($restaurant->GeofenceRadius ?? DEFAULT_RADIUS);
                $more = DEFAULT_RES_RADIUS + 0.01;

                $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

                $restaurant->distance = $distance;

                if ($distance >= $more && $distance <= $geofenceRadius) {

                    $availabledistance[] = $restaurant;
                    $availableRestIDs[] = $restaurant->id;
                }
            }
        }

        $data = collect($availabledistance)->sortBy('distance')->toArray();
        //print_r($data);


        return $data;
    }
    public static function moreDistance_business_new($lat, $lng, $id)
    {

        $business = DB::table('business_type')->where('status', 1)->where('layout_id', '!=', $id)->get();


        $availableRestIDs = [];
        $availabledistance = array();

        foreach ($business as $b) {
            $restaurants = DB::table(self::$table)->where('status', 1)->where('business_type', $b->id)->select('id', 'lat', 'lng', 'GeofenceRadius')->get();

            foreach ($restaurants as $restaurant) {

                $geofenceRadius = (float) ($restaurant->GeofenceRadius ?? DEFAULT_RADIUS);
                $more = DEFAULT_RES_RADIUS + 0.01;

                $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

                $restaurant->distance = $distance;

                if ($distance >= $more && $distance <= $geofenceRadius) {

                    $availabledistance[] = $restaurant;
                    $availableRestIDs[] = $restaurant->id;
                }
            }
        }

        $data = collect($availabledistance)->sortBy('distance')->toArray();
        //print_r($data);


        return $data;
    }
    public static function moreDistance_business_cat($lat, $lng, $id)
    {




        $availableRestIDs = [];
        $availabledistance = array();


        $restaurants = DB::table(self::$table)->where('status', 1)->where('business_type', $id)->select('id', 'lat', 'lng', 'GeofenceRadius')->get();

        foreach ($restaurants as $restaurant) {

            $geofenceRadius = (float) ($restaurant->GeofenceRadius ?? DEFAULT_RADIUS);
            $more = DEFAULT_RES_RADIUS + 0.01;

            $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

            $restaurant->distance = $distance;

            if ($distance >= $more && $distance <= $geofenceRadius) {

                $availabledistance[] = $restaurant;
                $availableRestIDs[] = $restaurant->id;
            }
        }




        $data = collect($availabledistance)->sortBy('distance')->toArray();
        //print_r($data);


        return $data;
    }
    public static function moreDistance($lat, $lng)
    {

        $restaurants = DB::table(self::$table)->where('status', 1)->select('id', 'lat', 'lng', 'GeofenceRadius')->get();

        $availableRestIDs = [];
        $availabledistance = array();

        foreach ($restaurants as $restaurant) {

            $geofenceRadius = (float) ($restaurant->GeofenceRadius ?? DEFAULT_RADIUS);
            $more = DEFAULT_RES_RADIUS + 0.01;
            $distance = vincentyGreatCircleDistance($lat, $lng, $restaurant->lat, $restaurant->lng);

            $restaurant->distance = $distance;

            if ($distance >= $more && $distance <= $geofenceRadius) {

                $availabledistance[] = $restaurant;
                $availableRestIDs[] = $restaurant->id;
            }
        }

        $data = collect($availabledistance)->sortBy('distance')->toArray();
        //print_r($data);


        return $data;
    }


    public static function callRestaurant($phone)
    {
        $restPhone = sprintf("%03d", $phone);
        $data = [
            'From' => $restPhone,
            'CallerId' => '08069459183',
            'Url' => 'http://my.exotel.com/bytesflow1/exoml/start_voice/458452'
        ];

        $url = 'https://fc0b30177b930559d6d6181bc1f1e9eacc16c5ab547caefd:21cab75346ad696dfc9356a2c9558dcc35e15f637bfb7762@api.exotel.com/v1/Accounts/bytesflow1/Calls/connect';
        $headers = array();
        $curl = cURL::url($url)->params($data)->post();
    }
}
