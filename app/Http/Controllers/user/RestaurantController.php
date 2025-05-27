<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\web\BaseController;
use App\Model\Requestdetail;
use App\Model\RequestdetailAddons;
use App\Http\Controllers\Mailer;
use App\Http\Controllers\PaymentGateway\EasyCheckout;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Validator;
use Mollie\Laravel\Facades\Mollie;
use Cartalyst\Stripe\Stripe;
use SebastianBergmann\Environment\Console;
use \stdClass;

class RestaurantController extends BaseController
{

    public function single_restaurant(Request $request)
    {

        $schedule = session()->get('schedule_date'); //dd($schedule);
        $restaurant_id = $request->id;
        $veg_only = 1;
        $restaurants = $this->restaurants;
        $foodlist = $this->foodlist;
        $menu = $this->menu;
        $cart = $this->cart;

        $user = Auth::guard('user')->user();
        if ($user == null) {
            $ses_user = 0;
        } else {
            $ses_user = 1;
        }
        $user_id = $user ? $user->id : 0;

        if ($request->table) {
            $request->session()->put('table_code', $request->table);
        } else {
            $request->session()->put('table_code', '');
        }

        $rest = $restaurants::where('restaurants.id', $restaurant_id)->first();
        if (empty($rest)) {
            return redirect('user.home');
        }
        //update stock item on new day
        stockRevice($dt = Carbon::now());

        $cuisines = DB::table('restaurant_cuisines')->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
            ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
            ->select('cuisines.name as cuisine_name')
            ->where('restaurants.id', $restaurant_id)
            ->get()->pluck('cuisine_name')->toArray();

        $last_3_reviews = DB::table('order_ratings')
            ->join('requests', 'requests.id', '=', 'order_ratings.request_id')
            ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('restaurants.id', $restaurant_id)
            ->select(
                'restaurant_rating',
                'restaurant_feedback',
                'order_ratings.updated_at as reviewd_at',
                'users.name as user_name',
                'users.profile_image as user_image'
            )
            ->orderBy('order_ratings.id', 'desc')
            ->limit(50)
            ->get()->toArray();
        $ratings = DB::table('order_ratings')
            ->join('requests', 'requests.id', '=', 'order_ratings.request_id')
            ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
            ->where('restaurants.id', $restaurant_id)
            ->select(
                DB::raw('SUM(1) as total_raters'),
                DB::raw('SUM(restaurant_rating) as total_ratings')
            )->first();
        $rating_statistics = get_rating_statistics($restaurant_id);
        // dd($rating_statistics);

        // $ratings->total_raters = $ratings->total_raters === null ? 1 : $ratings->total_raters;
        // $ratings->total_ratings = $ratings->total_ratings === null ? 1 : $ratings->total_ratings;

        $restaurant = $restaurants::where('restaurants.id', $restaurant_id)
            // ->leftJoin('favourite_list', 'favourite_list.restaurant_id', '=', 'restaurants.id')
            ->select(
                '*',
                'restaurants.id as restaurant_id',
                DB::raw('
                    IF( EXISTS(
                            SELECT *
                            FROM favourite_list
                            WHERE `user_id` =' . $user_id . ' AND `restaurant_id` = ' . $restaurant_id . '
                    ) , 1, 0) as is_favourite
                ')
            )
            ->first();

        $restaurant->is_open = restaurant_is_open($restaurant_id);



        $near_by_restaurants = get_nearby_restaurants($restaurant->lat, $restaurant->lng, [$restaurant_id]);


        $food = $foodlist::where('food_list.restaurant_id', $restaurant_id)
            ->where('food_list.status', 1)
            ->join('menu', function ($join) {
                $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                $join->on('menu.id', '=', 'food_list.menu_id');
            })
            ->select('food_list.id as food_id', 'name', 'price', 'description', 'secondary_name', 'secondary_description', 'is_veg', 'tax', 'menu.id', 'menu_name', 'food_list.image as image', 'out_of_stock')
            ->orderBy('rank')->orderBy('food_list.id')
            ->get();

        $food = $this->updateSecondaryLang($food);
        $foodlist_addons = [];
        foreach ($food as $each_food) {
            $this_addon = DB::table('foodlist_addons')
                ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                ->where('foodlist_id', $each_food->food_id)->select('foodlist_addons.addons_id', 'add_ons.name', 'add_ons.secondary_name',  'add_ons.price')->get()->toArray();

            if (!$this_addon) {
                continue;
            }

            $foodlist_addons[] = ['food_id' => $each_food->food_id, 'addons' => $this_addon];
        }

        $foodlist_addons = ['foodlist_addons' => $foodlist_addons];
        $foodlist_addons = $this->updateSecondaryLangAddon($foodlist_addons);

        $foodlist_sizes = [];
        foreach ($food as $each_food) {
            $this_size = DB::table('foodlist_foodquantity')
                ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select('food_quantity.name', 'food_quantity.secondary_name', 'foodlist_foodquantity.foodquantity_id', 'foodlist_foodquantity.price', 'foodlist_foodquantity.size_description', 'foodlist_foodquantity.is_default')
                ->get()->toArray();

            if (!$this_size) {
                continue;
            }

            $foodlist_sizes[] = ['food_id' => $each_food->food_id, 'sizes' => $this_size];
        }
        $foodlist_sizes = ['foodlist_sizes' => $foodlist_sizes];
        $foodlist_sizes = $this->updateSecondaryLangSizes($foodlist_sizes);

        $cart = $cart::where('user_id', $user_id)->get();

        $amount = 0;
        $quantity = 0;
        foreach ($cart as $key) {

            $food_detail = $foodlist::where('id', $key->food_id)->first();
            $amount = $amount + ($food_detail->price * $key->quantity);
            $quantity = $quantity + $key->quantity;
        }
        $food_cart[] = array(
            'amount' => $amount,
            'quantity' => $quantity,
        );

        $promocode_list_restaurant = [];
        $dt = Carbon::now();
        $promocode_list_restaurant = $this->promocode->where('restaurant_id', $restaurant_id)->where('status', '1')->whereRaw('"' . $dt . '" between `available_from` and `valid_till`')->get();
        // $promocode_list_restaurant = ['promocode_list_restaurant'=>$promocode_list_restaurant];
        //dd($food);
        $delivery_address = $this->deliveryaddress;

        $addresses = $delivery_address->where('user_id', $user_id)->get();
        if ($request->table) {
            $near_by_restaurants = [];
            return view(
                'user.restaurant-dining',
                compact(
                    'restaurant',
                    'cart',
                    'food',
                    'foodlist_addons',
                    'foodlist_sizes',
                    'food_cart',
                    'cuisines',
                    'last_3_reviews',
                    'ratings',
                    'near_by_restaurants',
                    'rating_statistics',
                    'promocode_list_restaurant',
                    'ses_user'
                )
            );
        }

        return view(
            'user.restaurant',
            compact(
                'restaurant',
                'cart',
                'food',
                'foodlist_addons',
                'foodlist_sizes',
                'food_cart',
                'cuisines',
                'last_3_reviews',
                'ratings',
                'near_by_restaurants',
                'rating_statistics',
                'promocode_list_restaurant',
                'ses_user',
                'addresses'
            )
        );
    }


    public function cart(Request $request)
    {

        $veg_only = 1;
        $restaurants = $this->restaurants;
        $foodlist = $this->foodlist;
        $menu = $this->menu;
        $cart = $this->cart;
        $user = Auth::guard('user')->user();
        $user_id = $user ? $user->id : 0;


        $cart = $cart::where('user_id', $user_id)->get();
        $amount = 0;
        $quantity = 0;
        foreach ($cart as $key) {
            $food_detail = $foodlist::where('id', $key->food_id)->first();
            $amount = $amount + ($food_detail->price * $key->quantity);
            $quantity = $quantity + $key->quantity;
        }
        $food_cart[] = array(
            'amount' => $amount,
            'quantity' => $quantity,
        );


        return view(
            'user.cart',
            compact(
                'cart',
                'food_cart'
            )
        );
    }

    public function add_to_cart(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'food_id' => 'required',
                'quantity' => 'required',
                'restaurant_id' => 'required',
                'force_insert' => 'required', // To Overwrite previous restaurant cart if exist - 1
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
                if (count($check) != 0) {
                    $cart::where('id', $check->id)->update(['quantity' => $quantity]);
                } else {
                    $last_data = $cart::where('user_id', $user_id)->first();
                    if (count($last_data) != 0) {
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
                        'quantity' => $quantity,
                    );
                    $cart::insert($insert_data);
                }
            } else {
                $cart::where('user_id', $user_id)->delete();
                $insert_data = array();
                $insert_data[] = array(
                    'user_id' => $user_id,
                    'food_id' => $food_id,
                    'quantity' => $quantity,
                );
                $cart::insert($insert_data);
            }
            $response_array = array('status' => true, 'message' => 'Item quantity added to cart');
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
                'quantity' => 'required',
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
            if (count($check) != 0 && $quantity != 0) {
                $cart::where('id', $check->id)->update(['quantity' => $quantity]);
            } elseif (count($check) != 0 && $quantity == 0) {

                $cart::where('id', $check->id)->delete();
            }
            $response_array = array('status' => true, 'message' => 'Item quantity removed from cart');
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
                'price' => $key->quantity * $food_detail->price,
            );

