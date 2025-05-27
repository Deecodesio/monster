<?php

namespace App\Http\Controllers\web;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;
use Session;

class PromocodeController extends BaseController
{
	public function promocodes_list(Request $request)
	{

		
		$promocode_list = $this->promocode->leftjoin('restaurants', 'restaurants.id', '=', 'coupon_code.restaurant_id')
				 ->select('coupon_code.*','restaurants.id as res_id','restaurants.restaurant_name as restaurant_name')->get();
		

                 return response()->json($promocode_list);

	}

	public function add_to_promocode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'promo_code' => 'required|max:50',
            // 'email' => 'required|email',
            'offer_type' => 'required',
            'discount' => 'required',
            'title' => 'required',
            'description' => 'required',
            'available_from' => 'required',
            'valid_till' => 'required',
            'use_per_customer' => 'required',
            'total_use' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());

			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
			return $response_Array;
        }

        $rest_id = null;
        $id = $request->id;
        $promo_code = $request->promo_code;
        $title = $request->title;
        $description = $request->description;
        $offer_type = $request->offer_type;
        $value = $request->discount;
        $available_from = $request->available_from;
        $valid_till = $request->valid_till;
        $use_per_customer = $request->use_per_customer;
        $total_use = $request->total_use;
        $max_amount = (! empty($request->max_amount)) ? $request->max_amount : 0;
        $min_amount = (! empty($request->min_amount)) ? $request->min_amount : 0;
        $status = $request->status;
        $available_from_date = date('Y-m-d', strtotime($available_from));
        $valid_till_date = date('Y-m-d', strtotime($valid_till));

        if ($id != '' || $id != null) {
            $check = $this->promocode->where('id', '!=', $request->id)->where('code', $promo_code)->get();
            $insert = $this->promocode->find($id);
        } else {
            $check = $this->promocode->where('code', $promo_code)->get();
            $insert = $this->promocode;
        }

        $insert->restaurant_id = $rest_id;
        $insert->code = $promo_code;
        $insert->title = $title;
        $insert->description = $description;
        $insert->offer_type = $offer_type;
        $insert->value = $value;
        $insert->available_from = $available_from_date;
        $insert->max_amount = $max_amount;
        $insert->min_amount = $min_amount;
        $insert->valid_till = $valid_till_date;
        $insert->use_per_customer = $use_per_customer;
        $insert->total_use = $total_use;
        $insert->status = $status;
        $insert->save();

        if (count($check) != 0) {
			$message = "Promocode Added Exists";
			$status = false;
			$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
			return $response_Array;
        }

		$message = "Promocode Added Successfully";
		$status = true;
		$response_Array = json_encode(['message'=>$message,'status'=>$status]);


		return $response_Array;
    }

}