<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Mailer;
use App\Model\Users;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Mockery\CountValidator\Exception;
use Cartalyst\Stripe\Stripe;
use App\Http\Controllers\PaymentGateway\EasyCheckout;
use App\Http\Controllers\Order;
use App\Http\Controllers\Restaurant;
use Hash;
use Illuminate\Support\Facades\Storage;
use Socialite;
use File;

use App\Http\Controllers\cURL;

use Artisan;

class UserController extends BaseController
{
    public function home_page(Request $request)
    {
        $restaurants = $this->restaurants;
        $offers_banner = $this->banner;
        // dd(session()->get('latitude'));
        if (session()->get('latitude') == 0) {
            // dd("if");
            $lat = $request->lat ? $request->lat : 0;
            $lng = $request->lng ? $request->lng : 0;
            // $lat = $request->lat ? $request->lat : env('DEFAULT_LATITUDE');
            // $lng = $request->lng ? $request->lng : env('DEFAULT_LONGITUDE');
            $request->session()->put('latitude',  $lat);
            $request->session()->put('longitude', $lng);
        } else {
            //   dd("else");
            $lat = session()->get('latitude');
            $lng = session()->get('longitude');
        }
        $popular = get_nearby_restaurants($lat, $lng, $id = [0], $limit = 5);
        $banner = get_nearby_banners($lat, $lng); //dd($banner);
        $cuisines = get_nearby_cuisines($lat, $lng);
        $cities = get_nearby_cities($lat, $lng);
        $categories = get_category_by_rank(1);
        $user = Auth::guard('user')->user();
        $user_id = $user ? $user->id : 0;
        $amount = 0;
        $quantity = 0;
        $foodlist = $this->foodlist;
        $cart = $this->cart;
        $cart = $cart::where('user_id', $user_id)->get();
        foreach ($cart as $key) {
            $food_detail = $foodlist::where('id', $key->food_id)->first();
            $amount = $amount + ($food_detail->price * $key->quantity);
            $quantity = $quantity + $key->quantity;
        }
        $food_cart[] = array(
            'amount' => $amount,
            'quantity' => $quantity,
        );
        return view('user.home', compact('popular', 'banner', 'cuisines', 'cities', 'categories', 'cart', 'food_cart'));
    }
    public function subscribe(Request $request)
    {
        try {
            if (!DB::table('subscriber_list')->where('email', $request->email)->first()) {
                DB::table('subscriber_list')->insert(['email' => $request->email]);
            }
            return response()->json(['message' => 'subscribed successfully', 'status' => true]);
        } catch (Exception $ex) {
            return response()->json(['message' => 'subscription failed', 'status' => true]);
        }
    }