            $restaurant_id = $food_detail->restaurant_id;
            $order_on = $key->created_at;
        }

        if (isset($restaurant_id)) {
            $restaurant_detail = $restaurants::where('id', $restaurant_id)->first();
            $restaurant_name = $restaurant_detail->restaurant_name;
            $restaurant_image = $restaurant_detail->image;
            $order_on = $order_on;
            $restaurant_address = $restaurant_detail->address;
        } else {
            $restaurant_name = "";
            $restaurant_image = "";
            $order_on = "";
            $restaurant_address = "";
        }

        $food_cart[] = array(
            'item_list' => $item_list,
            'amount' => $amount,
            'quantity' => $quantity,
            'restaurant_name' => $restaurant_name,
            'restaurant_image' => $restaurant_image,
            'order_on' => $order_on,
            'restaurant_address' => $restaurant_address,
        );

        $response_array = array('status' => true, 'cart' => $food_cart);

        // }


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

        $data = $category->whereIn('id', $category_list)->get();

        if (count($data) != 0) {

            $food_category = array();
            $i = 1;
            foreach ($data as $d) {
                $food_category[] = array(
                    'category_id' => $d->id,
                    'name' => $d->category_name,
                    'position' => $i,
                );

                $i = $i + 1;
            }

            $response_array = array('status' => true, 'category' => $food_category);
        } else {
            $response_array = array('status' => false, 'message' => 'No Data Found');
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_food_list(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'restaurant_id' => 'required',
                'is_veg' => 'required',
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
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
            //dd($food_list);
            $check_for_cart = $cart::where('user_id', $user_id)->get();

            $get_food_list = array();

            foreach ($category_list as $key) {
                $category_wise_food = array();

                foreach ($food_list as $foods) {
                    $item_count = 0;
                    if ($foods->category_id == $key->id) {

                        if (count($check_for_cart) != 0) {
                            foreach ($check_for_cart as $check_for_item) {
                                if ($foods->id == $check_for_item->food_id) {
                                    $item_count = $check_for_item->quantity;
                                }
                                // else
                                // {
                                //     $item_count =0;
                                // }
                            }
                        } else {
                            $item_count = 0;
                        }

                        if ($is_veg != 1) {
                            $category_wise_food[] = array(
                                'food_id' => $foods->id,
                                'name' => $foods->name,
                                'is_veg' => $foods->is_veg,
                                'price' => $foods->price,
                                'description' => $foods->description,
                                'category_id' => $foods->category_id,
                                'item_count' => $item_count,
                                'item_tax' => $foods->tax,
                                'add_ons' => $foods->Add_ons,
                                'food_quantity' => $foods->FoodQuantity,
                            );
                        } else {
                            if ($foods->is_veg == 1) {
                                $category_wise_food[] = array(
                                    'food_id' => $foods->id,
                                    'name' => $foods->name,
                                    'is_veg' => $foods->is_veg,
                                    'price' => $foods->price,
                                    'description' => $foods->description,
                                    'category_id' => $foods->category_id,
                                    'item_count' => $item_count,
                                    'item_tax' => $foods->tax,
                                    'add_ons' => $foods->Add_ons,
                                    'food_quantity' => $foods->FoodQuantity,
                                );
                            }
                        }
                    }
                }

                if ($category_wise_food) {
                    $get_food_list[] = array(
                        'category_id' => $key->id,
                        'category_name' => $key->category_name,
                        'items' => $category_wise_food,
                    );
                }
            }

            $restaurant_detail = $restaurants::where('id', $restaurant_id)->select('restaurant_name', 'image', 'address')->first();
            $restaurant_detail->image = BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image;
            if (count($get_food_list) != 0) {
                $response_array = array('status' => true, 'food_list' => $get_food_list, 'restaurant_detail' => $restaurant_detail);
            } else {
                $response_array = array('status' => false, 'message' => 'No Data Found');
            }
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
                'veg_only' => 'required',
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
                    $cart_count = $cart::where('user_id', $user_id)->where('food_id', $f->id)->first(); // For Cart item quantity

                    if (count($cart_count) != 0) {
                        $count = $cart_count->quantity;
                    } else {
                        $count = 0;
                    }
                    if ($veg_only == 0) {
                        $check_food_for_null += 1;
                        $foods[] = array(
                            'food_id' => $f->id,
                            'name' => $f->name,
                            'price' => $f->price,
                            'description' => $f->description,
                            'is_veg' => $f->is_veg,
                            'item_count' => $count,
                        );
                    } else {
                        if ($f->is_veg == 1) {
                            $check_food_for_null += 1;

                            $foods[] = array(
                                'food_id' => $f->id,
                                'name' => $f->name,
                                'price' => $f->price,
                                'description' => $f->description,
                                'is_veg' => $f->is_veg,
                                'item_count' => $count,
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
                    $response_array = array('status' => false, 'message' => 'No Data Found');
                }
            } else {
                $response_array = array('status' => false, 'message' => 'No Data Found');
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

    //     c

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
    public function get_checkout_details(Request $request)
    {
        //  dd($request);
        $delivery_address = $this->deliveryaddress;
        $restaurant_model = $this->restaurants;

        $user = Auth::guard('user')->user();

        $user_id = $user ? $user->id : 0;
        // dd($user_id);
        $user_number = $user ? $user->phone : null;

        if ($user == null) {
            $ses_user = 0;
            $user_wallet_amount =  0;
        } else {
            $ses_user = 1;
            $user_wallet_amount =  $user->wallet_amount;
        }

        $user_id = $user ? $user->id : 0;

        $addresses = $delivery_address->where('user_id', $user_id)->get();
        $restaurant = $restaurant_model->where('id', $request->restaurant_id)->first();
        // dd($restaurant);
        $near_by_restaurants = get_nearby_restaurants(env('DEFAULT_LATITUDE'), env('DEFAULT_LONGITUDE'), $id = [0], $limit = 3);
        $delivery_charge_limits = DB::table('delivery_charge_limits')->select('Minimum', 'Maximum', 'Value', 'Type')
            ->where('restaurant_id', $restaurant->id)->get()->toArray();

        $delivery_types = json_decode($restaurant->delivery_type);
        $is_delivery_enabled = 0;
        $is_pickup_enabled = 0;

        if ($delivery_types) {
            foreach ($delivery_types as $each_delivery_type) {
                if ($each_delivery_type == 1) {
                    $is_delivery_enabled = 1;
                }
                if ($each_delivery_type == 2) {
                    $is_pickup_enabled = 1;
                }
            }
        }

        $table_code = $request->session()->get('table_code');
        if (!$table_code) {
            $table_code = '';
        }
        $table_code = '';

        if ($table_code) {
            return view('user.checkout-dining', compact(
                'addresses',
                'restaurant',
                'near_by_restaurants',
                'delivery_charge_limits',
                'is_delivery_enabled',
                'is_pickup_enabled',
                'table_code',
                'ses_user',
                'user_wallet_amount',
                'user_number',
                'user_id',

            ));
        }

        $dt = Carbon::now();
        $promocode_list_restaurant = $this->promocode->where('restaurant_id', $request->restaurant_id)->where('status', '1')->whereRaw('"' . $dt . '" between `available_from` and `valid_till`')->get();
        // $promocode_list_restaurant = ['promocode_list_restaurant'=>$promocode_list_restaurant];
        //$promocode_list_restaurant = [];
        //dd($promocode_list_restaurant);
        if (sizeof($addresses)) {

            foreach ($addresses as $add) {

                if ($add->is_default == 1) {
                    $user_lat = $add->lat;
                    $user_lng = $add->lng;
                    //$distance = vincentyGreatCircleDistance($restaurant->lat, $restaurant->lng, $user_lat, $user_lng);
                    $d = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$restaurant->lat,$restaurant->lng&destinations=$user_lat,$user_lng&mode=driving&sensor=false&key=" . GOOGLE_API_KEY;
                    $json = file_get_contents($d); //dd($d);

                    $details = json_decode($json, TRUE);

                    if (isset($details['rows'][0]['elements'][0]['distance'])) {

                        $distance = (float) $details['rows'][0]['elements'][0]['distance']['text'];
                    } else {

                        return back()->with('flash_errors', 'Restaurant is not comes under your location', 'res_id', $request->restaurant_id);
                    }
                    $distance = (float) $details['rows'][0]['elements'][0]['distance']['text'];
                }
            }
        } else {
            //dd("ji");
            $user_lat = session()->get('lat');
            $user_lng = session()->get('lng');
            if (empty($user_lat)) {
                $user_lat = $restaurant->lat;
                $user_lng = $restaurant->lng;
            }
            //$distance = vincentyGreatCircleDistance($restaurant->lat, $restaurant->lng, $user_lat, $user_lng);
            $d = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$restaurant->lat,$restaurant->lng&destinations=$user_lat,$user_lng&mode=driving&sensor=false&key=" . GOOGLE_API_KEY;
            $json = file_get_contents($d);
            $details = json_decode($json, TRUE); //dd($d);

            if (isset($details['rows'][0]['elements'][0]['distance'])) {
                $distance = (float) $details['rows'][0]['elements'][0]['distance']['text'];
            } else {
                return back()->with('flash_errors', 'Restaurant is not comes under your location', 'res_id', $request->restaurant_id);
            }
            $distance = (float) $details['rows'][0]['elements'][0]['distance']['text'];
        }
        return view('user.checkout', compact(
            'addresses',
            'restaurant',
            'near_by_restaurants',
            'delivery_charge_limits',
            'is_delivery_enabled',
            'is_pickup_enabled',
            'table_code',
            'promocode_list_restaurant',
            'distance',
            'ses_user',
            'user_wallet_amount',
            'user_number',
            'user_id',

        ));
    }
    public function checkout(Request $request)
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

            $restaurants = $this->restaurants;
            $restaurant = $restaurants::where('id', $request->restaurant_id)->first();

            $restaurant_detail = array();

            $restaurant_detail[] = array(
                'restaurant_id' => $restaurant->id,
                'name' => $restaurant->restaurant_name,
                'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant->image,
                'address' => $restaurant->address,
                'estimated_delivery_time' => $restaurant->estimated_delivery_time,
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
                    $response_array = array('status' => false, 'message' => 'Invalid Coupon Code');
                    $response = response()->json($response_array, 200);
                    return $response;
                }
            } else {
                $coupon_code = "NA";
                $coupon_discount = 0;
            }

            $restaurant_packaging_charge = $restaurant->packaging_charge;
            $delivery_charge = 10;

            $invoice = array();

            $invoice[] = array(
                'offer_discount' => $coupon_discount,
                'restaurant_packaging_charge' => $restaurant_packaging_charge,
                'delivery_charge' => $delivery_charge,
                'coupon_code' => $coupon_code,
            );

            $response_array = array('status' => true, 'restaurant_detail' => $restaurant_detail, 'invoice' => $invoice);
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function paynow(Request $request)
    {
        // dd($request);

        $validator = Validator::make(
            $request->all(),
            [
                'restaurant_id' => 'required',
                'item_total' => 'required',
                //'offer_discount' => 'required',
                'restaurant_packaging_charge' => 'required',
                'gst' => 'required',
                'delivery_charge' => 'required',
                'bill_amount' => 'required',
                'coupon_code' => 'required',
                'food_id' => 'required',
                'food_qty' => 'required',
                'paid_type' => 'required',
                'delivery_type' => 'required',
                // 'delivery_address' => 'required'
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = ['status' => false, 'error_code' => 101, 'message' => $error_messages];
            return response()->json($response_array, 200);
        }

        $is_cod = $request->is_cod;
        dd($is_cod);
        $paymentmode = $is_cod;
        $phonenumber = $request->phonenumber;

        //   dd( $phonenumber);
        $user_wallet = $request->user_wallet;
        $table_name = null;
        $table_code = ''; //$request->session()->get('table_code');
        $table = DB::table('tables')->where('table_code', $table_code)->first();
        if ($table) {
            $table_name = $table->table_name;
        }


        $foodrequest = $this->foodrequest;
        $cart = $this->cart;
        $trackorderstatus = $this->trackorderstatus;
        $settings = $this->settings;
        $user = Auth::guard('user')->user();
        $user_id = $user ? $user->id : 0;

        if ($table_name && !$user_id) {
            $user = DB::table('users')->where('email', 'dining@deliware.app')->first();
            if ($user) {
                $user_id = $user->id;
            }
        }

        if ($user_id == 0) {
            $response_array = [
                'status' => false,
                'data' => [],
                'message' => 'Please login to continue'
            ];

            return response()->json($response_array, 200);
        }


        // $user_id = $request->header('authId');
        $restaurant_id = $request->restaurant_id;
        $item_total = $request->item_total;
        if (isset($request->offer_discount)) {
            $offer_discount = $request->offer_discount;
        } else {
            $offer_discount = 0;
        }
        $restaurant_packaging_charge = $request->restaurant_packaging_charge;
        $gst = $request->gst;
        $tips = $request->tips;
        $delivery_charge = $request->delivery_charge;
        $bill_amount = $request->bill_amount;
        $coupon_code = $request->coupon_code;
        $delivery_type = $request->delivery_type;
        $cooking_notes = $request->cooking_notes;


        $user_detail = $this->users::where('id', $user_id)->first();
        if (isset($user_detail) && ($user_detail->device_type == WEB || $request->device_type == WEB)) {
            $food_id = array();
            $food_qty = $food_quantity = $food_quantity_price = array();
            $food_ids = str_replace('"', '', (string) $request->food_id);
            $food_id = explode(',', $food_ids);
            $food_prices = str_replace('"', '', (string) $request->food_price);
            $food_price = explode(',', $food_prices);

            $food_qtys = str_replace('"', '', (string) $request->food_qty);
            $food_qty = explode(',', $food_qtys);

            $food_addons_all = explode('-', $request->addons);
            $food_addons = [];
            foreach ($food_addons_all as $key => $food_addons_of_each_product) {
                $eachProductAddons = explode(' ', $food_addons_of_each_product);

                if (isset($food_id[$key]))
                    $food_addons[$food_id[$key]] = $eachProductAddons;
            };

            $food_sizes_all = explode('-', $request->sizes);
            $food_sizes = [];
            foreach ($food_sizes_all as $key => $food_sizes_of_each_product) {
                $eachProductSizes = explode(' ', $food_sizes_of_each_product);

                if (isset($food_id[$key]))
                    $food_sizes[$food_id[$key]] = $eachProductSizes;
            };


            $food_quantitys = str_replace('"', '', (string) $request->food_quantity);
            $food_quantity = explode(',', $food_quantitys);
            $food_quantity_prices = str_replace('"', '', (string) $request->food_quantity_price);
            $food_quantity_price = explode(',', $food_quantity_prices);
        } else {
            $food_id = $request->food_id;
            $food_qty = $request->food_qty;
            $food_quantity = $request->food_quantity;
            $food_quantity_price = $request->food_quantity_price;
            $food_addons = [];
        }
        $food_id_size = sizeof($food_id);
        $food_qty_size = sizeof($food_qty);
        $paid_type = $request->paid_type;
        $delivery_address_detail = $this->deliveryaddress::where('user_id', $user_id)->where('is_default', 1)->first();

        if ($delivery_type == 1) {
            $d_lat = $delivery_address_detail->lat ?? 0;
            $d_lng = $delivery_address_detail->lng ?? 0;
            $delivery_address = $delivery_address_detail->address ?? '';
        } else {
            $d_lat = "";
            $d_lng = "";
            $delivery_address = '';
        }

        $order_id = $this->generate_booking_id();
        //  dd( $order_id);

        //get restaurant based commission
        $restaurant_details = $this->restaurants->find($restaurant_id);
        if (!empty($restaurant_details->admin_commision)) {
            $admin_commision_setting = $restaurant_details->admin_commision;
        } else {
            $admin_commision_setting = $settings::where('key_word', 'admin_commission')->value('value');
        }

        /*if (!empty($restaurant_details->driver_commision)) {
            $delivery_boy_commision_setting = $restaurant_details->driver_commision;
        } else {
            $delivery_boy_commision_setting = $settings::where('key_word', 'delivery_boy_commission')->value('value');
        }*/

        //$restaurant_commision_setting = $settings::where('key_word','restaurant_commission')->first();

        $admin_commission = ($bill_amount / 100) * $admin_commision_setting;
        $delivery_boy_commission = $delivery_charge;
        if ($delivery_type == 2) {
            $delivery_boy_commission = 0;
            $takeaway_code = rand(10000, 99999);
        } else {
            $takeaway_code = '';
        }
        $restaurant_commission = $bill_amount - ($admin_commission + $delivery_boy_commission);

        $order_data = array();
        $schedule = session()->get('schedule_date');
        $order_data[] = array(
            'order_id' => $order_id,
            'user_id' => $user_id,
            'restaurant_id' => $restaurant_id,
            'item_total' => $item_total,
            'offer_discount' => $offer_discount,
            'restaurant_packaging_charge' => $restaurant_packaging_charge,
            'tax' => $gst,
            'tips' => $tips,
            'delivery_charge' => $delivery_charge,
            'bill_amount' => $bill_amount,
            'admin_commision' => $admin_commission,
            'restaurant_commision' => $restaurant_commission,
            'delivery_boy_commision' => $delivery_charge,
            'coupon_code' => $coupon_code,
            'delivery_type' => $delivery_type,
            'table_name' => $table_name,
            'takeaway_code' => $takeaway_code,
            'is_cod' => $is_cod,
            'is_confirmed' => 0,
            'is_paid' => 0,
            'cooking_notes' => $cooking_notes,
            'paid_type' => $paid_type,
            'delivery_address' => $delivery_address,
            'd_lat' => $d_lat,
            'd_lng' => $d_lng,
            'ScheduledAt' => $schedule,
            'ordered_time' => date('Y-m-d H:i:s'),
        );


        if ($bill_amount >= $user_wallet) {
            $user_detail = $this->users->find($user_id);
            $status_entry1 = array();
            $status_entry1[] = array(
                'user_id' => $user_id,
                'amount' => $user_wallet,
                'topup_code' => '',
                'type' => 1, //1-credit 2-debit
                'status' => 1

            );
            $reduced = $user_wallet;

            $ins =     DB::table('user_wallet_history')->insert($status_entry1);
            $this->users->find($user_id)->decrement('wallet_amount', $user_wallet);
        } else {
            $user_detail = $this->users->find($user_id);
            $status_entry1 = array();
            $status_entry1[] = array(
                'user_id' => $user_id,
                'amount' => $bill_amount,
                'topup_code' => '',
                'type' => 1, //1-credit 2-debit
                'status' => 1
            );
            $reduced = $bill_amount;

            $ins = DB::table('user_wallet_history')->insert($status_entry1);
            $this->users->find($user_id)->decrement('wallet_amount', $bill_amount);
        }
        if ($is_cod == 0) {
            $order_data[0]['status'] = -1;
        }

        $foodrequest::insert($order_data);
        // dd($request);
        $last_id = $foodrequest::where('user_id', $user_id)->where('restaurant_id', $restaurant_id)->orderBy('id', 'desc')->first();

        $request_id = $last_id->id;

        $update_wallet = DB::table('requests')->where('id', $request_id)->update([
            'wallet_amount' => $reduced
        ]);
        $update_ins = DB::table('user_wallet_history')
            ->where('id', $ins)
            ->update([
                'order_id' => $request_id
            ]);
        $number = DB::table('requests')->where('order_id', $order_id)->update(['payment_ph_number' => $phonenumber]);



        for ($i = 0; $i < $food_id_size; $i++) {
            $request_detail = new Requestdetail;
            $request_detail->request_id = $last_id->id;
            $request_detail->restaurant_id = $restaurant_id;
            $request_detail->food_id = $food_id[$i];
            $request_detail->quantity = $food_qty[$i];
            $request_detail->addon_list = '0';
            $request_detail->food_quantity_price =  ($food_qty[$i] * $food_price[$i]);
            $request_detail->food_quantity = $food_sizes[$food_id[$i]][0] ?? ''; //taking only first size
            $request_detail->save();

            $request_detail_id = $request_detail->id;
            if (isset($food_addons[$food_id[$i]][0])) { //we are now taking the first addon only
                $addon_ids = explode(',', $food_addons[$food_id[$i]][0]);
                for ($j = 0; $j < count($addon_ids); $j++) {
                    $add_ons = $this->add_ons->find($addon_ids[$j]);
                    if (!isset($add_ons->id) || empty($add_ons->id)) {
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
        if ($is_cod == 0 || $is_cod == -1) {
            //  dd($paymentmode);
            $bill_amount_string = number_format($bill_amount, 2, '.', '');
            $payment_gateway = '';
            $payment_id = '';
            if ($paymentmode == 'Razorpay') {
                $payment_gateway = 'Razorpay';
                $checkout_url = url('/razorpay') . '/' . $last_id->id;
                $payment_id = '';
            } else if ($paymentmode == 'Stripe') {
                $payment_gateway = 'Stripe';
                $checkout_url = url('/pay-for-order') . '/' . $last_id->id;
                $payment_id = '';
            } else if ($paymentmode == 'Mollie') {
                $payment_gateway = 'Mollie';
                $payment = mollie()->payments()->create([
                    "amount" => [
                        "currency" => env('CURRENCY_CODE'),
                        "value" => $bill_amount_string
                    ],
                    "description" => $order_id,
                    "redirectUrl" => route('user.orders') . '#current-orders',
                    "webhookUrl" => env('MOLLIE_PAYMENT_CALLBACK')
                ]);
                $checkout_url = $payment->_links->checkout->href ?? null;
                $payment_id = $payment->id;
            } else if ($paymentmode == 'dahab') {
                $users = DB::table('users')
                    ->where('id', $user_id)->first();
                $phone_no = substr($phonenumber, -9);

                $payment_gateway = 'eDahab';


                $request_param = array("apiKey" => env('EDAHAB_API_KEY'), "edahabNumber" => $phone_no, "amount" => $bill_amount, "agentCode" => env('AGENT_CODE'), "returnUrl" => url('/edahab_response'));

                /* Encode it into a JSON string. */
                $json = json_encode($request_param, JSON_UNESCAPED_SLASHES);

                $hashed = hash('SHA256', $json . "3YgLehQBt4G6MNZkAh1EQ1h78n3dPyfjTS7FJD");


                $url = "https://edahab.net/api/api/IssueInvoice?hash=" . $hashed;

                $curl = curl_init($url);
                curl_setopt_array($curl, array(
                    CURLOPT_POST => TRUE,
                    CURLOPT_POSTFIELDS => $json,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/json",
                    ),
                ));
                $response = curl_exec($curl);

                $result = json_decode($response);
                session()->put('invoice_id', $result->InvoiceId);
                session()->put('order_id', $order_id);
                $checkout_url = "https://edahab.net/API/Payment?invoiceId=" . $result->InvoiceId;
                $payment_id = $result->InvoiceId;
            } else if ($paymentmode == 0) {
                // dd(env('PAYMENT_GATEWAY'));
                $payment_gateway = 'Mybank';

                $outletRef   = env('MYBANK_OUTLET');         // set your outlet reference/ID value here (example only)
                $apikey      = env('MYBANK_API');       // set your service account API key (example only)

                $users = DB::table('users')
                    ->where('id', $user_id)->first();
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api-gateway.sandbox.mybank.ngenius-payments.com/identity/auth/access-token");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "accept: application/vnd.ni-identity.v1+json",
                    "authorization: Basic " . $apikey,
                    "content-type: application/vnd.ni-identity.v1+json",
                    "User-Agent:PostmanRuntime/7.29.2",
                    "Accept-Encoding:gzip, deflate, br",
                    "Cookie:_abck=A7C1C963C589C30BEEAE1F4E54AFA2D7~-1~YAAQN687Fy0KnPOCAQAAjJARDAjtyL/Au65Vjk1ErxWmEDybbznUM0qzSpSGCUcavZxIsOAAza+xdp+BNJjBK85WTD4OaHz630cI+Ub0hdkFmKEr/hn/skGdr3n1Yi0nAX1OqhgDGJjhvXtgEtagKGKbJ0Ee7ODTyowwPqYd6/YpuhpQJGO+566s/2FkPUMDZ3vuEe0VVat+GN6riLcY0mGu4v9Ql00hF+322RLWCE65fdlFjETjoGS25I1F4k0CZTjZp8cKAz93OvJBQ1CUX8GqSfBD1mYmqmnwlfq/Qzhe3tB2k8B3v41/CFuNNEMhsHI/Gta1ILGcEI6J66Ptn0jJ5ghVl9HFTEcwqmdPVAR9FaMDBaNRW/D/pA66LscEa8uc1VdmYu4e28ImYScQMvI=~-1~-1~-1; bm_sz=DCF6FABE542147583BBB0BCCC20542F9~YAAQN687Fy4KnPOCAQAAjJARDBHbqKblzjT5TMfFhM2uRZPObiGph3eKllB0H72Z3VWwkyM++WRdpris5Z0xJXf3SRkCEiKzlO+tLy0c/QkBTuLFVqHNUnBMob6N6mJY2VAZO1DJfHX+qrvxAlz2x61yFn23ZdHDbmsULLuZEu/xg2CxlnSMWJi0k6xXZw+N8bqWCd0xRgdppZdpjagOkuyAVrQZsz9lkUKEcf9JvZJOt3UfsNSQDL2yKfm1mReA7vNmx2UZ+pRjm32pYdQ+dx0L81Yt9RbaXusx3jaAEiUzqldP1ew+X+ERhRSJ~3487814~3227971",
                    "Referer:https://api-gateway.sandbox.mybank.ngenius-payments.com/identity/auth/access-token"
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);

                $output1 = json_decode(curl_exec($ch));
                $access_token = $output1->access_token;
                //dd( $access_token);
                session()->put('token', $access_token);

                $postData = new StdClass();
                $postData->action = "PURCHASE";
                $postData->amount = new StdClass();
                $postData->amount->currencyCode = "USD";
                $postData->amount->value = $bill_amount * 100;
                $postData->emailAddress = $users->email;

                $postData->merchantAttributes  = new StdClass();
                $postData->merchantAttributes->merchantOrderReference = $order_id;
                $postData->merchantAttributes->redirectUrl = "http://127.0.0.1:8000/order_response";
                $postData->billingAddress  = new StdClass();
                $postData->billingAddress->firstName =  $users->name;
                $postData->billingAddress->lastName = $restaurant_id;
                $outlet = $outletRef;
                $token = $access_token; // to get this access token you have first call get access token API.
                // dd($token);
                $json = json_encode($postData);
                //dd($json);
                $ch1 = curl_init();
                curl_setopt($ch1, CURLOPT_URL, "https://api-gateway.sandbox.mybank.ngenius-payments.com/transactions/outlets/" . $outlet . "/orders");
                curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
                    "Authorization: Bearer " . $access_token,
                    "Content-Type: application/vnd.ni-payment.v2+json",
                    "Accept: application/vnd.ni-payment.v2+json"
                ));

                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch1, CURLOPT_POST, 1);
                curl_setopt($ch1, CURLOPT_POSTFIELDS, $json);
                $output = json_decode(curl_exec($ch1));
                curl_close($ch1);
                $checkout_url = $output->_links->payment->href;
                $payment_id =  $output->reference;
            } else if ($paymentmode == -1) {
                $payment_gateway = 'waafipay';
                $checkout_url = url('/waafipay_order') . '/' . $last_id->id;
                $payment_id = '';
            }

            DB::table('payments')->insert([
                'request_id' => $last_id->id,
                'payment_gateway' => $payment_gateway,
                'amount' => $bill_amount_string,
                'currency' => env('CURRENCY_CODE'),
                'payment_id' => $payment_id,
                'payment_description' => $order_id
            ]);
        }
        // dd($is_cod);
        if ($is_cod == 1) {
            sendNewOrderEmail2Restaurant($request_id);
            sendNewOrderEmail2Customer($request_id);
        }

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
        $postdata['request_id'] = (string) $request_id;
        $postdata['user_id'] = (float) $user_id;
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

        //dd("fcm");
        $cart::where('user_id', $user_id)->delete();

        $status_entry[] = array(
            'request_id' => $request_id,
            'status' => 0,
            'detail' => "Order Placed",
        );

        $trackorderstatus::insert($status_entry);

        if ($table_name) {
            $response_data_array = [
                'redirect_url' => url(''),
            ];
        } else {
            $response_data_array = [
                'checkout_url' => $checkout_url,
                'order_id' => preg_replace("/[^0-9.]/", "", $order_id),
                // 'payment_getway' => $payment_gateway,
                // 'token'=>$token,
            ];
        }
        $webToken = DB::table('user_fcm_token')->where('userid', $restaurant_id)->where('type', 'web')->value('token');
        //send push notification to user
        if ($webToken) {
            $title = trans('constants.new_order');
            $this->user_send_push_notification($webToken, 'web', $title, "New Order has been placed: $request_id", $request_id);
        }
        $response_array = [
            'status' => true,
            'data' => $response_data_array,
            'message' => 'Order placed successfully'
        ];

        return response()->json($response_array, 200);
    }

    public function search_restaurants(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            array(
                'key_word' => 'required',
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
            $key_word = $request->key_word;

            $restaurant_cuisines = DB::table('restaurant_cuisines')->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
                ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
                ->select('restaurant_cuisines.restaurant_id as restaurant_id', 'cuisines.name as cuisine_name', 'restaurants.restaurant_name as restaurant_name')->get();

            $data = $restaurants::where('restaurant_name', 'like', '%' . $key_word . '%')->where('status', 1)->get();

            $restaurant_list = array();
            $j = 0;
            foreach ($data as $d) {
                // if($j<2)  // To display only two restaurants
                // {
                $rcuisines = array();
                $i = 0;
                foreach ($restaurant_cuisines as $r_cuisines) {

                    if ($d->restaurant_name == $r_cuisines->restaurant_name && $i < 2) // To display only two cuisines
                    {

                        $rcuisines[] = array(
                            'name' => $r_cuisines->cuisine_name,
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


                $restaurant_list[] = array(
                    'id' => $d->id,
                    'name' => $d->restaurant_name,
                    'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image,
                    'discount' => $d->discount,
                    'rating' => $d->rating,
                    'is_open' => $is_open, // 1- Open , 0 - Close
                    'cuisines' => $rcuisines,
                    'travel_time' => $d->estimated_delivery_time,
                    'price' => '',
                    'is_favourite' => $is_favourite,
                    'address' => $d->address,
                );

                $j++;

                // }
            }

            if (count($data) != 0) {
                $response_array = array('status' => true, 'restaurants' => $restaurant_list);
            } else {
                $response_array = array('status' => false, 'message' => 'No Restaurants Found');
            }
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function signup_form(Request $request)
    {

        $city = $this->addcity->get();
        //$cuisines = $this->cuisines->get();
        $cuisines = $this->cuisines->get();
        return view('user.new-restaurant', [
            'city' => $city,
            'cuisines' => $cuisines,
            //'area' => $area, 
        ]);
    }

    public function signup_form_submit(Request $request)
    {

        $rules = [
            'name' => 'required|max:50',
            'email' => 'required|unique:restaurants,email',
            'phone' => 'required|numeric|unique:restaurants,phone',
            'password' => 'required',
            // 'city' => 'required',
            // 'area' => 'required',
            //'status' => 'required',
            //'estimated_delivery_time' => 'required',
            //'banner' => 'mimes:jpeg,jpg,png|dimensions:min_width=1359,min_height=424',
            'address' => 'required',
            //'packaging_charge' => 'required',
            //'offer_percentage' => 'required',
            // 'delivery_type' => 'required|array',
            'cuisines' => 'required|array',
            // 'account_name' => 'required',
            // 'account_address' => 'required',
            // 'account_no' => 'required',
            // 'bank_name' => 'required',
            // 'branch_name' => 'required',
            // 'branch_address' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('error', $error_messages)->withInput();
        }


        $restaurants = $this->restaurants;
        $custom = $this->custom;
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $city = $request->city ?? null;
        $area = $request->area ?? null;
        $discount_type = ''; //$request->discount_type;
        $target_amount = ''; //$request->target_amount;
        $offer_amount = ''; //$request->offer_amount;
        $admin_commision = ''; //$request->admin_commision;
        //$status = $request->status;

        $packaging_charge = '0'; //$request->packaging_charge;
        //$offer_percentage = $request->offer_percentage;
        $shop_description = $request->shop_description ? $request->shop_description : '';

        $estimated_delivery_time = $request->estimated_delivery_time; //$request->estimated_delivery_time;
        $address = $request->address;

        $check_email_phone = $restaurants->where('email', $request->email)->orwhere('phone', $request->phone)->first();
        if ($check_email_phone) {
            return back()->with('error', 'Email/Phone already exists')->withInput();;
        }

        $restaurants->restaurant_name = $name;
        $restaurants->image = ''; //$image;
        $restaurants->banner = $request->banner ?? ''; //$banner;

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
        $restaurants->admin_commision = '0'; //$admin_commision;
        $restaurants->restaurant_delivery_charge = '0'; //$request->restaurant_delivery_charge;
        // $restaurants->driver_commision = '0'; //$request->driver_commision;
        //$restaurants->discount = $offer_percentage;
        $restaurants->shop_description = $shop_description;
        $restaurants->is_open = 0;
        $restaurants->estimated_delivery_time = $estimated_delivery_time;
        $restaurants->packaging_charge = $packaging_charge;
        $restaurants->address = $address;
        $restaurants->lat = $request->latitude;
        $restaurants->lng = $request->longitude;

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

        $restaurants->status = '0'; //$status;
        $restaurants->delivery_type = json_encode($request->delivery_type); //json_encode($request->delivery_type);
        $restaurants->save();
        //save cuisine
        $cuisines = $this->cuisines->find($request->cuisines); //dd($cuisines);
        $restaurants->Cuisines()->sync($cuisines);

        $restaurant_new = $restaurants->where('email', $request->email)->first();
        $html = view('emails.restaurant.restaurant-welcome')->with(['restaurant' => $restaurant_new]);

        $Mailer = new Mailer();
        $Mailer->to         = $restaurant_new->email;
        $Mailer->toName     = $restaurant_new->restaurant_name;
        $Mailer->subject     = "Welcome";
        $Mailer->body       = $html;
        $Mailer->send();

        return back()->with('success', 1);
    }

    public function signup_success(Request $request)
    {
        return view('user.restaurant_register_success');
    }

    public function pay_for_order(Request $request)
    {
        //dd($request->orderID);
        $order = DB::table('requests')->where('id', $request->orderID)->first();

        return view('user.pay_for_order')->with('order', $order);
    }

    public function pay_nets(Request $request)
    {

        $EasyCheckout = new EasyCheckout;
        $EasyCheckout->createCheckoutURL(100);

        //dd($a);

        return view('user.pay-nets'); //->with('order', $order);
    }

    public function check_pay_for_order(Request $request)
    {
        dd("g");
        $stripe = new Stripe(STRIPE_SK_KEY, STRIPE_VERSION);

        $order = DB::table('requests')->where('id', $request->orderID)->first();
        $restaurant = DB::table('restaurants')->where('id', $order->restaurant_id)->first();

        $charge = $stripe->charges()->create([
            'source' => $request->stripeToken,
            'currency' => env('CURRENCY_CODE'),
            'amount'   => $order->bill_amount,
        ]);

        if ($charge['paid']) {
            DB::table('payments')->where('request_id', $order->id)->update(['payment_id' => $charge['id'], 'is_paid' => 1]);
            DB::table('requests')->where('id', $order->id)->update(['status' => 0, 'is_paid' => 1]);

            sendNotification(
                [$restaurant->device_token],
                trans('constants.notification_restaurant_new_title'),
                trans('constants.notification_restaurant_new_msg')
            );
        }

        return redirect(route('user.orders') . '#current-orders');
    }

    public function verify_restaurant(Request $request)
    {

        $restaurant = DB::table('restaurants')->where('id', $request->id)->first();

        if ($restaurant->email_confirmation == "") {
            return redirect('/admin')->with('success', 'Email already verified');
        } else if ($restaurant->email_confirmation == $request->code) {
            DB::table('restaurants')->where('id', $restaurant->id)->update(['email_confirmation' => '']);
            return redirect('/store/my_restaurant')->with('success', 'Email verified successfully');
        } else {
            return redirect('/store')->with('success', 'Email not verified');
        }
    }
    public function storeFCMToken(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'token' => 'required'
            ]
        );
        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
            $response = response()->json($response_array, 200);
            return $response;
        }
        $userId = $request->user;
        $token = $request->token;
        DB::table('user_fcm_token')->updateOrInsert(
            ['userid' => $userId, 'type' => 'web'],
            ['token' => $token]
        );
        return response()->json(['status' => true, 'message' => 'Inserted Updated'], 200);
    }
    public function createstripePayment()
    {

        $stripe = new Stripe(STRIPE_SK_KEY, STRIPE_VERSION);

        $paymentIntent = $stripe->paymentIntents()->create([
            'amount' => 10,
            'currency' => env('CURRENCY_CODE'),
            'automatic_payment_methods' => [
                'enabled' => 'true',
            ],
            'capture_method' => 'manual'
        ]);
        $output = [
            'clientSecret' => $paymentIntent['client_secret'],
        ];
        session()->put('clientSecret', $paymentIntent['client_secret']);
        echo json_encode($output);
    }

    public function  response_mybank(Request $request)
    {
        $token = $request->session()->get('token');
        // dd($token);
        $outlet = env('MYBANK_OUTLET');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api-gateway.sandbox.mybank.ngenius-payments.com/transactions/outlets/" . $outlet . "/orders" . "/" . $request->ref,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $token,
                "Accept: application/vnd.ni-payment.v2+json"
            ],
        ]);
        $response = curl_exec($curl);

        $result = json_decode($response);
        // dd($result);
        $order_id1 = $result->merchantAttributes->merchantOrderReference;
        $res_id = $result->billingAddress->lastName;

        $status = $result->_embedded->payment[0]->state;
        $err = curl_error($curl);
        $order_id =  preg_replace("/[^0-9.]/", "", $order_id1);
        curl_close($curl);


        if ($status == "PURCHASED") {
            $orderid = DB::table('requests')->where('order_id', $order_id1)->first();
            $device_id = DB::table('restaurants')->where('id', $orderid->restaurant_id)->first();
            DB::table('payments')->where('request_id', $order_id)->update(['payment_id' => $request->ref, 'is_paid' => 1]);
            DB::table('requests')->where('id', $order_id)->update(['status' => 0, 'is_paid' => 1]);

            sendNotification(
                [$device_id->device_token],
                trans('constants.notification_restaurant_new_title'),
                trans('constants.notification_restaurant_new_msg')
            );
            return redirect()->route('user.track_order', ['order_id' => $order_id]);
        } else {
            return redirect()->route('user.get_checkout_details', ['rest_id' =>  $res_id]);
        }
    }


    function asmconnect($postType, $oder_id, $bill_amount, $phonenumber)
    {
        $user = Auth::guard('user')->user();
        $user_id = $user ? $user->id : 0;
        $cus_no = DB::table('users')->where('id', $user_id)->first();
        $referenceId = $oder_id;
        $invoiceId = self::random_string(8);
        $account = $phonenumber;
        // $amount = $bill_amount;
        $amount = "0.01";
        $currency = env('CURRENCY_CODE');
        $description = "wan diray";
        $paymentBrand = "WAAFI / ZAAD / SAHAL / EVCPLUS / VISA/MASTERCARD";
        $transactionCategory = "ECOMMERCE / AIRLINE/ APPOINTMENTS ";
        $merchantUid = env('WAAFIPAY_MERCHANT_ID');
        $apiUserId = env('WAAFIPAY_API_USER_ID');
        $apiKey = env('WAAFIPAY_API_KEY');
        $api_URL = "https://api.waafipay.net/asm";

        $phone_no = substr($account, -9);
        if (!str_starts_with($phone_no, '252')) {
            $account = '252' . $phone_no;
        }

        $Pre_Auth_Request = [
            "schemaVersion" => "1.0",
            "requestId" => $oder_id,
            "timestamp" => time(),
            "channelName" => "WEB",
            "serviceName" => "API_PREAUTHORIZE",
            "serviceParams" => [
                "merchantUid" => $merchantUid,
                "apiUserId" => $apiUserId,
                "apiKey" => $apiKey,
                "paymentMethod" => "MWALLET_ACCOUNT",
                "payerInfo" => [
                    "accountNo" => $account
                ],
                "transactionInfo" => [
                    "referenceId" => $referenceId,
                    "invoiceId" => $invoiceId,
                    "amount" => $amount,
                    "currency" => $currency,
                    "description" => $description,
                    "paymentBrand" => $paymentBrand,
                    "transactionCategory" => $transactionCategory
                ]
            ],

        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $postType,
            CURLOPT_POSTFIELDS => json_encode($Pre_Auth_Request),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }

    function asmcommit($postType, $pre_auth_response)
    {

        $merchantUid = env('WAAFIPAY_MERCHANT_ID');
        $apiUserId = env('WAAFIPAY_API_USER_ID');
        $apiKey = env('WAAFIPAY_API_KEY');
        $api_URL = "https://api.waafipay.net/asm";

        $CommitRequest = [
            "schemaVersion" => "1.0",
            "requestId" => $pre_auth_response->requestId,
            "timestamp" => time(),
            "channelName" => "WEB",
            "serviceName" => "API_PREAUTHORIZE_COMMIT",
            "serviceParams" => [
                "merchantUid" => $merchantUid,
                "apiUserId" => $apiUserId,
                "apiKey" => $apiKey,
                "transactionId" => $pre_auth_response->params->transactionId,
                "description" => "Commited",
                "referenceId" => $pre_auth_response->params->referenceId
            ],
        ];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $postType,
            CURLOPT_POSTFIELDS => json_encode($CommitRequest),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);


        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }

    public static function random_string($length = 10)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function waafipay_order(Request $request)
    {

        $order = DB::table('requests')->where('id', $request->orderID)->first();
        $pre_auth_response =  $this->asmconnect('POST', $order->order_id, $order->bill_amount, $order->payment_ph_number);

        //  dd($pre_auth_response); 
        if ($pre_auth_response->responseCode == 2001) {
            $commit_trans =  $this->asmcommit('POST', $pre_auth_response);
            if ($commit_trans->responseCode == 2001) {
                //dd($commit_trans);
                //    $order_id1 = $commit_trans->params->commit_trans;
                $trans_id = $commit_trans->params->transactionId;
                $order_id =  preg_replace("/[^0-9.]/", "", $order->order_id);
                // $orderid = DB::table('requests')->where('order_id', $order_id1)->first();
                $device_id = DB::table('restaurants')->where('id', $order->restaurant_id)->first();
                DB::table('payments')->where('request_id', $order_id)->update(['payment_id' => $trans_id, 'is_paid' => 1]);
                DB::table('requests')->where('id', $order_id)->update(['status' => 0, 'is_paid' => 1]);

                sendNotification(
                    [$device_id->device_token],
                    trans('constants.notification_restaurant_new_title'),
                    trans('constants.notification_restaurant_new_msg')
                );
                return redirect()->route('user.track_order', ['order_id' => $order_id]);
            } else {
                // dd($commit_trans);
                return redirect()->route('user.get_checkout_details', ['rest_id' =>  $order->restaurant_id]);
                // return false;
            }
        } else {
            return redirect()->route('user.get_checkout_details', ['rest_id' =>  $order->restaurant_id]);
            // return false;

        }
    }

    public function  edahab_response(Request $request)
    {
        $invoiceId = $request->session()->get('invoice_id');

        $request_param = array("apiKey" => env('EDAHAB_API_KEY'), "invoiceId" => $invoiceId);
        /* Encode it into a JSON string. */
        $json = json_encode($request_param, JSON_UNESCAPED_SLASHES);

        $hashed = hash('SHA256', $json . "3YgLehQBt4G6MNZkAh1EQ1h78n3dPyfjTS7FJD");


        $url = "https://edahab.net/api/api/CheckInvoiceStatus?hash=" . $hashed;

        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
            ),
        ));

        $output = curl_exec($curl);
        $result = json_decode($output);
        $orderID = $request->session()->get('order_id');
        $order = DB::table('requests')->where('order_id', $orderID)->first();
        $order_id =  preg_replace("/[^0-9.]/", "", $order->order_id);
        if ($result->StatusCode != 0) {
            return redirect()->route('user.get_checkout_details', ['rest_id' =>  $order->restaurant_id]);
        }
        if ($result->InvoiceStatus == "Paid") {
            // $orderid = DB::table('requests')->where('order_id', $order_id1)->first();
            $device_id = DB::table('restaurants')->where('id', $order->restaurant_id)->first();
            DB::table('payments')->where('request_id', $order_id)->update(['payment_id' => $result->RequestId, 'is_paid' => 1]);
            DB::table('requests')->where('id', $order_id)->update(['status' => 0, 'is_paid' => 1]);

            sendNotification(
                [$device_id->device_token],
                trans('constants.notification_restaurant_new_title'),
                trans('constants.notification_restaurant_new_msg')
            );
            return redirect()->route('user.track_order', ['order_id' => $order_id]);
        } else {
            return redirect()->route('user.get_checkout_details', ['rest_id' =>  $order->restaurant_id]);
        }
    }

    public function allCityList()
    {
        $city_list = DB::table('add_city')->where('status', 1)->get();

        return response()->json($city_list);
    }
}
