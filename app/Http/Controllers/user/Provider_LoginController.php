<?php

namespace App\Http\Controllers\api;
                                    
use Validator;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;

class Provider_LoginController extends BaseController
{
	    public function get_profile(Request $request)
    {
        # code...
        $partner_id = $request->header('authId');
        $data = $this->deliverypartners->where('id',$partner_id)->first();
        $partner_rating = 4.4;
        $response_array = array(
            'status'=>true,
            'id'=>$data->id,
            'partner_id'=>$data->partner_id,
            'name'=>$data->name,
            'phone'=>$data->phone,
            'address'=>isset($data->Deliverypartner_detail)?$data->Deliverypartner_detail->address_line_1:"",
            'profile_pic'=>BASE_URL.$data->profile_pic,
            'driving_license_no'=>$data->driving_license_no,
            'service_zone'=>isset($data->Deliverypartner_detail->City)?$data->Deliverypartner_detail->City->city:"",
            'is_approved'=>$data->status,
            'joining_date'=>date("d-m-Y",strtotime($data->created_at)),
            'bank_name'=>isset($data->Deliverypartner_detail)?$data->Deliverypartner_detail->bank_name:"",
            "acc_no"=>isset($data->Deliverypartner_detail)?$data->Deliverypartner_detail->account_no:"",
            "ifsc_code"=>isset($data->Deliverypartner_detail)?$data->Deliverypartner_detail->ifsc_code:"",
            'rating'=>$partner_rating,
            'city'=>isset($data->Deliverypartner_detail->City)?$data->Deliverypartner_detail->City->city:"",
            'earnings'=>1500.00
        );
   
       // $response_array = array('status'=>true,'data'=>$provider);
       $response = response()->json($response_array, 200);
        return $response;
    }

    public function send_otp_login(Request $request)
    {
        $phone = (string) $request->phone;
                $otp = rand(10000,99999);
                $message = 'OTP to verify EatZilla Application : '.$otp;
                $sendSms = $this->send_otp($phone,$otp);

        $response_array = array('status'=>true,'message'=>'OTP sent successfully','otp'=>$otp);

        $response = response()->json($response_array, 200);
        return $response;
    }

    	public function provider_login(Request $request)
    {

           $validator = Validator::make(
                $request->all(),
                array(
                    'device_token' => 'required',
                    'phone' => 'required',
                    'password' => 'required'
                ));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        }else
        {
            $deliverypartners = $this->deliverypartners;
            $device_token = $request->device_token;
            $device_type = $request->device_type;
            
                $phone = $request->phone;
                $password = $this->encrypt_password($request->password);


                 $data = $deliverypartners::where('phone','like','%'.$phone.'%')->where('password',$password)->first();

                  if (!empty($data)) {

                    $authId = $data->id;
                    $profile_image = $data->profile_image;
                    if($profile_image==NULL || $profile_image=="")
                    {
                    	$profile_image = BASE_URL.PROFILE_ICON;
                    }
                    if($data->name!=NULL)
                    {
                    $name = $data->name;
                    }else
                    {
                        $name="";
                    } 

                    $authToken =$this->generateRandomString();

                    $partner_id = $data->partner_id;

                    $deliverypartners::where('id',$data->id)->update(['device_type'=>$device_type, 'device_token'=>$device_token,'authToken'=>$authToken]);

                    $response_array = array('status' => true,'message' => 'Logged in successfully','authId'=>$authId,'authToken'=>$authToken,'phone'=>$phone,'profile_image'=>$profile_image,'email'=>"",'user_name'=>$name,'partner_id'=>$partner_id);

                  }else
                  {
                    $response_array = array('status' => false,'message' => 'Invalid Login');
                  }
            
        }

