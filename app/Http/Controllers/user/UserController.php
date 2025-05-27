<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\web\BaseController;
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
       // dd($user_id);
       $user_number =$user ? $user->phone : null;
      // dd($user_number);
        $amount = 0;
        $quantity = 0;
        $foodlist = $this->foodlist;
        $cart = $this->cart;
        $cart = $cart::where('user_id', $user_id)->get();
	$city = $this->addcity->where('status',1)->get();
	$restaurants = $this->restaurants->where('status' ,1)->get();//dd($restaurants);
        foreach ($cart as $key) {
            $food_detail = $foodlist::where('id', $key->food_id)->first();
            $amount = $amount + ($food_detail->price * $key->quantity);
            $quantity = $quantity + $key->quantity;
        }
        $food_cart[] = array(
            'amount' => $amount,
            'quantity' => $quantity,
        );
        return view('user.home', compact('user_number','user_id','popular', 'banner', 'cuisines', 'cities', 'categories', 'cart', 'food_cart','city','restaurants'));
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
    public function mark_as_favourite(Request $request)
    {
        $restaurant_id = $request->restaurant_id;
        $user_id = Auth::guard('user')->user()->id;
        $cond = [
            'restaurant_id' => $restaurant_id,
            'user_id' => $user_id,
        ];
        $favouritelist = $this->favouritelist;
        $favourite = $favouritelist::where($cond)->get();
        // dd(count($favoursite));
        if (count($favourite) === 0) {
            $favouritelist::insert($cond);
            return redirect()->back()->with('message', 'Added to favourite')->withInput();
        } else {
            $favouritelist::where($cond)->delete();

            return redirect()->back()->with('message', 'Removed from favourite')->withInput();
        }
    }
    public function get_default_address(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }
        // $user_id = $request->header('authId');
        $delivery_address = $this->deliveryaddress;

        $data = $delivery_address::where('user_id', $user_id)->where('is_default', 1)->first();

        if (count($data) != 0) {
            $response_array = array('status' => true, 'data' => $data);
        } else {
            $response_array = array('status' => false, 'message' => 'No address found');
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function set_delivery_address(Request $request)
    {
        $user_id = Auth::guard('user')->user()->id;

        $address_id = $request->address_id;

        $address = $this->deliveryaddress::where('user_id', $user_id)->where('id', $address_id)->first();
        if ($address) {
            $data = $address->update(['is_default' => 1]);
            // dd($address);
            // dd($data);
            $this->deliveryaddress::where('user_id', $user_id)
                ->where('id', '!=', $address_id)
                ->update(['is_default' => 0]);

            return redirect()->back()->with('message', trans('constants.address_changed_success'));
        } else {
            return redirect()->back()->withError(trans('constants.address_changed_failed'));
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
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }
        // $user_id = $request->header('authId');
        $delivery_address = $this->deliveryaddress;
        $data = $delivery_address::where('user_id', $user_id)->get();

        if (count($data) != 0) {
            return response()->json(['status' => true, 'data' => $data]); // type - 1 home, 2 work, 3 others
        } else {
            return response()->json(['status' => false, 'message' => 'No address found']);
        }
    }

    public function offers_page(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
            $user = Auth::guard('user')->user();
    
            $user_id = $user ? $user->id : 0;
           // dd($user_id);
           $user_number =$user ? $user->phone : null;
        } else {
            $user_id = $request->authId;
            $user_id =  0;
            // dd($user_id);
            $user_number = null;
           
        }
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

        return view('user.offers')->with(compact('promocode_list','user_id','user_number'));
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
            return redirect()->back()->withError($error_messages);
        } else {
            $user_id = Auth::guard('user')->user()->id;
            $this->deliveryaddress::where('user_id', $user_id)
            ->update(['is_default' => 0]);
            // $user_id = $request->header('authId');
            $address = $request->address;
            $lat = $request->lat;
            $lng = $request->lng;
            $type = $request->type;
            $flat_no = $request->flat_no;
            $landmark = $request->landmark;
            $delivery_address = $this->deliveryaddress;
            
            $check_for_default_address = $delivery_address::where('user_id', $user_id)->where('is_default', 1)->get();
          
            // if (count($check_for_default_address) != 0) {
            //     $is_default = 0;
            // } else {
            //     $is_default = 1;
            // }
            $is_default = 1;
            $insert_data = array();
  
             $insert_data[] = array(
                'user_id' => $user_id,
                'address' => $address,
                'lat' => $lat,
                'lng' => $lng,
                'type' => $type,
                'flat_no' => $flat_no,
                'landmark' => $landmark,
                'is_default' => $is_default,
            );
            
            $id = $delivery_address::insert($insert_data);
            $data = $delivery_address::where('user_id', $user_id)->orderBy('id', 'desc')->first();
            $response_array = array('status' => true, 'data' => $data, 'message' => trans('constants.address_added_success'));
        }
        return redirect()->back()->with('message', trans('constants.address_added_success'));
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

            // $query = $query->when(($request->is_pureveg==1),
            //             function($q){
            //                 return $q->where('offer_amount','!=',0);
            //             });

            $data = $query->get();

            $restaurant_list = array();
            foreach ($data as $d) {

                $rcuisines = array();
                $i = 0;
                foreach ($d->Cuisines as $r_cuisines) {
                    if ($i < 2) {
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

                if (sizeof($rcuisines) > 0) {
                    $restaurant_list[] = array(
                        'id' => $d->id,
                        'name' => $this->updateRestaurantName($d),
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
                'name' => $this->updateRestaurantName($d),
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
            );
        }

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
        $session_user_id = Auth::guard('user')->user()->id;
        $custom = $this->custom;
        $user = Users::find($session_user_id);
       if($request->otp != null)
       {
        if($request->otp ==  $user->otp)
        {
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
            return back()->with('flash_success', 'Profile updated successfully.');
        }
        else{
            return back()->with('flash_success', 'Otp is wrong');
        }

       }
       else{
        $user->name = $request->has('name') ? $request->name : $user->name;
        // $user->phone = $request->has('phone') ? $request->phone : $user->phone;
        if ($request->profile_image) {
            if ($user->profile_image != "") {
                $custom::delete_image($user->profile_image);
            }
            $profile_pic = $custom::upload_image($request, 'profile_image');
            $user->profile_image = 'uploads/' . $profile_pic;
        }
        $user->save();
        return back()->with('flash_success', 'Profile updated successfully.');
       }
        
        
            

        
       
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

    public function privacy_policy(Request $request)
    {
        $content = DB::table('content')->where('key', 'privacy-policy')->value('value');

        return view('user.privacy_policy')->with('content', $content)->with('title', trans('constants.privacy_policy'));
    }

    public function terms_conditions(Request $request)
    {
        $content = DB::table('content')->where('key', 'terms-conditions')->value('value');

        return view('user.privacy_policy')->with('content', $content)->with('title', trans('constants.terms_conditions'));
    }

    public function faq(Request $request)
    {
        $content = DB::table('content')->where('key', 'faq')->value('value');

        return view('user.privacy_policy')->with('content', $content)->with('title', trans('constants.faq'));
    }

    public function about_us(Request $request)
    {
        $content = DB::table('content')->where('key', 'about-us')->value('value');

        return view('user.privacy_policy')->with('content', $content)->with('title', trans('constants.about_us'));
    }
public function partner(Request $request)
    {
        
        return view('user.partner');
    }

public function courier(Request $request)
    {

        $vehicle = $this->vehicle->get();
        return view('user.courier')->with('vehicle', $vehicle);
    }
public function team(Request $request)
    {

        return view('user.team');
    }

public function work(Request $request)
    {

        return view('user.work');
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
        $restaurant = DB::table('restaurants')->first();
        $html = view('emails.restaurant.restaurant-welcome')->with(['restaurant' => $restaurant]);

        $Mailer = new Mailer();
        $Mailer->to         = $request->email;
        $Mailer->toName     = "Hello";
        $Mailer->subject     = "Welcome";
        $Mailer->body       = $html;

        $Mailer->send();

        return 1;
    }

    public function test_pk(Request $request)
    {
    }

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
            // $user_id = $request->header('authId');
            $address = $request->address;
            $lat = $request->lat;
            $lng = $request->lng;
            $type = $request->type;
            $flat_no = $request->flat_no;
            $landmark = $request->landmark;
            $delivery_address = $this->deliveryaddress;
            $restaurant_update = $delivery_address::findOrFail($request->address_id);
            //dd($restaurant_update);
            $restaurant_update->address = $address;
            $restaurant_update->lat = $lat;
            $restaurant_update->lng = $lng;
            $restaurant_update->type = $type;
            $restaurant_update->flat_no = $flat_no;
            $restaurant_update->landmark = $landmark;
            $restaurant_update->update();
            // $data = $delivery_address::where('user_id', $user_id)->orderBy('id', 'desc')->first();
            //$response_array = array('status' => true, 'data' => $data, 'message' => trans('constants.address_added_success'));
        }
        return redirect("orders#addresses")->with('message', trans('constants.address_added_success'));
    }
    public function restaurants_for_schedule_delivery(Request $request){
        $restaurant_list = array();
        // to hide loc popup
        if (isset($request->loc_flag)) {
        $request->session()->put('location_ses', 1);            
        }
        $request->session()->put('add', $request->add);
        $request->session()->put('lat', $request->lat);
        $request->session()->put('lng', $request->lng);
        $date= date("Y-m-d", strtotime($request->schedule_date));;
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
                'name' => $this->updateRestaurantName($d),
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

public function contact_admin(Request $request){
//dd($request);
$Mailer = new Mailer();
                $Mailer->to         = "mariyal@bytesflow.com";
                $Mailer->toName     = $request->email;
                $Mailer->subject    = "Contact";
                $Mailer->body       = $request->message;
                $Mailer->send();
//dd($Mailer);
return back()->with('success', "Message sent successfully");
    }

    public function verify_otp(Request $request)
    {
      
        $session_user_id = Auth::guard('user')->user()->id;
        $custom = $this->custom;
        $user = Users::find($session_user_id);
        $otp = rand(1000,9999);
        $html = view('emails.user.Verifyotp')->with(['otp' => $otp,'name' =>$user->name]);
        $Mailer = new Mailer();
        $Mailer->to         = $user->email;
        $Mailer->toName     = $user->name;
        $Mailer->subject    = "Verify Otp";
        $Mailer->body       =$otp;
        $Mailer->send();
         $use=  DB::table('users')->where('id', $user->id)->update([
            'otp' => $otp
        ]);
        $response_array = array('data' => $otp);
        $response = response()->json( $response_array);
		return $response;
    }
    public function addphonenumber_otp(Request $request)
    {
       
        $session_user_id = Auth::guard('user')->user()->id;
      // dd($session_user_id);
        $custom = $this->custom;
        $user = Users::find($session_user_id);
        $otp = rand(1000,9999);
        $html = view('emails.user.Verifyotp')->with(['otp' => $otp,'name' =>$user->name]);
        $Mailer = new Mailer();
        $Mailer->to         = $user->email;
        $Mailer->toName     = $user->name;
        $Mailer->subject    = "Verify Otp";
        $Mailer->body       =$otp;
        $Mailer->send();
         $use=  DB::table('users')->where('id', $user->id)->update([
            'otp' => $otp
        ]);
       // dd($use);
        $response_array = array('data' => $otp);
        $response = response()->json( $response_array);
		return $response;
    }
}
