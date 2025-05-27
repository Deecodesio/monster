<?php

namespace App\Http\Controllers\web;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\web\BaseController;
use DB;
use Carbon\Carbon;
use Exception;
use App\Http\Controllers\Order;

class OrderController extends BaseController
{
	public function index()
	{
		$session_user_id = Auth::guard('user')->user()->id;
		$user_detail 			= DB::table('users')->where('id', $session_user_id)->first();
		$order_list 			= $this->order_history();
		$current_order_list 	= $this->current_order_history();
		$upcoming_order_list 	= $this->upcoming_order_list();
		$favourite_list 		= $this->favourites();
		$address_detail 		= DB::table('delivery_address')->where('user_id', $session_user_id)->get();
		return view('user.orders')->with(compact('user_detail', 'upcoming_order_list', 'order_list', 'favourite_list', 'address_detail', 'current_order_list'));
	}

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
				'bill_amount' => $request_detail->bill_amount
			);

			$response_array = array('status' => true, 'request_id' => $request_id, 'ordered_time' => $ordered_time, 'order_id' => $order_id, 'restaurant_detail' => $restaurant_detail, 'user_detail' => $user_detail, 'address_detail' => $address_detail, 'bill_detail' => $bill_detail, 'food_detail' => $food_detail, 'request_status' => $request_status);
		}

		$response = response()->json($response_array, 200);
		return $response;
	}


	public function get_order_status(Request $request)
	{

		// $request_id = $request->request_id;

		$delivery_boy_id = $request->header('authId');

		$request_detail = DB::table('requests')->where('delivery_boy_id', $delivery_boy_id)
			->where('status', '!=', 10)
			->where('status', '!=', 7)
			->first();

		if (count($request_detail) != 0) {
			$order_id = $request_detail->order_id;

			$request_id = $request_detail->id;

			$ordered_time = $request_detail->ordered_time;

			$restaurant_detail = $this->restaurants::where('id', $request_detail->restaurant_id)->first();
			$restaurant_detail->image = BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image;



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
				'bill_amount' => $request_detail->bill_amount
			);

			$response_array = array('status' => true, 'request_id' => $request_id, 'ordered_time' => $ordered_time, 'order_id' => $order_id, 'restaurant_detail' => $restaurant_detail, 'user_detail' => $user_detail, 'address_detail' => $address_detail, 'bill_detail' => $bill_detail, 'food_detail' => $food_detail, 'request_status' => $request_status);
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
			array(
				'request_id' => 'required',
				'status' => 'required'
			)
		);

		if ($validator->fails()) {
			$error_messages = implode(',', $validator->messages()->all());
			$response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
		} else {
			$request_id = $request->request_id;
			$status = $request->status;
			$trackorderstatus = $this->trackorderstatus;
			$deliverypartners = $this->deliverypartners;
			$partner_id = $request->header('authId');

			$request_detail = $this->foodrequest->find($request_id);

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
				$message = "Delivery Boy Started towards Restaurant";
			} elseif ($status == 4) {
				$message = "Delivery Boy Reached restaurant";
			} elseif ($status == 5) {
				$message = "Started towards Customer";
			} elseif ($status == 6) {
				$message = "Food delivered";

				$partner_detail = $deliverypartners::where('id', $partner_id)->first();
				$partner_earnings = $partner_detail->total_earnings + $request_detail->delivery_boy_commision;
				$partner_detail->total_earnings = $partner_earnings;
				$partner_detail->pending_payout = $partner_detail->pending_payout + $request_detail->delivery_boy_commision;
				$partner_detail->save();

				//commission update in admin 
				$this->admin->find(1)->increment('total_earnings', $request_detail->admin_commision);

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
			}

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

			//send push notification to user
			if (isset($request_detail->Users->device_token) && $request_detail->Users->device_token != '') {
				$title = trans('constants.order_status_update');
				$this->user_send_push_notification($request_detail->Users->device_token, $request_detail->Users->device_type, $title, $message, $request_id);
			}

			$response_array = array('status' => true, 'request_id' => $request_id, 'order_id' => $request_detail->order_id, 'message' => $message);
		}

		$response = response()->json($response_array, 200);
		return $response;
	}

	public function cancel_request(Request $request)
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
			$provider_id = $request->header('authId');

			$old_provider = 0;


			$request_data = DB::table('requests')
				->where('id', $request_id)->first();

			// delete request to driver 
			$temp_driver = $provider_id;
			$header = array();
			$header[] = 'Content-Type: application/json';
			$postdata = array();
			$postdata['request_id'] = $request_id;
			$postdata['user_id'] = $request_data->user_id;
			$postdata['status'] = 1;
			$postdata = json_encode($postdata);

			$ch = curl_init(FIREBASE_URL . "/new_request/$temp_driver.json");
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
			if ($data != NULL && $data != "") {
				foreach ($data as $key => $value) {
					# code...
					$driver_id = $key;
					if ($old_provider == 0) {
						$old_provider = -1;
					}
					if ($driver_id != $old_provider && $driver_id != $provider_id) {
						if ($value != NULL && $value != "") {
							$driver_lat = $value->lat;
							$driver_lng = $value->lng;


							try {
								// $q = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$source_lat,$source_lng&destinations=$driver_lat,$driver_lng&mode=driving&sensor=false";
								$q = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$source_lat,$source_lng&destinations=$driver_lat,$driver_lng&mode=driving&sensor=false&key=AIzaSyCGX6aGjOeMptlBNc0WF3vhm0SPMl1vNBE";
								$json = file_get_contents($q);
								$details = json_decode($json, TRUE);

								// var_dump($details); exit;
								if ($details['rows'][0]['elements'][0]['status'] != 'ZERO_RESULTS') {
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
								}
							} catch (Exception $e) {
							}
						}
					}

					//print_r($value->lat); exit;
				}
			}

			if ($temp_driver != 0) {
				# code...

				$user_data = DB::table('requests')
					->where('id', $request_id)
					->first();

				DB::table('requests')->where('id', $request_id)->update(['delivery_boy_id' => $temp_driver, 'status' => 2]);



				// to insert into firebase
				$header = array();
				$header[] = 'Content-Type: application/json';
				$postdata = array();
				// $postdata['id'] = $request_id;
				$postdata['request_id'] = $request_id;
				$postdata['provider_id'] = (string)$temp_driver;
				$postdata['user_id'] = $user_data->user_id;
				$postdata['status'] = 2;
				$postdata = json_encode($postdata);

				$ch = curl_init(FIREBASE_URL . "/current_request/$request_id.json");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				$result = curl_exec($ch);
				curl_close($ch);

				// sending request to driver 
				$header = array();
				$header[] = 'Content-Type: application/json';
				$postdata = array();
				$postdata['request_id'] = $request_id;
				$postdata['user_id'] = $user_data->user_id;
				$postdata['status'] = 1;
				$postdata = json_encode($postdata);

				$ch = curl_init(FIREBASE_URL . "/new_request/$temp_driver.json");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				$result = curl_exec($ch);
				curl_close($ch);

				//  $title = "New Request Recieved";
				//         $message = array();
				// $message['title'] = "Taxi Request.4";
				//        $message['body'] = "New Request Received";

				//        $provider=4;
				//            $this->send_provider_push_notification($temp_driver, $title, $message,$provider);



			} else {
				# code...
				$title = "No Providers available";

				$user_data = DB::table('requests')
					->where('id', $request_id)
					->first();

				DB::table('requests')->where('id', $request_id)->update(['delivery_boy_id' => 0, 'status' => 1]);


				// to insert into firebase
				$header = array();
				$header[] = 'Content-Type: application/json';
				$postdata = array();
				// $postdata['id'] = $request_id;
				$postdata['request_id'] = $request_id;
				$postdata['provider_id'] = (string)0;
				$postdata['user_id'] = $user_data->user_id;
				$postdata['status'] = 1;
				$postdata = json_encode($postdata);

				$ch = curl_init(FIREBASE_URL . "/current_request/$request_id.json");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				$result = curl_exec($ch);
				curl_close($ch);
				//         $message = array();
				// $message['title'] = "Taxi Request";
				//        $message['body'] = "No Providers available";


				//           $this->send_push_notification($request->header('authId'), $title, $message);

			}

			$response_array = array('status' => true, 'message' => 'Request Cancelled Successfully');
		}

		$response = response()->json($response_array, 200);
		return $response;
	}

	public function order_history()
	{
		$session_user_id = Auth::guard('user')->user()->id;

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
			if (!empty($restaurant_detail)) {

				$order_list[] = array(
					'request_id' => $key->id,
					'order_id' => $key->order_id,
					'restaurant_id' => $restaurant_detail->id,
					'restaurant_name' => $restaurant_detail->restaurant_name,
					'restaurant_image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image,
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
					'restaurant_address' => $restaurant_detail->address
				);
			}
		}

		// dd($order_list);

		return $order_list;
	}

	public function current_order_history()
	{
		$session_user_id = Auth::guard('user')->user()->id;

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
			if (!empty($restaurant_detail)) {

				$order_list[] = array(
					'request_id' => $key->id,
					'order_id' => $key->order_id,
					'status' => $key->status,
					'restaurant_id' => $restaurant_detail->id,
					'restaurant_name' => $restaurant_detail->restaurant_name,
					'restaurant_image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image,
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
					'restaurant_address' => $restaurant_detail->address
				);
			}
		}

		return $order_list;
	}

	public function upcoming_order_list()
	{
		$session_user_id = Auth::guard('user')->user()->id;
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

			if (!empty($restaurant_details)) {

				$upcoming_order_list[] = array(
					'request_id' => $key->id,
					'order_id' => $key->order_id,
					'restaurant_id' => $restaurant_details->id,
					'restaurant_name' => $restaurant_details->restaurant_name,
					'restaurant_image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_details->image,
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
					'restaurant_address' => $restaurant_details->address
				);
			}
		}
		return $upcoming_order_list;
	}

	public function favourites()
	{
		$session_user_id = Auth::guard('user')->user()->id;
		$favouritelist 	= $this->favouritelist;
		$restaurants 	= $this->restaurants;
		$data = $favouritelist::where('user_id', $session_user_id)->get();
		$favourite_list = array();
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

					$is_open = restaurant_is_open($restaurant_detail->id);

					$favourite_list[] = array(
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
		}
		return $favourite_list;
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
			array(
				'bill_amount' => 'required',
				'promocode' => 'required',
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
			$get_promocode = $this->promocode->where('code', $request->promocode)
				->where('status', 1)
				->whereDate('available_from', '<=', Carbon::now())
				->whereDate('valid_till', '>=', Carbon::now())->first();
			if (!empty($get_promocode)) {
				//check total usage of promocode
				$total_usage = $this->foodrequest->where('coupon_code', $request->promocode)->where('status', '!=', 10)
					->count();

				//check the promocode usage by user
				$user_usage = $this->foodrequest->where('coupon_code', $request->promocode)
					->where('status', '!=', 10)->where('user_id', $user_id)
					->count();

				if ($total_usage >= $get_promocode->total_use) {
					$response_array = array('status' => false, 'error_code' => 101, 'message' => trans('constants.promocode_used'));
				} elseif ($user_usage >= $get_promocode->use_per_customer) {
					$response_array = array('status' => false, 'error_code' => 101, 'message' => trans('constants.promocode_used'));
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
			} else {
				$response_array = array('status' => false, 'error_code' => 101, 'message' => trans('constants.invalid_promo'));
			}
			$response = response()->json($response_array, 200);
			return $response;
		}
	}

	public function delete_address(Request $request)
	{
		$id = $request->id;
		DB::table('delivery_address')->where('id', $id)->delete();
		return back()->with('flash_success', 'Address deleted successfully.');
	}

	public function track_order($request_id)
	{
		//dd($request_id);
		$session_user_id 	= Auth::guard('user')->user()->id;

		$trackorderstatus 	= $this->trackorderstatus;

		$check_request 		= $this->foodrequest::where([
			'id' => $request_id,
			'user_id' => $session_user_id
		])->first();
		if (!$check_request) throw new Exception("Could not find the order.", 1);

		// $check_request 		= $this->foodrequest::where('user_id', $session_user_id)->where('id', $request_id)->first();

		$restaurant_detail 	= DB::table('restaurants')->where('id', $check_request->restaurant_id)->first();

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
		// dd($order_detail);
		$order_list_detail = array();
		foreach ($order_detail as $k) {
			if (isset($k->FoodQuantity)) $k->FoodQuantity->price = $k->food_quantity_price;
			$order_list_detail[] = array(
				'food_id' => (!empty($k->Foodlist) ? $k->Foodlist->id : ""),
				'food_name' => (!empty($k->Foodlist) ? $k->Foodlist->name : ""),
				'food_quantity' => $k->quantity,
				'tax' => (!empty($k->Foodlist) ? $k->Foodlist->tax : ""),
				//'item_price' => (!empty($k->Foodlist) ? $k->food_quantity_price : 0) * $k->quantity,
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
		//dd($check_request);
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
			'status' => $check_request->status,
			'is_cod' => $check_request->is_cod,
			'item_list' => $order_list_detail,
			'delivery_address' => $check_request->delivery_address,
			'door' => $deliver_add->flat_no,
			'landmark' => $deliver_add->landmark,
			'delivery_boy_id' => $delivery_boy_id,
			'delivery_boy_name' => $delivery_boy_name,
			'delivery_boy_image' => $delivery_boy_image,
			'delivery_boy_phone' => $delivery_boy_phone,
			'restaurant_address' => $restaurant_detail->address,
			'restaurant_lat' => $restaurant_detail->lat,
			'restaurant_lng' => $restaurant_detail->lng,
			'user_lat' => $check_request->d_lat,
			'user_lng' => $check_request->d_lng,

		);
		//dd($order_status);
		$tracking_detail = $trackorderstatus::where('request_id', $request_id)->get();
		$datas = file_get_contents(FIREBASE_URL . "/available_providers/.json");
		$data = json_decode($datas);
		$locations = [];
		$i = 1;
		// dd($order_status);
		//dd(FIREBASE_URL . "/available_providers/.json");
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
				//$locations[] = [ucfirst($restaurant_detail->restaurant_name), $restaurant_detail->lat, $restaurant_detail->lng, $i++];
			}
		}

		$locations = json_encode($locations);

		$center = [];
		$user = [];
		if (!$center) {
			$center = [$check_request->d_lat, $check_request->d_lng];
		}
		if (!isset($rider_id)) {
			$rider_id = null;
		}
		// if (empty($locations)) {
		// 	$locations[] = [ucfirst($restaurant_detail->restaurant_name), $restaurant_detail->lat, $restaurant_detail->lng, $i++];
		// }
		// //dd($order_status[0]['user_lat']);
		// //$locations[0][0]=$order_status[0]['user_lat'];
		// $locations[1][0] = $check_request->delivery_address;
		$user[0] = $order_status[0]['user_lat'];
		$user[1] = $order_status[0]['user_lng'];
		$user = json_encode($user);
		// $locations = json_encode($locations); //dd($locations);
		// $center = json_encode($center);
		// return view('user.track_order')->with(compact('order_status', 'tracking_detail', 'locations', 'center'));
		return view('user.track_order')->with(compact('order_status', 'rider_id', 'tracking_detail', 'locations', 'center', 'user'));
	}

	public function view_order_bill($request_id, Request $request)
	{
		$data = $this->foodrequest->where('id', $request_id)
			->with('Restaurants.city_list')
			->with('Restaurants.Area')
			->first();

		$restaurant = DB::table('restaurants')->where('id', $data->restaurant_id)->first();


		if (!$data || $data->status == -1) {
			return 'Order not found';
		}
		return view('admin/view_order_bill', compact('data', 'restaurant'));
	}

	public function review(Request $request)
	{
		$order_id = $request->id;
		return view('user.orders_review');
	}

	public function cancel_order(Request $request)
	{
		$Order = Order::find($request->cancelOrderID);
		$Order->cancelByUser($request->reason);
		return back();
	}
}