        $response = response()->json($response_array, 200);
        return $response;
    }
    

    public function update_profile(Request $request)
    {
        # code... profile_update

        $deliverypartners = $this->deliverypartners;
        $custom = $this->custom;
        $update = array();
        $data = $deliverypartners::where('id',$request->id)->first();

        if ($request->name) {
            $update['name'] = $request->name;
        }
        if ($request->email) {
            $update['email'] = $request->email;
        }
        if($request->password)
        {
            $update['password'] = $this->encrypt_password($request->password);
        }
        // if ($request->address) {
        //     $update['lat'] = $request->lat;
        // }
        // if ($request->address) {
        //     $update['lng'] = $request->lng;
        // }
        // if ($request->address) {
        //     $update['address'] = $request->address;
        // }
        // if ($request->city) {
        //     $update['city'] = $request->city;
        // }


        // if ($request->profile_image) {
        //     if ($data->profile_image != "") {
        //         $custom::delete_image($data->profile_image);
        //     }
        //    $profile_pic = $custom::upload_image($request,'profile_image');
        //     $update['profile_image'] = BASE_URL.$profile_pic;
        // }
        
        $deliverypartners::where('id',$request->id)->update($update);
        
        $data = $deliverypartners::where('id',$request->id)->first();
        // $data = $this->check_null($data)->toArray();
        $response_array = array('status'=>true,'message'=>'Profile updated successfully','data'=>$data);
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function forgot_password(Request $request)
    {
         $validator = Validator::make(
                $request->all(),
                array(
                    'phone' => 'required'
                ));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        }else
        {
            $phone = $request->phone;

            $deliverypartners = $this->deliverypartners;

            $check_user = $deliverypartners::where('phone','like','%'.$phone.'%')->first();

            if(count($check_user)!=0)
            {
                $phone = (string) $request->phone;
                $otp = rand(10000,99999);
                $message = 'OTP to verify EatZilla Application : '.$otp;
                $sendSms = $this->send_otp($phone,$otp);

            $response_array = array('status'=>true,'message'=>'OTP sent successfully','otp'=>$otp);
            }else
            {
                $response_array = array('status'=>false,'message'=>'Mobile number not registered');
            }
        }

         $response = response()->json($response_array, 200);
        return $response;

    }

    public function reset_password(Request $request)
    {

         $validator = Validator::make(
                $request->all(),
                array(
                    'password' => 'required',
                    'phone' => 'required'
                ));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        }else
        {
            $password = $this->encrypt_password($request->password);
            $phone = $request->phone;
            $deliverypartners = $this->deliverypartners;

            $get_user = $deliverypartners::where('phone',$phone)->first();

            $deliverypartners::where('phone',$phone)->update(['password'=>$password]);

            $response_array = array('status'=>true,'message'=>'Password Reset Successfull');
        }

        $response = response()->json($response_array, 200);
        return $response;

    }


    /**
     * API for earnings based on daily
     * 
     * @param object $request
     * 
     * @return json $response
     */
    public function today_earnings(Request $request)
    {
        $user_id = $request->header('authId');
        $foodrequest = $this->foodrequest;
        $today_date = new \DateTime();
        $date = ((isset($request->filter_date) && $request->filter_date!='')) ? new \DateTime($request->filter_date) : $today_date;
        
        //get daily total earnings        
        $today_earnings = $foodrequest::where('delivery_boy_id',$user_id)
                            ->whereBetween('ordered_time',[$date->format('Y-m-d')." 00:00:00", $date->format('Y-m-d')." 23:59:59"])
                            ->where('is_paid',1)
                            ->sum('bill_amount');
        $today_incentives = $foodrequest::where('delivery_boy_id',$user_id)
                            ->whereBetween('ordered_time',[$date->format('Y-m-d')." 00:00:00", $date->format('Y-m-d')." 23:59:59"])
                            ->where('is_paid',1)
                            ->sum('delivery_boy_commision'); 

        $response_array = array('status'=>true,'today_earnings'=>$today_earnings,'today_incentives'=>
                            $today_incentives);
        $response = response()->json($response_array, 200);
        return $response;
    }



    /**
     * API for weekly earnings
     * 
     * @param object $request
     * 
     * @return json $response
     */
    public function weekly_earnings(Request $request)
    {
        $user_id = $request->header('authId');
        $foodrequest = $this->foodrequest;
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        $date = ((isset($request->filter_date) && $request->filter_date!='')) ? $request->filter_date : Carbon::now();

        //get weekly earnings
        $weekly_earnings  = $foodrequest->where('delivery_boy_id',$user_id)->where('is_paid',1)
                                    ->whereBetween('ordered_time',[Carbon::parse($date)->startOfWeek(),Carbon::parse($date)->endOfWeek()])
                                    ->sum('bill_amount');
        $weekly_incentives  = $foodrequest->where('delivery_boy_id',$user_id)->where('is_paid',1)
                                    ->whereBetween('ordered_time',[Carbon::parse($date)->startOfWeek(),Carbon::parse($date)->endOfWeek()])
                                    ->sum('delivery_boy_commision');
        $graph_data = $foodrequest->where('delivery_boy_id',$user_id)->where('is_paid',1)
                            ->whereBetween('ordered_time',[Carbon::parse($date)->startOfWeek(),Carbon::parse($date)->endOfWeek()])
                            ->select(array(DB::Raw('sum(bill_amount) as total_amount'),DB::Raw('count(id) as total_orders'),DB::Raw('DATE(ordered_time) day')))
                            ->groupBy('day')
                            ->get();
        $response_array = array('status'=>true,'weekly_earnings'=>round($weekly_earnings,2),'weekly_incentives'=>
                            round($weekly_incentives,2),'graph_data'=>$graph_data);

        $response = response()->json($response_array, 200);
        return $response;
    }


    /**
     * API for monthly earnings
     * 
     * @param object $request
     * 
     * @return json $response
     */
    public function monthly_earnings(Request $request)
    {
        $user_id = $request->header('authId');
        $foodrequest = $this->foodrequest;
        $date = ((isset($request->filter_date) && $request->filter_date!='')) ? $request->filter_date : Carbon::now();

        //get weekly earnings
        $monthly_earnings  = $foodrequest->where('delivery_boy_id',$user_id)->where('is_paid',1)
                                    ->whereBetween('ordered_time',[Carbon::parse($date)->startOfMonth(),Carbon::parse($date)->endOfMonth()])
                                    ->sum('bill_amount');
        $monthly_incentives  = $foodrequest->where('delivery_boy_id',$user_id)->where('is_paid',1)
                                    ->whereBetween('ordered_time',[Carbon::parse($date)->startOfMonth(),Carbon::parse($date)->endOfMonth()])
                                    ->sum('delivery_boy_commision');
        $graph_data = $foodrequest->where('delivery_boy_id',$user_id)->where('is_paid',1)
                            ->whereBetween('ordered_time',[Carbon::parse($date)->startOfMonth(),Carbon::parse($date)->endOfMonth()])
                            ->select(array(DB::Raw('sum(bill_amount) as total_amount'),DB::Raw('count(id) as total_orders'),DB::Raw('DATE(ordered_time) day')))
                            ->groupBy('day')
                            ->get();
        $response_array = array('status'=>true,'monthly_earnings'=>round($monthly_earnings,2),'monthly_incentives'=>
                            round($monthly_incentives,2),'graph_data'=>$graph_data);

        $response = response()->json($response_array, 200);
        return $response;
    }


    /**
     * delivery boy logout
     * 
     * @param object $request
     * 
     * @return json $response
     */
    public function logout(Request $request)
    {
        $user_id = $request->header('authId');
        $deliverypartners = $this->deliverypartners;
        $deliverypartners::where('id','=',$user_id)->update(['authToken'=>0]);

        $response_array = array('status'=>true,'message'=>'Logged Out Successfully');
        $response = response()->json($response_array, 200);
        return $response;
    }


    /**
     * API for driver payout
     * 
     * @param object $request
     * 
     * @return json $response
     */
    public function payout_details(Request $request)
    {
        $user_id = $request->header('authId');

        if($user_id=='')
        {
            $error_messages = "AuthID should not be empty";
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        }
        //get payout history
        $payout_history  = $this->driver_payout_history->where('delivery_boy_id', $user_id)
                                        ->orderBy('created_at','desc')->limit(5)->get();
                            
        $pending_amount = $this->foodrequest->where('delivery_boy_id', $user_id)
                                    ->where('driver_settlement_status',0)->where('status',7)
                                    ->sum('delivery_boy_commision');

        $response_array = array('status'=>true,'pending_payout'=> round($pending_amount,2),'payout_history'=>$payout_history);
        $response = response()->json($response_array, 200);
        return $response;
    }

}