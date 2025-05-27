<?php

namespace App\Http\Controllers\store;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Order;
use Carbon\Carbon;
use DB;
use Log;
use File;

class OrderController extends BaseController
{

	public function Orders_list(Request $request, $type, $id)
	{

		if ($type == 'new') $status = [0];
		if ($type == 'scheduled') $status = [0];
		if ($type == 'processing') $status = [1, 2, -3];
		if ($type == 'pickup') $status = [3, 4, 5];
		if ($type == 'delivered') $status = [6, 7];
		if ($type == 'cancelled') $status = [9, 10, 11, 12, 13, 14];
		if ($type == 'failed') $status = [-2];
		if ($type == 'abandon') $status = [-1];
		if ($type == 'refund') $status = [10, 12, 13, 14];
		//   dd($id);
		//   $explode_id = array_map('intval', explode(',',$id));
		// $explode_id = json_decode($request->id, true);
		// $explode_id = explode(',', $request->data);
		//   dd($explode_id);
		$data = DB::table('requests')

			->leftJoin('users', 'users.id', '=', 'requests.user_id')
			->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
			->leftJoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
			->select('requests.*', 'users.name as user_name', 'users.device_type', 'delivery_partners.name as rider_name', 'requests.wallet_amount as wallet_amounts', 'restaurants.restaurant_name', 'users.phone')

			->orderBy('requests.id', 'desc')
			->whereIn('requests.status', $status)
			->where('requests.restaurant_id', $id)
			->get();

		// if ($type == 'scheduled') {
		// 	$data = $this->foodrequest->whereIn('status', $status)->where('ScheduledAt', '!=', null)->where('restaurant_id', $request->id)->orderBy('id', 'desc')->get();
		// } else {
		// 	$data = $this->foodrequest->whereIn('status', $status)->where('restaurant_id', $request->id)->orderBy('id', 'desc')->get();
		// }

		//   dd($data);
		foreach ($data as $datas) {
			if ($datas->delivery_address != "") {
				$decodedText = html_entity_decode($datas->delivery_address);
				$datas->delivery_address = $decodedText;
			}
		}
		return response()->json($data);
	}





















	public function neworder_list(Request $request)
	{

		$restaurant_id = $request->session()->get('res_id');
		$role = $request->session()->get('res_role');

		if ($role == 2) {

			$data = DB::table('requests')->where('requests.restaurant_id', $restaurant_id)
				->join('users', 'users.id', '=', 'requests.user_id')
				->select('requests.id as request_id', 'requests.status as order_status', 'users.name as user_name', 'requests.*', 'users.*')
				->orderBy('request_id', 'desc')
				->get();
			// print_r($data); exit;
			$data1 = DB::table('requests')->where('requests.restaurant_id', $restaurant_id)
				->join('request_detail', 'request_detail.request_id', '=', 'requests.id')
				->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
				->select('food_list.name as food_name', 'request_detail.*', 'food_list.*', 'requests.id')
				->get();
		} else {
			$data = DB::table('requests')->join('users', 'users.id', '=', 'requests.user_id')
				->select('requests.id as request_id', 'requests.status as order_status', 'users.name as user_name', 'requests.*', 'users.*')
				->orderBy('request_id', 'desc')
				->get();
			// print_r($data); exit;
			$data1 = DB::table('requests')->join('request_detail', 'request_detail.request_id', '=', 'requests.id')
				->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
				->select('food_list.name as food_name', 'request_detail.*', 'food_list.*', 'requests.id')
				->get();
		}

		return view('store/neworder_list', ['data' => $data, 'data1' => $data1]);
	}

	public function accept_request(Request $request)
	{
		$Order = Order::find($request->id);
		$Order->acceptByRestaurant();


		$message = "Order Accepted Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;

		// return redirect('/store/orders/new')->with('success', 'Order accepted successfully');
	}

	public function delayOrder($orderID, Request $request)
	{

		$Order = Order::find($orderID);
		$Order->delay((int) $request->delayMinutes);

		return response()->json(['status' => true, 'message' => "Order Delayed"], 200);
	}

