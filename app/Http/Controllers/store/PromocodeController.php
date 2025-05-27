<?php

namespace App\Http\Controllers\store;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;
use Session;

class PromocodeController extends BaseController
{

	/**
	 * add promocode view page
	 * 
	 * @return view page
	 * 
	 */
	public function add_promocode()
	{
		$privilege = session()->get('privilege');
		$permission = permission($privilege, 'promocodes-edit');
		if ($permission == 1 || $privilege == -1) {
			return view('store/add_promocode');
		} else {
			return view('store/restricted');
		}
	}


	public function promocodes_list(Request $request)
	{
			$promocode_list = $this->promocode->where('restaurant_id', $request->id)->get();
			foreach($promocode_list as $p)
			{
			 $p->title = $this->secondLanguage_store($p->title,$p->second_title); 
			 $p->description = $this->secondLanguage_store($p->description,$p->second_description); 
			 $p->code = $this->secondLanguage_store($p->code,$p->second_code); 
			}
		return response()->json($promocode_list);
	}

	public function edit_promocode($id, Request $request)
	{
		$data = $this->promocode->where('id', $id)->first();

		
			$data->second_title = $this->secound_name($data->second_title);
			$data->title = $this->secound_name($data->title);
		
		
			$data->description = $this->secound_name($data->description);
			$data->second_description = $this->secound_name($data->second_description);

		
		
			$data->code = $this->secound_name($data->code);
			$data->second_code = $this->secound_name($data->second_code);
		
		$coupon_timing =  DB::table('coupon_timing')->where('coupon_id',$id)->get();
        if(count($coupon_timing)){

            if($coupon_timing[0]->all_days != 1 )
            {
                $integerIDs = array_map('intval', json_decode($coupon_timing[0]->available_day, true));
       
                $array1 =$integerIDs;
            }
            else
            {
                $array1 = null; 
            }

        }else{
            $array1 = null; 

        }
        $response_Array = json_encode(['coupon'=>$data,'coupon_timing'=>$coupon_timing,'day' => $array1]);
		return $response_Array;
	}

	public function add_to_promocode(Request $request)
	{
        //   dd($request);
		$validator = Validator::make($request->all(), [
			'code' => 'required|max:50',
			// 'email' => 'required|email',
			'offer_type' => 'required',
			// 'discount' => 'required',
			'title' => 'required',
			'description' => 'required',
			'available_from' => 'required',
			'valid_till' => 'required',
			'use_per_customer' => 'required',
			'total_use' => 'required',
			// 'status' => 'required'
		]);

		if ($validator->fails()) {
			$error_messages = implode(',', $validator->messages()->all());
			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
			return $response_Array;
		}
       
		if($request->rest_id == "" || $request->rest_id == null)
		{
			$rest_id = null ;
			
		}
		else{
			
			$rest_id = $request->rest_id ;
		}
		
		$id = $request->id;

		$promo_code = $this->secound_lang_name($request->code) ;
        $title = $this->secound_lang_name( $request->title);
        $description = $this->secound_lang_name($request->description);

        $second_code = $this->secound_lang_name($request->second_code);
        $second_title = $this->secound_lang_name($request->second_title);
        $second_description = $this->secound_lang_name($request->second_description);

		// $promo_code = $request->code;
		// $title = $request->title;
		// $description = $request->description;


		$offer_type = $request->offer_type;
		$value = $request->value;
		$available_from = $request->available_from;
		$valid_till = $request->valid_till;
		$use_per_customer = $request->use_per_customer;
		$total_use = $request->total_use;
		$max_amount = (!empty($request->max_amount)) ? $request->max_amount : 0;
		$min_amount = (!empty($request->min_amount)) ? $request->min_amount : 0;
		$status = $request->status;
		$available_from_date = date("Y-m-d", strtotime($available_from));
		$valid_till_date =  date("Y-m-d", strtotime($valid_till));

			if($request->selected_l ==  "true"){
				$code_required = 1;
			}else{
				$code_required = 0;
			}
		
		if ($request->id == "" || $request->id == null || $request->id == "undefined") {
			$check = $this->promocode->where('code', $promo_code)->get();
			$insert = $this->promocode;
			$error_message ="Promocode Added Successfully";
			
		} else {
			$check = $this->promocode->where('id', '!=', $request->id)->where('code', $promo_code)->get();
			$insert = $this->promocode->find($id);
			$error_message ="Promocode Updated Successfully";
		
			
		}
		
		$insert->restaurant_id = $rest_id;
		$insert->code = str_replace(' ', '', strtoupper($promo_code));
		$insert->title = $title;
		$insert->description = $description;
		$insert->second_title = $second_title ?? '';
        $insert->second_code = str_replace(' ', '', strtoupper($second_code));
        $insert->second_description = $second_description ?? '';
		$insert->offer_type = $offer_type;
		$insert->value = $value;
		$insert->available_from = $available_from_date;
		$insert->max_amount = $max_amount;
		$insert->min_amount = $min_amount;
		$insert->valid_till = $valid_till_date;
		$insert->use_per_customer = $use_per_customer;
		$insert->total_use = $total_use;
		$insert->status = $status;
		$insert->code_required = $code_required;
		$insert->save();

		$seven = $request->sevendays;
        if ($id != '' || $id != null) {
            $delete_old = DB::table('coupon_timing')->where('coupon_id',$id)->delete();
            if($request->from != null){
                $fromDate = $request->from ? count($request->from) : 0;
                $toDate = $request->to;
                $from = $request->from;
                for ($i = 0; $i < $fromDate; $i++) {
                    if ($request->days == 1) { //if days checked not update days
                        $all_days =  $request->days;
                        $available_day = NULL;
                    } else {
                        $all_days =  NUll;
                        $available_day = $seven;
                    }
                    if (($from[$i]) == null) {
                        $from[$i] = 0;
                        $toDate[$i] = 0;
                    }
                    DB::table('coupon_timing')->insert([
                        'coupon_id' => $id,
                        'all_days' => $all_days,
                        'available_day' => $available_day,
                        'from_time' =>  date("G:i", strtotime($from[$i])),
                        'to_time' => date("G:i", strtotime($toDate[$i])),
                    ]);
                   
                }
            }
        }else{
            if($request->from[0] != null){
                $fromDate = $request->from ? count($request->from) : 0;
                $toDate = $request->to;
                $from = $request->from;
                for ($i = 0; $i < $fromDate; $i++) {
                    if ($request->days == 1) { //if days checked not update days
                        $all_days =  $request->days;
                        $available_day = NULL;
                    } else {
                        $all_days =  NUll;
                        $available_day = $seven;
                    }
                    if (($from[$i]) == null) {
                        $from[$i] = 0;
                        $toDate[$i] = 0;
                    }
                    DB::table('coupon_timing')->insert([
                        'coupon_id' => $insert->id,
                        'all_days' => $all_days,
                        'available_day' => $available_day,
                        'from_time' =>  date("G:i", strtotime($from[$i])),
                        'to_time' => date("G:i", strtotime($toDate[$i])),
                    ]);
                   
                }
            }
        }

		// if (count($check) != 0) {
		// 	$message = "Promocode Added Exists";
		// 	$status = false;
		// 	$response_Array = json_encode(['message'=>$message,'status'=>$status]);
		// 	return $response_Array;
        // }

		$message = $error_message;
		$status = true;
		$response_Array = json_encode(['message'=>$message,'status'=>$status]);


		return $response_Array;
	}

