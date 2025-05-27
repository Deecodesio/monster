<?php

namespace App\Http\Controllers\admin;

use Validator;
use Hash;
use DB;
use Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\BaseController;
use Carbon\Carbon;
use App\Http\Controllers\Order;
use File;

class OrderController extends BaseController
{

    public function fetch_enquirys(Request $request)
    {
        $enquirys = DB::table('enquiry')
            ->leftJoin('users', 'users.id', '=', 'enquiry.user_id')
            ->select('enquiry.id as enquiry_id', 'enquiry.*', 'users.*')
            ->get();
        $gapi = DB::table('settings')->where('key_word', 'google_api_key')->first();

        foreach ($enquirys as $order) {
            $date = Carbon::parse($order->created_at);
            $order->created_at = $date->isoFormat('Do MMMM YYYY, h:mm:ss a');

            $ad = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$order->lat,$order->lng&sensor=true&key=$gapi->value";
            $json = file_get_contents($ad);
            $details = json_decode($json, TRUE);

            $addr_comp = $details['results'][1]['address_components'];
            $state = "";
            foreach ($addr_comp as $ac) {
                $types_array = $ac['types'];
                foreach ($types_array as $key) {
                    if ($key == "administrative_area_level_1") {
                        $state = $ac['long_name'];
                        $order->delivery_address = $state;
                    }
                }
            }
        }
        return response()->json($enquirys);
    }

