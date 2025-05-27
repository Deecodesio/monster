<?php

namespace App\Http\Controllers\store;

use App\Http\Controllers\api\BaseController;
use Illuminate\Http\Request;
use Validator;
use DB;

class SettingController extends BaseController
{
    /**
     * Get the settings data based on type.
     *
     * @return value to blade file
     */
    public function Getsettings($type, Request $request)
    {

        $id = $request->session()->get('userid');
        if (env('IS_DELIWARE') && $id != 1) {
            if (session()->get('role') == 2) {
                return redirect('/store');
            } else {
                return redirect('/admin');
            }
        }

        $data = $this->settings->pluck('value', 'key_word')->toArray();
        //   dd($data);
        if ($type == 'site') {
            return view('admin/site-settings', compact('data'));
        } elseif ($type == 'email') {
            return view('admin/email-settings', compact('data'));
        } else {
            return view('admin/google-settings', compact('data'));
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

        if ($type == 'site') {

            $check = $this->settings->where('type', 1)->get();
        } elseif ($type == 'email') {

            $check = $this->settings->where('type', 2)->get();
        } else {

            $check = $this->settings->where('type', 3)->get();
        }

        $rules = array();

        foreach ($check as $key => $value) {

            $validate_field = '$request->' . $value->key_word;

            $not_required_keys = [
                'site_favicon', 'site_logo', 'ios_app_link', 'android_app_link'
            ];

            if (!in_array($value->key_word, $not_required_keys)) {
                $rules[$value->key_word] = 'required';
            }

            if ($value->key_word == 'site_favicon') {
                $rules[$value->key_word] = 'mimes:jpeg,ico,jpg,png,gif|max:20||dimensions:max_width=48,max_height=48';
            }

            if ($value->key_word == 'site_logo') {
                $rules[$value->key_word] = 'mimes:jpeg,ico,jpg,png,gif|max:1024||dimensions:max_width=1200,max_height=600';
            }
        }

        //dd($rules);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            return back()->with('error', $error_messages);
        }

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

            if ($request->delivery_boy_commission != "") {
                $settings->where('key_word', 'delivery_boy_commission')
                    ->update(['value' => $request->delivery_boy_commission]);
            }

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

                $site_favicon = $this->custom->restaurant_upload_image($request, 'site_favicon');

                $settings->where('key_word', 'site_favicon')
                    ->update(['value' => $site_favicon]);
            }

            if ($request->site_logo != "") {

                $site_logo = $this->custom->restaurant_upload_image($request, 'site_logo');

                $settings->where('key_word', 'site_logo')
                    ->update(['value' => $site_logo]);
            }

            if ($request->default_unit != "") {
                $settings->where('key_word', 'default_unit')
                    ->update(['value' => $request->default_unit]);
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

            if ($request->time_zone != "") {
                $settings->where('key_word', 'time_zone')
                    ->update(['value' => $request->time_zone]);
            }

            if ($request->currency != "") {
                $settings->where('key_word', 'currency')
                    ->update(['value' => $request->currency]);
            }

            if ($request->order_prefix != "") {
                $settings->where('key_word', 'order_prefix')
                    ->update(['value' => $request->order_prefix]);
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

            if ($request->stripe_sk_key != "") {
                $settings->where('key_word', 'stripe_sk_key')
                    ->update(['value' => $request->stripe_sk_key]);
            }

            if ($request->stripe_pk_key != "") {
                $settings->where('key_word', 'stripe_pk_key')
                    ->update(['value' => $request->stripe_pk_key]);
            }

            if ($request->stripe_version != "") {
                $settings->where('key_word', 'stripe_version')
                    ->update(['value' => $request->stripe_version]);
            }
        }
        return back()->with('success', trans('constants.update_success_msg', ['param' => 'Setting']));
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

    public function get_currency(Request $request){
        $data = DB::table('country')->where('is_default', 1)->first();
        $data->value =  $data->currency_symbol;
		return response()->json($data);

    }
    public function get_logo(Request $request){
        $datas = DB::table('settings')->where('key_word', 'site_favicon')->first();
       
        if(file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH . $datas->value)) {
            $datas->value =  BASE_URL . RESTAURANT_UPLOADS_PATH . $datas->value;
        } else {
            $datas->value =  BASE_URL . RESTAURANT_UPLOADS_PATH ."logo.png";
        }
        $data1 = DB::table('settings')->where('key_word', 'app_name')->first();
        $logo = DB::table('settings')->where('key_word','site_logo')->first();

        $logo_img = BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
        
        $data = [];
        
        $data[] = [
            'name' => $data1->value,
            'logo' =>   $datas->value,
            'logo_img'=>$logo_img

        ];
        // if(file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH. $data->site_favicon)) {
        //     $data->site_favicon =  BASE_URL . RESTAURANT_UPLOADS_PATH .  $data->site_favicon;
        // } else {
        //     $data->site_favicon =  BASE_URL . RESTAURANT_UPLOADS_PATH ."logo.png";
        // }
		return response()->json($data);

    }
    public function get_lang(Request $request){
        $secondary = DB::table('settings')->where('key_word', 'secondary_language')->first();
        if( $secondary->value == "sw")
        {
            $secondary->value = "Swedish";
        }
        elseif($secondary->value == "du")
        {
            $secondary->value = "Dutch";
        }
        elseif($secondary->value == "fr")
        {
            $secondary->value = "French";
        }
        elseif($secondary->value == "pt")
        {
            $secondary->value = "Portuguese";
        }
        elseif($secondary->value == "ar")
        {
            $secondary->value = "Arabic";
        }
        elseif($secondary->value == "es")
        {
            $secondary->value = "Spanish";
        }
        elseif($secondary->value == "it")
        {
            $secondary->value = "Italian";
        }
        elseif($secondary->value == "vie")
        {
            $secondary->value = "Vietnamese";
        }
        elseif($secondary->value == "de")
        {
            $secondary->value = "German";
        }
        else{
            $secondary->value = "English";
        }
        $primary = DB::table('settings')->where('key_word', 'primary_language')->first();

        if($primary->value == "sw")
        {
            $primary->value = "Swedish";
        }
        elseif($primary->value == "du")
        {
            $primary->value = "Dutch";
        }
        elseif($primary->value == "fr")
        {
            $primary->value = "French";
        }
        elseif($primary->value == "pt")
        {
            $primary->value = "Portuguese";
        }
        elseif($primary->value == "ar")
        {
            $primary->value = "Arabic";
        }
        elseif($primary->value == "es")
        {
            $primary->value = "Spanish";
        }
        elseif($primary->value == "it")
        {
            $primary->value = "Italian";
        }
        elseif($primary->value == "vie")
        {
            $primary->value = "Vietnamese";
        }
        elseif($primary->value == "de")
        {
            $primary->value = "German";
        }
        else{
            $primary->value = "English";
        }
        $lang = DB::table('settings')->where('key_word', 'lang_enable')->first();
        $response_Array = json_encode(['primary' => $primary, 'secondary' => $secondary,'lang' => $lang]);
        return $response_Array;
		

    }
}
