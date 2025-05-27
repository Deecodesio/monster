<?php

namespace App\Http\Controllers\store;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;

class BannerlistController extends BaseController
{
	public function banner_list(Request $request)
	{

		$base_url = BASE_URL;

		$data = DB::table('offers_banner')
			->leftjoin('restaurants', 'restaurants.id', '=', 'offers_banner.restaurant_id')
			->leftjoin('add_city', 'add_city.id', '=', 'offers_banner.city_id')
			->select('restaurants.restaurant_name as restaurant_name', 'add_city.city as city_name', 'offers_banner.image as banner_image', 'offers_banner.position as banner_position', 'offers_banner.status as banner_status', 'offers_banner.is_suffle as banner_suffle', 'offers_banner.id as banner_id')
			->get();

		foreach ($data as $d) {
			$d->banner_image = BASE_URL . UPLOADS_PATH . $d->banner_image;
		}
		if (session()->get('role') == 2) {
			return view('admin/banner_list', ['banner_list' => $data]);
		} else {
			return view('admin/banner_list', ['banner_list' => $data]);
		}
	}

	public function add_banner(Request $request)
	{
		$restaurant_list = $this->restaurants;
		$city_list = DB::table('add_city')->where('status', 1)->get();
		$data = $restaurant_list->get();
		if (session()->get('role') == 2) {
			return view('admin/add_banner', ['restaurant_list' => $data, 'city_list' => $city_list]);
		} else {
			return view('admin/add_banner', ['restaurant_list' => $data, 'city_list' => $city_list]);
		}
	}

	public function add_to_banners(Request $request)
	{

		$validator = Validator::make($request->all(), [
			//'restaurant_id' => 'required',
			'status' => 'required',
			'banner_image' => 'required|mimes:jpeg,jpg,bmp,png',
		]);


		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			return back()->with('error', $error_messages);
		} else {
			$banner_list = $this->banner;
			$custom = $this->custom;
			$restaurant_id = $request->restaurant_id;
			$city_id = $request->city_id;
			$status = $request->status;
			$image = $custom->upload_image($request, 'banner_image');
			$image = $image;
			if ($request->id) {

				$banner_list->where('id', $request->id)->update([
					'restaurant_id' => $restaurant_id,
					'city_id' => $city_id,
					'image' => $image,
					'status' => $status
				]);
			} else {
				$data = array();

				$data[] = array(
					'restaurant_id' => $restaurant_id,
					'city_id' => $city_id,
					'image' => $image,
					'status' => $status,
				);

				$banner_list->insert($data);
			}
		}
		
			return redirect('/store/banner_list')->with('success', 'Banner added Successfully');
		
	}

	public function edit_banner($banner_id)
	{
		$restaurant_list = $this->restaurants;
		$city_list = DB::table('add_city')->where('status', 1)->get();

		$restaurants = $restaurant_list->get();

		$banner = $this->banner;
		$data = $banner::where('id', $banner_id)->first();
		$data->image = BASE_URL . UPLOADS_PATH . $data->image;
		if (session()->get('role') == 2) {
			return view('admin/add_banner', ['data' => $data, 'restaurant_list' => $restaurants, 'city_list' => $city_list]);
		} else {
			return view('admin/add_banner', ['data' => $data, 'restaurant_list' => $restaurants, 'city_list' => $city_list]);
		}
	}

	public function delete_banner(Request $request)
	{
		$banner_list = $this->banner;

		$banner_id = $request->banner_id;

		$banner_list->where('id', $banner_id)->delete();
			return redirect('/store/banner_list')->with('success', 'Banner Deleted Successfully');
		
	}
}