    public function Orders_list(Request $request, $type)
    {
        $ad = DB::table('admin')->where('email', $request->user)->first();
        $ad2 = DB::table('admin_role')->where('email', $request->user)->first();
        if ($type == 'new') $status = [0];
        if ($type == 'scheduled') $status = [0];
        if ($type == 'processing') $status = [1, 2, -3];
        if ($type == 'pickup') $status = [3, 4, 5];
        if ($type == 'delivered') $status = [6, 7];
        if ($type == 'cancelled') $status = [9, 10, 11, 12, 13, 14];
        if ($type == 'failed') $status = [-2];
        if ($type == 'abandon') $status = [-1];
        if ($type == 'refund') $status = [12, 13, 14];



        if ($type == 'refund') {
            if ($ad) {
                $data = DB::table('requests')
                    ->whereIn('requests.status', $status)
                    ->where('requests.is_cod', 0)
                    ->leftJoin('users', 'users.id', '=', 'requests.user_id')
                    ->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
                    ->leftJoin('business_type', 'business_type.id', '=', 'restaurants.business_type')
                    ->leftJoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                    ->select('requests.*', 'business_type.name as business_name', 'requests.wallet_amount as wallet_amounts', 'users.name as user_name', 'delivery_partners.name as rider_name', 'restaurants.restaurant_name', 'users.phone', 'users.device_type', 'requests.status')
                    ->orderBy('requests.id', 'desc')->get();
            } else {
                $datas =  array_map('intval', json_decode($ad2->restaurant_id));
                if (count($datas) >= 1) {
                    $data = DB::table('requests')
                        ->whereIn('requests.restaurant_id', $datas)
                        ->whereIn('requests.status', $status)
                        ->where('requests.is_cod', 0)
                        ->leftJoin('users', 'users.id', '=', 'requests.user_id')
                        ->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
                        ->leftJoin('business_type', 'business_type.id', '=', 'restaurants.business_type')
                        ->leftJoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                        ->select('requests.*', 'business_type.name as business_name', 'requests.wallet_amount as wallet_amounts', 'users.name as user_name', 'delivery_partners.name as rider_name', 'restaurants.restaurant_name', 'users.phone', 'users.device_type', 'requests.status')
                        ->orderBy('requests.id', 'desc')->get();
                } else {
                    $data = DB::table('requests')
                        ->whereIn('requests.status', $status)
                        ->where('requests.is_cod', 0)
                        ->leftJoin('users', 'users.id', '=', 'requests.user_id')
                        ->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
                        ->leftJoin('business_type', 'business_type.id', '=', 'restaurants.business_type')
                        ->leftJoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                        ->select('requests.*', 'business_type.name as business_name', 'requests.wallet_amount as wallet_amounts', 'users.name as user_name', 'delivery_partners.name as rider_name', 'restaurants.restaurant_name', 'users.phone', 'users.device_type', 'requests.status')
                        ->orderBy('requests.id', 'desc')->get();
                }
            }
        } else {
            if ($ad) {
                $data = DB::table('requests')
                    ->whereIn('requests.status', $status)
                    ->leftJoin('users', 'users.id', '=', 'requests.user_id')
                    ->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
                    ->leftJoin('business_type', 'business_type.id', '=', 'restaurants.business_type')
                    ->leftJoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                    ->select('requests.*', 'business_type.name as business_name', 'requests.wallet_amount as wallet_amounts', 'users.name as user_name', 'delivery_partners.name as rider_name', 'restaurants.restaurant_name', 'users.phone', 'users.device_type', 'requests.status')
                    ->orderBy('requests.id', 'desc')->get();
            } else {
                $datas =  array_map('intval', json_decode($ad2->restaurant_id));
                if (count($datas) >= 1) {
                    $data = DB::table('requests')
                        ->whereIn('requests.status', $status)
                        ->whereIn('requests.restaurant_id', $datas)
                        ->leftJoin('users', 'users.id', '=', 'requests.user_id')
                        ->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
                        ->leftJoin('business_type', 'business_type.id', '=', 'restaurants.business_type')
                        ->leftJoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                        ->select('requests.*', 'business_type.name as business_name', 'requests.wallet_amount as wallet_amounts', 'users.name as user_name', 'delivery_partners.name as rider_name', 'restaurants.restaurant_name', 'users.phone', 'users.device_type', 'requests.status')
                        ->orderBy('requests.id', 'desc')->get();
                } else {
                    $data = DB::table('requests')
                        ->whereIn('requests.status', $status)
                        ->leftJoin('users', 'users.id', '=', 'requests.user_id')
                        ->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
                        ->leftJoin('business_type', 'business_type.id', '=', 'restaurants.business_type')
                        ->leftJoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                        ->select('requests.*', 'business_type.name as business_name', 'requests.wallet_amount as wallet_amounts', 'users.name as user_name', 'delivery_partners.name as rider_name', 'restaurants.restaurant_name', 'users.phone', 'users.device_type', 'requests.status')
                        ->orderBy('requests.id', 'desc')->get();
                }
            }
        }
        foreach ($data as $datas) {
            if ($datas->delivery_address != "") {
                $decodedText = html_entity_decode($datas->delivery_address);
                $datas->delivery_address = $decodedText;
            }
        }
        return response()->json($data);
    }
    public function new_update(Request $request)
    {
        //  dd( $request->request_id);
        $request_id = $request->request_id;

        $status = $request->status;

        $query = DB::table('requests')->where('id', $request_id)->update(['status' => $status]);

        if ($status == 12) {
            $get = DB::table('requests')->where('id', $request_id)->first();

            $amount = $get->bill_amount - $get->offer_discount;
            $user_id = $get->user_id;
            $user_detail = $this->users->find($user_id);

            $status_entry = [];

            $status_entry[] = [
                'user_id' => $user_id,
                'amount' => $amount,
                'topup_code' => '',
                'type' => 1, //1-credit 2-debit
                'status' => 1,

            ];
            DB::table('user_wallet_history')->insert($status_entry);

            $this->users->find($user_id)->increment('wallet_amount', $amount);
        }



        if ($query == 1) {
            if ($status == 0) {
                $message = "Order Moved to  New Order";
                $status = true;
            } elseif ($status == 7) {
                $message = "Order Moved to  Delivery Order";
                $status = true;
            } else {
                $message = "Order Moved to  Cancelled Order";
                $status = true;
            }
        } else {
            $message = "Something Went Wrong";
            $status = false;
        }
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function view_order(Request $request)
    {

        $request_id = $request->id;
        $data = DB::table('requests')
            ->where('requests.id', $request_id)
            ->leftjoin('users', 'users.id', '=', 'requests.user_id')
            ->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
            ->select('requests.id as request_id',  'requests.tax as rtax', 'requests.status as order_status', 'requests.wallet_amount as wallet_amounts', 'users.name as user_name', 'users.email as user_email', 'users.phone as user_phone', 'restaurants.email as restaurants_email', 'requests.image as req_image', 'requests.*', 'users.*', 'restaurants.*')
            ->first();


        $date = Carbon::parse($data->ordered_time);
        $data->created_at = $date->isoFormat('Do MMMM YYYY, h:mm:ss a');


        if ($data->address != "") {
            $decodedText = html_entity_decode($data->address);
            $data->address = $decodedText;
        }

        if ($data->delivery_address != "") {
            $decodedText = html_entity_decode($data->delivery_address);
            $data->delivery_address = $decodedText;
        }
        $layout = DB::table('business_type')->where('id', $data->business_type)->first();

        $layout_id = DB::table('layout')->where('id', $layout->layout_id)->first();
        if ($layout_id->id == 3) {
            if ($data->package_type) {
                $types = DB::table('package_type')->where('id', $data->package_type)->first();
                $data->package_type = $types->name;
            }

            if ($data->req_image) {
                if ($data->req_image != "undefined" || $data->req_image != '') {
                    if (file_exists(public_path('/') . UPLOADS_PATH . $data->req_image)) {
                        $data->req_image = BASE_URL . UPLOADS_PATH . $data->req_image;
                        $ext = pathinfo($data->req_image, PATHINFO_EXTENSION);
                        if ($ext == 'docx' || $ext == 'docx') {
                            $data->req_image = BASE_URL . 'doc_image.jpg';
                        } elseif ($ext == 'txt') {
                            $data->req_image = BASE_URL .  'txt_image.png';
                        } elseif ($ext == 'pdf') {
                            $data->req_image = BASE_URL . 'pdf_image1.jpg';
                        } else {
                            $data->req_image = $data->req_image;
                        }
                    } else {
                        $data->req_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
                    }
                }

                $rt_food = [];
                $rt_food[] = [
                    'type' => $data->package_type,
                    'image' => $data->req_image,
                    'description' => $data->instruction,
                    'quantity' => 1,
                    'price' => $data->bill_amount,
                ];
            }
        } else {

            $food_list = DB::table('request_detail')->where('request_id', $request_id)
                ->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
                ->select('request_detail.id as detail_id', 'request_detail.*', 'food_list.*')
                ->get();

            $rt_food = [];
            foreach ($food_list as $fl) {
                if ($layout_id->id == 2) {
                    if ($fl->image) {
                        if ($fl->image != 'undefined') {
                            $img = File::exists(public_path('product_image/') . $fl->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $fl->image : (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Product/' . $fl->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Product/' . $fl->image : (File::exists(public_path('common_images/') . $fl->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $fl->image : BASE_URL  . $fl->image));
                        } else {
                            $img =  BASE_URL  . "no-storeprod.jpg";
                        }
                    } else {

                        $img =  BASE_URL  . "no-storeprod.jpg";
                    }
                } else {
                    if ($fl->image) {
                        if ($fl->image != 'undefined') {
                            $img = File::exists(public_path('product_image/') . $fl->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $fl->image : (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Product/' . $fl->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Product/' . $fl->image : (File::exists(public_path('common_images/') . $fl->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $fl->image : BASE_URL  . $fl->image));
                        } else {
                            $img =  BASE_URL  . "fork.svg";
                        }
                    } else {
                        $img =  BASE_URL  . "fork.svg";
                    }
                }




                //     $img = BASE_URL .$fl->image;
                // } else {
                //     $img= BASE_URL ."fork.svg";
                // }
                $adds = DB::table('requestdetail_addons')->where('requestdetail_id', $fl->detail_id)->get();
                $to_display = [];
                if ($adds) {
                    foreach ($adds as $ad) {

                        $to_display[] = $ad->name . " x " . $ad->quantity;
                    }
                }
                $string_version = implode(',', $to_display);
                $size = DB::table('food_quantity')->where('id', $fl->food_quantity)->first();
                if ($size) {
                    $sel_size = $size->name;
                } else {
                    $sel_size = '';
                }

                $rt_food[] = [
                    'Name' => $fl->name,
                    'description' => $fl->description,
                    'quantity' => $fl->quantity,
                    'price' => $fl->food_quantity_price,
                    'image' => $img,
                    'add_ons' => $string_version,
                    'size' => $sel_size
                ];
            }

            if ($data->req_image) {
                if (file_exists(public_path('/uploads') . '/' . $data->req_image)) {

                    $data->req_image =  BASE_URL . UPLOADS_PATH . $data->req_image;
                } else {
                    $data->req_image = '';
                }
            }
        }






        if ($data->order_status == "0") {
            $status = "New Order";
        }
        if ($data->order_status == "1") {
            $status = "Order Accepted";
        }
        if ($data->order_status == "2") {
            $status = "Driver Assigned";
        }
        if ($data->order_status == "3") {
            $status = "Delivered to Driver";
        }
        if ($data->order_status == "4") {
            $status = "Towards Customer";
        }
        if ($data->order_status == "5") {
            $status = "Reached Customer";
        }
        if ($data->order_status == "6") {
            $status = "Delivered To Customer";
        }
        if ($data->order_status == "7") {
            $status = "Completed";
        }
        if ($data->order_status >= "9") {
            $status = "Cancelled";
        }

        if ($data->order_status == "-1") {
            $status = "Failed";
        }
        if ($data->order_status == "-2") {
            $status = "Failed";
        }
        if ($data->order_status == "-3") {
            $status = "On Hold";
        }

        $order_status = $status;
        $message = $data;
        $food = $rt_food;
        $status = true;

        $response_Array = json_encode(['message' => $message, 'status' => $status, 'food' => $food, 'order_status' => $order_status, 'pharmacy' => $layout->p_status, 'layout' => $layout_id->id]);
        return $response_Array;
    }

    public function user_payout(Request $request)
    {

        $request_id = $request->request_id;

        $status = $request->status;

        $type = "user";
        if ($status == 13) {

            $get = DB::table('requests')->where('id', $request_id)->first();

            $amount = $get->bill_amount - $get->offer_discount;
            $id = $get->user_id;

            $type = $type;
            $amount =  $amount;
            $id = $id;
            $request_id = $request_id;
            $status = true;

            $response_Array = json_encode(['status' => $status, 'type' => $type, 'id' => $id, 'request_id' => $request_id, 'amount' => $amount]);
            return $response_Array;
        } else {
            $message = "Could not make Payment";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }
    }
    public function add_userpayout(Request $request)
    {


        $request_id = $request->request_id;
        $type = $request->type;


        $status_entry = array();

        $status_entry[] = array(
            'user_id' => $request->id,
            'request_id' => $request_id,
            'payout_amount' => $request->amount,
            'description' => $request->description,
            'status' => 1
        );
        DB::table('user_payout_history')->insert($status_entry);
        $query = DB::table('requests')->where('id', $request_id)->update(['status' => 14]);

        $message = "Paid Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;

        // return redirect('admin/orders/refund')->with('success', trans('constants.paid_success_msg'));

    }
    public function get_neworder_alert(Request $request)
    {
        $data = $this->foodrequest->where('status', 0)
            ->first();
        $get_data = $this->foodrequest->where('status', 0)
            ->get();

        $response_Array = json_encode(['data' => $data, 'all' => $get_data]);
        return $response_Array;
        // return response()->json( $data);
    }

    function printOrderBill(Request $request)
    {
        //   dd($request->id);
        $data = DB::table('requests')
            ->where('requests.id', $request->id)
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
            ->select('requests.id as request_id', 'requests.status as order_status', 'users.name as user_name', 'users.email as user_email', 'users.phone as user_phone', 'restaurants.email as restaurants_email', 'requests.*', 'users.*', 'restaurants.*')
            ->first();

        $food_list = DB::table('request_detail')->where('request_id', $request->id)
            ->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
            ->get();
        // dd($food_list);
        $rt_food = [];
        foreach ($food_list as $fl) {
            if (file_exists(public_path('/') . $fl->image)) {
                $img = BASE_URL . $fl->image;
            } else {
                $img = BASE_URL . "fork.svg";
            }

            $rt_food[] = [
                'Name' => $fl->name,
                'description' => $fl->description,
                'quantity' => $fl->quantity,
                'price' => $fl->food_quantity_price,
                'image' => $img,

            ];
        }

        if ($data->order_status == "0") {
            $status = "New Order";
        }
        if ($data->order_status == "1") {
            $status = "Order Accepted";
        }
        if ($data->order_status == "2") {
            $status = "Driver Assigned";
        }
        if ($data->order_status == "3") {
            $status = "Delivered to Driver";
        }
        if ($data->order_status == "4") {
            $status = "Towards Customer";
        }
        if ($data->order_status == "5") {
            $status = "Reached Customer";
        }
        if ($data->order_status == "6") {
            $status = "Delivered To Customer";
        }
        if ($data->order_status == "7") {
            $status = "Completed";
        }
        if ($data->order_status >= "9") {
            $status = "Cancelled";
        }

        if ($data->order_status == "-1") {
            $status = "Failed";
        }
        if ($data->order_status == "-2") {
            $status = "Failed";
        }
        $order_status = $status;
        $message = $data;
        $food = $rt_food;
        $status = true;


        $response_Array = json_encode(['message' => $message, 'status' => $status, 'food' => $food, 'order_status' => $order_status]);


        return $response_Array;
    }


    public function fetch_enquiry_order(Request $request)
    {

        $request_id = $request->id;
        $enq = DB::table('enquiry')->where('enquiry.id', $request_id)
            ->leftjoin('users', 'users.id', '=', 'enquiry.user_id')
            ->first();
        $date = Carbon::parse($enq->created_at);
        $enq->ordered_time = $date->isoFormat('Do MMMM YYYY, h:mm:ss a');

        $enq->user_name = $enq->name;
        $enq->user_phone = $enq->phone;
        $enq->user_email = $enq->email;


        $products = explode(",", $enq->products);
        $quantity = explode(",", $enq->quantity);
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
        $food = $rt_food;
        $status = true;
        $response_Array = json_encode(['status' => $status, 'food' => $food, 'request_details' => $enq]);
        return $response_Array;
    }
}
