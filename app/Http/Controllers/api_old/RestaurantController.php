<?php

namespace App\Http\Controllers\api;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;
use App\Model\Requestdetail;
use App\Model\RequestdetailAddons;
use Cartalyst\Stripe\Stripe;
use App\Http\Controllers\PaymentGateway\EasyCheckout;
use Carbon\Carbon;

use App\Http\Controllers\Restaurant;
use File;


class RestaurantController extends BaseController
{

    public function single_restaurant(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            array(
                'restaurant_id' => 'required',
                'veg_only' => 'required'
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $restaurant_id = $request->restaurant_id;
            $veg_only = $request->veg_only;
            $restaurants = $this->restaurants;
            $foodlist = $this->foodlist;
            $menu = $this->menu;
            $cart = $this->cart;
            $scheduleAt = $request->date . ' ' . $request->time;

            if ($request->header('authId') != "") {
                $user_id = $request->header('authId');
            } else {
                $user_id = $request->authId;
            }
            // $user_id = $request->header('authId');

            $rating_statistics = get_rating_statistics($restaurant_id);

            $restaurant_cuisines = DB::table('restaurant_cuisines')
                ->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
                ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
                ->select('restaurant_cuisines.restaurant_id as restaurant_id', 'cuisines.name as cuisine_name', 'restaurants.restaurant_name as restaurant_name')
                ->get();

            $data = $restaurants::where('id', $restaurant_id)->first();
            if ($data->address != "") {
                $decodedText = html_entity_decode($data->address);
                $data->address = $decodedText;
              }
            $food_filter = $foodlist::where('restaurant_id', $restaurant_id)->where('status', 1)->get();

            $restaurant_list = array();
            $rcuisines = array();
            $i = 0;

            foreach ($restaurant_cuisines as $r_cuisines) {
                if ($data->restaurant_name == $r_cuisines->restaurant_name && $i < 2) { // To display only two cuisines
                    $rcuisines[] = array(
                        'name' => $r_cuisines->cuisine_name
                    );

                    $i = $i + 1;
                }
            }

            $foods = array();
            $j = 0;

            foreach ($food_filter as $f) {
                $cart_count = $cart::where('user_id', $user_id)->where('food_id', $f->id)->first();  // For Cart item quantity

                if ($cart_count) {
                    $count = $cart_count->quantity;
                } else {
                    $count = 0;
                }

                if ($j < 4) {
                    if ($veg_only == 0) {
                        $foods[] = array(
                            'food_id'       => $f->id,
                            'name'          => $f->name,
                            'price'         => $f->price,
                            'description'   => $f->description,
                            'is_veg'        => $f->is_veg,
                            'item_count'    => $count,
                        );
                        $j = $j + 1;
                    } else {
                        if ($f->is_veg == 1) {
                            $foods[] = array(
                                'food_id'       => $f->id,
                                'name'          => $f->name,
                                'price'         => $f->price,
                                'description'   => $f->description,
                                'is_veg'        => $f->is_veg,
                                'item_count'    => $count,
                            );
                            $j = $j + 1;
                        }
                    }
                }
            }

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $data->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }
            $is_open = restaurant_is_open($data->id);
            $video = json_decode($data->video_url);
            $title = json_decode($data->video_title);

            if ($request->date && $request->time) {
                $isOpen_schedule = Restaurant::isOpenAt($data->id, $scheduleAt);

                $restaurant_list[] = array(
                    'id'            => $data->id,
                    'name' => $data->restaurant_name,
                    'delivery_type'          => json_decode($data->delivery_type),
                    'image' => File::exists(public_path('restaurant_uploads/').$data->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$data->image: (File::exists(public_path('restaurant_uploads/').$data->id.'/'.'Logo/'.$data->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$data->id.'/'.'Logo/'.$data->image : BASE_URL.UPLOADS_PATH_LOGO.$data->image),
                    'banner' => File::exists(public_path('restaurant_uploads/').$data->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$data->banner:(File::exists(public_path('restaurant_uploads/').$data->id.'/'.'Banner/'.$data->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$data->id.'/'.'Banner/'.$data->banner: BASE_URL.UPLOADS_PATH_BANNER.$data->banner),
                    
                    'address'       => $data->address,
                    'lat'     => $data->lat,
                    'lng'     => $data->lng,
                    'video_title'     => $title,
                    'video_url'     => $video,
                    'discount'      => $data->discount,
                    'rating'        => (float) $rating_statistics['rating_percent'],
                    //'is_open'       => $data->is_open,     // 1- Open , 0 - Close
                    'is_open'       => $isOpen_schedule,
                    'cuisines'      => $rcuisines,
                    'travel_time'   => $data->estimated_delivery_time,
                    'price'         => '',
                    'is_favourite'  => $is_favourite,
                    'food_list'     => $foods
                );
            } else {
                $restaurant_list[] = array(
                    'id'            => $data->id,
                    'name' => $data->restaurant_name,
                    'delivery_type'          => json_decode($data->delivery_type),
                    'image' => File::exists(public_path('restaurant_uploads/').$data->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$data->image: (File::exists(public_path('restaurant_uploads/').$data->id.'/'.'Logo/'.$data->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$data->id.'/'.'Logo/'.$data->image : BASE_URL.UPLOADS_PATH_LOGO.$data->image),
                    'banner' => File::exists(public_path('restaurant_uploads/').$data->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$data->banner:(File::exists(public_path('restaurant_uploads/').$data->id.'/'.'Banner/'.$data->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$data->id.'/'.'Banner/'.$data->banner: BASE_URL.UPLOADS_PATH_BANNER.$data->banner),
                  
                    'address'       => $data->address,
                    'lat'     => $data->lat,
                    'lng'     => $data->lng,
                    'video_title'     => $title,
                    'video_url'     => $video,
                    'discount'      => $data->discount,
                    'rating'        => (float) $rating_statistics['rating_percent'],
                    //'is_open'       => $data->is_open,     // 1- Open , 0 - Close
                    'is_open'       => $is_open,
                    'cuisines'      => $rcuisines,
                    'travel_time'   => $data->estimated_delivery_time,
                    'price'         => '',
                    'is_favourite'  => $is_favourite,
                    'food_list'     => $foods
                );
            }

            $food_cart = array();

            $check_for_cart = $cart::where('user_id', $user_id)->get();
            $amount = 0;
            $quantity = 0;
            foreach ($check_for_cart as $key) {
                $food_detail = $foodlist::where('id', $key->food_id)->first();
                $amount = $amount + ($food_detail->price * $key->quantity);
                $quantity = $quantity + $key->quantity;
            }

            $food_cart[] = array(
                'amount'     => $amount,
                'quantity'   => $quantity,
            );

            $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'cart' => $food_cart);
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function add_to_cart(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'food_id' => 'required',
                'quantity' => 'required',
                'restaurant_id' => 'required',
                'force_insert' => 'required'  // To Overwrite previous restaurant cart if exist - 1
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $cart = $this->cart;
            $foodlist = $this->foodlist;
            $restaurants = $this->restaurants;
            if ($request->header('authId') != "") {
                $user_id = $request->header('authId');
            } else {
                $user_id = $request->authId;
            }
            // $user_id = $request->header('authId');
            $food_id = $request->food_id;
            $quantity = $request->quantity;
            $force_insert = $request->force_insert;
            $restaurant_id = $request->restaurant_id;

            $check = $cart::where('user_id', $user_id)->where('food_id', $food_id)->first();

            if ($force_insert == 0) {
                if ($check) {
                    $cart::where('id', $check->id)->update(['quantity' => $quantity]);
                } else {
                    $last_data = $cart::where('user_id', $user_id)->first();
                    if ($last_data) {
                        $check_restaurant = $foodlist::where('id', $last_data->food_id)->first();

                        if ($check_restaurant->restaurant_id == $restaurant_id) {
                            $is_same_restaurant = 1;
                        } else {
                            $existing_restaurant = $restaurants::where('id', $check_restaurant->restaurant_id)->first();
                            $new_restaurant = $restaurants::where('id', $restaurant_id)->first();

                            $message = 'Your cart contains dishes from ' . $existing_restaurant->restaurant_name . ' . Do you want to discard the selection and add dishes from ' . $new_restaurant->restaurant_name . ' ?';


                            $response_array = array('status' => false, 'error_code' => 102, 'message' => $message);
                            $response = response()->json($response_array, 200);

                            return $response;
                        }
                    }

                    $insert_data = array();
                    $insert_data[] = array(
                        'user_id' => $user_id,
                        'food_id' => $food_id,
                        'quantity' => $quantity
                    );

                    $cart::insert($insert_data);
                }
            } else {
                $cart::where('user_id', $user_id)->delete();

                $insert_data = array();
                $insert_data[] = array(
                    'user_id' => $user_id,
                    'food_id' => $food_id,
                    'quantity' => $quantity
                );

                $cart::insert($insert_data);
            }

            $response_array = array('status' => true, 'message' => trans('constants.item_quantity_added_to_cart'));
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function reduce_from_cart(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'food_id' => 'required',
                'quantity' => 'required'
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $cart = $this->cart;
            if ($request->header('authId') != "") {
                $user_id = $request->header('authId');
            } else {
                $user_id = $request->authId;
            }
            // $user_id = $request->header('authId');
            $food_id = $request->food_id;
            $quantity = $request->quantity;

            $check = $cart::where('user_id', $user_id)->where('food_id', $food_id)->first();
            if ($check && $quantity != 0) {
                $cart::where('id', $check->id)->update(['quantity' => $quantity]);
            } elseif (count($check) != 0 && $quantity == 0) {

                $cart::where('id', $check->id)->delete();
            }
            $response_array = array('status' => true, 'message' => trans('constants.item_quantity_removed_from_cart'));
        }
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function check_cart(Request $request)
    {

        $cart = $this->cart;
        $foodlist = $this->foodlist;
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }
        // $user_id = $request->header('authId');
        $user_table = $this->users::where('id', $user_id)->first();
        $restaurants = $this->restaurants;

        $food_cart = array();

        // if($user_table->device_type!=WEB)
        // {

        //         $check_for_cart = $cart::where('user_id',$user_id)->get();
        //         $amount = 0;
        //         $quantity = 0;
        //         foreach ($check_for_cart as $key) {

        //           $food_detail = $foodlist::where('id',$key->food_id)->first();

        //             $quantity = $quantity + $key->quantity;
        //             $amount = $amount+($food_detail->price* $key->quantity);
        //         }

        //          $food_cart[] = array(
        //                 'amount'     => $amount,
        //                 'quantity'   => $quantity,
        //             );

        //          $response_array = array('status'=>true,'cart'=>$food_cart);
        // }else
        // {

        $check_for_cart = $cart::where('user_id', $user_id)->get();
        $amount = 0;
        $quantity = 0;
        $item_list = array();
        foreach ($check_for_cart as $key) {

            $food_detail = $foodlist::where('id', $key->food_id)->first();

            $quantity = $quantity + $key->quantity;
            $amount = $amount + ($food_detail->price * $key->quantity);
            $item_list[] = array(
                'item_id' => $key->food_id,
                'item_name' => $food_detail->name,
                'quantity' => $key->quantity,
                'price' => $key->quantity * $food_detail->price
            );

            $restaurant_id = $food_detail->restaurant_id;
            $order_on = $key->created_at;
        }

        if (isset($restaurant_id)) {
            $restaurant_detail = $restaurants::where('id', $restaurant_id)->first();
            $restaurant_id = $restaurant_detail->id;
            $restaurant_name = $restaurant_detail->restaurant_name;
            $restaurant_image = $restaurant_detail->image;
            $order_on = $order_on;
            $restaurant_address = $restaurant_detail->address;

            $estimated_delivery_time = $restaurant_detail->estimated_delivery_time;
            $offer_amount = $restaurant_detail->offer_amount;
            $discount_type = $restaurant_detail->discount_type;
            $discount = $restaurant_detail->discount;
            $restaurant_delivery_charge = $restaurant_detail->restaurant_delivery_charge;
            $packaging_charge = $restaurant_detail->packaging_charge;
            $driver_commision = $restaurant_detail->driver_commision;
            $admin_commision = $restaurant_detail->admin_commision;
        } else {
            $restaurant_id = null;
            $restaurant_name = "";
            $restaurant_image = "";
            $order_on = "";
            $restaurant_address = "";

            $estimated_delivery_time = 0;
            $offer_amount = 0;
            $discount_type = 0;
            $discount = 0;
            $restaurant_delivery_charge = 0;
            $packaging_charge = 0;
            $driver_commision = 0;
            $admin_commision = 0;
        }

        $food_cart[] = array(
            'item_list'  => $item_list,
            'amount'     => $amount,
            'order_on' => $order_on,
            'quantity'   => $quantity,
            'restaurant_id' => $restaurant_id,
            'restaurant_name' => $restaurant_name,
            'restaurant_image' => $restaurant_image,
            'restaurant_address' => $restaurant_address,

            'estimated_delivery_time' => $estimated_delivery_time,
            'offer_amount' => $offer_amount,
            'discount_type' => $discount_type,
            'discount' => $discount,
            'restaurant_delivery_charge' => $restaurant_delivery_charge,
            'packaging_charge' => $packaging_charge,
            'driver_commision' => $driver_commision,
            'admin_commision' => $admin_commision,
        );

        $response_array = array('status' => true, 'cart' => $food_cart);

        // }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_menu($restaurant_id, Request $request)
    {
        $foodlist = $this->foodlist;
        $category = $this->category;
        $menu = $this->menu;

        $menu_list = $menu->where('restaurant_id', $restaurant_id)
            ->pluck('id');

        $data = $menu->whereIn('id', $menu_list)->orderBy('rank')->get();

        if ($data && count($data) != 0) {

            $food_menu = array();
            $i = 1;
            foreach ($data as $d) {
                $food_menu[] = [
                    'menu_id'   => $d->id,
                    'name'          => $d->menu_name,
                    'rank'          => $d->rank
                ];

                $i = $i + 1;
            }

            $response_array = array('status' => true, 'menu' => $food_menu);
        } else {
            $response_array = array('status' => false, 'message' => trans('constants.no_data_found'));
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_category($restaurant_id, Request $request)
    {
        $foodlist = $this->foodlist;
        $category = $this->category;
        $menu = $this->menu;

        $category_list = $foodlist->where('restaurant_id', $restaurant_id)
            ->groupBy('category_id')
            ->pluck('category_id');

        $data = $category->whereIn('id', $category_list)->where('image', '<>', '')->orderBy('rank', 'asc')->get();

        if ($data && count($data) != 0) {

            $food_category = array();
            $i = 1;
            foreach ($data as $d) {
                $food_category[] = [
                    'category_id'   => $d->id,
                    'name'          => $d->category_name,
                    'image'          => $d->image,
                    'position'      => $i
                ];

                $i = $i + 1;
            }

            $response_array = array('status' => true, 'category' => $food_category);
        } else {
            $response_array = array('status' => false, 'message' => trans('constants.no_data_found'));
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_category_list(Request $request)
    {
        $foodlist = $this->foodlist;
        $category = $this->category;
        $menu = $this->menu;
        $scheduleAt = $request->date . ' ' . $request->time;
        $data = DB::table('category')->join('food_list', 'food_list.category_id', '=', 'category.id')->select('category.*', 'food_list.restaurant_id')->groupBy('food_list.category_id')->get();

        if ($data && count($data) != 0) {

            $food_category = array();
            $i = 1;
            foreach ($data as $d) {
                $isOpen_schedule = Restaurant::isOpenAt($d->restaurant_id, $scheduleAt);
                if ($request->date && $request->time) {
                    if ($isOpen_schedule == 1) {
                        $food_category[] = [
                            'category_id'   => $d->id,
                            'name'          => $d->category_name,
                            'image'          => $d->image,
                            'position'      => $i
                        ];
                    }
                } else {
                    $food_category[] = [
                        'category_id'   => $d->id,
                        'name'          => $d->category_name,
                        'image'          => $d->image,
                        'position'      => $i
                    ];
                }
                $i = $i + 1;
            }

            $response_array = array('status' => true, 'category' => $food_category);
        } else {
            $response_array = array('status' => false, 'message' => trans('constants.no_data_found'));
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_food_list_by_menu(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'restaurant_id' => 'required',
                'is_veg' => 'required'
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
            return response()->json($response_array, 200);
        }

        $restaurant_id = $request->restaurant_id;
        $foodlist = $this->foodlist;
        $category = $this->category;
        $cart = $this->cart;
        $restaurants = $this->restaurants;

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        if (empty($user_id)) {
            // return response()->json(['status' => false, 'error_code' => 101, 'message' => 'User not found'], 200);
        }

        // $user_id = $request->header('authId');
        $is_veg = $request->is_veg;

        $check_for_cart = $cart::where('user_id', $user_id)->get();

        $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
            ->where('food_list.status', 1)
            ->join(
                'menu',
                function ($join) {
                    $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                    $join->on('menu.id', '=', 'food_list.menu_id');
                }
            )
            ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image')
            //->where('food_list.name', 'BBQ Chicken Wings')
            ->orderBy('rank')->orderBy('image', 'desc')
            ->get();
            foreach($food_list as $key => $menu)
            {
                if($menu->image == "")
                {
                    $food_list[$key]->image ="";
                }
                else{
                    $food_list[$key]->image = File::exists(public_path('product_image/').$menu->image)? UPLOADS_PATH_PRODUCT.$menu->image:( File::exists(public_path('restaurant_uploads/').$restaurant_id.'/'.'Product/'.$menu->image)? RESTAURANT_UPLOADS_PATH.$restaurant_id.'/'.'Product/'.$menu->image :(File::exists(public_path('common_images/').$menu->image)? UPLOADS_PATH_COMMON.$menu->image:$menu->image));
                }
           
            }

        foreach ($food_list as $key => $each_food) {
            $this_addon = DB::table('foodlist_addons')
                ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select('foodlist_addons.addons_id as id', 'add_ons.name',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at')
                ->get()->toArray();

            if (!$this_addon) {
                $food_list[$key]->add_ons = [];
                continue;
            }

            foreach ($this_addon as $addon_key => $each_addon) {
                $this_addon[$addon_key]->restaurant_id = $restaurant_id;
                $this_addon[$addon_key]->pivot = ['foodlist_id' => $each_food->food_id, 'addons_id' => $each_addon->id];
            }

            $food_list[$key]->add_ons = $this_addon;
        }

        foreach ($food_list as $key => $each_food) {

            $item_count = 0;
            if (count($check_for_cart) != 0) {
                foreach ($check_for_cart as $check_for_item) {
                    if ($each_food->food_id == $check_for_item->food_id) {
                        $item_count = $check_for_item->quantity;
                    }
                }
            }
            $food_list[$key]->item_count = $item_count;


            $this_size = DB::table('foodlist_foodquantity')
                ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select(
                    'foodlist_foodquantity.foodquantity_id as id',
                    'food_quantity.name',
                    'food_quantity.secondary_name',
                    'foodlist_foodquantity.price',
                    'foodlist_foodquantity.is_default',
                    'foodlist_foodquantity.created_at',
                    'foodlist_foodquantity.updated_at'
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }

            foreach ($this_size as $size_key => $each_size) {
                $this_size[$size_key]->pivot = [
                    'foodlist_id' => $each_food->food_id,
                    'foodquantity_id' => $each_size->id,
                    'price' => $each_size->price,
                    'is_default' => $each_size->is_default
                ];
            }

            $food_list[$key]->food_quantity = $this_size;
        }

        $new_food_list = [];

        foreach ($food_list as $key => $each_food) {

            if ($is_veg == 1 && $each_food->is_veg == 0) {
                continue;
            }

            $new_food_list[$each_food->menu_id][] = $each_food;
        }

        $return_food_list = [];
        foreach ($new_food_list as $key => $each_menu) {
            // checking menu avaible now
            $available = 0;
            $menutime = DB::table('menu_timing')->where('menu_id', $each_menu[0]->menu_id)->get();
            if (sizeof($menutime)) {
               
                foreach ($menutime as $key => $time) {
                    if($available != 1 ){

                    $available_day =json_decode($time->available_day);
                    if($available_day != null){
                    $times = Carbon::now();
                    $today = Carbon::now()->dayOfWeek;
                    $yes =in_array($today, $available_day);
                    if($yes==false){

                        $available = 0;
                    }else{
                        $current_time = $times->toTimeString();
                        $date1 = $current_time;
                        $date2 = $time->from_time;
                        $date3 = $time->to_time;
                        if ($date1 > $date2 && $date1 < $date3) {
                            $available = 1;
                        }
                        else{
                            $available = 0; //latest feature so some menu dont assume time so set is available
                        }
                    }
                }elseif($time->all_days == 1){
                    $times = Carbon::now();
                    $current_time = $times->toTimeString();
                    $date1 = $current_time;
                    $date2 = $time->from_time;
                    $date3 = $time->to_time;
                    if ($date1 > $date2 && $date1 < $date3) {
                        $available = 1;
                    }
                    else{
                        $available = 0; //latest feature so some menu dont assume time so set is available
                    }
                }else{
                    $available=1;
                }
                }
            }
            }else{
                $available = 1; //latest feature so some menu dont assume time so set is available
            }
            if($available == 1){

            $return_food_list[] = [
                'menu_id' => $each_menu[0]->menu_id,
                'menu_available' => $available,
                'menu_name' => $each_menu[0]->menu_name,
                'items' => $each_menu,
            ];
        }

        }
        //print_r($return_food_list);
        $restaurant_detail = $restaurants::where('id', $restaurant_id)->select('id', 'restaurant_name', 'image', 'address')->first();
        $restaurant_detail->image = File::exists(public_path('restaurant_uploads/').$restaurant_detail->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_detail->image: (File::exists(public_path('restaurant_uploads/').$restaurant_detail->id.'/'.'Logo/'.$restaurant_detail->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_detail->id.'/'.'Logo/'.$restaurant_detail->image : BASE_URL.UPLOADS_PATH_LOGO.$restaurant_detail->image);

        if (!$restaurant_detail) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Restaurant not found'];
            return response()->json($response_array, 200);
        }

        $return_food_list = $this->updateSecondaryLang($return_food_list, $request);

        return response()->json([
            'status' => true,
            'recommended' => [],
            'items' => $return_food_list,
            'restaurant_detail' => $restaurant_detail
        ], 200);
    }

    public function get_food_list_by_search(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'restaurant_id' => 'required',
                'is_veg' => 'required',
                'key_word' => ''
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
            return response()->json($response_array, 200);
        }

        $restaurant_id = $request->restaurant_id;
        $foodlist = $this->foodlist;
        $category = $this->category;
        $cart = $this->cart;
        $key_word = $request->key_word;
        $restaurants = $this->restaurants;

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        if (empty($user_id)) {
            // return response()->json(['status' => false, 'error_code' => 101, 'message' => 'User not found'], 200);
        }

        // $user_id = $request->header('authId');
        $is_veg = $request->is_veg;

        $check_for_cart = $cart::where('user_id', $user_id)->get();

        $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
            ->where('food_list.status', 1)
            ->where('food_list.name', 'like', '%' . $key_word . '%')
            ->join(
                'menu',
                function ($join) {
                    $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                    $join->on('menu.id', '=', 'food_list.menu_id');
                }
            )
            ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image')
            //->where('food_list.name', 'BBQ Chicken Wings')
            ->orderBy('rank')->orderBy('image', 'desc')
            ->get();

            foreach($food_list as $key => $menu)
            {
            if($menu->image == "")
            {
                $food_list[$key]->image ="";
            }
            else{
              //  $food_list[$key]->image = File::exists(public_path('product_image/').$menu->image)? UPLOADS_PATH_PRODUCT.$menu->image: (File::exists(public_path('restaurant_uploads/').$menu->restaurant_id.'/'.'Product/'.$menu->image)? RESTAURANT_UPLOADS_PATH.$menu->restaurant_id.'/'.'Product/'.$menu->image :$menu->image);
              $food_list[$key]->image = File::exists(public_path('product_image/').$menu->image)? UPLOADS_PATH_PRODUCT.$menu->image:( File::exists(public_path('restaurant_uploads/').$menu->restaurant_id.'/'.'Product/'.$menu->image)? RESTAURANT_UPLOADS_PATH.$menu->restaurant_id.'/'.'Product/'.$menu->image :(File::exists(public_path('common_images/').$menu->image)? UPLOADS_PATH_COMMON.$menu->image:$menu->image));
            }
       
        }

        foreach ($food_list as $key => $each_food) {
            $this_addon = DB::table('foodlist_addons')
                ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select('foodlist_addons.addons_id as id', 'add_ons.name',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at')
                ->get()->toArray();

            if (!$this_addon) {
                $food_list[$key]->add_ons = [];
                continue;
            }

            foreach ($this_addon as $addon_key => $each_addon) {
                $this_addon[$addon_key]->restaurant_id = $restaurant_id;
                $this_addon[$addon_key]->pivot = ['foodlist_id' => $each_food->food_id, 'addons_id' => $each_addon->id];
            }

            $food_list[$key]->add_ons = $this_addon;
        }

        foreach ($food_list as $key => $each_food) {

            $item_count = 0;
            if (count($check_for_cart) != 0) {
                foreach ($check_for_cart as $check_for_item) {
                    if ($each_food->food_id == $check_for_item->food_id) {
                        $item_count = $check_for_item->quantity;
                    }
                }
            }
            $food_list[$key]->item_count = $item_count;


            $this_size = DB::table('foodlist_foodquantity')
                ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select(
                    'foodlist_foodquantity.foodquantity_id as id',
                    'food_quantity.name',
                    'food_quantity.secondary_name',
                    'foodlist_foodquantity.price',
                    'foodlist_foodquantity.is_default',
                    'foodlist_foodquantity.created_at',
                    'foodlist_foodquantity.updated_at'
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }

            foreach ($this_size as $size_key => $each_size) {
                $this_size[$size_key]->pivot = [
                    'foodlist_id' => $each_food->food_id,
                    'foodquantity_id' => $each_size->id,
                    'price' => $each_size->price,
                    'is_default' => $each_size->is_default
                ];
            }

            $food_list[$key]->food_quantity = $this_size;
        }

        $new_food_list = [];

        foreach ($food_list as $key => $each_food) {

            if ($is_veg == 1 && $each_food->is_veg == 0) {
                continue;
            }

            $new_food_list[$each_food->menu_id][] = $each_food;
        }

        $return_food_list = [];
        foreach ($new_food_list as $key => $each_menu) {
            $return_food_list[] = [
                'menu_id' => $each_menu[0]->menu_id,
                'menu_name' => $each_menu[0]->menu_name,
                'items' => $each_menu,
            ];
        }

        $restaurant_detail = $restaurants::where('id', $restaurant_id)->select('id', 'restaurant_name', 'image', 'address')->first();
        if (!$restaurant_detail) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Restaurant not found'];
            return response()->json($response_array, 200);
        }

        $return_food_list = $this->updateSecondaryLang($return_food_list, $request);

        return response()->json([
            'status' => true,
            'recommended' => [],
            'items' => $return_food_list,
            'restaurant_detail' => $restaurant_detail
        ], 200);
    }

    public function get_food_list(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'restaurant_id' => 'required',
                'is_veg' => 'required'
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
            return response()->json($response_array, 200);
        }

        $restaurant_id = $request->restaurant_id;
        $foodlist = $this->foodlist;
        $category = $this->category;
        $cart = $this->cart;
        $restaurants = $this->restaurants;

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        // $user_id = $request->header('authId');
        $is_veg = $request->is_veg;

        $food_list = $foodlist::with('FoodQuantity')->where('food_list.status', 1)->where('restaurant_id', $restaurant_id)->get();
        $category_list = $category::get();

        $check_for_cart = $cart::where('user_id', $user_id)->get();

        $get_food_list = [];
        $get_food_list_recommended = [];

        foreach ($category_list as $key) {
            $category_wise_food = [];
            $category_wise_food_recommended = [];

            foreach ($food_list as $foods) {

                if ($foods->category_id != $key->id) {
                    continue;
                }

                $item_count = 0;
                if (count($check_for_cart) != 0) {
                    foreach ($check_for_cart as $check_for_item) {
                        if ($foods->id == $check_for_item->food_id) {
                            $item_count = $check_for_item->quantity;
                        }
                    }
                }

                if ($is_veg == 1 && $foods->is_veg == 0) {
                    continue;
                }

                $this_item = [
                    'food_id' => $foods->id,
                    'name' => $foods->name,
                    'is_veg' => $foods->is_veg,
                    'image' => $foods->image,
                    'price' => $foods->price,
                    'description' => $foods->description,
                    'category_id' => $foods->category_id,
                    'item_count' => $item_count,
                    'item_tax' => $foods->tax,
                    'add_ons' => $foods->Add_ons,
                    'food_quantity' => $foods->FoodQuantity
                ];

                // if ($foods->image) {
                //     $category_wise_food_recommended[] = $this_item;
                // } else {
                //     $category_wise_food[] = $this_item;
                // }
                $category_wise_food[] = $this_item;
            }

            if ($category_wise_food) {
                $get_food_list[] = [
                    'category_id' => $key->id,
                    'category_name' => $key->category_name,
                    'items' => $category_wise_food
                ];
            }

            if ($category_wise_food_recommended) {
                $get_food_list_recommended[] = [
                    'category_id' => $key->id,
                    'category_name' => $key->category_name,
                    'items' => $category_wise_food_recommended
                ];
            }
        }

        $restaurant_detail = $restaurants::where('id', $restaurant_id)->select('restaurant_name', 'image', 'address')->first();
        if (!$restaurant_detail) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Restaurant not found'];
            return response()->json($response_array, 200);
        }

        $restaurant_detail->image = File::exists(public_path('restaurant_uploads/').$restaurant_detail->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_detail->image: (File::exists(public_path('restaurant_uploads/').$restaurant_detail->id.'/'.'Logo/'.$restaurant_detail->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_detail->id.'/'.'Logo/'.$restaurant_detail->image : BASE_URL.UPLOADS_PATH_LOGO.$restaurant_detail->image);


        if (!empty($get_food_list_recommended) || !empty($get_food_list)) {
            $response_array = array('status' => true, 'recommended' => $get_food_list_recommended, 'food_list' => $get_food_list, 'restaurant_detail' => $restaurant_detail);
        } else {
            $response_array = array('status' => false, 'message' => trans('constants.no_data_found'));
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_category_wise_food_list(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'restaurant_id' => 'required',
                'category_id' => 'required',
                'veg_only'  => 'required'
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $restaurant_id = $request->restaurant_id;
            $category_id = $request->category_id;
            $foodlist = $this->foodlist;
            $cart = $this->cart;
            if ($request->header('authId') != "") {
                $user_id = $request->header('authId');
            } else {
                $user_id = $request->authId;
            }
            // $user_id = $request->header('authId');
            $veg_only = $request->veg_only;

            $food_list = $foodlist::where('restaurant_id', $restaurant_id)->where('status', 1)->where('category_id', $category_id)->get();

            $check_food_for_null = 0;
            if (count($food_list) != 0) {
                foreach ($food_list as $f) {
                    $cart_count = $cart::where('user_id', $user_id)->where('food_id', $f->id)->first();  // For Cart item quantity

                    if (count($cart_count) != 0) {
                        $count = $cart_count->quantity;
                    } else {
                        $count = 0;
                    }
                    if ($veg_only == 0) {
                        $check_food_for_null += 1;
                        $foods[] = array(
                            'food_id'       => $f->id,
                            'name'          => $f->name,
                            'price'         => $f->price,
                            'description'   => $f->description,
                            'is_veg'        => $f->is_veg,
                            'item_count'    => $count,
                        );
                    } else {
                        if ($f->is_veg == 1) {
                            $check_food_for_null += 1;

                            $foods[] = array(
                                'food_id'       => $f->id,
                                'name'          => $f->name,
                                'price'         => $f->price,
                                'description'   => $f->description,
                                'is_veg'        => $f->is_veg,
                                'item_count'    => $count,
                            );
                        }
                    }
                }

                $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $restaurant_id)->get();

                if (count($check_favourite) != 0) {
                    $is_favourite = 1;
                } else {
                    $is_favourite = 0;
                }

                if ($check_food_for_null != 0) {
                    $response_array = array('status' => true, 'food_list' => $foods, 'is_favourite' => $is_favourite);
                } else {
                    $response_array = array('status' => false, 'message' => trans('constants.no_data_found'));
                }
            } else {
                $response_array = array('status' => false, 'message' => trans('constants.no_data_found'));
            }
        }
        $response = response()->json($response_array, 200);
        return $response;
    }

    // public function checkout(Request $request)
    // {
    //      if($request->header('authId')!="")
    //         {
    //             $user_id = $request->header('authId');
    //         }else
    //         {
    //             $user_id = $request->authId;
    //         }
    //     // $user_id = $request->header('authId');

    //     $cart = $this->cart;
    //     $foodlist = $this->foodlist;

    //     $restaurants = $this->restaurants;

    //     $check_cart = $cart::where('user_id',$user_id)->first();

    //     if(count($check_cart)!=0)
    //     {

    //             $check_food = $foodlist::where('id',$check_cart->food_id)->first();
    //             $restaurant = $restaurants::where('id',$check_food->restaurant_id)->first();

    //             $restaurant_detail = array();

    //             $restaurant_detail[] = array(
    //                 'restaurant_id'=>$restaurant->id,
    //                 'name'=>$restaurant->restaurant_name,
    //                 'image'=>$restaurant->image,
    //                 'address'=>$restaurant->address,
    //                 'estimated_delivery_time'=>$restaurant->estimated_delivery_time
    //             );



    //             $check_cart = $cart::where('user_id',$user_id)
    //                                 ->join('food_list','food_list.id','=','cart.food_id')
    //                                 ->select('food_list.name as name','food_list.price as price','food_list.is_veg as is_veg','cart.quantity as quantity','food_list.tax as tax','cart.food_id as food_id')
    //                                 ->get();

    //             $food_detail = array();
    //             $total_price = 0;
    //             $total_tax = 0;
    //             foreach($check_cart as $key)
    //             {
    //                 $total_tax = $total_tax + $key->tax;
    //                 $total_price = $total_price + ($key->price * $key->quantity);
    //                 $food_detail[] = array(
    //                     'food_id' => $key->food_id,
    //                     'name' => $key->name,
    //                     'price' => $key->price * $key->quantity,
    //                     'is_veg' => $key->is_veg,
    //                     'quantity' => $key->quantity,
    //                 );

    //             }


    //             $item_total = $total_price;

    //             // FOR COUPON CODE 
    //             if($request->coupon_code!="")
    //             {
    //                 $get_offer = DB::table('coupon_code')->where('code',$request->coupon_code)->first();
    //                 if(count($get_offer)!=0)
    //                 {   
    //                     $coupon_code = $request->coupon_code;
    //                     $offer_type = $get_offer->offer_type;
    //                     if($offer_type==0) // For % offer
    //                     {
    //                         $offer = $get_offer->value;

    //                         $coupon_discount = ($total_price/100)*$offer;
    //                     }else
    //                     {
    //                         // For price offer
    //                         $coupon_discount = $get_offer->value;

    //                     }
    //                 }else
    //                 {
    //                     $response_array = array('status'=>false,'message'=>'Invalid Coupon Code');
    //                     $response = response()->json($response_array, 200);
    //                     return $response;
    //                 }
    //             }else{
    //             $coupon_code = "NA";
    //             $coupon_discount = 0;
    //             }
    //             ////
    //             $offer_discount = $coupon_discount;
    //             $restaurant_packaging_charge = $restaurant->packaging_charge;
    //             $gst = $total_tax;
    //             $delivery_charge = 0;

    //             $bill_amount = $item_total + $restaurant_packaging_charge + $gst + $delivery_charge - $offer_discount;


    //             $invoice = array();

    //             $invoice[] = array(
    //                 'item_total' =>$total_price,
    //                 'offer_discount' => $offer_discount,
    //                 'restaurant_packaging_charge' => $restaurant_packaging_charge,
    //                 'gst' => $gst,
    //                 'delivery_charge' => $delivery_charge,
    //                 'bill_amount' => $bill_amount,
    //                 'coupon_code' => $coupon_code
    //             );

    //              $response_array = array('status'=>true,'restaurant_detail'=>$restaurant_detail,'food_detail'=>$food_detail,'invoice'=>$invoice);

    //     }else
    //     {
    //             $response_array = array('status'=>false,'message'=>'No Items in your cart');
    //     }

    //     $response = response()->json($response_array, 200);
    //         return $response;

    // }

    public function checkout(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            array(
                'restaurant_id' => 'required'
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {


            $restaurants = $this->restaurants;
            $restaurant = $restaurants::where('id', $request->restaurant_id)->first();
            if ($restaurant->address != "") {
                $decodedText = html_entity_decode($restaurant->address);
                $restaurant->address = $decodedText;
              }
            $restaurant->is_open = restaurant_is_open($request->restaurant_id);

            $restaurant_detail = array();

            $restaurant_detail[] = array(
                'restaurant_id' => $restaurant->id,
                'name' => $restaurant->restaurant_name,
                'image' => File::exists(public_path('restaurant_uploads/').$restaurant->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant->image: (File::exists(public_path('restaurant_uploads/').$restaurant->id.'/'.'Logo/'.$restaurant->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant->id.'/'.'Logo/'.$restaurant->image : BASE_URL.UPLOADS_PATH_LOGO.$restaurant->image),

                'address' => $restaurant->address,
                'is_open' => $restaurant->is_open,
                'estimated_delivery_time' => $restaurant->estimated_delivery_time,
                'is_tax_inclusive' => (int) env('IS_TAX_INCLUSIVE')
            );

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

            $restaurant_packaging_charge = $restaurant->packaging_charge;
            $delivery_charge = $restaurant->restaurant_delivery_charge;
            $delivery_charge_type = $restaurant->delivery_charge_type;

            $delivery_charge_limits = DB::table('delivery_charge_limits')->select('Minimum', 'Maximum', 'Value', 'Type')->where('restaurant_id', $request->restaurant_id)->get();

            $invoice = array();

            $invoice[] = array(
                'offer_discount' => $coupon_discount,
                'restaurant_packaging_charge' => $restaurant_packaging_charge,
                'DeliveryChargeBasedOn' => $restaurant->DeliveryChargeBasedOn,
                'delivery_charge' => $delivery_charge,
                'delivery_charge_type' => $delivery_charge_type,
                'delivery_charge_limits' => $delivery_charge_limits,
                'coupon_code' => $coupon_code
            );
            if (isset($request->user_id)) {
                $user = DB::table('users')->select('wallet_amount', 'name')->where('id', $request->user_id)->get();
                $response_array = array('status' => true, 'restaurant_detail' => $restaurant_detail, 'invoice' => $invoice, 'user' => $user);
            } else {
                $response_array = array('status' => true, 'restaurant_detail' => $restaurant_detail, 'invoice' => $invoice);
            }
        }

        return response()->json($response_array, 200);
    }

    public function paynow(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'restaurant_id' => 'required',
                'item_total' => 'required',
                'offer_discount'  => 'required',
                'restaurant_packaging_charge'  => 'required',
                'gst'  => 'required',
                'delivery_charge'  => 'required',
                'bill_amount'  => 'required',
                'coupon_code'  => 'required',
                'food_id'  => 'required',
                'food_qty'  => 'required',
                'paid_type' => 'required',
                'food_quantity_price' => 'required',
                //'delivery_type' => 'required',
                // 'delivery_address' => 'required'
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            return response()->json(['status' => false, 'error_code' => 101, 'message' => $error_messages], 200);;
        }

        $is_cod = $request->is_cod ?? 1;
        $delivery_type = $request->delivery_type ?? 1;

        $is_cod_enabled = DB::table('settings')->select('value')->where('key_word', 'cod_enable')->first();
        if (($is_cod) && (!$is_cod_enabled || !$is_cod_enabled->value)) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => 'CoD is disabled'], 200);;
        }

        $foodrequest = $this->foodrequest;
        $cart = $this->cart;
        $trackorderstatus = $this->trackorderstatus;
        $settings = $this->settings;

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        // $user_id = $request->header('authId');
        $restaurant_id = $request->restaurant_id;
        $item_total = $request->item_total;
        $offer_discount = $request->offer_discount;
        $restaurant_packaging_charge = $request->restaurant_packaging_charge;
        $gst = $request->gst;
        $delivery_charge = $request->delivery_charge;
        $bill_amount = $request->bill_amount;
        $coupon_code = $request->coupon_code;

        $user_detail = $this->users::where('id', $user_id)->first();

        if (!$user_detail) {
            return response()->json(['status' => false, 'message' => 'User not found'], 200);;
        }

        $restaurant_details = $this->restaurants->find($request->restaurant_id);
        if (!$restaurant_details) {
            return response()->json(['status' => false, 'message' => 'Restaurant not found'], 200);;
        }

        $delivery_types = json_decode($restaurant_details->delivery_type);
        if (!$delivery_types || !in_array($delivery_type, $delivery_types)) {
            return response()->json(['status' => false, 'message' => 'This delivery type is not allowed'], 200);;
        }



        // if (isset($user_detail) && $user_detail->device_type==WEB) {
        $food_id = [];
        $food_ids = str_replace('"', '', (string) $request->food_id);
        $food_id = explode(',', $food_ids);

        $food_qty = $food_quantity = $food_quantity_price = [];
        $food_qtys = str_replace('"', '', (string) $request->food_qty);
        $food_qty = explode(',', $food_qtys);

        $food_quantitys = str_replace('"', '', (string) $request->food_quantity);
        $food_quantity = explode(',', $food_quantitys);
        $food_quantity_prices = str_replace('"', '', (string) $request->food_quantity_price);
        $food_quantity_price = explode(',', $food_quantity_prices);

        // } else {
        //     $food_id = $request->food_id;
        //     $food_qty = $request->food_qty;
        //     $food_quantity = $request->food_quantity;
        //     $food_quantity_price = $request->food_quantity_price;
        // }

        $food_id_size = is_array($food_id) ? count($food_id) : 1;
        $food_qty_size = is_array($food_qty) ? count($food_qty) : 1;
        $paid_type = $request->paid_type;


        if ($user_detail->device_type == WEB) {

            $delivery_address_detail = $this->deliveryaddress::where('user_id', $user_id)->where('is_default', 1)->first();

            if (!$delivery_address_detail) {
                return response()->json(['status' => false, 'message' => 'No delivery address found.'], 200);
            }

            $d_lat = $delivery_address_detail->lat;
            $d_lng = $delivery_address_detail->lng;
            $delivery_address = $delivery_address_detail->address;
        } else {
            $data = file_get_contents(FIREBASE_URL . "/current_address/$user_id.json");
            $data = json_decode($data);

            if ($delivery_type == 1) {
                $d_lat = isset($data->lat) ? $data->lat : "";
                $d_lng = isset($data->lng) ? $data->lng : "";
                $delivery_address = $data->current_address ?? '';
            } else {
                $d_lat = "";
                $d_lng = "";
                $delivery_address = '';
            }
        }

        if ($delivery_type == 1 && !$delivery_address) {
            return response()->json(['status' => false, 'message' => 'No delivery address found'], 200);
        }

        $order_id = $this->generate_booking_id();

        //get restaurant based commission
        $restaurant_details = $this->restaurants->find($restaurant_id);
        if (!empty($restaurant_details->admin_commision))
            $admin_commision_setting = $restaurant_details->admin_commision;
        else
            $admin_commision_setting = $settings::where('key_word', 'admin_commission')->value('value');

        if (!empty($delivery_charge))
            $delivery_boy_commision_setting = $delivery_charge;
        else
            $delivery_boy_commision_setting = $settings::where('key_word', 'delivery_boy_commission')->value('value');

        //$restaurant_commision_setting = $settings::where('key_word','restaurant_commission')->first();


        $admin_commission = ($bill_amount / 100) * $admin_commision_setting;
        $delivery_boy_commission =  $delivery_boy_commision_setting;
        if ($delivery_type == 2) {
            $delivery_boy_commission = 0;
            $takeaway_code = rand(10000, 99999);
        } else {
            $takeaway_code = '';
        }
        $restaurant_commission = $bill_amount - ($admin_commission + $delivery_boy_commission);

        $order_data = array();

        $order_data[] = array(
            'order_id' => $order_id,
            'user_id' => $user_id,
            'restaurant_id' => $restaurant_id,
            'item_total' => $item_total,
            'offer_discount' => $offer_discount,
            'restaurant_packaging_charge' => $restaurant_packaging_charge,
            'tax' => $gst,
            'delivery_charge' => $delivery_charge,
            'delivery_type' => $delivery_type,
            'takeaway_code' => $takeaway_code,
            'bill_amount' => $bill_amount,
            'admin_commision' => $admin_commission,
            'restaurant_commision' => $restaurant_commission,
            'delivery_boy_commision' => $delivery_charge,
            'coupon_code' => $coupon_code,
            'is_cod' => $is_cod,
            'cooking_notes' => $request->cooking_notes,
            'is_confirmed' => 0,
            'is_paid' => 0,
            'paid_type' => $paid_type,
            'delivery_address' => $delivery_address,
            'd_lat' => $d_lat,
            'd_lng' => $d_lng,
            'ordered_time' => date('Y-m-d H:i:s'),
        );

        if ($is_cod == 0) {
            if (env('PAYMENT_GATEWAY') == 'Stripe') {
                $order_data[0]['status'] = 0;
                //Stripe orders are paid in mobile before sending the requests.
                $order_data[0]['is_paid'] = 1;
            } else {
                $order_data[0]['status'] = -1;
            }
        }

        $foodrequest::insert($order_data);

        $last_id = $foodrequest::where('user_id', $user_id)->where('restaurant_id', $restaurant_id)->orderBy('id', 'desc')->first();

        $request_id = $last_id->id;

        $add_ons_all = explode(' ', $request->add_ons);

        for ($i = 0; $i < $food_id_size; $i++) {
            $request_detail = new Requestdetail;
            $request_detail->request_id = $last_id->id;
            $request_detail->restaurant_id = $restaurant_id;
            $request_detail->food_id = $food_id[$i];
            $request_detail->quantity = $food_qty[$i];
            $request_detail->addon_list = '0';
            $request_detail->food_quantity_price = $food_quantity_price[$i] ?? null;
            $request_detail->food_quantity = $food_quantity[$i] ?? null;
            $request_detail->save();

            $request_detail_id = $request_detail->id;
            if (isset($add_ons_all[$i]) && $add_ons_all[$i] != '' && $add_ons_all[$i] != 0) {
                $addon_ids = explode(',', $add_ons_all[$i]);
                for ($j = 0; $j < count($addon_ids); $j++) {
                    $add_ons = $this->add_ons->find($addon_ids[$j]);
                    if (!$add_ons) {
                        continue;
                    }
                    $requestdetail_addons = new RequestdetailAddons;
                    $requestdetail_addons->requestdetail_id = $request_detail_id;
                    $requestdetail_addons->addons_id = $add_ons->id;
                    $requestdetail_addons->name = $add_ons->name;
                    $requestdetail_addons->price = $add_ons->price;
                    $requestdetail_addons->save();
                }
            }
        }

        $checkout_url = null;
        $payment_id = '';

        $bill_amount_string = number_format($bill_amount, 2, '.', '');

        if ($is_cod == 0) {

            if (env('PAYMENT_GATEWAY') == 'Mollie') {
                $payment = mollie()->payments()->create([
                    "amount" => [
                        "currency" => "EUR",
                        "value" => $bill_amount_string
                    ],
                    "description" => $order_id,
                    "redirectUrl" => route('user.payment_completed_msg'),
                    "webhookUrl" => env('MOLLIE_PAYMENT_CALLBACK')
                ]);

                $checkout_url = $payment->_links->checkout->href ?? null;
                $payment_id = $payment->id;
            } else if (env('PAYMENT_GATEWAY') == 'Stripe') {
                $checkout_url = null;
                $payment_id = '';
            } else if (env('PAYMENT_GATEWAY') == 'Easy') {
                $EasyCheckout = new EasyCheckout;
                $EasyCheckout->isAPI = true;
                $checkoutData = $EasyCheckout->createCheckoutURL($bill_amount);

                if ($checkoutData->flag) {
                    $checkout_url = $checkoutData->data->hostedPaymentPageUrl;
                    $payment_id = $checkoutData->data->paymentId;
                }
            }

            DB::table('payments')->insert([
                'request_id' => $last_id->id,
                'payment_gateway' => env('PAYMENT_GATEWAY'),
                'amount' => $bill_amount_string,
                'currency' => env('CURRENCY_CODE'),
                'payment_id' => $payment_id,
                'payment_description' => $order_id
            ]);
        }

        // if ($is_cod == 1) {
        sendNewOrderEmail2Restaurant($request_id);
        sendNewOrderEmail2Customer($request_id);
        // }

        sendNotification(
            [$restaurant_details->device_token],
            trans('constants.notification_restaurant_new_title'),
            trans('constants.notification_restaurant_new_msg')
        );

        $header = array();
        $header[] = 'Content-Type: application/json';
        $postdata = array();
        // $postdata['id'] = $request_id;
        $postdata['user_id'] = $user_id;
        $postdata['request_id'] = $request_id;
        $postdata = json_encode($postdata);

        $ch = curl_init(FIREBASE_URL . "/new_user_request/$user_id.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);

        $header = array();
        $header[] = 'Content-Type: application/json';
        $postdata = array();
        // $postdata['id'] = $request_id;
        $postdata['request_id'] = (string)$request_id;
        $postdata['user_id'] = (float)$user_id;
        $postdata['provider_id'] = "0";
        $postdata['status'] = 0;
        $postdata = json_encode($postdata);

        $ch = curl_init(FIREBASE_URL . "/current_request/$request_id.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);

        //Restaurant notif
        $admin = DB::table('restaurants')->where('id', $restaurant_id)->first();

        $header = [];
        $header[] = 'Content-Type: application/json';
        $postdata = [
            'request_id' => (string) $request_id,
            'user_id' => (float) $user_id,
            'provider_id' => "0",
            'status' => 0
        ];

        $postdata = json_encode($postdata);

        $ch = curl_init(FIREBASE_URL . "/admin_requests/$admin->id.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);
        //Restaurant notif ends


        $cart::where('user_id', $user_id)->delete();

        $status_entry[] = array(
            'request_id' => $request_id,
            'status' => 0,
            'detail' => "Order Placed"
        );

        $trackorderstatus::insert($status_entry);

        return response()->json(['status' => true, 'message' => 'Order Placed Successfully', 'checkout_url' => $checkout_url], 200);
    }

    public function restaurants_for_schedule_delivery(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'date' => 'required',
                'time'    => 'required'
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = ['status' => false, 'error_code' => 101, 'message' => $error_messages];
            return response()->json($response_array, 200);
        }

        $scheduleAt = $request->date . ' ' . $request->time;

        $restaurants = $this->restaurants;

        $allRestaurants = $restaurants->where('status', 1)->get();

        $returnRestaurants = [];

        $restaurant_cuisines = DB::table('restaurant_cuisines')
            ->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
            ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
            ->select(
                'restaurant_cuisines.restaurant_id as restaurant_id',
                'cuisines.name as cuisine_name',
                'restaurants.restaurant_name as restaurant_name',
                'restaurants.delivery_type as delivery_type'
            )
            ->where('restaurants.status', 1)
            ->get();

        foreach ($allRestaurants as $eachRestaurant) {

            $restaurantRating = get_rating_statistics($eachRestaurant->id);
            $restaurantRating = (float) $restaurantRating['rating_percent'];
            $isFavourite = 0;
            $isOpen = Restaurant::isOpenAt($eachRestaurant->id, $scheduleAt);
            $delivery_type = json_decode($eachRestaurant->delivery_type);
            if (!$isOpen) {
                continue;
            }

            $rcuisines = [];

            foreach ($restaurant_cuisines as $r_cuisines) {
                if ($eachRestaurant->restaurant_name == $r_cuisines->restaurant_name) {
                    $added_cuisines = array_column($rcuisines, 'name');
                    if (!in_array($r_cuisines->cuisine_name, $added_cuisines)) {
                        $rcuisines[] = ['name' => $r_cuisines->cuisine_name];
                    }
                }
            }
            foreach ($delivery_type as $d_type) {
                if ($d_type == 4) {
                    $returnRestaurant = [
                        'id'             => $eachRestaurant->id,
                        'name'             => $eachRestaurant->restaurant_name,
                        'image' => File::exists(public_path('restaurant_uploads/').$eachRestaurant->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$eachRestaurant->image: (File::exists(public_path('restaurant_uploads/').$eachRestaurant->id.'/'.'Logo/'.$eachRestaurant->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$eachRestaurant->id.'/'.'Logo/'.$eachRestaurant->image : BASE_URL.UPLOADS_PATH_LOGO.$eachRestaurant->image),

                        'discount'         => $eachRestaurant->discount,
                        'rating'         => $restaurantRating,
                        'is_open'         => $isOpen,
                        'cuisines'         => $rcuisines,
                        'travel_time'     => $eachRestaurant->estimated_delivery_time,
                        // 'price' 		=> $eachRestaurant->price,
                        'is_favourite'     => $isFavourite,
                        'address'         => $eachRestaurant->address,
                    ];

                    $returnRestaurants[] = $returnRestaurant;
                }
            }
        }

        $response_array = [
            'status' => true,
            'restaurants' => $returnRestaurants,
        ];

        return response()->json($response_array, 200);
    }

    public function search_restaurants(Request $request)
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

        $restaurants = $this->restaurants;
        $key_word = $request->key_word;

        $source_lat = $request->lat;
        $source_lng = $request->lng;

        $availableRestIDs = [];

        $all_restaurant = $restaurants->where('status', 1)->select('id', 'city', 'lat', 'lng')->get();
        foreach ($all_restaurant as $each_restaurant) {
            if (!empty($source_lat) && !empty($source_lng)) {
                $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $each_restaurant->lat, $each_restaurant->lng);
                if ($distance && $distance <= DEFAULT_RADIUS) {
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


        $restaurant_cuisines = DB::table('restaurant_cuisines')
            ->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
            ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
            ->select(
                'restaurant_cuisines.restaurant_id as restaurant_id',
                'cuisines.name as cuisine_name',
                'restaurants.restaurant_name as restaurant_name'
            )
            ->where('restaurants.status', 1)
            ->get();

        $data = $restaurants::where('restaurant_name', 'like', '%' . $key_word . '%')->get();

        $data_foodlist = DB::table('food_list')
            ->join('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
            ->join('category', 'category.id', '=', 'food_list.category_id')
            ->where('food_list.status', 1)
            ->where('name', 'like', '%' . $key_word . '%')
            ->OrWhere('category_name', 'like', '%' . $key_word . '%')
            ->where('restaurants.status', 1)
            ->select('food_list.*', 'restaurants.restaurant_name', 'restaurants.image as restaurant_image', 'restaurants.rating', 'restaurants.lat', 'restaurants.lng', 'restaurants.estimated_delivery_time', 'category.category_name')
            ->orderby('restaurants.id', 'asc')
            ->get();

        $data_foodlist_return = [];

        foreach ($data_foodlist as $k => $d) {

            if (!empty($source_lat) && !empty($source_lng)) {
                $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $d->lat, $d->lng);
                if (!$distance || $distance > DEFAULT_RADIUS) {
                    continue;
                }
            }

            $data_foodlist[$k]->is_open =  restaurant_is_open($d->restaurant_id);
            if ($data_foodlist[$k]->is_open) {
                $data_foodlist_return[] = $data_foodlist[$k];
            }
        }

        $restIDs = [];
        foreach ($data as $value) {
            $restIDs[] = $value->id;
        }

        foreach ($data_foodlist as $value) {
            $restIDs[] = $value->restaurant_id;
        }

        $restIDs = array_values(array_unique($restIDs));

        $data = $restaurants::whereIn('id', $restIDs)->orderBy('restaurant_name', 'asc')->get();

        $restaurant_list = [];
        $j = 0;
        foreach ($data as $d) {

            if (!empty($source_lat) && !empty($source_lng)) {
                $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $d->lat, $d->lng);
                if (!$distance || $distance > DEFAULT_RADIUS) {
                    continue;
                }
            }


            $rcuisines = [];

            foreach ($restaurant_cuisines as $r_cuisines) {
                if ($d->restaurant_name == $r_cuisines->restaurant_name) {
                    $added_cuisines = array_column($rcuisines, 'name');
                    if (!in_array($r_cuisines->cuisine_name, $added_cuisines)) {
                        $rcuisines[] = ['name' => $r_cuisines->cuisine_name];
                    }
                }
            }

            // $rcuisines = array_unique($rcuisines); dd($rcuisines);

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d->id);
            $rating_statistics = get_rating_statistics($d->id);

            if (isset($request->city_id) && !empty($request->city_id)) {
                if ($d->city != $request->city_id) {
                    $j++;
                    continue;
                }
            }

            $restaurant_list[] = [
                'id'        => $d->id,
                'name'      => $d->restaurant_name,
                'image' => File::exists(public_path('restaurant_uploads/').$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->image: (File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Logo/'.$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Logo/'.$d->image : BASE_URL.UPLOADS_PATH_LOGO.$d->image),

                'discount'  => $d->discount,
                'rating'    => (float) $rating_statistics['rating_percent'],
                'is_open'   => $is_open,     // 1- Open , 0 - Close
                'cuisines'  => $rcuisines,
                'travel_time' => $d->estimated_delivery_time,
                'price'     => '',
                'is_favourite' => $is_favourite,
                'address' => $d->address
            ];

            $j++;
        }

        if ($restaurant_list || $data_foodlist_return) {
            $response_array = ['status' => true, 'restaurants' => $restaurant_list, 'foodlist' => $data_foodlist_return];
        } else {
            $response_array = ['status' => false, 'message' => 'No Restaurants Found'];
        }

        return response()->json($response_array, 200);
    }

    public function get_restaurants_by_cuisine(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'cuisine_ids' => 'required',
            ]
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

        $cuisineIDs = explode(',', $request->cuisine_ids);

        $restaurants = $this->restaurants;

        $restIDs = DB::table('restaurant_cuisines')->whereIn('cuisine_id', $cuisineIDs)->pluck('restaurant_id')->toArray();
        $restIDs = array_values(array_unique($restIDs));

        //geofencing for restaurant
        $data = $restaurants
            ->whereIn('id', $restIDs)
            ->get();

        $restaurant_list = [];

        foreach ($data as $d) {

            if (isset($request->lat) && isset($request->lng)) {
                $distance = vincentyGreatCircleDistance($request->lat, $request->lng, $d->lat, $d->lng);
                if ($distance == 0 || $distance > DEFAULT_RADIUS) {
                    continue;
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

            $restaurant_list[] = array(
                'id' => $d->id,
                'name' => $d->restaurant_name,
                'image' => File::exists(public_path('restaurant_uploads/').$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->image: (File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Logo/'.$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Logo/'.$d->image : BASE_URL.UPLOADS_PATH_LOGO.$d->image),

                'discount' => $d->discount,
                'rating' => $d->rating,
                'is_open' => $is_open, // 1- Open , 0 - Close
                'travel_time' => $d->estimated_delivery_time,
                'price' => '',
                'is_favourite' => $is_favourite,
                'address' => $d->address,
            );
        }

        if ($data) {
            $response_array = array('status' => true, 'restaurants' => $restaurant_list);
        } else {
            $response_array = array('status' => false, 'message' => 'No Restaurants Found');
        }



        $response = response()->json($response_array, 200);
        return $response;
    }

    public function payment_intent(Request $request)
    {

        $stripe = new Stripe(STRIPE_SK_KEY, STRIPE_VERSION);

        $paymentIntent = $stripe->paymentIntents()->create([
            'amount' => $request->amount,
            'currency' => env('CURRENCY_CODE'),
            'payment_method_types' => [
                'card',
            ],
            // 'capture_method' => 'manual'
        ]);

        return $paymentIntent;
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
                $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $each_restaurant->lat, $each_restaurant->lng);
                if ($distance && $distance <= DEFAULT_RADIUS) {
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


        $restaurant_cuisines = DB::table('restaurant_cuisines')
            ->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
            ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
            ->select(
                'restaurant_cuisines.restaurant_id as restaurant_id',
                'cuisines.name as cuisine_name',
                'restaurants.restaurant_name as restaurant_name'
            )
            ->where('restaurants.status', 1)
            ->get();

        $data = $restaurants::where('restaurant_name', 'like', '%' . $key_word . '%')->get();

        $food_list = DB::table('food_list')
            ->join('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
            ->join('category', 'category.id', '=', 'food_list.category_id')
            ->where('food_list.status', 1)
            ->where('name', 'like', '%' . $key_word . '%')
            ->OrWhere('category_name', 'like', '%' . $key_word . '%')
            ->where('restaurants.status', 1)
            ->select(
                'food_list.*',
                'restaurants.restaurant_name',
                'restaurants.is_open',
                'restaurants.image as restaurant_image',
                'restaurants.rating',
                'restaurants.lat',
                'restaurants.lng',
                'restaurants.estimated_delivery_time',
                'restaurants.DeliveryChargeBasedOn',
                'restaurants.address',
                'restaurants.restaurant_delivery_charge',
                'restaurants.delivery_type',
                'category.category_name'
            )
            ->orderby('restaurants.id', 'asc')
            ->get();
        //  print_r($food_list);

        foreach($food_list as $key => $menu)
        {
            if($menu->image == "")
            {
                $food_list[$key]->image ="";
            }
            else{
                //$food_list[$key]->image = File::exists(public_path('product_image/').$menu->image)? UPLOADS_PATH_PRODUCT.$menu->image: (File::exists(public_path('restaurant_uploads/').$menu->restaurant_id.'/'.'Product/'.$menu->image)? RESTAURANT_UPLOADS_PATH.$menu->restaurant_id.'/'.'Product/'.$menu->image :$menu->image);
                $food_list[$key]->image = File::exists(public_path('product_image/').$menu->image)? UPLOADS_PATH_PRODUCT.$menu->image:( File::exists(public_path('restaurant_uploads/').$menu->restaurant_id.'/'.'Product/'.$menu->image)? RESTAURANT_UPLOADS_PATH.$menu->restaurant_id.'/'.'Product/'.$menu->image :(File::exists(public_path('common_images/').$menu->image)? UPLOADS_PATH_COMMON.$menu->image:$menu->image));
            }
       
        }
        foreach ($food_list as $key => $each_food) {
            $this_addon = DB::table('foodlist_addons')
                ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                ->where('foodlist_id', $each_food->id)
                ->select('foodlist_addons.addons_id as id', 'add_ons.name', 'add_ons.restaurant_id',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at')
                ->get()->toArray();

            if (!$this_addon) {
                $food_list[$key]->add_ons = [];
                continue;
            }
            //print_r($this_addon);
            foreach ($this_addon as $addon_key => $each_addon) {
                $this_addon[$addon_key]->restaurant_id = $each_addon->restaurant_id;
                $this_addon[$addon_key]->pivot = ['foodlist_id' => $each_food->id, 'addons_id' => $each_addon->id];
            }

            $food_list[$key]->add_ons = $this_addon;
        }
        //deliverycharge limit response get

        foreach ($food_list as $key => $each_size) {

            $is_open = restaurant_is_open($each_size->restaurant_id);
            $food_list[$key]->is_open = $is_open;

            $delivery_charge = DB::table('delivery_charge_limits')
                ->where('restaurant_id', $each_size->restaurant_id)
                ->select('Minimum', 'Maximum', 'Value',  'Type')
                ->get()->toArray();
            // print_r($delivery_charge);
            if (!$delivery_charge) {
                $food_list[$key]->delivery_charge = [];
                continue;
            }

            // if (!empty($source_lat) && !empty($source_lng)) {
            //     $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $each_size->lat, $each_size->lng);
            //     if (!$distance || $distance > DEFAULT_RADIUS) { 
            //         continue;
            //     }
            // }



            $food_list[$key]->restaurant_image = File::exists(public_path('restaurant_uploads/').$each_size->restaurant_image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$each_size->restaurant_image: (File::exists(public_path('restaurant_uploads/').$each_size->restaurant_id.'/'.'Logo/'.$each_size->restaurant_image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$each_size->restaurant_id.'/'.'Logo/'.$each_size->restaurant_image : BASE_URL.UPLOADS_PATH_LOGO.$each_size->restaurant_image);

            $food_list[$key]->delivery_type =   json_decode($each_size->delivery_type);
            $food_list[$key]->delivery_charge = $delivery_charge;
        }
        //end deliverycharge limit response get

        // print_r($food_list);
        foreach ($food_list as $key => $each_food) {

            $item_count = 0;
            if (count($check_for_cart) != 0) {
                foreach ($check_for_cart as $check_for_item) {
                    if ($each_food->food_id == $check_for_item->food_id) {
                        $item_count = $check_for_item->quantity;
                    }
                }
            }
            $food_list[$key]->item_count = $item_count;


            $this_size = DB::table('foodlist_foodquantity')
                ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
                ->where('foodlist_id', $each_food->id)
                ->select(
                    'foodlist_foodquantity.foodquantity_id as id',
                    'food_quantity.name',
                    'food_quantity.secondary_name',
                    'foodlist_foodquantity.price',
                    'foodlist_foodquantity.is_default',
                    'foodlist_foodquantity.created_at',
                    'foodlist_foodquantity.updated_at'
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }

            foreach ($this_size as $size_key => $each_size) {
                $this_size[$size_key]->pivot = [
                    'foodlist_id' => $each_food->id,
                    'foodquantity_id' => $each_size->id,
                    'price' => $each_size->price,
                    'is_default' => $each_size->is_default
                ];
            }

            $food_list[$key]->food_quantity = $this_size;
        }
        $food_list1 = [];
        $f = 0;
        foreach ($food_list as $food_key => $d) {
            if (!empty($source_lat) && !empty($source_lng)) {
                $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $d->lat, $d->lng);
                if (!$distance || $distance > DEFAULT_RADIUS) {
                    continue;
                }
            }


            $rcuisines = [];

            foreach ($restaurant_cuisines as $r_cuisines) {
                if ($d->restaurant_name == $r_cuisines->restaurant_name) {
                    $added_cuisines = array_column($rcuisines, 'name');
                    if (!in_array($r_cuisines->cuisine_name, $added_cuisines)) {
                        $rcuisines[] = ['name' => $r_cuisines->cuisine_name];
                    }
                }
            }

            // $rcuisines = array_unique($rcuisines); dd($rcuisines);

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d->id);
            $rating_statistics = get_rating_statistics($d->id);

            if (isset($request->city_id) && !empty($request->city_id)) {
                if ($d->city != $request->city_id) {
                    $f++;
                    continue;
                }
            }

            $food_list1[$food_key] = $d;

            $f++;
        }
        $new_food_list = [];

        foreach ($food_list1 as $key => $each_food) { //print_r($each_food);
            $new_food_list[$each_food->restaurant_id][] = $each_food;
        }

        $return_food_list = [];
        foreach ($new_food_list as $key => $each_menu) {
            $isOpen_schedule = Restaurant::isOpenAt($each_menu[0]->restaurant_id, $scheduleAt);
            if ($request->date && $request->time) {
                if ($isOpen_schedule == 1) {
                    $return_food_list[] = [
                        //'menu_id' => $each_menu[0]->menu_id,
                        //'menu_name' => $each_menu[0]->menu_name,
                        'items' => $each_menu,
                    ];
                }
            } else {
                $return_food_list[] = [
                    //'menu_id' => $each_menu[0]->menu_id,
                    //'menu_name' => $each_menu[0]->menu_name,
                    'items' => $each_menu,
                ];
            }
        }

        //print_r($return_food_list);
        //end

        $restIDs = [];
        foreach ($data as $value) {
            $restIDs[] = $value->id;
        }

        foreach ($food_list as $value) {
            // foreach ($data_foodlist as $value) {food_list
            $restIDs[] = $value->restaurant_id;
        }

        $restIDs = array_values(array_unique($restIDs));

        $data = $restaurants::whereIn('id', $restIDs)->orderBy('restaurant_name', 'asc')->get();

        $restaurant_list = [];
        $j = 0;
        // print_r($data);

        foreach ($data as $d) {

            if (!empty($source_lat) && !empty($source_lng)) {
                $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $d->lat, $d->lng);
                if (!$distance || $distance > DEFAULT_RADIUS) {
                    continue;
                }
            }


            $rcuisines = [];

            foreach ($restaurant_cuisines as $r_cuisines) {
                if ($d->restaurant_name == $r_cuisines->restaurant_name) {
                    $added_cuisines = array_column($rcuisines, 'name');
                    if (!in_array($r_cuisines->cuisine_name, $added_cuisines)) {
                        $rcuisines[] = ['name' => $r_cuisines->cuisine_name];
                    }
                }
            }

            // $rcuisines = array_unique($rcuisines); dd($rcuisines);

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d->id);
            if (isset($scheduleAt)) {
                $isOpen_schedule = Restaurant::isOpenAt($d->id, $scheduleAt);
            } else {
                $isOpen_schedule = null;
            }
            $rating_statistics = get_rating_statistics($d->id);

            if (isset($request->city_id) && !empty($request->city_id)) {
                if ($d->city != $request->city_id) {
                    $j++;
                    continue;
                }
            }

            if ($request->date && $request->time) {
                if ($isOpen_schedule == 1) {
                    $restaurant_list[] = [
                        'id'        => $d->id,
                        'name'      => $d->restaurant_name,
                        'image' => File::exists(public_path('restaurant_uploads/').$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->image: (File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Logo/'.$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Logo/'.$d->image : BASE_URL.UPLOADS_PATH_LOGO.$d->image),

                        'discount'  => $d->discount,
                        'rating'    => (float) $rating_statistics['rating_percent'],
                        'is_open'   => $is_open,
                        'schedule_open' => $isOpen_schedule,     // 1- Open , 0 - Close
                        'cuisines'  => $rcuisines,
                        'travel_time' => $d->estimated_delivery_time,
                        'price'     => '',
                        'is_favourite' => $is_favourite,
                        'address' => $d->address
                    ];
                }
            } else {
                $restaurant_list[] = [
                    'id'        => $d->id,
                    'name'      => $d->restaurant_name,
                    'image' => File::exists(public_path('restaurant_uploads/').$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->image: (File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Logo/'.$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Logo/'.$d->image : BASE_URL.UPLOADS_PATH_LOGO.$d->image),

                    'discount'  => $d->discount,
                    'rating'    => (float) $rating_statistics['rating_percent'],
                    'is_open'   => $is_open,
                    'schedule_open' => $isOpen_schedule,     // 1- Open , 0 - Close
                    'cuisines'  => $rcuisines,
                    'travel_time' => $d->estimated_delivery_time,
                    'price'     => '',
                    'is_favourite' => $is_favourite,
                    'address' => $d->address
                ];
            }
            $j++;
        }

        if ($restaurant_list || $return_food_list) {
            // if ($restaurant_list || $data_foodlist_return) {return_food_list
            $response_array = ['status' => true, 'restaurants' => $restaurant_list, 'foodlist' => $return_food_list];
            // $response_array = ['status'=>true, 'restaurants' => $restaurant_list, 'foodlist' => $data_foodlist_return];
        } else {
            $response_array = ['status' => false, 'message' => 'No Restaurants Found'];
        }

        return response()->json($response_array, 200);
    }

    public function get_brands(request $request)
    {
        $lat = $request->lat;
        $lag = $request->lag;

        $data = DB::table('brands')

                ->leftJoin('restaurants', 'restaurants.brand_name', '=', 'brands.id')
                ->where('brands.status','=',1)
                ->where('restaurants.brand_name','!=',"")
                
                ->select('brands.id', 'brands.status','brands.name','restaurants.image','brands.rank','restaurants.lat','restaurants.lng')
                ->orderBy('brands.rank', 'ASC')->get();

                foreach ($data as $value) {
                    $geofenceRadius = (float) ($value->GeofenceRadius ?? DEFAULT_RADIUS);
                    $distance = vincentyGreatCircleDistance($lat, $lag, $value->lat, $value->lng);
                 if ($distance <= $geofenceRadius) {
                        $response_array[] = array(
                            'id' => $value->id,
                            'brand_name' => $value->name,
                            'status' => $value->status ,
                            'logo' => $value->image,
                            'rank' => $value->rank
                           
                        );
                    }
                             
                }

        $outpute = array_unique($response_array, SORT_REGULAR);
        $response = response()->json($outpute, 200);

        return $response;
    }

    public function get_restaurantby_brand(request $request)
    {
        $brand_id = $request->brand_id;
        $lat = $request->lat;
        $lag = $request->lag;

        $data = DB::table('restaurants')
            ->where('brand_name', $brand_id)->get();
        $restaurant_cuisines = DB::table('restaurant_cuisines')
            ->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
            ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
            ->select(
                'restaurant_cuisines.restaurant_id as restaurant_id',
                'cuisines.name as cuisine_name',
                'restaurants.restaurant_name as restaurant_name'
            )
            ->where('restaurants.status', 1)
            ->get();
        $rcuisines = [];




        // print_r($data);
        foreach ($data as $value) {
            $rating_statistics = get_rating_statistics($value->id);
            $is_open = restaurant_is_open($value->id);


            foreach ($restaurant_cuisines as $r_cuisines) {
                if ($value->restaurant_name == $r_cuisines->restaurant_name) {
                    $added_cuisines = array_column($rcuisines, 'name');
                    if (!in_array($r_cuisines->cuisine_name, $added_cuisines)) {
                        $rcuisines[] = ['name' => $r_cuisines->cuisine_name];
                    }
                }
            }
            $geofenceRadius = (float) ($value->GeofenceRadius ?? DEFAULT_RADIUS);
            $distance = vincentyGreatCircleDistance($lat, $lag, $value->lat, $value->lng);

            if ($distance <= $geofenceRadius) {
                $response_array[] = array(
                    'id'        => $value->id,
                    'name'      => $value->restaurant_name,
                    'image' => File::exists(public_path('restaurant_uploads/').$value->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$value->image: (File::exists(public_path('restaurant_uploads/').$value->id.'/'.'Logo/'.$value->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$value->id.'/'.'Logo/'.$value->image : BASE_URL.UPLOADS_PATH_LOGO.$value->image),

                    'discount'  => $value->discount,
                    'rating'    => (float) $rating_statistics['rating_percent'],
                    'is_open'   => $is_open,
                    // 1- Open , 0 - Close
                    'cuisines'  => $rcuisines,
                    'travel_time' => $value->estimated_delivery_time,
                    'price'     => '',

                    'address' => $value->address
                );
            }
        }
        if (isset($response_array)) {
            $out_array = array('status' => true, 'restaurants' => $response_array);
        } else {
            $out_array = array('status' => false, 'message' => 'No Restaurants Found');
        }
        $response = response()->json($out_array, 200);
        return $response;
    }
}
