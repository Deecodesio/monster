<?php

namespace App\Http\Controllers\web;

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
						//'restaurant_id' => 'required',
						'status' => 'required',
						//'banner_image' => 'required|mimes:jpeg,jpg,bmp,png',
					]);

			}else{
			$validator = Validator::make($request->all(), [
						//'restaurant_id' => 'required',
						'status' => 'required',
						'banner_image' => 'required|mimes:jpeg,jpg,bmp,png',
					]);

			}
		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());
			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
			return $response_Array;
		} else {
			$banner_list = $this->banner;
			$custom = $this->custom;
			$restaurant_id = $request->restaurant_id;
			$city_id = $request->city_id;
			$status = $request->status;
			$image = $custom->upload_image($request, 'banner_image');
			$image = $image;//dd($image);
			if ($request->id) {
				if($image){
				$banner_list->where('id', $request->id)->update([
					'restaurant_id' => $restaurant_id,
					'city_id' => $city_id,
					'image' => $image,
					'status' => $status
				]);
				}else{
					$banner_list->where('id', $request->id)->update([
					'restaurant_id' => $restaurant_id,
					'city_id' => $city_id,
					'status' => $status
				]);

				}
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
	
		$message = "Banner Added Successfully";
        $status = true;
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);


        return $response_Array;
	}

	public function edit_banner($banner_id)
	{
		$restaurant_list = $this->restaurants;
		$city_list = DB::table('add_city')->where('status', 1)->get();

		$restaurants = $restaurant_list->get();

		$banner = $this->banner;
		$data = $banner::where('id', $banner_id)->first();
		$data->image = BASE_URL . UPLOADS_PATH . $data->image;
		
		$message = $data;
        $status = true;
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);


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
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);


        return $response_Array;
	}
}