	public function delete_promocode($id, Request $request)
	{
		
		
		$promo_code = $this->promocode->find($id);
		$status = $promo_code->status;
		
        
		if ($status == 0) {
			$promo_code->status = 1;
		} else {
			$promo_code->status = 0;
		}
		$promo_code->save();
		$message = "Promocode Updated Successfully";
        $status = true;
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);

        return $response_Array;

		// return redirect('/store/promocodes_list')->with('success', trans('constants.' . $msg) . " " . trans('constants.promo_code'));
	}

	public function coupon_list()
	{
		$coupon_list = $this->coupon->get();

		return view('store/coupon_list', ['coupon_list' => $coupon_list]);
	}

	public function coupon()
	{

		return view('store/add_coupon');
	}

	public function coupon_add(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'coupon_type' => 'required',
			// 'email' => 'required|email',
			'code' => 'required',
			'discount_type' => 'required',
			'amount' => 'required',
			'usage_limit_per_coupon' => 'required',
			'usage_limit_per_user' => 'required',
			'valid_from' => 'required',
			'status' => 'required'
		]);

		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			return back()->with('error', $error_messages);
		} else {

			$coupon_type = $request->coupon_type;
			$code = $request->code;
			$discount_type = $request->discount_type;
			$amount = $request->amount;
			$usage_limit_per_coupon = $request->usage_limit_per_coupon;
			$usage_limit_per_user = $request->usage_limit_per_user;
			$valid_from = $request->valid_from;
			$status = $request->status;


			$coupon_management = $this->coupon;

			$data = array();

			// echo date("Y-m-d",strtotime($available_from)); exit;

			if ($request->id) {
				$coupon_management->where('id', $request->id)->update([
					'coupon_type' => $coupon_type,
					'code' => $code,
					'discount_type' => $discount_type,
					'amount' => $amount,
					'usage_limit_per_coupon' => $usage_limit_per_coupon,
					'usage_limit_per_user' => $usage_limit_per_user,
					'valid_from' => $valid_from,
					'status' => $status
				]);
			} else {
				$check = $coupon_management->where('code', $code)->get();

				if (count($check) != 0) {

					return redirect('/store/coupon_list')->with('error', $code . ' already exists');
				} else {
					$data[] = array(
						'coupon_type' => $coupon_type,
						'code' => $code,
						'discount_type' => $discount_type,
						'amount' => $amount,
						'usage_limit_per_coupon' => $usage_limit_per_coupon,
						'usage_limit_per_user' => $usage_limit_per_user,
						'valid_from' => $valid_from,
						'status' => $status
					);

					$coupon_management->insert($data);
				}
			}
		}

		return redirect('/store/coupon_list')->with('success', 'Coupon Added');
	}


	public function edit_coupon($id, Request $request)
	{
		$coupon = $this->coupon->where('id', $id)->first();

		return view('store/add_coupon', ['data' => $coupon]);
	}

	public function delete_coupon($id)
	{
		$delete = $this->coupon->where('id', $id)->delete();

		return back()->with('success', 'Coupon Deleted');
	}
}
