<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\api\BaseController;
use Illuminate\Http\Request;
use Validator;

use Hash;

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
        //   dd($data);
        if ($type == 'site') {
           
        $message = $data;
        $status = true;
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);
        return $response_Array;

        } elseif ($type == 'email') {
          
        $message = $data;
        $status = true;
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);
        return $response_Array;

        } else {
            
        $message = $data;
        $status = true;
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);
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

            if ($request->IsGpayEnabled != "") {
                $settings->where('key_word', 'IsGpayEnabled')
                    ->update(['value' => $request->IsGpayEnabled]);
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


    public function get_role()
    {
        $roles = $this->role->get();
        return view('admin/add_role', compact('roles'));
    }

    public function get_roles()
    {
        $roles = $this->role->get();
        
        return response()->json($roles);
    }

    public function role_add(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'role_name' => 'required',
            'privilege' => 'required',
        ]);
      
        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('error', $error_messages);
        }

        $privileges =  preg_split("/[,]/",$request->privilege);
      
        $role = new Roles();
        $role->role_name = $request->role_name;
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
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);


        return $response_Array;
    }

    public function show_privilege($id)
    {
        $roles = $this->role->where('id', $id)->first();
        $privileges = $this->privileges->where('role_id', $id)->get();
        return view('admin/privilege', compact('roles', 'privileges'));
    }

    public function privilege_add(Request $request)
    {
        //dd(count($request->restaurant));
        $role_id = $request->role_id;
        $validator = Validator::make($request->all(), [
            'privilege' => 'required',
        ]);
        //dd($request);
        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            return back()->with('error', $error_messages);
        }
        $roles = $this->privileges->where('role_id', $role_id)->delete();

        $value = $request->privilege;
        for ($i = 0; $i < count($value); $i++) {
            $Privilege = new Privileges();
            $Privilege->role_id = $request->role_id;
            $Privilege->name = $value[$i];
            $Privilege->is_allowed = 1;
            $Privilege->save();
        }
        return back();
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

            $message =$error_messages;
            $status = false;
            $response_Array = json_encode(['message'=>$message,'status'=>$status]);
    
    
            return $response_Array;
        }
        if(empty($request->res_id)){
        $admin = new Add_admin();
        $admin->name = $request->admin_name;
        $admin->restaurant_id = json_encode($request->restaurant_name);
        $admin->email = $request->email;
        $admin->password =  Hash::make($request->password);
        $admin->org_password =  $request->password;
        $admin->role_id = $request->role_type;
        $admin->status = $request->status;
        $v = $admin->save();
        }else{
            $admin = $this->admin_role;
            $admin_role = $admin::findOrFail($request->res_id);
            $admin_role->name = $request->admin_name;
            $admin_role->restaurant_id = json_encode($request->restaurant_name);
            $admin_role->email = $request->email;
            $admin_role->password = Hash::make($request->password);
            $admin_role->org_password = $request->password;
            $admin_role->role_id = $request->role_type;
            $admin_role->status = $request->status;

            $admin_role->update();
        }
        //dd($v);
            $message ="Staff Added Successfully";
            $status = true;
            $response_Array = json_encode(['message'=>$message,'status'=>$status]);
    
    
            return $response_Array;
    }
    public function show_admin()
    {
        $admin = $this->admin_role->join('roles', 'roles.id', '=', 'admin_role.role_id')
            ->leftjoin('restaurants', 'restaurants.id', '=', 'admin_role.restaurant_id')->select('admin_role.*', 'roles.*', 'restaurant_name','admin_role.id as admin_id')->get();
       
        $admin1 = $this->admin_role->join('roles', 'roles.id', '=', 'admin_role.role_id')
            ->select('admin_role.*', 'roles.*')->where('restaurant_id', 0)->get();

               return response()->json($admin);

    }
    public function role_type(Request $request, $id)
    {
        $roles = $this->role->where('type',$id)->get();
        echo json_encode($roles);
    }
    public function get_roleprivilege(Request $request, $id)
    {
        $privileges = $this->privileges->where('role_id',$id)->get();
         echo json_encode($privileges);
        // return $privileges;
    }
    public function edit_admin(Request $request){
       $data = $this->admin_role->where('id', $request->id)->first();
     
		$message = $data;
		$status = true;
		$response_Array = json_encode(['message'=>$message,'status'=>$status,'data'=>$data]);
		return $response_Array;

    }

    public function get_roles_new(Request $request){
		$roles = $this->role->get();

		return response()->json($roles);
	}

    public function get_roles_byid(Request $request){
        $roles = $this->role->where('type',$request->type)->get();
		return response()->json($roles);

    }
}
