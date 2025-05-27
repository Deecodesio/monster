<?php

namespace App\Http\Controllers\admin;

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
		
                 foreach($promocode_list as $p)
                 {
                    $p->code =  $this->secondLanguage($p->code,$p->second_code);
                    $p->title = $this->secondLanguage($p->title,$p->second_title);
                    $p->description = $this->secondLanguage($p->second_description,$p->second_description);
                 }
                 return response()->json($promocode_list);

	}

	public function add_to_promocode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'code' => 'required|max:50',
            // 'email' => 'required|email',
            'offer_type' => 'required',
            'value' => 'required',
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

        $promo_code = $this->secound_lang_name($request->code) ;
        $title = $this->secound_lang_name( $request->title);
        $description = $this->secound_lang_name($request->description);

        $second_code = $this->secound_lang_name($request->second_code);
        $second_title = $this->secound_lang_name($request->second_title);
        $second_description = $this->secound_lang_name($request->second_description);


        $rest_id = $request->restaurant_id ? $request->restaurant_id : null;
        $id = $request->id;
        // $promo_code = $request->code;
        // $title = $request->title;
        // $description = $request->description;
        $offer_type = $request->offer_type;
        $value = $request->value;
        $available_from = $request->available_from;
        $valid_till = $request->valid_till;
        $use_per_customer = $request->use_per_customer;
        $total_use = $request->total_use;
        $max_amount = (! empty($request->max_amount)) ? $request->max_amount : 0;
        $min_amount = (! empty($request->min_amount)) ? $request->min_amount : 0;
        $status = $request->status;
        $available_from_date = date('Y-m-d', strtotime($available_from));
        $valid_till_date = date('Y-m-d', strtotime($valid_till));

        if($request->selected_l ==  "true"){
            $code_required = 1;
        }else{
            $code_required = 0;
        }
       
        if ($id != '' || $id != null) {
            $check = $this->promocode->where('id', '!=', $request->id)->where('code', $promo_code)->get();
            $insert = $this->promocode->find($id);
            $msg = "Promocode Updated Successfully";
        } else {
            $check = $this->promocode->where('code', $promo_code)->get();
            $insert = $this->promocode;
            $msg =  "Promocode Added Successfully";
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

        // if (count($check) != 0) {
		// 	$message = "Promocode Added Exists";
		// 	$status = false;
		// 	$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
		// 	return $response_Array;
        // }

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

		$message = $msg ;
		$status = true;
		$response_Array = json_encode(['message'=>$message,'status'=>$status]);


		return $response_Array;
    }

    public function edit_coupon($id)
	{
       
		$coupon = DB::table('coupon_code')->where('id', $id)->first();

        if($coupon)
        {
            $coupon->second_title =$this->secound_name($coupon->second_title);
            $coupon->second_description =$this->secound_name($coupon->second_description);
            $coupon->second_code =$this->secound_name($coupon->second_code);

            $coupon->title =$this->secound_name($coupon->title);
            $coupon->description =$this->secound_name($coupon->description);
            $coupon->code =$this->secound_name($coupon->code);
        }
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
        if($coupon->restaurant_id == null){
            $coupon->restaurant_id ='';
        }
        $response_Array = json_encode(['coupon'=>$coupon,'coupon_timing'=>$coupon_timing,'day' => $array1]);
		return $response_Array;
		return response()->json($coupon);
	}
    public function delete_coupon($id)
	{
		$delete = DB::table('coupon_code')->where('id', $id)->delete();
        //  dd($delete);
        $message = "Coupon Deleted";
		$status = true;
		$response_Array = json_encode(['message'=>$message,'status'=>$status]);
		return $response_Array;
		
	}

}