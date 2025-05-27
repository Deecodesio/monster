<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\api\BaseController;
use Illuminate\Http\Request;
use Validator;

use Hash;
use DB;
use App\Model\Add_admin;
use App\Model\Roles;
use App\Model\Privileges;

class SettingController extends BaseController
{
    /**
     * Get the settings data based on type.
     *
     * @return value to blade file
     */
    public function Getsettings($type, Request $request)
    {

        // $id = $request->session()->get('userid');
        // if (env('IS_DELIWARE') && $id != 1) {

        //         return redirect('/admin');

        // }

        $data = $this->settings->pluck('value', 'key_word')->toArray();

        if ($type == 'site') {

            if (file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH . $data["site_logo"])) {
                $data["site_logo"] =  BASE_URL . RESTAURANT_UPLOADS_PATH . $data["site_logo"];
            } else {
                $data["site_logo"] =  BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            }

            if (file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH . $data["site_favicon"])) {
                $data["site_favicon"] =  BASE_URL . RESTAURANT_UPLOADS_PATH . $data["site_favicon"];
            } else {
                $data["site_favicon"] =  BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            }
            if (file_exists(public_path('/') . $data["qrcode"])) {
                $data["qrcode"] =  BASE_URL  . $data["qrcode"];
            } else {
                $data["qrcode"] =  BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            }




