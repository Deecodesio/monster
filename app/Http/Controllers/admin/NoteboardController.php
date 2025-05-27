<?php

namespace App\Http\Controllers\admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use Excel;
use App\Exports\UsersExport;
use App\Exports\ExportDelivery;
use App\Exports\ExportOrder;
use App\Exports\ExportRes;
use App\Exports\ExportWallet;

class NoteboardController extends BaseController
{

	/**
	 * function to get notification list 
	 * @param no param
	 * @return array o blade file
	 */

	public function noticeboard_list()
	{

		return view('admin/noticeboard_list');
	}

	/**
	 * function to View add_noticeboard blade file 
	 * @param no param
	 * @return view add_noticeboard
	 */

	public function add_noticeboard()
	{

		return view('admin/add_noticeboard');
	}

	/**
	 * function to View custum push blade file 
	 * @param no param
	 * @return view custumpush
	 */

	public function custumpush()
	{
		$driver_group_names = DB::table('driver_groups')->distinct()->select('group_name')->get();

		return view('admin/custumpush', ['driver_group_names' => $driver_group_names]);
	}

	public function custumpush_admin()
	{
		$users = DB::table('users')->get();

		return view('admin/custumpush_admin', ['users' => $users]);
	}
	/**
	 * function to send push notification based send to user , provider or all 
	 * @param Request param
	 * @return back with success response
	 */

