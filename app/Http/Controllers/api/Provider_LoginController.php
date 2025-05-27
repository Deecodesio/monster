<?php

namespace App\Http\Controllers\api;

use Validator;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use Log;
use File;
use App\Model\Deliverypartners;
use App\Model\DriverList;
use App\Model\DriverDocument;
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
                //$password = bcrypt($request->password);
                $password = $this->encrypt_password($request->password);


                Log::info('NEW PASSWORD IS = '.$request->password.' '.$password);

// dd('NEW PASSWORD IS = '.$request->password.' '.$password);
                $data = $deliverypartners::where('phone',$phone)->where('password',$password)->where('status',1)->first();
                  if($data)
                  {

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
            $update['password'] = bcrypt($request->password);
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
            $password = bcrypt($request->password);
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
        $today_tips = $foodrequest::where('delivery_boy_id',$user_id)
                            ->whereBetween('ordered_time',[$date->format('Y-m-d')." 00:00:00", $date->format('Y-m-d')." 23:59:59"])
                            ->where('is_paid',1)
                            ->sum('tips');

        $todayEarningsAmt = $foodrequest::where('delivery_boy_id', $user_id)
                            ->whereBetween('ordered_time', [$date->format('Y-m-d')." 00:00:00", $date->format('Y-m-d')." 23:59:59"])
                            ->where('is_paid', 1)
                            ->sum('delivery_charge');

        $todayOrders = $foodrequest::where('delivery_boy_id', $user_id)
                            ->whereBetween('ordered_time', [$date->format('Y-m-d')." 00:00:00", $date->format('Y-m-d')." 23:59:59"])
                            ->where('is_paid', 1)
                            ->count();
        $sessions = DB::table('delivery_boy_login_status')
                            ->where('delivery_boy_id', $user_id)
                            ->whereBetween('start_time',  [$date->format('Y-m-d')." 00:00:00", $date->format('Y-m-d')." 23:59:59"])
                            ->get();
                            $totalSeconds = 0;
                            foreach ($sessions as $session) {
                                $sessionStart = new \DateTime($session->start_time);
                                $sessionEnd = $session->end_time ? new \DateTime($session->end_time) : now();

                                $diffInSeconds = $sessionStart->diff($sessionEnd)->days * 24 * 60 * 60 +
                                                 $sessionStart->diff($sessionEnd)->h * 60 * 60 +
                                                 $sessionStart->diff($sessionEnd)->i * 60 +
                                                 $sessionStart->diff($sessionEnd)->s;

                                $totalSeconds += $diffInSeconds;
                            }

                            $hours = floor($totalSeconds / 3600);
                            $minutes = floor(($totalSeconds % 3600) / 60);
                            $seconds = $totalSeconds % 60;
            $loggedTimes = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $response_array = array('status'=>true,'today_earnings'=>$todayEarningsAmt,'today_incentives'=>
                            $today_incentives,'tips' => $today_tips,'today_orders' => $todayOrders,'today_total_time' => $loggedTimes);
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
        $weekly_tips  = $foodrequest->where('delivery_boy_id',$user_id)->where('is_paid',1)
                                    ->whereBetween('ordered_time',[Carbon::parse($date)->startOfWeek(),Carbon::parse($date)->endOfWeek()])
                                    ->sum('tips');

        $graph_data = $foodrequest->where('delivery_boy_id',$user_id)->where('is_paid',1)
                            ->whereBetween('ordered_time',[Carbon::parse($date)->startOfWeek(),Carbon::parse($date)->endOfWeek()])
                            ->select(array(DB::Raw('sum(delivery_charge) as total_amount'),DB::Raw('count(id) as total_orders'),DB::Raw('DATE(ordered_time) day')))
                            ->groupBy('day')
                            ->get();


            $weeklyEarningsAmt = $foodrequest::where('delivery_boy_id', $user_id)
                            ->whereBetween('ordered_time', [Carbon::parse($date)->startOfWeek(),Carbon::parse($date)->endOfWeek()])
                            ->where('is_paid', 1)
                            ->sum('delivery_charge');

            $weeklyOrders = $foodrequest::where('delivery_boy_id', $user_id)
                            ->whereBetween('ordered_time', [Carbon::parse($date)->startOfWeek(),Carbon::parse($date)->endOfWeek()])
                            ->where('is_paid', 1)
                            ->count();

            $sessions = DB::table('delivery_boy_login_status')
                            ->where('delivery_boy_id', $user_id)
                            ->whereBetween('start_time', [Carbon::parse($date)->startOfWeek(),Carbon::parse($date)->endOfWeek()])
                            ->get();
                            $totalSeconds = 0;
                            foreach ($sessions as $session) {
                                $sessionStart = new \DateTime($session->start_time);
                                $sessionEnd = $session->end_time ? new \DateTime($session->end_time) : now();

                                $diffInSeconds = $sessionStart->diff($sessionEnd)->days * 24 * 60 * 60 +
                                                 $sessionStart->diff($sessionEnd)->h * 60 * 60 +
                                                 $sessionStart->diff($sessionEnd)->i * 60 +
                                                 $sessionStart->diff($sessionEnd)->s;

                                $totalSeconds += $diffInSeconds;
                            }

                            $hours = floor($totalSeconds / 3600);
                            $minutes = floor(($totalSeconds % 3600) / 60);
                            $seconds = $totalSeconds % 60;

                            $weekly_total_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $response_array = array('status'=>true,'weekly_earnings'=>round($weeklyEarningsAmt,2),'weekly_tips'=>round($weekly_tips,2),'weekly_incentives'=>
                            round($weekly_incentives,2),'graph_data'=>$graph_data,'weeklyOrders'=>$weeklyOrders,'weekly_total_time' => $weekly_total_time);

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
        $tips  = $foodrequest->where('delivery_boy_id',$user_id)->where('is_paid',1)
                                    ->whereBetween('ordered_time',[Carbon::parse($date)->startOfMonth(),Carbon::parse($date)->endOfMonth()])
                                    ->sum('tips');
        $graph_data = $foodrequest->where('delivery_boy_id',$user_id)->where('is_paid',1)
                            ->whereBetween('ordered_time',[Carbon::parse($date)->startOfMonth(),Carbon::parse($date)->endOfMonth()])
                            ->select(array(DB::Raw('sum(delivery_charge) as total_amount'),DB::Raw('count(id) as total_orders'),DB::Raw('DATE(ordered_time) day')))
                            ->groupBy('day')
                            ->get();


        $monthlyEarningsAmt = $foodrequest::where('delivery_boy_id', $user_id)
                            ->whereBetween('ordered_time', [Carbon::parse($date)->startOfMonth(),Carbon::parse($date)->endOfMonth()])
                            ->where('is_paid', 1)
                            ->sum('delivery_charge');

        $monthlyOrders = $foodrequest::where('delivery_boy_id', $user_id)
                            ->whereBetween('ordered_time', [Carbon::parse($date)->startOfMonth(),Carbon::parse($date)->endOfMonth()])
                            ->where('is_paid', 1)
                            ->count();

                            $sessions = DB::table('delivery_boy_login_status')
                            ->where('delivery_boy_id', $user_id)
                            ->whereBetween('start_time', [Carbon::parse($date)->startOfMonth(),Carbon::parse($date)->endOfMonth()])
                            ->get();

                            $totalSeconds = 0;
                            foreach ($sessions as $session) {
                                $sessionStart = new \DateTime($session->start_time);
                                $sessionEnd = $session->end_time ? new \DateTime($session->end_time) : now();

                                $diffInSeconds = $sessionStart->diff($sessionEnd)->days * 24 * 60 * 60 +
                                                 $sessionStart->diff($sessionEnd)->h * 60 * 60 +
                                                 $sessionStart->diff($sessionEnd)->i * 60 +
                                                 $sessionStart->diff($sessionEnd)->s;

                                $totalSeconds += $diffInSeconds;
                            }

                            $hours = floor($totalSeconds / 3600);
                            $minutes = floor(($totalSeconds % 3600) / 60);
                            $seconds = $totalSeconds % 60;

                            $monthly_total_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $response_array = array('status'=>true,'monthly_earnings'=>round($monthlyEarningsAmt,2),'monthly_tips'=>round($tips,2),'monthly_incentives'=>
                            round($monthly_incentives,2),'graph_data'=>$graph_data,'monthlyOrders'=>$monthlyOrders,'monthly_total_time' => $monthly_total_time);

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

        $pending = $this->foodrequest->where('delivery_boy_id', $user_id)
                                    ->where('driver_settlement_status',0)->where('status',7)
                                    ->sum('delivery_boy_commision');
        $tips = $this->foodrequest->where('delivery_boy_id', $user_id)
                                    ->where('driver_settlement_status',0)->where('status',7)
                                    ->sum('tips');
         $pending_amount =  $pending +  $tips;

        $response_array = array('status'=>true,'pending_payout'=> round($pending_amount,2),'payout_history'=>$payout_history);
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function android_version () {
        $version = env('ANDROID_DRIVER_APP_VERSION');
        $response = response()->json(['status'=>true,'message'=>'Success', 'Version' => $version], 200);
        return $response;
    }

    public function ios_version () {
        $version = env('IOS_DRIVER_APP_VERSION');
        $response = response()->json(['status'=>true,'message'=>'Success', 'Version' => $version], 200);
        return $response;
    }



    public function login_status(Request $request)
{
    // Validate input
    $validatedData = $request->validate([
        'authId' => 'required|integer',
        'authToken' => 'required|string',
        'status' => 'required|integer|in:0,1',
    ]);

    $user_id = $validatedData['authId'];
    $token = $validatedData['authToken'];



    if ($validatedData['status'] == 1) {
        // Insert a new login record with the current timestamp
        DB::table('delivery_boy_login_status')->insert([
            'start_time' => now(),
            'delivery_boy_id' => $user_id,
            'date' => now()->toDateString(),
        ]);
    } else {
        // Get the last login record for the user
        $lastEntry = DB::table('delivery_boy_login_status')
            ->where('delivery_boy_id', $user_id)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastEntry && !$lastEntry->end_time) {
            // Update the end_time for the last login entry
            DB::table('delivery_boy_login_status')
                ->where('id', $lastEntry->id)
                ->update(['end_time' => now(),'date' => now()->toDateString(),]);
        } else {
            return response()->json(['status' => false, 'message' => 'No active login record found'], 400);
        }
    }

    return response()->json(['status' => true, 'message' => 'Status updated successfully'], 200);
}

