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
use File;
use Hash;
use App\Http\Controllers\Mailer;
use App\Http\Controllers\Restaurant;
use App\Http\Controllers\Order;


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
            $title = json_decode($data->video_title);
            $url = "";
            $acc_hash = "";
            $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
            if ($cloudflare->value == 1) {
                $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
                $url = $get_url->value;

                $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
                $acc_hash = $get_acchash->value;
            }
            if ($request->date && $request->time) {
                $isOpen_schedule = Restaurant::isOpenAt($data->id, $scheduleAt);
                if ($cloudflare->value == 1 && $data->cloudflare_imageid != null) {

                    $img = $url . '/' . $acc_hash . '/' . $data->cloudflare_imageid . '/' . "w=300";
                } else {
                    $img = File::exists(public_path('restaurant_uploads/') . $data->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->image : (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Logo/' . $data->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Logo/' . $data->image : BASE_URL . UPLOADS_PATH_LOGO . $data->image);
                }

                if ($cloudflare->value == 1 && $data->cloudflare_bannerid != null) {

                    $ban = $url . '/' . $acc_hash . '/' . $data->cloudflare_bannerid . '/' . "w=430,height=287,trim=0;400";
                } else {
                    $ban = File::exists(public_path('restaurant_uploads/') . $data->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->banner : (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Banner/' . $data->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Banner/' . $data->banner : BASE_URL . UPLOADS_PATH_BANNER . $data->banner);
                }
                $restaurant_list[] = array(
                    'id'            => $data->id,
                    'name' => $data->restaurant_name,
                    'delivery_type'          => json_decode($data->delivery_type),
                    'image' => $img,
                    'banner' => $ban,

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
                if ($cloudflare->value == 1 && $data->cloudflare_imageid != null) {

                    $img = $url . '/' . $acc_hash . '/' . $data->cloudflare_imageid . '/' . "w=300";
                } else {
                    $img = File::exists(public_path('restaurant_uploads/') . $data->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->image : (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Logo/' . $data->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Logo/' . $data->image : BASE_URL . UPLOADS_PATH_LOGO . $data->image);
                }

                if ($cloudflare->value == 1 && $data->cloudflare_bannerid != null) {

                    $ban = $url . '/' . $acc_hash . '/' . $data->cloudflare_bannerid . '/' . "w=430,height=287,trim=0;400";
                } else {
                    $ban = File::exists(public_path('restaurant_uploads/') . $data->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->banner : (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Banner/' . $data->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Banner/' . $data->banner : BASE_URL . UPLOADS_PATH_BANNER . $data->banner);
                }
                $restaurant_list[] = array(
                    'id'            => $data->id,
                    'name' => $data->restaurant_name,
                    'delivery_type'          => json_decode($data->delivery_type),
                    'image'         => $img,
                    'banner'         => $ban,
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


        $business = $request->business_id;
        $foodlist = $this->foodlist;
        $category = $this->category;
        $menu = $this->menu;
        $scheduleAt = $request->date . ' ' . $request->time;
        $data = DB::table('category')->where('business_type', $business)->join('food_list', 'food_list.category_id', '=', 'category.id')->select('category.*', 'food_list.restaurant_id')->groupBy('food_list.category_id')->get();

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
        $business_res = DB::table('restaurants')->where('id', $restaurant_id)->first();
        $business = DB::table('business_type')->where('id', $business_res->business_type)->first();
        $layout = DB::table('layout')->where('id', $business->layout_id)->first();

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

        if ($layout->id == 2) {

            if ($request->category_id == 0) {

                $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                    ->where('food_list.status', 1)
                    ->where('food_list.out_of_stock', 0)
                    ->select('food_list.id as food_id', 'food_list.restaurant_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax',  'food_list.image as image')
                    //->where('food_list.name', 'BBQ Chicken Wings')
                    ->orderBy('image', 'desc')
                    ->get();
            } else {

                $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                    ->where('food_list.category_id', $request->category_id)
                    ->where('food_list.status', 1)
                    ->where('food_list.out_of_stock', 0)

                    ->select('food_list.id as food_id', 'food_list.restaurant_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'food_list.image as image')
                    //->where('food_list.name', 'BBQ Chicken Wings')
                    ->orderBy('image', 'desc')
                    ->get();
            }
        } else {
            if ($request->category_id == 0) {

                $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                    ->where('food_list.status', 1)
                    ->where('food_list.out_of_stock', 0)

                    ->join(
                        'menu',
                        function ($join) {
                            $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                            $join->on('menu.id', '=', 'food_list.menu_id');
                        }
                    )
                    ->select('food_list.id as food_id', 'food_list.restaurant_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image')
                    //->where('food_list.name', 'BBQ Chicken Wings')
                    ->orderBy('rank')->orderBy('image', 'desc')
                    ->get();
            } else {

                $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                    ->where('food_list.category_id', $request->category_id)
                    ->where('food_list.status', 1)
                    ->where('food_list.out_of_stock', 0)

                    ->join(
                        'menu',
                        function ($join) {
                            $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                            $join->on('menu.id', '=', 'food_list.menu_id');
                        }
                    )
                    ->select('food_list.id as food_id', 'food_list.restaurant_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image')
                    //->where('food_list.name', 'BBQ Chicken Wings')
                    ->orderBy('rank')->orderBy('image', 'desc')
                    ->get();
            }
        }


        foreach ($food_list as $key => $menu) {
            if ($menu->image == "") {
                $food_list[$key]->image = "";
            } else {
                $food_list[$key]->image = File::exists(public_path('product_image/') . $menu->image) ? UPLOADS_PATH_PRODUCT . $menu->image : (File::exists(public_path('restaurant_uploads/') . $menu->restaurant_id . '/' . 'Product/' . $menu->image) ? RESTAURANT_UPLOADS_PATH . $menu->restaurant_id . '/' . 'Product/' . $menu->image : (File::exists(public_path('common_images/') . $menu->image) ? UPLOADS_PATH_COMMON . $menu->image : $menu->image));
            }
        }

        foreach ($food_list as $key => $each_food) {
            $this_addon = DB::table('foodlist_addons')
                ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select('foodlist_addons.addons_id as id', 'add_ons.name',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at', 'foodlist_addons.addon_group')
                ->get()->toArray();

            if (!$this_addon) {
                $food_list[$key]->add_ons = [];
                continue;
            }


            foreach ($this_addon as $addon_key => $each_addon) {

                $this_addon[$addon_key]->id = (string) $this_addon[$addon_key]->id;
                $this_addon[$addon_key]->restaurant_id = $restaurant_id;
                $this_addon[$addon_key]->pivot = ['foodlist_id' => $each_food->food_id, 'addons_id' => $each_addon->id];
            }
            $food_list[$key]->add_ons = $this_addon;
        }

        foreach ($food_list as $key => $each_food) {
            $this_gaddon = DB::table('foodlist_addons')
                ->where('foodlist_id', $each_food->food_id)
                ->where('addons_id', null)
                ->get();
            $add_group = [];
            foreach ($this_gaddon as $addon_key => $each_addon) {
                $gadd_ons = [];
                if ($each_addon->addon_group != null) {
                    $group = DB::table('addon_group')->where('id', $each_addon->addon_group)->first();

                    $array_group = json_decode($group->add_ons);

                    foreach ($array_group as $ag) {
                        $addon_detail = DB::table('add_ons')->where('id', $ag)->first();


                        $gadd_ons[] = [
                            'id' => (string) $ag,
                            'name' => $addon_detail->name,
                            'secondary_name' => $addon_detail->secondary_name,
                            'price' => $addon_detail->price

                        ];
                    }
                    $add_group[] = [
                        'id' => (string) $each_addon->addon_group,
                        'name' => $group->name,
                        'min' => $group->min,
                        'max' => $group->max,
                        'allow_multiple' => $group->allow_multiple,
                        'mandatory' => $group->mandatory,
                        'group_addons' => $gadd_ons,
                        'restaurant' => $restaurant_id
                    ];
                }
            }
            $food_list[$key]->groups = $add_group;
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
                    'foodlist_foodquantity.updated_at',
                    'foodlist_foodquantity.addons_id',
                    'foodlist_foodquantity.addon_group',
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }
            foreach ($this_size as $size_key => $each_size) {
                $sadd_group = [];

                if ($each_size->addon_group != null) {
                    $sg = $each_size->addon_group;
                    $sg_group = json_decode($sg);



                    foreach ($sg_group as $sgg) {
                        $sgadd_ons = [];
                        $sgg_group = DB::table('addon_group')->where('id', $sgg)->first();

                        $sarray_group = json_decode($sgg_group->add_ons);
                        foreach ($sarray_group as $sag) {
                            $saddon_detail = DB::table('add_ons')->where('id', $sag)->first();
                            $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('group_id', $sgg)->where('addon', $sag)->first();
                            if ($variant) {
                                $price = $variant->price;
                                $stock = $variant->stock;
                            } else {
                                $price = $saddon_detail->price;
                                $stock = 0;
                            }

                            $sgadd_ons[] = [
                                'id' => (string) $sag,
                                'name' => $saddon_detail->name,
                                'secondary_name' => $saddon_detail->secondary_name,
                                'price' => $price,
                                'stock' => $stock

                            ];
                        }
                        $sadd_group[] = [
                            'id' => (string) $sgg,
                            'name' => $sgg_group->name,
                            'min' => $sgg_group->min,
                            'max' => $sgg_group->max,
                            'allow_multiple' => $sgg_group->allow_multiple,
                            'mandatory' => $sgg_group->mandatory,
                            'group_addons' => $sgadd_ons

                        ];
                    }
                }
                $this_size[$size_key]->groups = $sadd_group;
                $saa_addons = [];

                if ($each_size->addons_id != null) {
                    $sa = $each_size->addons_id;
                    $sa_group = json_decode($sa);

                    foreach ($sa_group as $saa) {
                        $sa_detail = DB::table('add_ons')->where('id', $saa)->first();
                        $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('addon', (int)$saa)->first();
                        if ($variant) {
                            $price = $variant->price;
                            $stock = $variant->stock;
                        } else {
                            $price = $sa_detail->price;
                            $stock = 0;
                        }
                        $saa_addons[] = [
                            'id' => (string) $saa,
                            'name' => $sa_detail->name,
                            'secondary_name' => $sa_detail->secondary_name,
                            'price' => $price,
                            'stock' => $stock
                        ];
                    }
                }
                $this_size[$size_key]->add_ons = $saa_addons;

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

            if ($layout->id == 2) {
                $new_food_list[$each_food->category_id][] = $each_food;
            } else {
                if ($is_veg == 1 && $each_food->is_veg == 0) {
                    continue;
                }
                $new_food_list[$each_food->menu_id][] = $each_food;
            }
        }

        $return_food_list = [];
        foreach ($new_food_list as $key => $each_menu) {
            // checking menu avaible now

            $available = 0;
            if ($layout->id == 2) {
                $return_food_list[] = [
                    'items' => $each_menu,
                ];
            } else {
                $menutime = DB::table('menu_timing')->where('menu_id', $each_menu[0]->menu_id)->get();
                if (sizeof($menutime)) {

                    foreach ($menutime as $key => $time) {
                        $times = Carbon::now();
                        $current_time = $times->toTimeString();
                        $date1 = $current_time;
                        $date2 = $time->from_time;
                        $date3 = $time->to_time;
                        if ($date1 > $date2 && $date1 < $date3) {
                            $available = 1;
                        }
                    }
                } else {
                    $available = 1; //latest feature so some menu dont assume time so set is available
                }
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

        $restaurant_detail->image = File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image);

        if (!$restaurant_detail) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Store not found'];
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
            ->select('food_list.id as food_id', 'food_list.restaurant_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image')
            //->where('food_list.name', 'BBQ Chicken Wings')
            ->orderBy('rank')->orderBy('image', 'desc')
            ->get();
        foreach ($food_list as $key => $menu) {
            if ($menu->image == "") {
                $food_list[$key]->image = "";
            } else {
                //  $food_list[$key]->image = File::exists(public_path('product_image/').$menu->image)? UPLOADS_PATH_PRODUCT.$menu->image: (File::exists(public_path('restaurant_uploads/').$menu->restaurant_id.'/'.'Product/'.$menu->image)? RESTAURANT_UPLOADS_PATH.$menu->restaurant_id.'/'.'Product/'.$menu->image :$menu->image);
                $food_list[$key]->image = File::exists(public_path('product_image/') . $menu->image) ? UPLOADS_PATH_PRODUCT . $menu->image : (File::exists(public_path('restaurant_uploads/') . $menu->restaurant_id . '/' . 'Product/' . $menu->image) ? RESTAURANT_UPLOADS_PATH . $menu->restaurant_id . '/' . 'Product/' . $menu->image : (File::exists(public_path('common_images/') . $menu->image) ? UPLOADS_PATH_COMMON . $menu->image : $menu->image));
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
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Store not found'];
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
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Store not found'];
            return response()->json($response_array, 200);
        }

        $restaurant_detail->image = File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image);

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
            $restaurant->is_open = restaurant_is_open($request->restaurant_id);
            $tax_inclusive = DB::table('settings')->where('key_word', 'tax_inclusive')->first();

            $restaurant_detail = array();

            $restaurant_detail[] = array(
                'restaurant_id' => $restaurant->id,
                'name' => $restaurant->restaurant_name,
                'image' => File::exists(public_path('restaurant_uploads/') . $restaurant->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant->image : (File::exists(public_path('restaurant_uploads/') . $restaurant->id . '/' . 'Logo/' . $restaurant->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant->id . '/' . 'Logo/' . $restaurant->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant->image),

                'address' => $restaurant->address,
                'is_open' => $restaurant->is_open,
                'estimated_delivery_time' => $restaurant->estimated_delivery_time,
                'is_tax_inclusive' => (int) $tax_inclusive->value,
                'lat' => $restaurant->lat,
                'lng' => $restaurant->lng,
                'business_type' => $restaurant->business_type
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
            $tips_enable = DB::table('settings')->where('key_word', "tips_enable")->first();
            $tips_text = DB::table('settings')->where('key_word', "tips_text")->first();

            $selected_tips = DB::table('tips')->where('selected', 1)->get();
            $sel = [];
            foreach ($selected_tips as $st) {
                $sel[] = $st->value;
            }
            if (isset($request->user_id)) {
                $user = DB::table('users')->select('wallet_amount', 'name')->where('id', $request->user_id)->get();
                $response_array = array('status' => true, 'restaurant_detail' => $restaurant_detail, 'invoice' => $invoice, 'user' => $user, 'tips' => $sel, 'tips_enable' => $tips_enable->value, 'tips_text' => $tips_text->value);
            } else {
                $response_array = array('status' => true, 'restaurant_detail' => $restaurant_detail, 'invoice' => $invoice, 'tips' => $sel, 'tips_enable' => $tips_enable->value, 'tips_text' => $tips_text->value);
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
            return response()->json(['status' => false, 'message' => 'Store not found'], 200);;
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
                        'image'         => BASE_URL . RESTAURANT_UPLOADS_PATH . $eachRestaurant->image,
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
                'image' => File::exists(public_path('restaurant_uploads/') . $eachRestaurant->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $eachRestaurant->image : (File::exists(public_path('restaurant_uploads/') . $eachRestaurant->id . '/' . 'Logo/' . $eachRestaurant->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $eachRestaurant->id . '/' . 'Logo/' . $eachRestaurant->image : BASE_URL . UPLOADS_PATH_LOGO . $eachRestaurant->image),

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
            $response_array = ['status' => false, 'message' => 'No Stores Found'];
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
                'image' => File::exists(public_path('restaurant_uploads/') . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Logo/' . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image : BASE_URL . UPLOADS_PATH_LOGO . $d->image),

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
            $response_array = array('status' => false, 'message' => 'No Stores Found');
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
            ->leftjoin('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
            ->leftjoin('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
            ->select(
                'restaurant_cuisines.restaurant_id as restaurant_id',
                'cuisines.name as cuisine_name',
                'cuisines.secondary_name as secondary_name',
                'restaurants.restaurant_name as restaurant_name'
            )
            ->where('restaurants.status', 1)
            ->get();

        $data = $restaurants::where('restaurant_name', 'like', '%' . $key_word . '%')->get();

        $food_list = DB::table('food_list')
            ->leftjoin('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
            ->leftjoin('category', 'category.id', '=', 'food_list.category_id')
            ->leftjoin('menu', 'menu.id', '=', 'food_list.menu_id')
            ->where('food_list.status', 1)
            ->where('name', 'like', '%' . $key_word . '%')
            ->OrWhere('category_name', 'like', '%' . $key_word . '%')
            ->OrWhere('menu_name', 'like', '%' . $key_word . '%')
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
                'restaurants.business_type',
                'category.category_name',
                'food_list.restaurant_id'
            )
            ->orderby('restaurants.id', 'asc')
            ->get();
        //  print_r($food_list);

        foreach ($food_list as $dt) {

            $dt->delivery_type =   json_decode($dt->delivery_type);
        }

        foreach ($food_list as $key => $each_food) {
            $this_addon = DB::table('foodlist_addons')
                ->leftjoin('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                ->where('foodlist_id', $each_food->id)
                ->where('addons_id', '!=', null)
                ->select('foodlist_addons.addons_id as id', 'add_ons.name', 'add_ons.restaurant_id',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at')
                ->get()->toArray();
            // $each_food->image = File::exists(public_path('/').$each_food->image)? $each_food->image: (File::exists(public_path('product_image/').$each_food->image) ? UPLOADS_PATH_PRODUCT.$each_food->image : $each_food->image);
            $each_food->image = File::exists(public_path('product_image/') . $each_food->image) ? UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $each_food->restaurant_id . '/' . 'Product/' . $each_food->image) ? RESTAURANT_UPLOADS_PATH . $each_food->restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? UPLOADS_PATH_COMMON . $each_food->image : $each_food->image));

            if (!$this_addon) {
                $food_list[$key]->add_ons = [];
                continue;
            }
            //print_r($this_addon);


            foreach ($this_addon as $addon_key => $each_addon) {

                $this_addon[$addon_key]->id = (string) $this_addon[$addon_key]->id;
                $this_addon[$addon_key]->restaurant_id = $each_addon->restaurant_id;
                $this_addon[$addon_key]->pivot = ['foodlist_id' => $each_food->id, 'addons_id' => $each_addon->id];
            }

            $food_list[$key]->add_ons = $this_addon;
        }
        //deliverycharge limit response get

        foreach ($food_list as $key => $each_size) {

            $is_open = restaurant_is_open($each_size->restaurant_id);
            $food_list[$key]->is_open = $is_open;

            $layout =  DB::table('business_type')->where('id', $each_size->business_type)->first();
            if ($layout) {
                $food_list[$key]->layout_id =  $layout->layout_id;
            } else {
                $food_list[$key]->layout_id =  '';
            }


            $delivery_charge = DB::table('delivery_charge_limits')
                ->where('restaurant_id', $each_size->restaurant_id)
                ->select('Minimum', 'Maximum', 'Value',  'Type')
                ->get()->toArray();
            if (!$delivery_charge) {
                $food_list[$key]->delivery_charge = [];
                continue;
            }


            $food_list[$key]->restaurant_image = File::exists(public_path('restaurant_uploads/') . $each_size->restaurant_image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $each_size->restaurant_image : (File::exists(public_path('restaurant_uploads/') . $each_size->restaurant_id . '/' . 'Logo/' . $each_size->restaurant_image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $each_size->restaurant_id . '/' . 'Logo/' . $each_size->restaurant_image : BASE_URL . UPLOADS_PATH_LOGO . $each_size->restaurant_image);

            $food_list[$key]->delivery_charge = $delivery_charge;
        }

        foreach ($food_list as $key => $each_food) {
            $this_gaddon = DB::table('foodlist_addons')
                ->where('foodlist_id', $each_food->id)
                ->where('addons_id', null)
                ->get();
            $add_group = [];
            foreach ($this_gaddon as $addon_key => $each_addon) {
                $gadd_ons = [];
                if ($each_addon->addon_group != null) {
                    $group = DB::table('addon_group')->where('id', $each_addon->addon_group)->first();

                    $array_group = json_decode($group->add_ons);

                    foreach ($array_group as $ag) {
                        $addon_detail = DB::table('add_ons')->where('id', $ag)->first();


                        $gadd_ons[] = [
                            'id' => (string) $ag,
                            'name' => $addon_detail->name ? html_entity_decode($addon_detail->name, ENT_QUOTES | ENT_XML1, 'UTF-8') : '',
                            'secondary_name' => $addon_detail->secondary_name ? html_entity_decode($addon_detail->secondary_name, ENT_QUOTES | ENT_XML1, 'UTF-8') : '',
                            'price' => $addon_detail->price

                        ];
                    }
                    $add_group[] = [
                        'id' => (string)$each_addon->addon_group,
                        'name' => $group->name,
                        'min' => $group->min,
                        'max' => $group->max,
                        'allow_multiple' => $group->allow_multiple,
                        'mandatory' => $group->mandatory,
                        'group_addons' => $gadd_ons,
                        'restaurant' => $each_food->restaurant_id
                    ];
                }
            }
            $food_list[$key]->groups = $add_group;
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
                ->leftjoin('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
                ->where('foodlist_id', $each_food->id)
                ->select(
                    'foodlist_foodquantity.foodquantity_id as id',
                    'food_quantity.name',
                    'food_quantity.secondary_name',
                    'foodlist_foodquantity.price',
                    'foodlist_foodquantity.is_default',
                    'foodlist_foodquantity.created_at',
                    'foodlist_foodquantity.updated_at',
                    'foodlist_foodquantity.updated_at',
                    'foodlist_foodquantity.addons_id',
                    'foodlist_foodquantity.addon_group',
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }

            foreach ($this_size as $size_key => $each_size) {
                $sadd_group = [];
                if ($each_size->addon_group != null) {
                    $sg = $each_size->addon_group;
                    $sg_group = json_decode($sg);




                    foreach ($sg_group as $sgg) {
                        $sgadd_ons = [];
                        $sgg_group = DB::table('addon_group')->where('id', $sgg)->first();
                        $sarray_group = json_decode($sgg_group->add_ons);
                        foreach ($sarray_group as $sag) {
                            $saddon_detail = DB::table('add_ons')->where('id', $sag)->first();

                            $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('group_id', $sgg)->where('addon', $sag)->first();
                            if ($variant) {
                                $price = $variant->price;
                                $stock = $variant->stock;
                            } else {
                                $price = $saddon_detail->price;
                                $stock = 0;
                            }
                            $sgadd_ons[] = [
                                'id' => $sag,
                                'name' => $saddon_detail->name ? html_entity_decode($saddon_detail->name, ENT_QUOTES | ENT_XML1, 'UTF-8') : '',
                                'secondary_name' => $saddon_detail->secondary_name ? html_entity_decode($saddon_detail->secondary_name, ENT_QUOTES | ENT_XML1, 'UTF-8') : '',
                                'price' => $price,
                                'stock' => $stock

                            ];
                        }

                        $sadd_group[] = [
                            'id' => $sgg,
                            'name' => $sgg_group->name,
                            'min' => $sgg_group->min,
                            'max' => $sgg_group->max,
                            'allow_multiple' => $sgg_group->allow_multiple,
                            'mandatory' => $sgg_group->mandatory,
                            'group_addons' => $sgadd_ons,
                            'restaurant' => $each_food->restaurant_id
                        ];
                    }
                }
                $this_size[$size_key]->groups = $sadd_group;
                $saa_addons = [];
                if ($each_size->addons_id != null) {
                    $sa = $each_size->addons_id;
                    $sa_group = json_decode($sa);


                    foreach ($sa_group as $saa) {
                        $sa_detail = DB::table('add_ons')->where('id', $saa)->first();
                        $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('addon', (int)$saa)->first();
                        if ($variant) {
                            $price = $variant->price;
                            $stock = $variant->stock;
                        } else {
                            $price = $sa_detail->price;
                            $stock = 0;
                        }
                        $saa_addons[] = [
                            'id' => $saa,
                            'name' => $sa_detail->name ? html_entity_decode($sa_detail->name, ENT_QUOTES | ENT_XML1, 'UTF-8') : '',
                            'secondary_name' => $sa_detail->secondary_name ? html_entity_decode($sa_detail->secondary_name, ENT_QUOTES | ENT_XML1, 'UTF-8') : '',
                            'price' => $price,
                            'stock' => $stock
                        ];
                    }
                }
                $this_size[$size_key]->add_ons = $saa_addons;
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
                        $rcuisines[] = [
                            'name' => $r_cuisines->cuisine_name,
                            'secondary_name' => $r_cuisines->secondary_name
                        ];
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

            $layout =  DB::table('business_type')->where('id', $d->business_type)->first();
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


            $layout =  DB::table('business_type')->where('id', $d->business_type)->first();
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
                        $rcuisines[] = [
                            'name' => $r_cuisines->cuisine_name,
                            'secondary_name' => $r_cuisines->secondary_name
                        ];
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
                        'image' => File::exists(public_path('restaurant_uploads/') . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Logo/' . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image : BASE_URL . UPLOADS_PATH_LOGO . $d->image),

                        'discount'  => $d->discount,
                        'rating'    => (float) $rating_statistics['rating_percent'],
                        'is_open'   => $is_open,
                        'schedule_open' => $isOpen_schedule,     // 1- Open , 0 - Close
                        'cuisines'  => $rcuisines,
                        'travel_time' => $d->estimated_delivery_time,
                        'price'     => '',
                        'is_favourite' => $is_favourite,
                        'address' => $d->address,
                        'layout_id' =>  $layout->layout_id
                    ];
                }
            } else {
                $restaurant_list[] = [
                    'id'        => $d->id,
                    'name'      => $d->restaurant_name,
                    'image' => File::exists(public_path('restaurant_uploads/') . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Logo/' . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image : BASE_URL . UPLOADS_PATH_LOGO . $d->image),

                    'discount'  => $d->discount,
                    'rating'    => (float) $rating_statistics['rating_percent'],
                    'is_open'   => $is_open,
                    'schedule_open' => $isOpen_schedule,     // 1- Open , 0 - Close
                    'cuisines'  => $rcuisines,
                    'travel_time' => $d->estimated_delivery_time,
                    'price'     => '',
                    'is_favourite' => $is_favourite,
                    'address' => $d->address,
                    'layout_id' =>  $layout->layout_id ?? ''
                ];
            }
            $j++;
        }

        if ($restaurant_list || $return_food_list) {
            // if ($restaurant_list || $data_foodlist_return) {return_food_list
            $response_array = ['status' => true, 'restaurants' => $restaurant_list, 'foodlist' => $return_food_list];
            // $response_array = ['status'=>true, 'restaurants' => $restaurant_list, 'foodlist' => $data_foodlist_return];
        } else {
            $response_array = ['status' => false, 'message' => 'No Stores Found'];
        }

        return response()->json($response_array, 200);
    }

    public function get_brands(request $request)
    {
        $lat = $request->lat;
        $lag = $request->lag;

        $data = DB::table('brands')

            ->leftJoin('restaurants', 'restaurants.brand_name', '=', 'brands.id')
            ->where('brands.status', '=', 1)
            ->where('restaurants.brand_name', '!=', "")

            ->select('brands.id', 'brands.status', 'brands.name', 'restaurants.image', 'brands.rank', 'restaurants.lat', 'restaurants.lng')
            ->orderBy('brands.rank', 'ASC')->get();

        foreach ($data as $value) {
            $geofenceRadius = (float) ($value->GeofenceRadius ?? DEFAULT_RADIUS);
            $distance = vincentyGreatCircleDistance($lat, $lag, $value->lat, $value->lng);
            if ($distance <= $geofenceRadius) {
                $response_array[] = array(
                    'id' => $value->id,
                    'brand_name' => $value->name,
                    'status' => $value->status,
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
                    'image' => File::exists(public_path('restaurant_uploads/') . $value->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $value->image : (File::exists(public_path('restaurant_uploads/') . $value->id . '/' . 'Logo/' . $value->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $value->id . '/' . 'Logo/' . $value->image : BASE_URL . UPLOADS_PATH_LOGO . $value->image),

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
            $out_array = array('status' => false, 'message' => 'No Stores Found');
        }
        $response = response()->json($out_array, 200);
        return $response;
    }


    public function get_menu_category(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'category_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
            return response()->json($response_array, 200);
        }
        $foodlist = $this->foodlist;
        $category = $this->category;
        $menu = $this->menu;
        $category_id = $request->category_id;
        $food_filter = DB::table("food_list")->where('category_id', $category_id)->where('status', 1)->get();

        foreach ($food_filter as $ff) {
            $food_menu[] = $ff->menu_id;
        }
        $restIDs = array_values(array_unique($food_menu));

        $data = DB::table("menu")->whereIn('id', $restIDs)->orderBy('rank')->get();



        return response()->json([
            'status' => true,
            'menus' => $data
        ], 200);
    }
    public function get_menu_store(Request $request)
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
                    'menu_image' => $c->image,
                    'food_count' => $food_count,
                );
            }

            $response_array = array('status' => true, 'menus' => $menu_list);
        }



        $response_array = array('status' => true, 'data' => $response_array);
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_business_category(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'business_id' => 'required',
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $business_id = $request->business_id;
            $data = DB::table("business_category")->where('business_id', $business_id)->get();
        }


        $response_array = array('status' => true, 'category' => $data);
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_sub_category(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'business_category_id' => 'required',
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $business_category_id = $request->business_category_id;

            $data = DB::table("category")->where('business_category_id', $business_category_id)->get();
        }

        $response_array = array('status' => true, 'sub_category' => $data);
        $response = response()->json($response_array, 200);
        return $response;
    }
    public function get_sub_catetory_product(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'business_category_id' => 'required',
            )
        );


        $restaurants = $this->restaurants;
        $key_word = $request->key_word;
        $scheduleAt = $request->date . ' ' . $request->time;
        $source_lat = $request->lat;
        $source_lng = $request->lng;

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $cart = $this->cart;
        $check_for_cart = $cart::where('user_id', $user_id)->get();
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

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {

            $business_category_id = $request->business_category_id;
            $bus_id =  DB::table('business_category')->where('id', $business_category_id)->first();
            $layout =  DB::table('business_type')->where('id', $bus_id->business_id)->first();
            if ($request->category_id == 0) {
                if ($layout->layout_id == 2) {
                    $food_list = DB::table('food_list')
                        ->join('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
                        ->join('category', 'category.id', '=', 'food_list.category_id')
                        ->where('food_list.business_category_id', $business_category_id)
                        ->where('restaurants.status', 1)
                        ->where('food_list.menu_id', 0)
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
                            'category.category_name',
                            'food_list.restaurant_id',
                            'food_list.id as food_id'
                        )
                        ->orderby('restaurants.id', 'asc')
                        ->get();
                } else {
                    $food_list = DB::table('food_list')
                        ->join('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
                        ->join('category', 'category.id', '=', 'food_list.category_id')
                        ->where('food_list.business_category_id', $business_category_id)
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
                            'category.category_name',
                            'food_list.restaurant_id',
                            'food_list.id as food_id'
                        )
                        ->orderby('restaurants.id', 'asc')
                        ->get();
                }
            } else {

                $food_list = DB::table('food_list')
                    ->join('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
                    ->join('category', 'category.id', '=', 'food_list.category_id')
                    ->where('food_list.business_category_id', $business_category_id)
                    ->where('food_list.category_id', $request->category_id)
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
                        'category.category_name',
                        'food_list.restaurant_id',
                        'food_list.id as food_id'
                    )
                    ->orderby('restaurants.id', 'asc')
                    ->get();
            }

            foreach ($food_list as $key => $menu) {
                if ($menu->image == "") {
                    $food_list[$key]->image = "";
                } else {
                    $food_list[$key]->image = File::exists(public_path('product_image/') . $menu->image) ? UPLOADS_PATH_PRODUCT . $menu->image : (File::exists(public_path('restaurant_uploads/') . $menu->restaurant_id . '/' . 'Product/' . $menu->image) ? RESTAURANT_UPLOADS_PATH . $menu->restaurant_id . '/' . 'Product/' . $menu->image : (File::exists(public_path('common_images/') . $menu->image) ? UPLOADS_PATH_COMMON . $menu->image : $menu->image));
                }
            }

            foreach ($food_list as $key => $each_food) {
                $this_addon = DB::table('foodlist_addons')
                    ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                    ->where('foodlist_id', $each_food->id)
                    ->select('foodlist_addons.addons_id as id', 'add_ons.name', 'add_ons.restaurant_id',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at', 'foodlist_addons.addon_group')
                    ->get()->toArray();

                if (!$this_addon) {
                    $food_list[$key]->add_ons = [];
                    continue;
                }
                foreach ($this_addon as $addon_key => $each_addon) {

                    $this_addon[$addon_key]->id = (string) $this_addon[$addon_key]->id;
                    $this_addon[$addon_key]->restaurant_id = $each_addon->restaurant_id;
                    $this_addon[$addon_key]->pivot = ['foodlist_id' => $each_food->food_id, 'addons_id' => (string)$each_addon->id];
                }

                $food_list[$key]->add_ons = $this_addon;
            }

            foreach ($food_list as $key => $each_food) {
                $this_gaddon = DB::table('foodlist_addons')
                    ->where('foodlist_id', $each_food->food_id)
                    ->where('addons_id', null)
                    ->get();
                $add_group = [];
                foreach ($this_gaddon as $addon_key => $each_addon) {
                    $gadd_ons = [];
                    if ($each_addon->addon_group != null) {
                        $group = DB::table('addon_group')->where('id', $each_addon->addon_group)->first();

                        $array_group = json_decode($group->add_ons);

                        foreach ($array_group as $ag) {
                            $addon_detail = DB::table('add_ons')->where('id', $ag)->first();


                            $gadd_ons[] = [
                                'id' => (string)$ag,
                                'name' => $addon_detail->name,
                                'secondary_name' => $addon_detail->secondary_name,
                                'price' => $addon_detail->price

                            ];
                        }
                        $add_group[] = [
                            'id' => (string) $each_addon->addon_group,
                            'name' => $group->name,
                            'min' => $group->min,
                            'max' => $group->max,
                            'allow_multiple' => $group->allow_multiple,
                            'mandatory' => $group->mandatory,
                            'group_addons' => $gadd_ons,
                            'restaurant' => $each_food->restaurant_id
                        ];
                    }
                }
                $food_list[$key]->groups = $add_group;
            }


            foreach ($food_list as $key => $each_size) {

                $is_open = restaurant_is_open($each_size->restaurant_id);
                $food_list[$key]->is_open = $is_open;

                $delivery_charge = DB::table('delivery_charge_limits')
                    ->where('restaurant_id', $each_size->restaurant_id)
                    ->select('Minimum', 'Maximum', 'Value',  'Type')
                    ->get()->toArray();
                if (!$delivery_charge) {
                    $food_list[$key]->delivery_charge = [];
                    continue;
                }



                $food_list[$key]->restaurant_image = File::exists(public_path('restaurant_uploads/') . $each_size->restaurant_image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $each_size->restaurant_image : (File::exists(public_path('restaurant_uploads/') . $each_size->restaurant_id . '/' . 'Logo/' . $each_size->restaurant_image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $each_size->restaurant_id . '/' . 'Logo/' . $each_size->restaurant_image : BASE_URL . UPLOADS_PATH_LOGO . $each_size->restaurant_image);

                $food_list[$key]->delivery_type =   json_decode($each_size->delivery_type);
                $food_list[$key]->delivery_charge = $delivery_charge;
            }

            // foreach ($food_list as $key => $each_food) {

            //     $item_count = 0;
            //     if (count($check_for_cart) != 0) {
            //         foreach ($check_for_cart as $check_for_item) {
            //             if ($each_food->food_id == $check_for_item->food_id) {
            //                 $item_count = $check_for_item->quantity;
            //             }
            //         }
            //     }
            //     $food_list[$key]->item_count = $item_count;


            //     $this_size = DB::table('foodlist_foodquantity')
            //         ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
            //         ->where('foodlist_id', $each_food->id)
            //         ->select(
            //             'foodlist_foodquantity.foodquantity_id as id',
            //             'food_quantity.name',
            //             'food_quantity.secondary_name',
            //             'foodlist_foodquantity.price',
            //             'foodlist_foodquantity.is_default',
            //             'foodlist_foodquantity.created_at',
            //             'foodlist_foodquantity.updated_at'
            //         )
            //         ->get()->toArray();

            //     if (!$this_size) {
            //         $food_list[$key]->food_quantity = [];
            //         continue;
            //     }

            //     foreach ($this_size as $size_key => $each_size) {
            //         $this_size[$size_key]->pivot = [
            //             'foodlist_id' => $each_food->id,
            //             'foodquantity_id' => $each_size->id,
            //             'price' => $each_size->price,
            //             'is_default' => $each_size->is_default
            //         ];
            //     }

            //     $food_list[$key]->food_quantity = $this_size;
            // }
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
                        'foodlist_foodquantity.updated_at',
                        'foodlist_foodquantity.addons_id',
                        'foodlist_foodquantity.addon_group',
                    )
                    ->get()->toArray();

                if (!$this_size) {
                    $food_list[$key]->food_quantity = [];
                    continue;
                }
                foreach ($this_size as $size_key => $each_size) {
                    $sadd_group = [];

                    if ($each_size->addon_group != null) {
                        $sg = $each_size->addon_group;
                        $sg_group = json_decode($sg);



                        foreach ($sg_group as $sgg) {
                            $sgadd_ons = [];
                            $sgg_group = DB::table('addon_group')->where('id', $sgg)->first();

                            $sarray_group = json_decode($sgg_group->add_ons);
                            foreach ($sarray_group as $sag) {
                                $saddon_detail = DB::table('add_ons')->where('id', $sag)->first();
                                $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('group_id', $sgg)->where('addon', $sag)->first();
                                if ($variant) {
                                    $price = $variant->price;
                                    $stock = $variant->stock;
                                } else {
                                    $price = $saddon_detail->price;
                                    $stock = 0;
                                }

                                $sgadd_ons[] = [
                                    'id' => (string)$sag,
                                    'name' => $saddon_detail->name,
                                    'secondary_name' => $saddon_detail->secondary_name,
                                    'price' => $price,
                                    'stock' => $stock

                                ];
                            }
                            $sadd_group[] = [
                                'id' => (string)$sgg,
                                'name' => $sgg_group->name,
                                'min' => $sgg_group->min,
                                'max' => $sgg_group->max,
                                'allow_multiple' => $sgg_group->allow_multiple,
                                'mandatory' => $sgg_group->mandatory,
                                'group_addons' => $sgadd_ons

                            ];
                        }
                    }
                    $this_size[$size_key]->groups = $sadd_group;
                    $saa_addons = [];

                    if ($each_size->addons_id != null) {
                        $sa = $each_size->addons_id;
                        $sa_group = json_decode($sa);

                        foreach ($sa_group as $saa) {
                            $sa_detail = DB::table('add_ons')->where('id', $saa)->first();
                            $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('addon', (int)$saa)->first();
                            if ($variant) {
                                $price = $variant->price;
                                $stock = $variant->stock;
                            } else {
                                $price = $sa_detail->price;
                                $stock = 0;
                            }
                            $saa_addons[] = [
                                'id' => (string)$saa,
                                'name' => $sa_detail->name,
                                'secondary_name' => $sa_detail->secondary_name,
                                'price' => $price,
                                'stock' => $stock
                            ];
                        }
                    }
                    $this_size[$size_key]->add_ons = $saa_addons;

                    $this_size[$size_key]->pivot = [
                        'foodlist_id' => $each_food->food_id,
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

                $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d->id)->get();
                if (count($check_favourite) != 0) {
                    $is_favourite = 1;
                } else {
                    $is_favourite = 0;
                }
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

                            'items' => $each_menu,
                        ];
                    }
                } else {
                    $return_food_list[] = [

                        'items' => $each_menu,
                    ];
                }
            }




            if (count($food_list) != 0) {
                foreach ($food_list as $ff) {
                    $restaurant[] = $ff->restaurant_id;
                }
                $restIDs = array_values(array_unique($restaurant));

                $data = DB::table("restaurants")->whereIn('id', $restIDs)->get();
            } else {
                $data = [];
            }

            $j = 0;
            $restaurant_list = [];
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
                            'image' => File::exists(public_path('restaurant_uploads/') . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Logo/' . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image : BASE_URL . UPLOADS_PATH_LOGO . $d->image),
                            'banner' => File::exists(public_path('restaurant_uploads/') . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Banner/' . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Banner/' . $d->banner : BASE_URL . UPLOADS_PATH_BANNER . $d->banner),

                            'discount'  => $d->discount,
                            'rating'    => (float) $rating_statistics['rating_percent'],
                            'is_open'   => $is_open,
                            'schedule_open' => $isOpen_schedule,
                            'cuisines'  => $rcuisines,
                            'travel_time' => $d->estimated_delivery_time,
                            'price'     => '',
                            'address' => $d->address
                        ];
                    }
                } else {
                    $restaurant_list[] = [
                        'id'        => $d->id,
                        'name'      => $d->restaurant_name,
                        'image' => File::exists(public_path('restaurant_uploads/') . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Logo/' . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image : BASE_URL . UPLOADS_PATH_LOGO . $d->image),
                        'banner' => File::exists(public_path('restaurant_uploads/') . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Banner/' . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Banner/' . $d->banner : BASE_URL . UPLOADS_PATH_BANNER . $d->banner),

                        'discount'  => $d->discount,
                        'rating'    => (float) $rating_statistics['rating_percent'],
                        'is_open'   => $is_open,
                        'schedule_open' => $isOpen_schedule,
                        'cuisines'  => $rcuisines,
                        'travel_time' => $d->estimated_delivery_time,
                        'price'     => '',
                        'address' => $d->address
                    ];
                }
                $j++;
            }
        }

        $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'foodlist' => $return_food_list);
        $response = response()->json($response_array, 200);
        return $response;
    }
    public function get_sub_catetory_restaurant(Request $request)
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

            $restaurant_id = $request->restaurant_id;

            $food_filter = DB::table("food_list")->where('restaurant_id', $restaurant_id)->get();
            if (count($food_filter) != 0) {
                foreach ($food_filter as $ff) {
                    $category[] = $ff->category_id;
                }
                $restIDs = array_values(array_unique($category));

                $data = DB::table("category")->whereIn('id', $restIDs)->get();
            } else {
                $data = [];
            }
        }
        $response_array = array('status' => true, 'sub_category' => $data);
        $response = response()->json($response_array, 200);
        return $response;
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
        $source_lat = $request->lat;
        $source_lng = $request->lng;
        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $availableRestIDs = [];
            // $restaurant = $restaurants::where('business_type', $request->business_id)->first();
            $all_restaurant = $restaurants->where('status', 1)->where('business_type', $request->business_id)->get();
            foreach ($all_restaurant as $each_restaurant) {
                if (!empty($source_lat) && !empty($source_lng)) {
                    $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $each_restaurant->lat, $each_restaurant->lng);
                    if ($distance && $distance <= "100000") {
                        $availableRestIDs[] = $each_restaurant->id;
                    }
                } else {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            }



            $restIDs = array_values(array_unique($availableRestIDs));

            $data = $restaurants::whereIn('id', $restIDs)->orderBy('restaurant_name', 'asc')->get();


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



                $invoice = array(
                    'offer_discount' => $coupon_discount,
                    'restaurant_packaging_charge' => $restaurant_packaging_charge,
                    'DeliveryChargeBasedOn' => $restaurant->DeliveryChargeBasedOn,
                    'delivery_charge' => $delivery_charge,
                    'delivery_charge_type' => $delivery_charge_type,
                    'delivery_charge_limits' => $delivery_charge_limits,
                    'coupon_code' => $coupon_code,
                    'AdminCommission' => (int)$commission->value
                );
                $restaurant_detail[] = [
                    'restaurant_id' => $restaurant->id,
                    'name' => $restaurant->restaurant_name,
                    'image' => File::exists(public_path('restaurant_uploads/') . $restaurant->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant->image : (File::exists(public_path('restaurant_uploads/') . $restaurant->id . '/' . 'Logo/' . $restaurant->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant->id . '/' . 'Logo/' . $restaurant->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant->image),

                    'address' => $restaurant->address,
                    'is_open' => $restaurant->is_open,
                    'estimated_delivery_time' => $restaurant->estimated_delivery_time,
                    'is_tax_inclusive' => (int) env('IS_TAX_INCLUSIVE'),
                    'invoice' =>  $invoice
                ];
            }



            if (isset($request->user_id)) {
                $user = DB::table('users')->select('wallet_amount', 'name')->where('id', $request->user_id)->get();

                $response_array = array('status' => true, 'restaurants' => $restaurant_detail, 'user' =>  $user,);

                // $response_array = array('status' => true, 'restaurant_detail' => $restaurant_detail, 'invoice' => $invoice, 'user' => $user);
            } else {
                // $response_array = array('status' => true, 'restaurant_detail' => $restaurant_detail, 'invoice' => $invoice);


                $response_array = array('status' => true, 'restaurants' => $restaurant_detail);
            }
        }

        return response()->json($response_array, 200);
    }

    public function business_type(Request $request)
    {

        $data = DB::table('business_type')->where('status', 1)
            ->get();
        $response_array = [
            'status' => true,
            'business_list' => $data,
        ];
        return response()->json($response_array, 200);
    }
    public function city_list()
    {
        $city_list = DB::table('add_city')->get();
        $data = DB::table('cuisines')->get();
        $document_list = DB::table('document')->where('status', 1)->get();
        $response_array = [
            'status' => true,
            'city_list' => $city_list,
            'cuisines_list' => $data,
            'documents' => $document_list
        ];
        return response()->json($response_array, 200);
    }
    public function area_list(Request $request)
    {
        $area_list = DB::table('add_area')->where('add_city_id', $request->city)->get();
        $response_array = [
            'status' => true,
            'area_list' => $area_list,
        ];
        return response()->json($response_array, 200);
    }
    public function country_list(Request $request)
    {
        $country_list = DB::table('country')->get();
        $response_array = [
            'status' => true,
            'country_list' => $country_list,
        ];
        return response()->json($response_array, 200);
    }
    public function state_list(Request $request)
    {
        $state_list = DB::table('state')->where('country_id', $request->country_id)->get();
        $response_array = [
            'status' => true,
            'state_list' => $state_list,
        ];
        return response()->json($response_array, 200);
    }
    public function restaurant_signup(Request $request)
    {

        $rules = [
            'name' => 'required|max:50',
            'email' => 'required|unique:restaurants,email',
            'phone' => 'required|numeric|unique:restaurants,phone',
            'password' => 'required',

            'address' => 'required',
            'business_type' => 'required',


        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = [
                'status' => false,
                'message' => $error_messages

            ];
            return response()->json($response_array, 200);
        }


        $restaurants = $this->restaurants;
        $custom = $this->custom;
        $business_type = $request->business_type;
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $city = $request->city ?? null;
        $area = $request->area ?? null;
        $discount_type = '';
        $target_amount = '';
        $offer_amount = '';
        $admin_commision = '';


        $packaging_charge =  $request->packaging_charge;
        $shop_description = $request->shop_description ? $request->shop_description : '';

        $estimated_delivery_time = $request->estimated_delivery_time;
        $address = $request->address;

        $check_email_phone = $restaurants->where('email', $request->email)->orwhere('phone', $request->phone)->first();
        if ($check_email_phone) {
            $response_array = [
                'status' => false,
                'message' => "Email/Phone already exists"

            ];
            return response()->json($response_array, 200);
        }

        $restaurants->restaurant_name = $name;
        $restaurants->image = '';
        $restaurants->banner = $request->banner ?? '';

        $restaurants->email = $email;
        $restaurants->org_password = $request->password;
        $restaurants->password = Hash::make($request->password);
        $restaurants->email_confirmation = md5(time());
        $restaurants->phone = $phone;
        $restaurants->contact = $request->contact;
        $restaurants->city = $city;
        $restaurants->area = $area;
        $restaurants->discount_type = $discount_type;
        $restaurants->target_amount = $target_amount;
        $restaurants->offer_amount = $offer_amount;
        $restaurants->admin_commision = '0';
        $restaurants->restaurant_delivery_charge = '0';
        $restaurants->shop_description = $shop_description;
        $restaurants->is_open = 0;
        $restaurants->estimated_delivery_time = $estimated_delivery_time;
        $restaurants->packaging_charge = $packaging_charge;
        $restaurants->address = $address;
        $restaurants->lat = $request->latitude;
        $restaurants->lng = $request->longitude;
        $restaurants->business_type = $business_type;

        //Opening closing time
        $restaurants->opening_time_1 = ''; //$opening_time_1;
        $restaurants->closing_time_1 = ''; //$closing_time_1;

        $restaurants->opening_time_2 = ''; //$opening_time_2;
        $restaurants->closing_time_2 = ''; //$closing_time_2;

        $restaurants->opening_time_3 = ''; //$opening_time_3;
        $restaurants->closing_time_3 = ''; //$closing_time_3;

        $restaurants->opening_time_4 = ''; //$opening_time_4;
        $restaurants->closing_time_4 = ''; //$closing_time_4;

        $restaurants->opening_time_5 = ''; //$opening_time_5;
        $restaurants->closing_time_5 = ''; //$closing_time_5;

        $restaurants->opening_time_6 = ''; //$opening_time_6;
        $restaurants->closing_time_6 = ''; //$closing_time_6;

        $restaurants->opening_time_7 = ''; //$opening_time_7;
        $restaurants->closing_time_7 = ''; //$closing_time_7;
        // -- Opening closing time

        $restaurants->status = '0';
        $restaurants->delivery_type = $request->delivery_type;
        $restaurants->save();

        $cuisines = $this->cuisines->find($request->cuisines);
        $restaurants->Cuisines()->sync($cuisines);

        $restaurant_new = $restaurants->where('email', $request->email)->first();
        $restaurants_det = $restaurants->find($restaurant_new->id);

        // foreach($request->document as $doc){

        //     $imageName = $custom->move_uploaded_file($doc[1]);
        //     $expiry_date = '';

        //             if (isset($doc[2])) {
        //                 $expiry_date = date("Y-m-d", strtotime($doc[2]));
        //             }

        //     $insert = DB::table('restaurants_document')->insert([
        //         'document_id'=>$doc[0],
        //         'restaurants_id'=>$restaurants_det->id,
        //         'document'=>$imageName,
        //         'expiry_date'=>$expiry_date
        //     ]);
        // }


        $restaurant_new = $restaurants->where('email', $request->email)->first();
        $neworder = DB::table('mail_ids')->where("mail_for", "rest_signup")->first();
        $emails = json_decode($neworder->mail_ids);
        $template = DB::table('mail_contents')->where("content_for", "rest_signup")->get();
        $cont = [];
        foreach ($template as $temp) {
            $cont[] = $temp->content;
        }
        $logo = DB::table('settings')->where('key_word', "site_logo")->first();
        $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
        $url = BASE_URL . "/admin/new";
        $html = view('emails.restaurant.restaurant-welcome')->with(['restaurant' => $restaurant_new, 'template' => $cont, 'logo' => $to_logo, 'url' => $url]);


        for ($i = 0; $i < count($emails); $i++) {
            $Mailer             = new Mailer();
            $Mailer->to         = $emails[$i];
            $Mailer->toName     = $restaurant_new->restaurant_name;
            $Mailer->subject    = "Welcome to" . APP_NAME;
            $Mailer->body       = $html;

            $Mailer->send();
        }

        $response_array = [
            'status' => true,
            'message' => "Success"

        ];
        return response()->json($response_array, 200);
    }
    public function driver_signup(Request $request)
    {
        //echo $request;
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            // 'license' => 'required',
            'password' => 'required'
        ]);


        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());
            $response_array = [
                'status' => false,
                'message' => $error_messages

            ];
            return response()->json($response_array, 200);
        } else {
            $deliverypartners = $this->deliverypartners;
            $custom = $this->custom;
            $name = $request->name;
            $phone = $request->phone;
            $email = $request->email;
            $country = $request->country;
            $state_province = $request->state;
            $city = $request->city;
            $address = $request->address;
            $password = $this->encrypt_password($request->password);
            $org_password = $request->password;
            $zipcode = $request->zipcode;
            $authToken = "";
            $license = "";



            $data = array();

            $data[] = array(
                'partner_id' => $this->generate_partner_id(),
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'password' => $password,
                'org_password' => $org_password,
                'address' => $address,
                'license' => $license,
                'status' => 3
                //'country'=>$country,
                //'state_province'=>$state_province,
            );

            $deliverypartners->insert($data);
            $id =  DB::table('delivery_partners')->orderBy('id', 'desc')->first();
            $id->id;
            if ($id->id) {
                $delivery_detail = $this->driver_partner_details;
                $data1[] = array(
                    'delivery_partners_id' => $id->id,
                    'city' => $city,
                    'state_province' => $state_province,
                    'country' => $country,
                    'zip_code' => $zipcode,
                    'address_line_1' => $address
                );

                $delivery_detail->insert($data1);
                if ($request->license) {
                    $licenses = $request->license;
                    $exp_date = $request->license_expiry;
                    $document_id = $request->doc_id;
                    $expdate_id = $request->exp_id;
                    for ($i = 0; $i < count($licenses); $i++) {
                        $dri_doc = new DriverDocument();
                        $license = $custom->base_image_upload_license($licenses[$i]); //dd($license);
                        $dri_doc->driver_id =  $id->id;
                        $dri_doc->document_id = $document_id[$i];
                        $dri_doc->document = $license;
                        //dd($dri_doc);
                        $dri_doc->save();
                        for ($e = 0; $e < count($exp_date); $e++) {
                            if ($document_id[$i] ==  $expdate_id[$e]) {
                                //dd($exp_date[$e]);
                                $doc = DB::table('driver_document')->where('driver_id', $id->id)->where('document_id', $document_id[$i])
                                    ->update(['expiry_date' => $exp_date[$e]]);
                            }
                        }
                    }
                }
                // if($delivery_detail)
                // {

                //     $driver_new = $deliverypartners->where('email', $request->email)->first(); echo  $driver_new ;
                //     $html = view('emails.deliverpartner.driver-welcome')->with(['driver' => $driver_new]);

                //     $Mailer = new Mailer();
                //     $Mailer->to         = $driver_new->email;
                //     $Mailer->toName     = $driver_new->name;
                //     $Mailer->subject     = "Welcome";
                //     $Mailer->body       = $html;
                //     $Mailer->send();
                // }
            }
        }

        $response_array = [
            'status' => true,
            'message' => "Success"

        ];
        return response()->json($response_array, 200);
    }

    public function get_revise_orderdetails(Request $request)
    {
        $order_id = $request->order_id;
        $order = DB::table('requests')->where('id', $order_id)->first();
        // $order_items = DB::table('request_detail')->where('request_id',$order_id)->where('status',-1)->get();

        $order_items = DB::table('request_detail')->where('request_id', $order_id)->where('request_detail.status', -1)
            ->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
            ->select('request_detail.id as detail_id', 'request_detail.status as detail_status', 'request_detail.*', 'food_list.*')
            ->get();
        $item_list = [];
        if ($order_items) {
            foreach ($order_items as $food_detail) {
                $img = File::exists(public_path('product_image/') . $food_detail->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $food_detail->image : (File::exists(public_path('restaurant_uploads/') . $food_detail->id . '/' . 'Product/' . $food_detail->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $food_detail->id . '/' . 'Product/' . $food_detail->image : (File::exists(public_path('common_images/') . $food_detail->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $food_detail->image : BASE_URL  . $food_detail->image));
                $adds = DB::table('requestdetail_addons')->where('requestdetail_id', $food_detail->detail_id)->get();
                $to_display = [];
                if ($adds) {
                    foreach ($adds as $ad) {

                        $to_display[] = $ad->name . " x " . $ad->quantity;
                    }
                }

                $string_version = implode(',', $to_display);
                $size = DB::table('food_quantity')->where('id', $food_detail->food_quantity)->first();
                if ($size) {
                    $sel_size = $size->name;
                } else {
                    $sel_size = '';
                }
                $item_list[] = [
                    'id' => $food_detail->id,
                    'detail_status' => $food_detail->detail_status,
                    'Name' => $food_detail->name,
                    'description' => $food_detail->description,
                    'quantity' => $food_detail->quantity,
                    'price' => $food_detail->food_quantity_price,
                    'image' => $img,
                    'add_ons' => $string_version,
                    'size' => $sel_size

                ];
            }
        }
        $restaurant = DB::table('restaurants')->where('id', $order->restaurant_id)->first();
        $business = DB::table('business_type')->where('id', $restaurant->business_type)->first();
        $layout = DB::table('layout')->where('id', $business->layout_id)->first();
        $total_count = DB::table('request_detail')->where('request_id', $order_id)->count();
        $response_array = [
            'status' => true,
            'message' => "Success",
            'items' => $item_list,
            'restaurant_id' => $order->restaurant_id,
            'layout_id' => $layout->id,
            'is_cod' => $order->is_cod,
            'total_items_count' => $total_count

        ];
        return response()->json($response_array, 200);
    }

    public function continue_order(Request $request)
    {
        $order_id = $request->order_id;
        $order_details = DB::table('requests')->where('id', $order_id)->first();
        $bill_amount = $order_details->bill_amount;
        $order_items = DB::table('request_detail')->where('request_id', $order_id)->where('status', -1)->get();
        $unavailable = 0;
        if ($order_items) {
            foreach ($order_items as $ot) {
                $unavailable = $unavailable + $ot->food_quantity_price;
            }
        }
        $balance = $bill_amount - $unavailable;
        $status_entry = [];

        $status_entry[] = [
            'user_id' => $order_details->user_id,
            'amount' => $balance,
            'topup_code' => '',
            'type' => 1, //1-credit 2-debit
            'status' => 1,

        ];
        DB::table('user_wallet_history')->insert($status_entry);

        $this->users->find($order_details->user_id)->increment('wallet_amount', $balance);
        $title = "Order Revised";
        $message = "Your wallet has been topped up with" . $balance;
        $user = DB::table('users')->where('id', $order_details->user_id)->first();
        sendNotification([$user->device_token], $title, $message);


        $unavailableit = DB::table('request_detail')->where('request_id', $order_id)->where('status', -1)->get();

        $unavailable_price = 0;
        if (count($unavailableit)) {
            foreach ($unavailableit as $unavail) {
                $unavailable_price = $unavailable_price + $unavail->food_quantity_price;

                $unavail_adds = DB::table('requestdetail_addons')->where('requestdetail_id', $unavail->id)->get();
                if (count($unavail_adds)) {
                    foreach ($unavail_adds as $un_adds) {
                        $unavailable_price = $unavailable_price + $un_adds->price;
                    }
                }
            }
        }
        $item_total = 0;
        $fr_it = DB::table('request_detail')->where('request_id', $order_id)->where('status', 1)->get();


        if (count($fr_it)) {
            foreach ($fr_it as $tt) {
                $item_total = $item_total + $tt->food_quantity_price;

                $it_adds = DB::table('requestdetail_addons')->where('requestdetail_id', $tt->id)->get();
                if (count($it_adds)) {
                    foreach ($it_adds as $as) {
                        $unavailable_price = $item_total + $as->price;
                    }
                }
            }
        }


        DB::table('requests')->where('id', $order_id)->update([
            'item_total' => $item_total
        ]);
        $update_order = DB::table('requests')->where('id', $order_id)->update([
            'status' => 1
        ]);
        $response_array = [
            'status' => true,
            'message' => "Success",


        ];
        return response()->json($response_array, 200);
    }



    public function get_revise_orderitems(Request $request)
    {
        $order_id = $request->order_id;


        $order_items = DB::table('request_detail')->where('request_id', $order_id)
            ->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
            ->select('request_detail.id as detail_id', 'request_detail.status as detail_status', 'request_detail.*', 'food_list.*')
            ->get();
        $item_list = [];
        if ($order_items) {
            foreach ($order_items as $food_detail) {
                $img = File::exists(public_path('product_image/') . $food_detail->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $food_detail->image : (File::exists(public_path('restaurant_uploads/') . $food_detail->id . '/' . 'Product/' . $food_detail->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $food_detail->id . '/' . 'Product/' . $food_detail->image : (File::exists(public_path('common_images/') . $food_detail->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $food_detail->image : BASE_URL  . $food_detail->image));
                $adds = DB::table('requestdetail_addons')->where('requestdetail_id', $food_detail->detail_id)->get();
                $to_display = [];
                if ($adds) {
                    foreach ($adds as $ad) {

                        $to_display[] = $ad->name . " x " . $ad->quantity;
                    }
                }

                $string_version = implode(',', $to_display);
                $size = DB::table('food_quantity')->where('id', $food_detail->food_quantity)->first();
                if ($size) {
                    $sel_size = $size->name;
                } else {
                    $sel_size = '';
                }
                $item_list[] = [
                    'id' => $food_detail->id,
                    'detail_status' => $food_detail->detail_status,
                    'Name' => $food_detail->name,
                    'description' => $food_detail->description,
                    'quantity' => $food_detail->quantity,
                    'price' => $food_detail->food_quantity_price,
                    'image' => $img,
                    'add_ons' => $string_version,
                    'size' => $sel_size

                ];
            }
        }

        $order_details = DB::table('requests')->where('id', $order_id)->first();
        $user = DB::table('users')->where('id', $order_details->user_id)->first();
        $unavailable = DB::table('request_detail')->where('request_id', $order_id)->where('status', -1)->get();

        $unavailable_price = 0;
        if (count($unavailable)) {
            foreach ($unavailable as $unavail) {
                $unavailable_price = $unavailable_price + $unavail->food_quantity_price;

                $unavail_adds = DB::table('requestdetail_addons')->where('requestdetail_id', $unavail->id)->get();
                if (count($unavail_adds)) {
                    foreach ($unavail_adds as $un_adds) {
                        $unavailable_price = $unavailable_price + $un_adds->price;
                    }
                }
            }
        }
        $return_order_details[] = [
            'order_id' => $order_details->id,
            'item_total' => $order_details->item_total,
            'offer_discount' => $order_details->offer_discount,
            'tax' => $order_details->tax,
            'delivery_charge' => $order_details->delivery_charge,
            'wallet_amount' => $order_details->wallet_amount,
            'bill_amount' => $order_details->bill_amount,
            'packaging_charge' => $order_details->restaurant_packaging_charge,
            'user_wallet_amount' => $user->wallet_amount,
            'unavailable_price' => $unavailable_price

        ];

        $response_array = [
            'status' => true,
            'message' => "Success",
            'items' => $item_list,
            'order_details' => $return_order_details,

        ];
        return response()->json($response_array, 200);
    }

    public function revise_order(Request $request)
    {
        $order_id = $request->order_id;

        $order_details = DB::table('requests')->where('id', $order_id)->first();
        if ($order_details->status != -3) {
            $response_array = [
                'status' => true,
                'message' => "Order Cannot be revised at this state",
            ];
            return response()->json($response_array, 200);
        }

        $unavailable = DB::table('request_detail')->where('request_id', $order_id)->where('status', -1)->get();

        $unavailable_price = 0;
        if (count($unavailable)) {
            foreach ($unavailable as $unavail) {
                $unavailable_price = $unavailable_price + $unavail->food_quantity_price;

                $unavail_adds = DB::table('requestdetail_addons')->where('requestdetail_id', $unavail->id)->get();
                if (count($unavail_adds)) {
                    foreach ($unavail_adds as $un_adds) {
                        $unavailable_price = $unavailable_price + $un_adds->price;
                    }
                }
            }
        }

        $typecastedItems = [];
        if (is_array($request->Items)) {
            foreach ($request->Items as $i => $eachItem) {
                if (empty($eachItem)) {
                    return new ReturnData(false, "Item #$i is empty");
                }

                if (!is_array($eachItem)) {
                    return new ReturnData(false, "Item #$i is not an array");
                }

                $eachItemKeys = array_keys($eachItem);
                $diffKeys = array_diff(['FoodID', 'SizeID', 'AddonIDs', 'Quantity', 'addons_quantity'], $eachItemKeys);
                if ($diffKeys) {
                    return new ReturnData(false, "Item #$i expecting following values: " . cimplode($diffKeys));
                }

                if (!is_array($eachItem['AddonIDs'])) {
                    return new ReturnData(false, "Item #$i expecting AddonIDs to be an array");
                }

                $newItem = new \StdClass;
                $newItem->FoodID = (int) $eachItem['FoodID'];
                $newItem->SizeID = $eachItem['SizeID'] ? ((int) $eachItem['SizeID']) : null;
                $newItem->AddonIDs = (array) $eachItem['AddonIDs'];
                $newItem->Quantity = (int) $eachItem['Quantity'];
                $newItem->addons_quantity = (array) $eachItem['addons_quantity'];

                $typecastedItems[] = $newItem;
            }
        }

        $request->Items = $typecastedItems;

        $items = $request->Items;
        $f_price = 0;
        foreach ($items as $item) {
            $foodPrice = DB::table('food_list')->select('price')->where('id', $item->FoodID)->first();
            $sizePrice      = DB::table('foodlist_foodquantity')->select('price')->where('foodlist_id', $item->FoodID)->where('foodquantity_id', $item->SizeID)->first();

            if ($sizePrice) {
                $sprice = $sizePrice->price;
            } else {
                $sprice = 0;
            }
            $rows = DB::table('add_ons')->whereIn('id', $item->AddonIDs)->get()->toArray();

            $addonsPrice =  array_sum(array_column($rows, 'price'));

            $eachPrice = $sprice > 0 ? $sprice : $foodPrice->price;

            $eachPrice += $addonsPrice;


            $itemTotal = $eachPrice * $item->Quantity;
            $f_price = $f_price + $itemTotal;

            $ins = DB::table('request_detail')->insertGetId([
                'request_id' => $order_id,
                'restaurant_id' => $order_details->restaurant_id,
                'food_id' => $item->FoodID,
                'quantity' => $item->Quantity,
                'food_quantity' => $item->SizeID,
                'food_quantity_price' => $itemTotal
            ]);

            $item->ItemTotal = $itemTotal;
            $addonsDetails = [];

            foreach ($item->AddonIDs as $addonID) {

                $row = DB::table('add_ons')->where('id', $addonID)->first();

                $addonsDetails[] = $row;
            }
            for ($i = 0; $i < count($addonsDetails); $i++) {
                $addonsDetails[$i]->quantity = $item->addons_quantity[$i];
            }
            $item->AddonsDetails = $addonsDetails;

            foreach ($item->AddonsDetails as $addonKey => $eachAddon) {
                $ins2 =  DB::table('requestdetail_addons')->insert([
                    'requestdetail_id' => $ins,
                    'addons_id' => $eachAddon->id,
                    'name' => $eachAddon->name,
                    'price' => $eachAddon->price,
                    'quantity' => $eachAddon->quantity
                ]);
                $f_price = $f_price + $eachAddon->price;
            }
        }
        $old_bill = $order_details->bill_amount;

        $new = $order_details->bill_amount + $f_price - $unavailable_price;
        DB::table('requests')->where('id', $order_id)->update([
            'bill_amount' => $new,
        ]);
        $item_total = 0;
        $fr_it = DB::table('request_detail')->where('request_id', $order_id)->where('status', 1)->get();


        if (count($fr_it)) {
            foreach ($fr_it as $tt) {
                $item_total = $item_total + $tt->food_quantity_price;

                $it_adds = DB::table('requestdetail_addons')->where('requestdetail_id', $tt->id)->get();
                if (count($it_adds)) {
                    foreach ($it_adds as $as) {
                        $unavailable_price = $item_total + $as->price;
                    }
                }
            }
        }


        DB::table('requests')->where('id', $order_id)->update([
            'item_total' => $item_total
        ]);
        if ($order_details->is_cod == 0) {
            if ($old_bill > $new) {
                $amount = $old_bill - $new;

                $status_entry = array();

                $status_entry[] = array(
                    'user_id' => $order_details->user_id,
                    'amount' => $amount,
                    'order_id' => $order_id,
                    'topup_code' => '',
                    'type' => 1, //1-credit 2-debit
                    'status' => 1
                );

                DB::table('user_wallet_history')->insert($status_entry);

                $this->users->find($order_details->user_id)->increment('wallet_amount', $amount);
                $user_noti = DB::table('users')->where('id', $order_details->user_id)->first();
                $title = "Wallet";
                $message = 'Your wallet is topped up with Rs.' . $amount;
                sendNotification([$user_noti->device_token], $title, $message, ['amount' => $amount]);
                $response_array = [
                    'status' => true,
                    'OrderID' => $order_id,
                    'message' => 'Order reviced successfully',
                    'PaymentID' => '',
                    'ClientSecret' => ''
                ];
            } else {
                $amount = $new - $old_bill;
                $pg = DB::table('settings')->where('key_word', 'PAYMENT_GATEWAY')->first();


                if ($pg->value == 0) {
                } elseif ($pg->value == 1) {
                    $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
                    $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
                    $version = DB::table('settings')->where('key_word', 'stripe_version')->first();
                    $default_currency = DB::table('country')->where('is_default', 1)->first();
                    $stripe = new Stripe($sk->value, $version->value);

                    $paymentIntent = $stripe->paymentIntents()->create([
                        'setup_future_usage' => 'off_session',
                        'amount' => $amount,
                        'currency' => $default_currency->currency_code,
                        'automatic_payment_methods' => [
                            'enabled' => 'true',
                        ],
                        'capture_method' => 'manual',
                    ]);
                }





                $response_array = [
                    'status' => true,
                    'OrderID' => $order_id,
                    'message' => 'Order reviced successfully',
                    'PaymentID' => $paymentIntent['id'],
                    'ClientSecret' => $paymentIntent['client_secret']
                ];
            }
        } else {
            DB::table('requests')->where('id', $order_id)->update([
                'status' => 1,
            ]);
            $response_array = [
                'status' => true,
                'OrderID' => $order_id,
                'message' => 'Order reviced successfully',
                'PaymentID' => '',
                'ClientSecret' => ''
            ];
        }

        return response()->json($response_array, 200);
    }

    public function out_of_stock(Request $request)
    {
        $order_id = $request->order_id;

        $items = $request->items;

        $update_order_status = DB::table('requests')->where('id', $order_id)
            ->update([
                'status' => -3,
                'is_revised' => 1
            ]);
        foreach ($items as $item) {

            $update_orderstock = DB::table('request_detail')->where('request_id', $order_id)->where('food_id', $item)
                ->update([
                    'status' => -1
                ]);
            $update_foodstock = DB::table('food_list')->where('id', $item)
                ->update([
                    'out_of_stock' => 1
                ]);
        }
        $ord = DB::table('requests')->where('id', $order_id)->first();
        $user_noti = DB::table('users')->where('id', $ord->user_id)->first();
        $title = "Items Out of Stock";
        $message = 'Some Items in your Order is not Available Please change the Items';
        sendNotification([$user_noti->device_token], $title, $message, ['order_id' => $order_id]);

        $message = "Items Marked as Out of Stock";
        $status = true;
        $response_array = [
            'status' => true,
            'OrderID' => $order_id,
            'message' => $message,

        ];

        return $response_array;
    }

    public function get_cate_store(Request $request)
    {


        $restaurant_id = $request->restaurant_id;

        $foodlist = $this->foodlist;
        $category = $this->category;
        $menu = $this->menu;

        $category_list = $foodlist->where('restaurant_id', $restaurant_id)
            ->groupBy('business_category_id')
            ->pluck('business_category_id');

        $data = DB::table('business_category')->whereIn('id', $category_list)->where('image', '<>', '')->get();

        if ($data && count($data) != 0) {

            $food_category = array();
            $i = 1;
            foreach ($data as $d) {
                $food_category[] = [
                    'category_id'   => $d->id,
                    'name'          => $d->category_name ? $d->category_name : '',
                    'secondary_name' => $d->secondary_categoryname ? $$d->secondary_categoryname : '',
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


    public function get_sub_catetory_store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            array(
                'restaurant_id' => 'required',
                'category_id' => 'required',
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {

            $restaurant_id = $request->restaurant_id;
            $category_id = $request->category_id;
            if ($category_id == 0) {
                $food_filter = DB::table("food_list")->where('restaurant_id', $restaurant_id)->get();
            } else {
                $food_filter = DB::table("food_list")->where('restaurant_id', $restaurant_id)->where('business_category_id', $category_id)->get();
            }

            if (count($food_filter) != 0) {
                foreach ($food_filter as $ff) {
                    $category[] = $ff->category_id;
                }
                $restIDs = array_values(array_unique($category));

                $data = DB::table("category")->whereIn('id', $restIDs)->get();
                foreach ($data as $d) {
                    $d->category_name = $d->category_name ? $d->category_name : '';
                    $d->category_secondaryname = $d->category_secondaryname ?  $d->category_secondaryname : '';
                }
            } else {
                $data = [];
            }
            foreach ($data as $d) {
                if ($d->business_category_id == null) {
                    $d->business_category_id = '';
                }
            }
        }
        $response_array = array('status' => true, 'sub_category' => $data);
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function callcustomer(Request $request)
    {
        $this->call_customer();
    }

    public function get_food_list_by_menu_store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'restaurant_id' => 'required',
                'business_category_id' => 'required',
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
        $business_res = DB::table('restaurants')->where('id', $restaurant_id)->first();
        $business = DB::table('business_type')->where('id', $business_res->business_type)->first();
        $layout = DB::table('layout')->where('id', $business->layout_id)->first();

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

        if ($layout->id == 2) {
            if ($request->business_category_id == 0) {
                if ($request->category_id == 0) {

                    $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                        ->where('food_list.status', 1)

                        ->select('food_list.id as food_id', 'food_list.restaurant_id', 'name', 'secondary_name', 'price', 'sprice', 'price_schedule', 'p_from', 'p_to', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax',  'food_list.image as image', 'food_list.stock as stock', 'food_list.current_stock as current_stock', 'food_list.out_of_stock as out_of_stock')
                        //->where('food_list.name', 'BBQ Chicken Wings')
                        ->orderBy('image', 'desc')
                        ->get();
                } else {

                    $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                        ->where('food_list.category_id', $request->category_id)

                        ->where('food_list.status', 1)

                        ->select('food_list.id as food_id', 'food_list.restaurant_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'food_list.image as image', 'food_list.stock as stock', 'food_list.current_stock as current_stock', 'food_list.out_of_stock as out_of_stock')
                        //->where('food_list.name', 'BBQ Chicken Wings')
                        ->orderBy('image', 'desc')
                        ->get();
                }
            } else {

                if ($request->category_id == 0) {

                    $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                        ->where('food_list.status', 1)
                        ->where('food_list.business_category_id', $request->business_category_id)


                        ->select('food_list.id as food_id', 'food_list.restaurant_id', 'name', 'secondary_name', 'price', 'sprice', 'price_schedule', 'p_from', 'p_to', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax',  'food_list.image as image', 'food_list.stock as stock', 'food_list.current_stock as current_stock', 'food_list.out_of_stock as out_of_stock')
                        //->where('food_list.name', 'BBQ Chicken Wings')
                        ->orderBy('image', 'desc')
                        ->get();
                } else {

                    $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                        ->where('food_list.category_id', $request->category_id)
                        ->where('food_list.business_category_id', $request->business_category_id)

                        ->where('food_list.status', 1)

                        ->select('food_list.id as food_id', 'food_list.restaurant_id', 'name', 'secondary_name', 'price', 'sprice', 'price_schedule', 'p_from', 'p_to', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'food_list.image as image', 'food_list.stock as stock', 'food_list.current_stock as current_stock', 'food_list.out_of_stock as out_of_stock')
                        //->where('food_list.name', 'BBQ Chicken Wings')
                        ->orderBy('image', 'desc')
                        ->get();
                }
            }
        } else {
            if ($request->category_id == 0) {

                $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                    ->where('food_list.status', 1)
                    ->where('food_list.business_category_id', $request->business_category_id)
                    ->join(
                        'menu',
                        function ($join) {
                            $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                            $join->on('menu.id', '=', 'food_list.menu_id');
                        }
                    )
                    ->select('food_list.id as food_id', 'food_list.restaurant_id', 'name', 'secondary_name', 'price', 'sprice', 'price_schedule', 'p_from', 'p_to', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image', 'food_list.stock as stock', 'food_list.current_stock as current_stock', 'food_list.out_of_stock as out_of_stock')
                    //->where('food_list.name', 'BBQ Chicken Wings')
                    ->orderBy('rank')->orderBy('image', 'desc')
                    ->get();
            } else {

                $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                    ->where('food_list.category_id', $request->category_id)
                    ->where('food_list.business_category_id', $request->business_category_id)
                    ->where('food_list.status', 1)
                    ->join(
                        'menu',
                        function ($join) {
                            $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                            $join->on('menu.id', '=', 'food_list.menu_id');
                        }
                    )
                    ->select('food_list.id as food_id', 'food_list.restaurant_id', 'name', 'secondary_name', 'price', 'sprice', 'price_schedule', 'p_from', 'p_to', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image', 'food_list.stock as stock', 'food_list.current_stock as current_stock', 'food_list.out_of_stock as out_of_stock')
                    //->where('food_list.name', 'BBQ Chicken Wings')
                    ->orderBy('rank')->orderBy('image', 'desc')
                    ->get();
            }
        }

        $sale_date = 0;
        foreach ($food_list as $key => $each_food) {
            if ($each_food->price_schedule == 1) {
                $startDate = Carbon::parse($each_food->p_from);
                $endDate = Carbon::parse($each_food->p_to);
                $dateToCheck = Carbon::parse(Carbon::now()->toDateString());

                if ($dateToCheck->between($startDate, $endDate)) {
                    $sale_date = 1;
                } else {
                    $sale_date = 0;
                }
            } else {
                $sale_date = 0;
            }
            $food_list[$key]->sale_date = $sale_date;
        }
        foreach ($food_list as $key => $each_food) {
            if ($each_food->sale_date == 1) {
                $dummy = $each_food->price;
                $dummy2 = $each_food->sprice;

                $food_list[$key]->sprice = $dummy;
                $food_list[$key]->price = $dummy2;
            }
        }
        foreach ($food_list as $key => $menu) {
            if ($menu->image == "") {
                $food_list[$key]->image = "";
            } else {
                $food_list[$key]->image = File::exists(public_path('product_image/') . $menu->image) ? UPLOADS_PATH_PRODUCT . $menu->image : (File::exists(public_path('restaurant_uploads/') . $menu->restaurant_id . '/' . 'Product/' . $menu->image) ? RESTAURANT_UPLOADS_PATH . $menu->restaurant_id . '/' . 'Product/' . $menu->image : (File::exists(public_path('common_images/') . $menu->image) ? UPLOADS_PATH_COMMON . $menu->image : $menu->image));
            }
            $food_list[$key]->name = $food_list[$key]->name;
            $food_list[$key]->description = $food_list[$key]->description;
            $food_list[$key]->secondary_name = $food_list[$key]->secondary_name;
            $food_list[$key]->secondary_description = $food_list[$key]->secondary_description;
        }

        foreach ($food_list as $key => $each_food) {
            $this_addon = DB::table('foodlist_addons')
                ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select('foodlist_addons.addons_id as id', 'add_ons.name',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at', 'foodlist_addons.addon_group')
                ->get()->toArray();

            if (!$this_addon) {
                $food_list[$key]->add_ons = [];
                continue;
            }


            foreach ($this_addon as $addon_key => $each_addon) {

                $this_addon[$addon_key]->id = (string) $this_addon[$addon_key]->id;
                $this_addon[$addon_key]->restaurant_id = $restaurant_id;
                $this_addon[$addon_key]->pivot = ['foodlist_id' => $each_food->food_id, 'addons_id' =>  (string)$each_addon->id];
            }
            $food_list[$key]->add_ons = $this_addon;
        }

        foreach ($food_list as $key => $each_food) {
            $this_gaddon = DB::table('foodlist_addons')
                ->where('foodlist_id', $each_food->food_id)
                ->where('addons_id', null)
                ->get();
            $add_group = [];
            foreach ($this_gaddon as $addon_key => $each_addon) {
                $gadd_ons = [];
                if ($each_addon->addon_group != null) {
                    $group = DB::table('addon_group')->where('id', $each_addon->addon_group)->first();

                    $array_group = json_decode($group->add_ons);

                    foreach ($array_group as $ag) {
                        $addon_detail = DB::table('add_ons')->where('id', $ag)->first();


                        $gadd_ons[] = [
                            'id' =>  (string) $ag,
                            'name' => $addon_detail->name ? $addon_detail->name : '',
                            'secondary_name' => $addon_detail->secondary_name ? $addon_detail->secondary_name : '',
                            'price' => $addon_detail->price

                        ];
                    }
                    $add_group[] = [
                        'id' =>  (string) $each_addon->addon_group,
                        'name' => $group->name,
                        'min' => $group->min,
                        'max' => $group->max,
                        'allow_multiple' => $group->allow_multiple,
                        'mandatory' => $group->mandatory,
                        'group_addons' => $gadd_ons,
                        'restaurant' => $restaurant_id
                    ];
                }
            }
            $food_list[$key]->groups = $add_group;
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
                    'foodlist_foodquantity.updated_at',
                    'foodlist_foodquantity.addons_id',
                    'foodlist_foodquantity.addon_group',
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }
            foreach ($this_size as $size_key => $each_size) {
                $sadd_group = [];

                if ($each_size->addon_group != null) {
                    $sg = $each_size->addon_group;
                    $sg_group = json_decode($sg);



                    foreach ($sg_group as $sgg) {
                        $sgadd_ons = [];
                        $sgg_group = DB::table('addon_group')->where('id', $sgg)->first();

                        $sarray_group = json_decode($sgg_group->add_ons);
                        foreach ($sarray_group as $sag) {
                            $saddon_detail = DB::table('add_ons')->where('id', $sag)->first();
                            $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('group_id', $sgg)->where('addon', $sag)->first();
                            if ($variant) {
                                $price = $variant->price;
                                $stock = $variant->stock;
                            } else {
                                $price = $saddon_detail->price;
                                $stock = 0;
                            }

                            $sgadd_ons[] = [
                                'id' =>  (string) $sag,
                                'name' => $saddon_detail->name ? $saddon_detail->name : '',
                                'secondary_name' => $saddon_detail->secondary_name ? $saddon_detail->secondary_name : '',
                                'price' => $price,
                                'stock' => $stock

                            ];
                        }
                        $sadd_group[] = [
                            'id' =>  (string) $sgg,
                            'name' => $sgg_group->name,
                            'min' => $sgg_group->min,
                            'max' => $sgg_group->max,
                            'allow_multiple' => $sgg_group->allow_multiple,
                            'mandatory' => $sgg_group->mandatory,
                            'group_addons' => $sgadd_ons

                        ];
                    }
                }
                $this_size[$size_key]->groups = $sadd_group;
                $saa_addons = [];

                if ($each_size->addons_id != null) {
                    $sa = $each_size->addons_id;
                    $sa_group = json_decode($sa);

                    foreach ($sa_group as $saa) {
                        $sa_detail = DB::table('add_ons')->where('id', $saa)->first();
                        $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('addon', (int)$saa)->first();
                        if ($variant) {
                            $price = $variant->price;
                            $stock = $variant->stock;
                        } else {
                            $price = $sa_detail->price;
                            $stock = 0;
                        }
                        $saa_addons[] = [
                            'id' =>  (string) $saa,
                            'name' => $sa_detail->name ? $sa_detail->name : '',
                            'secondary_name' => $sa_detail->secondary_name ? $sa_detail->secondary_name : '',
                            'price' => $price,
                            'stock' => $stock
                        ];
                    }
                }
                $this_size[$size_key]->add_ons = $saa_addons;

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

            if ($layout->id == 2) {
                $new_food_list[$each_food->category_id][] = $each_food;
            } else {
                if ($is_veg == 1 && $each_food->is_veg == 0) {
                    continue;
                }
                $new_food_list[$each_food->menu_id][] = $each_food;
            }
        }

        $return_food_list = [];
        foreach ($new_food_list as $key => $each_menu) {
            // checking menu avaible now

            $available = 0;
            if ($layout->id == 2) {
                $return_food_list[] = [
                    'items' => $each_menu,
                ];
            } else {
                $menutime = DB::table('menu_timing')->where('menu_id', $each_menu[0]->menu_id)->get();
                if (sizeof($menutime)) {

                    foreach ($menutime as $key => $time) {
                        $times = Carbon::now();
                        $current_time = $times->toTimeString();
                        $date1 = $current_time;
                        $date2 = $time->from_time;
                        $date3 = $time->to_time;
                        if ($date1 > $date2 && $date1 < $date3) {
                            $available = 1;
                        }
                    }
                } else {
                    $available = 1; //latest feature so some menu dont assume time so set is available
                }
                $return_food_list[] = [
                    'menu_id' => $each_menu[0]->menu_id,
                    'menu_available' => $available,
                    'menu_name' => $each_menu[0]->menu_name ? $each_menu[0]->menu_name : '',
                    'secondary_name' => $each_menu[0]->secondary_menu_name ? $each_menu[0]->secondary_menu_name : '',
                    'items' => $each_menu,
                ];
            }
        }

        //print_r($return_food_list);
        $restaurant_detail = $restaurants::where('id', $restaurant_id)->select('id', 'restaurant_name', 'image', 'address')->first();

        $restaurant_detail->image = File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image);

        if (!$restaurant_detail) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Store not found'];
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

    public function get_home_segments(Request $request)
    {
        $source_lat = $request->lat;
        $source_lng = $request->lng;
        $business_id = 1;

        $nearbyRestaurantIDs = Restaurant::nearDistance_business_rest($source_lat, $source_lng, $business_id);
        $restaurants = $this->restaurants;
        $data = $restaurants::where('id', $nearbyRestaurantIDs[0])->first();
        $is_open = restaurant_is_open($data->id);
        $rating_statistics = get_rating_statistics($nearbyRestaurantIDs[0]);
        $delivery_charge_limits = DB::table('delivery_charge_limits')->select('Minimum', 'Maximum', 'Value', 'Type')
            ->where('restaurant_id', $data->id)->get()->toArray();
        $tax1 = DB::table('settings')->where('key_word', 'tax_inclusive')->first();
        $tax = $tax1->value;

        $restaurant_list = array(
            'id'            => $data->id,
            'name' =>  $this->secondLanguage_user($data->restaurant_name, $data->restaurant_secondary_name),
            'phone' => $data->phone,
            'email' => $data->email,
            'delivery_type'          => json_decode($data->delivery_type),
            'address'       => $data->address,
            'lat'     => $data->lat,
            'lng'     => $data->lng,
            'discount'      => $data->discount,
            'rating'        => (float) $rating_statistics['rating_percent'],
            'is_open'       => $is_open,
            'travel_time'   => $data->estimated_delivery_time,
            'opening_time_1' => $data->opening_time_1,
            'closing_time_1' => $data->closing_time_1,
            'opening_second_time_1' => $data->opening_second_time_1,
            'closing_second_time_1' => $data->closing_second_time_1,
            'opening_time_2' => $data->opening_time_2,
            'closing_time_2' => $data->closing_time_2,
            'opening_second_time_2' => $data->opening_second_time_2,
            'closing_second_time_2' => $data->closing_second_time_2,
            'opening_time_3' => $data->opening_time_3,
            'closing_time_3' => $data->closing_time_3,
            'opening_second_time_3' => $data->opening_second_time_3,
            'closing_second_time_3' => $data->closing_second_time_3,
            'opening_time_4' => $data->opening_time_4,
            'closing_time_4' => $data->closing_time_4,
            'opening_second_time_4' => $data->opening_second_time_4,
            'closing_second_time_4' => $data->closing_second_time_4,
            'opening_time_5' => $data->opening_time_5,
            'closing_time_5' => $data->closing_time_5,
            'opening_second_time_5' => $data->opening_second_time_5,
            'closing_second_time_5' => $data->closing_second_time_5,
            'opening_time_6' => $data->opening_time_6,
            'closing_time_6' => $data->closing_time_6,
            'opening_second_time_6' => $data->opening_second_time_6,
            'closing_second_time_6' => $data->closing_second_time_6,
            'opening_time_7' => $data->opening_time_7,
            'closing_time_7' => $data->closing_time_7,
            'opening_second_time_7' => $data->opening_second_time_7,
            'closing_second_time_7' => $data->closing_second_time_7,
            'delivery_charge_limits' => json_encode($delivery_charge_limits),
            'packaging_charge' => $data->packaging_charge,
            'DeliveryChargeBasedOn' => $data->DeliveryChargeBasedOn,
            'offer_discount' => $data->offer_discount,
            'delivery_charge_type' => $data->delivery_charge_type ? $data->delivery_charge_type : 1,
            'tax' => $tax,


        );
        $featured = featured($nearbyRestaurantIDs[0]);
        $recent = recent($nearbyRestaurantIDs[0]);

        $segments[] = [
            'title' => "Featured",
            'products' => $featured
        ];
        $segments[] = [
            'title' => "Recent",
            'products' => $recent
        ];
        $response_array = [
            'status' => true,
            'segments' => $segments,
            // 'recent' => $recent,
            'restaurant_details' => $restaurant_list

        ];
        return response()->json($response_array, 200);
    }

    public function get_product_details(Request $request)
    {
        $product_id = $request->id;
        $product = product_details($product_id);
        $restaurant = $product->restaurant_id;
        $rest_details = DB::table('restaurants')->where('id', $restaurant)->first();
        $specs = DB::table('product_specification')->where('product_id', $product_id)->get();
        $faq = DB::table('product_faq')->where('product_id', $product_id)->get();
        $tables = DB::table('table_type')->where('product_id', $product_id)->get();
        $table = [];
        foreach ($tables as $td) {
            $rows = DB::table('product_specification')->where('table_type', $td->id)->get();
            $items = [];
            foreach ($rows as $rw) {
                $items[] = [
                    'label' => $rw->label,
                    'value' => $rw->value
                ];
            }
            $table[] = [
                'name' => $td->name,
                'items' => $items
            ];
        }
        $response_array = [
            'status' => true,
            'product' => $product,
            'restaurant' => $rest_details,
            'faq' => $faq,
            'tables' => $table,
            'is_flyer_available' => 0


        ];
        return response()->json($response_array, 200);
    }
    public function fetch_category_list(Request $request)
    {
        $categories = DB::table('business_category')->where('status', 1)->get();
        $cats = [];
        foreach ($categories as $cat) {
            $cat_img = null;
            if ($cat->image) {
                $cat_img = BASE_URL . $cat->image;
            }
            $cats[] = [
                'id' => $cat->id,
                'name' => $cat->category_name,
                'image' => $cat_img,
            ];
        }
        $response_array = [
            'status' => true,
            'categories' => $cats,

        ];
        return response()->json($response_array, 200);
    }


    public function get_home_categories(Request $request)
    {


        $categories = DB::table('business_category')->where('status', 1)->get();
        $cats = [];

        foreach ($categories as $cat) {

            $subs = DB::table('category')->where('business_category_id', $cat->id)->where('status', 1)->get();

            $subs_array = [];

            foreach ($subs as $ss) {
                $img = null;
                if ($ss->image) {
                    $img = BASE_URL . $ss->image;
                }
                $subs_array[] = [
                    'id' => $ss->id,
                    'name' => $ss->category_name,
                    'rank' => $ss->rank,
                    'image' => $img,
                    'status' => $ss->status,

                ];
            }
            $cat_img = null;
            if ($cat->image) {
                $cat_img = BASE_URL . $cat->image;
            }
            $cats[] = [
                'id' => $cat->id,
                'name' => $cat->category_name,
                'image' => $cat_img,
                'sub_categories' => $subs_array
            ];
        }

        $response_array = [
            'status' => true,
            'categories' => $cats,

        ];
        return response()->json($response_array, 200);
    }

    public function validate_pincode(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $gapi = DB::table('settings')->where('key_word', 'google_api_key')->first();
        $ad = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true&key=$gapi->value";
        $json = file_get_contents($ad);
        $details = json_decode($json, TRUE);
        $rest = DB::table('restaurants')->first();
        $delivery_areas = $rest->delivery_states;
        $sts = [];
        foreach (json_decode($delivery_areas) as $ar) {
            $state = DB::table('state')->where('id', $ar)->first();
            $sts[] = $state->state;
        }
        $addr_comp = $details['results'][1]['address_components'];
        $state = "";
        $is_deliverable = false;
        foreach ($addr_comp as $ac) {
            $types_array = $ac['types'];
            foreach ($types_array as $key) {
                if ($key == "administrative_area_level_1") {
                    $state = $ac['long_name'];
                    if (in_array($state, $sts)) {
                        $is_deliverable = true;
                    }
                }
            }
        }
        $response_array = [
            'status' => true,
            'is_deliverable' => $is_deliverable,
        ];
        return response()->json($response_array, 200);
    }
    public function get_segment(Request $request)
    {

        $source_lat = $request->lat;
        $source_lng = $request->lng;
        $business_id = 1;

        $nearbyRestaurantIDs = Restaurant::nearDistance_business_rest($source_lat, $source_lng, $business_id);

        $recent = recent($nearbyRestaurantIDs[0]);


        $segments[] = [
            'title' => "Recent",
            'products' => $recent
        ];
        $response_array = [
            'status' => true,
            'segments' => $segments,

        ];
        return response()->json($response_array, 200);
    }
    public function get_subcategory_products(Request $request)
    {
        $id = $request->id;
        $business_id = $request->business_id;

        $source_lat = $request->lat;
        $source_lng = $request->lng;
        $data = DB::table('category')->where('id', $id)->first();

        $nearbyRestaurantIDs = Restaurant::nearDistance_business_rest($source_lat, $source_lng, $business_id);

        $restaurants = $this->restaurants;


        $cat_products = get_subcat_products($nearbyRestaurantIDs[0], $id);


        $response_array = [
            'status' => true,
            'products' => $cat_products,


        ];
        return response()->json($response_array, 200);
    }

    public function search_restaurants_new(Request $request)
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

        $key_word = $request->key_word;
        $source_lat = $request->lat;
        $source_lng = $request->lng;

        $business_id = 1;

        $nearbyRestaurantIDs = Restaurant::nearDistance_business_rest($source_lat, $source_lng, $business_id);
        $restaurants = $this->restaurants;
        $data = $restaurants::where('id', $nearbyRestaurantIDs[0])->first();
        $is_open = restaurant_is_open($data->id);
        $rating_statistics = get_rating_statistics($nearbyRestaurantIDs[0]);
        $delivery_charge_limits = DB::table('delivery_charge_limits')->select('Minimum', 'Maximum', 'Value', 'Type')
            ->where('restaurant_id', $data->id)->get()->toArray();
        $tax1 = DB::table('settings')->where('key_word', 'tax_inclusive')->first();
        $tax = $tax1->value;

        $restaurant_list = array(
            'id'            => $data->id,
            'name' =>  $this->secondLanguage_user($data->restaurant_name, $data->restaurant_secondary_name),
            'phone' => $data->phone,
            'email' => $data->email,
            'delivery_type'          => json_decode($data->delivery_type),
            'address'       => $data->address,
            'lat'     => $data->lat,
            'lng'     => $data->lng,
            'discount'      => $data->discount,
            'rating'        => (float) $rating_statistics['rating_percent'],
            'is_open'       => $is_open,
            'travel_time'   => $data->estimated_delivery_time,
            'opening_time_1' => $data->opening_time_1,
            'closing_time_1' => $data->closing_time_1,
            'opening_second_time_1' => $data->opening_second_time_1,
            'closing_second_time_1' => $data->closing_second_time_1,
            'opening_time_2' => $data->opening_time_2,
            'closing_time_2' => $data->closing_time_2,
            'opening_second_time_2' => $data->opening_second_time_2,
            'closing_second_time_2' => $data->closing_second_time_2,
            'opening_time_3' => $data->opening_time_3,
            'closing_time_3' => $data->closing_time_3,
            'opening_second_time_3' => $data->opening_second_time_3,
            'closing_second_time_3' => $data->closing_second_time_3,
            'opening_time_4' => $data->opening_time_4,
            'closing_time_4' => $data->closing_time_4,
            'opening_second_time_4' => $data->opening_second_time_4,
            'closing_second_time_4' => $data->closing_second_time_4,
            'opening_time_5' => $data->opening_time_5,
            'closing_time_5' => $data->closing_time_5,
            'opening_second_time_5' => $data->opening_second_time_5,
            'closing_second_time_5' => $data->closing_second_time_5,
            'opening_time_6' => $data->opening_time_6,
            'closing_time_6' => $data->closing_time_6,
            'opening_second_time_6' => $data->opening_second_time_6,
            'closing_second_time_6' => $data->closing_second_time_6,
            'opening_time_7' => $data->opening_time_7,
            'closing_time_7' => $data->closing_time_7,
            'opening_second_time_7' => $data->opening_second_time_7,
            'closing_second_time_7' => $data->closing_second_time_7,
            'delivery_charge_limits' => json_encode($delivery_charge_limits),
            'packaging_charge' => $data->packaging_charge,
            'DeliveryChargeBasedOn' => $data->DeliveryChargeBasedOn,
            'offer_discount' => $data->offer_discount,
            'delivery_charge_type' => $data->delivery_charge_type ? $data->delivery_charge_type : 1,
            'tax' => $tax,


        );
        // $product = product_details($product_id);

        $food_list = DB::table('food_list')
            ->where('food_list.status', 1)
            ->where('name', 'like', '%' . $key_word . '%')
            ->where('restaurant_id', $nearbyRestaurantIDs[0])
            ->get();
        $segments = [];
        foreach ($food_list as $fl) {
            $product = product_details($fl->id);
            $segments[] = $product;
        }


        $response_array = [
            'status' => true,
            'product' => $segments,
            'restaurant_details' => $restaurant_list

        ];
        return response()->json($response_array, 200);
    }
    public function enquire_now(Request $request)
    {
        $user_id = $request->user_id;
        // $products = explode(',',  $request->food_id);


        $products = $request->food_id;
        $quantity = $request->food_qty;


        // $quantity = explode(',',  $request->food_qty);

        $lat = $request->lat;
        $lng = $request->lng;




        $rest = DB::table('restaurants')->first();
        $insert = DB::table('enquiry')->insertGetId([
            'user_id' => $user_id,
            'lat' => $lat,
            'lng' => $lng,
            'products' => implode(',', $products),
            'quantity' => implode(',', $quantity),
            'created_at' => Carbon::now()
        ]);

        $mail_enable = DB::table('settings')->where('key_Word', 'email_enable')->first();
        if ($mail_enable->value == 1) {
            $user = DB::table('users')->where('id', $user_id)->first();
            $rt_food = [];
            $i = 0;
            foreach ($products as $fl) {
                $prod = DB::table('food_list')->where('id', $fl)->first();
                $rt_food[] = [
                    'Name' => $prod->name,
                    'description' => $prod->description,
                    'quantity' => $quantity[$i],
                ];
                $i++;
            }

            $url = BASE_URL . "/admin/new";

            $template = DB::table('mail_contents')->where("content_for", "enquirymail")->get();
            $cont = [];
            foreach ($template as $temp) {
                $cont[] = $temp->content;
            }
            $logo = DB::table('settings')->where('key_word', "site_logo")->first();
            $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
            $html = view('emails.user.enquiry')->with(['user' => $user, 'food' => $rt_food, 'content' => $cont, 'logo' => $to_logo, 'url' => $url]);
            $Mailer = new Mailer();
            $Mailer->to         = $rest->email;
            $Mailer->toName     = $rest->restaurant_name;
            $Mailer->subject     = "Welcome";
            $Mailer->body       = $html;
            $Mailer->send();
        }

        $response_array = [
            'status' => true,


        ];
        return response()->json($response_array, 200);
    }
    public function get_schedule_slots(Request $request)
    {
        $restaurant_id = (int) $request->restaurant_id;
        $restaurant = $this->restaurants->where('id', $restaurant_id)->first();

        if (!$restaurant) {
            return response()->json(['status' => false, 'message' => 'Restaurant not found'], 404);
        }

        $holidays = array_map('intval', explode(',', $restaurant->holidays));
        $interval = (int) $this->settings->where("key_word", "schedule_interval")->value('value');
        $start = Carbon::now();
        $end = $start->copy()->addDays(7);
        $dates = collect();

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $dates->push($d->format('d-m-Y'));
        }

        $weekMap = [
            'Sunday' => 7,
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
        ];

        $date_day = $dates->map(function ($dat) use ($holidays, $restaurant, $weekMap, $interval) {
            $dayOfWeek = Carbon::createFromFormat('d-m-Y', $dat)->format('l');
            $weekday = $weekMap[$dayOfWeek];
            $isHoliday = in_array($weekday, $holidays);

            $data = [];
            if (!$isHoliday) {
                $data = array_merge(
                    $this->generateTimeSlots($restaurant, $weekday, 'opening_time_', 'closing_time_', $interval),
                    $this->generateTimeSlots($restaurant, $weekday, 'opening_second_time_', 'closing_second_time_', $interval)
                );

                $data2 = array_merge(
                    $this->generateTimeSlots_24($restaurant, $weekday, 'opening_time_', 'closing_time_', $interval),
                    $this->generateTimeSlots_24($restaurant, $weekday, 'opening_second_time_', 'closing_second_time_', $interval)
                );

                $data = array_map(function ($slot) {
                    preg_match('/(\d{1,2}):(\d{2}) (am|pm) - (\d{1,2}):(\d{2}) (am|pm)/i', $slot, $matches);
                    if (!empty($matches)) {
                        return "{$matches[1]}:{$matches[2]} - {$matches[4]}:{$matches[5]} {$matches[6]}";
                    }
                    return $slot;
                }, $data);
            }


            return [
                'date' => $dat,
                'day' => $dayOfWeek,
                'is_holiday' => $isHoliday,
                'slots' => $data,
                'converted_slots' => $data2
            ];
        });

        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $date_day
        ], 200);
    }


    private function generateTimeSlots($restaurant, $weekday, $openKeyPrefix, $closeKeyPrefix, $interval)
    {
        $startTime = Carbon::parse($restaurant->{$openKeyPrefix . $weekday});
        if ($weekday === Carbon::now()->dayOfWeekIso) {
            $now = Carbon::now();
            if ($startTime->lt($now)) {
                $startTime = $now->copy()->ceilMinutes($interval);
            }
        }
        $endTime = Carbon::parse($restaurant->{$closeKeyPrefix . $weekday});
        $slots = [];

        while ($startTime->lt($endTime)) {
            $to = $startTime->copy()->addMinutes($interval);
            $slots[] = $startTime->format('g:i A') . ' - ' . $to->format('g:i A');
            $startTime = $to;
        }

        return $slots;
    }

    private function generateTimeSlots_24($restaurant, $weekday, $openKeyPrefix, $closeKeyPrefix, $interval)
    {
        $startTime = Carbon::parse($restaurant->{$openKeyPrefix . $weekday});
        if ($weekday === Carbon::now()->dayOfWeekIso) {
            $now = Carbon::now();
            if ($startTime->lt($now)) {
                $startTime = $now->copy()->ceilMinutes($interval);
            }
        }
        $endTime = Carbon::parse($restaurant->{$closeKeyPrefix . $weekday});
        $slots = [];

        while ($startTime->lt($endTime)) {
            $to = $startTime->copy()->addMinutes($interval);
            $slots[] = $startTime->format('H:i') . ' - ' . $to->format('H:i');
            $startTime = $to;
        }

        return $slots;
    }

    public function check_menu(Request $request)
    {
        $food_id =  $request->food_id;
        $date = $request->date;
        $timer = $request->time;
        $current_time = substr($timer, 0, 5);
        $today = Carbon::parse($date)->dayOfWeek;
        $return_food_list = [];
        foreach ($food_id as $food) {
            $return_food_list[] = check_menu_time($food, $current_time, $today);
        }
        $response_array = [
            'status' => true,
            'return_food_list' => $return_food_list
        ];
        return response()->json($response_array, 200);
    }
}
