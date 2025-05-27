<?php

namespace App\Http\Controllers\web;

use Validator;
use Hash;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Order;

class OrderController extends BaseController
{

	public function Orders_list(Request $request, $type)
	{

		if ($type == 'new') $status = [0];
		if ($type == 'scheduled') $status = [0];
		if ($type == 'processing') $status = [1, 2];
		if ($type == 'pickup') $status = [3, 4, 5];
		if ($type == 'delivered') $status = [6, 7];
		if ($type == 'cancelled') $status = [9, 10, 11, 12, 13, 14];
		if ($type == 'failed') $status = [-2];
		if ($type == 'abandon') $status = [-1];
		if ($type == 'refund') $status = [10, 12, 13, 14];


		$data = DB::table('requests')
			->whereIn('requests.status', $status)
			->leftJoin('users', 'users.id', '=', 'requests.user_id')
			->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
			->leftJoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
			->select('requests.*', 'users.name as user_name', 'requests.wallet_amount as wallet_amounts', 'delivery_partners.name as rider_name', 'restaurants.restaurant_name')
			->orderBy('requests.id', 'desc')->get();



		return response()->json($data);
	}
	public function new_update(Request $request)
	{
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

		if ($query == true) {
			$message = "Status Changed Successfully";
			$status = true;
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
			->select('requests.id as request_id', 'requests.tax as rtax', 'requests.status as order_status', 'requests.wallet_amount as wallet_amounts', 'users.name as user_name', 'users.email as user_email', 'users.phone as user_phone', 'restaurants.email as restaurants_email', 'restaurants.tax as restaurants_tax', 'requests.*', 'users.*', 'restaurants.*', 'requests.image as req_image')
			->first();
		if ($data) {
			$status = [10, 11];
			$cancell_status = DB::table('track_order_status')
				->where('request_id', $request_id)
				->whereIn('status', $status)
				->first();
		} else {
			$cancell_status = '';
		}

		if ($data->package_type != '') {
			$types = DB::table('package_type')->where('id', $data->package_type)->first();
			$data->package_type = $types->name;
		}

		if ($data->req_image) {
			if ($data->req_image != "undefined") {
				$data->req_image = BASE_URL . UPLOADS_PATH . $data->req_image;
			}
		}

		if ($data->address != "") {
			$decodedText = html_entity_decode($data->address);
			$data->address = $decodedText;
		}
		if ($data->delivery_address != "") {
			$decodedText = html_entity_decode($data->delivery_address);
			$data->delivery_address = $decodedText;
		}

		if ($data->business_type) {
			$layout = DB::table('business_type')->where('id', $data->business_type)->first();
		}

		if ($layout->layout_id == 3) {
			$rt_food = [];
			$rt_food[] = [
				'type' => $data->package_type,
				'image' => $data->req_image,
				'description' => $data->instruction,
				'quantity' => 1,
				'price' => $data->bill_amount,
			];
		} else {
			$food_list = DB::table('request_detail')->where('request_id', $request_id)
				->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
				->get();
			$rt_food = [];
			foreach ($food_list as $fl) {
				$rt_food[] = [
					'Name' => $fl->name,
					'description' => $fl->description,
					'quantity' => $fl->quantity,
					'price' => $fl->food_quantity_price
				];
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

		$order_status = $status;
		$message = $data;
		$food = $rt_food;
		$layout = $layout->layout_id;
		$cancell_status = $cancell_status;
		$status = true;


		$response_Array = json_encode(['message' => $message, 'status' => $status, 'layout' => $layout, 'food' => $food, 'order_status' => $order_status, 'cancell_status' => $cancell_status]);


		return $response_Array;
	}
	public function cancel_order(Request $request)
	{
		$Order = Order::find($request->cancelOrderID);
		$Order->cancelByUser($request->reason);
		$message = "Order Cancelled";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}

	public function track_order(Request $request)
	{
		$session_user_id 	= $request->user_id;
		$request_id = $request->request_id;
		$trackorderstatus 	= $this->trackorderstatus;

		$check_request 		= $this->foodrequest::where([
			'id' => $request->request_id,
			'user_id' => $session_user_id
		])->first();
		if (!$check_request) {
			$data = "Could not find the order";
			$status = false;
			$response_Array = json_encode(['data' => $data, 'status' => $status]);
			return $response_Array;
		}

		$restaurant_detail 	= DB::table('restaurants')->where('id', $check_request->restaurant_id)->first();
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
		$deliver_add =	DB::table('delivery_address')->where([
			'is_default' => 1,
			'user_id' => $session_user_id
		])->first();
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
		if ($layout->layout_id == 3) {
			$icons = [BASE_URL . "anki_user.svg", BASE_URL . "anki_store.svg", BASE_URL . "anki_store.svg"];
		} else {
			$icons = [BASE_URL . "anki_user.svg", BASE_URL . "anki_store.svg"];
		}


		$status = false;
		$response_Array = json_encode(['order_status' => $order_status, 'status' => $status, 'rider_id' => $rider_id, 'tracking_detail' => $tracking_detail, 'locations' => $locations, 'center' => $center, 'user' => $user, 'icons' => $icons]);
		return $response_Array;
	}

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

	public function delete_address(Request $request)
	{
		$id = $request->id;

		$get_id = DB::table('delivery_address')->where('id', $id)->first();
		$user = $get_id->user_id;

		DB::table('delivery_address')->where('id', $id)->delete();



		$address_detail = DB::table('delivery_address')->where('user_id', $user)->get();


		$response_array = array('status' => true, 'error_code' => 101, 'message' => "Address Deleted", 'address_detail' => $address_detail);
		$response = response()->json($response_array, 200);
		return $response;
	}
}
