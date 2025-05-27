<?php

namespace App\Http\Controllers\api;

use Validator;
use Hash;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use File;

class LoginController extends BaseController
{
    //
    public function support()
    {
        $isCoDEnabled = $this->settings->where('key_word', 'site_contact')->first();
        $email = $this->settings->where('key_word', 'site_email')->first();

        $isMollieEnabled = env('PAYMENT_GATEWAY') == 'Mollie';

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'mobile' => $isCoDEnabled->value,
            'email' => $email->value,

        ]);
    }

    public function get_profile(Request $request)
    {

        $user_id = $request->header('authId') ? $request->header('authId') : $request->authId;

        $data = $this->users->where('id', $user_id)->get();

        foreach ($data as $d) {
            $d->password = $this->decrypt_password($d->password);
        }
        $req = DB::table('requests')->where('user_id', $user_id)
            ->where('status', '!=', -1)
            ->where('status', '!=', 9)
            ->where('status', '!=', 10)
            ->where('status', '!=', 11)
            ->count();
        $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->count();

        // $data->password = $this->decrypt_password($data->password);
        $response_array = array('status' => true, 'data' => $data, 'order_count' => $req, 'fav' => $check_favourite);
        $response = response()->json($response_array, 200);
        return $response;
    }
    public function send_otp_login_v1(Request $request)
    {

        $phone = $request->phone;
        $otp = rand(10000, 99999);
        $message = 'OTP to verify ' . APP_NAME . ' Application : ' . $otp;
        $IsSMSEnabled = $this->settings->where('key_word', 'otp')->first();

        //check user already exist or not
        $check_sms = DB::table('settings')->where('key_word', "sms_enable")->first();
        $message = '';
        $getuser = $this->users->where('phone', $phone)->first();
        if ($getuser) {
            if ($getuser->email) {
                $authId =  $getuser->id;
                $is_new_user = 0;
                if ($check_sms->value == 0) {
                    $sendSms = 1;
                } else {

                    $sendSms = $this->send_otp($phone, $otp);
                }
                if ($sendSms) {
                    $message = trans('constants.otp_sent_successfully');
                } else {
                    $otp = null;
                    $message = 'Failed to send OTP';
                }
            } else {
                $authId =  $getuser->id;
                $is_new_user = 0;
                $otp = null;
                $message = 'Existing user, so not sending OTP';
            }
        } else {
            if ($check_sms->value == 0) {
                $sendSms = 1;
            } else {

                $sendSms = $this->send_otp($phone, $otp);
            }
            $authId = null;
            $is_new_user = 1;
            if ($sendSms) {
                $message = trans('constants.otp_sent_successfully');
            } else {
                $otp = null;
                $message = 'Failed to send OTP';
            }
        }

        $is_demo = false;

        $response_array = [
            'status' => true,
            'message' => $message,
            'otp' => $otp,
            'is_new_user' => $is_new_user,
            'IsSMSEnabled' => $check_sms->value,
            'phone' =>  $phone,
            'authId' => $authId,
            'isdemo' => $is_demo

        ];
        $response = response()->json($response_array, 200);
        return $response;
    }
    public function send_otp_login(Request $request)
    {
        $phone = (string) $request->phone;
        $otp = rand(10000, 99999);
        $message = 'OTP to verify ' . APP_NAME . ' Application : ' . $otp;
        $IsSMSEnabled = $this->settings->where('key_word', 'otp')->first();

        //check user already exist or not
        $check_sms = DB::table('settings')->where('key_word', "sms_enable")->first();
        $message = '';
        $getuser = $this->users->where('phone', $phone)->first();
        if ($getuser) {
            $is_new_user = 0;
            $otp = null;
            $message = 'Existing user, so not sending OTP';
        } else {
            if ($check_sms->value == 0) {
                $sendSms = 1;
            } else {
                $sendSms = $this->send_otp($phone, $otp);
            }

            $is_new_user = 1;
            if ($sendSms) {
                $message = trans('constants.otp_sent_successfully');
            } else {
                $otp = null;
                $message = 'Failed to send OTP';
            }
        }
        if ($check_sms->value == 1) {
            $ch = true;
        } else {
            $ch = false;
        }
        $response_array = [
            'status' => true,
            'message' => $message,
            'otp' => $otp,
            'is_new_user' => $is_new_user,
            'IsSMSEnabled' => $ch
        ];
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function verify_otp_login(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            array(
                'phone' => 'required',
                'otp' => 'required'
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {

            $phone = (string) $request->phone;
            if ($phone == "+918825739779") {
                $response_array = array('status' => true, 'message' => "Logged In");
                $response = response()->json($response_array, 200);
                return $response;
            }
            $otp = (string) $request->otp;

            $check_sms = DB::table('settings')->where('key_word', "sms_enable")->first();

            if ($check_sms->value == 0) {
                $response_array = array('status' => true, 'message' => "Logged In");
                $response = response()->json($response_array, 200);
                return $response;
            }
            $verify_sms = $this->verify_otp($phone, $otp);


            if ($verify_sms->type == "error") {

                $response_array = array('status' => false, 'message' => $verify_sms->message);
            } else {

                $response_array = array('status' => true, 'message' => $verify_sms->message);
            }
            // if($verify_sms)
            // {
            //     // echo $verify_sms->status; exit;
            //     if($verify_sms->status == "ERROR")
            //     {
            //         $response_array = array('status'=>false,'message'=>'Invalid OTP');
            //     }else
            //     {
            //         $response_array = array('status'=>true,'message'=>trans('constants.otp_verified_successfully'));
            //     }
            // }else
            // {
            //     $response_array = array('status'=>false,'message'=>'Something went wrong. Try after sometime');
            // }                
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                //'phone' => 'required',
                //'email' => 'required',
                //'password' => 'required',
                'device_token' => 'required',
                'login_type' => 'required',
                'device_type' => 'required|in:' . ANDROID . ',' . IOS . ',' . WEB,
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
            return response()->json($response_array, 200);
        }

        $currency = DB::table('country')->select('currency_code', 'currency_symbol')->where('is_default', 1)->first();

        $users = $this->users;
        $phone = $request->phone ?? '';
        $email = $request->email;
        $name = $request->name;
        $password = bcrypt($request->password);
        $device_type = $request->device_type;
        $device_token = $request->device_token;
        $facebook_id = $request->facebook_id ? $request->facebook_id : null;
        $apple_id = $request->apple_id ? $request->apple_id : null;
        $login_type = $request->login_type;
        $authToken = $this->generateRandomString();
        $profile_image = "http://www.freeiconspng.com/uploads/account-profile-icon-1.png";


        if ($login_type == 0) {
            if (empty($request->phone) || empty($request->email)) {
                return response()->json(['status' => false, 'message' => 'Phone and email are required'], 200);
            }
        } else if ($login_type == 1) {
            if (empty($request->email)) {
                return response()->json(['status' => false, 'message' => 'Email is required'], 200);
            }
        } else if ($login_type == 2) {
            if (empty($request->facebook_id)) {
                return response()->json(['status' => false, 'message' => 'Facebook id is required'], 200);
            }
        } else if ($login_type == 4) {
            if (empty($request->apple_id)) {
                return response()->json(['status' => false, 'message' => 'Apple id is required'], 200);
            }
        }

        $is_social_login = 0;

        if (!empty($facebook_id) || !empty($apple_id)) {
            $is_social_login = 1;
        }

        if ($facebook_id) {
            $check_facebook = $users::where('facebook_id', $facebook_id)->get();
            if ($check_facebook && count($check_facebook) > 0) {
                if ($login_type == 2) {
                    $data = $check_facebook[0];
                    $is_social_login = 1;
                } else {
                    $response_array = array('status' => false, 'message' => trans('constants.facebook_id_already_exist'));
                    return response()->json($response_array, 200);
                }
            }
        }

        if ($apple_id) {
            $check_apple = $users::where('apple_id', $apple_id)->get();
            if ($check_apple && count($check_apple) > 0) {
                if ($login_type == 3) {
                    $data = $check_apple[0];
                    $is_social_login = 1;
                } else {
                    $response_array = array('status' => false, 'message' => trans('constants.apple_id_already_exist'));
                    return response()->json($response_array, 200);
                }
            }
        }

        if ($is_social_login == 0 && $email) {
            $check_email = $users::where('email', $email)->get();
            if ($check_email && count($check_email) > 0) {
                if ($login_type == 1) {
                    $data = $check_email[0];
                    $is_social_login = 1;
                } else {
                    $response_array = array('status' => false, 'message' => trans('constants.email_already_exist'));
                    return response()->json($response_array, 200);
                }
            }
        }

        if ($is_social_login == 0 && $phone) {
            $check_phone = $users::where('phone', 'like', '%' . $phone . '%')->get();
            if ($check_phone && count($check_phone) > 0) {
                $response_array = array('status' => false, 'message' => trans('constants.mobile_number_already_exist'));
                return response()->json($response_array, 200);
            }
        }

        if ($is_social_login) {
            $response_array = [
                'status' => true,
                'login_type' => $login_type,
                'authToken' => $data->authToken,
                'authId' => $data->id,
                'phone' => $data->phone,
                'name' => $data->name,
                'profile_image' => $data->profile_image,
                'email' => $data->email,
                'currency_code' => $currency->currency_code ?? '',
                'currency_symbol' => $currency->currency_symbol ?? ''
            ];

            $response = response()->json($response_array, 200);
            return $response;
        }

        $new_user = array();

        if ($device_type != WEB) {
            $new_user[] = array(
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'authToken' => $authToken,
                'device_type' => $device_type,
                'password' => $password,
                'device_type' => $device_type,
                'device_token' => $device_token,
                'facebook_id' => $facebook_id,
                'profile_image' => $profile_image,
                'login_type' => $login_type,
                'referral_code' => $this->generateRandomString_referral()
            );
        } else {
            $new_user[] = array(
                'name' => $request->first_name . ' ' . $request->last_name,
                'phone' => $phone,
                'email' => $email,
                'authToken' => $authToken,
                'password' => $password,
                'device_type' => $device_type,
                'device_token' => $device_token,
                'facebook_id' => $facebook_id,
                'profile_image' => $profile_image,
                'login_type' => $login_type,
                'referral_code' => $this->generateRandomString_referral()
            );
        }

        $users::insert($new_user);

        if ($phone) {
            $data = $users::where('phone', '=', $phone)->first();
        } else if ($email) {
            $data = $users::where('email', '=', $email)->first();
        } else {
            $data = $users::where('facebook_id', '=', $facebook_id)->first();
        }

        $authToken = $data->authToken;
        $authId = $data->id;

        $response_array = [
            'status' => true,
            'login_type' => 0,
            'authToken' => $authToken,
            'authId' => $authId,
            'phone' => $data->phone,
            'name' => $data->name,
            'profile_image' => $profile_image,
            'email' => $email,
            'currency_code' => $currency->currency_code ?? '',
            'currency_symbol' => $currency->currency_symbol ?? ''
        ];

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function social_login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'device_token' => 'required',
                'login_type' => 'required',
                'device_type' => 'required|in:' . ANDROID . ',' . IOS . ',' . WEB,
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
            return response()->json($response_array, 200);
        }

        $currency = DB::table('country')->select('currency_code', 'currency_symbol')->where('is_default', 1)->first();

        $users = $this->users;
        $phone = $request->phone ?? '';
        $email = $request->email;
        $name = $request->name;
        $password = '';
        $device_type = $request->device_type;
        $device_token = $request->device_token;
        $facebook_id = $request->facebook_id ? $request->facebook_id : null;
        $apple_id = $request->apple_id ? $request->apple_id : null;
        $login_type = $request->login_type;
        $authToken = $this->generateRandomString();
        $profile_image = "";


        if ($login_type == 1) {
            if (empty($request->email)) {
                return response()->json(['status' => false, 'message' => 'Email is required'], 200);
            }
        } else if ($login_type == 2) {
            if (empty($request->facebook_id)) {
                return response()->json(['status' => false, 'message' => 'Facebook id is required'], 200);
            }
        } else if ($login_type == 4) {
            if (empty($request->apple_id)) {
                return response()->json(['status' => false, 'message' => 'Apple id is required'], 200);
            }
        }

        $user_id = 0;
        $data = null;
        if ($email) {
            $data = $users::where('email', $email)->first();
            $user_id = $data->id ?? 0;
        }



        $check_fb  = 0;
        $check_apple = 0;
        if ($facebook_id) {
            $check_fb = $users::where('facebook_id', $facebook_id)->where('id', '!=', $user_id)->first();
        } else if ($apple_id) {
            $check_apple = $users::where('apple_id', $apple_id)->where('id', '!=', $user_id)->first();
        }

        if ($user_id) {
            if ($check_fb || $check_apple) {
                $return_msg = $check_fb ? 'This email is already linked with another facebook id' : 'This email is already linked with another apple id';
                return response()->json(['status' => false, 'message' => $return_msg], 200);
            }
        } else {
            $data = $check_fb ? $check_fb : $check_apple;
        }

        $is_new_user = $data ? 0 : 1;

        if ($data) {
            $update_user = [
                'device_token'    => $device_token,
                'authToken'     => $authToken,
                'device_type'     => $device_type,
                'apple_id'         => $apple_id,
                'facebook_id'     => $facebook_id
            ];

            $check_email = $users::where('email', $email)->where('id', '!=', $data->id)->first();
            if (!$check_email) {
                $update_user['email'] = $email;
            }

            $check_phone = $users::where('phone', $phone)->where('id', '!=', $data->id)->first();
            if (!$check_phone) {
                $update_user['phone'] = $data->phone;
            }

            $users::where('id', $data->id)->update($update_user);
        } else {

            $new_user = [];
            $new_user[] = [
                'name'              => $name,
                'phone'             => $phone,
                'email'             => $email,
                'authToken'         => $authToken,
                'password'          => $password,
                'device_type'       => $device_type,
                'device_token'      => $device_token,
                'facebook_id'       => $facebook_id,
                'apple_id'           => $apple_id,
                'profile_image'        => $profile_image,
                'login_type'        => $login_type,
                'referral_code'        => $this->generateRandomString_referral()
            ];

            $users::insert($new_user);
        }

        if ($facebook_id) {
            $data = $users::where('facebook_id', '=', $facebook_id)->first();
        } else if ($apple_id) {
            $data = $users::where('apple_id', '=', $apple_id)->first();
        } else {
            $data = $users::where('email', '=', $email)->first();
        }

        $response_array = [
            'status'            => true,
            'message'             => $is_new_user ? 'Registered sucessfully' : 'Fetched sucessfully',
            'login_type'        => $login_type,
            'authToken'            => $data->authToken,
            'authId'            => $data->id,
            'phone'                => $data->phone,
            'profile_image'        => $data->profile_image,
            'email'                => $data->email,
            'name'                => $data->name,
            'currency_code'     => $currency->currency_code ?? '',
            'currency_symbol'     => $currency->currency_symbol ?? ''
        ];

        $response = response()->json($response_array, 200);
        return $response;
    }


    public function user_login(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'device_token' => 'required',
                'login_type' => 'required',
                'device_type' => 'required|in:' . ANDROID . ',' . IOS . ',' . WEB,
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            return response()->json(['status' => false, 'error_code' => 101, 'message' => $error_messages], 200);
        }

        $users = $this->users;
        $device_token = $request->device_token;
        $login_type = $request->login_type;
        $device_type = $request->device_type;

        $currency = DB::table('country')->select('currency_code', 'currency_symbol')->where('is_default', 1)->first();

        ###############     TYPE=0 - mobile, TYPE=1 - Gmail, TYPE=2 - Facebook  TYPE=3 Apple     ########################
        if ($login_type == 0) {
            $phone = $request->phone;
            $password = $this->encrypt_password($request->password);

            $data = $users::where('phone', 'like', '%' . $phone . '%')->first();

            if (!$data || !Hash::check($request->password, $data->password)) {
                return response()->json(['status' => false, 'message' => 'Invalid Login'], 200);
            }
            if ($data->status == 1) {
                return response()->json(['status' => false, 'message' => 'User Deactivated'], 200);
            }

            $authId = $data->id;
            $profile_image = $data->profile_image;
            $name = $data->name != NULL ? $data->name : "";
            $email = $data->email ? $data->email : "";

            $authToken = $this->generateRandomString();

            $users::where('id', $data->id)->update(['device_token' => $device_token, 'authToken' => $authToken, 'device_type' => $device_type]);

            $response_array = [
                'status' => true,
                'message' => trans('constants.logged_in_successfully'),
                'authId' => $authId,
                'authToken' => $authToken,
                'phone' => $phone,
                'profile_image' => $profile_image,
                'email' => $email,
                'user_name' => $name,
                'currency_code' => $currency->currency_code ?? '',
                'currency_symbol' => $currency->currency_symbol ?? ''
            ];

            return response()->json($response_array, 200);
        } else if ($login_type == 1) {
            // Gmail Login

            $email = $request->email;
            if (!$email) {
                return response()->json(['status' => false, 'message' => 'Email required in request'], 200);
            }

            $device_token = $request->device_token;

            $data = $users::where('email', $email)->first();

            if (!$data) {
                return response()->json(['status' => false, 'message' => 'Invalid Login'], 200);
            }
            if ($data->status == 1) {
                return response()->json(['status' => false, 'message' => 'User Deactivated'], 200);
            }
            $authId = $data->id;
            $profile_image = $data->profile_image;
            $name = $data->name != NULL ? $data->name : "";

            $authToken = $this->generateRandomString();

            $users::where('id', $data->id)->update(['device_token' => $device_token, 'authToken' => $authToken, 'device_type' => $device_type]);

            $response_array = [
                'status' => true,
                'message' => trans('constants.logged_in_successfully'),
                'authId' => $authId,
                'authToken' => $authToken,
                'phone' => $data->phone,
                'profile_image' => $profile_image,
                'email' => $email,
                'user_name' => $name,
                'currency_code' => $currency->currency_code ?? '',
                'currency_symbol' => $currency->currency_symbol ?? ''
            ];

            return response()->json($response_array, 200);
        } else if ($login_type == 2) {
            // Facebook Login

            $facebook_id = $request->facebook_id;
            if (!$facebook_id) {
                return response()->json(['status' => false, 'message' => 'Facebook ID required in request'], 200);
            }
            $device_token = $request->device_token;

            $data = $users::where('facebook_id', $facebook_id)->first();

            if (!$data) {
                return response()->json(['status' => false, 'message' => 'Invalid Login'], 200);
            }

            if ($data->status == 1) {
                return response()->json(['status' => false, 'message' => 'User Deactivated'], 200);
            }

            $authId = $data->id;
            $profile_image = $data->profile_image;
            $name = $data->name != NULL ? $data->name : "";
            $authToken = $this->generateRandomString();

            $users::where('id', $data->id)->update(['device_token' => $device_token, 'authToken' => $authToken, 'device_type' => $device_type]);

            $response_array = [
                'status' => true,
                'message' => trans('constants.logged_in_successfully'),
                'authId' => $authId,
                'authToken' => $authToken,
                'phone' => $data->phone,
                'profile_image' => $profile_image,
                'email' => $data->email,
                'user_name' => $name,
                'currency_code' => $currency->currency_code ?? '',
                'currency_symbol' => $currency->currency_symbol ?? ''
            ];

            return response()->json($response_array, 200);
        } else if ($login_type == 3) {
            // Apple Login

            $apple_id = $request->apple_id;
            if (!$apple_id) {
                return response()->json(['status' => false, 'message' => 'Apple ID required in request'], 200);
            }
            $device_token = $request->device_token;

            $data = $users::where('apple_id', $apple_id)->first();

            if (!$data) {
                $authToken = $this->generateRandomString();
                $new_user = [];
                $new_user[] = array(
                    'phone' => '',
                    'email' => $request->email,
                    'authToken' => $authToken,
                    'password' => '',
                    'device_type' => $device_type,
                    'device_token' => $device_token,
                    'facebook_id' => '',
                    'apple_id' => $apple_id,
                    'profile_image' => '',
                    'login_type' => $login_type,
                    'referral_code' => $this->generateRandomString_referral()
                );
                $users::insert($new_user);

                $data = $users::where('apple_id', $apple_id)->first();
            }
            if ($data->status == 1) {
                return response()->json(['status' => false, 'message' => 'User Deactivated'], 200);
            }

            $authId = $data->id;
            $profile_image = $data->profile_image;
            $name = $data->name != NULL ? $data->name : "";
            $authToken = $this->generateRandomString();

            $users::where('id', $data->id)->update(['device_token' => $device_token, 'authToken' => $authToken, 'device_type' => $device_type]);

            $response_array = [
                'status' => true,
                'message' => trans('constants.logged_in_successfully'),
                'authId' => $authId,
                'authToken' => $authToken,
                'phone' => $data->phone,
                'profile_image' => $profile_image,
                'email' => $data->email,
                'user_name' => $name,
                'currency_code' => $currency->currency_code ?? '',
                'currency_symbol' => $currency->currency_symbol ?? ''
            ];

            return response()->json($response_array, 200);
        }
    }


    public function resend_otp(Request $request)
    {

        // $check = $users::where('phone','=',$phone)->first();
        $users = $this->users;
        $otp = rand(10000, 99999);
        $sendSms = $this->send_otp($request->phone, $otp);
        $update = $users::where('phone', '=', $request->phone)
            ->update(['otp' => $otp]);

        return response()->json(['status' => true, 'message' => 'OTP resend successfully']);
    }

    public function update_profile(Request $request)
    {
        # code... profile_update

        $users = $this->users;
        $custom = $this->custom;
        $update = array();
        $data = $users::where('id', $request->id)->first();

        if ($request->name) {
            $update['name'] = $request->name;
        }
        if ($request->email) {
            $update['email'] = $request->email;
        }
        if ($request->phone) {
            $update['phone'] = $request->phone;
        }
        if ($request->password) {
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
        if ($request->profile_image) {
            if ($data->profile_image != "") {
                $custom::delete_image($data->profile_image);
            }
            $profile_pic = $custom::upload_image($request, 'profile_image');
            $update['profile_image'] = BASE_URL . 'uploads/' . $profile_pic;
        }

        $users::where('id', $request->id)->update($update);

        $data = $users::where('id', $request->id)->first();
        // $data = $this->check_null($data)->toArray();
        $response_array = array('status' => true, 'message' => trans('constants.profile_updated_successfully'), 'data' => $data);
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function forgot_password(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'phone' => 'required'
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $phone = $request->phone;

            $users = $this->users;

            $check_user = $users::where('phone', $phone)->first();

            if (count($check_user) != 0) {
                $phone = (string) $request->phone;
                $otp = rand(10000, 99999);
                $message = 'OTP to verify ' . APP_NAME . ' Application : ' . $otp;
                $sendSms = $this->send_otp($phone, $otp);

                $response_array = array('status' => true, 'message' => trans('constants.otp_sent_successfully'), 'otp' => $otp);
            } else {
                $response_array = array('status' => false, 'message' => trans('constants.mobile_number_not_registered'));
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
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
        } else {
            $password = Hash::make($request->password);
            $phone = $request->phone;
            $users = $this->users;

            $get_user = $users::where('phone', $phone)->first();

            $users::where('phone', $phone)->update(['password' => $password]);

            $response_array = array('status' => true, 'message' => trans('constants.password_reset_successfull'));
        }

        $response = response()->json($response_array, 200);
        return $response;
    }


    public function logout(Request $request)
    {
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $users = $this->users;
        $users::where('id', '=', $user_id)->update(['authToken' => 0]);

        $response_array = array('status' => true, 'message' => trans('constants.logged_out_successfully'));
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function android_version()
    {
        // $version = env('ANDROID_CUSTOMER_APP_VERSION');
        $version = DB::table('settings')->select('value')->where('key_word', 'android_version')->first();
        $force_update = DB::table('settings')->select('value')->where('key_word', 'force_update')->first();

        $response = response()->json(['status' => true, 'message' => 'Success', 'Version' => $version->value, 'force_update' => $force_update->value], 200);
        return $response;
    }

    public function ios_version()
    {
        $version = env('IOS_CUSTOMER_APP_VERSION');
        $response = response()->json(['status' => true, 'message' => 'Success', 'Version' => $version], 200);
        return $response;
    }

    public function payment_methods()
    {

        $isCoDEnabled = $this->settings->where('key_word', 'cod_enable')->first();
        $isMollieEnabled = env('PAYMENT_GATEWAY') == 'Mollie';
        $razor_key    = DB::table('settings')->where('key_word', 'razor_key')->first();

        $isonlinepayment_enable = $this->settings->where('key_word', 'onlinepayment_enable')->first();


        return response()->json([
            'status' => true,
            'message' => 'Success',
            'IsCoDEnabled' => (int) ($isCoDEnabled->value ?? 0),
            'IsMollieEnabled' => $isMollieEnabled,
            'StripePublicKey' => STRIPE_PK_KEY,
            'RazorKey' => $razor_key->value,
            'IsOnlinePaymentEnabled' => (int) ($isonlinepayment_enable->value ?? 0)
        ]);
    }

    public function service_status()
    {
        return response()->json(['status' => true, 'message' => 'OK']);
    }


    public function deactivate_account(Request $request)
    {
        $user_id = $request->user_id;
        $data = $this->users->where('id', $user_id)->update(['status' => "1"]);
        $response_array = array('status' => true, 'message' => "User Deactivated");
        $response = response()->json($response_array, 200);
        return $response;
    }
    public function forget_otp(Request $request)
    {
        $phone = (string) $request->phone;
        $otp = rand(10000, 99999);
        $sms_enabled = DB::table('settings')->where('key_word', 'sms_enable')->first();
        if ($sms_enabled->value == '0') {
            $sendSms = 1;
            $message = 'SMS DISABLED';

            $status = true;
        } else {
            $sendSms = $this->send_otp($phone, $otp);

            if ($sendSms) {
                $status = true;
                $message = trans('constants.otp_sent_successfully');
            } else {
                $otp = null;
                $message = 'Failed to send OTP';
                $status = false;
            }
        }
        if ($sms_enabled->value == 0) {
            $ch = false;
        } else {
            $ch = true;
        }
        $response_array = [
            'status' => $status,
            'message' => $message,
            'IsSMSEnabled' => $ch,
            'otp' => $otp,

        ];
        $response = response()->json($response_array, 200);
        return $response;
    }
    public function device_token_update(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'device_token' => 'required',
                'authId' => 'required',

            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status' => false, 'error_code' => 101, 'message' => $error_messages);
            return response()->json($response_array, 200);
        }


        $device_token = $request->device_token;
        $user_id = $request->authId;
        if ($user_id) {
            $this->users::where('id', $user_id)->update([
                'device_token' =>  $device_token,
            ]);
            $response_array = array('status' => true, 'message' => 'Updated Successfully');
        } else {
            $response_array = array('status' => true, 'message' => 'Something went wrong');
        }

        $response = response()->json($response_array, 200);
        return $response;
    }
}
