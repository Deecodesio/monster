<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Restaurant;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use File;
class UserController extends BaseController
{

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
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }
        $address_id = $request->address_id;

        $address = $this->deliveryaddress::where('user_id', $user_id)->where('id', $address_id)->first();
        if ($address) {
            $data = $address->update(['is_default' => 1]);
            // dd($data);
            $this->deliveryaddress::where('user_id', $user_id)
                ->where('id', '!=', $address_id)
                ->update(['is_default' => 0]);
            $response_array = array('status' => true, 'message' => 'Default address added.', 'data' => $address);
        } else {
            $response_array = array('status' => false, 'message' => 'No address found');
        }
        return response()->json($response_array, 200);
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
        foreach($data as $datas)
        {
          if ($datas->address != "") {
              $decodedText = html_entity_decode($datas->address);
              $datas->address = $decodedText;
            }
        }
        if (count($data) != 0) {
            return response()->json(['status' => true, 'data' => $data]); // type - 1 home, 2 work, 3 others
        } else {
            return response()->json(['status' => false, 'message' => 'No address found']);
        }
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
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            if ($request->header('authId') != "") {
                $user_id = $request->header('authId');
            } else {
                $user_id = $request->authId;
            }
            // $user_id = $request->header('authId');
            // $address = $request->address;
            $lat = $request->lat;
            $lng = $request->lng;
            $type = $request->type;
            $flat_no = $request->flat_no;
            $landmark = $request->landmark;
            $delivery_address = $this->deliveryaddress;

            $check_for_default_address = $delivery_address::where('user_id', $user_id)->where('is_default', 1)->get();

            if (count($check_for_default_address) != 0) {
                $is_default = 0;
            } else {
                $is_default = 1;
            }
            $originalText = $request->address;
            $convertedText = mb_convert_encoding($originalText, 'HTML-ENTITIES', 'UTF-8');
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
                'is_default' => $is_default,
            );

            $id = $delivery_address::insert($insert_data);
            $data = $delivery_address::where('user_id', $user_id)->orderBy('id', 'desc')->first();
            $response_array = array('status' => true, 'data' => $data, 'message' => 'Address added successfully');
        }

        $response = response()->json($response_array, 200);
        return $response;
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
        $data = DB::table('offers_banner')->where('status', 1)->orderBy('position', 'asc')->get();

        foreach ($data as $k => $d) {
            $data[$k]->is_open = restaurant_is_open($d->restaurant_id);
        }

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

            $size_cuisines = is_array($request->cuisines) ? count($request->cuisines) : 0;
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

                $this_rating = get_restaurant_ratings($d->id);

                $rating = $this_rating->total_raters ? number_format($this_rating->total_ratings / $this_rating->total_raters, 2) : 0;


                if (count($rcuisines) > 0) {
                    $restaurant_list[] = array(
                        'id' => $d->id,
                        'name' => $this->updateRestaurantName($d, $request),
                        'image' => File::exists(public_path('restaurant_uploads/').$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->image: (File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Logo/'.$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Logo/'.$d->image : BASE_URL.UPLOADS_PATH_LOGO.$d->image),
                        'banner' => File::exists(public_path('restaurant_uploads/').$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->banner:(File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Banner/'.$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Banner/'.$d->banner: BASE_URL.UPLOADS_PATH_BANNER.$d->banner),
                        'discount' => $d->discount,
                        'rating' => $rating,
                        'is_open' => $is_open, // 1- Open , 0 - Close
                        'cuisines' => $rcuisines,
                        'travel_time' => $d->estimated_delivery_time,
                        'price' => '',
                        'is_favourite' => $is_favourite,
                    );
                }
            }

            if (count($restaurant_list) > 0) {

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
        $validator = Validator::make(
            $request->all(),
            array(
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

            $delivery_type = $request->delivery_type ?? 0;

            $source_lat = $request->lat;
            $source_lng = $request->lng;

            $restaurants = $this->restaurants;

            //geofencing for restaurant
            $data = $restaurants->with('Cuisines')->where('status', 1)
                ->select('restaurants.*')
                ->selectRaw("(6371 * acos(cos(radians(" . $source_lat . "))* cos(radians(`lat`))
                            * cos(radians(`lng`) - radians(" . $source_lng . ")) + sin(radians(" . $source_lat . "))
                            * sin(radians(`lat`)))) as distance")
                ->having('distance', '<=', DEFAULT_RADIUS)
                ->orderBy('distance')
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

                $restaurant_list[] = array(
                    'id' => $d->id,
                    'name' => $this->updateRestaurantName($d, $request),
                    'image' => File::exists(public_path('restaurant_uploads/').$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->image: (File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Logo/'.$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Logo/'.$d->image : BASE_URL.UPLOADS_PATH_LOGO.$d->image),
                    'banner' => File::exists(public_path('restaurant_uploads/').$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->banner:(File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Banner/'.$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Banner/'.$d->banner: BASE_URL.UPLOADS_PATH_BANNER.$d->banner),
                    'discount' => $d->discount,
                    'rating' => (float) $rating_statistics['rating_percent'], //$d->rating,
                    'is_open' => $is_open, // 1- Open , 0 - Close
                    'cuisines' => $rcuisines,
                    'travel_time' => $d->estimated_delivery_time,
                    'delivery_type' => json_decode($d->delivery_type),
                    'price' => '',
                    'is_favourite' => $is_favourite,
                    'address' => $d->address,
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
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_popular_brands(Request $request)
    {
        $popularbrands = $this->popularbrands;

        $data = $popularbrands::get()->toArray();
        $base_url = BASE_URL;
        $data = array_map(function ($item) use ($base_url) {
            $item['image'] = $base_url . $item['image'];
            return $item;
        }, $data);
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
                    $is_open = restaurant_is_open($restaurant_detail->id);

                    $rating_statistics = get_rating_statistics($restaurant_detail->id);

                    $restaurant_list[] = array(
                        'restaurant_id' => $key->restaurant_id,
                        'name' => $restaurant_detail->restaurant_name,
                        'image' =>File::exists(public_path('restaurant_uploads/').$restaurant_detail->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_detail->image: (File::exists(public_path('restaurant_uploads/').$restaurant_detail->id.'/'.'Logo/'.$restaurant_detail->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_detail->id.'/'.'Logo/'.$restaurant_detail->image : BASE_URL.UPLOADS_PATH_LOGO.$restaurant_detail->image),

                        'discount' => $restaurant_detail->discount,
                        'rating' => (float) $rating_statistics['rating_percent'],
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

        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);

            $response = response()->json($response_array, 200);
            return $response;
        }

        $restaurant_id = $request->restaurant_id;
        $favouritelist = $this->favouritelist;

        $check = $favouritelist::where('user_id', $user_id)->where('restaurant_id', $restaurant_id)->first();

        if ($check) {
            $favouritelist::where('id', $check->id)->delete();

            $response_array = array('status' => true, 'message' => 'Removed from Favourites');
        } else {
            $data = array();
            $data['user_id'] = $user_id;
            $data['restaurant_id'] = $restaurant_id;
            $favouritelist::insert($data);

            $response_array = array('status' => true, 'message' => 'Added to Favourites');
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

        $check_request = $this->foodrequest::where('user_id', $user_id)
            ->where('status', '!=', -1)
            ->where('status', '!=', 9)
            ->where('status', '!=', 10)
            ->where('status', '!=', 11)
            ->where('is_rated', '=', 0)
            ->orderBy('id', 'desc')->get();

        if (!$check_request) {

            $response_array = array('status' => false, 'message' => 'No orders in processing');
            $response = response()->json($response_array, 200);
            return $response;
        }

        $order_status = array();
        foreach ($check_request as $key) {

            $restaurant_detail = $this->restaurants::where('id', $key->restaurant_id)->first();

            if (!$restaurant_detail) {
                continue;
            }

            $item_list = DB::table('request_detail')->where('request_id', $key->id)->get();

            $item_count = 0;


            $delivery_boy_id = 0;
            $delivery_boy_name = "";
            $delivery_boy_image = "";
            $delivery_boy_phone = "";

            if ($key->delivery_boy_id != 0) {
                $delivery_boy_id = $key->delivery_boy_id;
                // echo $delivery_boy_id; exit;
                $delivery_boy_detail = DB::table('delivery_partners')->where('id', $delivery_boy_id)->first();

                if ($delivery_boy_detail) {
                    $delivery_boy_name = $delivery_boy_detail->name;

                    $delivery_boy_image = $delivery_boy_detail->profile_pic;

                    $delivery_boy_phone = $delivery_boy_detail->phone;
                }
            }

            $get_item_lists = array();
            foreach ($item_list as $list) {

                $item_count = $item_count + $list->quantity;
                $food_detail = DB::table('food_list')->where('id', $list->food_id)->first();
                if (!$food_detail) {
                    continue;
                }
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

            $check_request = $this->foodrequest::where('id', $request_id)->first();
            if (!$check_request) {
                return response()->json(['status' => false, 'error_code' => 101, 'message' => 'Order not found'], 200);
            }

            $restaurant_detail = DB::table('restaurants')->where('id', $check_request->restaurant_id)->first();
            if (!$restaurant_detail) {
                return response()->json(['status' => false, 'error_code' => 101, 'message' => 'Restaurant not found'], 200);
            }

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

            $order_detail = $this->requestdetail->where('request_id', $request_id)->get();
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
                    //'item_price' => (!empty($k->Foodlist) ? $k->Foodlist->price : 0) * $k->quantity,
                    'item_price' => (!empty($k->Foodlist) ? $k->food_quantity_price : 0) * $k->quantity,
                    'is_veg' => (!empty($k->Foodlist) ? $k->Foodlist->is_veg : ""),
                    'food_size' => $k->FoodQuantity,
                    'add_ons' => $k->Addons,
                );
            }
            if ($restaurant_detail->address != "") {
                $decodedText = html_entity_decode($restaurant_detail->address);
                $restaurant_detail->address = $decodedText;
              }
              if ($check_request->delivery_address != "") {
                $decodedText = html_entity_decode($check_request->delivery_address);
                $check_request->delivery_address = $decodedText;
              }
            $order_status[] = array(
                'request_id' => $request_id,
                'order_id' => $check_request->order_id,
                'ordered_time' => $check_request->ordered_time,
                'schedule_time' => $check_request->ScheduledAt,
                'tips' => $check_request->tips,
                'restaurant_name' => $restaurant_detail->restaurant_name,
                'restaurant_image' => File::exists(public_path('restaurant_uploads/').$restaurant_detail->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_detail->image: (File::exists(public_path('restaurant_uploads/').$restaurant_detail->id.'/'.'Logo/'.$restaurant_detail->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_detail->id.'/'.'Logo/'.$restaurant_detail->image : BASE_URL.UPLOADS_PATH_LOGO.$restaurant_detail->image),

                'restaurant_phone' => $restaurant_detail->phone,
                'restaurant_address' => $restaurant_detail->address,
                'delivery_address' => $check_request->delivery_address,
                'item_count' => $item_count,
                'item_total' => $check_request->item_total,
                'offer_discount' => $check_request->offer_discount,
                'restaurant_packaging_charge' => $check_request->restaurant_packaging_charge,
                'tax' => $check_request->tax,
                'delivery_charge' => $check_request->delivery_charge,
                'delivery_type' => $check_request->delivery_type,
                'takeaway_code' => $check_request->takeaway_code,
                'delay_time' => $check_request->delay_time,
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
                'food_items' => $order_list_detail,
                'is_cod'=>$check_request->is_cod,
            );

            $tracking_detail = $trackorderstatus::where('request_id', $request_id)->get();

            $response_array = array('status' => true, 'order_status' => $order_status, 'tracking_detail' => $tracking_detail);
        }

        $response = response()->json($response_array, 200);
        return $response;
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

        if ($request_data->status >= 5) {
            return response()->json(['status' => false, 'message' => 'Cannot cancel order at this state'], 200);
        }


        $this->foodrequest->where('id', $request_id)->update(['status' => 11]);

        $status_entry = [
            'request_id' => $request_id,
            'status' => 11,
            'detail' => $request->detail
        ];

        $this->trackorderstatus->insert($status_entry);

        sendCancelledOrderEmail2Restaurant($request_id);
        sendOrderCancelledEmail2Customer($request_id);

        return response()->json(['status' => true, 'message' => 'Success'], 200);
    }

    public function order_history(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }
        // $user_id = $request->header('authId');
        $user_wa = DB::table('users')->where('id',$user_id)->first();
        $user_wallet = $user_wa->wallet_amount;
        $orders = DB::table('requests')->where('requests.user_id', $user_id)
            ->where('status', '!=', -1)
            ->where('status', '!=', 9)
            ->where('status', '!=', 10)
            ->where('status', '!=', 11)
            ->where('requests.is_paid', 1)
            ->latest()->limit(10)->get();

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
                if ($restaurant_detail->address != "") {
                    $decodedText = html_entity_decode($restaurant_detail->address);
                    $restaurant_detail->address = $decodedText;
                  }
                  if ($key->delivery_address != "") {
                    $decodedText = html_entity_decode($key->delivery_address);
                    $key->delivery_address = $decodedText;
                  }
            $order_list[] = array(
                'request_id' => $key->id,
                'order_id' => $key->order_id,
                'restaurant_id' => isset($restaurant_detail->id) ? $restaurant_detail->id : $key->restaurant_id,
                'restaurant_name' => isset($restaurant_detail->restaurant_name) ? $restaurant_detail->restaurant_name : "",
                'restaurant_image' =>  File::exists(public_path('restaurant_uploads/').$restaurant_detail->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_detail->image: (File::exists(public_path('restaurant_uploads/').$restaurant_detail->id.'/'.'Logo/'.$restaurant_detail->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_detail->id.'/'.'Logo/'.$restaurant_detail->image : BASE_URL.UPLOADS_PATH_LOGO.$restaurant_detail->image),

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
                'wallet_amount'=>$key->wallet_amount,
            );
            //}

        }

        $upcoming_orders = DB::table('requests')->where('requests.user_id', $user_id)
            ->where('requests.status', '!=', -1)
            ->where('requests.status', '!=', 7)
            ->where('requests.status', '!=', 9)
            ->where('requests.status', '!=', 10)
            ->where('requests.status', '!=', 11)
            ->get();

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
            if ($restaurant_details->address != "") {
                $decodedText = html_entity_decode($restaurant_details->address);
                $restaurant_details->address = $decodedText;
              }
              if ($key->delivery_address != "") {
                $decodedText = html_entity_decode($key->delivery_address);
                $key->delivery_address = $decodedText;
              }
            if (isset($restaurant_details->id)) {
                $upcoming_order_list[] = array(
                    'request_id' => $key->id,
                    'order_id' => $key->order_id,
                    'restaurant_id' => $key->restaurant_id,
                    'restaurant_name' => $restaurant_details->restaurant_name,
                    'restaurant_image' =>  File::exists(public_path('restaurant_uploads/').$restaurant_details->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_details->image: (File::exists(public_path('restaurant_uploads/').$restaurant_details->id.'/'.'Logo/'.$restaurant_details->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_details->id.'/'.'Logo/'.$restaurant_details->image : BASE_URL.UPLOADS_PATH_LOGO.$restaurant_details->image),

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
                    'wallet_amount'=>$key->wallet_amount,
                );
            }
        }

        if (count($upcoming_order_list) != 0 || count($order_list) != 0) {
            $response_array = array('status' => true, 'past_orders' => $order_list, 'upcoming_orders' => $upcoming_order_list,'wallet_balance'=>$user_wallet);
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

        if (!empty($request_detail) && isset($request_detail->order_id) && !empty($request_detail->order_id)) {

            $order_id = $request_detail->order_id;

            $request_id = $request_detail->id;

            $ordered_time = $request_detail->ordered_time;
            $schedule_time = $request_detail->ScheduledAt;

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

            $response_array = array('status' => true, 'request_id' => $request_id, 'ordered_time' => $ordered_time, 'schedule_time' => $schedule_time, 'order_id' => $order_id, 'restaurant_detail' => $restaurant_detail, 'user_detail' => $user_detail, 'address_detail' => $address_detail, 'bill_detail' => $bill_detail, 'food_detail' => $food_detail, 'request_status' => $request_status);
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

    public function get_rest_promo_list(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $rest_id = $request->restaurant_id;

        //get promo list
        $get_promo = $this->promocode->where('coupon_code.status', 1)
        ->where('restaurant_id',$rest_id)
        ->orwhere('restaurant_id',null)
		->leftjoin('restaurants', 'restaurants.id', '=', 'coupon_code.restaurant_id')
		->select('coupon_code.*','restaurants.image')
            ->whereDate('available_from', '<=', Carbon::now())
            ->whereDate('valid_till', '>=', Carbon::now())
            ->get();
        foreach($get_promo as $promo){
        if($promo->image == null){
        $promo->image = 'off.png';
        }
        }
        $response_array = array('status' => true, 'promo_list' => $get_promo);
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function payment_callback(Request $request)
    {

        $payment = mollie()->payments()->get($request->id);

        if ($payment->isPaid()) {
            $payment_row = DB::table('payments')->where('payment_id', $request->id)->first();
            if (!empty($payment_row)) {
                if (isset($payment_row->is_paid) && $payment_row->is_paid == 0) {
                    DB::table('payments')->where('payment_id', $request->id)->update(['is_paid' => 1, 'paid_at' => Carbon::now()]);
                    DB::table('requests')->where('id', $payment_row->request_id)->update(['status' => 0, 'is_paid' => 1]);
                    $response_array = array('status' => true, 'message' => 'Payment status updated');

                    sendNewOrderEmail2Restaurant($request->id);
                    sendNewOrderEmail2Customer($request->id);
                } else {
                    $response_array = array('status' => true, 'message' => 'Payment status already updated');
                }
            } else {
                $response_array = array('status' => true, 'message' => 'Payment id not found');
            }
        }

        return response()->json($response_array, 200);
    }

    public function get_cancellation_reasons()
    {
        $reasons = DB::table('cancellation_reason')->select('reason')
            ->where('cancellation_for', 2)->where('status', 1)->pluck('reason')->toArray();

        return response()->json(['status' => true, 'message' => 'Success', 'reasons' => $reasons], 200);
    }


    public function get_user_language(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $user_language_data = DB::table('user_preferred_lang')->where('user_id', $user_id)->get();

        $response_array = array('status' => true, 'user_language' => $user_language_data);
        $response = response()->json($response_array, 200);
        return $response;
    }


    public function set_user_language(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $user_language_data = DB::table('user_preferred_lang')->where('user_id', $user_id)->get();

        if (count($user_language_data) != 0) {
            DB::table('user_preferred_lang')->where('user_id', $user_id)->update(['preferred_lang' => $request->language]);
            $response_array = array('status' => true, 'message' => 'Preferred Language updated successfully');
            $response = response()->json($response_array, 200);
            return $response;
        } else {
            DB::table('user_preferred_lang')->insert(['preferred_lang' => $request->language, 'user_id' => $user_id]);
            $response_array = array('status' => true, 'message' => 'Added to Preferred Language');
            $response = response()->json($response_array, 200);
            return $response;
        }
    }

    public function content(Request $request, $type)
    {
        $content = DB::table('content')->select('value')->where('key', $type)->first();
        return $content->value ?? '';
    }


    public function get_near_restaurant(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
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

            $delivery_type = $request->delivery_type ?? 0;

            $source_lat = $request->lat;
            $source_lng = $request->lng;

            $restaurants = $this->restaurants;

            $nearbyRestaurantIDs = Restaurant::nearDistance($source_lat, $source_lng);

            //geofencing for restaurant
            $data = $restaurants->with('Cuisines')->where('status', 1)
                ->select('restaurants.*')
                ->whereIn('id', $nearbyRestaurantIDs)
                ->orderby('is_open','desc')
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

                $restaurant_list[] = array(
                    'id' => $d->id,
                    'name' => $this->updateRestaurantName($d, $request),
                    'image' => File::exists(public_path('restaurant_uploads/').$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->image: (File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Logo/'.$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Logo/'.$d->image : BASE_URL.UPLOADS_PATH_LOGO.$d->image),
                    'banner' => File::exists(public_path('restaurant_uploads/').$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->banner:(File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Banner/'.$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Banner/'.$d->banner: BASE_URL.UPLOADS_PATH_BANNER.$d->banner),
                    'discount' => $d->discount,
                    'rating' => (float) $rating_statistics['rating_percent'], //$d->rating,
                    'is_open' => $is_open, // 1- Open , 0 - Close
                    'cuisines' => $rcuisines,
                    'travel_time' => $d->estimated_delivery_time,
                    'delivery_type' => json_decode($d->delivery_type),
                    'price' => '',
                    'is_favourite' => $is_favourite,
                    'address' => $d->address,
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
        }

        $response = response()->json($response_array, 200);
        return $response;
    }
    public function get_near_brands(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
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

            
            $source_lat = $request->lat;
            $source_lng = $request->lng;

            $restaurants = $this->restaurants;

            $nearbyRestaurantIDs = Restaurant::nearbrandDistance($source_lat, $source_lng);

            $data = $restaurants
                ->select('restaurants.*')
                ->whereIn('id', $nearbyRestaurantIDs)
                ->get();
                $brand_list = array();
                foreach($data as $d){
                    if($d->brand_name != 0){
                    $brand_name = DB::table('brands')->where('id',  $d->brand_name)->first();
                    $b_rest = $restaurants->whereIn('id', $nearbyRestaurantIDs)->where('brand_name',$d->brand_name)->get();
                    if(count($b_rest) == 1){
                   
                    $brand_list[]=array(
                        'id'=>$brand_name->id,
                        'name'=>$brand_name->name,
                        'status'=>$brand_name->status,
                        'rank'=>$brand_name->rank,
                        'image' => File::exists(public_path('restaurant_uploads/').$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->image: (File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Logo/'.$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Logo/'.$d->image : BASE_URL.UPLOADS_PATH_LOGO.$d->image),
                            'banner' => File::exists(public_path('restaurant_uploads/').$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->banner:(File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Banner/'.$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Banner/'.$d->banner: BASE_URL.UPLOADS_PATH_BANNER.$d->banner),
                    
                        'count'=>count($b_rest),
                        'restaurant'=>$d->id,
                        
                    );
                       
                    }
                    else{
                        $brand_list[]=array(
                            'id'=>$brand_name->id,
                            'name'=>$brand_name->name,
                            'status'=>$brand_name->status,
                            'rank'=>$brand_name->rank,
                            'image' => File::exists(public_path('restaurant_uploads/').$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->image: (File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Logo/'.$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Logo/'.$d->image : BASE_URL.UPLOADS_PATH_LOGO.$d->image),
                            'banner' => File::exists(public_path('restaurant_uploads/').$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->banner:(File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Banner/'.$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Banner/'.$d->banner: BASE_URL.UPLOADS_PATH_BANNER.$d->banner),
                    
                            'count'=>count($b_rest),
                            'restaurant'=>null,
                            
                        );
                    }
                }
                }
                $unique_array = [];
                foreach($brand_list as $element) {
                    $uni = $element['id'];
                    $unique_array[$uni] = $element;

                }
                $u_brand_list = array_values($unique_array);
                
                $is = array();
                foreach($u_brand_list as $key=>$row){
                    $is[$key] = $row['rank'];
                }
     
                array_multisort($is, SORT_ASC, $u_brand_list);

                $size = sizeof($u_brand_list);

                

                if ($size != 0) {
                $response_array = array('status' => true, 'brands' => $u_brand_list);
            } else {
                $response_array = array('status' => false,'message' => 'No Brands Found');
            }
        }

        $response = response()->json($response_array, 200);
        return $response;
    }
    public function get_brand_rest(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'brand_id' => 'required',
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

            
            $id = $request->brand_id;
           
     
            $source_lat = $request->lat;
            $source_lng = $request->lng;

            $restaurants = $this->restaurants;
         
            $nearbyRestaurantIDs = Restaurant::nearbrandDistance($source_lat, $source_lng);
           
            $data = $restaurants
                ->select('restaurants.*')
                ->whereIn('id', $nearbyRestaurantIDs)
                ->where('brand_name', $id)
                ->get();
                
            $restaurant_list = array();
            $j = 0;
            foreach ($data as $d) {

               
               
                $rcuisines = array();
                $i = 0;
                foreach ($d->Cuisines as $r_cuisines) {
                    if ($i < 2) 
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

                $restaurant_list[] = array(
                    'id' => $d->id,
                    'name' => $this->updateRestaurantName($d, $request),
                    'image' => File::exists(public_path('restaurant_uploads/').$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->image: (File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Logo/'.$d->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Logo/'.$d->image : BASE_URL.UPLOADS_PATH_LOGO.$d->image),
                    'banner' => File::exists(public_path('restaurant_uploads/').$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->banner:(File::exists(public_path('restaurant_uploads/').$d->id.'/'.'Banner/'.$d->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d->id.'/'.'Banner/'.$d->banner: BASE_URL.UPLOADS_PATH_BANNER.$d->banner),
                    
                    'discount' => $d->discount,
                    'rating' => (float) $rating_statistics['rating_percent'], //$d->rating,
                    'is_open' => $is_open, // 1- Open , 0 - Close
                    'cuisines' => $rcuisines,
                    'travel_time' => $d->estimated_delivery_time,
                    'delivery_type' => json_decode($d->delivery_type),
                    'price' => '',
                    'is_favourite' => $is_favourite,
                    'address' => $d->address,
                    
                );
                $j++;

                // }
            }
            $schedule = DB::table('settings')->where('key_word', 'schedule')->value('value');

            $is = array();
            foreach($restaurant_list as $key=>$row){
                $is[$key] = $row['is_open'];
            }
 
            array_multisort($is, SORT_DESC, $restaurant_list);
            if ($data) {
                $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'schedule' => $schedule);
            } else {
                $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found');
            }
        }

        $response = response()->json($response_array, 200);
        return $response;
    }
    public function get_more_restaurant(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
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

            $delivery_type = $request->delivery_type ?? 0;

            $source_lat = $request->lat;
            $source_lng = $request->lng;

            $restaurants = $this->restaurants;

            $nearbyRestaurantIDs = Restaurant::moreDistance($source_lat, $source_lng);
            //geofencing for restaurant
            /*    $data = $restaurants->with('Cuisines')->where('status', 1)
                ->select('restaurants.*')
                ->whereIn('id', $nearbyRestaurantIDs)
                ->get();
*/
            foreach ($nearbyRestaurantIDs as $res) { //print_R($res);
                $data = $restaurants->with('Cuisines')->where('status', 1)
                    ->select('restaurants.*')
                    ->where('id', $res->id)->orderBy('is_open')
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
                            'name' => $r_cuisines->name,
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
                    'name' => $this->updateRestaurantName($d[0], $request),
                    'image' => File::exists(public_path('restaurant_uploads/').$d[0]->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d[0]->image: (File::exists(public_path('restaurant_uploads/').$d[0]->id.'/'.'Logo/'.$d[0]->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d[0]->id.'/'.'Logo/'.$d[0]->image : BASE_URL.UPLOADS_PATH_LOGO.$d[0]->image),
                    'banner' => File::exists(public_path('restaurant_uploads/').$d[0]->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d[0]->banner:(File::exists(public_path('restaurant_uploads/').$d[0]->id.'/'.'Banner/'.$d[0]->banner)? BASE_URL.RESTAURANT_UPLOADS_PATH.$d[0]->id.'/'.'Banner/'.$d[0]->banner: BASE_URL.UPLOADS_PATH_BANNER.$d[0]->banner),
                    
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
                );
                $j++;

                // }
            }
            // $restaurantcolection = collect($restaurant_list)->sortByDesc('is_open')->toArray();

            if ($data) {
                $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'schedule' => $schedule);
            } else {
                $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found');
            }
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_near_address(Request $request){

        $lat = $request->lat;
        $lng = $request->lng;
        $user_id = $request->user_id;
    
        $address = DB::table('delivery_address')->where('user_id',$user_id)->get();
        $near_address = [];
        foreach($address as $ad){
    
            $distance = vincentyGreatCircleDistance2($lat, $lng, $ad->lat, $ad->lng);
    
            if($distance  <= 0.1){
                $near_address[]=[
                    'id'=>$ad->id,
                    'user_id'=>$ad->user_id,
                    'address'=>$ad->address,
                    'lat'=>$ad->lat,
                    'lng'=>$ad->lng,
                    'flat_no'=>$ad->flat_no,
                    'landmark'=>$ad->landmark,
                    'is_default'=>$ad->is_default,
                    'type'=>$ad->type,
                    'distance'=>$distance
                ];
            }
    
        }
        if(count($near_address) >=1){
            foreach($near_address as $key=>$row){
                $is[$key] = $row['distance'];
            }
        
            array_multisort($is, SORT_ASC, $near_address);
            $response_array = array('status' => true, 'address' => $near_address[0]);
           
        
            }else{
                $response_array = array('status' => false, 'message' => 'No Address Found');
        
            }
            return json_encode($response_array);
    
       }

       public function delete_user_address(Request $request){

        $address_id = $request->address_id;

        $get_address = DB::table('delivery_address')->where('id',$address_id)->first();

        if(!$get_address){
            $response_array = array('status' => false, 'message' => 'Address Not Found');

        }else{
            DB::table('delivery_address')->where('id',$address_id)->delete();

            $response_array = array('status' => true, 'message' => 'Address Deleted Successfully');

        }

        return json_encode($response_array);

    }
}
