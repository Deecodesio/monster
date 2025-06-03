<?php

namespace App\Http\Controllers\api;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Coupon;
use App\Http\Controllers\Order;
use DB;
use Carbon\Carbon;
use File;

class OrderController extends BaseController
{
    public function get_address_detail(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            array(
                'request_id' => 'required'
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $request_id = $request->request_id;

            $request_detail = DB::table('requests')->where('id', $request_id)->first();

            $order_id = $request_detail->order_id;

            $ordered_time = $request_detail->ordered_time;

            $restaurant_detail = $this->restaurants::where('id', $request_detail->restaurant_id)->first();

            $user_detail = $this->users::where('id', $request_detail->user_id)->first();

            $address_detail = array();

            $request_status = $request_detail->status;

            $business = DB::table('business_type')->where('id', $restaurant_detail->business_type)->first();


            if ($business->layout_id == 3) {
                $address_detail[] = array(
                    'd_address' => $request_detail->delivery_address,
                    's_address' => $restaurant_detail->address,
                    'pickup_address' => $request_detail->pickup_address,
                    'd_lat' => $request_detail->d_lat,
                    'd_lng' => $request_detail->d_lng,
                    'pickup_lat' => $request_detail->pickup_lat,
                    'pickup_lng' => $request_detail->pickup_lng,
                    's_lat' => $restaurant_detail->lat,
                    's_lng' => $restaurant_detail->lng,
                    'pickup_name' => $request_detail->pickup_name,
                    'pickup_phone' => $request_detail->pickup_phone,
                    'drop_name' => $request_detail->drop_name,
                    'drop_phone' => $request_detail->drop_phone

                );
            } else {

                $address_detail[] = array(
                    'd_address' => $request_detail->delivery_address,
                    's_address' => $restaurant_detail->address,
                    'd_lat' => $request_detail->d_lat,
                    'd_lng' => $request_detail->d_lng,
                    's_lat' => $restaurant_detail->lat,
                    's_lng' => $restaurant_detail->lng
                );
            }

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
                    'is_veg' => $d->is_veg
                );
            }

            $bill_detail[] = array(
                'item_total' => $request_detail->item_total,
                'offer_discount' => $request_detail->offer_discount,
                'packaging_charge' => $request_detail->restaurant_packaging_charge,
                'tax' => $request_detail->tax,
                'delivery_charge' => $request_detail->delivery_charge,
                'bill_amount' => $request_detail->bill_amount,
                'cod' => $request_detail->is_cod,
                'paid' => $request_detail->is_paid
            );

