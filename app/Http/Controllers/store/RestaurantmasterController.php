<?php

namespace App\Http\Controllers\store;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;

class RestaurantmasterController extends BaseController
{
	public function restaurant_cuisines(Request $request)
	{
		$restaurant_id = $request->id;

		$restaurant_cuisines = DB::table('restaurant_cuisines')->where('restaurant_id', $restaurant_id)
			->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
			->select('restaurant_cuisines.id as cid', 'cuisines.name as cuisine_name', 'cuisines.cuisine_image')
			->get();

		$cuisines = $this->cuisines->get();
		
		return response()->json($restaurant_cuisines);
		// $privilege = $request->session()->get('privilege');
		// $permission = permission($privilege, 'cuisines-view');
		// if ($permission == 1 || $privilege == -1) {
		// 	return view('store/restaurant_cuisines', ['restaurant_cuisines' => $restaurant_cuisines, 'cuisines' => $cuisines]);
		// } else {
        //     return view('store/restricted');
		// }
	}
	public function add_to_restaurant_cuisines(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id' => 'required',
		]);

		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());
            // return back()->with('error', $error_messages)->withInput();
                $message =$error_messages;
                $status = false;
                $response_Array = json_encode(['message'=>$message,'status'=>$status]);
                return $response_Array;
		} else {
			$restaurant_id = $request->res_id;
			$cuisine_id = $request->id;

			$restaurant_cuisines = $this->restaurantcuisines;

			$data = array();

			$data[] = array(
				'restaurant_id' => $restaurant_id,
				'cuisine_id' => $cuisine_id
			);

			$check = $restaurant_cuisines->where('restaurant_id', $restaurant_id)->where('cuisine_id', $cuisine_id)->count();

			if ($check == 0) {

				$restaurant_cuisines->insert($data);
			} else {
				$message ="Cuisine Already Exist";
                $status = false;
                $response_Array = json_encode(['message'=>$message,'status'=>$status]);
                return $response_Array;
				// return redirect('/admin/restaurant_cuisines')->with('error', 'Cuisine already exist');
			}
		}

		$message ="Cuisine Add Successfully";
		$status = true;
		$response_Array = json_encode(['message'=>$message,'status'=>$status]);
		 return $response_Array;
	}

	public function delete_restaurant_cuisine(Request $request,$id)
	{
		

		
			// $cuisine_id = $request->cuisine_id;

			$this->restaurantcuisines->where('id', $id)->delete();
		

		// return redirect('/store/restaurant_cuisines')->with('success', 'Cuisine Deleted Successfully');
		   $message = "Cuisine Deleted Successfully";
			$status = true;
			$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
			return $response_Array;
	}
	public function edit_cuisines(Request $request,$id)
	{
		
		    $data = $this->restaurantcuisines->where('id', $id)->first();
			// $data[] = array(
			// 	'restaurant_id' =>$data1->restaurant_id,
			// 	'name' => $data1->cuisine_id
			// );
			return response()->json($data);
		// return redirect('/store/restaurant_cuisines')->with('success', 'Cuisine Deleted Successfully');
		
	}

	public function cuisines_list(Request $request)
	{
		

			$data = $this->cuisines->get();
			
			return response()->json($data);
		// return redirect('/store/restaurant_cuisines')->with('success', 'Cuisine Deleted Successfully');
		
	}
}