    public function get_default_address(Request $request)
    {

        $user_id = $request->user_id;

        // $user_id = $request->header('authId');
        $delivery_address = $this->deliveryaddress;

        $data = $delivery_address::where('user_id', $user_id)->where('is_default', 1)->first();
        if ($data->address != "") {
            $decodedText = html_entity_decode($data->address);
            $data->address = $decodedText;
        }
        if ($data) {
            $response_array = array('status' => true, 'data' => $data);
        } else {
            $response_array = array('status' => false, 'message' => 'No address found');
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function set_delivery_address(Request $request)
    {
        $user_id = $request->id;
        $address_id = $request->address_id;
        $address = $this->deliveryaddress::where('user_id', $user_id)->where('id', $address_id)->first();
        if ($address) {
            $data = $address->update(['is_default' => 1]);

            $this->deliveryaddress::where('user_id', $user_id)
                ->where('id', '!=', $address_id)
                ->update(['is_default' => 0]);

            $def_address = $this->deliveryaddress::where('user_id', $user_id)->where('id', $address_id)->first();


            $response_array = array('status' => true, 'message' => "Address changed Successfully", 'address' => $def_address);
            $response = response()->json($response_array, 200);
            return $response;
        } else {

            $response_array = array('status' => false, 'message' => "Address changed Failed");
            $response = response()->json($response_array, 200);
            return $response;
        }
    }
    public function new_address(Request $request)
    {
        $lat =  session()->get('latitude');
        $lng =  session()->get('longitude');
        return view('includes.user_add_address', compact('lat', 'lng'));
    }

    public function get_delivery_address(Request $request)
    {

        $user_id = $request->user_id;

        // $user_id = $request->header('authId');
        $delivery_address = $this->deliveryaddress;
        $data = $delivery_address::where('user_id', $user_id)->get();
        foreach ($data as $datas) {
            if ($datas->address != "") {
                $decodedText = html_entity_decode($datas->address);
                $datas->address = $decodedText;
            }
        }
        if (count($data) != 0) {
            // type - 1 home, 2 work, 3 others
            $message = "User Address";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $data]);
            return $response_Array;
        } else {
            $message = "No address found";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $data]);
            return $response_Array;
        }
    }

    public function offers_page(Request $request)
    {

        $user_id = $request->authId;

        $delivery_address = $this->deliveryaddress;
        $data = $delivery_address::where('user_id', $user_id)->get();
        $dt = Carbon::now();
        $promocode_list_new = $this->promocode->where('status', '1')->whereRaw('"' . $dt . '" between `available_from` and `valid_till`')->get();
        $promocode_list = array();

        foreach ($promocode_list_new as $data) {

            //print_r($promocode);
            if ($data->restaurant_id != "") {
                $restaurant_name = DB::table('restaurants')->where('id', $data->restaurant_id)->select('restaurant_name')->get();
                $promocode_list[] = array(
                    'id' => $data->id,
                    'code' => $data->code,
                    'restaurant_id' => $data->restaurant_id,
                    'title' => $data->title,
                    'description' => $data->description,
                    'offer_type' => $data->offer_type,
                    'value' => $data->value,
                    'max_amount' => $data->max_amount,
                    'use_per_customer' => $data->use_per_customer,
                    'total_use' => $data->total_use,
                    'restaurant_name' => $restaurant_name[0]->restaurant_name,
                );
            } else {
                $promocode_list[] = array(
                    'id' => $data->id,
                    'code' => $data->code,
                    'restaurant_id' => $data->restaurant_id,
                    'title' => $data->title,
                    'description' => $data->description,
                    'offer_type' => $data->offer_type,
                    'value' => $data->value,
                    'max_amount' => $data->max_amount,
                    'use_per_customer' => $data->use_per_customer,
                    'total_use' => $data->total_use,
                    'restaurant_name' => "",
                );
            }
        }

        $message = "";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $promocode_list]);
        return $response_Array;
    }

    public function add_delivery_address(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'address' => 'required',
                'lat' => 'required',
                'lng' => 'required',
                'type' => 'required', // Type -1 Home, 2- Office, 3 -Others
                'landmark' => 'required',
                'flat_no' => 'required',
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        } else {
            $user_id = $request->user_id;

            // $user_id = $request->header('authId');
            // $address = $request->address;
            $lat = $request->lat;
            $lng = $request->lng;
            $type = $request->type;
            $flat_no = $request->flat_no;
            $landmark = $request->landmark;
            $delivery_address = $this->deliveryaddress;

            // $check_for_default_address = $delivery_address::where('user_id', $user_id)->where('is_default', 1)->get();
            $update = DB::table('delivery_address')->where('user_id', $user_id)->where('is_default', 1)->update([
                'is_default' => 0
            ]);
            // if (count($check_for_default_address) != 0) {
            //     $is_default = 0;
            // } else {
            //     $is_default = 1;
            // }
            $originalText = $request->address;
            $convertedText = mb_convert_encoding($originalText, 'HTML-ENTITIES', 'UTF-8');
            // $decodedText = html_entity_decode($convertedText);
            //    dd($decodedText);
            $address = $convertedText;
            $insert_data = array();

            $insert_data[] = array(
                'user_id' => $user_id,
                'address' => $address,
                'lat' => $lat,
                'lng' => $lng,
                'type' => $type,
                'flat_no' => $flat_no,
                'landmark' => $landmark,
                'is_default' => 1,
            );

            $id = $delivery_address::insert($insert_data);
            $data = $delivery_address::where('user_id', $user_id)->orderBy('id', 'desc')->first();
            $message = "Address added Successfully";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }
    }

    public function get_filter_list($filter_type)
    {
        if ($filter_type == 1) // filter_type =1 - Cusines table else relevance table
        {
            $cuisines = $this->cuisines;
            $data = $cuisines::get();
        } else {
            $data = DB::table('relevance')->get();
        }

        if (count($data) != 0) {
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'message' => 'No data found']);
        }
    }

    public function get_banners(Request $request)
    {
        $data = DB::table('offers_banner')->orderBy('position', 'asc')->get();

        if (count($data) != 0) {
            return response()->json(['status' => true, 'data' => $data, 'base_url' => BASE_URL . UPLOADS_PATH]);
        } else {
            return response()->json(['status' => false, 'message' => 'No data found']);
        }
    }

    public function get_relevance_restaurant(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            array(
                'is_pureveg' => 'required',
                'is_offer' => 'required',
                'lat' => 'required',
                'lng' => 'required',

            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            if ($request->header('authId') != "") {
                $user_id = $request->header('authId');
            } else {
                $user_id = $request->authId;
            }
            $restaurants = $this->restaurants;

            $size_cuisines = sizeof($request->cuisines);
            // echo $size_cuisines; exit;

            $source_lat = $request->lat;
            $source_lng = $request->lng;
            $cuisines = $request->cuisines;

            $query = $restaurants->with('Cuisines')->where('status', 1)
                ->select('restaurants.*')
                ->selectRaw("(6371 * acos(cos(radians(" . $source_lat . "))* cos(radians(`lat`))
                            * cos(radians(`lng`) - radians(" . $source_lng . ")) + sin(radians(" . $source_lat . "))
                            * sin(radians(`lat`)))) as distance")
                ->having('distance', '<=', DEFAULT_RADIUS)
                ->orderBy('distance');

            $query = $query->when(($request->is_offer == 1),
                function ($q) {
                    return $q->where('offer_amount', '!=', 0);
                }
            );

            $query = $query->when(($size_cuisines != 0),
                function ($q) use ($cuisines) {
                    $q->wherehas('Cuisines', function ($q) use ($cuisines) {
                        return $q->whereIn('cuisines.id', $cuisines);
                    });
                }
            );



            $data = $query->get();

            $restaurant_list = array();
            foreach ($data as $d) {

                $rcuisines = array();
                $i = 0;
                foreach ($d->Cuisines as $r_cuisines) {
                    if ($i < 2) {
                        $rcuisines[] = array(
                            'name' => $this->secondLanguage_user($r_cuisines->name, $r_cuisines->secondary_name),
                        );
                        $i = $i + 1;
                    }
                }

                $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d->id)->get();
                if (count($check_favourite) != 0) {
                    $is_favourite = 1;
                } else {
                    $is_favourite = 0;
                }
                //calculate restaurant open time
                $is_open = restaurant_is_open($d->id);

                if (sizeof($rcuisines) > 0) {
                    $restaurant_list[] = array(
                        'id' => $d->id,
                        'name' => $this->secondLanguage_user($d->restaurant_name, $d->restaurant_secondary_name),
                        'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image,
                        'discount' => $d->discount,
                        'rating' => $d->rating,
                        'is_open' => $is_open, // 1- Open , 0 - Close
                        'cuisines' => $rcuisines,
                        'travel_time' => $d->estimated_delivery_time,
                        'price' => '',
                        'is_favourite' => $is_favourite,
                    );
                }
            }

            if (sizeof($restaurant_list) > 0) {

                $response_array = array('status' => true, 'restaurants' => $restaurant_list);
            } else {
                $response_array = array('status' => false, 'message' => 'No Data Found');
            }
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_menu(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'restaurant_id' => 'required',
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $menu = $this->menu;
            $foodlist = $this->foodlist;
            $restaurant_id = $request->restaurant_id;
            $menu_list = array();

            $check = $menu::where('restaurant_id', $restaurant_id)->get();

            foreach ($check as $c) {
                $food_count = $foodlist::where('menu_id', $c->id)->count();

                $menu_list[] = array(
                    'menu_id' => $c->id,
                    'menu_name' => $c->menu_name,
                    'food_count' => $food_count,
                );
            }

            $response_array = array('status' => true, 'menus' => $menu_list);
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_nearby_restaurant(Request $request)
    {
        // dd($request);
        $restaurant_list = array();
        // to hide loc popup
        if (isset($request->loc_flag)) {
            $request->session()->put('location_ses', 1);
        }
        $request->session()->put('add', $request->add);
        $request->session()->put('lat', $request->lat);
        $request->session()->put('lng', $request->lng);
        $request->session()->put('schedule_date', null);

        // dd(session()->get('lat'));dd("k");
        // dd($request->add);
        $validator = Validator::make(
            $request->all(),
            [
                'keyword' => 'required',
                'lat' => 'required',
                'lng' => 'required',
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
            //return view('user.restaurant_list')->with(['near_by_cities' => [], 'cuisines' => [], 'restaurant_list' => []]);//, compact('restaurant_list', 'cuisines', 'near_by_cities'));
        }

        if (empty($request->lat) || empty($request->lng)) {


            $sess_lat = $request->session()->get('latitude');
            $sess_lng = $request->session()->get('longitude');
            if (!empty($sess_lat) && !empty($sess_lng)) {
                return redirect("search_nearby_restaurant?keyword=$request->keyword&lat=$sess_lat&lng=$sess_lng");
            }
        }

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $source_lat = $request->lat;
        $source_lng = $request->lng;

        if (!empty($source_lat) && !empty($source_lng)) {
            $near_by_cities = get_nearby_cities($source_lat, $source_lng);
            $cuisines = get_all_cuisines(); //get_nearby_cuisines($source_lat, $source_lng);
        } else {
            $near_by_cities = [];
            $cuisines = get_all_cuisines();
        }


        $restaurants = $this->restaurants;

        // if (!empty($source_lat) && !empty($source_lng)) {
        //     //geofencing for restaurant
        //     $data = $restaurants->with('Cuisines')->where('status', 1)
        //         ->select('restaurants.*')
        //         ->selectRaw("(6371 * acos(cos(radians(" . $source_lat . "))* cos(radians(`lat`))
        //                     * cos(radians(`lng`) - radians(" . $source_lng . ")) + sin(radians(" . $source_lat . "))
        //                     * sin(radians(`lat`)))) as distance")
        //         ->having('distance', '<=', DEFAULT_RADIUS)
        //         ->orderBy('distance')
        //         ->get();
        // } else {

        //     $data = $restaurants->with('Cuisines')->where('status', 1)
        //     ->select('restaurants.*')
        //     ->where('restaurants.restaurant_name', 'LIKE', '%'.$request->keyword.'%')
        //     ->get();
        // }

        $availableRestIDs = [];

        $all_restaurant = $restaurants->where('status', 1)->select('id', 'city', 'lat', 'lng', 'GeofenceRadius')->get();
        foreach ($all_restaurant as $each_restaurant) {
            if (!empty($source_lat) && !empty($source_lng)) {
                $geofenceRadius = (float) ($each_restaurant->GeofenceRadius ?? DEFAULT_RADIUS);
                $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $each_restaurant->lat, $each_restaurant->lng);
                if ($distance <= $geofenceRadius) {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            } else if (!empty($request->city_id)) {
                if ($each_restaurant->city == $request->city_id) {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            } else {
                $availableRestIDs[] = $each_restaurant->id;
            }
        }

        $data = $restaurants->with('Cuisines')->where('status', 1)
            ->select('restaurants.*')
            ->where('restaurants.restaurant_name', 'LIKE', '%' . $request->keyword . '%')
            ->get();

        $restIDs = [];
        foreach ($data as $key => $value) {
            if (in_array($value->id, $availableRestIDs)) {
                $restIDs[] = $value->id;
            }
        }

        $data_foodlist = DB::table('food_list')
            ->join('category', 'category.id', '=', 'food_list.category_id')
            ->join('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
            ->where('name', 'like', '%' . $request->keyword . '%')
            ->OrWhere('category_name', 'like', '%' . $request->keyword . '%')
            ->select('food_list.*', 'restaurants.restaurant_name', 'restaurants.image', 'restaurants.lat', 'restaurants.lng')
            ->get();

        foreach ($data_foodlist as $value) {
            if (!in_array($value->restaurant_id, $restIDs) && in_array($value->restaurant_id, $availableRestIDs)) {
                $restIDs[] = $value->restaurant_id;
            }
        }

        $cat_restIDs = [];
        $cus_restIDs = [];

        if (!empty($request->categories)) {
            $cat_restIDs = DB::table('food_list')
                ->whereIn('category_id', $request->categories)
                ->groupBy('restaurant_id')
                ->pluck('restaurant_id')->toArray();

            //If filters are applied and keyword is empty, do no fetch all restaurants
            if (empty($request->keyword)) {
                $restIDs = [];
            }
        }

        if (!empty($request->cuisines)) {
            $cus_restIDs = DB::table('restaurant_cuisines')
                ->whereIn('cuisine_id', $request->cuisines)
                ->groupBy('restaurant_id')
                ->pluck('restaurant_id')->toArray();

            //If filters are applied and keyword is empty, do no fetch all restaurants
            if (empty($request->keyword)) {
                $restIDs = [];
            }
        }

        $filteredRestIDs = array_merge($cat_restIDs, $cus_restIDs, $restIDs);
        $filteredRestIDs = array_values(array_unique($filteredRestIDs));
        foreach ($filteredRestIDs as $eachRestID) {
            if (in_array($eachRestID, $availableRestIDs) && !in_array($eachRestID, $restIDs)) {
                $restIDs[] = $eachRestID;
            }
        }


        $data = $restaurants->with('Cuisines')->where('status', 1)
            ->select('restaurants.*')
            ->whereIn('restaurants.id', $restIDs)
            ->get();

        foreach ($data as $d) {
            $rcuisines = [];

            foreach ($d->Cuisines as $r_cuisines) {
                $r_cuisines->name = $this->secondLanguage_user($r_cuisines->name, $r_cuisines->secondary_name);
                $rcuisines[] = $r_cuisines->name;
            }

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d->id);


            $restaurant_list[] = array(
                'id' => $d->id,
                'name' => $this->secondLanguage_user($d->restaurant_name, $d->restaurant_secondary_name),
                'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image,
                'banner' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner,
                'discount' => $d->discount,
                'rating' => $d->rating,
                'is_open' => $is_open, // 1- Open , 0 - Close
                'cuisines' => $rcuisines,
                'travel_time' => $d->estimated_delivery_time,
                'price' => '',
                'is_favourite' => $is_favourite,
                'address' => $d->address,
                'ratings' => get_restaurant_ratings($d->id),
                'distance' => $d->distance,
            );
        }
        array_multisort(
            array_column($restaurant_list, 'is_open'),
            SORT_DESC,
            array_column($restaurant_list, 'distance'),
            SORT_ASC,
            $restaurant_list
        );
        // $cuisines = array_unique($cuisines);
        $categories = DB::table('category')->where('image', '<>', '')->where('status', 1)->orderBy('rank', 'asc')->get();
        $categories_all = get_all_categories();

        return view('user.restaurant_list', compact('restaurant_list', 'cuisines', 'categories', 'near_by_cities', 'categories_all'));
    }

    public function get_popular_brands(Request $request)
    {
        $popularbrands = $this->popularbrands;

        $data = $popularbrands::get();

        if (count($data) != 0) {
            $response_array = array('status' => true, 'data' => $data, 'base_url' => BASE_URL . UPLOADS_PATH);
        } else {
            $response_array = array('status' => false, 'message' => 'No Data Found');
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_favourite_list(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }
        // $user_id = $request->header('authId');
        $favouritelist = $this->favouritelist;
        $restaurants = $this->restaurants;
        $data = $favouritelist::where('user_id', $user_id)->get();
        $restaurant_list = array();
        if (count($data) != 0) {
            foreach ($data as $key) {

                $restaurant_detail = $restaurants::where('id', $key->restaurant_id)->first();

                $restaurant_cuisines = DB::table('restaurant_cuisines')->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
                    ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
                    ->select('restaurant_cuisines.restaurant_id as restaurant_id', 'cuisines.name as cuisine_name', 'restaurants.restaurant_name as restaurant_name')
                    ->where('restaurants.id', '=', $key->restaurant_id)
                    ->get();

                $rcuisines = array();
                $i = 0;
                foreach ($restaurant_cuisines as $r_cuisines) {

                    if ($restaurant_detail->restaurant_name == $r_cuisines->restaurant_name && $i < 2) // To display only two cuisines
                    {

                        $rcuisines[] = array(
                            'name' => $r_cuisines->cuisine_name,
                        );

                        $i = $i + 1;
                    }
                }

                if (isset($restaurant_detail->id)) {
                    //calculate restaurant open time
                    $is_open = restaurant_is_open($key->restaurant_id);

                    $restaurant_list[] = array(
                        'restaurant_id' => $key->restaurant_id,
                        'name' => $restaurant_detail->restaurant_name,
                        'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image,
                        'discount' => $restaurant_detail->discount,
                        'rating' => $restaurant_detail->rating,
                        'is_open' => $is_open, // 1- Open , 0 - Close
                        'travel_time' => $restaurant_detail->estimated_delivery_time,
                        'price' => '',
                        'address' => $restaurant_detail->address,
                        'is_favourite' => 1,
                        'cuisines' => $rcuisines,
                    );
                }
            }

            $response_array = array('status' => true, 'favourite_list' => $restaurant_list);
        } else {
            $response_array = array('status' => false, 'message' => 'No favourite restaurants found');
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function update_favourite(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }
        // $user_id = $request->header('authId');

        $validator = Validator::make(
            $request->all(),
            array(
                'restaurant_id' => 'required',
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $restaurant_id = $request->restaurant_id;

            $favouritelist = $this->favouritelist;

            $check = $favouritelist::where('user_id', $user_id)->where('restaurant_id', $restaurant_id)->first();

            if (count($check) != 0) {
                $favouritelist::where('id', $check->id)->delete();

                $response_array = array('status' => true, 'message' => 'Removed from Favourites');
            } else {
                $data = array();
                $data['user_id'] = $user_id;
                $data['restaurant_id'] = $restaurant_id;
                $favouritelist::insert($data);

                $response_array = array('status' => true, 'message' => 'Added to Favourites');
            }
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_current_order_status(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }
        // $user_id = $request->header('authId');

        $check_request = $this->foodrequest::where('user_id', $user_id)->where('status', '!=', 10)->where('is_rated', '=', 0)->orderBy('id', 'desc')->get();

        if (count($check_request) != 0) {
            $order_status = array();
            foreach ($check_request as $key) {

                $restaurant_detail = $this->restaurants::where('id', $key->restaurant_id)->first();

                $item_list = DB::table('request_detail')->where('request_id', $key->id)->get();

                $item_count = 0;

                if ($key->delivery_boy_id != 0) {
                    $delivery_boy_id = $key->delivery_boy_id;
                    // echo $delivery_boy_id; exit;
                    $delivery_boy_detail = DB::table('delivery_partners')->where('id', $delivery_boy_id)->first();

                    $delivery_boy_name = $delivery_boy_detail->name;

                    $delivery_boy_image = $delivery_boy_detail->profile_pic;

                    $delivery_boy_phone = $delivery_boy_detail->phone;
                } else {
                    $delivery_boy_id = 0;
                    $delivery_boy_name = "";
                    $delivery_boy_image = "";
                    $delivery_boy_phone = "";
                }

                $get_item_lists = array();
                foreach ($item_list as $list) {

                    $item_count = $item_count + $list->quantity;
                    $food_detail = DB::table('food_list')->where('id', $list->food_id)->first();
                    $get_item_lists[] = array(
                        'item_name' => $food_detail->name,
                        'item_quantity' => $list->quantity,
                        'price' => $food_detail->price * $list->quantity,
                    );
                }

                $order_status[] = array(
                    'request_id' => $key->id,
                    'order_id' => $key->order_id,
                    'ordered_time' => $key->ordered_time,
                    'restaurant_name' => $restaurant_detail->restaurant_name,
                    'restaurant_image' => $restaurant_detail->image,
                    'item_count' => $item_count,
                    'bill_amount' => $key->bill_amount,
                    'status' => $key->status,
                    'delivery_boy_id' => $delivery_boy_id,
                    'delivery_boy_image' => $delivery_boy_image,
                    'delivery_boy_phone' => $delivery_boy_phone,
                    'item_list' => $get_item_lists,

                );
            }

            $response_array = array('status' => true, 'order_status' => $order_status);
        } else {
            $response_array = array('status' => false, 'message' => 'No orders in processing');
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function track_order_detail(Request $request)
    {
        //  if($request->header('authId')!="")
        //     {
        //         $user_id = $request->header('authId');
        //     }else
        //     {
        //         $user_id = $request->authId;
        //     }
        $user_id = $request->header('authId') ?: $request->authId;

        $validator = Validator::make(
            $request->all(),
            array(
                'request_id' => 'required',
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $request_id = $request->request_id;
            $trackorderstatus = $this->trackorderstatus;

            $check_request = $this->foodrequest::where('user_id', $user_id)->where('id', $request_id)->first();
            $restaurant_detail = DB::table('restaurants')->where('id', $check_request->restaurant_id)->first();

            $order_status = array();

            $item_list = DB::table('request_detail')->where('request_id', $request_id)->get();

            $item_count = 0;

            if ($check_request->delivery_boy_id != 0) {
                $delivery_boy_id = $check_request->delivery_boy_id;

                $delivery_boy_detail = DB::table('delivery_partners')->where('id', $delivery_boy_id)->first();

                $delivery_boy_name = $delivery_boy_detail->name;

                $delivery_boy_image = $delivery_boy_detail->profile_pic;

                $delivery_boy_phone = $delivery_boy_detail->phone;
            } else {
                $delivery_boy_id = 0;
                $delivery_boy_name = "";
                $delivery_boy_image = "";
                $delivery_boy_phone = "";
            }

            foreach ($item_list as $list) {
                $item_count = $item_count + $list->quantity;
            }

            $order_status[] = array(
                'request_id' => $request_id,
                'order_id' => $check_request->order_id,
                'ordered_time' => $check_request->ordered_time,
                'restaurant_name' => $restaurant_detail->restaurant_name,
                'restaurant_image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image,
                'item_count' => $item_count,
                'item_total' => $check_request->item_total,
                'offer_discount' => $check_request->offer_discount,
                'restaurant_packaging_charge' => $check_request->restaurant_packaging_charge,
                'tax' => $check_request->tax,
                'delivery_charge' => $check_request->delivery_charge,
                'bill_amount' => $check_request->bill_amount,
                'status' => $check_request->status,
                'delivery_boy_id' => $delivery_boy_id,
                'delivery_boy_name' => $delivery_boy_name,
                'delivery_boy_image' => $delivery_boy_image,
                'delivery_boy_phone' => $delivery_boy_phone,
                'restaurant_lat' => $restaurant_detail->lat,
                'restaurant_lng' => $restaurant_detail->lng,
                'user_lat' => $check_request->d_lat,
                'user_lng' => $check_request->d_lng,

            );

            $tracking_detail = $trackorderstatus::where('request_id', $request_id)->get();

            $response_array = array('status' => true, 'order_status' => $order_status, 'tracking_detail' => $tracking_detail);
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function order_history(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }
        // $user_id = $request->header('authId');

        $orders = DB::table('requests')->where('requests.user_id', $user_id)->where('requests.is_paid', 1)->latest()->limit(10)->get();

        $order_list = array();

        foreach ($orders as $key) {
            $order_detail = $this->requestdetail->where('request_id', $key->id)->get();
            //dd($order_detail);
            $order_list_detail = array();
            foreach ($order_detail as $k) {
                if (isset($k->FoodQuantity)) {
                    $k->FoodQuantity->price = $k->food_quantity_price;
                }

                $order_list_detail[] = array(
                    'food_id' => (!empty($k->Foodlist) ? $k->Foodlist->id : ""),
                    'food_name' => (!empty($k->Foodlist) ? $k->Foodlist->name : ""),
                    'food_quantity' => $k->quantity,
                    'tax' => (!empty($k->Foodlist) ? $k->Foodlist->tax : ""),
                    'item_price' => (!empty($k->Foodlist) ? $k->Foodlist->price : 0) * $k->quantity,
                    'is_veg' => (!empty($k->Foodlist) ? $k->Foodlist->is_veg : ""),
                    'food_size' => $k->FoodQuantity,
                    'add_ons' => $k->Addons,
                );
            }

            $restaurant_detail = DB::table('restaurants')->where('id', $key->restaurant_id)->first();
            //if (isset($request_detail->id)) {
            $order_list[] = array(
                'request_id' => $key->id,
                'order_id' => $key->order_id,
                'restaurant_id' => isset($restaurant_detail->id) ? $restaurant_detail->id : $key->restaurant_id,
                'restaurant_name' => isset($restaurant_detail->restaurant_name) ? $restaurant_detail->restaurant_name : "",
                'restaurant_image' => isset($restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : "",
                'ordered_on' => $key->ordered_time,
                'bill_amount' => $key->bill_amount,
                'item_list' => $order_list_detail,
                'item_total' => $key->item_total,
                'offer_discount' => $key->offer_discount,
                'restaurant_packaging_charge' => $key->restaurant_packaging_charge,
                'tax' => $key->tax,
                'delivery_charge' => $key->delivery_charge,
                'delivery_address' => $key->delivery_address,
                'restaurant_address' => $restaurant_detail->address,
            );
            //}

        }

        $upcoming_orders = DB::table('requests')->where('requests.user_id', $user_id)->where('requests.status', '!=', 10)->where('requests.status', '!=', 7)->get();

        $upcoming_order_list = array();

        foreach ($upcoming_orders as $key) {
            $upcoming_order_detail = $this->requestdetail->where('request_id', $key->id)->get();
            $upcoming_order_list_detail = array();
            foreach ($upcoming_order_detail as $k) {
                if (isset($k->FoodQuantity)) {
                    $k->FoodQuantity->price = $k->food_quantity_price;
                }

                $upcoming_order_list_detail[] = array(
                    'food_id' => (!empty($k->Foodlist) ? $k->Foodlist->id : ""),
                    'food_name' => (!empty($k->Foodlist) ? $k->Foodlist->name : ""),
                    'food_quantity' => $k->quantity,
                    'tax' => (!empty($k->Foodlist) ? $k->Foodlist->tax : ""),
                    'item_price' => (!empty($k->Foodlist) ? $k->Foodlist->price : 0) * $k->quantity,
                    'is_veg' => (!empty($k->Foodlist) ? $k->Foodlist->is_veg : ""),
                    'food_size' => $k->FoodQuantity,
                    'add_ons' => $k->Addons,
                );
            }

            $restaurant_details = DB::table('restaurants')->where('id', $key->restaurant_id)->first();
            //dd($restaurant_details->restaurant_name);
            if (isset($restaurant_details->id)) {
                $upcoming_order_list[] = array(
                    'request_id' => $key->id,
                    'order_id' => $key->order_id,
                    'restaurant_id' => $key->restaurant_id,
                    'restaurant_name' => $restaurant_details->restaurant_name,
                    'restaurant_image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_details->image,
                    'ordered_on' => $key->ordered_time,
                    'bill_amount' => $key->bill_amount,
                    'item_list' => $upcoming_order_list_detail,
                    'item_total' => $key->item_total,
                    'offer_discount' => $key->offer_discount,
                    'restaurant_packaging_charge' => $key->restaurant_packaging_charge,
                    'tax' => $key->tax,
                    'delivery_charge' => $key->delivery_charge,
                    'delivery_address' => $key->delivery_address,
                    'restaurant_address' => $restaurant_details->address,
                );
            }
        }

        if (count($upcoming_order_list) != 0 || count($order_list) != 0) {
            $response_array = array('status' => true, 'past_orders' => $order_list, 'upcoming_orders' => $upcoming_order_list);
        } else {

            $response_array = array('status' => false, 'message' => "No Orders Placed");
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_order_status(Request $request)
    {

        // $request_id = $request->request_id;

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        // $user_id = $request->header('authId');

        $request_detail = DB::table('requests')->where('user_id', $user_id)
            ->where('status', '!=', 10)
            ->where('status', '!=', 7)
            ->first();

        if (count($request_detail) != 0) {

            $order_id = $request_detail->order_id;

            $request_id = $request_detail->id;

            $ordered_time = $request_detail->ordered_time;

            $restaurant_detail = $this->restaurants::where('id', $request_detail->restaurant_id)->first();

            $user_detail = DB::table('users')->where('id', $request_detail->user_id)->first();

            $address_detail = array();

            $request_status = $request_detail->status;

            $address_detail[] = array(
                'd_address' => $request_detail->delivery_address,
                's_address' => $restaurant_detail->address,
                'd_lat' => $request_detail->d_lat,
                'd_lng' => $request_detail->d_lng,
                's_lat' => $restaurant_detail->lat,
                's_lng' => $restaurant_detail->lng,
            );

            $food_detail = array();
            $bill_detail = array();

            $data = DB::table('request_detail')->where('request_detail.request_id', $request_id)
                ->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
                ->select('request_detail.quantity as quantity', 'food_list.name as food', 'food_list.price as price_per_quantity', 'food_list.is_veg as is_veg')
                ->get();

            foreach ($data as $d) {
                $food_detail[] = array(
                    'name' => $d->food,
                    'quantity' => $d->quantity,
                    'price' => $d->quantity * $d->price_per_quantity,
                    'is_veg' => $d->is_veg,
                );
            }

            $bill_detail[] = array(
                'item_total' => $request_detail->item_total,
                'offer_discount' => $request_detail->offer_discount,
                'packaging_charge' => $request_detail->restaurant_packaging_charge,
                'tax' => $request_detail->tax,
                'delivery_charge' => $request_detail->delivery_charge,
                'bill_amount' => $request_detail->bill_amount,
            );

            $response_array = array('status' => true, 'request_id' => $request_id, 'ordered_time' => $ordered_time, 'order_id' => $order_id, 'restaurant_detail' => $restaurant_detail, 'user_detail' => $user_detail, 'address_detail' => $address_detail, 'bill_detail' => $bill_detail, 'food_detail' => $food_detail, 'request_status' => $request_status);
        } else {
            $response_array = array('status' => true, 'message' => 'No Orders Available');
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    /**
     * to list all promocode details
     *
     * @param object $request
     *
     * @return json $response
     */
    public function get_promo_list(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        //get promo list
        $get_promo = $this->promocode->where('status', 1)
            ->whereDate('available_from', '<=', Carbon::now())
            ->whereDate('valid_till', '>=', Carbon::now())
            ->get();

        $response_array = array('status' => true, 'promo_list' => $get_promo);
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function update_profile(Request $request)
    {
        $session_user_id = $request->id;
        $custom = $this->custom;
        $user = Users::find($session_user_id);
        $user->name = $request->has('name') ? $request->name : $user->name;
        $user->phone = $request->has('phone') ? $request->phone : $user->phone;
        if ($request->profile_image) {
            if ($user->profile_image != "") {
                $custom::delete_image($user->profile_image);
            }
            $profile_pic = $custom::upload_image($request, 'profile_image');
            $user->profile_image = 'uploads/' . $profile_pic;
        }
        $user->save();
        $user_detail = DB::table('users')->where('id', $session_user_id)->first();
        $message = "Profile Updated";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $user_detail]);
        return $response_Array;
    }

    public function payment_completed_api(Request $request)
    {

        $Order = Order::findByPaymentID($request->paymentid);
        if (!$Order->flag) {
            return 'Order not found';
        }

        $confirmOrder = $Order->confirmOrder();
        if (!$confirmOrder->flag) {
            return 'Payment not confirmed';
        }

        return view('user.payment_completed_msg');
    }

    public function payment_completed_msg(Request $request)
    {
        return view('user.payment_completed_msg');
    }


    public function terms_conditions(Request $request)
    {
        $content = DB::table('content')->where('key', 'terms-conditions')->value('value');

        return view('user.privacy_policy')->with('content', $content)->with('title', trans('constants.terms_conditions'));
    }


    public function refund(Request $request)
    {
        $content = DB::table('content')->where('key', 'refund')->value('value');
        //dd("f");
        return view('user.privacy_policy')->with('content', $content)->with('title', trans('constants.refund'));
    }

    public function about_us(Request $request)
    {
        $content = DB::table('content')->where('key', 'about-us')->value('value');

        return view('user.about-us')->with('content', $content)->with('title', trans('constants.about_us'));
    }



    public function contact(Request $request)
    {
        $content = DB::table('content')->where('key', 'contact')->value('value');

        return view('user.privacy_policy')->with('content', $content)->with('title', trans('constants.contact'));
    }

    public function terms_and_conditions2(Request $request)
    {
        $content = DB::table('content')->where('key', 'terms-conditions')->value('value');

        return view('user.privacy_policy')->with('content', $content)->with('title', trans('constants.terms_conditions'));
    }

    public function test(Request $request)
    {

        $Order = Order::find(1295);
        dd($Order);

        $coupon = $this->promocode->where('code', $Order->Request->coupon_code)->first();
        if ($coupon->restaurant_id != '') {
            if ($coupon->contribution >= 1 && $coupon->offer_type == 0) {
                $Item_without_tax = $Order->Request->item_total - $Order->Request->tax;
                $offer_without_tax = ($Item_without_tax / 100) * $coupon->value;
                if ($offer_without_tax > $coupon->max_amount) {
                    $offer_without_tax = $coupon->max_amount;
                }
                $admin_off_contribution = ($offer_without_tax / 100) * $coupon->contribution;
                echo $restaurant_off_contribution = $Order->Request->offer_discount - $admin_off_contribution;

                $admin_commision = $Order->Request->admin_commision - $admin_off_contribution;
                echo $restaurant_commision = $Order->Request->restaurant_commision - $restaurant_off_contribution;
                dd($coupon);
                DB::table('requests')->where('id', $Order->Request->id)->update([
                    'admin_commision' => $admin_commision,
                    'restaurant_commision' => $restaurant_commision,
                    'ac_after_contribution' => $admin_off_contribution,
                    'rc_after_contribution' => $restaurant_off_contribution
                ]);
            }
        }
    }

    public function test_pk(Request $request) {}

    public function storeLocation2Session(Request $request)
    {

        $request->session()->put('latitude', $request->lat);
        $request->session()->put('longitude', $request->lng);
    }
    public function edit_address($id)
    {
        $delivery_address = $this->deliveryaddress;
        $address = $delivery_address::where('id', $id)->first();
        //dd($data);
        return view('user.edit_address', compact('address'));
    }

    public function restaurants_for_schedule_delivery(Request $request)
    {
        $restaurant_list = array();
        // to hide loc popup
        if (isset($request->loc_flag)) {
            $request->session()->put('location_ses', 1);
        }
        $request->session()->put('add', $request->add);
        $request->session()->put('lat', $request->lat);
        $request->session()->put('lng', $request->lng);
        $date = date("Y-m-d", strtotime($request->schedule_date));;
        $scheduleAt = $date . ' ' . $request->time;
        $request->session()->put('schedule_date', $scheduleAt);
        // dd(session()->get('lat'));dd("k");
        //  dd($request);
        $validator = Validator::make(
            $request->all(),
            [
                'keyword' => 'required',
                'lat' => 'required',
                'lng' => 'required',
                'schedule_date' => 'required',
                'time' => 'required'
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
            //return view('user.restaurant_list')->with(['near_by_cities' => [], 'cuisines' => [], 'restaurant_list' => []]);//, compact('restaurant_list', 'cuisines', 'near_by_cities'));
        }

        if (empty($request->lat) || empty($request->lng)) {
            $sess_lat = $request->session()->get('latitude');
            $sess_lng = $request->session()->get('longitude');
            if (!empty($sess_lat) && !empty($sess_lng)) {
                return redirect("restaurants_for_schedule_delivery?keyword=$request->keyword&lat=$sess_lat&lng=$sess_lng");
            }
        }

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $source_lat = $request->lat;
        $source_lng = $request->lng;

        if (!empty($source_lat) && !empty($source_lng)) {
            $near_by_cities = get_nearby_cities($source_lat, $source_lng);
            $cuisines = get_all_cuisines(); //get_nearby_cuisines($source_lat, $source_lng);
        } else {
            $near_by_cities = [];
            $cuisines = get_all_cuisines();
        }


        $restaurants = $this->restaurants;

        // if (!empty($source_lat) && !empty($source_lng)) {
        //     //geofencing for restaurant
        //     $data = $restaurants->with('Cuisines')->where('status', 1)
        //         ->select('restaurants.*')
        //         ->selectRaw("(6371 * acos(cos(radians(" . $source_lat . "))* cos(radians(`lat`))
        //                     * cos(radians(`lng`) - radians(" . $source_lng . ")) + sin(radians(" . $source_lat . "))
        //                     * sin(radians(`lat`)))) as distance")
        //         ->having('distance', '<=', DEFAULT_RADIUS)
        //         ->orderBy('distance')
        //         ->get();
        // } else {

        //     $data = $restaurants->with('Cuisines')->where('status', 1)
        //     ->select('restaurants.*')
        //     ->where('restaurants.restaurant_name', 'LIKE', '%'.$request->keyword.'%')
        //     ->get();
        // }

        $availableRestIDs = [];

        $all_restaurant = $restaurants->where('status', 1)->select('id', 'city', 'lat', 'lng', 'GeofenceRadius')->get();
        foreach ($all_restaurant as $each_restaurant) {
            if (!empty($source_lat) && !empty($source_lng)) {
                $geofenceRadius = (float) ($each_restaurant->GeofenceRadius ?? DEFAULT_RADIUS);
                $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $each_restaurant->lat, $each_restaurant->lng);
                if ($distance <= $geofenceRadius) {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            } else if (!empty($request->city_id)) {
                if ($each_restaurant->city == $request->city_id) {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            } else {
                $availableRestIDs[] = $each_restaurant->id;
            }
        }

        $data = $restaurants->with('Cuisines')->where('status', 1)
            ->select('restaurants.*')
            ->where('restaurants.restaurant_name', 'LIKE', '%' . $request->keyword . '%')
            ->get();

        $restIDs = [];
        foreach ($data as $key => $value) {
            if (in_array($value->id, $availableRestIDs)) {
                $restIDs[] = $value->id;
            }
        }

        $data_foodlist = DB::table('food_list')
            ->join('category', 'category.id', '=', 'food_list.category_id')
            ->join('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
            ->where('name', 'like', '%' . $request->keyword . '%')
            ->OrWhere('category_name', 'like', '%' . $request->keyword . '%')
            ->select('food_list.*', 'restaurants.restaurant_name', 'restaurants.image', 'restaurants.lat', 'restaurants.lng')
            ->get();

        foreach ($data_foodlist as $value) {
            if (!in_array($value->restaurant_id, $restIDs) && in_array($value->restaurant_id, $availableRestIDs)) {
                $restIDs[] = $value->restaurant_id;
            }
        }

        $cat_restIDs = [];
        $cus_restIDs = [];

        if (!empty($request->categories)) {
            $cat_restIDs = DB::table('food_list')
                ->whereIn('category_id', $request->categories)
                ->groupBy('restaurant_id')
                ->pluck('restaurant_id')->toArray();

            //If filters are applied and keyword is empty, do no fetch all restaurants
            if (empty($request->keyword)) {
                $restIDs = [];
            }
        }

        if (!empty($request->cuisines)) {
            $cus_restIDs = DB::table('restaurant_cuisines')
                ->whereIn('cuisine_id', $request->cuisines)
                ->groupBy('restaurant_id')
                ->pluck('restaurant_id')->toArray();

            //If filters are applied and keyword is empty, do no fetch all restaurants
            if (empty($request->keyword)) {
                $restIDs = [];
            }
        }

        $filteredRestIDs = array_merge($cat_restIDs, $cus_restIDs, $restIDs);
        $filteredRestIDs = array_values(array_unique($filteredRestIDs));
        foreach ($filteredRestIDs as $eachRestID) {
            if (in_array($eachRestID, $availableRestIDs) && !in_array($eachRestID, $restIDs)) {
                $restIDs[] = $eachRestID;
            }
        }


        $data = $restaurants->with('Cuisines')->where('status', 1)
            ->select('restaurants.*')
            ->whereIn('restaurants.id', $restIDs)
            ->get();

        foreach ($data as $d) {
            $rcuisines = [];

            foreach ($d->Cuisines as $r_cuisines) {
                if ($r_cuisines) {
                    $r_cuisines->name = $this->secondLanguage_user($r_cuisines->name, $r_cuisines->secondary_name);
                }
                $rcuisines[] = $r_cuisines->name;
            }

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $isOpen = Restaurant::isOpenAt($d->id, $scheduleAt);
            // $is_open = restaurant_is_open($d->id);


            $restaurant_list[] = array(
                'id' => $d->id,
                'name' => $this->secondLanguage_user($d->restaurant_name, $d->restaurant_secondary_name),
                'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image,
                'discount' => $d->discount,
                'rating' => $d->rating,
                'is_open' => $isOpen, // 1- Open , 0 - Close
                'cuisines' => $rcuisines,
                'travel_time' => $d->estimated_delivery_time,
                'price' => '',
                'is_favourite' => $is_favourite,
                'address' => $d->address,
                'ratings' => get_restaurant_ratings($d->id),
            );
        }

        // $cuisines = array_unique($cuisines);
        $categories = DB::table('category')->where('image', '<>', '')->where('status', 1)->orderBy('rank', 'asc')->get();
        $categories_all = get_all_categories();

        return view('user.restaurant_list', compact('restaurant_list', 'cuisines', 'categories', 'near_by_cities', 'categories_all'));
    }

    public function cache_clear(Request $request)
    {
        $exitcode = Artisan::call('config:cache');
        $exitcode = Artisan::call('view:clear');
        echo "Successfully cache clear!";
    }
    public function banner_list(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;


        if ($lat == "null" || $lng == "null") {
            $lat = DEFAULT_LATITUDE;
            $lng = DEFAULT_LONGITUDE;
        }
        $banner = get_nearby_banners($lat, $lng);

        $rt = [];
        $url = "";
        $acc_hash = "";
        $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();

        if ($cloudflare->value == 1) {
            $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
            $url = $get_url->value;

            $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
            $acc_hash = $get_acchash->value;
        }
        foreach ($banner as $item) {
            $img = asset('uploads/' . $item->image);
            if ($cloudflare->value == 1 && $item->cloudflare_bannerid != null) {

                $img = $url . '/' . $acc_hash . '/' . $item->cloudflare_bannerid . '/' . "w=500";
            } else {
                $img = asset('uploads/' . $item->image);
            }

            if ($item->category != null && $item->category != 0) {
                $cat_det = DB::table('business_category')->where('id', $item->category)->first();
                $redirect_to = strtolower(str_replace(' ', '-', $cat_det->category_name) . '-' . $cat_det->id);
                $redirect_type = 1;
                $redirect_name = $cat_det->category_name;
            } else if ($item->product != null && $item->product != 0) {
                $product = DB::table('food_list')->where('id', $item->product)->first();
                $redirect_to = $item->product;
                $redirect_type = 2;
                $redirect_name = $product->name;
            } else {
                $redirect_to = 0;
                $redirect_type = 0;
                $redirect_name = "";
            }
            $rt[] = [
                'restaurant_id' => $item->restaurant_id,
                'img' => $img,
                'name' => $item->name,
                'redirect_to' => $redirect_to,
                'redirect_type' => $redirect_type,
                'redirect_name' => $redirect_name


            ];
        }
        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt]);
        return $response_Array;
    }
    public function category_lists(Request $request)
    {

        $categories = get_category_by_rank_one($request->id);
        $rt = [];
        $url = "";
        $acc_hash = "";
        $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();

        if ($cloudflare->value == 1) {
            $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
            $url = $get_url->value;

            $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
            $acc_hash = $get_acchash->value;
        }
        foreach ($categories as $item) {
            $img = asset('/' . $item->image);
            if ($cloudflare->value == 1 && $item->cloudflare_imageid != null) {

                $img = $url . '/' . $acc_hash . '/' . $item->cloudflare_imageid . '/' . "w=300";
            } else {
                $img = asset('/' . $item->image);
            }

            $rt[] = [
                'img' => $img,
                'text' => $this->secondLanguage_user($item->category_name, $item->secondary_categoryname),
                'id' => $item->id,
                'slug' => strtolower(str_replace(' ', '-', $item->category_name) . '-' . $item->id)
            ];
        }

        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt]);
        return $response_Array;
    }

    public function category_list(Request $request)
    {

        $data1 = DB::table('business_type')
            ->join('layout', 'layout.id', '=', 'business_type.layout_id')
            ->where('business_type.status', 1)
            ->select('business_type.*', 'layout.layout_name as layout_name', 'business_type.id as business_id')
            ->get();
        $rt = [];
        $url = "";
        $acc_hash = "";
        $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();

        if ($cloudflare->value == 1) {
            $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
            $url = $get_url->value;

            $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
            $acc_hash = $get_acchash->value;
        }
        foreach ($data1 as $data) {
            if ($data) {
                $data->name = $this->secondLanguage_user($data->name, $data->secondary_name);
            }
            if ($data->image != null) {
                if ($cloudflare->value == 1 && $data->cloudflare_imageid != null) {

                    $data->image = $url . '/' . $acc_hash . '/' . $data->cloudflare_imageid . '/' . "w=129";
                } else {
                    if (file_exists(public_path('/')  . $data->image)) {
                        $data->image = BASE_URL .  $data->image;
                    } else {
                        $data->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
                    }
                }
            } else {

                $data->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            }

            //    if($data->image != null)
            //    {
            // 	if(file_exists(public_path('/')  . $data->image)) {
            // 		$data->image = BASE_URL .  $data->image;
            // 	} else {
            // 		$data->image= BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            // 	}
            //    }
            //    else{

            // 	$data->image= BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            //    }
            $rt[] = [
                'img' => $data->image,
                'text' => $data->name,
                'id' => $data->business_id,
            ];
        }

        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt]);
        return $response_Array;
    }

    public function todays_special_restaurant(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $business_id = 1;
        if ($lat == "null" || $lng == "null") {
            $lat = DEFAULT_LATITUDE;
            $lng = DEFAULT_LONGITUDE;
        }

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $delivery_type = $request->delivery_type ?? 0;

        $source_lat = $lat;
        $source_lng = $lng;

        $restaurants = $this->restaurants;

        $nearbyRestaurantIDs = Restaurant::nearDistance_business_rest($source_lat, $source_lng, $business_id);

        //geofencing for restaurant
        $data = $restaurants->with('Cuisines')->where('status', 1)
            ->select('restaurants.*')
            ->whereIn('id', $nearbyRestaurantIDs)
            ->orderby('is_open', 'desc')
            ->get();

        $restaurant_list = array();
        $j = 0;
        foreach ($data as $d) {

            if ($delivery_type) {
                $rest_delivery_types = json_decode($d->delivery_type);
                if (!$rest_delivery_types || !in_array($delivery_type, $rest_delivery_types)) {
                    continue;
                }
            }
            // if($j<2)  // To display only two restaurants
            // {
            $rcuisines = array();
            $i = 0;
            foreach ($d->Cuisines as $r_cuisines) {
                if ($r_cuisines) {
                    $r_cuisines->name = $this->secondLanguage_user($r_cuisines->name, $r_cuisines->secondary_name);
                }
                if ($i < 2) // To display only two cuisines
                {
                    $rcuisines[] = array(
                        'name' => $r_cuisines->name,
                    );
                    $i = $i + 1;
                }
            }

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d->id);

            $rating_statistics = get_rating_statistics($d->id);
            $distance = Restaurant::getrestdistance($source_lat, $source_lng, $d->id);


            $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();


            $url = "";
            $acc_hash = "";
            if ($cloudflare->value == 1) {
                $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
                $url = $get_url->value;

                $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
                $acc_hash = $get_acchash->value;
            }

            if ($cloudflare->value == 1 && $d->cloudflare_imageid != null) {


                $fimage = $url . '/' . $acc_hash . '/' . $d->cloudflare_imageid;
            } else {

                if ($d->image != '') {
                    if (File::exists(public_path('restaurant_uploads/') . $d->image)) {
                        $fimage =   BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image;
                    } elseif (File::exists(public_path('restaurant_uploads/'))) {
                        $fimage =  BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image;
                    } else {
                        $fimage = BASE_URL . "def_logo.jpg";
                    }
                } else {
                    $fimage = BASE_URL . "def_logo.jpg";
                }
            }

            if ($cloudflare->value == 1 && $d->cloudflare_bannerid != null) {

                $fbanner = $url . '/' . $acc_hash . '/' . $d->cloudflare_bannerid . '/' . "w=1000,height=136,trim=0;400";
            } else {
                if ($d->banner != '') {
                    if (File::exists(public_path('restaurant_uploads/') . $d->banner)) {
                        $fbanner =   BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner;
                    } elseif (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Banner/' . $d->banner)) {
                        $fbanner =  BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Banner/' . $d->banner;
                    } else {
                        $fbanner = BASE_URL . "def_banner.jpg";
                    }
                } else {
                    $fbanner = BASE_URL . "def_banner.jpg";
                }
            }
            $restaurant_list[] = array(
                'id' => $d->id,
                'name' => $this->secondLanguage_user($d->restaurant_name, $d->restaurant_secondary_name),
                'image' => $fimage,
                'banner' => $fbanner,
                // 'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image,
                // 'banner' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner,
                'discount' => $d->discount,
                'rating' => (float) $rating_statistics['rating_percent'], //$d->rating,
                'is_open' => $is_open, // 1- Open , 0 - Close
                'cuisines' => $rcuisines,
                'travel_time' => $d->estimated_delivery_time,
                'delivery_type' => json_decode($d->delivery_type),
                'price' => '',
                'is_favourite' => $is_favourite,
                'address' => $d->address,
                'distance' => $distance,
            );
            $j++;

            // }
        }
        $schedule = DB::table('settings')->where('key_word', 'schedule')->value('value');
        if ($data) {
            $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'schedule' => $schedule);
        } else {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found');
        }


        $rt = [];
        $co = 0;

        foreach ($restaurant_list as $res_list) {
            if ($co < 10) {
                $img = $res_list['banner'];

                $rt[] = [
                    'id' => $res_list['id'],
                    'img' => $img,
                    'text' => $res_list['name'],
                    'travel_time' => $res_list['travel_time'],
                    'rating' => $res_list['rating'],
                    'icon' => 'StarIcon',
                    'c_icon' => 'ClockIcon',
                    'd_icon' => 'MapPinIcon',
                    'distance' => $res_list['distance'],
                    'cuisines' => $res_list['cuisines'],
                    'is_open' => $res_list['is_open'],

                ];
            }
            $co++;
        }
        $price = array_column($rt, 'distance');

        array_multisort($price, SORT_ASC, $rt);
        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt]);
        return $response_Array;
    }

    public function todays_special_store(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $business_id = 1;
        if ($lat == "null" || $lng == "null") {
            $lat = DEFAULT_LATITUDE;
            $lng = DEFAULT_LONGITUDE;
        }

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $delivery_type = $request->delivery_type ?? 0;

        $source_lat = $lat;
        $source_lng = $lng;

        $restaurants = $this->restaurants;

        $nearbyRestaurantIDs = Restaurant::nearDistance_business_store($source_lat, $source_lng, $business_id);


        //geofencing for restaurant
        $data = $restaurants->with('Cuisines')->where('status', 1)
            ->select('restaurants.*')
            ->whereIn('id', $nearbyRestaurantIDs)
            ->orderby('is_open', 'desc')
            ->get();

        $restaurant_list = array();
        $j = 0;
        foreach ($data as $d) {

            if ($delivery_type) {
                $rest_delivery_types = json_decode($d->delivery_type);
                if (!$rest_delivery_types || !in_array($delivery_type, $rest_delivery_types)) {
                    continue;
                }
            }
            // if($j<2)  // To display only two restaurants
            // {
            $rcuisines = array();
            $i = 0;
            foreach ($d->Cuisines as $r_cuisines) {
                if ($r_cuisines) {
                    $r_cuisines->name = $this->secondLanguage_user($r_cuisines->name, $r_cuisines->secondary_name);
                }
                if ($i < 2) // To display only two cuisines
                {
                    $rcuisines[] = array(
                        'name' => $r_cuisines->name,
                    );
                    $i = $i + 1;
                }
            }

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d->id);

            $rating_statistics = get_rating_statistics($d->id);
            $distance = Restaurant::getrestdistance($source_lat, $source_lng, $d->id);
            $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();


            $url = "";
            $acc_hash = "";
            if ($cloudflare->value == 1) {
                $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
                $url = $get_url->value;

                $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
                $acc_hash = $get_acchash->value;
            }

            if ($cloudflare->value == 1 && $d->cloudflare_imageid != null) {


                $fimage = $url . '/' . $acc_hash . '/' . $d->cloudflare_imageid;
            } else {
                if ($d->image != '') {
                    if (File::exists(public_path('restaurant_uploads/') . $d->image)) {
                        $fimage =   BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image;
                    } elseif (File::exists(public_path('restaurant_uploads/'))) {
                        $fimage =  BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image;
                    } else {
                        $fimage = BASE_URL . "def_logo.jpg";
                    }
                } else {
                    $fimage = BASE_URL . "def_logo.jpg";
                }
            }

            if ($cloudflare->value == 1 && $d->cloudflare_bannerid != null) {

                $fbanner = $url . '/' . $acc_hash . '/' . $d->cloudflare_bannerid . '/' . "w=500,height=136,trim=0;400";
            } else {
                if ($d->banner != '') {
                    if (File::exists(public_path('restaurant_uploads/') . $d->banner)) {
                        $fbanner =   BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner;
                    } elseif (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Banner/' . $d->banner)) {
                        $fbanner =  BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Banner/' . $d->banner;
                    } else {
                        $fbanner = BASE_URL . "def_banner.jpg";
                    }
                } else {
                    $fbanner = BASE_URL . "def_banner.jpg";
                }
            }
            $restaurant_list[] = array(
                'id' => $d->id,
                'name' => $this->secondLanguage_user($d->restaurant_name, $d->restaurant_secondary_name),
                'image' => $fimage,
                'banner' => $fbanner,

                // 'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image,
                // 'banner' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner,
                'discount' => $d->discount,
                'rating' => (float) $rating_statistics['rating_percent'], //$d->rating,
                'is_open' => $is_open, // 1- Open , 0 - Close
                'cuisines' => $rcuisines,
                'travel_time' => $d->estimated_delivery_time,
                'delivery_type' => json_decode($d->delivery_type),
                'price' => '',
                'is_favourite' => $is_favourite,
                'address' => $d->address,
                'distance' => $distance,
            );
            $j++;

            // }
        }
        $schedule = DB::table('settings')->where('key_word', 'schedule')->value('value');
        if ($data) {
            $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'schedule' => $schedule);
        } else {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found');
        }


        $rt = [];
        $co = 0;

        foreach ($restaurant_list as $res_list) {
            if ($co < 10) {
                $img = $res_list['banner'];

                $rt[] = [
                    'id' => $res_list['id'],
                    'img' => $img,
                    'text' => $res_list['name'],
                    'travel_time' => $res_list['travel_time'],
                    'rating' => $res_list['rating'],
                    'icon' => 'StarIcon',
                    'c_icon' => 'ClockIcon',
                    'd_icon' => 'MapPinIcon',
                    'distance' => $res_list['distance'],
                    'cuisines' => $res_list['cuisines'],
                    'is_open' => $res_list['is_open'],

                ];
            }
            $co++;
        }
        $price = array_column($rt, 'distance');

        array_multisort($price, SORT_ASC, $rt);
        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt]);
        return $response_Array;
    }
    public function todays_special_business(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $business_id = $request->id;
        $business_type = DB::table('business_type')->where('id', $business_id)->first();

        if ($lat == "null" || $lng == "null") {
            $lat = DEFAULT_LATITUDE;
            $lng = DEFAULT_LONGITUDE;
        }

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $delivery_type = $request->delivery_type ?? 0;

        $source_lat = $lat;
        $source_lng = $lng;

        $restaurants = $this->restaurants;

        $nearbyRestaurantIDs = Restaurant::nearDistance_business($source_lat, $source_lng, $business_id);

        //geofencing for restaurant
        $data = $restaurants->with('Cuisines')->where('status', 1)
            ->select('restaurants.*')
            ->whereIn('id', $nearbyRestaurantIDs)
            ->orderby('is_open', 'desc')
            ->get();

        $restaurant_list = array();
        $j = 0;
        foreach ($data as $d) {

            if ($delivery_type) {
                $rest_delivery_types = json_decode($d->delivery_type);
                if (!$rest_delivery_types || !in_array($delivery_type, $rest_delivery_types)) {
                    continue;
                }
            }
            // if($j<2)  // To display only two restaurants
            // {
            $rcuisines = array();
            $i = 0;
            foreach ($d->Cuisines as $r_cuisines) {
                if ($i < 2) // To display only two cuisines
                {
                    $rcuisines[] = array(
                        'name' => $r_cuisines->name,
                    );
                    $i = $i + 1;
                }
            }

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d->id);

            $rating_statistics = get_rating_statistics($d->id);
            $distance = Restaurant::getrestdistance($source_lat, $source_lng, $d->id);
            $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();


            $url = "";
            $acc_hash = "";
            if ($cloudflare->value == 1) {
                $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
                $url = $get_url->value;

                $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
                $acc_hash = $get_acchash->value;
            }

            if ($cloudflare->value == 1 && $d->cloudflare_imageid != null) {


                $fimage = $url . '/' . $acc_hash . '/' . $d->cloudflare_imageid;
            } else {
                $fimage = File::exists(public_path('restaurant_uploads/') . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Logo/' . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image : BASE_URL . UPLOADS_PATH_LOGO . $d->image);
            }

            if ($cloudflare->value == 1 && $d->cloudflare_bannerid != null) {

                $fbanner = $url . '/' . $acc_hash . '/' . $d->cloudflare_bannerid . '/' . "w=500,height=136,trim=0;400";
            } else {
                // $fbanner = File::exists(public_path('restaurant_uploads/') . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Banner/' . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Banner/' . $d->banner : BASE_URL . UPLOADS_PATH_BANNER . $d->banner);

                if ($d->banner != '') {
                    if (File::exists(public_path('restaurant_uploads/') . $d->banner)) {
                        $fbanner =   BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner;
                    } elseif (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Banner/' . $d->banner)) {
                        $fbanner =  BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Banner/' . $d->banner;
                    } else {
                        $fbanner = BASE_URL . "def_banner.jpg";
                    }
                } else {
                    $fbanner = BASE_URL . "def_banner.jpg";
                }
            }
            $restaurant_list[] = array(
                'id' => $d->id,
                'name' => $this->secondLanguage_user($d->restaurant_name, $d->restaurant_secondary_name),
                'image' => $fimage,
                'banner' => $fbanner,

                // 'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image,
                // 'banner' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner,
                'discount' => $d->discount,
                'rating' => (float) $rating_statistics['rating_percent'], //$d->rating,
                'is_open' => $is_open, // 1- Open , 0 - Close
                'cuisines' => $rcuisines,
                'travel_time' => $d->estimated_delivery_time,
                'delivery_type' => json_decode($d->delivery_type),
                'price' => '',
                'is_favourite' => $is_favourite,
                'address' => $d->address,
                'distance' => $distance,
            );
            $j++;

            // }
        }
        $schedule = DB::table('settings')->where('key_word', 'schedule')->value('value');
        if ($data) {
            $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'schedule' => $schedule);
        } else {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found');
        }


        $rt = [];
        $co = 0;

        foreach ($restaurant_list as $res_list) {
            if ($co < 10) {
                $img = $res_list['banner'];

                $rt[] = [
                    'id' => $res_list['id'],
                    'img' => $img,
                    'text' => $res_list['name'],
                    'travel_time' => $res_list['travel_time'],
                    'rating' => $res_list['rating'],
                    'icon' => 'StarIcon',
                    'c_icon' => 'ClockIcon',
                    'd_icon' => 'MapPinIcon',
                    'distance' => $res_list['distance'],
                    'cuisines' => $res_list['cuisines'],
                    'is_open' => $res_list['is_open'],

                ];
            }
            $co++;
        }
        $price = array_column($rt, 'distance');

        array_multisort($price, SORT_ASC, $rt);
        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt, 'business_id' => $business_type->layout_id]);
        return $response_Array;
    }

    public function get_near_brands(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;

        if ($lat == "null" || $lng == "null") {
            $lat = DEFAULT_LATITUDE;
            $lng = DEFAULT_LONGITUDE;
        }



        $source_lat = $lat;
        $source_lng = $lng;

        $restaurants = $this->restaurants;

        $nearbyRestaurantIDs = Restaurant::nearbrandDistance($source_lat, $source_lng);

        $data = $restaurants
            ->select('restaurants.*')
            ->whereIn('id', $nearbyRestaurantIDs)
            ->get();
        $brand_list = array();
        foreach ($data as $d) {
            if ($d->brand_name != 0) {
                $brand_name = DB::table('brands')->where('id',  $d->brand_name)->first();
                $b_rest = $restaurants->whereIn('id', $nearbyRestaurantIDs)->where('brand_name', $d->brand_name)->get();


                if (count($b_rest) == 1) {

                    $rest_name = preg_replace('!\s+!', '-', $d->restaurant_name);
                    $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();


                    $url = "";
                    $acc_hash = "";
                    if ($cloudflare->value == 1) {
                        $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
                        $url = $get_url->value;

                        $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
                        $acc_hash = $get_acchash->value;
                    }
                    if ($cloudflare->value == 1 && $d->cloudflare_imageid != null) {


                        $fimage = $url . '/' . $acc_hash . '/' . $d->cloudflare_imageid . '/' . "w=213";
                    } else {
                        // $fimage = File::exists(public_path('restaurant_uploads/') . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Logo/' . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image : BASE_URL . UPLOADS_PATH_LOGO . $d->image);


                        if ($d->image != '') {
                            if (File::exists(public_path('restaurant_uploads/') . $d->image)) {
                                $fimage =   BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image;
                            } elseif (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Logo/' . $d->image)) {
                                $fimage =  BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image;
                            } else {
                                $fimage = BASE_URL . "def_logo.jpg";
                            }
                        } else {
                            $fimage = BASE_URL . "def_logo.jpg";
                        }
                    }

                    if ($cloudflare->value == 1 && $d->cloudflare_bannerid != null) {

                        $fbanner = $url . '/' . $acc_hash . '/' . $d->cloudflare_bannerid . '/' . "w=215";
                    } else {
                        $fbanner = File::exists(public_path('restaurant_uploads/') . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Banner/' . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Banner/' . $d->banner : BASE_URL . UPLOADS_PATH_BANNER . $d->banner);
                    }
                    if ($brand_name->status == 1) {
                        $brand_list[] = array(
                            'id' => $brand_name->id,
                            'name' => $brand_name->name,
                            'status' => $brand_name->status,
                            'rank' => $brand_name->rank,
                            'image' =>  $fimage,
                            'banner' => $fbanner,
                            // 'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image,
                            // 'banner' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner,
                            'count' => count($b_rest),
                            'restaurant' => $d->id,
                            'restaurant_name' => $rest_name

                        );
                    }
                } else {
                    $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();


                    $url = "";
                    $acc_hash = "";
                    if ($cloudflare->value == 1) {
                        $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
                        $url = $get_url->value;

                        $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
                        $acc_hash = $get_acchash->value;
                    }
                    if ($cloudflare->value == 1 && $d->cloudflare_imageid != null) {


                        $fimage = $url . '/' . $acc_hash . '/' . $d->cloudflare_imageid . '/' . "w=345";
                    } else {
                        $fimage = File::exists(public_path('restaurant_uploads/') . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Logo/' . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image : BASE_URL . UPLOADS_PATH_LOGO . $d->image);
                    }

                    if ($cloudflare->value == 1 && $d->cloudflare_bannerid != null) {

                        $fbanner = $url . '/' . $acc_hash . '/' . $d->cloudflare_bannerid . '/' . "w=345";
                    } else {
                        $fbanner = File::exists(public_path('restaurant_uploads/') . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Banner/' . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Banner/' . $d->banner : BASE_URL . UPLOADS_PATH_BANNER . $d->banner);
                    }
                    if ($brand_name->status == 1) {
                        $brand_list[] = array(
                            'id' => $brand_name->id,
                            'name' => $brand_name->name,
                            'status' => $brand_name->status,
                            'rank' => $brand_name->rank,
                            // 'image' => File::exists(public_path('restaurant_uploads/').$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->image: (File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Logo/'.$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Logo/'.$d->image : BASE_URL.UPLOADS_PATH_LOGO.$d->image),
                            // 'banner' => File::exists(public_path('restaurant_uploads/').$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->banner:(File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Banner/'.$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Banner/'.$d->banner: BASE_URL.UPLOADS_PATH_BANNER.$d->banner),
                            // 'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image,
                            // 'banner' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner,
                            'image' =>  $fimage,
                            'banner' => $fbanner,
                            'count' => count($b_rest),
                            'restaurant' => null,
                            'restaurant_name' => null

                        );
                    }
                }
            }
        }
        $unique_array = [];
        foreach ($brand_list as $element) {
            $uni = $element['id'];
            $unique_array[$uni] = $element;
        }
        $u_brand_list = array_values($unique_array);

        $is = array();
        foreach ($u_brand_list as $key => $row) {
            $is[$key] = $row['rank'];
        }

        array_multisort($is, SORT_ASC, $u_brand_list);
        $size = sizeof($u_brand_list);



        if ($size != 0) {
            $response_array = array('status' => true, 'brands' => $u_brand_list);
        } else {
            $response_array = array('status' => false, 'message' => 'No Brands Found');
        }
        $rt = [];

        foreach ($u_brand_list as $res_list) {
            $img = $res_list['image'];
            $rt[] = [
                'img' => $img,
                'text' => $res_list['name'],
                'id' => $res_list['id'],
                'icon' => 'StarIcon',
                'c_icon' => 'ClockIcon',
                'count' => $res_list['count'],
                'restaurant' => $res_list['restaurant'],
                'restaurant_name' => $res_list['restaurant_name']

            ];
        }
        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt]);



        return $response_Array;
    }
    public function get_more_restaurant(Request $request)
    {
        // $lat = "12.9815179";
        // $lng = "80.2179755";



        $source_lat = $request->lat;
        $source_lng = $request->lng;
        $business_type = 1;

        if ($source_lat == "null" || $source_lng == "null") {
            $source_lat = DEFAULT_LATITUDE;
            $source_lng = DEFAULT_LONGITUDE;
        }

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $delivery_type = $request->delivery_type ?? 0;



        $restaurants = $this->restaurants;

        $nearbyRestaurantIDs = Restaurant::moreDistance_business_new($source_lat, $source_lng, $business_type);

        foreach ($nearbyRestaurantIDs as $res) { //print_R($res);
            $data = $restaurants->where('restaurants.status', 1)
                ->leftJoin('add_city', 'add_city.id', '=', 'restaurants.city')
                ->leftJoin('add_area', 'add_area.id', '=', 'restaurants.area')
                ->select('restaurants.*', 'add_city.city as city_name', 'add_area.area as area_name')
                ->where('restaurants.id', $res->id)->orderBy('restaurants.is_open')
                ->get();
            $data->distance = $res->distance;
            $datas[] = $data;
        }
        $schedule = DB::table('settings')->where('key_word', 'schedule')->value('value');
        if (empty($datas)) {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found');
            $response = response()->json($response_array, 200);
            return $response;
        }

        $restaurant_list = array();
        $j = 0;
        foreach ($datas as $d) {

            if ($delivery_type) {
                $rest_delivery_types = json_decode($d[0]->delivery_type);
                if (!$rest_delivery_types || !in_array($delivery_type, $rest_delivery_types)) {
                    continue;
                }
            }
            // if($j<2)  // To display only two restaurants
            // {


            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d[0]->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d[0]->id);

            $rating_statistics = get_rating_statistics($d[0]->id);

            $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();

            // $cloudflare->value = 0;
            $url = "";
            $acc_hash = "";
            if ($cloudflare->value == 1) {
                $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
                $url = $get_url->value;

                $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
                $acc_hash = $get_acchash->value;
            }

            if ($cloudflare->value == 1 && $d[0]->cloudflare_imageid != null) {


                $fimage = $url . '/' . $acc_hash . '/' . $d[0]->cloudflare_imageid;
            } else {
                $fimage = File::exists(public_path('restaurant_uploads/') . $d[0]->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->image : (File::exists(public_path('restaurant_uploads/') . $d[0]->id . '/' . 'Logo/' . $d[0]->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->id . '/' . 'Logo/' . $d[0]->image : BASE_URL . UPLOADS_PATH_LOGO . $d[0]->image);
            }

            if ($cloudflare->value == 1 && $d[0]->cloudflare_bannerid != null) {

                $fbanner = $url . '/' . $acc_hash . '/' . $d[0]->cloudflare_bannerid . '/' . "w=1000,height=136,trim=0;400";
            } else {
                // $fbanner = File::exists(public_path('restaurant_uploads/') . $d[0]->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->banner : (File::exists(public_path('restaurant_uploads/') . $d[0]->id . '/' . 'Banner/' . $d[0]->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->id . '/' . 'Banner/' . $d[0]->banner : BASE_URL . UPLOADS_PATH_BANNER . $d[0]->banner);

                if ($d[0]->banner != '') {
                    if (File::exists(public_path('restaurant_uploads/') . $d[0]->banner)) {
                        $fbanner =   BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->banner;
                    } elseif (File::exists(public_path('restaurant_uploads/') . $d[0]->id . '/' . 'Banner/' . $d[0]->banner)) {
                        $fbanner =  BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->id . '/' . 'Banner/' . $d[0]->banner;
                    } else {
                        $fbanner = BASE_URL . "def_banner.jpg";
                    }
                } else {
                    $fbanner = BASE_URL . "def_banner.jpg";
                }
            }


            $restaurant_list[] = array(
                'id' => $d[0]->id,
                'name' => $this->secondLanguage_user($d[0]->restaurant_name, $d[0]->restaurant_secondary_name),
                'image' =>  $fimage,
                'banner' => $fbanner,
                'discount' => $d[0]->discount,
                'rating' => (float) $rating_statistics['rating_percent'], //$d->rating,
                'is_open' => $is_open, // 1- Open , 0 - Close
                'distance' => $d->distance,

                'travel_time' => $d[0]->estimated_delivery_time,
                'delivery_type' => json_decode($d[0]->delivery_type),
                'price' => '',
                'is_favourite' => $is_favourite,
                'address' => $d[0]->address,
                'area' => $d[0]->area_name,
                'city' => $d[0]->city_name,
            );
            $j++;
        }


        array_multisort(
            array_column($restaurant_list, 'is_open'),
            SORT_DESC,
            array_column($restaurant_list, 'distance'),
            SORT_ASC,
            $restaurant_list
        );
        if ($data) {
            $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'schedule' => $schedule);
        } else {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found');
        }



        $rt = [];
        $co = 0;
        foreach ($restaurant_list as $res_list) {
            if ($co < 12) {
                $img = $res_list['banner'];
                $city = explode(',', $res_list['city']);
                $area = explode(',', $res_list['area']);

                $rt[] = [
                    'img' => $img,
                    'text' => $res_list['name'],
                    'travel_time' => $res_list['travel_time'],
                    'rating' => $res_list['rating'],
                    'icon' => 'StarIcon',
                    'c_icon' => 'ClockIcon',
                    'distance' => $res_list['distance'],
                    'd_icon' => 'MapPinIcon',

                    'id' => $res_list['id'],
                    'city' => $city[0],
                    'area' => $area[0],
                    'is_open' => $res_list['is_open']
                ];
                $co++;
            }
        }

        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt]);
        return $response_Array;
    }

    public function get_more_restaurant_new(Request $request)
    {
        // $lat = "12.9815179";
        // $lng = "80.2179755";



        $source_lat = $request->lat;
        $source_lng = $request->lng;
        $business_type = 1;

        if ($source_lat == "null" || $source_lng == "null") {
            $source_lat = DEFAULT_LATITUDE;
            $source_lng = DEFAULT_LONGITUDE;
        }

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $delivery_type = $request->delivery_type ?? 0;



        $restaurants = $this->restaurants;

        $nearbyRestaurantIDs = Restaurant::moreDistance_business_rest($source_lat, $source_lng, $business_type);

        foreach ($nearbyRestaurantIDs as $res) { //print_R($res);
            $data = $restaurants->with('Cuisines')->where('restaurants.status', 1)
                ->leftJoin('add_city', 'add_city.id', '=', 'restaurants.city')
                ->leftJoin('add_area', 'add_area.id', '=', 'restaurants.area')
                ->select('restaurants.*', 'add_city.city as city_name', 'add_area.area as area_name')
                ->where('restaurants.id', $res->id)->orderBy('restaurants.is_open')
                ->get();
            $data->distance = $res->distance;
            $datas[] = $data;
        }
        $schedule = DB::table('settings')->where('key_word', 'schedule')->value('value');
        if (empty($datas)) {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found');
            $response = response()->json($response_array, 200);
            return $response;
        }

        $restaurant_list = array();
        $j = 0;
        foreach ($datas as $d) {

            if ($delivery_type) {
                $rest_delivery_types = json_decode($d[0]->delivery_type);
                if (!$rest_delivery_types || !in_array($delivery_type, $rest_delivery_types)) {
                    continue;
                }
            }
            // if($j<2)  // To display only two restaurants
            // {
            $rcuisines = array();
            $i = 0;
            foreach ($d[0]->Cuisines as $r_cuisines) {
                if ($i < 2) // To display only two cuisines
                {
                    $rcuisines[] = array(
                        'name' => $this->secondLanguage_user($r_cuisines->name, $r_cuisines->secondary_name),
                    );
                    $i = $i + 1;
                }
            }

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d[0]->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d[0]->id);

            $rating_statistics = get_rating_statistics($d[0]->id);
            $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();

            $cloudflare->value = 0;
            $url = "";
            $acc_hash = "";
            if ($cloudflare->value == 1) {
                $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
                $url = $get_url->value;

                $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
                $acc_hash = $get_acchash->value;
            }

            if ($cloudflare->value == 1 && $d[0]->cloudflare_imageid != null) {


                $fimage = $url . '/' . $acc_hash . '/' . $d[0]->cloudflare_imageid;
            } else {
                $fimage = File::exists(public_path('restaurant_uploads/') . $d[0]->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->image : (File::exists(public_path('restaurant_uploads/') . $d[0]->id . '/' . 'Logo/' . $d[0]->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->id . '/' . 'Logo/' . $d[0]->image : BASE_URL . UPLOADS_PATH_LOGO . $d[0]->image);
            }

            if ($cloudflare->value == 1 && $d[0]->cloudflare_bannerid != null) {

                $fbanner = $url . '/' . $acc_hash . '/' . $d[0]->cloudflare_bannerid . '/' . "w=1000,height=136,trim=0;400";
            } else {
                // $fbanner = File::exists(public_path('restaurant_uploads/') . $d[0]->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->banner : (File::exists(public_path('restaurant_uploads/') . $d[0]->id . '/' . 'Banner/' . $d[0]->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->id . '/' . 'Banner/' . $d[0]->banner : BASE_URL . UPLOADS_PATH_BANNER . $d[0]->banner);

                if ($d[0]->banner != '') {
                    if (File::exists(public_path('restaurant_uploads/') . $d[0]->banner)) {
                        $fbanner =   BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->banner;
                    } elseif (File::exists(public_path('restaurant_uploads/') . $d[0]->id . '/' . 'Banner/' . $d[0]->banner)) {
                        $fbanner =  BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->id . '/' . 'Banner/' . $d[0]->banner;
                    } else {
                        $fbanner = BASE_URL . "def_banner.jpg";
                    }
                } else {
                    $fbanner = BASE_URL . "def_banner.jpg";
                }
            }
            $restaurant_list[] = array(
                'id' => $d[0]->id,
                'name' => $this->secondLanguage_user($d[0]->restaurant_name, $d[0]->restaurant_secondary_name),
                'image' =>  $fimage,
                'banner' => $fbanner,
                // 'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->image,
                // 'banner' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->banner,
                'discount' => $d[0]->discount,
                'rating' => (float) $rating_statistics['rating_percent'], //$d->rating,
                'is_open' => $is_open, // 1- Open , 0 - Close
                'distance' => $d->distance,
                'cuisines' => $rcuisines,
                'travel_time' => $d[0]->estimated_delivery_time,
                'delivery_type' => json_decode($d[0]->delivery_type),
                'price' => '',
                'is_favourite' => $is_favourite,
                'address' => $d[0]->address,
                'area' => $d[0]->area_name,
                'city' => $d[0]->city_name,
            );
            $j++;
        }


        array_multisort(
            array_column($restaurant_list, 'is_open'),
            SORT_DESC,
            array_column($restaurant_list, 'distance'),
            SORT_ASC,
            $restaurant_list
        );
        if ($data) {
            $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'schedule' => $schedule);
        } else {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found');
        }



        $rt = [];
        $co = 0;
        foreach ($restaurant_list as $res_list) {
            if ($co < 12) {
                $img = $res_list['banner'];
                $city = explode(',', $res_list['city']);
                $area = explode(',', $res_list['area']);

                $rt[] = [
                    'img' => $img,
                    'text' => $res_list['name'],
                    'travel_time' => $res_list['travel_time'],
                    'rating' => $res_list['rating'],
                    'icon' => 'StarIcon',
                    'c_icon' => 'ClockIcon',
                    'distance' => $res_list['distance'],
                    'd_icon' => 'MapPinIcon',
                    'cuisines' => $res_list['cuisines'],
                    'id' => $res_list['id'],
                    'city' => $city[0],
                    'area' => $area[0],
                    'is_open' => $res_list['is_open']
                ];
                $co++;
            }
        }
        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt]);
        return $response_Array;
    }



    public function get_more_restaurant_business(Request $request)
    {
        // $lat = "12.9815179";
        // $lng = "80.2179755";



        $source_lat = $request->lat;
        $source_lng = $request->lng;
        $business_id = $request->id;

        if ($source_lat == "null" || $source_lng == "null") {
            $source_lat = DEFAULT_LATITUDE;
            $source_lng = DEFAULT_LONGITUDE;
        }

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $delivery_type = $request->delivery_type ?? 0;



        $restaurants = $this->restaurants;

        if ($business_id) {
            $nearbyRestaurantIDs = Restaurant::moreDistance_business_cat($source_lat, $source_lng, $business_id);
        }
        if ($business_id) {
            $layout = DB::table('business_type')->where('id', $business_id)->first();
        }

        $datas = [];
        if ($layout->layout_id == 2) {
            foreach ($nearbyRestaurantIDs as $res) { //print_R($res);
                $data = $restaurants->where('restaurants.status', 1)
                    ->leftJoin('add_city', 'add_city.id', '=', 'restaurants.city')
                    ->leftJoin('add_area', 'add_area.id', '=', 'restaurants.area')
                    ->select('restaurants.*', 'add_city.city as city_name', 'add_area.area as area_name')
                    ->where('restaurants.id', $res->id)->orderBy('restaurants.is_open')
                    ->get();
                $data->distance = $res->distance;
                $datas[] = $data;
            }
        } else {
            foreach ($nearbyRestaurantIDs as $res) { //print_R($res);
                $data = $restaurants->with('Cuisines')->where('restaurants.status', 1)
                    ->leftJoin('add_city', 'add_city.id', '=', 'restaurants.city')
                    ->leftJoin('add_area', 'add_area.id', '=', 'restaurants.area')
                    ->select('restaurants.*', 'add_city.city as city_name', 'add_area.area as area_name')
                    ->where('restaurants.id', $res->id)->orderBy('restaurants.is_open')
                    ->get();
                $data->distance = $res->distance;
                $datas[] = $data;
            }
        }
        $schedule = DB::table('settings')->where('key_word', 'schedule')->value('value');
        if (empty($datas)) {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Store Found', 'layout_type' => $layout->layout_id);
            $response = response()->json($response_array, 200);
            return $response;
        }

        $restaurant_list = array();
        $j = 0;
        foreach ($datas as $d) {

            if ($delivery_type) {
                $rest_delivery_types = json_decode($d[0]->delivery_type);
                if (!$rest_delivery_types || !in_array($delivery_type, $rest_delivery_types)) {
                    continue;
                }
            }
            // if($j<2)  // To display only two restaurants
            // {
            $rcuisines = array();
            $i = 0;
            foreach ($d[0]->Cuisines as $r_cuisines) {
                if ($i < 2) // To display only two cuisines
                {
                    $rcuisines[] = array(
                        'name' => $this->secondLanguage_user($r_cuisines->name, $r_cuisines->secondary_name),
                    );
                    $i = $i + 1;
                }
            }

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d[0]->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d[0]->id);

            $rating_statistics = get_rating_statistics($d[0]->id);
            $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();


            $url = "";
            $acc_hash = "";
            if ($cloudflare->value == 1) {
                $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
                $url = $get_url->value;

                $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
                $acc_hash = $get_acchash->value;
            }

            if ($cloudflare->value == 1 && $d[0]->cloudflare_imageid != null) {


                $fimage = $url . '/' . $acc_hash . '/' . $d[0]->cloudflare_imageid;
            } else {
                $fimage = File::exists(public_path('restaurant_uploads/') . $d[0]->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->image : (File::exists(public_path('restaurant_uploads/') . $d[0]->id . '/' . 'Logo/' . $d[0]->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->id . '/' . 'Logo/' . $d[0]->image : BASE_URL . UPLOADS_PATH_LOGO . $d[0]->image);
            }

            if ($cloudflare->value == 1 && $d[0]->cloudflare_bannerid != null) {

                $fbanner = $url . '/' . $acc_hash . '/' . $d[0]->cloudflare_bannerid . '/' . "w=1000,height=136,trim=0;400";
            } else {
                // $fbanner = File::exists(public_path('restaurant_uploads/') . $d[0]->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->banner : (File::exists(public_path('restaurant_uploads/') . $d[0]->id . '/' . 'Banner/' . $d[0]->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->id . '/' . 'Banner/' . $d[0]->banner : BASE_URL . UPLOADS_PATH_BANNER . $d[0]->banner);

                if ($d[0]->banner != '') {
                    if (File::exists(public_path('restaurant_uploads/') . $d[0]->banner)) {
                        $fbanner =   BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->banner;
                    } elseif (File::exists(public_path('restaurant_uploads/') . $d[0]->id . '/' . 'Banner/' . $d[0]->banner)) {
                        $fbanner =  BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->id . '/' . 'Banner/' . $d[0]->banner;
                    } else {
                        $fbanner = BASE_URL . "def_banner.jpg";
                    }
                } else {
                    $fbanner = BASE_URL . "def_banner.jpg";
                }
            }
            $restaurant_list[] = array(
                'id' => $d[0]->id,
                'name' => $this->secondLanguage_user($d[0]->restaurant_name, $d[0]->restaurant_secondary_name),
                'image' =>  $fimage,
                'banner' => $fbanner,
                'discount' => $d[0]->discount,
                'rating' => (float) $rating_statistics['rating_percent'], //$d->rating,
                'is_open' => $is_open, // 1- Open , 0 - Close
                'distance' => $d->distance,
                'cuisines' => $rcuisines,
                'travel_time' => $d[0]->estimated_delivery_time,
                'delivery_type' => json_decode($d[0]->delivery_type),
                'price' => '',
                'is_favourite' => $is_favourite,
                'address' => $d[0]->address,
                'area' => $d[0]->area_name,
                'city' => $d[0]->city_name,
            );
            $j++;
        }


        array_multisort(
            array_column($restaurant_list, 'is_open'),
            SORT_DESC,
            array_column($restaurant_list, 'distance'),
            SORT_ASC,
            $restaurant_list
        );
        if ($data) {
            $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'schedule' => $schedule, 'layout_type' => $layout->layout_id);
        } else {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Store Found', 'layout_type' => $layout->layout_id);
        }



        $rt = [];
        $co = 0;
        foreach ($restaurant_list as $res_list) {

            $img = $res_list['banner'];
            $city = explode(',', $res_list['city']);
            $area = explode(',', $res_list['area']);

            $rt[] = [
                'img' => $img,
                'text' => $res_list['name'],
                'travel_time' => $res_list['travel_time'],
                'rating' => $res_list['rating'],
                'icon' => 'StarIcon',
                'c_icon' => 'ClockIcon',
                'distance' => $res_list['distance'],
                'd_icon' => 'MapPinIcon',
                'cuisines' => $res_list['cuisines'],
                'id' => $res_list['id'],
                'city' => $city[0],
                'area' => $area[0],
                'is_open' => $res_list['is_open']
            ];
        }
        if ($business_id == 0) {
            $layout = DB::table('business_type')->where('id', 2)->first();
        } else {
            $layout = DB::table('business_type')->where('id', $business_id)->first();
        }

        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt, 'layout_type' => $layout->layout_id]);
        return $response_Array;
    }

    public function search_restaurants1(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            ['key_word' => '']
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = ['status' => false, 'error_code' => 101, 'message' => $error_messages];
            return response()->json($response_array, 200);
        }

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $cart = $this->cart;
        $check_for_cart = $cart::where('user_id', $user_id)->get();
        $restaurants = $this->restaurants;
        $key_word = $request->key_word;
        $scheduleAt = $request->date . ' ' . $request->time;

        $source_lat = $request->lat;
        $source_lng = $request->lng;

        $availableRestIDs = [];

        $all_restaurant = $restaurants->where('status', 1)->select('id', 'city', 'lat', 'lng')->get();
        foreach ($all_restaurant as $each_restaurant) {
            if (!empty($source_lat) && !empty($source_lng)) {
                $geofenceRadius = (float) ($each_restaurant->GeofenceRadius ?? DEFAULT_RADIUS);
                $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $each_restaurant->lat, $each_restaurant->lng);
                if ($distance && $distance <= $geofenceRadius) {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            } else if (!empty($request->city_id)) {
                if ($each_restaurant->city == $request->city_id) {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            } else {
                $availableRestIDs[] = $each_restaurant->id;
            }
        }




        $data = $restaurants::where('status', 1)->wherein('id', $availableRestIDs)->where('restaurant_name', 'like', '%' . $key_word . '%')->where('status', 1)->get();



        $rt = [];

        foreach ($data as $res_list) {
            $img = BASE_URL . RESTAURANT_UPLOADS_PATH . $res_list->image;
            $slug =  strtolower(str_replace(' ', '-', $res_list->restaurant_name) . '-' . $res_list->id);

            $rt[] = [
                'img' => $img,
                'name' => $this->secondLanguage_user($res_list->restaurant_name, $res_list->restaurant_secondary_name),
                'restaurant_id' => $res_list->id,
                'slug' => $slug

            ];
        }




        $food_list = DB::table('food_list')
            ->wherein('food_list.restaurant_id', $availableRestIDs)
            ->where('food_list.name', 'like', '%' . $key_word . '%')
            ->join('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
            ->join('category', 'category.id', '=', 'food_list.category_id')
            ->where('food_list.status', 1)
            ->where('restaurants.status', 1)
            ->OrWhere('category_name', 'like', '%' . $key_word . '%')
            ->where('restaurants.status', 1)
            ->select(
                'food_list.*',
                'restaurants.restaurant_name',
                'restaurants.restaurant_secondary_name',
                'category.category_name'
            )
            ->orderby('restaurants.id', 'asc')
            ->get();





        $rt2 = [];
        foreach ($food_list as $dat) {
            $img = BASE_URL  . $dat->image;
            $slug =  strtolower(str_replace(' ', '-', $dat->name) . '-' . $dat->id);

            $rt2[] = [
                'img' => $img,
                'name' => $this->secondLanguage_user($dat->name, $dat->secondary_name),
                'restaurant_name' => $this->secondLanguage_user($dat->restaurant_name, $dat->restaurant_secondary_name),
                'restaurant_id' => $dat->restaurant_id,
                'id' => $dat->id,
                'slug' => $slug

            ];
        }



        $cate = DB::table('category')->where('category_name', 'like', '%' . $key_word . '%')->get();

        $rt3 = [];
        foreach ($cate as $cat) {
            $img = asset('/' . $cat->image);
            $slug =  strtolower(str_replace(' ', '-', $cat->category_name) . '-' . $cat->id);

            $rt3[] = [
                'img' => $img,
                'name' => $this->secondLanguage_user($cat->category_name, $cat->category_secondaryname),
                'restaurant_name' => $this->secondLanguage_user($cat->category_name, $cat->category_secondaryname),
                'restaurant_id' => $cat->id,
                'slug' => $slug

            ];
        }

        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt, 'food_list' => $rt2, 'cat_list' => $rt3]);
        return $response_Array;
    }

    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|min:3|max:100',
            'password' => 'required|min:6|max:100'
        ]);
        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $error_messages]);
            return $response_Array;
        }
        $user = Users::where('email', $request->email)->first();
        if (!$user) {
            $message = "Incorrect Email Address";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $message]);
            return $response_Array;
        }
        if ($user && !Hash::check($request->password, $user->password)) {
            $message = "Incorrect Email or Password";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $message]);
            return $response_Array;
        }
        if ($user && Hash::check($request->password, $user->password)) {
            $phone_req = false;
            if ($user->phone == "") {
                $phone_req = true;
            }
            $message = "Welcome $user->name ";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $user, 'phone' => $phone_req]);
            return $response_Array;
        } else {
            $message = "Incorrect Email or Password";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $message]);
            return $response_Array;
        }
    }

    public function get_profile(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $session_user_id = $request->user_id;

        $user_detail             = DB::table('users')->where('id', $session_user_id)->first();

        $user_detail->profile  = File::exists(public_path('/') . $user_detail->profile_image) ? BASE_URL . $user_detail->profile_image : $user_detail->profile_image;
        $order_list             = $this->order_history2($session_user_id);
        $current_order_list     = $this->current_order_history($session_user_id);
        $upcoming_order_list     = $this->upcoming_order_list($session_user_id);
        $wallet = $user_detail->wallet_amount;
        $favourite_list         = $this->favourites($session_user_id, $lat, $lng);

        $address_detail         = DB::table('delivery_address')->where('user_id', $session_user_id)->get();
        if (count($address_detail)) {
            foreach ($address_detail as $data) {
                if ($data->address != "") {
                    $decodedText = html_entity_decode($data->address);
                    $data->address = $decodedText;
                }
            }
        }


        $message = $user_detail;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $user_detail, 'cur_orders' => $current_order_list, 'past_orders' => $order_list, 'upcoming_orders' => $upcoming_order_list, 'wallet' => $wallet, 'address' => $address_detail, 'favourite_list' => $favourite_list]);
        return $response_Array;
    }
    public function favourites($id, $lat, $lng)
    {
        $session_user_id = $id;
        $favouritelist     = $this->favouritelist;
        $restaurants     = $this->restaurants;
        $data = $favouritelist::where('user_id', $session_user_id)->get();
        $favourite_list = array();
        $rt = [];
        if (count($data) != 0) {
            foreach ($data as $key) {

                $restaurant_detail = $restaurants::where('id', $key->restaurant_id)->first();

                $restaurant_cuisines = DB::table('restaurant_cuisines')->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
                    ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
                    ->select('restaurant_cuisines.restaurant_id as restaurant_id', 'cuisines.name as cuisine_name', 'cuisines.secondary_name as cuisine_secondary_name', 'restaurants.restaurant_name as restaurant_name')
                    ->where('restaurants.id', '=', $key->restaurant_id)
                    ->get();

                $rcuisines = array();
                $i = 0;
                foreach ($restaurant_cuisines as $r_cuisines) {

                    if ($restaurant_detail->restaurant_name == $r_cuisines->restaurant_name && $i < 2) // To display only two cuisines
                    {

                        $rcuisines[] = array(
                            'name' =>   $this->secondLanguage_user($r_cuisines->cuisine_name, $r_cuisines->cuisine_secondary_name),

                        );

                        $i = $i + 1;
                    }
                }

                if (isset($restaurant_detail->id)) {

                    $is_open = restaurant_is_open($restaurant_detail->id);
                    $distance = Restaurant::getrestdistance($lat, $lng, $restaurant_detail->id);

                    $favourite_list[] = array(
                        'restaurant_id' => $key->restaurant_id,
                        'name' => $this->secondLanguage_user($restaurant_detail->restaurant_name, $restaurant_detail->restaurant_secondary_name),
                        'image' => File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image),
                        // 'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image,
                        'discount' => $restaurant_detail->discount,
                        'rating' => $restaurant_detail->rating,
                        'is_open' => $is_open, // 1- Open , 0 - Close
                        'travel_time' => $restaurant_detail->estimated_delivery_time,
                        'price' => '',
                        'address' => $restaurant_detail->address,
                        'is_favourite' => 1,
                        'cuisines' => $rcuisines,
                        'distance' => $distance

                    );
                }
            }


            $co = 0;
            foreach ($favourite_list as $res_list) {
                $img = $res_list['image'];

                $rt[] = [
                    'img' => $img,
                    'text' => $res_list['name'],
                    'travel_time' => $res_list['travel_time'],
                    'rating' => $res_list['rating'],
                    'icon' => 'StarIcon',
                    'c_icon' => 'ClockIcon',

                    'd_icon' => 'MapPinIcon',
                    'cuisines' => $res_list['cuisines'],

                    'is_open' => $res_list['is_open'],
                    'id' => $res_list['restaurant_id'],
                    'distance' => $res_list['distance']
                ];

                $co++;
            }
        }
        return $rt;
    }
    public function current_order_history($id)
    {
        $session_user_id = $id;

        $orders = DB::table('requests')
            ->where('requests.user_id', $session_user_id)
            ->whereRaw('requests.status >= 0 AND requests.status < 7')
            ->where('requests.is_rated', 0)->latest()->limit(15)->get();

        $order_list = array();

        foreach ($orders as $key) {
            $order_detail = $this->requestdetail->where('request_id', $key->id)->get();

            $order_list_detail = array();
            foreach ($order_detail as $k) {
                if (isset($k->FoodQuantity)) $k->FoodQuantity->price = $k->food_quantity_price;
                $order_list_detail[] = array(
                    'food_id' => (!empty($k->Foodlist) ? $k->Foodlist->id : ""),
                    'food_name' => (!empty($k->Foodlist) ? $k->Foodlist->name : ""),
                    'food_quantity' => $k->quantity,
                    'tax' => (!empty($k->Foodlist) ? $k->Foodlist->tax : ""),
                    'item_price' => (!empty($k->Foodlist) ? $k->Foodlist->price : 0) * $k->quantity,
                    'is_veg' => (!empty($k->Foodlist) ? $k->Foodlist->is_veg : ""),
                    'food_size' => $k->FoodQuantity,
                    'add_ons' => $k->Addons
                );
            }

            $restaurant_detail = DB::table('restaurants')->where('id', $key->restaurant_id)->first();

            $layout = DB::table('business_type')->where('id', $restaurant_detail->business_type)->first();

            if (!empty($restaurant_detail)) {

                $order_list[] = array(
                    'request_id' => $key->id,
                    'order_id' => $key->order_id,
                    'status' => $key->status,
                    'restaurant_id' => $restaurant_detail->id,
                    'restaurant_name' => $this->secondLanguage_user($restaurant_detail->restaurant_name, $restaurant_detail->restaurant_secondary_name),
                    'restaurant_image' => File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image),

                    // 'restaurant_image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image,
                    'ordered_on' => $key->ordered_time,
                    'bill_amount' => $key->bill_amount,
                    'item_list' => $order_list_detail,
                    'item_total' => $key->item_total,
                    'offer_discount' => $key->offer_discount,
                    'restaurant_packaging_charge' => $key->restaurant_packaging_charge,
                    'tax' => $key->tax,
                    'is_cod' => $key->is_cod,
                    'delivery_charge' => $key->delivery_charge,
                    'delivery_address' => $key->delivery_address,
                    'restaurant_address' => $restaurant_detail->address,
                    'layout' => $layout->layout_id,
                    'layout_name' => $layout->name,

                    'delivery_address' => $key->delivery_address,


                    'pickup_address' => $key->pickup_address,

                    'pickup_name' => $key->pickup_name,
                    'pickup_phone' => $key->pickup_phone,
                    'drop_name' => $key->drop_name,
                    'drop_phone' => $key->drop_phone,
                    'pickup_inst' => $key->pickup_inst,
                    'drop_inst' => $key->drop_inst,
                    // 'distance' => $request->distance,
                    'instruction' => $key->instruction,
                    'carrier' => $key->carrier,
                    'package_type' => $key->package_type,
                );
            }
        }

        return $order_list;
    }
    public function upcoming_order_list($id)
    {
        $session_user_id = $id;
        $upcoming_orders = DB::table('requests')
            ->where('requests.delivery_boy_id', $session_user_id)
            ->where('requests.status', '!=', -1)
            ->where('requests.status', '!=', 10)
            ->where('requests.status', '!=', 7)
            ->get();

        $upcoming_order_list = array();

        foreach ($upcoming_orders as $key) {
            $upcoming_order_detail = $this->requestdetail->where('request_id', $key->id)->get();
            $upcoming_order_list_detail = array();
            foreach ($upcoming_order_detail as $k) {
                if (isset($k->FoodQuantity)) $k->FoodQuantity->price = $k->food_quantity_price;
                $upcoming_order_list_detail[] = array(
                    'food_id' => (!empty($k->Foodlist) ? $k->Foodlist->id : ""),
                    'food_name' => (!empty($k->Foodlist) ? $k->Foodlist->name : ""),
                    'food_quantity' => $k->quantity,
                    'tax' => (!empty($k->Foodlist) ? $k->Foodlist->tax : ""),
                    'item_price' => (!empty($k->Foodlist) ? $k->Foodlist->price : 0) * $k->quantity,
                    'is_veg' => (!empty($k->Foodlist) ? $k->Foodlist->is_veg : ""),
                    'food_size' => $k->FoodQuantity,
                    'add_ons' => $k->Addons
                );
            }

            $restaurant_details = DB::table('restaurants')->where('id', $key->restaurant_id)->first();
            $layout = DB::table('business_type')->where('id', $restaurant_details->business_type)->first();

            if (!empty($restaurant_details)) {

                $upcoming_order_list[] = array(
                    'request_id' => $key->id,
                    'order_id' => $key->order_id,
                    'restaurant_id' => $restaurant_details->id,
                    'restaurant_name' => $this->secondLanguage_user($restaurant_details->restaurant_name, $restaurant_details->restaurant_secondary_name),
                    'restaurant_image' => File::exists(public_path('restaurant_uploads/') . $restaurant_details->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_details->id : (File::exists(public_path('restaurant_uploads/') . $restaurant_details->id . '/' . 'Logo/' . $restaurant_details->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_details->id . '/' . 'Logo/' . $restaurant_details->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_details->image),

                    // 'restaurant_image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_details->image,
                    'ordered_on' => $key->ordered_time,
                    'bill_amount' => $key->bill_amount,
                    'item_list' => $upcoming_order_list_detail,
                    'item_total' => $key->item_total,
                    'offer_discount' => $key->offer_discount,
                    'restaurant_packaging_charge' => $key->restaurant_packaging_charge,
                    'tax' => $key->tax,
                    'is_cod' => $key->is_cod,
                    'delivery_charge' => $key->delivery_charge,
                    'delivery_address' => $key->delivery_address,
                    'restaurant_address' => $restaurant_details->address,
                    'layout' => $layout->layout_id,
                    'layout_name' => $layout->name,

                    'delivery_address' => $key->delivery_address,


                    'pickup_address' => $key->pickup_address,

                    'pickup_name' => $key->pickup_name,
                    'pickup_phone' => $key->pickup_phone,
                    'drop_name' => $key->drop_name,
                    'drop_phone' => $key->drop_phone,
                    'pickup_inst' => $key->pickup_inst,
                    'drop_inst' => $key->drop_inst,
                    // 'distance' => $request->distance,
                    'instruction' => $key->instruction,
                    'carrier' => $key->carrier,
                    'package_type' => $key->package_type,
                );
            }
        }
        return $upcoming_order_list;
    }
    public function order_history2($id)
    {
        $session_user_id = $id;

        $orders = DB::table('requests')
            ->where('requests.user_id', $session_user_id)
            ->where('requests.status', '!=', -1)
            ->where('requests.is_paid', 1)
            ->latest()->limit(15)->get();

        $order_list = array();
        foreach ($orders as $key) {
            $order_detail = $this->requestdetail->where('request_id', $key->id)->get();

            $order_list_detail = array();
            foreach ($order_detail as $k) {
                if (isset($k->FoodQuantity)) $k->FoodQuantity->price = $k->food_quantity_price;
                $order_list_detail[] = array(
                    'food_id' => (!empty($k->Foodlist) ? $k->Foodlist->id : ""),
                    'food_name' => (!empty($k->Foodlist) ? $k->Foodlist->name : ""),
                    'food_quantity' => $k->quantity,
                    'tax' => (!empty($k->Foodlist) ? $k->Foodlist->tax : ""),
                    'item_price' => (!empty($k->Foodlist) ? $k->Foodlist->price : 0) * $k->quantity,
                    'is_veg' => (!empty($k->Foodlist) ? $k->Foodlist->is_veg : ""),
                    'food_size' => $k->FoodQuantity,
                    'add_ons' => $k->Addons
                );
            }

            $restaurant_detail = DB::table('restaurants')->where('id', $key->restaurant_id)->first();
            $layout = DB::table('business_type')->where('id', $restaurant_detail->business_type)->first();

            if (!empty($restaurant_detail)) {

                $order_list[] = array(
                    'request_id' => $key->id,
                    'order_id' => $key->order_id,
                    'restaurant_id' => $restaurant_detail->id,
                    'restaurant_name' => $this->secondLanguage_user($restaurant_detail->restaurant_name, $restaurant_detail->restaurant_secondary_name),
                    'restaurant_image' => File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image),

                    // 'restaurant_image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image,
                    'ordered_on' => $key->ordered_time,
                    'bill_amount' => $key->bill_amount,
                    'item_list' => $order_list_detail,
                    'item_total' => $key->item_total,
                    'offer_discount' => $key->offer_discount,
                    'restaurant_packaging_charge' => $key->restaurant_packaging_charge,
                    'tax' => $key->tax,
                    'is_cod' => $key->is_cod,
                    'delivery_charge' => $key->delivery_charge,
                    'delivery_address' => $key->delivery_address,
                    'restaurant_address' => $restaurant_detail->address,
                    'layout' => $layout->layout_id,
                    'layout_name' => $layout->name,

                    'delivery_address' => $key->delivery_address,

                    'pickup_address' => $key->pickup_address,

                    'pickup_name' => $key->pickup_name,
                    'pickup_phone' => $key->pickup_phone,
                    'drop_name' => $key->drop_name,
                    'drop_phone' => $key->drop_phone,
                    'pickup_inst' => $key->pickup_inst,
                    'drop_inst' => $key->drop_inst,
                    // 'distance' => $request->distance,
                    'instruction' => $key->instruction,
                    'carrier' => $key->carrier,
                    'package_type' => $key->package_type,
                );
            }
        }

        // dd($order_list);

        return $order_list;
    }

    public function get_app_url(Request $request)
    {

        $android = DB::table('settings')->where('key_word', "android_app_link")->first();

        $ios = DB::table('settings')->where('key_word', "ios_app_link")->first();
        $facebook = DB::table('settings')->where('key_word', "facebook")->first();
        $instagram = DB::table('settings')->where('key_word', "instagram")->first();

        $youtube = DB::table('settings')->where('key_word', "youtube")->first();
        $linkedin = DB::table('settings')->where('key_word', "linkedin")->first();
        $twitter = DB::table('settings')->where('key_word', "twitter")->first();



        $links = array();

        $links[] = array(
            'android' => $android->value,
            'ios' => $ios->value,
            'instagram' => $instagram->value,
            'facebook' => $facebook->value,
            'twitter' => $twitter->value,
            'linkedin' => $linkedin->value,
            'youtube' => $youtube->value


        );
        $message = $links;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $links]);
        return $response_Array;
    }

    public function mark_as_favourite(Request $request)
    {
        $restaurant_id = $request->restaurant_id;
        $user_id = $request->user_id;
        $cond = [
            'restaurant_id' => $restaurant_id,
            'user_id' => $user_id,
        ];
        $favouritelist = $this->favouritelist;
        $favourite = $favouritelist::where($cond)->get();

        if (count($favourite) === 0) {
            $favouritelist::insert($cond);
            $message = "Restaurant Marked as favorite";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        } else {
            $favouritelist::where($cond)->delete();
            $message = "Restaurant Removed from favourite";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }
    }
    public function remove_favourite(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $restaurant_id = $request->restaurant_id;
        $user_id = $request->user_id;
        $cond = [
            'restaurant_id' => $restaurant_id,
            'user_id' => $user_id,
        ];
        $favouritelist = $this->favouritelist;
        $favourite = $favouritelist::where($cond)->get();

        if (count($favourite) === 0) {
            $favouritelist::insert($cond);
            $favourite_list         = $this->favourites($user_id, $lat, $lng);
            $message = "Restaurant Marked as favorite";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'favorites' => $favourite_list]);
            return $response_Array;
        } else {
            $favouritelist::where($cond)->delete();
            $favourite_list         = $this->favourites($user_id, $lat, $lng);
            $message = "Restaurant Removed from favourite";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'favorites' => $favourite_list]);
            return $response_Array;
        }
    }
    public function fetch_contact(Request $request)
    {
        $content = fopen(Storage::path("contact.rtf"), 'r');

        while (!feof($content)) {

            $line = fgets($content);
        }

        fclose($content);
        $aboutus[] = array(
            'contact' => $line,

        );


        $message = "About Us updated successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $aboutus]);
        return $response_Array;
    }
    public function aboutus(Request $request)
    {
        $content = fopen(Storage::path("about_us.rtf"), 'r');

        while (!feof($content)) {

            $line = fgets($content);
        }

        fclose($content);
        $aboutus[] = array(
            'aboutus' => $line,

        );


        $message = "About Us updated successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $aboutus]);
        return $response_Array;
    }
    public function privacy_policy(Request $request)
    {
        $content = fopen(Storage::path("privacypolicy.rtf"), 'r');

        while (!feof($content)) {

            $line = fgets($content);
        }

        fclose($content);
        $privacypolicy[] = array(
            'privacypolicy' => $line,

        );


        $message = "About Us updated successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $privacypolicy]);
        return $response_Array;
    }
    public function terms(Request $request)
    {
        $content = fopen(Storage::path("terms_conditions.rtf"), 'r');

        while (!feof($content)) {

            $line = fgets($content);
        }

        fclose($content);
        $terms_conditions[] = array(
            'terms_conditions' => $line,

        );

        $message = "About Us updated successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $terms_conditions]);
        return $response_Array;
    }

    public function fetch_cancellation(Request $request)
    {
        $content = fopen(Storage::path("cancellation.rtf"), 'r');

        while (!feof($content)) {

            $line = fgets($content);
        }

        fclose($content);
        $terms_conditions[] = array(
            'terms_conditions' => $line,

        );

        $message = "About Us updated successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $terms_conditions]);
        return $response_Array;
    }
    public function fetch_shipping(Request $request)
    {
        $content = fopen(Storage::path("shipping.rtf"), 'r');

        while (!feof($content)) {

            $line = fgets($content);
        }

        fclose($content);
        $terms_conditions[] = array(
            'terms_conditions' => $line,

        );

        $message = "About Us updated successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $terms_conditions]);
        return $response_Array;
    }

    public function get_site_logo(Request $request)
    {

        $logo = DB::table('settings')->where('key_word', 'site_logo')->first();
        $app_name = DB::table('settings')->where('key_word', 'app_name')->first();

        $logo_img = BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;

        $site_contact = DB::table('settings')->where('key_word', 'site_contact')->first();
        $site_email = DB::table('settings')->where('key_word', 'site_email')->first();


        $status = true;
        $response_Array = json_encode(['status' => $status, 'logo' => $logo_img, 'app_name' => $app_name->value, 'site_contact' => $site_contact->value, 'site_email' => $site_email->value]);
        return $response_Array;
    }

    public function get_defaults(Request $request)
    {


        $default_currency = DB::table('country')->where('is_default', 1)->first();
        $decimals = DB::table('settings')->where('key_word', 'decimal')->first();
        $delimiter = DB::table('settings')->where('key_word', 'delimiter')->first();
        $app = DB::table('settings')->where('key_word', "app_name")->first();



        $google_client_id = DB::table('settings')->where('key_word', "google_client_id")->first();
        $google_client_secret = DB::table('settings')->where('key_word', "google_client_secret")->first();
        $google_redirect_url = DB::table('settings')->where('key_word', "google_redirect_url")->first();



        $app_name = $app->value;

        $status = true;
        $response_Array = json_encode(['status' => $status, 'default_country' => $default_currency, 'decimal' => $decimals->value, 'delimiter' => $delimiter->value, 'app_name' => $app_name, 'google_client_id' => $google_client_id->value, 'google_client_secret' => $google_client_secret->value, 'google_redirect_url' => $google_redirect_url->value]);
        return $response_Array;
    }

    public function get_qrcode(Request $request)
    {

        $qrcode = DB::table('settings')->where('key_word', "qrcode")->first();
        $qr = BASE_URL . $qrcode->value;

        $app_name = DB::table('settings')->where('key_word', 'app_name')->first();

        $status = true;
        $response_Array = json_encode(['status' => $status, 'qrcode' => $qr, 'app_name' => $app_name->value]);
        return $response_Array;
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        $data = DB::table('users')->where('email', $request->email)->first();

        $data2 = DB::table('users')->where('phone', $request->phone)->first();



        if (isset($data->email) || isset($data2->phone)) {
            $status = false;
            $message = "Account already exist. Please login";
            $response_Array = json_encode(['status' => $status, 'message' => $message]);
            return $response_Array;
        } else {
            $user = new Users;
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            $status = true;
            $message = "Account created successfully Please login.";
            $response_Array = json_encode(['status' => $status, 'message' => $message]);
            return $response_Array;
        }
    }

    public function forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required']);

        if ($validator->fails()) {
            $message = "Email address required";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        }

        $email = $request->email;

        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            $message = "We couldn't find your email address";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        }

        $randomString = generateRandomString(20);

        DB::table('users')->where('id', $user->id)->update(['password_reset_key' => $randomString]);

        sendForgotPasswordEmail2Restaurant($user->id);

        $message = "Your password reset link has been sent to your email address";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $user->id]);


        return $response_Array;
    }

    public function reset_password(Request $request)
    {

        $user = DB::table('users')->where('id', $request->id)->where('password_reset_key', $request->code)->first();

        if (!$user) {

            $message = "Password reset code is invalid";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'code_mismatch' => 1]);


            return $response_Array;
        }

        $message = "Success";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'code_mismatch' => 0]);


        return $response_Array;
    }

    public function reset_password_submit(Request $request)
    {

        $user = DB::table('users')->where('id', $request->id)->first();



        if ($request->password != $request->passwordConfirm) {
            $message = "Password do not match";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        }

        DB::table('users')->where('id', $request->id)->update(['password' => Hash::make($request->password), 'password_reset_key' => '']);

        $message = "Your password has been reset";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }


    public function getcontactreasons(Request $request)
    {
        $contact = DB::table('contact_form_reasons')->get();
        return response()->json($contact);
    }

    public function contact_admin(Request $request)
    {


        $reason_id = $request->reason;

        $reas = DB::table('contact_form_reasons')
            ->where('id', $reason_id)
            ->first();

        $reason = $reas->reason;

        $template = DB::table('mail_contents')->where("content_for", "contact_form_template")->get();
        $cont = [];
        foreach ($template as $temp) {
            $cont[] = $temp->content;
        }
        $logo = DB::table('settings')->where('key_word', "site_logo")->first();
        $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
        $url = BASE_URL . "/admin/new";
        $reason_email = $reas->mailid;
        $html = view('emails.user.contact')->with(['request' => $request, 'reason' => $reason, 'logo' => $to_logo, 'template' => $cont, 'url' => $url]);

        $emails = json_decode($reas->mailid);
        for ($i = 0; $i < count($emails); $i++) {
            $Mailer             = new Mailer();
            $Mailer->to         = $emails[$i];
            $Mailer->toName     = $emails[$i];
            $Mailer->subject    = $reason;
            $Mailer->body       = $html;

            $Mailer->send();
        }
        $message = "Message sent successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function faq(Request $request)
    {
        $faq = DB::table('faq_title')->get();

        $qandans = [];
        $st = [];
        foreach ($faq as $res) {
            if ($request->q  == null) {
                $qanda = DB::table('faq')->where('title', $res->id)->get();
            } else {
                $qanda = DB::table('faq')->where('title', $res->id)->where('question', 'like', '%' . $request->q . '%')->orwhere('answer', 'like', '%' . $request->q . '%')->get();
            }
            if ($qanda->count() > 0) {
                foreach ($qanda as $qa) {
                    $qandans[] = [
                        'question' => $qa->question,
                        'ans' => $qa->answer
                    ];
                }
                $st[] = [
                    'icon' => $res->icon,
                    'qandA' => $qandans,
                    'subtitile' => "",
                    'title' => $res->name,

                ];
            }
            $qandans = [];
        }

        $response_Array = json_encode(['data' => $st, 'status' => true]);


        return $response_Array;
    }

    public function getpaymentmethods(Request $request)
    {

        $is_cod = DB::table('settings')->where('key_word', "cod_enable")->first();

        $is_online = DB::table('settings')->where('key_word', "onlinepayment_enable")->first();

        $response_Array = json_encode(['status' => true, 'cod' => $is_cod->value, 'online' => $is_online->value]);

        return $response_Array;
    }

    public function SocialSignup(Request $request)
    {
        // Socialite will pick response data automatic 
        $user = Socialite::driver("google")->stateless()->user();
        $check_email = Users::where('email', $user->email)->first();
        $phone_req = false;
        if ($check_email && !empty($check_email)) {
            if ($check_email->phone == "") {
                $phone_req = true;
            }
            $message = "Welcome $check_email->name ";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $check_email, 'phone' => $phone_req]);
            return $response_Array;
        }

        $new_user = [
            'phone' => '',
            'email' => $user->email ?? '',
            'authToken' => '',
            'device_type' => 'web',
            'password' => '',
            'device_token' => '',
            'facebook_id' => '',
            'name' => $user->name,
            'profile_image' => $user->avatar,
            'login_type' => 1,
            'referral_code' => '',
        ];

        DB::table('users')->insert($new_user);
        $user = Users::where('email', $user->email)->first();
        $phone_req = true;
        $check_email = Users::where('email', $user->email)->first();
        $message = "Welcome $check_email->name ";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $check_email, 'phone' => $phone_req]);
        return $response_Array;
    }

    public function get_address(Request $request)
    {


        $lat = $request->lat;
        $lng = $request->lng;


        if ($lat == "null" || $lng == "null") {
            $status = true;
            $response_Array = json_encode(['status' => $status, 'data' => "Select Address"]);
            return $response_Array;
        }
        $gapi = DB::table('settings')->where('key_word', 'google_api_key')->first();
        $ad = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true&key=$gapi->value";
        $json = file_get_contents($ad);
        $details = json_decode($json, TRUE);


        $status = true;
        $response_Array = json_encode(['status' => $status, 'data' => $details['results'][1]['address_components'][2]['long_name']]);
        return $response_Array;
    }

    public function get_near_address(Request $request)
    {

        $lat = $request->lat;
        $lng = $request->lng;
        $user_id = $request->user_id;

        $address = DB::table('delivery_address')->where('user_id', $user_id)->get();
        $near_address = [];
        foreach ($address as $ad) {

            $distance = vincentyGreatCircleDistance2($lat, $lng, $ad->lat, $ad->lng);
            // print($distance);
            if ($distance <= 0.1) {
                $near_address[] = [
                    'id' => $ad->id,
                    'user_id' => $ad->user_id,
                    'address' => $ad->address,
                    'lat' => $ad->lat,
                    'lng' => $ad->lng,
                    'flat_no' => $ad->flat_no,
                    'landmark' => $ad->landmark,
                    'is_default' => $ad->is_default,
                    'type' => $ad->type,
                    'distance' => $distance
                ];
            }
        }
        if (count($near_address) >= 1) {
            foreach ($near_address as $key => $row) {
                $is[$key] = $row['distance'];
            }

            array_multisort($is, SORT_ASC, $near_address);
            $response_array = array('status' => true, 'address' => $near_address[0]);
        } else {
            $response_array = array('status' => false, 'message' => 'No Address Found');
        }
        return json_encode($response_array);
    }
    public function checkradius(Request $request)
    {

        $user_lat = $request->user_lat;
        $user_lng = $request->user_lng;

        $restaurant_id = $request->restaurant_id;

        $rest = DB::table('restaurants')->where('id', $restaurant_id)->first();
        $lat = $rest->lat;
        $lng = $rest->lng;
        $distance = vincentyGreatCircleDistance($lat, $lng, $user_lat, $user_lng);

        if ($distance <= $rest->GeofenceRadius) {
            $response_array = array('status' => true, 'radius' => 1);
        } else {
            $response_array = array('status' => false, 'radius' => 0);
        }
        return response()->json($response_array, 200);
    }

    public function checkradius1(Request $request)
    {


        $from_lat = $request->cart_details['from_lat'];
        $from_lng = $request->cart_details['from_lng'];
        $to_lat = $request->cart_details['to_lat'];
        $to_lng = $request->cart_details['to_lng'];

        $restaurant_id = $request->restaurant_id;

        $rest = DB::table('restaurants')->where('id', $restaurant_id)->first();
        $lat = $rest->lat;
        $lng = $rest->lng;
        $distance = vincentyGreatCircleDistance($from_lat, $from_lng, $to_lat, $to_lng);

        if ($distance <= $rest->GeofenceRadius) {
            $response_array = array('status' => true, 'radius' => 1);
        } else {
            $response_array = array('status' => false, 'radius' => 0);
        }
        return response()->json($response_array, 200);
    }

    public function add_phone_number(Request $request)
    {

        $phone = $request->phone;
        $user = $request->user;

        $update = DB::table('users')->where('id', $user)->update([
            'phone' => $phone
        ]);

        $rt = DB::table('users')->where('id', $user)->first();

        $message = "Phone Number Added Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt]);
        return $response_Array;
    }

    public function get_uaddress(Request $request)
    {

        $address = DB::table('delivery_address')->where('id', $request->id)->first();
        if ($address->address != "") {
            $decodedText = html_entity_decode($address->address);
            $address->address = $decodedText;
        }
        $status = true;

        $response_Array = json_encode(['status' => $status, 'address' => $address]);

        return $response_Array;
    }

    public function edit_delivery_address(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'address' => 'required',
                'lat' => 'required',
                'lng' => 'required',
                'type' => 'required', // Type -1 Home, 2- Office, 3 -Others
                'landmark' => 'required',
                'flat_no' => 'required',
                'address_id' => 'required',
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            return redirect()->back()->withError($error_messages);
        } else {

            $address = $request->address;
            $lat = $request->lat;
            $lng = $request->lng;
            $type = $request->type;
            $flat_no = $request->flat_no;
            $landmark = $request->landmark;
            $delivery_address = $this->deliveryaddress;
            $restaurant_update = $delivery_address::findOrFail($request->address_id);

            $restaurant_update->address = $address;
            $restaurant_update->lat = $lat;
            $restaurant_update->lng = $lng;
            $restaurant_update->type = $type;
            $restaurant_update->flat_no = $flat_no;
            $restaurant_update->landmark = $landmark;
            $originalText = $request->address;
            $convertedText = mb_convert_encoding($originalText, 'HTML-ENTITIES', 'UTF-8');

            $restaurant_update->address = $convertedText;
            $restaurant_update->update();
        }
        $get_id = DB::table('delivery_address')->where('id', $request->address_id)->first();
        $user = $get_id->user_id;
        $address_detail = DB::table('delivery_address')->where('user_id', $user)->get();

        $status = true;
        $message = "Address Edited Successfully";
        $response_Array = json_encode(['status' => $status, 'address' => $address, 'address_detail' => $address_detail, 'message' => $message]);

        return $response_Array;
    }
    public function business_banner(Request $request)
    {
        $business_id = $request->id;
        $business_type = DB::table('business_type')->where('id', $business_id)->first();
        $data1 = $business_type->layout_id;
        $data = [];
        // if ($business_id == 2) {
        //     $data_img = BASE_URL . 'Grocery_banner.jpg';
        //     $name =  $business_type->name;
        // } elseif ($business_id == 3) {
        //     $data_img = BASE_URL . 'pharm_banner.jpg';
        //     $name =  $business_type->name;
        // } elseif ($business_id == 4) {
        //     $data_img = BASE_URL . 'more.png';
        //     $name =  $business_type->name;
        // } elseif ($business_id == 5) {
        //     $data_img = BASE_URL . 'pickupdrop.svg';
        //     $name =  $business_type->name;
        // } elseif ($business_id == 6) {
        //     $data_img = BASE_URL . 'flower_banner.jpg';
        //     $name =  $business_type->name;
        // } elseif ($business_id == 7) {
        //     $data_img = BASE_URL . 'Meat_banner.jpg';
        //     $name =  $business_type->name;
        // } else {
        //     $data_img = BASE_URL . 'Rest_banner.jpg';
        //     $name =  $business_type->name;
        // }
        // 'image' => File::exists(public_path('/') . $business_type->banner_image) ? BASE_URL . $business_type->banner_image : '',

        $data_img = File::exists(public_path('/') . $business_type->banner_image) ? BASE_URL . $business_type->banner_image :  BASE_URL . "def_banner.jpg";
        $name =  $business_type->name;
        $data[] = [
            'image' => $data_img,
            'name' => $name,
        ];
        return response()->json($data);
    }


    public function get_business(Request $request)
    {
        $business_id = $request->id;
        $data = DB::table('business_type')->where('id', $business_id)->first();
        return response()->json($data);
    }

    public function get_pickupdrop_product_list(Request $request)
    {

        $data = DB::table('package_type')->where('status', 1)->get();
        return response()->json($data);
    }

    public function get_carrier(Request $request)
    {

        $data = DB::table('carrier_package')->where('status', 1)->get();
        foreach ($data as $d) {
            $d->image  = BASE_URL . UPLOADS_PATH . $d->image;
        }

        return response()->json($data);
    }
    public function get_address_details_pickup(Request $request)
    {

        $delivery = DB::table('delivery_address')->where('id', $request->id)->first();
        $data = DB::table('users')->where('id', $delivery->user_id)->first();
        return response()->json($data);
    }
    public function get_address_details_drop(Request $request)
    {

        $data = DB::table('delivery_address')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function get_distance_calculate(Request $request)
    {

        $from_lat = $request->from_lat;
        $from_lng = $request->from_lng;
        $to_lat = $request->to_lat;
        $to_lng = $request->to_lng;
        $data = vincentyGreatCircleDistance($from_lat, $from_lng, $to_lat, $to_lng);
        return response()->json($data);
    }
    public function pickup_drop_checkout(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            array(

                'business_id' => 'required'
            )
        );

        $restaurants = $this->restaurants;
        $source_fromlat = $request->from_lat;
        $source_fromlng = $request->from_lng;
        $source_tolat = $request->to_lat;
        $source_tolng = $request->to_lng;
        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $availableRestIDs = [];
            // $restaurant = $restaurants::where('business_type', $request->business_id)->first();
            $all_restaurant = $restaurants->where('status', 1)->get();
            $distance = '';
            foreach ($all_restaurant as $each_restaurant) {
                if (!empty($source_fromlat) && !empty($source_fromlng)) {
                    $distance = vincentyGreatCircleDistance($source_fromlat, $source_fromlng, $source_tolat, $source_tolng);

                    if ($distance && $distance <= "10000") {
                        $availableRestIDs[] = $each_restaurant->id;
                    }
                } else {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            }



            $restIDs = array_values(array_unique($availableRestIDs));

            if ($request->business_id == 3) {
                $business = DB::table('business_type')->where('layout_id', $request->business_id)->first();
                $data = $restaurants::whereIn('id', $restIDs)->where('business_type', $business->id)->orderBy('restaurant_name', 'asc')->get();
            } else {
                $data = $restaurants::whereIn('id', $restIDs)->orderBy('restaurant_name', 'asc')->get();
            }
            $promocode_list_restaurant = [];
            if ($data) {

                $dt = Carbon::now();
                $promocode_list_restaurant = $this->promocode->where('restaurant_id', $data[0]->id)->where('status', '1')->whereRaw('"' . $dt . '" between `available_from` and `valid_till`')->get();
                // $promocode_list_restaurant = ['promocode_list_restaurant'=>$promocode_list_restaurant];

            }


            $invoice = array();
            $restaurant_detail = [];
            foreach ($data as $d) {
                $restaurant = $restaurants::where('id', $d->id)->first();
                $restaurant->is_open = restaurant_is_open($d->id);



                // FOR COUPON CODE 
                if ($request->coupon_code != "") {
                    $get_offer = DB::table('coupon_code')->where('code', $request->coupon_code)->first();
                    if (count($get_offer) != 0) {
                        $coupon_code = $request->coupon_code;
                        $offer_type = $get_offer->offer_type;
                        if ($offer_type == 0) // For % offer
                        {
                            $offer = $get_offer->value;

                            // $coupon_discount = ($total_price/100)*$offer;
                            $coupon_discount = $offer;
                        } else {
                            // For price offer
                            $coupon_discount = $get_offer->value;
                        }
                    } else {
                        $response_array = array('status' => false, 'message' => trans('constants.invalid_coupon_code'));
                        $response = response()->json($response_array, 200);
                        return $response;
                    }
                } else {
                    $coupon_code = "NA";
                    $coupon_discount = 0;
                }
                $commission = DB::table('settings')->select('value')->where('key_word', 'admin_commission')
                    ->first();

                $restaurant_packaging_charge = $restaurant->packaging_charge;
                $delivery_charge = $restaurant->restaurant_delivery_charge;
                $delivery_charge_type = $restaurant->delivery_charge_type;

                $delivery_charge_limits = DB::table('delivery_charge_limits')->select('Minimum', 'Maximum', 'Value', 'Type')->where('restaurant_id', $restaurant->id)->get();


                if ($request->package_type) {
                    $types = DB::table('package_type')->where('id', $request->package_type)->first();
                    $t = $types->name;
                } else {
                    $t = '';
                }
                if ($request->instruction == "undefined") {
                    $request->instruction = '';
                }
                if ($request->image == "undefined") {
                    $request->image = '';
                }
                $tax = DB::table('restaurants')->where('id', $d->id)->first();
                $tax_inclusive = DB::table('settings')->where('key_word', 'tax_inclusive')->first();

                $cart_details = array(
                    'user_id' => $request->user_id,
                    'from_lat' => $request->from_lat,
                    'from_lng' => $request->from_lng,
                    'to_lat' => $request->to_lat,
                    'to_lng' => $request->to_lng,
                    'from_id' => $request->from_id,
                    'to_id' => $request->to_id,
                    'pickup_address' => $request->pickup_address,
                    'drop_address' => $request->drop_address,
                    'carrier' => $request->carrier,
                    'instruction' => $request->instruction,
                    'image' => $request->image,
                    'pickup_name' => $request->pickup_name,
                    'pickup_phone' => $request->pickup_phone,
                    'pickup_inst' => $request->pickup_inst,
                    'drop_name' => $request->drop_name,
                    'drop_phone' => $request->drop_phone,
                    'drop_inst' => $request->drop_inst,
                    'package_type' => $request->package_type,
                    'package_name' => $t,
                );

                $invoice = array(
                    'offer_discount' => $coupon_discount,
                    'restaurant_packaging_charge' => $restaurant_packaging_charge,
                    'DeliveryChargeBasedOn' => $restaurant->DeliveryChargeBasedOn,
                    'delivery_charge' => $delivery_charge,
                    'delivery_charge_type' => $delivery_charge_type ? $delivery_charge_type : 1,
                    'delivery_charge_limits' => $delivery_charge_limits,
                    'coupon_code' => $coupon_code,
                    'AdminCommission' => (int)$commission->value,
                    'tax' => (int)$tax->tax,
                );
                $restaurant_detail[] = [
                    'restaurant_id' => $restaurant->id,
                    'name' => $restaurant->restaurant_name,
                    'image' => File::exists(public_path('restaurant_uploads/') . $restaurant->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant->image : (File::exists(public_path('restaurant_uploads/') . $restaurant->id . '/' . 'Logo/' . $restaurant->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant->id . '/' . 'Logo/' . $restaurant->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant->image),
                    'address' => $restaurant->address,
                    'is_open' => $restaurant->is_open,
                    'estimated_delivery_time' => $restaurant->estimated_delivery_time,
                    'is_tax_inclusive' => (int) env('IS_TAX_INCLUSIVE'),
                    'invoice' =>  $invoice,
                    'cart_details' => $cart_details,
                    'distance' => $distance,
                    'promocode' => $promocode_list_restaurant,
                ];
            }


            if (isset($request->user_id)) {
                $user = DB::table('users')->select('wallet_amount', 'name')->where('id', $request->user_id)->get();

                $response_array = array('status' => true, 'restaurants' => $restaurant_detail[0], 'user' =>  $user,);

                // $response_array = array('status' => true, 'restaurant_detail' => $restaurant_detail, 'invoice' => $invoice, 'user' => $user);
            } else {
                // $response_array = array('status' => true, 'restaurant_detail' => $restaurant_detail, 'invoice' => $invoice);


                $response_array = array('status' => true, 'restaurants' => $restaurant_detail[0]);
            }
        }

        return response()->json($response_array, 200);
    }
    public function get_tips(Request $request)
    {

        $tips_enable = DB::table('settings')->where('key_word', "tips_enable")->first();
        $selected_tips = DB::table('tips')->where('selected', 1)->get();
        $tips_text = DB::table('settings')->where('key_word', "tips_text")->first();

        $sel = [];
        foreach ($selected_tips as $st) {
            $sel[] = [
                $st->value
            ];
        }
        $status = true;

        $response_Array = json_encode(['status' => $status, 'tips' => $sel, 'tips_enable' => $tips_enable->value, 'tips_text' => $tips_text->value]);

        return $response_Array;
    }

    public function get_cancellation_reasons()
    {
        $reasons = DB::table('cancellation_reason')->select('reason')
            ->where('cancellation_for', 2)->where('status', 1)->pluck('reason')->toArray();

        return response()->json(['status' => true, 'message' => 'Success', 'reasons' => $reasons], 200);
    }

    public function get_settings()
    {
        $settings = DB::table('settings')->where('status', 1)->get();

        return response()->json(['status' => true, 'message' => 'Success', 'setting' => $settings], 200);
    }

    public function order_cancel(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'request_id' => 'required',
                'detail' => 'required'
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
            $response = response()->json($response_array, 200);
            return $response;
        }

        $request_id = $request->request_id;

        $request_data = $this->foodrequest->where('id', $request_id)->first();



        $this->foodrequest->where('id', $request_id)->update(['status' => 11]);

        $status_entry = [
            'request_id' => $request_id,
            'status' => 11,
            'detail' => $request->detail
        ];

        $this->trackorderstatus->insert($status_entry);


        return response()->json(['status' => true, 'message' => 'Order cancelled Successfully'], 200);
    }
    public function get_more_restaurant_business_new(Request $request)
    {
        // $lat = "12.9815179";
        // $lng = "80.2179755";



        $source_lat = $request->lat;
        $source_lng = $request->lng;
        $business_id = $request->id;

        if ($source_lat == "null" || $source_lng == "null") {
            $source_lat = DEFAULT_LATITUDE;
            $source_lng = DEFAULT_LONGITUDE;
        }

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $delivery_type = $request->delivery_type ?? 0;



        $restaurants = $this->restaurants;

        if ($business_id == 0) {
            $nearbyRestaurantIDs = Restaurant::moreDistance_business_new($source_lat, $source_lng, 1);
        } else {
            $nearbyRestaurantIDs = Restaurant::moreDistance_business_rest($source_lat, $source_lng, $business_id);
        }
        if ($business_id == 0) {
            $layout = DB::table('business_type')->where('id', 2)->first();
        } else {
            $layout = DB::table('business_type')->where('id', $business_id)->first();
        }

        $datas = [];
        if ($layout->layout_id == 2) {
            foreach ($nearbyRestaurantIDs as $res) { //print_R($res);
                $data = $restaurants->where('restaurants.status', 1)
                    ->leftJoin('add_city', 'add_city.id', '=', 'restaurants.city')
                    ->leftJoin('add_area', 'add_area.id', '=', 'restaurants.area')
                    ->select('restaurants.*', 'add_city.city as city_name', 'add_area.area as area_name')
                    ->where('restaurants.id', $res->id)->orderBy('restaurants.is_open')
                    ->get();
                $data->distance = $res->distance;
                $datas[] = $data;
            }
        } else {
            foreach ($nearbyRestaurantIDs as $res) { //print_R($res);
                $data = $restaurants->with('Cuisines')->where('restaurants.status', 1)
                    ->leftJoin('add_city', 'add_city.id', '=', 'restaurants.city')
                    ->leftJoin('add_area', 'add_area.id', '=', 'restaurants.area')
                    ->select('restaurants.*', 'add_city.city as city_name', 'add_area.area as area_name')
                    ->where('restaurants.id', $res->id)->orderBy('restaurants.is_open')
                    ->get();
                $data->distance = $res->distance;
                $datas[] = $data;
            }
        }
        $schedule = DB::table('settings')->where('key_word', 'schedule')->value('value');
        if (empty($datas)) {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found', 'layout_type' => $layout->layout_id);
            $response = response()->json($response_array, 200);
            return $response;
        }

        $restaurant_list = array();
        $j = 0;
        foreach ($datas as $d) {

            if ($delivery_type) {
                $rest_delivery_types = json_decode($d[0]->delivery_type);
                if (!$rest_delivery_types || !in_array($delivery_type, $rest_delivery_types)) {
                    continue;
                }
            }
            // if($j<2)  // To display only two restaurants
            // {
            $rcuisines = array();
            $i = 0;
            foreach ($d[0]->Cuisines as $r_cuisines) {
                if ($i < 2) // To display only two cuisines
                {
                    $rcuisines[] = array(
                        'name' => $this->secondLanguage_user($r_cuisines->name, $r_cuisines->secondary_name),
                    );
                    $i = $i + 1;
                }
            }

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d[0]->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d[0]->id);

            $rating_statistics = get_rating_statistics($d[0]->id);

            $restaurant_list[] = array(
                'id' => $d[0]->id,
                'name' => $this->secondLanguage_user($d[0]->restaurant_name, $d[0]->restaurant_secondary_name),
                'image' => File::exists(public_path('restaurant_uploads/') . $d[0]->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->image : (File::exists(public_path('restaurant_uploads/') . $d[0]->id . '/' . 'Logo/' . $d[0]->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->id . '/' . 'Logo/' . $d[0]->image : BASE_URL . UPLOADS_PATH_LOGO . $d[0]->image),
                'banner' => File::exists(public_path('restaurant_uploads/') . $d[0]->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->banner : (File::exists(public_path('restaurant_uploads/') . $d[0]->id . '/' . 'Banner/' . $d[0]->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->id . '/' . 'Banner/' . $d[0]->banner : BASE_URL . UPLOADS_PATH_BANNER . $d[0]->banner),
                // 'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->image,
                // 'banner' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d[0]->banner,
                'discount' => $d[0]->discount,
                'rating' => (float) $rating_statistics['rating_percent'], //$d->rating,
                'is_open' => $is_open, // 1- Open , 0 - Close
                'distance' => $d->distance,
                'cuisines' => $rcuisines,
                'travel_time' => $d[0]->estimated_delivery_time,
                'delivery_type' => json_decode($d[0]->delivery_type),
                'price' => '',
                'is_favourite' => $is_favourite,
                'address' => $d[0]->address,
                'area' => $d[0]->area_name,
                'city' => $d[0]->city_name,
            );
            $j++;
        }


        array_multisort(
            array_column($restaurant_list, 'is_open'),
            SORT_DESC,
            array_column($restaurant_list, 'distance'),
            SORT_ASC,
            $restaurant_list
        );
        if ($data) {
            $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'schedule' => $schedule, 'layout_type' => $layout->layout_id);
        } else {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found', 'layout_type' => $layout->layout_id);
        }



        $rt = [];
        $co = 0;
        foreach ($restaurant_list as $res_list) {

            $img = $res_list['banner'];
            $city = explode(',', $res_list['city']);
            $area = explode(',', $res_list['area']);

            $rt[] = [
                'img' => $img,
                'text' => $res_list['name'],
                'travel_time' => $res_list['travel_time'],
                'rating' => $res_list['rating'],
                'icon' => 'StarIcon',
                'c_icon' => 'ClockIcon',
                'distance' => $res_list['distance'],
                'd_icon' => 'MapPinIcon',
                'cuisines' => $res_list['cuisines'],
                'id' => $res_list['id'],
                'city' => $city[0],
                'area' => $area[0],
                'is_open' => $res_list['is_open']
            ];
        }
        if ($business_id == 0) {
            $layout = DB::table('business_type')->where('id', 2)->first();
        } else {
            $layout = DB::table('business_type')->where('id', $business_id)->first();
        }

        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt, 'layout_type' => $layout->layout_id]);
        return $response_Array;
    }

    public function add_wallet(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'user_id' => 'required',
                'amount' => 'required'
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $default_currency = DB::table('country')->where('is_default', 1)->first();

            $payment_gateway = 'Stripe';
            $checkout_url =  $request->amount;
            $payment_id = '';
            $users = DB::table('users')->where('id', $request->user_id)->first();
            $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
            $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
            $version = DB::table('settings')->where('key_word', 'stripe_version')->first();
            $stripe = new Stripe($sk->value, $version->value);
            $paymentIntent = $stripe->paymentIntents()->create([
                'shipping' => [
                    'name' => $users->name,
                    'address' => [
                        'line1' =>  $users->email,
                        'postal_code' => '98140',
                        'city' => 'San Francisco',
                        'state' => 'CA',
                        'country' => 'US',
                    ],
                ],
                'amount' =>  $request->amount,
                'currency' => $default_currency->currency_code,
                'payment_method_types' => [
                    'card',

                ],
                'description' => 'PickupDrop',

            ]);
            if (!isset($paymentIntent['id'])) {
                return false;
            }
            $PaymentID = $paymentIntent['id'];
            $ClientSecret = $paymentIntent['client_secret'];

            $response_data_array = [
                'checkout_url' => $checkout_url,
                'PaymentID' => $PaymentID,
                'ClientSecret' => $ClientSecret,
            ];
        }
        $response_array = [
            'status' => true,
            'data' => $response_data_array,
            'message' => 'Add Wallet Successfully'
        ];

        return response()->json($response_array, 200);
    }

    public function check_pay_for_wallet(Request $request)
    {
        $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
        $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
        $version = DB::table('settings')->where('key_word', 'stripe_version')->first();
        $default_currency = DB::table('country')->where('is_default', 1)->first();
        $stripe = new Stripe($sk->value, $version->value);

        // $order = DB::table('requests')->where('id', $request->orderID)->first();



        if ($request->redirect_status == "succeeded") {
            $user_detail = $this->users->find($request->id);
            $amount = $request->amount;
            $status_entry = array();

            $status_entry[] = array(
                'user_id' => $request->id,
                'amount' => $amount,
                'type' => 3,
                'status' => 0

            );
            DB::table('user_wallet_history')->insert($status_entry);

            $this->users->find($request->id)->increment('wallet_amount', $amount);
            $user_noti = DB::table('users')->where('id', $request->id)->first();
            $title = "Wallet";
            $message = 'Your wallet is topped up with AED ' . $amount;
            sendNotification([$user_noti->device_token], $title, $message, ['amount' => $amount]);

            $url = "wallet";
            return redirect($url);
        } else {
            $response_array = [
                'status' => false,
            ];
        }
        $response_array = [
            'status' => true,

        ];
        return response()->json($response_array, 200);
    }

    public function setlocale($locale)
    {
        if ($locale) {
            session(['locale_user' => $locale]);
        }
        \Session::save();
        $response_array = array('status' => true,);
        return response()->json($response_array, 200);
    }
    public function setlocale_lang(Request $request)
    {
        $data = session('locale_user');

        $response_array = array('data' => $data);

        return response()->json($response_array, 200);
    }

    public function plans(Request $request)
    {

        $plan_enable = DB::table('settings')->where('key_word', 'plan_enable')->first();
        $plans = DB::table('plans')->where('status', 1)->get();

        $status = true;
        $response_Array = json_encode(['plans' => $plans, 'status' => $status, 'plan_enable' => $plan_enable->value]);
        return $response_Array;
    }


    public function get_plan_details(Request $request)
    {
        $plan = $request->plan;
        $rest = $request->rest_id;

        $plan_d = DB::table('plans')->where('id', $plan)->first();

        $rest_d = DB::table('restaurants')->where('id', $rest)->first();

        $status = true;

        $response_Array = json_encode(['status' => $status, 'plan' => $plan_d, 'restaurant' => $rest_d]);

        return $response_Array;
    }

    public function get_plan_byid(Request $request)
    {
        $plan = $request->plan;


        $plan_d = DB::table('plans')->where('id', $plan)->first();



        $status = true;

        $response_Array = json_encode(['status' => $status, 'plan' => $plan_d]);

        return $response_Array;
    }

    public function landing(Request $request)
    {


        $business_type = DB::table('business_type')->where('status', 1)->get();

        if (count($business_type) > 1) {
            $single_business = false;
        } else {
            $single_business = true;
            $business_type = DB::table('business_type')->where('status', 1)->first();

            $single_business_id = $business_type->id;
        }

        $status = true;

        $response_Array = json_encode(['status' => $status, 'single_business' => $single_business, 'single_business_id' => $single_business_id]);
        return $response_Array;
    }
    public function calc_delivery_charge(Request $request)
    {
        $user_lat = $request->lat;
        $user_lng = $request->lng;
        $rest_id = $request->restaurant_id;
        $restaurant = DB::table('restaurants')->where('id', $rest_id)->first();
        $rest_lat = $restaurant->lat;
        $rest_lng = $restaurant->lng;
        $distance = 0;
        $del_charge = 0;


        $del_charge = 0;
        $delivery_charge_calc1 = 0;
        $delivery_charge_calc2 = 0;
        $delivery_charge_calc3 = 0;
        $tot = 0;
        $dc_rate = 0;
        $dc_total = 0;
        $dckm_rate = 0;
        $rate = 0;
        $deliver_charge_mode = $restaurant->DeliveryChargeBasedOn;
        $delivery_charge_limits = DB::table('delivery_charge_limits')->where('restaurant_id', $rest_id)->get();

        $distance = vincentyGreatCircleDistance($rest_lat, $rest_lng, $user_lat, $user_lng);
        $total_price = $request->itemtotal;

        // $distance = distanceMatrix($rest_lat, $rest_lng, $user_lat, $user_lng);

        // $distance = direction_distance($rest_lat, $rest_lng, $user_lat, $user_lng);
        $distance = round($distance);
        $delivery_charge_type = 1;
        $delivery_charge_calc = 0;
        if (count($delivery_charge_limits)) {
            foreach ($delivery_charge_limits as $dcl) {
                if ($deliver_charge_mode == 1) {
                    if (
                        $total_price >= $dcl->Minimum &&
                        ($dcl->Maximum == null || $total_price <= $dcl->Maximum)
                    ) {
                        $delivery_charge_calc =  (float) $dcl->Value;
                        $delivery_charge_type = $dcl->Type;
                    }
                }

                if ($deliver_charge_mode == 2) {
                    if ($dcl->Type == 1) {
                        if ($distance >= $dcl->Minimum && ($dcl->Maximum == null || $distance <= $dcl->Maximum) && $dcl->Type == 1) {

                            $delivery_charge_calc1 = (float) $dcl->Value;
                            $delivery_charge_calc3 += $delivery_charge_calc1;
                            $delivery_charge_type = $dcl->Type;
                            $delivery_charge_calc = $delivery_charge_calc1;
                        }
                    }

                    if ($dcl->Type == 3) {
                        for ($i = 1; $i <= $distance; $i++) {
                            if ($i <= $dcl->Maximum && $i >= $dcl->Minimum && $dcl->Type == 3) {
                                $total = $dcl->Value * 1;
                                $tot += $total;
                                $delivery_charge_calc2 = (float) $tot;
                            } else {
                                if ($i == $dcl->Maximum) {
                                    $dc_rate = $dcl->Value;
                                }
                            }
                            // print($delivery_charge_calc2);

                            $dckm_rate = (float) $dc_rate + (float) $delivery_charge_calc2;
                        }
                    }



                    if ($dckm_rate) {
                        $delivery_charge_calc = (float) $delivery_charge_calc3 + (float) $dckm_rate;
                    } else {
                        $delivery_charge_calc = (float) $delivery_charge_calc3;
                    }





                    $dc_total += $delivery_charge_calc;
                }
            }

            if ($deliver_charge_mode == 1 && $delivery_charge_type == 2) {
                $delivery_charge_calc = (float) (($total_price / 100) * $delivery_charge_calc);
            }

            $delivery_charge_calc = (float)((float)($delivery_charge_calc));

            $del_charge = $delivery_charge_calc;
        }




        $response_array = ['status' => true, 'delivery_charge' => $del_charge, 'distance' => $distance];

        return response()->json($response_array, 200);
    }

    public function get_page_details(Request $request)
    {
        $page_details = DB::table('home_page')->where('id', $request->id)->first();

        $page_details->image = BASE_URL . "pages/" . $page_details->image;
        $content = fopen(Storage::path($page_details->content_file_name), 'r');

        while (!feof($content)) {

            $line = fgets($content);
        }

        fclose($content);
        $data[] = array(
            'data' => $line,

        );


        $status = true;
        $response_Array = json_encode(['page_details' => $page_details, 'status' => $status, 'data' => $data]);
        return $response_Array;
    }

    public function get_header_contents(Request $request)
    {
        $cats = DB::table('header_category')->where('status', 1)->orderby('rank', 'asc')->get();
        foreach ($cats as $cat) {
            $to_show = [];
            $pages = DB::table('header_page_contents')->where('header_category', $cat->id)->get();
            if (count($pages)) {
                foreach ($pages as $pg) {
                    $slug = strtolower(str_replace(' ', '-', $pg->name) . '-' . $pg->id);

                    $to_show[] = [
                        'text' => $pg->name,
                        'type' => 1,
                        'id' => $pg->id,
                        'slug' => $slug
                    ];
                }
            }
            $cat->to_show = $to_show;
        }
        $to_show = [];
        $s_pages = DB::table('header_page_contents')->where('header_category', null)->where('status', 1)->get();
        if (count($s_pages)) {
            foreach ($s_pages as $pg) {
                $slug = strtolower(str_replace(' ', '-', $pg->name) . '-' . $pg->id);

                $to_show[] = [
                    'text' => $pg->name,
                    'type' => 2,
                    'id' => $pg->id,
                    'slug' => $slug

                ];
                $to_show = $to_show;
            }
        }
        $status = true;
        $response_Array = json_encode(['cats' => $cats, 'status' => $status, 'to_show' => $to_show]);
        return $response_Array;
    }

    public function validate_pincode(Request $request)
    {
        // $lat = $request->lat;
        // $lng = $request->lng;
        // $gapi = DB::table('settings')->where('key_word', 'google_api_key')->first();
        // $ad = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true&key=$gapi->value";
        // $json = file_get_contents($ad);
        // $details = json_decode($json, TRUE);


        // $rest = DB::table('restaurants')->where('id', $request->rest)->first();
        // $delivery_areas = $rest->delivery_states;

        // $sts = [];
        // foreach (json_decode($delivery_areas) as $ar) {
        //     $state = DB::table('state')->where('id', $ar)->first();
        //     $sts[] = $state->state;
        // }

        // $addr_comp = $details['results'][1]['address_components'];
        // $state = "";
        // $is_deliverable = false;
        // foreach ($addr_comp as $ac) {
        //     $types_array = $ac['types'];
        //     foreach ($types_array as $key) {
        //         if ($key == "administrative_area_level_1") {
        //             $state = $ac['long_name'];
        //             if (in_array($state, $sts)) {
        //                 $is_deliverable = true;
        //             }
        //         }
        //     }
        // }

        $is_deliverable = true;
        $status = true;
        $response_Array = json_encode(['status' => $status, 'is_deliverable' => $is_deliverable]);
        return $response_Array;
    }

    public function track_user_order(Request $request)
    {
        $order_id = $request->order_id;

        $order = DB::table('requests')->where('order_id', $order_id)->first();
        if (!$order) {

            $status = false;
            $message = "Order Not Found";
            $response_Array = json_encode(['status' => $status, 'message' => $message]);
            return $response_Array;
        }

        $request_id = $order->id;
        $trackorderstatus     = $this->trackorderstatus;

        $check_request         = $this->foodrequest::where([
            'id' => $request_id,

        ])->first();

        $restaurant_detail     = DB::table('restaurants')->where('id', $check_request->restaurant_id)->first();
        $layout = DB::table('business_type')->where('id', $restaurant_detail->business_type)->first();
        $order_status = array();

        $item_list = DB::table('request_detail')->where('request_id', $request_id)->get();

        $item_count = 0;

        if ($check_request->delivery_boy_id != 0) {
            $delivery_boy_id = $check_request->delivery_boy_id;

            $delivery_boy_detail = DB::table('delivery_partners')->where('id', $delivery_boy_id)->first();

            $delivery_boy_name = $delivery_boy_detail->name;

            $delivery_boy_image = $delivery_boy_detail->profile_pic;

            $delivery_boy_phone = $delivery_boy_detail->phone;
        } else {
            $delivery_boy_id = 0;
            $delivery_boy_name = "";
            $delivery_boy_image = "";
            $delivery_boy_phone = "";
        }
        foreach ($item_list as $list) {
            $item_count = $item_count + $list->quantity;
        }

        $order_detail = $this->requestdetail->where([
            'request_id' => $request_id
        ])->get();

        $order_list_detail = array();
        foreach ($order_detail as $k) {
            if (isset($k->FoodQuantity)) $k->FoodQuantity->price = $k->food_quantity_price;
            $order_list_detail[] = array(
                'food_id' => (!empty($k->Foodlist) ? $k->Foodlist->id : ""),
                'food_name' => (!empty($k->Foodlist) ? $k->Foodlist->name : ""),
                'food_quantity' => $k->quantity,
                'tax' => (!empty($k->Foodlist) ? $k->Foodlist->tax : ""),
                'item_price' => (!empty($k->Foodlist) ? $k->Foodlist->price : 0) * $k->quantity,
                'is_veg' => (!empty($k->Foodlist) ? $k->Foodlist->is_veg : ""),
                'food_size' => $k->FoodQuantity,
                'add_ons' => $k->Addons,
                'individual_price' => (!empty($k->Foodlist) ? $k->Foodlist->price : 0)
            );
        }

        if ($check_request->status >= 2) {
            $n_status = "Order Confirmed";
        } elseif ($check_request->status >= 4) {
            $n_status = "Order Picked Up";
        } elseif ($check_request->status >= 6) {
            $n_status = "Order Delivered";
        } else {
            $n_status = "Order Received";
        }

        if ($layout->layout_id == 3) {
            $lat_res = $check_request->pickup_lat;
            $lng_res = $check_request->pickup_lng;
        } else {

            $lat_res =  $restaurant_detail->lat;
            $lng_res =  $restaurant_detail->lng;
        }

        $order_status[] = array(
            'request_id' => $request_id,
            'order_id' => $check_request->order_id,
            'ordered_time' => $check_request->ordered_time,
            'restaurant_name' => $restaurant_detail->restaurant_name,
            'restaurant_image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image,
            'item_count' => $item_count,
            'item_total' => $check_request->item_total,
            'offer_discount' => $check_request->offer_discount,
            'restaurant_packaging_charge' => $check_request->restaurant_packaging_charge,
            'tax' => $check_request->tax,
            'tips' => $check_request->tips,
            'delivery_charge' => $check_request->delivery_charge,
            'bill_amount' => $check_request->bill_amount,
            'status' => $n_status,
            'is_cod' => $check_request->is_cod,
            'item_list' => $order_list_detail,
            'delivery_address' => $check_request->delivery_address,
            // 'door' => $deliver_add->flat_no,
            // 'landmark' => $deliver_add->landmark,
            'delivery_boy_id' => $delivery_boy_id,
            'delivery_boy_name' => $delivery_boy_name,
            'delivery_boy_image' => $delivery_boy_image,
            'delivery_boy_phone' => $delivery_boy_phone,
            'restaurant_address' => $restaurant_detail->address,
            'restaurant_lat' => $lat_res,
            'restaurant_lng' => $lng_res,
            'user_lat' => $check_request->d_lat,
            'user_lng' => $check_request->d_lng,

        );

        $tracking_detail = $trackorderstatus::where('request_id', $request_id)->get();
        foreach ($tracking_detail as $td) {
            $date = Carbon::parse($td->created_at);
            $td->created_at_time = $date->isoFormat('Do MMMM YYYY, h:mm:ss a');
        }


        $datas = file_get_contents(FIREBASE_URL . "/available_providers/.json");
        $data = json_decode($datas);
        $locations = [];
        $i = 1;

        if (isset($data)) {
            foreach ($data as $driver_key => $each_driver) {
                if ($driver_key == $delivery_boy_id) {
                    $this_driver = DB::table('delivery_partners')->where('id', $driver_key)->first();
                    $driver = $driver_key;
                    if ($this_driver != null) {
                        $locations[] = [ucfirst($this_driver->name), $each_driver->lat, $each_driver->lng, $i++];
                        $rider_id = $this_driver->id;
                    } else {
                        $locations[] = null;
                    }
                } else {
                }
            }
        } else {
            $locations = [];
        }
        if ($layout->layout_id == 3) {
            $locations1 = json_encode($locations);
            $locations = json_decode($locations1);
        } else {
            $locations = json_encode($locations);
        }


        $center = [];
        $user = [];
        if (!$center) {
            $center = [$check_request->d_lat, $check_request->d_lng];
        }
        if (!isset($rider_id)) {
            $rider_id = null;
        }

        $user[0] = $order_status[0]['user_lat'];
        $user[1] = $order_status[0]['user_lng'];
        $user = json_encode($user);



        $status = true;
        $response_Array = json_encode(['order_status' => $order_status, 'status' => $status, 'rider_id' => $rider_id, 'tracking_detail' => $tracking_detail, 'locations' => $locations, 'center' => $center, 'user' => $user]);
        return $response_Array;
    }

    public function categoryWiseProductList($categoryid)
    {
        $productList = DB::table('food_list')
            ->leftJoin('business_category', 'business_category.id', '=', 'food_list.business_category_id')
            ->where('food_list.business_category_id', $categoryid) // changed condition
            ->select(
                'food_list.id as food_id',
                'business_category.category_name as business_name',
                'food_list.*',
                'food_list.status as f_status',
                'food_list.out_of_stock as stock_status'
            )
            ->get();


        $status = $productList->isNotEmpty(); // returns true if there’s any record

        return response()->json([
            'status' => $status,
            'data' => $productList,
        ]);
    }
}