	public function assign_orders_automatically()
	{
		$orders_list = DB::table('requests')->where(['status' => 2])->get();

		$res = [];
		foreach ($orders_list as $o) {

			if (!$o->assigned_at) {
				continue;
			}

			$assigned_timestamp = (strtotime($o->assigned_at));
			$interval_minutes = 1;
			$current_timestamp = (strtotime(date('Y-m-d H:i:s'))) - ($interval_minutes * 60);

			$interval_timestamp = $current_timestamp - $assigned_timestamp;

			if ($interval_timestamp <= 0) {
				continue;
			}

			$res[] = app('App\Http\Controllers\store\OrderController')->assign_order($o->id);
		}

		return $res;
	}

	public function assign_order($request_id)
	{

		$trackorderstatus = $this->trackorderstatus;

		$request_data = DB::table('requests')->where('id', $request_id)->first();
		$restaurant_id = $request_data->restaurant_id;

		if (!$request_data) {
			return ['status' => false, 'message' => trans('constants.order_not_found')];
		}

		$restuarant_detail = $this->restaurants->where('id', $restaurant_id)->first();
		$business = DB::table('business_type')->where('id', $restuarant_detail->business_type)->first();

		if (!$restuarant_detail) {
			return ['status' => false, 'message' => trans('constants.restaurant_not_found')];
		}

		$source_lat = $restuarant_detail->lat;
		$source_lng = $restuarant_detail->lng;

		$data = file_get_contents(FIREBASE_URL . "/available_providers/.json");
		$data = json_decode($data);
		if ($data == NULL || $data == "") {
			return ['status' => false, 'message' => trans('It seems riders are not logged in')];
		}

		$temp_driver = 0;
		$last_distance = 0;

		foreach ($data as $key => $value) {

			$driver_id = $key;
			if ($business->layout_id == 3) {
				$driver = DB::table('delivery_partners')
					->where('delivery_partners.id', $driver_id)
					->whereIn('restaurants.carrier_type', $request_data->carrier)
					->whereIn('delivery_partner_details.services', array($business->layout_id))
					->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
					->join('restaurants', 'restaurants.id', '=', $restuarant_detail->id)
					->select('delivery_partners.*', 'restaurants.*',  'delivery_partner_details.*')
					->first();
			} else {
				// $driver = DB::table('delivery_partners')->where('id', $driver_id)->first();

				$driver = DB::table('delivery_partners')
					->where('delivery_partners.id', $driver_id)
					->whereIn('delivery_partner_details.services', array($business->layout_id))
					->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
					->select('delivery_partners.*',   'delivery_partner_details.*')
					->first();
			}






			if (!$driver || !$driver->status) {
				continue;
			}

			if ($driver->restaurant_id != null && $driver->restaurant_id != $request_data->restaurant_id) {
				continue;
			}

			if ($driver_id == $request_data->delivery_boy_id) {
				continue;
			}

			if ($value == NULL || $value == "") {
				continue;
			}

			$driver_lat = $value->lat;
			$driver_lng = $value->lng;

			try {
				$q = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$driver_lat,$driver_lng&destinations=$source_lat,$source_lng&mode=driving&sensor=false&key=" . GOOGLE_API_KEY;
				$json = file_get_contents($q);

				$details = json_decode($json, TRUE);

				if (isset($details['status']) && $details['status'] != 'OK') {
					throw new \Exception($details['status']);
				}

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
			} catch (\Exception $e) {
				return ['status' => false, 'message' => trans('constants.something_went_wrong_while_finding_available_riders') . $e->getMessage()];
			}
		}

		if ($temp_driver == 0) {
			return ['status' => false, 'message' => trans('constants.no_driver_available_within_radius', ['distance' => DEFAULT_RADIUS])];
		}

		$driver = DB::table('delivery_partners')->where('id', $temp_driver)->first();
		// if (!empty($driver->device_token)) {
		// 	sendNotification(
		// 		[$driver->device_token],
		// 		trans('constants.notification_rider_new_order_title'),
		// 		trans('constants.notification_rider_new_order_message'),
		// 		['order_id' => $request_id]
		// 	);
		// }

		if ($driver->device_type == "ios") {
			if (!empty($driver->device_token)) {
				sendNotification(
					[$driver->device_token],
					trans('constants.notification_rider_new_order_title'),
					trans('constants.you_assigned_with_new_order'),
					['order_id' => $request_id]
				);
			}
		} else {
			if (!empty($driver->device_token)) {
				sendNotification_driver(
					[$driver->device_token],
					trans('constants.notification_rider_new_order_title'),
					trans('constants.you_assigned_with_new_order'),
					['order_id' => $request_id]
				);
			}
		}

		$user_data = DB::table('requests')->where('id', $request_id)->first();

		DB::table('requests')->where('id', $request_id)->update(['delivery_boy_id' => $temp_driver, 'status' => 2, 'assigned_at' => date('Y-m-d H:i:s')]);

		$check_status = $trackorderstatus->where('request_id', $request_id)->where('status', 2)->count();

		if ($check_status == 0) {
			$status_entry[] = [
				'request_id' => $request_id,
				'status' => 2,
				'detail' => "Your Order is being prepared"
			];

			$trackorderstatus->insert($status_entry);
		}

		$order = DB::table('requests')->where('id', $request_id)->first();
		$user = DB::table('users')->where('id', $order->user_id)->first();
		sendNotification(
			[$user->device_token],
			trans('constants.order_assigned_to_rider'),
			trans('constants.notification_user_rider_assigned'),
			['order_id' => $request_id]
		);

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
		return ['status' => false, 'message' => trans('constants.order_assigned_to_rider') . $driver->name];
	}