            $message = $data;
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        } elseif ($type == 'email') {

            $message = $data;
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        } else {

            $message = $data;
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }
    }

    /**
     * Post the request data in setting table.
     *
     * @param Request $request
     *
     * @return value to blade file
     */
    public function Updatesetting(Request $request)
    {
        $type = $request->type;
        $settings = $this->settings;
        //  dd($request);
        // if ($type == 'site') {

        //     $check = $this->settings->where('type', 1)->get();
        // } elseif ($type == 'email') {

        //     $check = $this->settings->where('type', 2)->get();
        // } else {

        //     $check = $this->settings->where('type', 3)->get();
        // }

        $rules = array();

        // foreach ($check as $key => $value) {

        //     $validate_field = '$request->' . $value->key_word;

        //     $not_required_keys = [
        //         'site_favicon', 'site_logo', 'ios_app_link', 'android_app_link'
        //     ];

        //     if (!in_array($value->key_word, $not_required_keys)) {
        //         $rules[$value->key_word] = 'required';
        //     }

        //     // if ($value->key_word == 'site_favicon') {
        //     //     $rules[$value->key_word] = 'mimes:jpeg,ico,jpg,png,gif|max:20||dimensions:max_width=48,max_height=48';
        //     // }

        //     // if ($value->key_word == 'site_logo') {
        //     //     $rules[$value->key_word] = 'mimes:jpeg,ico,jpg,png,gif|max:1024||dimensions:max_width=1200,max_height=600';
        //     // }
        // }

        //dd($rules);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());

            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }
        //   dd($type);
        if ($type == 'site') {


            if ($request->script != "") {
                $settings->where('key_word', 'script')
                    ->update(['value' => trim($request->script)]);
            }
            if ($request->analytics_script != "") {
                $settings->where('key_word', 'analytics_script')
                    ->update(['value' => trim($request->analytics_script)]);
            }
            if ($request->sharing_script != "") {
                $settings->where('key_word', 'sharing_script')
                    ->update(['value' => trim($request->sharing_script)]);
            }
            if ($request->meta_title != "") {
                $settings->where('key_word', 'meta_title')
                    ->update(['value' => trim($request->meta_title)]);
            }

            if ($request->meta_description != "") {
                $settings->where('key_word', 'meta_description')
                    ->update(['value' => trim($request->meta_description)]);
            }
            if ($request->google_site_verification != "") {
                $settings->where('key_word', 'google_site_verification')
                    ->update(['value' => trim($request->google_site_verification)]);
            }


            $settings->where('key_word', 'ios_app_link')
                ->update(['value' => trim($request->ios_app_link)]);

            $settings->where('key_word', 'android_app_link')
                ->update(['value' => trim($request->android_app_link)]);


            if ($request->admin_commission != "") {
                $settings->where('key_word', 'admin_commission')
                    ->update(['value' => $request->admin_commission]);
            }

            if ($request->restaurant_commission != "") {
                $settings->where('key_word', 'restaurant_commission')
                    ->update(['value' => $request->restaurant_commission]);
            }


            if ($request->android_version != "") {
                $settings->where('key_word', 'android_version')
                    ->update(['value' => $request->android_version]);
            }

            if ($request->force_update != "") {
                $settings->where('key_word', 'force_update')
                    ->update(['value' => $request->force_update]);
            }

            // if ($request->delivery_boy_commission != "") {
            //     $settings->where('key_word', 'delivery_boy_commission')
            //         ->update(['value' => $request->delivery_boy_commission]);
            // }

            if ($request->stripe_sk_key != "") {
                $settings->where('key_word', 'stripe_sk_key')
                    ->update(['value' => $request->stripe_sk_key]);
            }

            if ($request->app_name != "") {
                $settings->where('key_word', 'app_name')
                    ->update(['value' => $request->app_name]);
            }


            if ($request->partner_notification_key != "") {
                $settings->where('key_word', 'partner_notification_key')
                    ->update(['value' => $request->partner_notification_key]);
            }

            if ($request->default_radius != "") {
                $settings->where('key_word', 'default_radius')
                    ->update(['value' => $request->default_radius]);
            }

            if ($request->highlight_color != "") {
                $settings->where('key_word', 'highlight_color')
                    ->update(['value' => $request->highlight_color]);
            }

            if ($request->menu_color != "") {
                $settings->where('key_word', 'menu_color')
                    ->update(['value' => $request->menu_color]);
            }

            if ($request->site_contact != "") {
                $settings->where('key_word', 'site_contact')
                    ->update(['value' => $request->site_contact]);
            }

            if ($request->site_email != "") {
                $settings->where('key_word', 'site_email')
                    ->update(['value' => $request->site_email]);
            }

            if ($request->site_favicon != "") {
                if ($request->file('site_favicon')) {
                    $site_favicon = $this->custom->restaurant_upload_image($request, 'site_favicon');

                    $settings->where('key_word', 'site_favicon')
                        ->update(['value' => $site_favicon]);
                }
            }

            if ($request->site_logo != "") {

                if ($request->file('site_logo')) {
                    $site_logo = $this->custom->restaurant_upload_image($request, 'site_logo');

                    $settings->where('key_word', 'site_logo')
                        ->update(['value' => $site_logo]);
                }
            }

            if ($request->qrcode != "") {

                if ($request->file('qrcode')) {
                    $path = public_path('/');
                    $qrcode = $this->custom->common_upload_images($request, 'qrcode', $path);

                    $settings->where('key_word', 'qrcode')
                        ->update(['value' => $qrcode]);
                }
            }

            if ($request->default_unit != "") {
                $settings->where('key_word', 'default_unit')
                    ->update(['value' => $request->default_unit]);
            }
            if ($request->tax_inclusive != "") {
                $settings->where('key_word', 'tax_inclusive')
                    ->update(['value' => $request->tax_inclusive]);
            }
            if ($request->tips_enable != "") {
                $settings->where('key_word', 'tips_enable')
                    ->update(['value' => $request->tips_enable]);
            }
            if ($request->tips_text != "") {
                $tips_text = mb_convert_encoding($request->tips_text, 'HTML-ENTITIES', 'UTF-8');
                $settings->where('key_word', 'tips_text')
                    ->update(['value' => $tips_text]);
            }
            if ($request->email_enable != "") {
                $settings->where('key_word', 'email_enable')
                    ->update(['value' => $request->email_enable]);
            }

            if ($request->sms_enable != "") {
                $settings->where('key_word', 'sms_enable')
                    ->update(['value' => $request->sms_enable]);
            }

            if ($request->cod_enable != "") {
                $settings->where('key_word', 'cod_enable')
                    ->update(['value' => $request->cod_enable]);
            }
            if ($request->tax != "") {
                $settings->where('key_word', 'tax')
                    ->update(['value' => $request->tax]);
            }
            if ($request->schedule != "") {
                $settings->where('key_word', 'schedule')
                    ->update(['value' => $request->schedule]);
            }
            if ($request->restaurant_distance != "") {
                $settings->where('key_word', 'restaurant_distance')
                    ->update(['value' => $request->restaurant_distance]);
            }

            if ($request->time_zone != "") {
                $settings->where('key_word', 'time_zone')
                    ->update(['value' => $request->time_zone]);
            }

            if ($request->lang_enable != "") {
                $settings->where('key_word', 'lang_enable')
                    ->update(['value' => $request->lang_enable]);
            }
            // if ($request->IsGpayEnabled != "") {
            //     $settings->where('key_word', 'IsGpayEnabled')
            //         ->update(['value' => $request->IsGpayEnabled]);
            // }

            if ($request->secondary_language != "") {
                $settings->where('key_word', 'secondary_language')
                    ->update(['value' => $request->secondary_language]);
            }
            if ($request->primary_language != "") {
                $settings->where('key_word', 'primary_language')
                    ->update(['value' => $request->primary_language]);
            }

            if ($request->currency != "") {
                $settings->where('key_word', 'currency')
                    ->update(['value' => $request->currency]);
            }

            if ($request->order_prefix != "") {
                $settings->where('key_word', 'order_prefix')
                    ->update(['value' => $request->order_prefix]);
            }
            if ($request->facebook != "") {
                $settings->where('key_word', 'facebook')
                    ->update(['value' => $request->facebook]);
            }
            if ($request->instagram != "") {
                $settings->where('key_word', 'instagram')
                    ->update(['value' => $request->instagram]);
            }

            if ($request->youtube != "") {

                $settings->where('key_word', 'youtube')
                    ->update(['value' => $request->youtube]);
            }

            if ($request->twitter != "") {
                $settings->where('key_word', 'twitter')
                    ->update(['value' => $request->twitter]);
            }

            if ($request->linkedin != "") {
                $settings->where('key_word', 'linkedin')
                    ->update(['value' => $request->linkedin]);
            }


            if ($request->tracking_page != "") {
                $settings->where('key_word', 'tracking_page')
                    ->update(['value' => $request->tracking_page]);
            }

            if ($request->disclaimer != "") {
                $settings->where('key_word', 'disclaimer')
                    ->update(['value' => $request->disclaimer]);
            }

            if ($request->disclaimer_enable != "") {
                $settings->where('key_word', 'disclaimer_enable')
                    ->update(['value' => $request->disclaimer_enable]);
            }

            if ($request->pending_payment_text != "") {
                $settings->where('key_word', 'pending_payment_text')
                    ->update(['value' => $request->pending_payment_text]);
            }

            if ($request->Longitude != "") {
                $settings->where('key_word', 'DEFAULT_LONGITUDE')
                    ->update(['value' => $request->Longitude]);
            }
            if ($request->Latitude != "") {
                $settings->where('key_word', 'DEFAULT_LATITUDE')
                    ->update(['value' => $request->Latitude]);
            }
            if ($request->onlinepayment_enable != '') {
                $settings->where('key_word', 'onlinepayment_enable')
                    ->update(['value' => $request->onlinepayment_enable]);
            }
        } elseif ($type == 'email') {

            if ($request->email_user_name != "") {
                $settings->where('key_word', 'email_user_name')
                    ->update(['value' => $request->email_user_name]);
            }

            if ($request->email_password != "") {
                $settings->where('key_word', 'email_password')
                    ->update(['value' => $request->email_password]);
            }
            if ($request->mail_driver != "") {
                $settings->where('key_word', 'mail_driver')
                    ->update(['value' => $request->mail_driver]);
            }

            if ($request->mail_debug != "") {
                $settings->where('key_word', 'mail_debug')
                    ->update(['value' => $request->mail_debug]);
            }


            if ($request->mail_host != "") {
                $settings->where('key_word', 'mail_host')
                    ->update(['value' => $request->mail_host]);
            }

            if ($request->mail_from != "") {
                $settings->where('key_word', 'mail_from')
                    ->update(['value' => $request->mail_from]);
            }
            if ($request->mail_from_name != "") {
                $settings->where('key_word', 'mail_from_name')
                    ->update(['value' => $request->mail_from_name]);
            }

            if ($request->mail_port != "") {
                $settings->where('key_word', 'mail_port')
                    ->update(['value' => $request->mail_port]);
            }
            if ($request->mail_username != "") {
                $settings->where('key_word', 'mail_username')
                    ->update(['value' => $request->mail_username]);
            }

            if ($request->mail_password != "") {
                $settings->where('key_word', 'mail_password')
                    ->update(['value' => $request->mail_password]);
            }

            if ($request->mail_encryption != "") {
                $settings->where('key_word', 'mail_encryption')
                    ->update(['value' => $request->mail_encryption]);
            }
        } elseif ($type == 'delivery') {
            if ($request->dunzo_clientid != "") {
                $settings->where('key_word', 'dunzo_clientid')
                    ->update(['value' => $request->dunzo_clientid]);
            }
            if ($request->dunzo_clientsecret != "") {
                $settings->where('key_word', 'dunzo_clientsecret')
                    ->update(['value' => $request->dunzo_clientsecret]);
            }
        } elseif ($type == 'sms') {

            $sms_provider = $request->provider;
            //  dd( $sms_provider );           //1-Msg91 , 2-Twillio
            if ($sms_provider == 1) {
                $settings->where('key_word', 'sms_provider')
                    ->update(['value' => 1]);
                if ($request->msg91_template_id != "") {
                    $settings->where('key_word', 'msg91_template_id')
                        ->update(['value' => $request->msg91_template_id]);
                }

                if ($request->msg91_auth_key != "") {
                    $settings->where('key_word', 'msg91_auth_key')
                        ->update(['value' => $request->msg91_auth_key]);
                }
            } else {
                $settings->where('key_word', 'sms_provider')
                    ->update(['value' => 2]);


                if ($request->t_sid != "") {
                    $settings->where('key_word', 't_sid')
                        ->update(['value' => $request->t_sid]);
                }

                if ($request->t_token != "") {
                    $settings->where('key_word', 't_token')
                        ->update(['value' => $request->t_token]);
                }
                if ($request->t_from != "") {
                    $settings->where('key_word', 't_from')
                        ->update(['value' => $request->t_from]);
                }
            }
        } elseif ($type == 'payment_gateway') {

            $stripe_status = $request->stripe_status;
            $razorpay = $request->razorpay;
            // dd($request->payment);          
            //1-Stripe , 2-Razorpay
            if ($stripe_status == "1") {
                $settings->where('key_word', 'payment_gateway')
                    ->update(['value' => 1]);
                if ($request->stripe_pk_key != "") {
                    $settings->where('key_word', 'stripe_pk_key')
                        ->update(['value' => $request->stripe_pk_key]);
                }

                if ($request->stripe_sk_key != "") {
                    $settings->where('key_word', 'stripe_sk_key')
                        ->update(['value' => $request->stripe_sk_key]);
                }
                if ($request->stripe_version != "") {
                    $settings->where('key_word', 'stripe_version')
                        ->update(['value' => $request->stripe_version]);
                }
                if ($request->stripe_status != "") {
                    $settings->where('key_word', 'stripe_status')
                        ->update(['value' => $request->stripe_status]);
                }
            } else {

                if ($request->stripe_status != "") {
                    $settings->where('key_word', 'stripe_status')
                        ->update(['value' => $request->stripe_status]);
                }
            }
            if ($razorpay == "1") {
                $settings->where('key_word', 'payment_gateway')
                    ->update(['value' => 2]);


                if ($request->razor_key != "") {
                    $settings->where('key_word', 'razor_key')
                        ->update(['value' => $request->razor_key]);
                }

                if ($request->razor_secret != "") {
                    $settings->where('key_word', 'razor_secret')
                        ->update(['value' => $request->razor_secret]);
                }
                if ($request->razorpay != "") {
                    $settings->where('key_word', 'razorpay')
                        ->update(['value' => $request->razorpay]);
                }
            } else {
                if ($request->razorpay != "") {
                    $settings->where('key_word', 'razorpay')
                        ->update(['value' => $request->razorpay]);
                }
            }
        } else {

            if ($request->google_api_key != "") {
                $settings->where('key_word', 'google_api_key')
                    ->update(['value' => $request->google_api_key]);
            }

            if ($request->firebase_url != "") {
                $settings->where('key_word', 'firebase_url')
                    ->update(['value' => $request->firebase_url]);
            }

            if ($request->user_notification_key != "") {
                $settings->where('key_word', 'user_notification_key')
                    ->update(['value' => $request->user_notification_key]);
            }

            if ($request->partner_notification_key != "") {
                $settings->where('key_word', 'partner_notification_key')
                    ->update(['value' => $request->partner_notification_key]);
            }

            if ($request->captcha_site_key != "") {
                $settings->where('key_word', 'captcha_site_key')
                    ->update(['value' => $request->captcha_site_key]);
            }

            if ($request->captcha_site_secret != "") {
                $settings->where('key_word', 'captcha_site_secret')
                    ->update(['value' => $request->captcha_site_secret]);
            }

            if ($request->stripe_version != "") {
                $settings->where('key_word', 'stripe_version')
                    ->update(['value' => $request->stripe_version]);
            }

            if ($request->google_client_id != "") {
                $settings->where('key_word', 'google_client_id')
                    ->update(['value' => $request->google_client_id]);
            }
            if ($request->google_client_secret != "") {
                $settings->where('key_word', 'google_client_secret')
                    ->update(['value' => $request->google_client_secret]);
            }
            if ($request->google_redirect_url != "") {
                $settings->where('key_word', 'google_redirect_url')
                    ->update(['value' => $request->google_redirect_url]);
            }
        }

        $message = "Updated Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
        // return back()->with('success', trans('constants.update_success_msg', ['param' => 'Setting']));
    }
    /**
     * Get the blade file.
     *
     * @return value to blade file
     */
    public function Getaddemail()
    {
        return view('admin/add_email');
    }

    /**
     * Get the email template list.
     *
     * @return value to blade file
     */
    public function Getemailtemplate()
    {
        return view('admin/email-template-list');
    }


    public function get_role()
    {
        $roles = $this->role->get();
        return view('admin/add_role', compact('roles'));
    }

    public function get_roles()
    {
        $roles = $this->role->get();
        foreach ($roles as $r) {
            $r->role_name = $this->secondLanguage($r->role_name, $r->second_role_name);
        }
        return response()->json($roles);
    }

    public function role_add(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'role_name' => 'required',
            'privilege' => 'required',
        ]);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $message =  $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        $privileges =  preg_split("/[,]/", $request->privilege);
        $role_name = $this->secound_lang_name($request->role_name);
        $second_role_name = $this->secound_lang_name($request->second_role_name);

        $role = new Roles();

        $role->role_name = $role_name;
        $role->second_role_name =  $second_role_name;
        $role->type = $request->role_type;
        $role->save();
        $role_id = $role->id;
        $value = $privileges;
        for ($i = 0; $i < count($value); $i++) {
            $Privilege = new Privileges();
            $Privilege->role_id = $role_id;
            $Privilege->name = $value[$i];
            $Privilege->is_allowed = 1;
            $Privilege->save();
        }
        $message = "Role Added Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function show_privilege($id)
    {
        $roles = $this->role->where('id', $id)->first();
        $privileges = $this->privileges->where('role_id', $id)->get();

        $response_Array = json_encode(['role' => $roles, 'privileges' => $privileges]);
        return $response_Array;
        // return view('admin/privilege', compact('roles', 'privileges'));
    }

    public function privilege_add(Request $request)
    {
        //dd(count($request->restaurant));
        $role_id = $request->role_id;
        $validator = Validator::make($request->all(), [
            'privilege' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());
            $message =  $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }
        $role_name = DB::table('roles')->where('id', $role_id)->update(['role_name' => $request->role_name]);

        $roles = $this->privileges->where('role_id', $role_id)->delete();
        $privileges =  preg_split("/[,]/", $request->privilege);
        $value = $privileges;
        for ($i = 0; $i < count($value); $i++) {
            $Privilege = new Privileges();
            $Privilege->role_id = $request->role_id;
            $Privilege->name = $value[$i];
            $Privilege->is_allowed = 1;
            $Privilege->save();
        }
        $message = "Privilege Added Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function new_role()
    {
        $roles = $this->role->get();
        $restaurants = $this->restaurants->orderBy('restaurant_name')->get();
        return view('admin/add_admin', compact('roles', 'restaurants'));
    }
    public function add_admin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'restaurant_name' => 'required',
            'admin_name' => 'required',
            'role_type' => 'required',
            'email' => 'required',
            'password' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        }


        $admin_name = $this->secound_lang_name($request->admin_name);
        $second_admin_name = $this->secound_lang_name($request->second_admin_name);

        if ($request->restaurant_id) {
            $rt = json_encode($request->restaurant_id);
        } else {
            $rt = '[]';
        }
        if (empty($request->res_id)) {

            $check = DB::table('admin_role')->where('email', $request->email)->first();
            if ($check) {
                $message = "Mail id Already exists";
                $status = false;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);


                return $response_Array;
            }
            $admin = new Add_admin();
            $admin->name = $request->admin_name;
            $admin->second_name = $second_admin_name ?? '';
            $admin->restaurant_id = $rt;
            $admin->email = $request->email;
            $admin->password =  Hash::make($request->password);
            $admin->org_password =  $request->password;
            $admin->role_id = $request->role_type;
            $admin->status = $request->status;
            $v = $admin->save();
        } else {
            $admin = $this->admin_role;
            $admin_role = $admin::findOrFail($request->res_id);
            $admin_role->name = $request->admin_name;
            $admin_role->second_name = $second_admin_name ?? '';
            $admin_role->restaurant_id = $rt;
            $admin_role->email = $request->email;
            $admin_role->password = Hash::make($request->password);
            $admin_role->org_password = $request->password;
            $admin_role->role_id = $request->role_type;
            $admin_role->status = $request->status;

            $admin_role->update();
        }
        //dd($v);
        $message = "Staff Added Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }
    public function show_admin()
    {
        $admin = $this->admin_role->join('roles', 'roles.id', '=', 'admin_role.role_id')
            ->leftjoin('restaurants', 'restaurants.id', '=', 'admin_role.restaurant_id')->select('admin_role.*', 'roles.*', 'restaurant_name', 'admin_role.id as admin_id')->get();

        // $admin1 = $this->admin_role->join('roles', 'roles.id', '=', 'admin_role.role_id')
        //     ->select('admin_role.*', 'roles.*')->where('restaurant_id', 0)->get();
        foreach ($admin as $a) {
            $a->name = $this->secondLanguage($a->name, $a->second_name);
            $a->restaurant_name = $this->secondLanguage($a->restaurant_name, $a->restaurant_secondary_name);
            $a->role_name = $this->secondLanguage($a->role_name, $a->second_role_name);
        }
        return response()->json($admin);
    }
    public function role_type(Request $request, $id)
    {
        $roles = $this->role->where('type', $id)->get();
        echo json_encode($roles);
    }
    public function get_roleprivilege(Request $request, $id)
    {
        $privileges = $this->privileges->where('role_id', $id)->get();
        echo json_encode($privileges);
        // return $privileges;
    }
    public function edit_admin(Request $request)
    {
        $data = $this->admin_role->where('id', $request->id)->first();

        if ($data->restaurant_id != "null") {
            $data->restaurant_id =  array_map('intval', json_decode($data->restaurant_id));
        } else {
            $data->restaurant_id = [];
        }


        if ($data->org_password) {
            $data->password = $data->org_password;
        }
        $message = $data;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $data]);
        return $response_Array;
    }

    public function get_roles_new(Request $request)
    {
        $roles = $this->role->get();
        foreach ($roles as $r) {
            $r->role_name = $this->secondLanguage($r->role_name, $r->second_role_name);
        }
        return response()->json($roles);
    }


    public function get_roles_byid(Request $request)
    {
        $roles = $this->role->where('type', $request->type)->get();
        return response()->json($roles);
    }
    public function get_role_id(Request $request)
    {
        $roles = $this->role->where('id', $request->id)->get();
        return response()->json($roles);
    }

    public function enablesmstoggle(Request $request)
    {
        // dd($request->status_sms);
        //1-SMS enabled , 2 - SMS disabled
        if ($request->status_sms == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $settings = $this->settings;
        $settings->where('key_word', 'sms_enable')
            ->update(['value' => $status]);


        $message = "SMS Updated";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
        // return back()->with('success', trans('constants.sms_updated'));
    }

    public function get_currency(Request $request)
    {
        $data = DB::table('country')->where('is_default', 1)->first();
        $data->value =  $data->currency_symbol;
        return response()->json($data);
    }
    public function get_geofence(Request $request)
    {
        $data = DB::table('settings')->where('key_word', 'default_radius')->first();
        return response()->json($data);
    }

    public function get_logo(Request $request)
    {
        $datas = DB::table('settings')->where('key_word', 'site_favicon')->first();

        if (file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH . $datas->value)) {
            $datas->value =  BASE_URL . RESTAURANT_UPLOADS_PATH . $datas->value;
        } else {
            $datas->value =  BASE_URL . RESTAURANT_UPLOADS_PATH . "logo.png";
        }
        $data1 = DB::table('settings')->where('key_word', 'app_name')->first();
        $logo = DB::table('settings')->where('key_word', 'site_logo')->first();

        $logo_img = BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;

        $data = [];

        $data[] = [
            'name' => $data1->value,
            'logo' =>   $datas->value,
            'logo_img' => $logo_img

        ];
        // if(file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH. $data->site_favicon)) {
        //     $data->site_favicon =  BASE_URL . RESTAURANT_UPLOADS_PATH .  $data->site_favicon;
        // } else {
        //     $data->site_favicon =  BASE_URL . RESTAURANT_UPLOADS_PATH ."logo.png";
        // }
        return response()->json($data);
    }


    public function faq_list(Request $request)
    {
        $data = DB::table('faq_title')->get();
        return response()->json($data);
    }

    public function add_faq(Request $request)
    {

        if ($request->id) {
            DB::table('faq_title')->where('id', $request->id)->update([
                'name' => $request->title,
                'icon' => $request->icon,
            ]);
            $msg = "FAQ Title Updated Successfully";
        } else {
            DB::table('faq_title')->insert([
                'name' => $request->title,
                'icon' => $request->icon,
            ]);
            $msg = "FAQ Title Add Successfully";
        }

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function edit_faq(Request $request, $id)
    {

        $data =   DB::table('faq_title')->where('id', $id)->first();

        return response()->json($data);
    }
    public function delete_faq($id)
    {

        $faq_title =  DB::table('faq_title')->where('id', $id)->delete();
        $faq =  DB::table('faq')->where('title', $id)->get();
        if (count($faq) > 0) {
            DB::table('faq')->where('title', $id)->delete();
        }


        $message = "FAQ Deleted Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function get_lang(Request $request)
    {
        $secondary = DB::table('settings')->where('key_word', 'secondary_language')->first();
        if ($secondary->value == "sw") {
            $secondary->value = "Swedish";
        } elseif ($secondary->value == "du") {
            $secondary->value = "Dutch";
        } elseif ($secondary->value == "fr") {
            $secondary->value = "French";
        } elseif ($secondary->value == "pt") {
            $secondary->value = "Portuguese";
        } elseif ($secondary->value == "ar") {
            $secondary->value = "Arabic";
        } elseif ($secondary->value == "es") {
            $secondary->value = "Spanish";
        } elseif ($secondary->value == "it") {
            $secondary->value = "Italian";
        } elseif ($secondary->value == "vie") {
            $secondary->value = "Vietnamese";
        } elseif ($secondary->value == "de") {
            $secondary->value = "German";
        } else {
            $secondary->value = "English";
        }
        $primary = DB::table('settings')->where('key_word', 'primary_language')->first();

        if ($primary->value == "sw") {
            $primary->value = "Swedish";
        } elseif ($primary->value == "du") {
            $primary->value = "Dutch";
        } elseif ($primary->value == "fr") {
            $primary->value = "French";
        } elseif ($primary->value == "pt") {
            $primary->value = "Portuguese";
        } elseif ($primary->value == "ar") {
            $primary->value = "Arabic";
        } elseif ($primary->value == "es") {
            $primary->value = "Spanish";
        } elseif ($primary->value == "it") {
            $primary->value = "Italian";
        } elseif ($primary->value == "vie") {
            $primary->value = "Vietnamese";
        } elseif ($primary->value == "de") {
            $primary->value = "German";
        } else {
            $primary->value = "English";
        }
        $lang = DB::table('settings')->where('key_word', 'lang_enable')->first();
        $response_Array = json_encode(['primary' => $primary, 'secondary' => $secondary, 'lang' => $lang]);
        return $response_Array;
    }

    public function get_mails(Request $request)
    {

        $neworder = DB::table('mail_ids')->where("mail_for", "newordermail")->first();
        $cancelledordermail = DB::table('mail_ids')->where("mail_for", "cancelledordermail")->first();
        $failedordermail = DB::table('mail_ids')->where("mail_for", "failedordermail")->first();
        $rest_signup = DB::table('mail_ids')->where("mail_for", "rest_signup")->first();
        $driver_signup = DB::table('mail_ids')->where("mail_for", "driver_signup")->first();
        $wallet_otp = DB::table('mail_ids')->where("mail_for", "wallet_otp")->first();






        $status = true;
        $response_Array = json_encode(['status' => $status, 'neworder' => json_decode($neworder->mail_ids), 'cancelledordermail' => json_decode($cancelledordermail->mail_ids), 'failedordermail' => json_decode($failedordermail->mail_ids), 'rest_signup' => json_decode($rest_signup->mail_ids), 'driver_signup' => json_decode($driver_signup->mail_ids), 'wallet_otp' => json_decode($wallet_otp->mail_ids)]);
        return $response_Array;
    }
    public function update_email(Request $request)
    {
        $type = $request->type;

        $mails = json_decode($request->mails);
        $check = DB::table('mail_ids')->where("mail_for", $type)->first();
        if ($check) {
            DB::table('mail_ids')->where("mail_for", $type)->update([
                'mail_ids' => $mails
            ]);
        } else {
            DB::table('mail_ids')->insert([
                'mail_for' => $type,

            ]);
            DB::table('mail_ids')->where("mail_for", $type)->update([
                'mail_ids' => $mails
            ]);
        }

        $message = "Mail Ids updated successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function get_contact_reasons(Request $request)
    {
        $contact = DB::table('contact_form_reasons')->get();

        foreach ($contact as $cont) {
            $cont->mailid = json_decode($cont->mailid);
        }

        $status = true;
        $response_Array = json_encode(['contact' => $contact, 'status' => $status]);
        return $response_Array;
    }

    public function update_contact(Request $request)
    {
        $mails = json_decode($request->mails);

        $reasons = json_decode($request->reasons);
        $trunc = DB::table('contact_form_reasons')->truncate();

        for ($i = 0; $i < count($reasons); $i++) {
            DB::table('contact_form_reasons')->insert([
                'reason' => $reasons[$i]->reason,
                'mailid' => $mails[$i]
            ]);
            // DB::table('contact_form_reasons')->where('reason',$reasons[$i])->update([
            //     'mailid'=>$mails[$i]
            // ]);

        }
        $message = "Contact Reasons Updated Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function edit_mails(Request $request)
    {
        $for = $request->page;
        $val = $request->content_value;
        $name = $request->content_name;

        $update = DB::table('mail_contents')->where('content_for', $for)->where('title', $name)->update([
            'content' => $val
        ]);

        $status = true;
        $response_Array = json_encode(['status' => $status, 'content' => $request->content_value]);
        return $response_Array;
    }

    public function get_mail_contents(Request $request)
    {
        $for = $request->page;
        $contents = DB::table('mail_contents')->where('content_for', $for)->get();
        $cont = [];
        $cont[0] = "";
        foreach ($contents as $co) {
            $cont[] = $co->content;
        }
        $status = true;
        $response_Array = json_encode(['status' => $status, 'contents' => $cont]);
        return $response_Array;
    }

    public function get_tips(Request $request)
    {

        $tips = DB::table('tips')->get();
        $s_tip = [];
        foreach ($tips as $tip) {
            $s_tip[] = [
                'title' => $tip->value
            ];
        }
        $selected_tips = DB::table('tips')->where('selected', 1)->get();
        $sel = [];
        foreach ($selected_tips as $st) {
            $sel[] = [
                'title' => $st->value
            ];
        }
        $status = true;

        $response_Array = json_encode(['status' => $status, 'tip' => $s_tip, 'selected' => $sel]);

        return $response_Array;
    }
    public function store_tips(Request $request)
    {

        $value = $request->name;
        $tips = DB::table('tips')->where('value', $value)->get();
        if (count($tips)) {
            $status = false;
            $message = "Tips Already Exists";
        } else {
            $insert = DB::table('tips')->insert([
                'value' => $value
            ]);
            $status = true;
            $message = "Tips Added Successfully";
        }


        $response_Array = json_encode(['status' => $status, 'message' => $message]);

        return $response_Array;
    }
    public function delete_role(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        } else {

            $id = $request->id;
            $role = $this->role;
            $admin_role = $this->admin_role;


            try {
                $res = $role->where('id', $id)->delete();
            } catch (\Exception $e) {
                $message = "Please remove the staff from role before deleting";
                $status = true;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }

            try {
                $admin_role->where('role_id', $id)->delete();
            } catch (\Exception $e) {
                $message = "Please remove the staff from role before deleting";
                $status = true;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }



            $message = "Role Removed Successfully";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
            // return redirect('/admin/restaurant_list')->with('success', trans('constants.restaurant_removed'));

        }
    }
}
