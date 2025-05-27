<?php

namespace App\Http\Controllers\admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;
use Artisan;
use Carbon\Carbon;
use Cartalyst\Stripe\Stripe;

class UserController extends BaseController
{

	public function user_list(Request $request)
	{

		// $data = DB::table('users')

		//         ->orderBy('id', 'desc')->get();
		// $data = DB::table('users')
		// ->Join('user_wallet_history','user_wallet_history.user_id','=','users.id')
		// ->select('users.*','user_wallet_history.*')
		// ->orderBy('users.id', 'desc')->get();
		$data = DB::table('users')->leftjoin('user_wallet_history', 'user_wallet_history.user_id', '=', 'users.id')
			->select('users.*', 'users.id as user_id', 'user_wallet_history.topup_code', 'user_wallet_history.status as wall_status', 'user_wallet_history.id as code_id', 'user_wallet_history.amount')
			->orderBy('users.id', 'desc')->groupby('users.id')->get();
		// dd($data);
		return response()->json($data);
	}

	public function top_users_report(Request $request)
	{
		$top_user = DB::table('requests')->join('users', 'users.id', '=', 'requests.user_id')
			->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'users.phone')
			->groupBy('user_id', 'name', 'phone')->where('requests.status', 7)->orderbyDesc('users_count')
			->get();

