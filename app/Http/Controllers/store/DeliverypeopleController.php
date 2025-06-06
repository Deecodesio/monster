<?php

namespace App\Http\Controllers\store;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;

class DeliverypeopleController extends BaseController
{
	public function deliverypeople_list(Request $request)
	{
		// echo $request->ip(); exit;
		$data = DB::table('delivery_partners')->where('status', 3)->get();
		//$delete =  $this->driver_partner_details::get();

		foreach ($data as $d) {
			$driver_id = $this->driver_partner_details->where('delivery_partners_id', $d->id)->first();
			if (isset($driver_id)) {
				$d->driver_id = $driver_id->id;
			} else {
				$d->driver_id = 0;
			}
			$d->profile_pic = BASE_URL . $d->profile_pic;
		}

		return view('admin/deliverypeople_list', ['data' => $data]);
	}

	public function add_deliverypeople()
	{
		$settings = $this->settings;

		$delivery_partner_commision = $settings->where('key_word', 'delivery_boy_commission')->first();

		$profile_icon = BASE_URL . PROFILE_ICON;

		return view('admin/add_deliverypeople')->with('delivery_partner_commision', $delivery_partner_commision->value)->with('profile_icon', $profile_icon);
	}

	public function edit_delivery_partner($id, Request $request)
	{
		$deliverypartners = $this->deliverypartners;

		$delivery_partner_detail = $deliverypartners->find($id);
		$profile_pic = BASE_URL . $delivery_partner_detail->profile_pic;

		return view('admin/add_deliverypeople', ['delivery_partner' => $delivery_partner_detail])->with('delivery_partner_commision', $delivery_partner_detail->partner_commision)->with('profile_icon', $profile_pic);
	}

	public function add_to_deliverypeople(Request $request)
	{


		if ($request->id) {
			$validator = Validator::make($request->all(), [
				'name' => 'required|max:50',
				// 'email' => 'required|email',
				'commision' => 'required',
				'address' => 'required',
				'driving_license_no' => 'required',
				'service_zone' => 'required',
				'bank_name' => 'required',
				'acc_no' => 'required',
				'ifsc_code' => 'required'
			]);
		} else {

			$validator = Validator::make($request->all(), [
				'name' => 'required|max:50',
				'phone' => 'required|numeric|digits_between:6,13',
				'country_code' => 'required|numeric',
				// 'email' => 'required|email',
				'commision' => 'required',
				'address' => 'required',
				'driving_license_no' => 'required',
				'service_zone' => 'required',
				'bank_name' => 'required',
				'acc_no' => 'required',
				'ifsc_code' => 'required',
				'profile_pic' => 'required|mimes:jpeg,jpg,bmp,png|max:2048'
			]);
		}

		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			return back()->with('error', $error_messages);
		} else {

			$deliverypartners = $this->deliverypartners;
			$custom = $this->custom;
			$name = $request->name;
			$phone = $request->phone;
			$country_code = $request->country_code;
			$service_zone = $request->service_zone;
			$bank_name = $request->bank_name;
			$acc_no = $request->acc_no;
			$ifsc_code = $request->ifsc_code;
			$driving_license_no = $request->driving_license_no;
			$email = $request->email ? $request->email : "";
			$password = $this->encrypt_password($request->password);
			$authToken = "";
			$commision = $request->commision;
			$address = $request->address;
			if ($request->profile_pic) {
				$profile_pic = $custom->restaurant_upload_image($request, 'profile_pic');
			}
			// else
			// {

			// 	$profile_pic=PROFILE_ICON;

			// }



			$data = array();

			if ($request->id) {
				if (!$request->profile_pic) {
					$check = $deliverypartners->where('id', $request->id)->first();
					$profile_pic = $check->profile_pic;
				}

				$deliverypartners->where('id', $request->id)->update([
					'name' => $name, 'email' => $email,
					'address' => $address,
					'profile_pic' => $profile_pic,
					'partner_commision' => $commision,
					'service_zone' => $service_zone,
					'driving_license_no' => $driving_license_no,
					'bank_name' => $bank_name,
					'acc_no' => $acc_no,
					'ifsc_code' => $ifsc_code
				]);
			} else {

				$data[] = array(
					'partner_id' => $this->generate_partner_id(),
					'name' => $name,
					'phone' => $country_code . $phone,
					'email' => $email,
					'password' => $password,
					'address' => $address,
					'profile_pic' => $profile_pic,
					'partner_commision' => $commision,
					'service_zone' => $service_zone,
					'driving_license_no' => $driving_license_no,
					'bank_name' => $bank_name,
					'acc_no' => $acc_no,
					'ifsc_code' => $ifsc_code
				);

				$deliverypartners->insert($data);
			}
			
				return redirect('/store/deliverypeople_list')->with('success', 'Delivery Boy Added');
			
		}
	}

	public function delete_delivery_partner(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id' => 'required',
		]);

		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			return back()->with('error', $error_messages);
		} else {
			$id = $request->id;
			$deliverypartners = $this->deliverypartners;

			$deliverypartners->where('id', $id)->delete();

			$id1 = $request->driver_id;
			$delivery = $this->driver_partner_details;

			$delivery->where('id', $id1)->delete();
			
				return redirect('/store/deliverypeople_list')->with('success', 'Delivery Partner Removed');
			
		}
	}
}