	public function send_custumpush(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'send_to' => 'required',
			'title' => 'required',
			'message' => 'required',
		]);


		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		} else {

			$send_to = $request->send_to;
			$title = $request->title;
			$message = $request->message;

			if ($send_to == 'ALL') $data = $this->users::get();
			$provider = $this->deliverypartners;
			if ($send_to == 'USERS') $data = $this->users::get();
			if ($send_to == 'PROVIDERS') $data = $this->deliverypartners::get();

			$deviceTokens = [];

			foreach ($data as $key => $value) {

				if ($value->device_token) {
					$deviceTokens[] = $value->device_token;
				}
			}
			if ($send_to == 'ALL') {

				foreach ($provider as $key => $value) {

					if (isset($value->device_token) && $value->device_token) {
						$deviceTokens[] = $value->device_token;
					}
				}
			}

			sendNotification($deviceTokens, $title, $message);
		}
		$message = "Send Successfully Message";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
		// return back()->with('success', trans('constants.push_notification') . trans('constants.send_success_msg'));
	}
	public function admin_custumpush(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'send_to' => 'required',
			'title' => 'required',
			'message' => 'required',
		]);


		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}
		$send_to = $request->send_to;
		$title = $request->title;
		$message = $request->message;

		$users = DB::table('users')->where('id', $send_to)->get();
		foreach ($users as $key => $value) {

			if ($value->device_token) {
				$deviceTokens[] = $value->device_token;
			}
		}

		sendNotification($deviceTokens, $title, $message);
		// return back()->with('success', trans('constants.push_notification') . trans('constants.send_success_msg'));
		$message = "Send Successfully Message";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}
	public function page_save(Request $request)
	{
		Storage::disk('local')->put('privacypolicy.rtf', $request->content_value);
		$message = "Privacy Policy updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}

	public function privacy_policy(Request $request)
	{

		$content = fopen(Storage::path("privacypolicy.rtf"), 'r');

		while (!feof($content)) {

			$line = fgets($content);

			echo $line . "<br>";
		}

		fclose($content);
		$message = "Privacy Policy updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $content]);
		return $response_Array;
	}

	public function page_save_about(Request $request)
	{
		Storage::disk('local')->put('about_us.rtf', $request->content_value);
		$message = "About Us updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}

	public function about(Request $request)
	{

		$content = fopen(Storage::path("about_us.rtf"), 'r');

		while (!feof($content)) {

			$line = fgets($content);

			echo $line . "<br>";
		}

		fclose($content);
		$message = "About Us updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $content]);
		return $response_Array;
	}

	public function page_save_terms(Request $request)
	{
		Storage::disk('local')->put('terms_conditions.rtf', $request->content_value);
		$message = "Terms Conditions updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}

	public function terms_conditions(Request $request)
	{

		$content = fopen(Storage::path("terms_conditions.rtf"), 'r');

		while (!feof($content)) {

			$line = fgets($content);

			echo $line . "<br>";
		}

		fclose($content);
		$message = "Terms Conditions updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $content]);
		return $response_Array;
	}

	public function page_save_contact(Request $request)
	{
		Storage::disk('local')->put('contact.rtf', $request->content_value);
		$message = "Contact updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}
	public function cancellation_policy(Request $request)
	{

		$content = fopen(Storage::path("cancellation.rtf"), 'r');

		while (!feof($content)) {

			$line = fgets($content);

			echo $line . "<br>";
		}

		fclose($content);
		$message = "Cancellation Policy updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $content]);
		return $response_Array;
	}


	public function shipping_policy(Request $request)
	{

		$content = fopen(Storage::path("shipping.rtf"), 'r');

		while (!feof($content)) {

			$line = fgets($content);

			echo $line . "<br>";
		}

		fclose($content);
		$message = "shipping policy updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $content]);
		return $response_Array;
	}
	public function contact(Request $request)
	{

		$content = fopen(Storage::path("contact.rtf"), 'r');

		while (!feof($content)) {

			$line = fgets($content);

			echo $line . "<br>";
		}

		fclose($content);
		$message = "Contact updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $content]);
		return $response_Array;
	}

	public function content_policy_cancel(Request $request)
	{
		Storage::disk('local')->put('cancellation.rtf', $request->content_value);
		$message = "Cancellation Policy updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}

	public function content_policy_shipping(Request $request)
	{
		Storage::disk('local')->put('shipping.rtf', $request->content_value);
		$message = "Shipping Policy updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}
	public function get_faq(Request $request, $id)
	{

		$data = DB::table('faq')->where('title', $id)->get();

		$status = true;
		$response_Array = json_encode(['status' => $status, 'data' => $data]);
		return $response_Array;
	}

	public function faq(Request $request)
	{

		$q_array = json_decode($request->question);
		$a_array = json_decode($request->answer);
		$title  = $request->title;
		$data = array();

		// $mergedArray = array_merge($a_array, $q_array);
		DB::table('faq')->where('title', $title)->delete();


		$faq = json_decode($request->question);
		if (count($faq)) {

			foreach ($faq as $faqs) {

				if (isset($faqs->question)) {
					DB::table('faq')
						->insert([
							'question' => $faqs->question,
							'answer' => $faqs->answer,
							'title' => $title,

						]);
				}
			}
		}


		// foreach ($q_array as $index => $question) {
		// 	$data[] = array(
		// 		'question' => $question,
		// 		'answer' => $a_array[$index],
		// 		'title'  => $title
		// 	);
		// }

		DB::table('faq')->insert($data);

		$message = "FAQ updated successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}

	public function banner_imgs(Request $request)
	{
		$base_url = BASE_URL;

		$data = DB::table('offers_banner')
			->leftjoin('restaurants', 'restaurants.id', '=', 'offers_banner.restaurant_id')
			->leftjoin('add_city', 'add_city.id', '=', 'offers_banner.city_id')
			->select('restaurants.restaurant_name as restaurant_name', 'add_city.city as city_name', 'offers_banner.image as banner_image', 'offers_banner.position as banner_position', 'offers_banner.status as banner_status', 'offers_banner.is_suffle as banner_suffle', 'offers_banner.id as banner_id', 'offers_banner.status')
			->get();

		foreach ($data as $d) {

			if (file_exists(public_path('/') . UPLOADS_PATH . $d->banner_image)) {
				$d->banner_image = BASE_URL . UPLOADS_PATH . $d->banner_image;
			} else {
				$d->banner_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
			}
		}

		return response()->json($data);
	}

	public function restaurant_csv(Request $request)
	{
		if ($request->from_date == null && $request->to_date == null) {
			$restaurant_details = $this->restaurants
				->leftjoin('add_city', 'add_city.id', '=', 'restaurants.city')
				->leftjoin('add_area', 'add_area.id', '=', 'restaurants.area')
				->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
				->get();
		} elseif ($request->to_date != null) {

			$restaurant_details = $this->restaurants
				->where('restaurants.created_at', '>=', $request->from_date)
				->where('restaurants.created_at', '<=', $request->to_date)
				->leftjoin('add_city', 'add_city.id', '=', 'restaurants.city')
				->leftjoin('add_area', 'add_area.id', '=', 'restaurants.area')
				->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
				->get();
		} else {
			$restaurant_details = $this->restaurants
				->where('restaurants.created_at', '>=', $request->from_date)
				->leftjoin('add_city', 'add_city.id', '=', 'restaurants.city')
				->leftjoin('add_area', 'add_area.id', '=', 'restaurants.area')
				->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
				->get();
		}


		foreach ($restaurant_details as $datas) {
			if ($datas->address != "") {
				$decodedText = html_entity_decode($datas->address);
				$datas->address = $decodedText;
			}
		}

		foreach ($restaurant_details as $key => $value) {
			$restaurant_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('restaurant_commision');
			$value->restaurant_commision = round($restaurant_commision, 2);
			$admin_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('admin_commision');
			$value->admin_commision = round($admin_commision, 2);

			$payout_done = $this->restaurant_payout_history->where('restaurant_id', $value->id)->sum('payout_amount');
			$value->payout_done = $payout_done;
			$total_orders = $this->foodrequest->where('restaurant_id', $value->id)->where('status', 7)->count();
			$value->total_orders = $total_orders;
		}

		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;

		$productList = [];

		$array_keys_template = [
			"Restaurant", "Email", "Phone Number",
			"Address", "City", "Area", "Total Orders",
			"Total Restaurant Earnings", "Pending Payouts", "Payouts Completed"
		];
		$productList[] = $array_keys_template;


		foreach ($restaurant_details as $eachProduct) {
			$restaurant_commision = $data->value . ' ' . $eachProduct->restaurant_commision;
			$pending_payout = $data->value . ' ' . $eachProduct->pending_payout;
			$payout_done = $data->value . ' ' . $eachProduct->payout_done;
			$thisNewProduct = [
				$eachProduct->restaurant_name,
				$eachProduct->email,
				$eachProduct->contact,
				$eachProduct->address,
				$eachProduct->city,
				$eachProduct->area,
				$eachProduct->total_orders,
				$restaurant_commision,
				$pending_payout,
				$payout_done,

			];

			$productList[] = $thisNewProduct;
		}
		$filePath = (public_path() . '/reports/');
		if (!file_exists($filePath)) {
			mkdir($filePath, 0777, true);
		}
		$fileName = $filePath . '' . $date . '_rest_report.csv';
		$fp = fopen($fileName, 'w+');
		foreach ($productList as $eachRow) {
			fputcsv($fp, $eachRow);
		}
		fclose($fp);
		$path =  BASE_URL . 'reports/' . $date . '_rest_report.csv';
		$message = "Download CSV successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}
	public function delivery_csv(Request $request)
	{
		if ($request->from_date == null && $request->to_date == null) {
			$delivery_boy_details = DB::table('delivery_partners')
				->leftjoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
				->leftjoin('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
				->leftjoin('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
				->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
				->get();
		} elseif ($request->to_date != null) {

			$delivery_boy_details = DB::table('delivery_partners')
				->where('delivery_partners.created_at', '>=', $request->from_date)
				->where('delivery_partners.created_at', '<=', $request->to_date)
				->leftjoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
				->leftjoin('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
				->leftjoin('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
				->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
				->get();
		} else {
			$delivery_boy_details = DB::table('delivery_partners')
				->where('delivery_partners.created_at', '>=', $request->from_date)

				->leftjoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
				->leftjoin('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
				->leftjoin('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
				->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
				->get();
		}




		foreach ($delivery_boy_details as $key => $value) {

			$value->delivery_boy_commision = DB::table('requests')->where('delivery_boy_id', $value->id)->sum('delivery_boy_commision');

			$value->admin_commision = DB::table('requests')->where('delivery_boy_id', $value->id)->sum('admin_commision');

			$city = DB::table('add_city')->where('id', $value->city)->first();

			if ($city) {
				$value->city_name = $city->city;
			} else {
				$value->city_name = '';
			}

			$area = DB::table('add_area')->where('id', $value->area)->first();
			if ($area) {
				$value->area_name = $area->area;
			}
			$total_orders = $this->foodrequest->where('delivery_boy_id', $value->id)->where('status', 7)->count();
			$value->total_orders = $total_orders;
			$id = $value->id;
			$ratings = $this->order_ratings->with('Foodrequest')
				->wherehas('Foodrequest', function ($q) use ($id) {
					$q->where('delivery_boy_id', $id);
				})
				->avg('restaurant_rating');

			$value->ratings = $ratings;
		}

		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;

		$productList = [];

		$array_keys_template = [
			"Name", "Email", "Phone Number",
			"Address", "City", "Area", "Total Orders",
			"Delivery Boy Commision", "Pending Payouts"
		];
		$productList[] = $array_keys_template;


		foreach ($delivery_boy_details as $eachProduct) {
			$delivery_commision = $data->value . ' ' . $eachProduct->delivery_boy_commision;
			$pending_payout = $data->value . ' ' . $eachProduct->pending_payout;
			// $payout_done = $data->value.' '.$eachProduct->payout_done;
			$thisNewProduct = [
				$eachProduct->name,
				$eachProduct->email,
				$eachProduct->phone,
				$eachProduct->address,
				$eachProduct->city_name,
				$eachProduct->area,
				$eachProduct->total_orders,
				$delivery_commision,
				$pending_payout,
				// $payout_done,

			];

			$productList[] = $thisNewProduct;
		}

		$filePath = (public_path() . '/reports/');
		if (!file_exists($filePath)) {
			mkdir($filePath, 0777, true);
		}
		$fileName = $filePath . '' . $date . '_delivery_report.csv';
		$fp = fopen($fileName, 'w+');
		foreach ($productList as $eachRow) {
			fputcsv($fp, $eachRow);
		}
		fclose($fp);
		$path =  BASE_URL . 'reports/' . $date . '_delivery_report.csv';
		$message = "Download CSV successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}

	public function order_csv(Request $request)
	{
		if ($request->from_date == null && $request->to_date == null) {
			$restaurant_details = DB::table('requests')
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		} elseif ($request->to_date != null) {
			$restaurant_details = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->where('requests.created_at', '<=', $request->to_date)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		} else {
			$restaurant_details = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		}
		// dd($restaurant_details);
		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;

		$productList = [];

		$array_keys_template = [
			"Order Id", "Order Time", "Customer Name",
			"Phone Number", "DeliveryBoy Name", "DeliveryBoy Phone", "Restaurant Name",
			"Item Totals", "Tax", "Offer Discount", "Admin Commision", "DeliveryBoy Commision",
			"Restaurant Commision", "Status"
		];
		$productList[] = $array_keys_template;


		foreach ($restaurant_details as $eachProduct) {
			if ($eachProduct->order_status == "0") {
				$status = "New Order";
			}
			if ($eachProduct->order_status == "1") {
				$status = "Order Accepted";
			}
			if ($eachProduct->order_status == "2") {
				$status = "Driver Assigned";
			}
			if ($eachProduct->order_status == "3") {
				$status = "Delivered to Driver";
			}
			if ($eachProduct->order_status == "4") {
				$status = "Towards Customer";
			}
			if ($eachProduct->order_status == "5") {
				$status = "Reached Customer";
			}
			if ($eachProduct->order_status == "6") {
				$status = "Delivered To Customer";
			}
			if ($eachProduct->order_status == "7") {
				$status = "Completed";
			}
			if ($eachProduct->order_status >= "9") {
				$status = "Cancelled";
			}

			if ($eachProduct->order_status == "-1") {
				$status = "Failed";
			}
			if ($eachProduct->order_status == "-2") {
				$status = "Failed";
			}
			$tax = $data->value . ' ' . $eachProduct->tax;
			$offer_discount = $data->value . ' ' . $eachProduct->offer_discount;
			$admin_commision = $data->value . ' ' . $eachProduct->admin_commision;
			$delivery_boy_commision = $data->value . ' ' . $eachProduct->delivery_boy_commision;
			$restaurant_commision = $data->value . ' ' . $eachProduct->restaurant_commision;
			$item_total = $data->value . ' ' . $eachProduct->item_total;
			$thisNewProduct = [
				$eachProduct->order_id,
				$eachProduct->ordered_time,
				$eachProduct->customer_name,
				$eachProduct->phones,
				$eachProduct->delivery_boy_name,
				$eachProduct->delivery_boy_phone,
				$eachProduct->restaurant_name,

				$eachProduct->item_total,
				$eachProduct->tax,
				$eachProduct->offer_discount,
				$eachProduct->admin_commision,
				$eachProduct->delivery_boy_commision,
				$eachProduct->restaurant_commision,
				$status,


			];

			$productList[] = $thisNewProduct;
		}
		$filePath = (public_path() . '/reports/');
		if (!file_exists($filePath)) {
			mkdir($filePath, 0777, true);
		}
		$fileName = $filePath . '' . $date . '_order_report.csv';
		$fp = fopen($fileName, 'w+');
		foreach ($productList as $eachRow) {
			fputcsv($fp, $eachRow);
		}
		fclose($fp);
		$path =  BASE_URL . 'reports/' . $date . '_order_report.csv';
		$message = "Download CSV successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}

	public function wallet_csv(Request $request)
	{

		if ($request->from_date == null && $request->to_date == null) {
			$wallet = DB::table('users')
				->where('wallet_amount', '>', 0)
				->orderbyDesc('wallet_amount')
				->get();
		} elseif ($request->to_date != null) {
			$wallet = DB::table('users')
				->where('wallet_amount', '>', 0)
				->where('created_at', '>=', $request->from_date)
				->where('created_at', '<=', $request->to_date)
				->orderbyDesc('wallet_amount')
				->get();
		} else {
			$wallet = DB::table('users')
				->where('wallet_amount', '>', 0)
				->where('created_at', '>=', $request->from_date)

				->orderbyDesc('wallet_amount')
				->get();
		}





		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;
		$productList = [];

		$array_keys_template = [
			"User", "Wallet Amount"
		];
		$productList[] = $array_keys_template;

		foreach ($wallet as $eachProduct) {

			$thisNewProduct = [
				$eachProduct->name,
				$eachProduct->wallet_amount,
			];

			$productList[] = $thisNewProduct;
		}
		$filePath = (public_path() . '/reports/');
		if (!file_exists($filePath)) {
			mkdir($filePath, 0777, true);
		}
		$fileName = $filePath . '' . $date . '_wallet_report.csv';
		$fp = fopen($fileName, 'w+');
		foreach ($productList as $eachRow) {
			fputcsv($fp, $eachRow);
		}
		fclose($fp);
		$path =  BASE_URL . 'reports/' . $date . '_wallet_report.csv';
		$message = "Download CSV successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}

	public function user_csv(Request $request)
	{
		if ($request->from_date == null && $request->to_date == null) {
			$top_user = DB::table('requests')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'users.phone')
				->groupBy('user_id', 'name', 'phone')->where('requests.status', 7)->orderbyDesc('users_count')
				->get();
		} elseif ($request->to_date != null) {

			$top_user = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->where('requests.created_at', '<=', $request->to_date)
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'users.phone')
				->groupBy('user_id', 'name', 'phone')->where('requests.status', 7)->orderbyDesc('users_count')
				->get();
		} else {
			$top_user = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'users.phone')
				->groupBy('user_id', 'name', 'phone')->where('requests.status', 7)->orderbyDesc('users_count')
				->get();
		}

		foreach ($top_user as $t) {
			$t->paidsum = number_format((float)($t->paidsum), 2, '.', ',');
		}
		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;
		$productList = [];

		$array_keys_template = [
			"User", "Phone Number", "Orders", "Amount"
		];
		$productList[] = $array_keys_template;

		foreach ($top_user as $eachProduct) {

			$thisNewProduct = [
				$eachProduct->name,
				$eachProduct->phone,
				$eachProduct->users_count,
				$eachProduct->paidsum,
			];

			$productList[] = $thisNewProduct;
		}
		$filePath = (public_path() . '/reports/');
		if (!file_exists($filePath)) {
			mkdir($filePath, 0777, true);
		}
		$fileName = $filePath . '' . $date . '_user_report.csv';
		$fp = fopen($fileName, 'w+');
		foreach ($productList as $eachRow) {
			fputcsv($fp, $eachRow);
		}
		fclose($fp);
		$path =  BASE_URL . 'reports/' . $date . '_user_report.csv';
		$message = "Download CSV successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}


	public function restaurant_pdf(Request $request)
	{

		if ($request->from_date == null && $request->to_date == null) {
			$restaurant_details = $this->restaurants
				->leftjoin('add_city', 'add_city.id', '=', 'restaurants.city')
				->leftjoin('add_area', 'add_area.id', '=', 'restaurants.area')
				->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
				->get();
		} elseif ($request->to_date != null) {

			$restaurant_details = $this->restaurants
				->where('restaurants.created_at', '>=', $request->from_date)
				->where('restaurants.created_at', '<=', $request->to_date)
				->leftjoin('add_city', 'add_city.id', '=', 'restaurants.city')
				->leftjoin('add_area', 'add_area.id', '=', 'restaurants.area')
				->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
				->get();
		} else {
			$restaurant_details = $this->restaurants
				->where('restaurants.created_at', '>=', $request->from_date)
				->leftjoin('add_city', 'add_city.id', '=', 'restaurants.city')
				->leftjoin('add_area', 'add_area.id', '=', 'restaurants.area')
				->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
				->get();
		}

		foreach ($restaurant_details as $datas) {
			if ($datas->address != "") {
				$decodedText = html_entity_decode($datas->address);
				$datas->address = $decodedText;
			}
		}

		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;

		$res_fields = [];
		foreach ($restaurant_details as $key => $value) {
			$restaurant_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('restaurant_commision');
			$value->restaurant_commision = round($restaurant_commision, 2);
			$admin_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('admin_commision');
			$value->admin_commision = round($admin_commision, 2);

			$payout_done = $this->restaurant_payout_history->where('restaurant_id', $value->id)->sum('payout_amount');
			$value->payout_done = $payout_done;
			$total_orders = $this->foodrequest->where('restaurant_id', $value->id)->where('status', 7)->count();
			$value->total_orders = $total_orders;


			$restaurant_commision =  $value->restaurant_commision;
			$pending_payout = $value->pending_payout;
			$payout_done = $value->payout_done;

			$res_fields[] = [
				'restaurant_name' => $value->restaurant_name,
				'email' => $value->email,
				'contact' => $value->contact,
				'address' => $value->address,
				'city' => $value->city,
				'area' => $value->area,
				'total_orders' => $value->total_orders,
				'restaurant_commision' => $restaurant_commision,
				'pending_payout' => $pending_payout,
				'payout_done' => $payout_done,


			];
		}





		$pdf = PDF::loadView('pdf.res_pdf', compact('res_fields'));
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_res_report.pdf';
		$pdf->save($filePath . '/' . $fileName);
		$path =  BASE_URL . 'reports/' . $date . '_res_report.pdf';
		$message = "Download PDF successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}

	public function delivery_pdf(Request $request)
	{
		if ($request->from_date == null && $request->to_date == null) {
			$delivery_boy_details = DB::table('delivery_partners')
				->leftjoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
				->leftjoin('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
				->leftjoin('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
				->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
				->get();
		} elseif ($request->to_date != null) {

			$delivery_boy_details = DB::table('delivery_partners')
				->where('delivery_partners.created_at', '>=', $request->from_date)
				->where('delivery_partners.created_at', '<=', $request->to_date)
				->leftjoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
				->leftjoin('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
				->leftjoin('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
				->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
				->get();
		} else {
			$delivery_boy_details = DB::table('delivery_partners')
				->where('delivery_partners.created_at', '>=', $request->from_date)

				->leftjoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
				->leftjoin('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
				->leftjoin('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
				->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
				->get();
		}


		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;

		$res_fields = [];
		foreach ($delivery_boy_details as $key => $value) {

			$value->delivery_boy_commision = DB::table('requests')->where('delivery_boy_id', $value->id)->sum('delivery_boy_commision');

			$value->admin_commision = DB::table('requests')->where('delivery_boy_id', $value->id)->sum('admin_commision');

			$city = DB::table('add_city')->where('id', $value->city)->first();

			if ($city) {
				$value->city_name = $city->city;
			} else {
				$value->city_name = '';
			}
			$area = DB::table('add_area')->where('id', $value->area)->first();
			if ($area) {
				$value->area_name = $area->area;
			}
			$total_orders = $this->foodrequest->where('delivery_boy_id', $value->id)->where('status', 7)->count();
			$value->total_orders = $total_orders;
			$id = $value->id;
			$ratings = $this->order_ratings->with('Foodrequest')
				->wherehas('Foodrequest', function ($q) use ($id) {
					$q->where('delivery_boy_id', $id);
				})
				->avg('restaurant_rating');

			$value->ratings = $ratings;

			$delivery_commision = $value->delivery_boy_commision;
			$pending_payout = $value->pending_payout;
			$res_fields[] = [
				'name' => $value->name,
				'email' => $value->email,
				'phone' => $value->phone,
				'address' => $value->address,
				'city_name' => $value->city_name,
				'area' => $value->area,
				'total_orders' => $value->total_orders,
				'delivery_commision' => $delivery_commision,
				'pending_payout' => $pending_payout,

			];
		}







		$pdf = PDF::loadView('pdf.delivery_pdf', compact('res_fields'));
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_delivery_report.pdf';
		$pdf->save($filePath . '/' . $fileName);
		$path =  BASE_URL . 'reports/' . $date . '_delivery_report.pdf';
		$message = "Download PDF successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}

	public function order_pdf(Request $request)
	{
		if ($request->from_date == null && $request->to_date == null) {
			$restaurant_details = DB::table('requests')
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		} elseif ($request->to_date != null) {


			$restaurant_details = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->where('requests.created_at', '<=', $request->to_date)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		} else {
			$restaurant_details = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		}

		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;

		$res_fields = [];
		foreach ($restaurant_details as $eachProduct) {
			if ($eachProduct->order_status == "0") {
				$status = "New Order";
			}
			if ($eachProduct->order_status == "1") {
				$status = "Order Accepted";
			}
			if ($eachProduct->order_status == "2") {
				$status = "Driver Assigned";
			}
			if ($eachProduct->order_status == "3") {
				$status = "Delivered to Driver";
			}
			if ($eachProduct->order_status == "4") {
				$status = "Towards Customer";
			}
			if ($eachProduct->order_status == "5") {
				$status = "Reached Customer";
			}
			if ($eachProduct->order_status == "6") {
				$status = "Delivered To Customer";
			}
			if ($eachProduct->order_status == "7") {
				$status = "Completed";
			}
			if ($eachProduct->order_status >= "9") {
				$status = "Cancelled";
			}

			if ($eachProduct->order_status == "-1") {
				$status = "Failed";
			}
			if ($eachProduct->order_status == "-2") {
				$status = "Failed";
			}
			$tax = $eachProduct->tax;
			$offer_discount = $eachProduct->offer_discount;
			$admin_commision = $eachProduct->admin_commision;
			$delivery_boy_commision = $eachProduct->delivery_boy_commision;
			$restaurant_commision = $eachProduct->restaurant_commision;
			$item_total = $eachProduct->item_total;


			$res_fields[] = [
				'order_id' => $eachProduct->order_id,
				'ordered_time' => $eachProduct->ordered_time,
				'customer_name' => $eachProduct->customer_name,
				'phones' => $eachProduct->phones,
				'driver_name' => $eachProduct->delivery_boy_name,
				'driver_phone' => $eachProduct->delivery_boy_phone,
				'restaurant_name' => $eachProduct->restaurant_name,
				'item_total' => $item_total,
				'tax' => $tax,

				'offer_discount' => $offer_discount,
				'admin_commision' => $admin_commision,
				'delivery_boy_commision' => $delivery_boy_commision,
				'restaurant_commision' => $restaurant_commision,
				'status' => $status,

			];
		}
		$pdf = PDF::loadView('pdf.order_pdf', compact('res_fields'));
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_order_report.pdf';
		$pdf->save($filePath . '/' . $fileName);
		$path =  BASE_URL . 'reports/' . $date . '_order_report.pdf';
		$message = "Download PDF successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}

	public function wallet_pdf(Request $request)
	{
		if ($request->from_date == null && $request->to_date == null) {
			$wallet = DB::table('users')
				->where('wallet_amount', '>', 0)
				->orderbyDesc('wallet_amount')
				->get();
		} elseif ($request->to_date != null) {
			$wallet = DB::table('users')
				->where('wallet_amount', '>', 0)
				->where('created_at', '>=', $request->from_date)
				->where('created_at', '<=', $request->to_date)
				->orderbyDesc('wallet_amount')
				->get();
		} else {
			$wallet = DB::table('users')
				->where('wallet_amount', '>', 0)
				->where('created_at', '>=', $request->from_date)

				->orderbyDesc('wallet_amount')
				->get();
		}


		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;
		$res_fields = [];



		foreach ($wallet as $eachProduct) {

			$res_fields[] = [
				'name' => $eachProduct->name,
				'wallet_amount' => $eachProduct->wallet_amount,


			];
		}
		$pdf = PDF::loadView('pdf.wallet_pdf', compact('res_fields'));
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_wallet_report.pdf';
		$pdf->save($filePath . '/' . $fileName);
		$path =  BASE_URL . 'reports/' . $date . '_wallet_report.pdf';
		$message = "Download PDF successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}

	public function user_pdf(Request $request)
	{
		if ($request->from_date == null && $request->to_date == null) {
			$top_user = DB::table('requests')->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'users.phone')
				->groupBy('user_id', 'name', 'phone')->where('requests.status', 7)->orderbyDesc('users_count')
				->get();
		} elseif ($request->to_date != null) {

			$top_user = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->where('requests.created_at', '<=', $request->to_date)
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'users.phone')
				->groupBy('user_id', 'name', 'phone')->where('requests.status', 7)->orderbyDesc('users_count')
				->get();
		} else {
			$top_user = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)

				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'users.phone')
				->groupBy('user_id', 'name', 'phone')->where('requests.status', 7)->orderbyDesc('users_count')
				->get();
		}
		foreach ($top_user as $t) {
			$t->paidsum = number_format((float)($t->paidsum), 2, '.', ',');
		}

		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;
		$res_fields = [];



		foreach ($top_user as $eachProduct) {

			$res_fields[] = [
				'name' => $eachProduct->name,
				'phone' => $eachProduct->phone,
				'users_count' => $eachProduct->users_count,
				'paidsums' => $eachProduct->paidsum,


			];
		}
		$pdf = PDF::loadView('pdf.user_pdf', compact('res_fields'));
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_user_report.pdf';
		$pdf->save($filePath . '/' . $fileName);
		$path =  BASE_URL . 'reports/' . $date . '_user_report.pdf';
		$message = "Download PDF successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}
	public function user_excel(Request $request)
	{
		$from = $request->from_date;
		$to = $request->to_date;
		$date = now()->timestamp;
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_user_report.xlsx';
		Excel::store(new UsersExport($from, $to), $date . '_user_report.xlsx', 'export');

		$path =  BASE_URL . 'reports/' . $date . '_user_report.xlsx';
		$message = "Download Excel successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}

	public function wallet_excel(Request $request)
	{
		$from = $request->from_date;
		$to = $request->to_date;
		$date = now()->timestamp;
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_wallet_report.xlsx';
		Excel::store(new ExportWallet($from, $to), $date . '_wallet_report.xlsx', 'export');
		$path =  BASE_URL . 'reports/' . $date . '_wallet_report.xlsx';
		$message = "Download Excel successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}
	public function restaurant_excel(Request $request)
	{
		$from = $request->from_date;
		$to = $request->to_date;
		$date = now()->timestamp;
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_res_report.xlsx';
		Excel::store(new ExportRes($from, $to), $date . '_res_report.xlsx', 'export');
		$path =  BASE_URL . 'reports/' . $date . '_res_report.xlsx';
		$message = "Download Excel successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}
	public function delivery_excel(Request $request)
	{


		$from = $request->from_date;
		$to = $request->to_date;
		$date = now()->timestamp;
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_delivery_report.xlsx';
		Excel::store(new ExportDelivery($from, $to), $date . '_delivery_report.xlsx', 'export');
		$path =  BASE_URL . 'reports/' . $date . '_delivery_report.xlsx';
		$message = "Download Excel successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}
	public function order_excel(Request $request)
	{
		$from = $request->from_date;
		$to = $request->to_date;
		$date = now()->timestamp;
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_order_report.xlsx';
		Excel::store(new ExportOrder($from, $to), $date . '_order_report.xlsx');
		$path =  BASE_URL . 'reports/' . $date . '_order_report.xlsx';
		$message = "Download Excel successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}

	public function Date_Filter(Request $request)
	{

		//  $from = Carbon::parse($request->from_date)->toDateTimeString();
		//  $to = Carbon::parse($request->to_date)->toDateTimeString();

		if ($request->type == "wallet") {
			if ($request->to_date) {
				$wallet = DB::table('users')
					->where('wallet_amount', '>', 0)
					->where('created_at', '>=', $request->from_date)
					->where('created_at', '<=', $request->to_date)
					->orderbyDesc('wallet_amount')
					->get();
			} else {
				$wallet = DB::table('users')
					->where('wallet_amount', '>', 0)
					->where('created_at', '>=', $request->from_date)
					->orderbyDesc('wallet_amount')
					->get();
			}



			return response()->json($wallet);
		} elseif ($request->type == "user") {
			if ($request->to_date) {
				$top_user = DB::table('requests')
					->where('requests.created_at', '>=', $request->from_date)
					->where('requests.created_at', '<=', $request->to_date)
					->leftjoin('users', 'users.id', '=', 'requests.user_id')
					->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'users.phone')
					->groupBy('user_id', 'name', 'phone')->where('requests.status', 7)->orderbyDesc('users_count')
					->get();
			} else {
				$top_user = DB::table('requests')
					->where('requests.created_at', '>=', $request->from_date)
					->leftjoin('users', 'users.id', '=', 'requests.user_id')
					->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'users.phone')
					->groupBy('user_id', 'name', 'phone')->where('requests.status', 7)->orderbyDesc('users_count')
					->get();
			}


			return response()->json($top_user);
		} elseif ($request->type == "restaurant") {
			if ($request->to_date) {
				$restaurant_details = $this->restaurants
					->where('restaurants.created_at', '>=', $request->from_date)
					->where('restaurants.created_at', '<=', $request->to_date)
					->leftjoin('add_city', 'add_city.id', '=', 'restaurants.city')
					->leftjoin('add_area', 'add_area.id', '=', 'restaurants.area')
					->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
					->get();
			} else {
				$restaurant_details = $this->restaurants
					->where('restaurants.created_at', '>=', $request->from_date)
					->leftjoin('add_city', 'add_city.id', '=', 'restaurants.city')
					->leftjoin('add_area', 'add_area.id', '=', 'restaurants.area')
					->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
					->get();
			}



			foreach ($restaurant_details as $datas) {
				if ($datas->address != "") {
					$decodedText = html_entity_decode($datas->address);
					$datas->address = $decodedText;
				}
			}
			foreach ($restaurant_details as $key => $value) {
				$restaurant_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('restaurant_commision');
				$value->restaurant_commision = round($restaurant_commision, 2);
				$admin_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('admin_commision');
				$value->admin_commision = round($admin_commision, 2);

				$payout_done = $this->restaurant_payout_history->where('restaurant_id', $value->id)->sum('payout_amount');
				$value->payout_done = $payout_done;
				$total_orders = $this->foodrequest->where('restaurant_id', $value->id)->where('status', 7)->count();
				$value->total_orders = $total_orders;
			}


			return response()->json($restaurant_details);
		} elseif ($request->type == "order") {
			if ($request->to_date) {
				$restaurant_details = DB::table('requests')
					->where('requests.created_at', '>=', $request->from_date)
					->where('requests.created_at', '<=', $request->to_date)
					->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
					->leftjoin('users', 'users.id', '=', 'requests.user_id')
					->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
					->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'requests.status as order_status', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.*')
					->get();
			} else {
				$restaurant_details = DB::table('requests')
					->where('requests.created_at', '>=', $request->from_date)
					->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
					->leftjoin('users', 'users.id', '=', 'requests.user_id')
					->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
					->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'requests.status as order_status', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.*')
					->get();
			}

			// }



			return response()->json($restaurant_details);
		} elseif ($request->type == "delivery") {
			if ($request->to_date) {
				$delivery_boy_details = DB::table('delivery_partners')
					->where('delivery_partners.created_at', '>=', $request->from_date)
					->where('delivery_partners.created_at', '<=', $request->to_date)
					->leftjoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
					->leftjoin('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
					->leftjoin('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
					->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
					->get();
			} else {
				$delivery_boy_details = DB::table('delivery_partners')
					->where('delivery_partners.created_at', '>=', $request->from_date)
					->leftjoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
					->leftjoin('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
					->leftjoin('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
					->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
					->get();
			}




			foreach ($delivery_boy_details as $key => $value) {

				$value->delivery_boy_commision = DB::table('requests')->where('delivery_boy_id', $value->id)->sum('delivery_boy_commision');

				$value->admin_commision = DB::table('requests')->where('delivery_boy_id', $value->id)->sum('admin_commision');

				$city = DB::table('add_city')->where('id', $value->city)->first();

				if ($city) {
					$value->city_name = $city->city;
				} else {
					$value->city_name = '';
				}

				$area = DB::table('add_area')->where('id', $value->area)->first();
				if ($area) {
					$value->area_name = $area->area;
				}
				$total_orders = $this->foodrequest->where('delivery_boy_id', $value->id)->where('status', 7)->count();
				$value->total_orders = $total_orders;
				$id = $value->id;
				$ratings = $this->order_ratings->with('Foodrequest')
					->wherehas('Foodrequest', function ($q) use ($id) {
						$q->where('delivery_boy_id', $id);
					})
					->avg('restaurant_rating');

				$value->ratings = $ratings;
			}

			return response()->json($delivery_boy_details);
		}
	}

	public function add_new_page(Request $request)
	{

		$name = $request->content_name;
		$filename = "homepage" . $name . ".rtf";
		Storage::disk('local')->put($filename, $request->content_value);
		$path = public_path('pages/');
		$image = $this->custom->common_upload_images($request, 'image', $path);
		$count = DB::table('home_page')->get();
		$nxt_rank = count($count);

		if ($request->id) {
			DB::table('home_page')->where('id', $request->id)->update([
				'name' => $name,
				'content_file_name' => $filename,
				'rank' => $nxt_rank + 1,
				'is_editable' => 1,
				'layout_type' => $request->layout_type,
				'image' => $image
			]);
		} else {
			DB::table('home_page')->insert([
				'name' => $name,
				'content_file_name' => $filename,
				'rank' => $nxt_rank + 1,
				'is_editable' => 1,
				'layout_type' => $request->layout_type,
				'image' => $image
			]);
		}

		$message = "Page Added Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}

	public function get_pages(Request $request)
	{


		$pages = DB::table('home_page')->orderby('rank', 'asc')
			->get();

		$status = true;
		$response_Array = json_encode(['pages' => $pages, 'status' => $status]);
		return $response_Array;
	}

	public function page_sort_save(Request $request)
	{
		$categoryRanks = $request->categoryRanks;
		foreach ($categoryRanks as $rank => $id) {
			DB::table('home_page')->where('id', $id)->update(['rank' => $rank]);
		}
		$categories = DB::table('home_page')->orderBy('rank', 'asc')->get();
		$message = "Updated Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $categories]);
		return $response_Array;
	}

	public function edit_pages(Request $request)
	{
		$page_details = DB::table('home_page')->where('id', $request->id)->first();

		$page_details->image = BASE_URL . "pages/" . $page_details->image;
		$content = fopen(Storage::path($page_details->content_file_name), 'r');

		while (!feof($content)) {

			$line = fgets($content);
		}

		fclose($content);
		$data[] = array(
			'data' => $line,

		);


		$status = true;
		$response_Array = json_encode(['page_details' => $page_details, 'status' => $status, 'data' => $data]);
		return $response_Array;
	}
}
