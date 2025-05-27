<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Order;
use DB;
use Validator;
use Hash;
use Log;
use File;

class AdminController extends BaseController
{


	public function login(Request $request)
	{

		$validator = Validator::make(
			$request->all(),
			[
				'email' => 'required',
				'password' => 'required',
				'device_token' => 'required'
			]
		);

		if ($validator->fails()) {
			$error_messages = implode(',', $validator->messages()->all());
			return response()->json(['status' => false, 'error_code' => 101, 'message' => $error_messages], 200);
		}

		$data = DB::table('restaurants')->where('email', '=', $request->email)->first();

		if (!$data || !Hash::check($request->password, $data->password)) {
			return response()->json(['status' => false, 'message' => 'Invalid Login'], 200);
		}

		$authId = $data->id;
		$name = $data->restaurant_name != NULL ? $data->restaurant_name : "";

		$authToken = $this->generateRandomString();

		$this->restaurants->where('id', $data->id)->update(['appAuthToken' => $authToken, 'device_token' => $request->device_token]);

		$currency = DB::table('country')->select('currency_code', 'currency_symbol')->where('is_default', 1)->first();

		$response_array = [
			'status' => true,
			'message' => 'Logged in successfully',
			'name' => $name,
			'authId' => $authId,
			'authToken' => $authToken,
			'currency_code' => $currency->currency_code ?? '',
			'currency_symbol' => $currency->currency_symbol ?? ''
		];

		return response()->json($response_array, 200);
	}

	public function orders(Request $request, $type)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		$orders = $this->order_list($type);

		$theOrders = $this->trim_orders_for_list($orders);