            $response_array = array('status' => true, 'request_id' => $request_id, 'layout_id' => $business->layout_id, 'ordered_time' => $ordered_time, 'order_id' => $order_id, 'restaurant_detail' => $restaurant_detail, 'user_detail' => $user_detail, 'address_detail' => $address_detail, 'bill_detail' => $bill_detail, 'food_detail' => $food_detail, 'request_status' => $request_status);
        }

        $response = response()->json($response_array, 200);
        return $response;
    }


    public function get_order_status(Request $request)
    {

        // $request_id = $request->request_id;

        $delivery_boy_id = $request->header('authId');

        $request_detail = DB::table('requests')
            ->where('delivery_boy_id', $delivery_boy_id)
            // ->where('status','!=',3)
            // ->where('status','!=',4)
            // ->where('status','!=',5)
            // ->where('status','!=',6)
            ->where('status', '!=', -1)
            ->where('status', '!=', 7)
            ->where('status', '!=', 9)
            ->where('status', '!=', 10)
            ->where('status', '!=', 11)
            ->first();

        if ($request_detail) {

            $order_id = $request_detail->order_id;
            $tips = $request_detail->tips;

            $request_id = $request_detail->id;

            $ordered_time = $request_detail->ordered_time;

            $restaurant_detail = $this->restaurants::where('id', $request_detail->restaurant_id)->first();
            $restaurant_detail->image = File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image);



            $user_detail = DB::table('users')->where('id', $request_detail->user_id)->first();

            $address_detail = array();

            $request_status = $request_detail->status;

            $address_detail[] = array(
                'd_address' => $request_detail->delivery_address,
                's_address' => $restaurant_detail->address,
                'd_lat' => $request_detail->d_lat,
                'd_lng' => $request_detail->d_lng,
                's_lat' => $restaurant_detail->lat,
                's_lng' => $restaurant_detail->lng
            );

            $food_detail = array();
            $bill_detail = array();

            $data = DB::table('request_detail')
                ->where('request_detail.request_id', $request_id)
                ->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
                ->select('request_detail.quantity as quantity', 'food_list.name as food', 'food_list.price as price_per_quantity', 'food_list.is_veg as is_veg')
                ->get();

            foreach ($data as $d) {
                $food_detail[] = array(
                    'name' => $d->food,
                    'quantity' => $d->quantity,
                    'price' => $d->quantity * $d->price_per_quantity,
                    'is_veg' => $d->is_veg
                );
            }

            $bill_detail[] = array(
                'item_total' => $request_detail->item_total,
                'offer_discount' => $request_detail->offer_discount,
                'packaging_charge' => $request_detail->restaurant_packaging_charge,
                'tax' => $request_detail->tax,
                'delivery_charge' => $request_detail->delivery_charge,
                'bill_amount' => $request_detail->bill_amount
            );

            $response_array = [
                'status' => true,
                'request_id' => $request_id,
                'tips' => $tips,
                'is_cod' => $request_detail->is_cod,
                'ordered_time' => $ordered_time,
                'order_id' => $order_id,
                'restaurant_detail' => $restaurant_detail,
                'user_detail' => $user_detail,
                'address_detail' => $address_detail,
                'bill_detail' => $bill_detail,
                'food_detail' => $food_detail,
                'request_status' => $request_status
            ];
        } else {
            $response_array = array('status' => false, 'message' => 'No orders available');
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function get_order_status_new(Request $request)
    {

        // $request_id = $request->request_id;

        $delivery_boy_id = $request->header('authId');

        $request_detail = DB::table('requests')
            ->where('delivery_boy_id', $delivery_boy_id)
            ->where('status', '!=', 3)
            ->where('status', '!=', 4)
            ->where('status', '!=', 5)
            ->where('status', '!=', 6)
            ->where('status', '!=', 7)
            ->where('status', '!=', 9)
            ->where('status', '!=', 10)
            ->where('status', '!=', 11)
            ->where('status', '!=', -1)
            ->orderBy('id', 'desc')
            ->first();

        if ($request_detail) {

            $order_id = $request_detail->order_id;
            $tips = $request_detail->tips;

            $request_id = $request_detail->id;

            $ordered_time = $request_detail->ordered_time;

            $restaurant_detail = $this->restaurants::where('id', $request_detail->restaurant_id)->first();
            $restaurant_detail->image =  File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image);

            $business = DB::table('business_type')->where('id', $restaurant_detail->business_type)->first();

            $user_detail = DB::table('users')->where('id', $request_detail->user_id)->first();

            $address_detail = array();

            $request_status = $request_detail->status;
            if ($business->layout_id == 3) {
                $address_detail[] = array(
                    'd_address' => $request_detail->delivery_address,
                    's_address' => $restaurant_detail->address,
                    'pickup_address' => $request_detail->pickup_address,
                    'd_lat' => $request_detail->d_lat,
                    'd_lng' => $request_detail->d_lng,
                    'pickup_lat' => $request_detail->pickup_lat,
                    'pickup_lng' => $request_detail->pickup_lng,
                    's_lat' => $restaurant_detail->lat,
                    's_lng' => $restaurant_detail->lng,
                    'pickup_name' => $request_detail->pickup_name,
                    'pickup_phone' => $request_detail->pickup_phone,
                    'drop_name' => $request_detail->drop_name,
                    'drop_phone' => $request_detail->drop_phone

                );
            } else {

                $address_detail[] = array(
                    'd_address' => $request_detail->delivery_address,
                    's_address' => $restaurant_detail->address,
                    'd_lat' => $request_detail->d_lat,
                    'd_lng' => $request_detail->d_lng,
                    's_lat' => $restaurant_detail->lat,
                    's_lng' => $restaurant_detail->lng
                );
            }


            $food_detail = array();
            $bill_detail = array();

            $data = DB::table('request_detail')
                ->where('request_detail.request_id', $request_id)
                ->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
                ->select('request_detail.quantity as quantity', 'food_list.name as food', 'food_list.price as price_per_quantity', 'food_list.is_veg as is_veg')
                ->get();

            foreach ($data as $d) {
                $food_detail[] = array(
                    'name' => $d->food,
                    'quantity' => $d->quantity,
                    'price' => $d->quantity * $d->price_per_quantity,
                    'is_veg' => $d->is_veg
                );
            }

            $bill_detail[] = array(
                'item_total' => $request_detail->item_total,
                'offer_discount' => $request_detail->offer_discount,
                'packaging_charge' => $request_detail->restaurant_packaging_charge,
                'tax' => $request_detail->tax,
                'delivery_charge' => $request_detail->delivery_charge,
                'bill_amount' => $request_detail->bill_amount
            );

            $response_array = [
                'status' => true,
                'request_id' => $request_id,
                'layout_id' => $business->layout_id,
                'tips' => $tips,
                'is_cod' => $request_detail->is_cod,
                'ordered_time' => $ordered_time,
                'order_id' => $order_id,
                'restaurant_detail' => $restaurant_detail,
                'user_detail' => $user_detail,
                'address_detail' => $address_detail,
                'bill_detail' => $bill_detail,
                'food_detail' => $food_detail,
                'request_status' => $request_status
            ];
        } else {
            $response_array = array('status' => false, 'message' => 'No orders available');
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function update_request(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'request_id' => 'required',
                'status' => 'required'
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);

            $response = response()->json($response_array, 200);
            return $response;
        }

        $request_id = $request->request_id;
        $status = $request->status;
        $trackorderstatus = $this->trackorderstatus;
        $deliverypartners = $this->deliverypartners;
        $partner_id = $request->header('authId');

        $request_detail = $this->foodrequest->find($request_id);

        $order = DB::table('requests')->where('id', $request_id)->first();
        $user = DB::table('users')->where('id', $order->user_id)->first();

        if ($partner_id != $order->delivery_boy_id) {
            $response_array = array('status' => false, 'error_code' => 101, 'message' => 'This order belongs to another rider');
            $response = response()->json($response_array, 200);
            return $response;
        }

        DB::table('requests')->where('id', $request_id)->update(['status' => $status, 'is_confirmed' => 1]);

        /*

				Started towards Restarent -> 3 -> (On the way)
				Reached restarent -> 4 -> (Food received)
				Started towards Customer -> 5 -> (On the way)
				Food delivered -> 6 -> (delivered)
				cash received - >  7 ->(order completed)
				cancelled - > 10 -> (Order canceled)
		*/

        if ($status == 3) {
            $message = "Delivery Boy Started towards Store";
        } else if ($status == 4) {
            $message = "Delivery Boy Reached Store";
        } else if ($status == 5) {
            $message = "Started towards Customer";
        } else if ($status == 6) {
            $message = "Order delivered";

            $partner_detail = $deliverypartners::where('id', $partner_id)->first();
            $partner_earnings = $partner_detail->total_earnings + $request_detail->delivery_boy_commision + $request_detail->tips;
            $partner_detail->total_earnings = $partner_earnings;
            $partner_detail->pending_payout = $partner_detail->pending_payout + $request_detail->delivery_boy_commision + $request_detail->tips;
            $partner_detail->save();

            //commission update in admin
            // $this->admin->find(1)->increment('total_earnings', $request_detail->admin_commision );

            //earnings update in restaurant
            $this->restaurants->find($request_detail->restaurant_id)->increment('total_earnings', $request_detail->restaurant_commision);
            $this->restaurants->find($request_detail->restaurant_id)->increment('pending_payout', $request_detail->restaurant_commision);
        }

        if ($status == 7) {
            DB::table('requests')->where('id', $request_id)->update(['is_paid' => 1]);

            $message = "Cash Received. Order Completed";

            // delete request to driver
            $temp_driver = $request_detail->delivery_boy_id;
            $header = array();
            $header[] = 'Content-Type: application/json';
            $postdata = array();
            $postdata['request_id'] = $request_id;
            $postdata['user_id'] = $request_detail->user_id;
            $postdata['status'] = 1;
            $postdata = json_encode($postdata);

            $ch = curl_init(FIREBASE_URL . "/new_request/$temp_driver.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);

            //Delete request to admin
            $restaurant_id = $request_detail->restaurant_id;
            $header = [];
            $header[] = 'Content-Type: application/json';
            $postdata = [];
            $postdata['request_id'] = $request_id;
            $postdata['user_id'] = $request_detail->user_id;
            $postdata['status'] = 1;
            $postdata = json_encode($postdata);

            $ch = curl_init(FIREBASE_URL . "/admin_requests/$restaurant_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
        }

        sendNotification(
            [$user->device_token],
            $message,
            $message,
            ['order_id' => $request_id]
        );

        $status_entry = array();

        $status_entry[] = array(
            'request_id' => $request_id,
            'status' => $status,
            'detail' => $message
        );

        $check_trackorder_status = $trackorderstatus::where('request_id', $request_id)->where('status', $status)->count();

        if ($check_trackorder_status == 0) {
            $trackorderstatus::insert($status_entry);
        }
        $webToken = DB::table('user_fcm_token')->where('userid', $request_detail->user_id)->where('type', 'web')->value('token');
        //send push notification to user
        if (isset($request_detail->Users->device_token) && $request_detail->Users->device_token != '' || $webToken) {
            $title = trans('constants.order_status_update');
            $this->user_send_push_notification($request_detail->Users->device_token || $webToken, $request_detail->Users->device_type, $title, $message, $request_id);
        }

        $response_array = array('status' => true, 'request_id' => $request_id, 'order_id' => $request_detail->order_id, 'message' => $message);

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function cancel_request(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'request_id' => 'required'
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            return response()->json(['status' => false, 'error_code' => 101, 'message' => $error_messages], 200);
        }

        $request_id = $request->request_id;
        $provider_id = $request->header('authId');

        $old_provider = 0;

        $request_data = DB::table('requests')->where('id', $request_id)->first();
        if (!$request_data) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => 'Order not found'], 200);
        }

        DB::table('cancelled_drivers')->insert(['delivery_partners_id' => $provider_id, 'request_id' => $request_id]);
        $cancelled_drivers_ids = DB::table('cancelled_drivers')->where('request_id', $request_id)->pluck('delivery_partners_id')->toArray();

        sendOrderRejectedByRiderEmail2Restaurant($request_id, $provider_id);

        // delete request to driver
        $temp_driver = $provider_id;
        $header = ['Content-Type: application/json'];
        $postdata = [
            'request_id' => $request_id,
            'user_id' => $request_data->user_id,
            'status' => 1,
        ];
        $postdata = json_encode($postdata);

        $ch = curl_init(FIREBASE_URL . "/new_request/$temp_driver.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);

        //Delete request to admin
        $restaurant_id = $request_data->restaurant_id;
        $header = [];
        $header[] = 'Content-Type: application/json';
        $postdata = [];
        $postdata['request_id'] = $request_id;
        $postdata['user_id'] = $request_data->user_id;
        $postdata['status'] = 1;
        $postdata = json_encode($postdata);

        $ch = curl_init(FIREBASE_URL . "/admin_requests/$restaurant_id.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);

        $restuarant_detail = $this->restaurants::where('id', $request_data->restaurant_id)->first();

        $source_lat = $restuarant_detail->lat;
        $source_lng = $restuarant_detail->lng;


        $data = file_get_contents(FIREBASE_URL . "/available_providers/.json");
        $data = json_decode($data);

        $temp_driver = 0;
        $last_distance = 0;

        if ($data == NULL || $data == "") {
            $data = [];
        }

        foreach ($data as $key => $value) {

            $driver_id = $key;

            $driver = DB::table('delivery_partners')->where('id', $driver_id)->first();
            if (!$driver) {
                continue;
            }

            if ($old_provider == 0) {
                $old_provider = -1;
            }

            if (in_array($driver_id, $cancelled_drivers_ids) || $driver_id == $old_provider || $driver_id == $provider_id) {
                continue;
            }

            if ($value == NULL || $value == "") {
                continue;
            }

            $driver_lat = $value->lat;
            $driver_lng = $value->lng;

            try {
                $q = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$source_lat,$source_lng&destinations=$driver_lat,$driver_lng&mode=driving&sensor=false&key=" . GOOGLE_API_KEY;
                $json = file_get_contents($q);
                $details = json_decode($json, TRUE);

                if ($details['rows'][0]['elements'][0]['status'] == 'ZERO_RESULTS') {
                    continue;
                }

                $current_distance = (float) $details['rows'][0]['elements'][0]['distance']['text'];

                if ($temp_driver == 0) {
                    $temp_driver = $driver_id;
                    $last_distance = $current_distance;
                } else {
                    if ($current_distance < $last_distance) {
                        $temp_driver = $driver_id;
                        $last_distance = $current_distance;
                    }
                }
            } catch (Exception $e) {
            }
        }

        if ($temp_driver != 0) {

            $user_data = DB::table('requests')->where('id', $request_id)->first();

            DB::table('requests')->where('id', $request_id)->update(['delivery_boy_id' => $temp_driver, 'status' => 2]);

            // to insert into firebase
            $header = ['Content-Type: application/json'];
            $postdata = [
                'request_id' => $request_id,
                'provider_id' => (string) $temp_driver,
                'user_id' => $user_data->user_id,
                'status' => 2,
            ];
            $postdata = json_encode($postdata);

            $ch = curl_init(FIREBASE_URL . "/current_request/$request_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);

            // sending request to driver
            $header = ['Content-Type: application/json'];;
            $postdata = [
                'request_id' => $request_id,
                'user_id' => $user_data->user_id,
                'status' => 1
            ];
            $postdata = json_encode($postdata);

            $ch = curl_init(FIREBASE_URL . "/new_request/$temp_driver.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {

            $user_data = DB::table('requests')->where('id', $request_id)->first();

            DB::table('requests')->where('id', $request_id)->update(['delivery_boy_id' => 0, 'status' => 1]);


            // to insert into firebase
            $header = ['Content-Type: application/json'];
            $postdata = [
                'request_id' => $request_id,
                'provider_id' => '0',
                'user_id' => $user_data->user_id,
                'status' => 1,
            ];
            $postdata = json_encode($postdata);

            $ch = curl_init(FIREBASE_URL . "/current_request/$request_id.json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);
            curl_close($ch);
        }

        $response = response()->json(['status' => true, 'message' => 'Request Cancelled Successfully'], 200);
        return $response;
    }

    public function order_history(Request $request)
    {
        $delivery_boy_id = $request->header('authId');

        $orders = DB::table('requests')->where('requests.delivery_boy_id', $delivery_boy_id)->where('requests.is_paid', 1)->latest()->limit(15)->get();

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
            if ($restaurant_detail) {

                $order_list[] = array(
                    'request_id' => $key->id,
                    'order_id' => $key->order_id,
                    'restaurant_id' => $restaurant_detail->id,
                    'restaurant_name' => $restaurant_detail->restaurant_name,
                    'restaurant_image' => File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image),

                    'ordered_on' => $key->ordered_time,
                    'bill_amount' => $key->bill_amount,
                    'item_list' => $order_list_detail,
                    'item_total' => $key->item_total,
                    'offer_discount' => $key->offer_discount,
                    'restaurant_packaging_charge' => $key->restaurant_packaging_charge,
                    'tax' => $key->tax,
                    'delivery_charge' => $key->delivery_charge,
                    'delivery_address' => $key->delivery_address,
                    'restaurant_address' => $restaurant_detail->address
                );
            }
        }

        $upcoming_orders = DB::table('requests')->where('requests.delivery_boy_id', $delivery_boy_id)->where('requests.status', '!=', 10)->where('requests.status', '!=', 7)->get();

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

            if (!empty($restaurant_details)) {

                $upcoming_order_list[] = array(
                    'request_id' => $key->id,
                    'order_id' => $key->order_id,
                    'restaurant_id' => $restaurant_details->id,
                    'restaurant_name' => $restaurant_details->restaurant_name,
                    'restaurant_image' =>  File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image),

                    'ordered_on' => $key->ordered_time,
                    'bill_amount' => $key->bill_amount,
                    'item_list' => $upcoming_order_list_detail,
                    'item_total' => $key->item_total,
                    'offer_discount' => $key->offer_discount,
                    'restaurant_packaging_charge' => $key->restaurant_packaging_charge,
                    'tax' => $key->tax,
                    'delivery_charge' => $key->delivery_charge,
                    'delivery_address' => $key->delivery_address,
                    'restaurant_address' => $restaurant_details->address
                );
            }
        }


        if (count($upcoming_order_list) != 0 || count($order_list) != 0) {
            $response_array = array('status' => true, 'past_orders' => $order_list, 'upcoming_orders' => $upcoming_order_list);
        } else {

            $response_array = array('status' => false, 'message' => "No Orders Received");
        }


        $response = response()->json($response_array, 200);
        return $response;
    }


    /**
     * update order ratings from user
     *
     * @param object $request
     *
     * @return json $response
     */
    public function order_ratings(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'request_id' => 'required',
                'restaurant_rating' => 'required|not_in:0',
                'delivery_boy_rating' => 'required|not_in:0',
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $order_det = $this->foodrequest->find($request->request_id);
            if (!empty($order_det)) {
                if ($order_det->status == 7) {
                    $feedback = (isset($request->restaurant_feedback) ? $request->restaurant_feedback : "");

                    //insert ratings into table
                    $this->order_ratings->request_id = $request->request_id;
                    $this->order_ratings->restaurant_rating = $request->restaurant_rating;
                    $this->order_ratings->restaurant_feedback = $feedback;
                    $this->order_ratings->delivery_boy_rating = $request->delivery_boy_rating;
                    $this->order_ratings->save();

                    $order_det->is_rated = 1;
                    $order_det->save();

                    $response_array = array('status' => true, 'message' => trans('constants.rate_msg'));
                } else {
                    $response_array = array('status' => false, 'error_code' => 101, 'message' => trans('constants.order_not_complete'));
                }
            } else {
                $response_array = array('status' => false, 'error_code' => 101, 'message' => trans('constants.invalid_order'));
            }
        }
        $response = response()->json($response_array, 200);
        return $response;
    }


    /**
     * validate promocode
     *
     * @param object $request
     *
     * @return json $response
     *
     */
    public function check_promocode(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'bill_amount' => 'required',
                'promocode' => 'required',
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            return response()->json(['status' => false, 'error_code' => 101, 'message' => $error_messages], 200);
        }

        $user_id = $request->header('authId') != "" ? $request->header('authId') : $request->authId;

        $get_promocode = $this->promocode->where('code', $request->promocode)
            ->where('status', 1)
            ->whereDate('available_from', '<=', Carbon::now())
            ->whereDate('valid_till', '>=', Carbon::now())->first();

        if (empty($get_promocode)) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => trans('constants.invalid_promo')], 200);
        }
        // check individual code available for restaurant
        $res_promocode = $this->promocode->where('code', $request->promocode)->first();
        if ($res_promocode->restaurant_id == null) {  //to check if is null is important
        } else if (($res_promocode->restaurant_id != $request->restaurant_id)) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => trans('constants.invalid_promo')], 200);
        } else {
        }

        //check total usage of promocode
        $total_usage = $this->foodrequest->where('coupon_code', $request->promocode)->where('status', '!=', 10)->count();

        //check the promocode usage by user
        $user_usage = $this->foodrequest->where('coupon_code', $request->promocode)
            ->where('status', '!=', 10)->where('user_id', $user_id)
            ->count();

        if ($total_usage >= $get_promocode->total_use) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => trans('constants.promocode_used')];
        } else if ($user_usage >= $get_promocode->use_per_customer) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => trans('constants.promocode_used')];
        } else {
            if ($get_promocode->offer_type == 1) {
                $offer_amount = $get_promocode->value;
            } else {
                $offer_amount = $request->bill_amount * ($get_promocode->value / 100);
                if (isset($get_promocode->max_amount) && ($offer_amount > $get_promocode->max_amount)) {
                    $offer_amount = $get_promocode->max_amount;
                }
            }
            $response_array = array('status' => true, 'offer_amount' => $offer_amount);
        }

        return response()->json($response_array, 200);
    }

    public function get_driver_order_list(Request $request)
    {

        $delivery_boy_id = $request->header('authId');

        $requests = DB::table('requests')
            ->where('delivery_boy_id', $delivery_boy_id)
            ->where('status', '!=', 10)
            ->where('status', '!=', 11)
            ->where('status', '!=', 7)
            ->where('status', '!=', -1)
            ->where('status', '!=', 12)
            ->where('status', '!=', 13)
            ->where('status', '!=', 14)
            ->get();

        if (empty($requests)) {
            return response()->json(['status' => false, 'message' => 'No orders available'], 200);
        }

        $orders = [];

        foreach ($requests as $requests_key => $requests_value) {
            $order_id = $requests_value->order_id;

            $request_id = $requests_value->id;
            $tips = $requests_value->tips;

            $ordered_time = $requests_value->ordered_time;

            $restaurant_detail = $this->restaurants::where('id', $requests_value->restaurant_id)->first();
            if (empty($restaurant_detail)) {
                continue;
            }
            // $restaurant_detail->image = BASE_URL . RESTAURANT_UPLOADS_PATH . ($restaurant_detail->image ?? '');

            if (isset($restaurant_detail->image)) {
                // $restaurant_detail->image = File::exists(public_path('restaurant_uploads/').$restaurant_detail->image)? BASE_URL.RESTAURANT_UPLOADS_PATH.$restaurant_detail->image:public_path('restaurant_uploads/').$restaurant_detail->id.'/'.'Logo/'.$restaurant_detail->image;
                $restaurant_detail->image = File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image);
            } else {
                $restaurant_detail->image = BASE_URL . RESTAURANT_UPLOADS_PATH . ($restaurant_detail->image ?? '');
            }

            $user_detail = DB::table('users')->where('id', $requests_value->user_id)->first();

            $address_detail = array();

            $request_status = $requests_value->status;

            $address_detail[] = array(
                'd_address' => $requests_value->delivery_address,
                's_address' => $restaurant_detail->address,
                'd_lat' => $requests_value->d_lat,
                'd_lng' => $requests_value->d_lng,
                's_lat' => $restaurant_detail->lat,
                's_lng' => $restaurant_detail->lng
            );

            $food_detail = array();
            $bill_detail = array();

            $data = DB::table('request_detail')
                ->where('request_detail.request_id', $request_id)
                ->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
                ->select('request_detail.quantity as quantity', 'food_list.name as food', 'food_list.price as price_per_quantity', 'food_list.is_veg as is_veg')
                ->get();

            foreach ($data as $d) {
                $food_detail[] = array(
                    'name' => $d->food,
                    'quantity' => $d->quantity,
                    'price' => $d->quantity * $d->price_per_quantity,
                    'is_veg' => $d->is_veg
                );
            }

            $bill_detail[] = array(
                'item_total' => $requests_value->item_total,
                'offer_discount' => $requests_value->offer_discount,
                'packaging_charge' => $requests_value->restaurant_packaging_charge,
                'tax' => $requests_value->tax,
                'delivery_charge' => $requests_value->delivery_charge,
                'bill_amount' => $requests_value->bill_amount
            );


            $orders[] = [
                'status' => true,
                'request_id' => $request_id,
                'tips' => $tips,
                'is_cod' => $requests_value->is_cod,
                'ordered_time' => $ordered_time,
                'order_id' => $order_id,
                'restaurant_detail' => $restaurant_detail,
                'user_detail' => $user_detail,
                'address_detail' => $address_detail,
                'bill_detail' => $bill_detail,
                'food_detail' => $food_detail,
                'request_status' => $request_status
            ];
        }

        return response()->json(['status' => true, 'message' => 'success', 'orders' => $orders], 200);
    }

    function CreateOrder(Request $request)
    {

        if (!$request->json()->all()) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => "Request should be JSON"], 200);
        }

        $validator = $request->validate(
        // $validator = Validator::make(
        //     $request->all(),
            [
                'CustomerID'         => 'required',
                'RestaurantID'         => 'required',
                'Items'             => 'required',
                'PaymentMethod'     => 'required',
                'DeliveryMethod'     => 'required',
                'Address'             => 'required',
                'Latitude'             => 'required',
                'Longitude'         => 'required',
                'DeliveryCharge'     => 'required',
                'CreatedThrough'     => 'required',

            ]
        );

        // if ($validator->fails()) {
        //     return response()->json(['status' => false, 'error_code' => 101, 'message' => cimplode($validator->messages()->all())], 200);
        // }


        $Order = new Order();

        $Order->CustomerID         = $request->CustomerID;
        $Order->RestaurantID     = $request->RestaurantID;
        $Order->Items             = $request->Items;
        $Order->PaymentMethod     = $request->PaymentMethod;
        $Order->CookingNotes     = $request->CookingNotes;
        $Order->ScheduledAt     = $request->ScheduledAt;
        $Order->tips             = $request->tips;
        $Order->tax             = $request->tax;
        $Order->DeliveryMethod     = $request->DeliveryMethod;
        $Order->Address         = $request->Address;
        $Order->Latitude         = $request->Latitude;
        $Order->Longitude         = $request->Longitude;
        $Order->DeliveryCharge     = (float) $request->DeliveryCharge;

        $Order->OfferCode     =  $request->OfferCode;
        $Order->OfferAmount     = (float) $request->OfferAmount;
        $Order->WalletAmount    = (float) $request->WalletAmount;
        $Order->CreatedThrough     = $request->CreatedThrough;
        $Order->PaymentMode     = $request->PaymentMode;
        $Order->PhoneNumber     = $request->PhoneNumber;
        $Order->Packaging_type  = $request->PackagingType;

        $Order->ScheduledTime  = $request->ScheduledTime;
        $Order->schedule  = $request->schedule;

        if ($request->WalletAmount > 0) {


            $Order->OfferCode     =  $request->OfferCode;
            $Order->OfferAmount     = (float) $request->OfferAmount;
            $Order->WalletAmount    = (float) $request->WalletAmount;
            if ($request->isGpay) {
                $Order->IsGpay    =  $request->isGpay;
            } else {
                $Order->IsGpay    =  0;
            }
            $Order->CreatedThrough     = $request->CreatedThrough;

            $CreatedOrder = $Order->createwallet();
        } else {
            $Order->CreatedThrough     = $request->CreatedThrough;

            $CreatedOrder = $Order->create();
        }
        if (!$CreatedOrder->flag) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => $CreatedOrder->flagMsg], 200);
        }


        $returnData = [
            'status'         => true,
            'error_code'     => 100,
            'OrderCode'     => $Order->OrderCode,
            'OrderID'         => $Order->OrderID,
            'CheckoutURL'     => $Order->CheckoutURL,
            'PaymentID'     => $Order->PaymentID,
            'ClientSecret'     => $Order->ClientSecret,
            'BillAmount'     => $Order->BillAmount,
            // 'token'         =>$Order ->token
        ];

        return response()->json($returnData, 200);
    }

    function ConfirmOrder(Request $request)
    {


        if (!$request->json()->all()) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => "Request should be JSON"], 200);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'OrderID' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => cimplode($validator->messages()->all())], 200);
        }

        $Order = Order::find($request->OrderID);
        if (!$Order->flag) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => "Order not found"], 200);
        }

        $confirmOrder = $Order->confirmOrder();
        if (!$confirmOrder->flag) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => $confirmOrder->flagMsg], 200);
        }

        return response()->json(['status' => true, 'error_code' => 100, 'message' => "Order confirmed"], 200);
    }

    function order_review(Request $request)
    {
        $restaurant_id = $request->restaurant_id;
        $restaurants = $this->restaurants;
        $data = $restaurants::where('id', $restaurant_id)->first();

        $ratings = $this->order_ratings->join('requests', 'requests.id', '=', 'order_ratings.request_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('order_ratings.*', 'users.name as user_name', 'users.profile_image')->where('restaurant_id', $restaurant_id)
            ->get();
        foreach ($ratings as $rat) {

            $restaurant_list[] = array(
                'id' => $rat->id,
                'request_id' => $rat->request_id,
                'restaurant_rating' => $rat->restaurant_rating,
                'restaurant_feedback' => $rat->restaurant_feedback,
                'delivery_boy_rating' => $rat->delivery_boy_rating,
                'created_at' => Carbon::parse($rat->created_at)->format('Y-m-d h:i:s'),
                'updated_at' => Carbon::parse($rat->updated_at)->format('Y-m-d h:i:s'),
                'user_name' => $rat->user_name

            );
        }

        $video = json_decode($data->video_url);
        $title = json_decode($data->video_title);
        $returnData = [
            'status'         => true,
            'error_code'     => 100,
            'review'     => $restaurant_list,
            'video_url'     => $video,
            'video_title'     => $title,
        ];

        return response()->json($returnData, 200);
    }
    function checkradius(Request $request)
    {
        $user_lat = $request->user_lat;
        $lat = $request->lat;
        $user_lng = $request->user_lng;
        $lng = $request->lng;
        $restaurant_id = $request->restaurant_id;
        $distance = vincentyGreatCircleDistance($lat, $lng, $user_lat, $user_lng);
        if ($distance <= DEFAULT_RADIUS) {
            $response_array = array('status' => true, 'radius' => 1);
        } else {
            $response_array = array('status' => false, 'radius' => 0);
        }
        return response()->json($response_array, 200);
    }

    public function applyCoupon(Request $request)
    {
        if (gettype($request->Items) == "string") {
            $request->Items = json_decode($request->Items);
        }
        $userID = (int) ($request->header('authId') != "" ? $request->header('authId') : $request->authId);

        $Offer = Coupon::apply($request->CouponCode, $userID, $request->RestaurantID, $request->Items);
        if ($Offer->flag) {

            $response_array = ['status' => true, 'message' => 'OK', 'OfferAmount' => number_format(doubleval($Offer->data), 2, '.', '')];
        } else {
            $response_array = ['status' => false, 'message' => $Offer->flagMsg, 'OfferAmount' => 0];
        }

        return response()->json($response_array, 200);
    }

    public function response_mybank(Request $request)
    {

        $token = $request->token;

        $outlet = env('MYBANK_OUTLET');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api-gateway.sandbox.mybank.ngenius-payments.com/transactions/outlets/" . $outlet . "/orders" . "/" . $request->order_reference,
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
        //dd($response);
        $err = curl_error($curl);

        curl_close($curl);

        return response()->json(['data' => json_decode($response)]);
    }

    public function response_mybank1(Request $request)
    {
        $apikey = "MzczMTgwYTctYjNlOC00MTIzLWI4ZTItOGFhZjY5NzAwODhkOjgxOTk0ZGI1LTNkMjMtNDIwMi04MjA2LWI2Y2RmZTk0MWI3MA==";     // enter your API key here
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
        //  curl_setopt($ch, CURLOPT_POSTFIELDS,  "{\"realmName\":\"ni\"}");
        $output = json_decode(curl_exec($ch));
        //  dd($output);
        $access_token = $output->access_token;
        return response()->json(['data' => $access_token]);
    }


    public function dahab_order(Request $request)
    {


        $request_param = array("apiKey" => "Gn8BQDDWSHuc6K7ASE0Op674keWqTFYlriqUoH8pK", "edahabNumber" => "624249299", "amount" => 1, "agentCode" => "085252");


        /* Encode it into a JSON string. */
        $json = json_encode($request_param, JSON_UNESCAPED_SLASHES);

        $hashed = hash('SHA256', $json . "3YgLehQBt4G6MNZkAh1EQ1h78n3dPyfjTS7FJD");


        $url = "https://edahab.net/api/api/IssueInvoice?hash=" . $hashed;

        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        // $err = curl_error($curl);
        curl_close($curl);



        // // Tell cURL to send a POST request.
        // curl_setopt($curl, CURLOPT_POST, true);

        // // Set the JSON object as the POST content.
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $json);

        // // Set the JSON content-type: application/json.
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        // // Send the request.
        // $result = curl_exec($curl);
        // curl_close($curl);
        // // Get the InvoiceId from the API response and store it in your database.
        // echo $result;



    }

    public function  edahab_response(Request $request)
    {
        $invoiceId = $request->invoice_id;

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
        if ($result->StatusCode != 0) {
            $this->Message = $result->ValidationErrors;

            return false;
        }
        if ($result->InvoiceStatus == "Paid") {
            $this->Message = $result->ValidationErrors;
            return false;
        } else {
            $this->Message = $result->ValidationErrors;
            return false;
        }
    }


    function pickup_drop_createOrder(Request $request)
    {

        // dd($request);
        if (!$request->json()->all()) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => "Request should be JSON"], 200);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'CustomerID'         => 'required',
                'BusinessType'      => 'required',
                'RestaurantID'         => 'required',
                'PickupAddress'     => 'required',
                'PickupLatitude'    => 'required',
                'PickupLongitude'   => 'required',
                'PickupName'        => 'required',
                'PickupNumber'      => 'required',
                'DropName'          => 'required',
                'DropNumber'        => 'required',
                // 'Items' 			=> 'required',
                'PaymentMethod'     => 'required',
                // 'DeliveryMethod' 	=> 'required',
                'Address'             => 'required',
                'Latitude'             => 'required',
                'Longitude'         => 'required',
                'DeliveryCharge'     => 'required',
                'CreatedThrough'     => 'required',

            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => cimplode($validator->messages()->all())], 200);
        }

        $Order = new Order();

        $Order->CustomerID         = $request->CustomerID;
        $Order->BusinessType     = $request->BusinessType;
        $Order->RestaurantID     = $request->RestaurantID;
        // $Order->Items 			= $request->Items;
        $Order->PaymentMethod     = $request->PaymentMethod;
        $Order->Packaging_Des     = $request->Packaging_Des;
        $Order->ScheduledAt     = $request->ScheduledAt;
        $Order->tips             = $request->tips;
        $Order->tax             = $request->tax;
        // $Order->DeliveryMethod 	= $request->DeliveryMethod;
        $Order->Address         = $request->Address;
        $Order->Latitude         = $request->Latitude;
        $Order->Longitude         = $request->Longitude;
        $Order->DropName          = $request->DropName;
        $Order->DropNumber      = $request->DropNumber;
        $Order->PickupAddress     = $request->PickupAddress;
        $Order->PickupLatitude     = $request->PickupLatitude;
        $Order->PickupLongitude = $request->PickupLongitude;
        $Order->PickupName      = $request->PickupName;
        $Order->PickupNumber    = $request->PickupNumber;
        $Order->DeliveryCharge     = (float) $request->DeliveryCharge;

        $Order->OfferCode     =  $request->OfferCode;
        $Order->OfferAmount     = (float) $request->OfferAmount;
        $Order->WalletAmount    = (float) $request->WalletAmount;
        $Order->CreatedThrough     = $request->CreatedThrough;
        $Order->PaymentMode     = $request->PaymentMode;
        $Order->PhoneNumber     = $request->PhoneNumber;


        $CreatedOrder = $Order->create_pickup_drop();

        if (!$CreatedOrder->flag) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => $CreatedOrder->flagMsg], 200);
        }


        $returnData = [
            'status'         => true,
            'error_code'     => 100,
            'OrderCode'     => $Order->OrderCode,
            'OrderID'         => $Order->OrderID,
            'CheckoutURL'     => $Order->CheckoutURL,
            'PaymentID'     => $Order->PaymentID,
            'ClientSecret'     => $Order->ClientSecret,
            'BillAmount'     => $Order->DeliveryCharge,
            // 'token'         =>$Order ->token
        ];

        return response()->json($returnData, 200);
    }

    function ConfirmOrder_pickup_order(Request $request)
    {


        if (!$request->json()->all()) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => "Request should be JSON"], 200);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'OrderID' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => cimplode($validator->messages()->all())], 200);
        }

        $Order = Order::find_pickup_order($request->OrderID);
        if (!$Order->flag) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => "Order not found"], 200);
        }

        $confirmOrder = $Order->confirmOrder_pickup_order();
        if (!$confirmOrder->flag) {
            return response()->json(['status' => false, 'error_code' => 101, 'message' => $confirmOrder->flagMsg], 200);
        }

        return response()->json(['status' => true, 'error_code' => 100, 'message' => "Order confirmed"], 200);
    }

    public function order_history_v2(Request $request)
    {
        $delivery_boy_id = $request->header('authId');

        $orders = DB::table('requests')->where('requests.delivery_boy_id', $delivery_boy_id)->where('requests.is_paid', 1)->latest()->limit(15)->get();

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
                    'tax' => (string)(!empty($k->Foodlist) ? $k->Foodlist->tax : ""),
                    'item_price' => (string)(!empty($k->Foodlist) ? $k->Foodlist->price : 0) * $k->quantity,
                    'is_veg' => (!empty($k->Foodlist) ? $k->Foodlist->is_veg : ""),
                    'food_size' => $k->FoodQuantity,
                    'add_ons' => $k->Addons
                );
            }

            $restaurant_detail = DB::table('restaurants')->where('id', $key->restaurant_id)->first();
            if ($restaurant_detail) {

                $order_list[] = array(
                    'request_id' => $key->id,
                    'order_id' => $key->order_id,
                    'restaurant_id' => $restaurant_detail->id,
                    'restaurant_name' => $restaurant_detail->restaurant_name ? $this->secound_name_api($restaurant_detail->restaurant_name) : '',
                    'secondary_name' => $restaurant_detail->restaurant_secondary_name ? $this->secound_name_api($restaurant_detail->restaurant_secondary_name) :  '',
                    'restaurant_image' => File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image),

                    'ordered_on' => $key->ordered_time,
                    'bill_amount' => (string) $key->bill_amount,
                    'item_list' => $order_list_detail,
                    'item_total' => (string) $key->item_total,
                    'offer_discount' => (string)$key->offer_discount,
                    'restaurant_packaging_charge' => (string) $key->restaurant_packaging_charge,
                    'tax' => (string) $key->tax,
                    'delivery_charge' => (string)$key->delivery_charge,
                    'delivery_address' => $key->delivery_address,
                    'restaurant_address' => $restaurant_detail->address
                );
            }
        }

        $upcoming_orders = DB::table('requests')->where('requests.delivery_boy_id', $delivery_boy_id)->where('requests.status', '!=', 10)->where('requests.status', '!=', 7)->latest()->get();

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
                    'tax' => (string) (!empty($k->Foodlist) ? $k->Foodlist->tax : ""),
                    'item_price' => (string)(!empty($k->Foodlist) ? $k->Foodlist->price : 0) * $k->quantity,
                    'is_veg' => (!empty($k->Foodlist) ? $k->Foodlist->is_veg : ""),
                    'food_size' => $k->FoodQuantity,
                    'add_ons' => $k->Addons
                );
            }

            $restaurant_details = DB::table('restaurants')->where('id', $key->restaurant_id)->first();

            if (!empty($restaurant_details)) {


                $upcoming_order_list[] = array(
                    'request_id' => $key->id,
                    'order_id' => $key->order_id,
                    'restaurant_id' => $restaurant_details->id,
                    'restaurant_name' => $restaurant_details->restaurant_name ? $this->secound_name_api($restaurant_details->restaurant_name) : '',
                    'secondary_name' => $restaurant_details->restaurant_secondary_name ? $this->secound_name_api($restaurant_details->restaurant_secondary_name) : '',
                    'restaurant_image' =>  File::exists(public_path('restaurant_uploads/') . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . '/' . 'Logo/' . $restaurant_detail->image : BASE_URL . UPLOADS_PATH_LOGO . $restaurant_detail->image),

                    'ordered_on' => $key->ordered_time,
                    'bill_amount' => (string)$key->bill_amount,
                    'item_list' => $upcoming_order_list_detail,
                    'item_total' => (string) $key->item_total,
                    'offer_discount' => (string)$key->offer_discount,
                    'restaurant_packaging_charge' => (string) $key->restaurant_packaging_charge,
                    'tax' => (string)$key->tax,
                    'delivery_charge' => (string) $key->delivery_charge,
                    'delivery_address' => $key->delivery_address,
                    'restaurant_address' => $restaurant_details->address
                );
            }
        }


        if (count($upcoming_order_list) != 0 || count($order_list) != 0) {
            $response_array = array('status' => true, 'past_orders' => $order_list, 'upcoming_orders' => $upcoming_order_list);
        } else {

            $response_array = array('status' => false, 'message' => "No Orders Received");
        }


        $response = response()->json($response_array, 200);
        return $response;
    }
}