public function earnings(Request $request)
{
    $user_id = $request->header('authId');

    if (!$user_id) {
        return response()->json(['status' => false, 'message' => 'User ID is required'], 400);
    }

    $foodRequest = $this->foodrequest;
    $todayDate = now()->format('Y-m-d');
    $weekStartDate = now()->subDays(6)->format('Y-m-d'); // Start of the last 7 days
    $monthStartDate = now()->startOfMonth()->format('Y-m-d'); // Start of the current month

    // Get today's earnings
    $todayEarningsAmt = $foodRequest::where('delivery_boy_id', $user_id)
        ->whereBetween('ordered_time', ["$todayDate 00:00:00", "$todayDate 23:59:59"])
        ->where('is_paid', 1)
        ->sum('delivery_charge');

    // $todayIncentives = $foodRequest::where('delivery_boy_id', $user_id)
    //     ->whereBetween('ordered_time', ["$todayDate 00:00:00", "$todayDate 23:59:59"])
    //     ->where('is_paid', 1)
    //     ->sum('delivery_boy_commision');

    // $todayTips = $foodRequest::where('delivery_boy_id', $user_id)
    //     ->whereBetween('ordered_time', ["$todayDate 00:00:00", "$todayDate 23:59:59"])
    //     ->where('is_paid', 1)
    //     ->sum('tips');

    $todayOrders = $foodRequest::where('delivery_boy_id', $user_id)
        ->whereBetween('ordered_time', ["$todayDate 00:00:00", "$todayDate 23:59:59"])
        ->where('is_paid', 1)
        ->count();

    $todayEarnings = $todayEarningsAmt ;

    // Get weekly earnings
    $weeklyEarningsAmt = $foodRequest::where('delivery_boy_id', $user_id)
        ->whereBetween('ordered_time', ["$weekStartDate 00:00:00", "$todayDate 23:59:59"])
        ->where('is_paid', 1)
        ->sum('delivery_charge');

    // $weeklyIncentives = $foodRequest::where('delivery_boy_id', $user_id)
    //     ->whereBetween('ordered_time', ["$weekStartDate 00:00:00", "$todayDate 23:59:59"])
    //     ->where('is_paid', 1)
    //     ->sum('delivery_boy_commision');

    // $weeklyTips = $foodRequest::where('delivery_boy_id', $user_id)
    //     ->whereBetween('ordered_time', ["$weekStartDate 00:00:00", "$todayDate 23:59:59"])
    //     ->where('is_paid', 1)
    //     ->sum('tips');

    $weeklyOrders = $foodRequest::where('delivery_boy_id', $user_id)
        ->whereBetween('ordered_time', ["$weekStartDate 00:00:00", "$todayDate 23:59:59"])
        ->where('is_paid', 1)
        ->count();

    $weeklyEarnings = $weeklyEarningsAmt ;

    // Get this month's earnings
    $monthlyEarningsAmt = $foodRequest::where('delivery_boy_id', $user_id)
        ->whereBetween('ordered_time', ["$monthStartDate 00:00:00", "$todayDate 23:59:59"])
        ->where('is_paid', 1)
        ->sum('delivery_charge');

    // $monthlyIncentives = $foodRequest::where('delivery_boy_id', $user_id)
    //     ->whereBetween('ordered_time', ["$monthStartDate 00:00:00", "$todayDate 23:59:59"])
    //     ->where('is_paid', 1)
    //     ->sum('delivery_boy_commision');

    // $monthlyTips = $foodRequest::where('delivery_boy_id', $user_id)
    //     ->whereBetween('ordered_time', ["$monthStartDate 00:00:00", "$todayDate 23:59:59"])
    //     ->where('is_paid', 1)
    //     ->sum('tips');

    $monthlyOrders = $foodRequest::where('delivery_boy_id', $user_id)
        ->whereBetween('ordered_time', ["$monthStartDate 00:00:00", "$todayDate 23:59:59"])
        ->where('is_paid', 1)
        ->count();

    $monthlyEarnings = $monthlyEarningsAmt ;

    // Calculate logged-in time for today, week, and month
    $timePeriods = [
        'today' => ["$todayDate 00:00:00", "$todayDate 23:59:59"],
        'week' => ["$weekStartDate 00:00:00", "$todayDate 23:59:59"],
        'month' => ["$monthStartDate 00:00:00", "$todayDate 23:59:59"],
    ];

    $loggedTimes = [];
    foreach ($timePeriods as $period => [$startTime, $endTime]) {
        $sessions = DB::table('delivery_boy_login_status')
            ->where('delivery_boy_id', $user_id)
            ->whereBetween('start_time', [$startTime, $endTime])
            ->get();

        $totalSeconds = 0;
        foreach ($sessions as $session) {
            $sessionStart = new \DateTime($session->start_time);
            $sessionEnd = $session->end_time ? new \DateTime($session->end_time) : now();

            $diffInSeconds = $sessionStart->diff($sessionEnd)->days * 24 * 60 * 60 +
                             $sessionStart->diff($sessionEnd)->h * 60 * 60 +
                             $sessionStart->diff($sessionEnd)->i * 60 +
                             $sessionStart->diff($sessionEnd)->s;

            $totalSeconds += $diffInSeconds;
        }

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $loggedTimes[$period] = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    // Build the response array
    $responseArray = [
        'status' => true,
        'today_earnings' => (string)$todayEarnings,
        'today_orders' => (string)$todayOrders,
        'today_total_time' => $loggedTimes['today'],
        'weekly_earnings' => (string)$weeklyEarnings,
        'weekly_orders' => (string)$weeklyOrders,
        'weekly_total_time' => $loggedTimes['week'],
        'monthly_earnings' => (string)$monthlyEarnings,
        'monthly_orders' => (string)$monthlyOrders,
        'monthly_total_time' => $loggedTimes['month'],
    ];

    return response()->json($responseArray, 200);
}



public function get_all_list(Request $request)
{



    // $zones = DB::table('zones')->get();
    // $zone1 = [];
    // foreach ($zones as $zone) {
    //     $zones_charge = DB::table('zone_delivery_charge')->where('business_type',1)->where('zone_id', $zone->id)->get();
    //     $zone1[] = [
    //         'id' =>$zone->id,
    //         'name' => $zone->name,
    //         'charges' => $zones_charge,
    //         'status' => $zone->status
    //     ];
    // }

    $city_list = DB::table('add_city')->where('status',1)->get();

    $service = DB::table('business_type')->where('status', 1)->get();


    // Build the response array
    $responseArray = [
        'status' => true,
        // 'zone' => $zone1,
        'city' => $city_list,
        'service' => $service,

    ];

    return response()->json($responseArray, 200);
}

public function document_list(Request $request)
{

    $document_list = $this->document->where('status',1)->where('document_for',1)->get();
    foreach($document_list as $d)
    {
    $d->document_name = $this->secondLanguage($d->document_name,$d->secondary_document_name);

    }


    // Build the response array
    $responseArray = [
        'status' => true,
        'document' => $document_list,


    ];

    return response()->json($responseArray, 200);
}


public function register(Request $request)
{
    // dd($request);
    $custom = $this->custom;


    $conditions = [
        'name' => 'required',
        'phone_no' => 'required',
        'email' => 'required',
        'dob' => 'required',
        'gender' => 'required',
        'address_line_1' => 'required',
        'city' => 'required',
        'zip_code' => 'required',

    ];

    $validator = Validator::make($request->all(), $conditions);

    if ($validator->fails()) {
        $error_messages = implode(',', $validator->messages()->all());
            $message =$error_messages;
            $status = false;
            $response_Array = json_encode(['message'=>$message,'status'=>$status]);
            return $response_Array;
    }


    $name = html_entity_decode($request->name, ENT_QUOTES | ENT_XML1, 'UTF-8');
    $address_line_1 = html_entity_decode($request->address_line_1, ENT_QUOTES | ENT_XML1, 'UTF-8');
    $city = html_entity_decode($request->city, ENT_QUOTES | ENT_XML1, 'UTF-8');

    if($request->id)
    {
        $insert1 = Deliverypartners::find($request->id);

        if ($request->phone_no != $insert1->phone)
        {
            $duplicate_check = Deliverypartners::where('phone', $request->phone_no)->first();
            if (!empty($duplicate_check)) {
                $message ="Phone number already exists";
                $status = false;
                $response_Array = json_encode(['message'=>$message,'status'=>$status]);
                return $response_Array;
            }
        }
    }else{
        $insert1 = new Deliverypartners();
        $duplicate_check = Deliverypartners::where('phone', $request->phone_no)->first();
        if (!empty($duplicate_check)) {
            // return back()->with('error', 'Phone number already exists')->withInput();
            $message ="Phone number already exists";
            $status = false;
            $response_Array = json_encode(['message'=>$message,'status'=>$status]);
            return $response_Array;

        }
    }


    if ($request->id) {
        $insert1->partner_id = $insert1->partner_id;
    } else {
        $insert1->partner_id = $this->generate_partner_id();
    }
    $insert1->name = $name ?? '';
    $insert1->second_driver_name = '';
    $insert1->email = $request->email ?? '';
    $insert1->phone = $request->phone_no ?? '';
    $insert1->country_code = $request->country_code ?? '';
    $insert1->country_calling_code = $request->country_calling_code ?? '';

    $insert1->status = 3;
    $insert1->license = null;
    $insert1->profile_pic = '';
    // $insert1->zones = '';
    $insert1->save();
    $partner_id = $insert1->id;


    if ($request->id) {
        $insert1 = DB::table('delivery_partner_details')->where('delivery_partners_id', $request->id)->first();
        $insert = DriverList::find($insert1->id);
    } else {
        $insert = new DriverList();
    }

    $insert->delivery_partners_id = $partner_id;
    $insert->city = $city ?? '';
    $insert->vehicle_name = $request->vehicle_name ?? '';
    $insert->address_line_1 = $address_line_1 ?? '';
    $insert->address_line_2 =  '';
    $insert->address_city =  '';
    $insert->state_province = '';
    $insert->country =  '';
    $insert->zip_code = $request->zip_code;
    $insert->about = '';
    $insert->gender = $request->gender;
    $insert->save();
    $detail = $insert->id;
    if ($request->id) {
        $detail =  DB::table('delivery_partners')->leftJoin('delivery_partner_details','delivery_partner_details.delivery_partners_id','=','delivery_partners.id')->where('delivery_partners.id',$request->id)->select('delivery_partners.*','delivery_partner_details.*','delivery_partners.id as Driver_id')->first();
   }else{
        $detail =  DB::table('delivery_partners')->leftJoin('delivery_partner_details','delivery_partner_details.delivery_partners_id','=','delivery_partners.id')->where('delivery_partners.id',$insert1->id)->select('delivery_partners.*','delivery_partner_details.*','delivery_partners.id as Driver_id')->first();

    }

    $message ="Added Sucessfully";
    $status = true;
    $response_Array = json_encode(['message'=>$message,'status'=>$status,'id'=>$partner_id,'detail'=>$detail]);
    return $response_Array;

//    return response()->json();
}


public function register2(Request $request)
{
    // dd($request);
    $custom = $this->custom;


    $conditions = [
        'services' => 'required',
        // 'zones' => 'required',
        'profile_pic' => 'required',
    ];

    $validator = Validator::make($request->all(), $conditions);

    if ($validator->fails()) {
        $error_messages = implode(',', $validator->messages()->all());
            $message =$error_messages;
            $status = false;
            $response_Array = json_encode(['message'=>$message,'status'=>$status]);
            return $response_Array;
    }

    if ($request->profile_pic) {

        $profile_picture = $custom->driver_image_upload_app($request, 'profile_pic');

    }


        $insert1 = Deliverypartners::find($request->id);



    $insert1->profile_pic = $profile_picture ?? '';
    // $insert1->zones = $request->zones ?? '';
    $insert1->save();
    $partner_id = $insert1->id;



        $insert1 = DB::table('delivery_partner_details')->where('delivery_partners_id', $request->id)->first();
        $insert = DriverList::find($insert1->id);

            if ($request->services) {
                $array_delivery = explode(',', $request->services);
            $rt = json_encode($array_delivery);
        } else {
            $rt = '[]';
        }
        $insert->services =  $rt;
    $insert->save();
    $detail = $insert->id;

    $detail =  DB::table('delivery_partners')->leftJoin('delivery_partner_details','delivery_partner_details.delivery_partners_id','=','delivery_partners.id')->where('delivery_partners.id',$request->id)->first();
    // if( $detail->zones)
    // {
    //     $detail->zones = array_map('intval', explode(',', $detail->zones));
    // }

    $detail->services = json_decode($detail->services);
    if($detail->profile_pic)
    {
        $detail->profile_pic = BASE_URL.$detail->profile_pic;
    }

    $message ="Added Sucessfully";
    $status = true;
    $response_Array = json_encode(['message'=>$message,'status'=>$status,'id'=>$partner_id,'detail'=>$detail]);
    return $response_Array;

//    return response()->json();
}


    public function register3(Request $request)
    {

         $custom = $this->custom;
         //driver document upload
         if ($request->license) {
         if ($request->license != null) {
            $licenses = $request->license;
            // $exp_date = $request->license_expiry;
            // $document_id = $request->doc_id;
            // $expdate_id = $request->exp_id;

            DB::table('driver_document')->where('driver_id', $request->id)->delete();

            for ($i = 0; $i < count($licenses); $i++) {
                $dri_doc = new DriverDocument();

                $license = $custom->base_image_upload_license_app($licenses[$i]["license"]); //dd($license);
                $dri_doc->driver_id = $request->id;
                $dri_doc->document_id = $licenses[$i]["doc_id"];
                $dri_doc->document = $license;
                //dd($dri_doc);
                $dri_doc->save();
                if($licenses[$i]["exp_id"])
                {

                        if ($licenses[$i]["doc_id"] ==  $licenses[$i]["exp_id"]) {
                            //dd($exp_date[$e]);
                            $doc = DB::table('driver_document')->where('driver_id', $request->id)->where('document_id', $licenses[$i]["doc_id"])
                                ->update(['expiry_date' => $licenses[$i]["license_expiry"]]);

                    }
                }

            }

        }
    }


    $detail =  DB::table('delivery_partners')->leftJoin('delivery_partner_details','delivery_partner_details.delivery_partners_id','=','delivery_partners.id')->where('delivery_partners.id',$request->id)->first();

//     if( $detail->zones)
//    {
//     $detail->zones = array_map('intval', explode(',', $detail->zones));
//    }

    $detail->services = json_decode($detail->services);
    if($detail->profile_pic)
    {
        $detail->profile_pic = BASE_URL.$detail->profile_pic;
    }
         $message ="Added Sucessfully";
         $status = true;
         $response_Array = json_encode(['message'=>$message,'status'=>$status,'id'=> $request->id,'detail'=>$detail]);
         return $response_Array;


    }


    public function register4(Request $request)
    {

        if ($request->id) {
            $insert1 = DB::table('delivery_partner_details')->where('delivery_partners_id', $request->id)->first();
            $insert = DriverList::find($insert1->id);
        } else {
            $insert = new DriverList();

        }

        $account_name = html_entity_decode($request->account_name, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $account_address = html_entity_decode($request->account_address, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $account_no = html_entity_decode($request->account_no, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $bank_name = html_entity_decode($request->bank_name, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $branch_name = html_entity_decode($request->branch_name, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $branch_address = html_entity_decode($request->branch_address, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $swift_code = html_entity_decode($request->swift_code, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $routing_no = html_entity_decode($request->routing_no, ENT_QUOTES | ENT_XML1, 'UTF-8');





        $insert->account_name = $account_name;
        $insert->account_address = $account_address;
        $insert->account_no = $account_no;
        $insert->bank_name = $bank_name;
        $insert->branch_name = $branch_name;
        $insert->branch_address = $branch_address;
        $insert->swift_code = $swift_code;
        $insert->routing_no = $routing_no;

        $insert->save();
        $detail =  DB::table('delivery_partners')->leftJoin('delivery_partner_details','delivery_partner_details.delivery_partners_id','=','delivery_partners.id')->where('delivery_partners.id',$request->id)->first();
        // $detail->zones = array_map('intval', explode(',', $detail->zones));
        $detail->services = json_decode($detail->services);
        if($detail->profile_pic)
        {
            $detail->profile_pic = BASE_URL.$detail->profile_pic;
        }

        $message ="Added Sucessfully";
        $status = true;
        $response_Array = json_encode(['message'=>$message,'status'=>$status,'detail'=>$detail]);
        return $response_Array;
    }


    public function provider_login_v1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error_code' => 101,
                'message' => implode(',', $validator->messages()->all())
            ], 400);
        }

        $otp = rand(10000, 99999);
        $message = 'OTP to verify ' . APP_NAME . ' Application : ' . $otp;

        $check_sms = DB::table('settings')->where('key_word', "sms_enable")->first();
        $deliverypartners = $this->deliverypartners;

        $phone = $request->phone;
        $country_code = $request->country_code;
        $country_calling_code = $request->country_calling_code;
        $device_token = $request->device_token;
        $device_type = $request->device_type;

        $data = $deliverypartners::where([
            ['phone', $phone],
            ['country_calling_code', $country_calling_code],
            ['country_code', $country_code],

        ])->first();
        $phone_new = $country_calling_code.''.$phone;

        // Initialize default values
        $detail = [];
        $document_detail = [];
        $register = 0;
        $register_profile = 0;
        $register_document = 0;
        $register_bank = 0;
        $authId = '';
        $authToken = '';
        $profile_image = BASE_URL . PROFILE_ICON;
        $partner_id = '';
        $name = '';
        $is_new_user = 1;
        $status = true;
        $message = "Logged in successfully";

        if ($data) {

            if($data->status == 4)
            {
                $status = true;
                $message = "Your profile is Rejected.Please contact admin";

            }
            if($data->status == 2)
            {
                $status = false;
                $message = "Your profile is Deactive.Please contact admin";

            }
            elseif($data->status == 3)
            {
                $status = false;
                $message = "Your profile is approval proceesing.Please contact admin";

            }else{
                $detail = DB::table('delivery_partners')
                ->leftJoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
                ->where('delivery_partners.id', $data->id)
                ->first();

            if ($detail) {
                // $detail->zones = array_map('intval', explode(',', $detail->zones));
                $detail->services = json_decode($detail->services);
                $detail->profile_pic = !empty($detail->profile_pic) ? BASE_URL . $detail->profile_pic : null;

                $register = !empty($detail->name) ? 1 : 0;
                $register_profile = !empty($detail->profile_pic) ? 1 : 0;
            }

            $document_detail = DB::table('driver_document')->where('driver_id', $data->id)->get();
            foreach($document_detail as $d)
            {
                $d->document = BASE_URL . $d->document;
            }
            $register_document = $document_detail->count() > 0 ? 1 : 0;

            $driver_bank_details = DB::table('delivery_partner_details')->where('delivery_partners_id', $data->id)->first();
            $register_bank = !empty($driver_bank_details->account_name) ? 2 : 0;

            $sendSms = ($check_sms->value == 0) ? 1 : $this->send_otp($phone_new, $otp);
            $message = $sendSms ? trans('constants.otp_sent_successfully') : 'Failed to send OTP';
            $otp = $sendSms ? $otp : '';
            if($sendSms == 1)
             {
                $status = true;
                $message = "Logged in successfully";
             }else{
                $status = false;
                $message = "Failed to send OTP";
             }

            $authId = $data->id;
            $profile_image = !empty($data->profile_image) ? $data->profile_image : $profile_image;
            $name = !empty($data->name) ? $data->name : '';
            $partner_id = $data->partner_id;
            $authToken = $this->generateRandomString();

            $deliverypartners::where('id', $data->id)->update([
                'otp' => $otp,
                'device_type' => $device_type,
                'device_token' => $device_token,
                'authToken' => $authToken
            ]);
            }


            $is_new_user = 0;
        } else {
            $sendSms = ($check_sms->value == 0) ? 1 : $this->send_otp($phone_new, $otp);
             $message = $sendSms ? trans('constants.otp_sent_successfully') : 'Failed to send OTP';
             $otp = $sendSms ? $otp : '';

             if($sendSms == 1)
             {
                $status = true;
                $message = "Logged in successfully";
             }else{
                $status = false;
                $message = "Failed to send OTP";
             }


        }

        return response()->json([
            'status' =>  $status,
            'message' =>  $message,
            'register' => $register,
            'register2' => $register_profile,
            'register3' => $register_document,
            'register4' => $register_bank,
            'document_detail' => $document_detail,
            'detail' => $detail,
            'country_code' => $country_code,
            'country_calling_code' => $country_calling_code,
            'is_new_user' => $is_new_user,
            'IsSMSEnabled' => ($check_sms->value == 1),
            'otp' => (string)$otp,
            'authId' => $authId,
            'authToken' => $authToken,
            'phone' => $phone,
            'profile_image' => $profile_image,
            'email' => '',
            'user_name' => $name,
            'partner_id' => $partner_id
        ], 200);
       }

}