		return response()->json(['status' => true, 'message' => 'Success', 'orders' => $theOrders], 200);
	}

	private function isLoggedIn(Request $request)
	{
		$authId = $request->header('authId');
		$authToken = $request->header('authToken');

		$this->authID = 0;
		if (empty($authId) || empty($authToken)) {
			return false;
		}

		$data = $this->restaurants->where(
			[
				['id', '=', $authId],
				['appAuthToken', '=', $authToken]
			]
		)->first();

		if ($data) {
			$this->authID = (int) $authId;
			return true;
		} else {
			return false;
		}
	}

	private function isOrderExists($request_id)
	{
		$data = DB::table('requests')->where('id', '=', $request_id)->first();

		return $data ? true : false;
	}

	private function isMyOrder($request_id)
	{
		$data = DB::table('requests')->where('id', '=', $request_id)->where('requests.restaurant_id', '=', $this->authID)->first();

		return $data ? true : false;
	}

	public function order_new_total(Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		$orders = $this->order_list('new');

		$theOrders = count($this->trim_orders_for_list($orders));

		return response()->json(['status' => true, 'message' => 'Success', 'TotalOrders' => $theOrders], 200);
	}

	public function order_total(Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}
		$authId = $request->header('authId');
		$order_list_detail = array();
		$data = DB::table('requests')->where('status', '=', 0)->where('restaurant_id', $authId)->orderBy('created_at', 'desc')->first();

		if ($data) {
			$user = DB::table('users')->where('id', '=', $data->user_id)->first();
			$order_detail = $this->requestdetail->where('request_id', $data->id)->get();

			//food itms
			// dd($order_detail);
			foreach ($order_detail as $k) {
				if (isset($k->FoodQuantity)) {
					$k->FoodQuantity->price = $k->food_quantity_price;
				}

				$order_list_detail[] = array(
					'food_id' => (!empty($k->Foodlist) ? $k->Foodlist->id : ""),
					'food_name' => (!empty($k->Foodlist) ? $k->Foodlist->name : ""),
					'food_quantity' => $k->quantity,
					'item_price' => (!empty($k->Foodlist) ? $k->Foodlist->price : 0) * $k->quantity,
					'food_size' => $k->FoodQuantity,
					'add_ons' => $k->Addons,
				);
			}
			$new_order[] = array(
				'order_id' => $data->id,
				'order_code' => $data->order_id,
				'address' => $data->delivery_address,
				'name' => $user->name,
				'phone' => $user->phone,
				'food_item' => $order_list_detail

			);
		} else {
			$new_order = array();
		}

		return response()->json([
			'status' 			=> true,
			'message' 			=> 'Success',
			'NewOrders' 		=> count($this->order_list('new')),
			'ProcessingOrders' 	=> count($this->order_list('processing')),
			'PickupOrders' 		=> count($this->order_list('pickup')),
			'DeliveredOrders' 	=> count($this->order_list('delivered')),
			'CancelledOrders' 	=> count($this->order_list('cancelled')),
			'neworder_detail'	=> $new_order
		], 200);
	}

	public function order(Request $request, $orderID)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		if (!$this->isOrderExists($orderID)) {
			return response()->json(['status' => false, 'message' => 'Order not found'], 200);
		}

		if (!$this->isMyOrder($orderID)) {
			return response()->json(['status' => false, 'message' => 'Order does not belongs to this restaurant'], 200);
		}

		$order = $this->order_details($orderID);

		if (!$order) {
			return response()->json(['status' => false, 'message' => 'Order not found'], 200);
		}

		return response()->json(['status' => true, 'message' => 'Success', 'order' => $order], 200);
	}

	private function trim_orders_for_list($orders)
	{
		$theOrders = [];
		foreach ($orders as $order) {
			$theOrders[] = $this->trim_order_for_list($order);
		}

		return $theOrders;
	}

	private function trim_order_for_list($order)
	{
		$thisOrder = new \StdClass();
		$thisOrder->id = $order->id;
		$thisOrder->order_id = $order->order_id;

		$thisOrder->user_name = $order->user_name;
		$thisOrder->rider_name = $order->rider_name;
		$thisOrder->restaurant_name = $order->restaurant_name;
		$thisOrder->delivery_type = $order->delivery_type;
		$thisOrder->status = $order->status;
		$thisOrder->ScheduledAt = $order->ScheduledAt;
		$thisOrder->is_revised = $order->is_revised;
		$thisOrder->item_total = $order->bill_amount;
		$thisOrder->delivery_address = $order->delivery_address;
		$thisOrder->items = $order->items;

		return $thisOrder;
	}

	public function order_list($type)
	{

		if ($type == 'new') $status = [0];
		if ($type == 'processing') $status = [1, 2, -3];
		if ($type == 'pickup') $status = [3, 4, 5];
		if ($type == 'delivered') $status = [6, 7];
		if ($type == 'cancelled') $status = [9, 10];

		$data = DB::table('requests')
			->leftJoin('users', 'users.id', '=', 'requests.user_id')
			->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
			->leftJoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
			->select('requests.*', 'users.name as user_name', 'delivery_partners.name as rider_name', 'restaurants.restaurant_name')
			->where('requests.restaurant_id', '=', $this->authID)
			->whereIn('requests.status', $status)->orderBy('requests.id', 'desc')->get();

		foreach ($data as $key => $value) {
			$data[$key]->items = $this->order_items($value->id);
		}

		return $data;
	}

	public function new_requests(Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		$data = DB::table('requests')
			->leftJoin('users', 'users.id', '=', 'requests.user_id')
			->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
			->leftJoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
			->select('requests.id as request_id', 'user_id', 'requests.status', 'requests.restaurant_id')
			->where('requests.restaurant_id', '=', $this->authID)
			->whereIn('requests.status', [1, 2])->orderBy('requests.id', 'desc')->get();

		return response()->json(['status' => true, 'message' => 'Success', 'data' => $data], 200);
	}

	public function order_details($orderID)
	{

		$data = DB::table('requests')
			->leftJoin('users', 'users.id', '=', 'requests.user_id')
			->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
			->leftJoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
			->select(
				'requests.*',
				'users.name as user_name',
				'users.email as user_email',
				'users.phone as user_phone',
				'restaurants.restaurant_name',
				'restaurants.email as restaurant_email',
				'restaurants.phone as restaurant_phone',
				'restaurants.address as restaurant_address',
				'delivery_partners.name as rider_name',
				'delivery_partners.phone as rider_phone',
				'delivery_partners.email as rider_email'
			)
			->where('requests.restaurant_id', '=', $this->authID)
			->where('requests.id', '=', $orderID)->first();

		if ($data) {
			$data->items = $this->order_items($orderID);
		}

		return $data;
	}

	public function order_items($orderID)
	{

		$data = DB::table('request_detail')
			->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
			->leftJoin('food_quantity', 'food_quantity.id', '=', 'request_detail.food_quantity')
			->select(
				'request_detail.id as request_detail_id',
				'request_detail.quantity',
				'food_list.id as foodlist_id',
				'request_detail.food_quantity',
				'request_detail.food_quantity_price',
				'request_detail.status',
				'food_list.name as food_name',
				'food_quantity.name as food_quantity_name'
			)
			->where('request_detail.request_id', '=', $orderID)
			->get();

		foreach ($data as $key => $dt) {
			$data[$key]->addons = $a = $this->order_addons($dt->request_detail_id);
		}

		return $data;
	}

	public function order_addons($requestdetail_id)
	{

		$data = DB::table('requestdetail_addons')
			->select('name', 'price')
			->where('requestdetail_id', '=', $requestdetail_id)
			->get();

		return $data;
	}

	public function order_accept(Request $request, $request_id)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		if (!$this->isOrderExists($request_id)) {
			return response()->json(['status' => false, 'message' => 'Order not found'], 200);
		}

		if (!$this->isMyOrder($request_id)) {
			return response()->json(['status' => false, 'message' => 'Order does not belongs to this Store'], 200);
		}

		$order = DB::table('requests')->where('id', $request_id)->first();
		$user = DB::table('users')->where('id', $order->user_id)->first();
		sendNotification(
			[$user->device_token],
			'Your order accepted by the Store',
			'Your order accepted by the Store',
			['order_id' => $request_id]
		);

		$webToken = DB::table('user_fcm_token')->where('userid', $order->user_id)->where('type', 'web')->value('token');
		//send push notification to user
		if ($webToken) {
			$title = trans('constants.order_status_update');
			$this->user_send_push_notification($webToken, 'web', $title, 'Your order accepted by the restaurant', $request_id);
		}

		DB::table('requests')->where('id', $request_id)->update(['status' => 1]);

		$status_entry[] = array(
			'request_id' => $request_id,
			'status' => 1,
			'detail' => "Order Accepted by Store"
		);

		$this->trackorderstatus->insert($status_entry);

		return response()->json(['status' => true, 'message' => 'Success'], 200);
	}

	public function order_assign(Request $request, $request_id)
	{

		$Order = Order::find($request_id);
		$Order->acceptByRestaurant();

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		if (!$this->isOrderExists($request_id)) {
			return response()->json(['status' => false, 'message' => 'Order not found'], 200);
		}

		if (!$this->isMyOrder($request_id)) {
			return response()->json(['status' => false, 'message' => 'Order does not belongs to this store'], 200);
		}

		$trackorderstatus = $this->trackorderstatus;

		$request_data = DB::table('requests')->where('id', $request_id)->first();

		//begin takeaway
		if ($request_data->delivery_type != 1) {
			//return response()->json([ 'status' => false, 'message' => 'This is not a deliverable order. You cannot assign riders.'], 200); by me


			$data = DB::table('requests')->where('id', $request_id)->first();
			$user = DB::table('users')->where('id',  $data->user_id)->first();

			sendNotification(
				[$user->device_token],
				'Your order ready to pickup',
				'Your order ready to pickup',
				['order_id' => $request_id]
			);

			$webToken = DB::table('user_fcm_token')->where('userid', $data->user_id)->where('type', 'web')->value('token');
			//send push notification to user
			if ($webToken) {
				$title = trans('constants.order_status_update');
				$this->user_send_push_notification($webToken, 'web', $title, 'Your order ready to pickup', $request_id);
			}

			DB::table('requests')->where('id', $request_id)->update(['status' => 3, 'is_ready_to_pickup' => 1]);

			$status_entry[] = array(
				'request_id' => $request_id,
				'status' => 3,
				'detail' => "Order ready to pickup"
			);

			$this->trackorderstatus->insert($status_entry);
			return response()->json(['status' => true, 'request_status' => 3, 'message' => 'Food is ready to pickup.'], 200);
		} //end takeaway

		$restuarant_detail = DB::table('restaurants')->where('id', $request_data->restaurant_id)->first();

		$source_lat = $restuarant_detail->lat;
		$source_lng = $restuarant_detail->lng;


		$data = file_get_contents(FIREBASE_URL . "/available_providers/.json");
		$data = json_decode($data);


		if ($data == NULL || $data == "") {
			return response()->json(['status' => false, 'message' => 'No rider logged in'], 200);
		}

		$temp_driver = 0;
		$last_distance = 0;

		foreach ($data as $key => $value) {

			$driver_id = $key;

			$driver = DB::table('delivery_partners')->where('id', $driver_id)->first();
			if (!$driver || !$driver->status) {
				continue;
			}

			if ($driver->restaurant_id != null && $driver->restaurant_id != $request_data->restaurant_id) {
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

				if (
					isset($details['rows'][0]['elements'][0]['status']) &&
					$details['rows'][0]['elements'][0]['status'] != 'ZERO_RESULTS'
				) {

					$current_distance = $details['rows'][0]['elements'][0]['distance']['value'] ?? 0;
					$current_distance = $current_distance / 1000;

					if ($current_distance > 0 && $current_distance <= DEFAULT_RADIUS) {
						if ($temp_driver == 0) {
							$temp_driver = $driver_id;
							$last_distance = $current_distance;
						} else {
							if ($current_distance < $last_distance) {
								$temp_driver = $driver_id;
								$last_distance = $current_distance;
							}
						}
					}
				}
			} catch (Exception $e) {
				return response()->json(['status' => false, 'message' => 'Something went wrong while finding available riders.'], 200);
			}
		}

		if ($temp_driver == 0) {
			return response()->json(['status' => false, 'message' => 'No Providers available'], 200);
		}

		$user_data = DB::table('requests')->where('id', $request_id)->first();

		DB::table('requests')->where('id', $request_id)->update(['delivery_boy_id' => $temp_driver, 'status' => 2, 'assigned_at' => date('Y-m-d H:i:s')]);

		$check_status = $trackorderstatus->where('request_id', $request_id)->where('status', 2)->count();

		if ($check_status == 0) {
			$status_entry[] = [
				'request_id' => $request_id,
				'status' => 2,
				'detail' => "Food is being prepared"
			];

			$trackorderstatus->insert($status_entry);
		}

		$order = DB::table('requests')->where('id', $request_id)->first();
		$user = DB::table('users')->where('id', $order->user_id)->first();
		sendNotification(
			[$user->device_token],
			'Your order assigned to a rider',
			'Your order assigned to a rider',
			['order_id' => $request_id]
		);
		$webToken = DB::table('user_fcm_token')->where('userid', $order->user_id)->where('type', 'web')->value('token');
		//send push notification to user
		if ($webToken) {
			$title = trans('constants.order_status_update');
			$this->user_send_push_notification($webToken, 'web', $title, 'Your order assigned to a rider', $request_id);
		}

		// to insert into firebase
		$postdata = [
			'request_id' 	=> $request_id,
			'provider_id' 	=> (string) $temp_driver,
			'user_id' 		=> $user_data->user_id,
			'status' 		=> 2
		];
		$postdata = json_encode($postdata);
		$this->update_firebase($postdata, 'current_request', $request_id);

		// sending request to driver
		$postdata = [
			'request_id' 	=> $request_id,
			'user_id' 		=> $user_data->user_id,
			'status' 		=> 1
		];
		$postdata = json_encode($postdata);
		$this->update_firebase($postdata, 'new_request', $temp_driver);

		//  $title = "New Request Recieved";
		//  $message = array();
		// 	$message['title'] = "Taxi Request.4";
		//  $message['body'] = "New Request Received";
		//  $provider=4;
		//  $this->send_provider_push_notification($temp_driver, $title, $message,$provider);

		// end of request to driver
		$driver = DB::table('delivery_partners')->where('id', $temp_driver)->first();

		return response()->json([
			'status' => true,
			'message' => 'Success',
			'driver_name' => $driver->name,
			'request_status' => 2
		], 200);
	}

	public function order_cancel(Request $request, $request_id)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		if (!$this->isOrderExists($request_id)) {
			return response()->json(['status' => false, 'message' => 'Order not found'], 200);
		}

		if (!$this->isMyOrder($request_id)) {
			return response()->json(['status' => false, 'message' => 'Order does not belongs to this restaurant'], 200);
		}

		DB::table('requests')->where('id', $request_id)->update(['status' => 10]);

		$status_entry[] = [
			'request_id' => $request_id,
			'status' => 10,
			'detail' => $request->detail
		];

		$this->trackorderstatus->insert($status_entry);

		$order = DB::table('requests')->where('id', $request_id)->first();
		$user = DB::table('users')->where('id', $order->user_id)->first();
		sendNotification(
			[$user->device_token],
			'Your order has been cancelled by the restaurant',
			'Your order has been cancelled by the restaurant',
			['order_id' => $request_id]
		);
		$webToken = DB::table('user_fcm_token')->where('userid', $order->user_id)->where('type', 'web')->value('token');
		//send push notification to user
		if ($webToken) {
			$title = trans('constants.order_status_update');
			$this->user_send_push_notification($webToken, 'web', $title, 'Your order has been cancelled by the restaurant', $request_id);
		}
		return response()->json(['status' => true, 'request_status' => 10, 'message' => 'Success'], 200);
	}

	public function products(Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		$data = DB::table('food_list')->where('food_list.restaurant_id', $this->authID)
			->leftjoin('category', 'category.id', '=', 'food_list.category_id')
			->leftjoin('menu', 'menu.id', '=', 'food_list.menu_id')
			->select('food_list.id as food_id', 'food_list.*', 'category.*', 'menu.*', 'food_list.status')
			->get();

		return response()->json(['status' => true, 'message' => 'Success', 'Products' => $data], 200);
	}

	public function product_enable($food_id, Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		$data = DB::table('food_list')
			->where('food_list.restaurant_id', $this->authID)
			->where('food_list.id', $food_id)
			->update(['status' => 1]);

		return response()->json(['status' => false, 'message' => 'Success'], 200);
	}

	public function product_disable($food_id, Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		$data = DB::table('food_list')
			->where('food_list.restaurant_id', $this->authID)
			->where('food_list.id', $food_id)
			->update(['status' => 0]);

		return response()->json(['status' => true, 'message' => 'Success'], 200);
	}

	public function get_category_list(Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		$data = DB::table('category')->get();

		return response()->json(['status' => true, 'message' => 'Success', 'CategoryList' => $data], 200);
	}

	public function restaurant_menu(Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		$data = DB::table('menu')->where('menu.restaurant_id', $this->authID)->get();

		return response()->json(['status' => true, 'message' => 'Success', 'MenuList' => $data], 200);
	}

	public function list_addons(Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		$data = $this->add_ons->where('restaurant_id', $this->authID)->get();

		return response()->json(['status' => true, 'message' => 'Success', 'AddonList' => $data], 200);
	}

	public function change_open_status(Request $request, $status)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		DB::table('restaurants')->where('id', $this->authID)->update(['is_open' => $status]);

		return response()->json(['status' => true, 'message' => 'Success'], 200);
	}

	public function android_version()
	{
		$version = env('ANDROID_ADMIN_APP_VERSION');
		$response = response()->json(['status' => true, 'message' => 'Success', 'Version' => $version], 200);
		return $response;
	}

	public function ios_version()
	{
		$version = env('IOS_ADMIN_APP_VERSION');
		$response = response()->json(['status' => true, 'message' => 'Success', 'Version' => $version], 200);
		return $response;
	}

	public function order_picked(Request $request)
	{

		$validator = Validator::make(
			$request->all(),
			[
				'request_id' => 'required',
				'takeaway_code' => 'required'
			]
		);

		if ($validator->fails()) {
			$error_messages = implode(',', $validator->messages()->all());
			$response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
			$response = response()->json($response_array, 200);
			return $response;
		}

		$request_id = $request->request_id;

		$order = $this->foodrequest->where('id', $request_id)->first();

		if ($order->delivery_type != 2) {
			return response()->json(['status' => false, 'message' => 'This is not a takeaway order'], 200);
		}

		if ($order->status == 7 || !$order->takeaway_code) {
			return response()->json(['status' => false, 'message' => 'Order is already completed'], 200);
		}

		if ($order->takeaway_code != $request->takeaway_code) {
			return response()->json(['status' => false, 'message' => 'Takeaway code not valid'], 200);
		}


		$this->foodrequest->where('id', $request_id)->update(['takeaway_code' => '', 'status' => 7]);

		$status_entry = [
			'request_id' => $request_id,
			'status' => 7,
			'detail' => 'Order completed'
		];

		$this->trackorderstatus->insert($status_entry);

		return response()->json(['status' => true, 'message' => 'Order completed'], 200);
	}
	public function delayOrder(Request $request)
	{

		$Order = Order::find($request->orderID);
		$Order->delay((int) $request->delayMinutes);

		return response()->json(['status' => true, 'message' => "Order Delayed"], 200);
	}
	public function stock_enable($food_id, Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		$data = DB::table('food_list')
			->where('food_list.restaurant_id', $this->authID)
			->where('food_list.id', $food_id)
			->update(['out_of_stock' => 0]); // 0-in stock

		return response()->json(['status' => false, 'message' => 'Success'], 200);
	}

	public function stock_disable($food_id, Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => 'Invalid authentication'], 200);
		}

		$data = DB::table('food_list')
			->where('food_list.restaurant_id', $this->authID)
			->where('food_list.id', $food_id)
			->update(['out_of_stock' => 1]); // 1-out of stock

		return response()->json(['status' => true, 'message' => 'Success'], 200);
	}
	public function multi_product_enable($food_id, Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => trans('constants.Invalid_authentication')], 200);
		}
		$f_id = array_map('intval', explode(',', $food_id));

		foreach ($f_id as $f) {
			$data = DB::table('food_list')
				->where('food_list.restaurant_id', $this->authID)
				->where('food_list.id', $f)
				->update(['status' => 1]);
		}

		return response()->json(['status' => false, 'message' => trans('constants.Success')], 200);
	}
	public function multi_product_disable($food_id, Request $request)
	{

		if (!$this->isLoggedIn($request)) {
			return response()->json(['status' => false, 'message' => trans('constants.Invalid_authentication')], 200);
		}
		$f_id = array_map('intval', explode(',', $food_id));
		foreach ($f_id as $f) {
			$data = DB::table('food_list')
				->where('food_list.restaurant_id', $this->authID)
				->where('food_list.id', $f)
				->update(['status' => 0]);
		}
		return response()->json(['status' => true, 'message' => trans('constants.Success')], 200);
	}

	public function restaurant_timing(Request $request)
	{
		if (!$request->id) {
			return response()->json(['status' => false, 'message' => trans('constants.Invalid_authentication')], 200);
		}
		$rest = DB::table('restaurants')->where('id', $request->id)->first();
		$date = date("Y-m-d");
		$weekday = date('w');
		$weekday = $weekday == 0 ? 7 : $weekday;
		$opening_var = 'opening_time_' . $weekday;
		$closing_var = 'closing_time_' . $weekday;

		$opening_second_var = 'opening_second_time_' . $weekday;
		$closing_second_var = 'closing_second_time_' . $weekday;

		$opening_var1 = $rest->$opening_var;
		$closing_var1 =  $rest->$closing_var;
		$opening_second_var1 = $rest->$opening_second_var;
		$closing_second_var1 = $rest->$closing_second_var;

		$response_array = [
			'status' => true,
			'1st_open' => $opening_var1,
			'1st_close' => $closing_var1,
			'2nd_open' => $opening_second_var1,
			'2nd_close' => $closing_second_var1
		];
		$response = response()->json($response_array, 200);
		return $response;
	}
}