	public function assign_request(Request $request)
	{

		$Order = Order::find($request->id);
		$Order->acceptByRestaurant();



		$trackorderstatus = $this->trackorderstatus;

		$request_data = DB::table('requests')->where('id', $request->id)->first();
		$restaurant_id = $request_data->restaurant_id;
		if (!$request_data) {
			$message = "Order Not Found";
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;

			// return redirect('/store/orders/processing')->with('error', trans('constants.order_not_found'));
		}

		$restuarant_detail = $this->restaurants->where('id', $restaurant_id)->first();
		$business = DB::table('business_type')->where('id', $restuarant_detail->business_type)->first();
		if (!$restuarant_detail) {
			$message = "Restaurant Not Found";
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
			// return redirect('/store/orders/processing')->with('error', trans('constants.restaurant_not_found'));
		}

		$source_lat = $restuarant_detail->lat;
		$source_lng = $restuarant_detail->lng;

		$data = file_get_contents(FIREBASE_URL . '/available_providers/.json');

		$data = json_decode($data);
		if ($data == NULL || $data == "") {

			$message = "It Seems Riders Are Not Logged In.";
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
			// return redirect('/store/orders/processing')->with('error', 'It seems riders are not logged in.');
		}

		$temp_driver = 0;
		$last_distance = 0;

		foreach ($data as $key => $value) {

			$driver_id = $key;

			if ($value != null) {
				if ($business->layout_id == 3) {
					$this_driver = DB::table('delivery_partners')
						->where('delivery_partners.id', $driver_id)
						//  ->whereJsonContains('restaurants.carrier_type', $request_data->carrier)
						//  ->whereJsonContains('delivery_partner_details.services', $business->layout_id)
						->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
						->select('delivery_partners.*',  'delivery_partner_details.*')
						->first();
					if ($this_driver) {
						foreach (json_decode($this_driver->services) as $d) {

							if ($d == $business->layout_id) {
								$driver = DB::table('delivery_partners')
									->where('delivery_partners.id', $driver_id)
									->where('vehicle.carrier_type', $request_data->carrier)
									->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
									->join('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
									->select('delivery_partners.*', 'vehicle.*',  'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
									->first();
								if ($driver) {
									$restaurant1 = DB::table('restaurants')->where('id', $restaurant_id)->first();
									foreach (json_decode($restaurant1->carrier_type) as $d1) {
										if ($d1 == $driver->carrier_type) {
											$driver = DB::table('delivery_partners')
												->where('delivery_partners.id', $driver_id)
												->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
												->select('delivery_partners.*',   'delivery_partner_details.*')
												->first();
											break;
										} else {
											$driver = [];
										}
									}
								} else {
									$driver = [];
								}
							} else {

								$driver = [];
							}
						}
					} else {
						$driver = [];
					}
				} else {
					// $driver = DB::table('delivery_partners')->where('id', $driver_id)->first();

					$driver = DB::table('delivery_partners')
						->where('delivery_partners.id', $driver_id)
						//  ->whereIn('delivery_partner_details.services', array($business->layout_id))
						->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
						->select('delivery_partners.*',   'delivery_partner_details.*')
						->first();

					if ($driver) {
						if (json_decode($driver->services) != null) {
							foreach (json_decode($driver->services) as $d) {

								if ($d == $business->layout_id) {
									$driver = DB::table('delivery_partners')
										->where('delivery_partners.id', $driver_id)
										//  ->whereIn('delivery_partner_details.services', array($business->layout_id))
										->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
										->select('delivery_partners.*',   'delivery_partner_details.*')
										->first();
									break;
								} else {

									$driver = [];
								}
							}
						}
					} else {
						$driver = [];
					}
				}













				// $driver = DB::table('delivery_partners')->where('id', $driver_id)->first();
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
					$q = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$driver_lat,$driver_lng&destinations=$source_lat,$source_lng&mode=driving&sensor=false&key=" . GOOGLE_API_KEY;
					$json = file_get_contents($q);

					$details = json_decode($json, TRUE);

					if (isset($details['status']) && $details['status'] != 'OK') {
						throw new \Exception($details['status']);
					}

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
				} catch (\Exception $e) {


					$message = $e->getMessage();
					$status = false;
					$response_Array = json_encode(['message' => $message, 'status' => $status]);
					return $response_Array;

					// return redirect('/store/orders/processing')->with('error', trans('constants.something_went_wrong_while_finding_available_riders') . $e->getMessage());
				}
			}
		}

		if ($temp_driver == 0) {

			$message = "No Driver Available Within Radius";
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;

			// return redirect('/store/orders/processing')->with('error', trans('constants.no_driver_available_within_radius', ['distance' => DEFAULT_RADIUS]));
		}

		$driver = DB::table('delivery_partners')->where('id', $temp_driver)->first();
		if ($driver->device_type == "ios") {
			if (!empty($driver->device_token)) {
				sendNotification(
					[$driver->device_token],
					trans('constants.notification_rider_new_order_title'),
					trans('constants.you_assigned_with_new_order'),
					['order_id' =>  $request->id]
				);
			}
		} else {
			if (!empty($driver->device_token)) {
				sendNotification_driver(
					[$driver->device_token],
					trans('constants.notification_rider_new_order_title'),
					trans('constants.you_assigned_with_new_order'),
					['order_id' =>  $request->id]
				);
			}
		}

		$user_data = DB::table('requests')->where('id',  $request->id)->first();

		DB::table('requests')->where('id',  $request->id)->update(['delivery_boy_id' => $temp_driver, 'status' => 2, 'assigned_at' => date('Y-m-d H:i:s')]);

		$check_status = $trackorderstatus->where('request_id',  $request->id)->where('status', 2)->count();

		if ($check_status == 0) {
			$status_entry[] = [
				'request_id' => $request->id,
				'status' => 2,
				'detail' => "Rider Assigned"
			];

			$trackorderstatus->insert($status_entry);
		}

		$order = DB::table('requests')->where('id', $request->id)->first();
		$user = DB::table('users')->where('id', $order->user_id)->first();
		sendNotification(
			[$user->device_token],
			trans('constants.notification_user_rider_assigned'),
			trans('constants.notification_user_rider_assigned'),
			['order_id' => $request->id]
		);

		// to insert into firebase
		$postdata = [
			'request_id' 	=> $request->id,
			'provider_id' 	=> (string) $temp_driver,
			'user_id' 		=> $user_data->user_id,
			'status' 		=> 2
		];
		$postdata = json_encode($postdata);
		$this->update_firebase($postdata, 'current_request', $request->id);

		// sending request to driver
		$postdata = [
			'request_id' 	=> $request->id,
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
		$message = "Order Assigned To Rider";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;

		// return redirect('/store/orders/processing')->with('success', trans('constants.order_assigned_to_rider') . $driver->name);
	}

	public function assign_driver_manually(Request $request)
	{


		$trackorderstatus = $this->trackorderstatus;

		$temp_driver = $request->delivery_id;
		$request_id = $request->order_id;

		$driver = DB::table('delivery_partners')->where('id', $temp_driver)->first();
		if (!$driver) {
			$message = "Driver Not Found";
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
			// return redirect('/store/orders/processing')->with('error', trans('constants.driver_not_found'));
		}

		if ($driver->device_type == "ios") {
			if (!empty($driver->device_token)) {
				sendNotification(
					[$driver->device_token],
					trans('constants.notification_rider_new_order_title'),
					trans('constants.you_assigned_with_new_order'),
					['order_id' =>  $request_id]
				);
			}
		} else {
			if (!empty($driver->device_token)) {
				sendNotification_driver(
					[$driver->device_token],
					trans('constants.notification_rider_new_order_title'),
					trans('constants.you_assigned_with_new_order'),
					['order_id' =>  $request_id]
				);
			}
		}

		$user_data = DB::table('requests')->where('id', $request_id)->first();

		DB::table('requests')->where('id', $request_id)->update(['delivery_boy_id' => $temp_driver, 'status' => 2]);

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
			trans('constants.notification_user_rider_assigned'),
			trans('constants.notification_user_rider_assigned'),
			['order_id' => $request_id]
		);

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
		$message = "Order Assigned To Rider";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
		// return redirect('/store/orders/processing')->with('success', trans('constants.order_assigned_to_rider', ['rider' => $driver->name]));
	}

	public function ready_pickup_request(Request $request, $id)
	{
		$data = DB::table('requests')->where('id', $id)->first();
		$user = DB::table('users')->where('id',  $data->user_id)->first();

		//DB::table('requests')->where('id', $id)->update(['status' => 3, 'is_ready_to_pickup' => 1]); by me
		//user noti

		sendNotification(
			[$user->device_token],
			trans('constants.order_ready_to_pickup'),
			trans('constants.order_ready_to_pickup'),
			['order_id' => $id]
		);

		$webToken = DB::table('user_fcm_token')->where('userid', $data->user_id)->where('type', 'web')->value('token');
		//send push notification to user
		if ($webToken) {
			$title = trans('constants.order_status_update');
			$this->user_send_push_notification($webToken, 'web', $title, 'Your order ready to pickup', $id);
		}

		DB::table('requests')->where('id', $id)->update(['status' => 3, 'is_ready_to_pickup' => 1]);

		$status_entry[] = array(
			'request_id' => $id,
			'status' => 1,
			'detail' => "Order ready to pickup"
		);

		$this->trackorderstatus->insert($status_entry);

		return redirect('/store/orders/processing')->with('success', trans('constants.order_assigned_successfully'));
	}

	public function takeaway_complete(Request $request)
	{

		$request_id = $request->request_id;

		$order = DB::table('requests')->where('id', $request_id)->first();

		if (!$order) {
			// return back()->with('error', trans('constants.order_not_found'));
			$message = "Order Not Found";
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}

		if ($request->takeaway_code != $order->takeaway_code) {
			// return back()->with('error', trans('constants.code_does_not_match'));
			$message = "Code Does Not Match";
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}

		$req = DB::table('requests')->where('id', $request_id)->update(['takeaway_code' => '', 'status' => 7]);

		$status_entry = [
			'request_id' => $request_id,
			'status' => 9,
			'detail' => 'Order completed',
		];

		$this->trackorderstatus->insert($status_entry);

		$message = "Takeaway Order  Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}

	/** 
	 * to get order list based on status
	 *
	 * @param object $request, string $type
	 *
	 * @return view page with details
	 */
	public function order_list(Request $request, $type)
	{
		$privilege = $request->session()->get('privilege');
		$restaurant_id = $request->session()->get('res_id');
		$role = $request->session()->get('res_role');
		if ($type == 'new') $status = [0];
		if ($type == 'scheduled') $status = [0];
		if ($type == 'processing') $status = [1, 2];
		if ($type == 'pickup') $status = [3, 4, 5];
		if ($type == 'delivered') $status = [6, 7];
		if ($type == 'cancelled') $status = [9, 10, 11];
		if ($type == 'failed') $status = [-1];

		if ($type == 'scheduled') {
			$data = $this->foodrequest->whereIn('status', $status)->where('ScheduledAt', '!=', null)->where('restaurant_id', $restaurant_id)->orderBy('id', 'desc')->get();
		} else {
			$data = $this->foodrequest->whereIn('status', $status)->where('restaurant_id', $restaurant_id)->orderBy('id', 'desc')->get();
		}


		$newdata = [];

		foreach ($data as $key => $val) {
			if ($type == 'processing') {
				if ($val->delivery_type == 2 && $val->is_ready_to_pickup) {
					continue;
				}
			}
			$payment = DB::table('payments')->where('request_id', $val->id)->first();
			if ($payment) {
				$data[$key]->is_cod = 0;
			} else {
				$data[$key]->is_cod = 1;
			}

			$newdata[] = $data;
		}

		$new_title = '';
		if ($type == 'new') {
			$new_title = trans('constants.new_orders_list');
		} else if ($type == 'scheduled') {
			$new_title = trans('constants.scheduled');
		} else if ($type == 'processing') {
			$new_title = trans('constants.processing_list');
		} else if ($type == 'pickup') {
			$new_title = trans('constants.pickup_list');
		} else if ($type == 'delivered') {
			$new_title = trans('constants.delivered_list');
		} else if ($type == 'cancelled') {
			$new_title = trans('constants.cancelled_list');
		}

		$cancellation_reasons = DB::table('cancellation_reason')->where('cancellation_for', 1)->get();
		$permission = permission($privilege, 'order-view');
		if ($permission == 1 || $privilege == -1) {
			return view('store/orders_list', ['data' => $data, 'title' => $type, 'new_title' => $new_title, 'cancellation_reasons' => $cancellation_reasons]);
		} else {
			return view('store/restricted');
		}
	}

	/**
	 * cancel the order request
	 * 
	 * @param int $request_id, object $request
	 * 
	 * @return return to blade page
	 */
	public function cancel_request(Request $request)
	{
		$role = $request->role;

		$foodrequest = $this->foodrequest;
		$trackorderstatus = $this->trackorderstatus;
		if ($role == 1) {
			$status = 9;
			$message = "Order Cancelled by Admin";
		} else {
			$status = 10;
			$message = "Order Cancelled ";
		}

		$foodrequest->where('id', $request->id)->update(['status' => $status]);

		$status_entry[] = array(
			'request_id' => $request->id,
			'status' => $status,
			'detail' => $message
		);

		$trackorderstatus->insert($status_entry);
		$message = "Order Cancelled Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;

		// return responce()->json($trackorderstatus);
	}

	public function cancel_request_with_reason(Request $request)
	{

		// $role = $request->session()->get('res_role');

		$Order = Order::find($request->order_id);

		$cancelFlag =  $Order->cancelByRestaurant($request->reason);

		$message = "Order Cancelled Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
		// return redirect('/store/orders/new')->with('success', 'Order cancelled successfully');
	}

	public function cancel_item_with_reason(Request $request)
	{

		$role = $request->session()->get('res_role');
		$items = DB::table('request_detail')->where('id', $request->cancelfoodID)->first();
		$item = DB::table('request_detail')->where('id', $request->cancelfoodID)
			->update(['reason' => $request->reason, 'status' => 0]);

		$this->update_request($items->request_id, $items->food_quantity_price, $items->quantity);

		return redirect('/store/view_order/' . $items->request_id . '')->with('success', 'Item cancelled successfully');
	}

	public function update_request($orderId, $price, $quantity)
	{
		$order = DB::table('requests')->where('id', $orderId)->first();
		//echo $refund = $price * $quantity; 
		echo $amt = $order->bill_amount - $price;
		$order = DB::table('requests')->where('id', $orderId)->update(['bill_amount' => $amt, 'refund' => $price]);
	}

	public function order_dashboard(Request $request)
	{
		$restaurant_id = $request->session()->get('res_id');
		$today_orders = DB::table('requests')
			->whereDate('created_at', Carbon::today())
			->where('restaurant_id', $restaurant_id)
			->count();

		$today_completed_orders = DB::table('requests')
			->whereDate('created_at', Carbon::today())
			->where('restaurant_id', $restaurant_id)
			->where('status', 7)
			->count();

		$today_cancel_orders = DB::table('requests')
			->whereDate('created_at', Carbon::today())
			->where('restaurant_id', $restaurant_id)
			->where('status', 10)
			->count();

		$today_processing_orders = DB::table('requests')
			->whereDate('created_at', Carbon::today())
			->where('restaurant_id', $restaurant_id)
			->whereIn('status', [2, 3, 4, 5, 6])
			->count();
		$restaurant_id = $request->session()->get('res_id');
		$role = $request->session()->get('res_role');
		$query = $this->foodrequest

			->orderby('id', 'desc')
			->limit(9);
		$query = $query->when(($role != 1), function ($q) use ($restaurant_id) {
			return $q->where('restaurant_id', $restaurant_id);
		});

		$recent_orders = $query->get();


		$area_wise_earnings = $this->foodrequest
			->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
			->join('add_area', 'add_area.id', '=', 'restaurants.area')

			->groupBy('restaurants.area', 'requests.id', 'add_area.area')
			->select('restaurants.area', 'requests.id', 'add_area.area as res_area')
			->get();

		$column = array();
		foreach ($area_wise_earnings as $key => $value) {

			$col['res_area'] = isset($value->Restaurants->Area) ? $value->Restaurants->Area->area : "";
			$col['id'] = $value->id;

			array_push($column, $col);
		}

		$area_wise_earnings = $column;

		return view('store/order_dashboard', ['today_orders' => $today_orders, 'today_completed_orders' => $today_completed_orders, 'today_cancel_orders' => $today_cancel_orders, 'today_processing_orders' => $today_processing_orders, 'recent_orders' => $recent_orders, 'area_wise_earnings' => $area_wise_earnings,]);
	}

	/**
	 * View the order request
	 * 
	 * @param int $request_id, object $request
	 * 
	 * @return return to blade page
	 */
	public function view_order($request_id, Request $request)
	{
		$data = $this->foodrequest->where('id', $request_id)
			->with('Restaurants.city_list')
			->with('Restaurants.Area')
			->first();

		$cancellation_reason = $this->trackorderstatus->where('request_id', $request_id)->whereIn('status', [9, 10, 11])->first();

		if (!$data) {

			return redirect('store/orders/new');
		}
		$cancellation_reasons = DB::table('cancellation_reason')->where('cancellation_for', 1)->get();
		$foodlist = $this->foodlist;
		// food item list toward restaurant
		$food = $foodlist::where('food_list.restaurant_id', $data->restaurant_id)
			->where('food_list.status', 1)
			->join('menu', function ($join) {
				$join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
				$join->on('menu.id', '=', 'food_list.menu_id');
			})
			->select('food_list.id as food_id', 'name', 'price', 'description', 'secondary_name', 'secondary_description', 'is_veg', 'tax', 'menu_name', 'food_list.image as image')
			->orderBy('rank')
			->get();

		return view('store/view_order', compact('data', 'cancellation_reason', 'cancellation_reasons', 'food'));
	}

	public function delay_order(Request $request)
	{
		$order_id = $request->delayOrderID;
		$Order = Order::find($order_id);
		$Order->delay((int) $request->delayMinutes);

		$message = "Delay Order  Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}
	public function scheduleNotification()
	{
		$newObj = new Order;
		$newObj->scheduleNotification();
	}

	public function get_addon(Request $request)
	{
		// addons list toward food 
		$food_id = $request->id;
		$this_addon = DB::table('foodlist_addons')
			->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
			->where('foodlist_id', $food_id)->select('foodlist_addons.addons_id', 'add_ons.name', 'add_ons.secondary_name',  'add_ons.price')->get()->toArray();

		echo json_encode($this_addon);
	}

	public function get_size(Request $request)
	{
		// addons list toward food 
		$food_id = $request->id;
		$this_size = DB::table('foodlist_foodquantity')
			->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
			->where('foodlist_id', $food_id)
			->select('food_quantity.name', 'food_quantity.secondary_name', 'foodlist_foodquantity.foodquantity_id', 'foodlist_foodquantity.price', 'foodlist_foodquantity.is_default')
			->get()->toArray();
		echo json_encode($this_size);
	}

	public function add_item(Request $request)
	{
		$order = DB::table('requests')->where('id', $request->OrderId)->first();
		$food = DB::table('food_list')->where('id', $request->food)->first();
		$price = $request->quantity * $food->price;
		$insertRow = [
			'request_id'		=> $order->id,
			'restaurant_id'         => $order->restaurant_id,
			'food_id'             => $food->id,
			'quantity'                 => $request->quantity,
			'addon_list'                   => $request->addons,
			'food_quantity'            => $request->size,
			'food_quantity_price'         => $price,
		];
		DB::table('request_detail')->insert($insertRow);
		$billAmount = $order->bill_amount + $price;
		$item = DB::table('requests')->where('id', $order->id)
			->update(['bill_amount' => $billAmount]);
		return redirect('/store/view_order/' . $order->id . '')->with('success', 'Item added successfully');
	}
	public function get_neworder_alert(Request $request, $id)
	{

		$restaurant_id = $id;


		$data = $this->foodrequest->where('status', 0)->where('restaurant_id', $restaurant_id)
			->first();
		$get_data = $this->foodrequest->where('status', 0)->where('restaurant_id', $restaurant_id)
			->get();

		$response_Array = json_encode(['data' => $data, 'all' => $get_data]);
		return $response_Array;
	}

	public function view_order1(Request $request)
	{

		$request_id = $request->id;
		$data = DB::table('requests')
			->where('requests.id', $request_id)
			->leftjoin('users', 'users.id', '=', 'requests.user_id')
			->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
			->select('requests.id as request_id',  'requests.tax as rtax', 'requests.status as order_status', 'users.name as user_name', 'requests.wallet_amount as wallet_amounts', 'users.email as user_email', 'users.phone as user_phone', 'restaurants.email as restaurants_email', 'restaurants.business_type as business_type', 'requests.image as req_image', 'requests.*', 'users.*', 'restaurants.*')
			->first();

		$layout = DB::table('business_type')->where('id', $data->business_type)->first();
		$layout_id = DB::table('layout')->where('id', $layout->layout_id)->first();
		if ($data->address != "") {
			$decodedText = html_entity_decode($data->address);
			$data->address = $decodedText;
		}

		if ($data->delivery_address != "") {
			$decodedText = html_entity_decode($data->delivery_address);
			$data->delivery_address = $decodedText;
		}
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
				->select('request_detail.id as detail_id', 'request_detail.status as detail_status', 'request_detail.*', 'food_list.*')
				->get();
			$rt_food = [];
			foreach ($food_list as $fl) {
				//   $img = File::exists(public_path('product_image/') . $fl->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $fl->image : (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Product/' . $fl->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Product/' . $fl->image : (File::exists(public_path('common_images/') . $fl->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $fl->image : BASE_URL  . $fl->image));


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
				// if(file_exists(public_path('/') .$fl->image)) {

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
					'id' => $fl->id,
					'detail_status' => $fl->detail_status,
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

	public function cancell_reson()
	{
		$data = DB::table('cancellation_reason')->where('cancellation_for', 1)->get();

		return response()->json($data);
	}
}