		return response()->json($top_user);
	}

	public function wallet_report(Request $request)
	{
		$wallet = DB::table('users')
			->where('wallet_amount', '>', 0)
			->orderbyDesc('wallet_amount')
			->get();

		return response()->json($wallet);
	}
	public function topupWallet(Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			array(
				'id' => 'required',
				'amount' => 'required'
			)
		);

		if ($validator->fails()) {
			$error_messages = implode(',', $validator->messages()->all());

			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		} else {

			$user_detail = $this->users->find($request->id);
			$amount = $request->amount;
			$topup_code = rand(10000, 99999);
			$status_entry = array();

			$status_entry[] = array(
				'user_id' => $request->id,
				'amount' => $request->amount,
				'topup_code' => $topup_code,
				'type' => 1, //1-credit 2-debit
				'status' => 0

			);

			DB::table('user_wallet_history')->insert($status_entry);
			sendtopupmail($topup_code, $amount, $user_detail);
			$message = "Otp Sent  Successfully";
			$status = true;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}
	}

	public function walletVerify(Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			array(
				'id' => 'required',
				'otp' => 'required'
			)
		);

		if ($validator->fails()) {
			$error_messages = implode(',', $validator->messages()->all());

			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		} else {

			$code = DB::table('user_wallet_history')->where('id', $request->id)->first();

			if ($code->topup_code == $request->otp) {
				DB::table('user_wallet_history')->where('id', $request->id)->update(['status' => 1, 'topup_code' => '']);

				$this->users->find($code->user_id)->increment('wallet_amount', $code->amount);
				$user_noti = DB::table('users')->where('id', $code->user_id)->first();
				$title = "Wallet";
				$message = 'Your wallet is topped up with Rs.' . $code->amount;
				sendNotification([$user_noti->device_token], $title, $message, ['amount' => $code->amount]);

				$message = "Wallet Updated Successfully";
				$status = true;
				$response_Array = json_encode(['message' => $message, 'status' => $status]);
				return $response_Array;


				// return back()->with('success', "Wallet updated");
			} else {

				$message = "Wallet Not Updated Code Is Wrong";
				$status = true;
				$response_Array = json_encode(['message' => $message, 'status' => $status]);
				return $response_Array;
				// return back()->with('error', "Wallet not updated code is wrong");

			}
		}
	}


	public function fetch_wallet(Request $request)
	{
		$id = $request->id;
		$wallet = DB::table('user_wallet_history')
			->where('user_id', $id)
			->get();

		return response()->json($wallet);
	}
	public function user_details(Request $request)
	{
		$id = $request->id;

		$user = DB::table('admin')
			->where('id', $id)
			->first();

		$response_Array = json_encode(['user' => $user]);
		return $response_Array;
	}
	public function clear_cache(Request $request)
	{
		Artisan::call('cache:clear');

		$response_Array = json_encode(['status' => true, 'message' => 'Cache Cleared']);
		return $response_Array;
	}

	public function user_data(Request $request)
	{
		$user_id = $request->id;
		$user_detail = DB::table('users')->where('id', $user_id)->first();
		$date = Carbon::parse($user_detail->created_at);
		$user_detail->create = $date->isoFormat('MMMM Do YYYY, h:mm:ss a');
		if ($user_detail->profile_image == "") {
			$user_detail->avatar = "https://www.freeiconspng.com/uploads/account-profile-icon-1.png";
		} else {
			$user_detail->avatar = "https://www.freeiconspng.com/uploads/account-profile-icon-1.png";
		}

		$orders = DB::table('requests')->where('user_id', $user_id)
			->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
			->select('requests.*', 'restaurants.restaurant_name')
			->get();

		foreach ($orders as $order) {
			$date = Carbon::parse($order->created_at);
			$order->create = $date->isoFormat('MMMM Do YYYY, h:mm:ss a');

			$order->restaurant_name = html_entity_decode($order->restaurant_name);
		}

		$wallet = DB::table('user_wallet_history')->where('user_id', $user_id)->get();

		$response_Array = json_encode(['user_detail' => $user_detail, 'orders' => $orders, 'wallet' => $wallet]);
		return $response_Array;
	}

	public function add_to_plans(Request $request)
	{
		$name = $request->name;
		$desc = $request->description;
		$price = $request->price;
		$status = $request->status;

		$sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
		$pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
		$default_currency = DB::table('country')->where('is_default', 1)->first();


		$version = DB::table('settings')->where('key_word', 'stripe_version')->first();

		if ($request->id) {
			if ($request->lifetime && $request->lifetime != false) {
				$lifetime = 1;
				$validity = null;
			} else {
				$lifetime = 0;
				$validity = $request->validity;
			}
			$status_entry  = [
				'name' => $name,
				'description' => $desc,
				'price' => $price,
				'validity' => $validity,
				'status' => $status,
				'lifetime' => $lifetime,
			];
			$id = $request->id;

			$ins = DB::table('plans')->where('id', $id)->update($status_entry);

			$chk = DB::table('plans')->where('id', $id)->first();




			$price_st =  new \Stripe\StripeClient($sk->value, $version->value);
			$stripe =  new \Stripe\StripeClient($sk->value, $version->value);

			$st_price = $stripe->prices->create([
				'unit_amount' => $price * 100,
				'currency' =>  $default_currency->currency_code,
				'recurring' => [
					'interval' => 'day',
					'interval_count' => $validity
				],
				'product' => $chk->stripe_product_id,
			]);


			$stripe =  new \Stripe\StripeClient($sk->value, $version->value);

			$st_pro = $stripe->products->update(
				$chk->stripe_product_id,
				[
					'name' => $name,
					'description' => $desc,
					'default_price' => $st_price->id
				]
			);

			$update = DB::table('plans')->where('id', $id)->update([
				'stripe_price_id' => $st_price->id
			]);

			$message = "Plan Updated Successfully";
		} else {
			if ($request->lifetime && count($request->lifetime) > 0) {
				$lifetime = 1;
				$validity = null;
			} else {
				$lifetime = 0;
				$validity = $request->validity;
			}
			$status_entry  = [
				'name' => $name,
				'description' => $desc,
				'price' => $price,
				'validity' => $validity,
				'status' => $status,
				'lifetime' => $lifetime,
			];

			$ins = DB::table('plans')->insertGetId($status_entry);

			$default_currency = DB::table('country')->where('is_default', 1)->first();

			$bill_amount_string = number_format($price, 2, '.', '');


			$stripe =  new \Stripe\StripeClient($sk->value, $version->value);
			$st_pro = $stripe->products->create([
				'name' => $name,
				'description' => $desc
			]);

			if ($st_pro->id) {

				$price_st =  new \Stripe\StripeClient($sk->value, $version->value);

				$st_price = $stripe->prices->create([
					'unit_amount' => $price * 100,
					'currency' =>  $default_currency->currency_code,
					'recurring' => [
						'interval' => 'day',
						'interval_count' => $validity
					],
					'product' => $st_pro->id,
				]);
				$update = DB::table('plans')->where('id', $ins)->update([
					'stripe_product_id' => $st_pro->id,
					'stripe_price_id' => $st_price->id
				]);
			}

			$message = "Plan added Successfully";
		}

		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}

	public function plans_list(Request $request)
	{

		$plans = DB::table('plans')->whereIn('status', [0, 1])->get();

		$status = true;
		$response_Array = json_encode(['plans' => $plans, 'status' => $status]);
		return $response_Array;
	}
	public function plan_status(Request $request)
	{

		$id = $request->id;

		$plan = DB::table('plans')->where('id', $id)->first();

		if ($plan->status == 1) {
			DB::table('plans')->where('id', $id)->update([
				'status' => 0
			]);

			$message = "Status Inactive";
			$status = false;
		} else {
			DB::table('plans')->where('id', $id)->update([
				'status' => 1
			]);

			$message = "Status Active";
			$status = true;
		}

		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}
	public function edit_plan(Request $request)
	{

		$id = $request->id;

		$plan = DB::table('plans')->where('id', $id)->first();



		$response_Array = json_encode(['plan' => $plan, 'status' => true]);
		return $response_Array;
	}

	public function delete_plan(Request $request)
	{

		$id = $request->id;

		$plan = DB::table('plans')->where('id', $id)->update([
			'status' => 3
		]);



		$message = "Plan Deleted Successfully";
		$response_Array = json_encode(['plan' => $plan, 'status' => true, 'message' => $message]);
		return $response_Array;
	}
}
