<?php

namespace App\Http\Controllers\admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;
use DeDmytro\CloudflareImages\Facades\CloudflareApi;

class BannerlistController extends BaseController
{
	public function banner_list(Request $request)
	{

		$base_url = BASE_URL;

		$data = DB::table('offers_banner')
			->leftjoin('restaurants', 'restaurants.id', '=', 'offers_banner.restaurant_id')
			->leftjoin('add_city', 'add_city.id', '=', 'offers_banner.city_id')
			->select('restaurants.restaurant_name as restaurant_name', 'add_city.city as city_name', 'offers_banner.image as banner_image', 'offers_banner.position as banner_position', 'offers_banner.status as banner_status', 'offers_banner.is_suffle as banner_suffle', 'offers_banner.id as banner_id', 'offers_banner.banner_type as banner_type', 'offers_banner.status', 'offers_banner.featured', 'offers_banner.cloudflare_bannerid as c_bannerid')
			->get();
		$cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
		foreach ($data as $d) {
			if ($cloudflare->value == 1 && $d->c_bannerid == null) {
				if (file_exists(public_path('/') . UPLOADS_PATH . $d->banner_image)) {
					$up_ban =  UPLOADS_PATH . $d->banner_image;

					$response = CloudflareApi::images()->upload(public_path($up_ban));
					$cimage = $response->result;

					$image_id = $cimage->id;

					DB::table('offers_banner')->where('id', $d->banner_id)->update(['cloudflare_bannerid' => $image_id]);
				}
			}
		}

		$url = "";
		$acc_hash = "";
		if ($cloudflare->value == 1) {
			$get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
			$url = $get_url->value;

			$get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
			$acc_hash = $get_acchash->value;
		}

		$data = DB::table('offers_banner')
			->leftjoin('restaurants', 'restaurants.id', '=', 'offers_banner.restaurant_id')
			->leftjoin('add_city', 'add_city.id', '=', 'offers_banner.city_id')
			->select('restaurants.restaurant_name as restaurant_name', 'add_city.city as city_name', 'offers_banner.image as banner_image', 'offers_banner.position as banner_position', 'offers_banner.status as banner_status', 'offers_banner.is_suffle as banner_suffle', 'offers_banner.id as banner_id', 'offers_banner.banner_type as banner_type', 'offers_banner.status', 'offers_banner.featured', 'offers_banner.cloudflare_bannerid as c_bannerid')
			->get();
		foreach ($data as $d) {
			if ($cloudflare->value == 1 && $d->c_bannerid != null) {

				$d->banner_image = $url . '/' . $acc_hash . '/' . $d->c_bannerid . '/' . "w=100";
			} else {
				if (file_exists(public_path('/') . UPLOADS_PATH . $d->banner_image)) {
					$d->banner_image = BASE_URL . UPLOADS_PATH . $d->banner_image;
				} else {
					$d->banner_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
				}
			}
		}

		return response()->json($data);
	}

	public function add_banner(Request $request)
	{
		$restaurant_list = $this->restaurants;
		$city_list = DB::table('add_city')->where('status', 1)->get();
		$data = $restaurant_list->get();

		return view('admin/add_banner', ['restaurant_list' => $data, 'city_list' => $city_list]);
	}

	public function add_to_banners(Request $request)
	{
		if ($request->id) {

			$validator = Validator::make($request->all(), [
				'status' => 'required',
			]);
		} else {
			$validator = Validator::make($request->all(), [
				'status' => 'required',
			]);
		}
		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());
			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);


			return $response_Array;
		} else {
			$banner_list = $this->banner;
			$custom = $this->custom;
			$restaurant = DB::table('restaurants')->first();
			$restaurant_id = $restaurant->id;
			$category = $request->category;
			$product = $request->product;
			$city_id = $request->city_id;
			$status = $request->status;
			$banner_type = $request->banner_type;
			if ($request->existing == "undefined") {
				$image  = basename($request->banner_image);
			} else {
				if ($request->existing == 1) {
					$image = $request->banner_image;
				} else {

					$image = $custom->upload_image($request, 'banner_image');
				}
			}
			$image = $image; //dd($image);
			if ($request->id) {
				if ($image) {
					$banner_list->where('id', $request->id)->update([
						'restaurant_id' => $restaurant_id,
						'city_id' => $city_id,
						'image' => $image,
						'status' => $status,
						'banner_type' => $banner_type,
						'cloudflare_bannerid' => null,
						'category' => $category,
						'product' => $product
					]);
				} else {
					$banner_list->where('id', $request->id)->update([
						'restaurant_id' => $restaurant_id,
						'city_id' => $city_id,
						'status' => $status,
						'banner_type' => $banner_type,
						'category' => $category,
						'product' => $product

					]);
				}
				$msg = "Banner Updated Successfully";
			} else {
				$data = array();

				$data[] = array(
					'restaurant_id' => $restaurant_id,
					'city_id' => $city_id,
					'image' => $image,
					'status' => $status,
					'banner_type' => $banner_type,
					'category' => $category,
					'product' => $product


				);

				$banner_list->insert($data);
				$msg = "Banner Added Successfully";
			}
		}

		$message =  $msg;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);


		return $response_Array;
	}

	public function edit_banner($banner_id)
	{
		$restaurant_list = $this->restaurants;
		$city_list = DB::table('add_city')->where('status', 1)->get();

		$restaurants = $restaurant_list->get();

		$banner = $this->banner;
		$data = $banner::where('id', $banner_id)->first();
		if ($data->city_id == null) {
			$data->city_id = '';
		}
		if ($data->restaurant_id == null) {
			$data->restaurant_id = '';
		}
		if (file_exists(public_path('/') . UPLOADS_PATH . $data->image)) {
			$data->image = BASE_URL . UPLOADS_PATH . $data->image;
		} else {
			$data->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
		}
		// if(file_exists(BASE_URL . UPLOADS_PATH . $data->image))
		// {
		// 	$data->image = BASE_URL . UPLOADS_PATH . $data->image;
		// }
		// else{
		// 	$data->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
		// }


		$message = $data;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);


		return $response_Array;
	}

	public function delete_banner(Request $request)
	{
		$banner_list = $this->banner;

		$banner_id = $request->banner_id;

		$banner_list->where('id', $banner_id)->delete();
		// if (session()->get('role') == 2) {
		// 	return redirect('/store/banner_list')->with('success', 'Banner Deleted Successfully');
		// } else {
		// 	return redirect('/admin/banner_list')->with('success', 'Banner Deleted Successfully');
		// }
		$message = "Banner Deleted Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);


		return $response_Array;
	}
}
