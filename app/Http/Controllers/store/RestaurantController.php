<?php

namespace App\Http\Controllers\store;

use App\Http\Controllers\api\BaseController;
use App\Model\City_geofencing;
use App\Model\Deliverypartners;
use App\Model\DriverList;
use App\Model\MenuTime;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Validator;
use App\Model\Requestdetail;
use App\Model\RequestdetailAddons;
use App\Http\Controllers\Mailer;
use App\Http\Controllers\Order;
use App\helpers;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use File;
use DeDmytro\CloudflareImages\Facades\CloudflareApi;
use Cartalyst\Stripe\Stripe;

class RestaurantController extends BaseController
{
    public function restaurant_list(Request $request)
    {
        $data = $this->restaurants->with('Foodrequest')->orderby('id', 'desc')->get();
        //dd($data[0]->Foodrequest[0]);

        return view('store/restaurant_list', ['data' => $data]);
    }

    public function restaurant()
    {

        $city = $this->addcity->get();
        $area = $this->addarea->get();
        $cuisines = $this->cuisines->get();
        $document = $this->document->where('document_for', 2)->where('status', 1)->get();
        $title = trans('constants.add_restaurant');

        session()->put('tab', 'tab1');
        return view('store/add_restaurant', [
            'city' => $city,
            'area' => $area,
            'title' => $title,
            'cuisines' => $cuisines,
            'document' => $document,
            'delivery_charge_limits' => [],
            'is_commission_editable' => 1,
        ]);
    }

    public function edit_restaurant($id, Request $request)
    {

        $city = $this->addcity->get();
        $brands = DB::table('brands')->where('status', '=', 1)->get();

        $cuisines = $this->cuisines->get();
        $title = trans('constants.edit_restaurant');
        $restaurants = $this->restaurants;

        $restaurant_detail = $restaurants->where('id', $id)->with(['Cuisines', 'Document', 'RestaurantBankDetails'])->first();

        $business = DB::table('business_type')->where('id', $restaurant_detail->business_type)->first();

        if ($restaurant_detail->password) {
            $restaurant_detail->password = $restaurant_detail->org_password;
        }
        if ($restaurant_detail->holidays != null) {
            $array = explode(',', $restaurant_detail->holidays);
            $restaurant_detail->holidays = $array;
        }
        if ($restaurant_detail->address != "") {
            $decodedText = html_entity_decode($restaurant_detail->address);
            $restaurant_detail->address = $decodedText;
        }

        $area = $this->addarea->where('add_city_id', $restaurant_detail->city)->get();



        if ($restaurant_detail->banner != "") {

            if (file_exists(public_path('/') . RESTAURANT_BANNER . $restaurant_detail->banner)) {
                $restaurant_detail->banner =  BASE_URL . RESTAURANT_BANNER . $restaurant_detail->banner;
            } elseif (file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH . $restaurant_detail->banner)) {
                $restaurant_detail->banner =  BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->banner;
            } elseif (file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . "/Banner" . "/" . $restaurant_detail->banner)) {
                $restaurant_detail->banner =  BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . "/Banner" . "/" . $restaurant_detail->banner;
            } else {
                $restaurant_detail->banner =  "";
            }
        } else {
            $restaurant_detail->banner = "";
        }
        if ($restaurant_detail->image != "") {

            if (file_exists(public_path('/') . RESTAURANT_LOGO . $restaurant_detail->image)) {
                $restaurant_detail->image =  BASE_URL . RESTAURANT_LOGO . $restaurant_detail->image;
            } elseif (file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image)) {
                $restaurant_detail->image =  BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->image;
            } elseif (file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . "/Logo" . "/" . $restaurant_detail->image)) {
                $restaurant_detail->image =  BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . "/Logo" . "/" . $restaurant_detail->image;
            } else {
                $restaurant_detail->image =  "";
            }
        } else {
            $restaurant_detail->image = "";
        }


        // }

        foreach ($restaurant_detail->document as $val) {


            $val->id = (string)$val->id;
            $val->pivot->document = BASE_URL . UPLOADS_PATH . $val->pivot->document;
        }
        if ($restaurant_detail->delivery_type != "null" || $restaurant_detail->delivery_type != '') {
            $integerIDs = array_map('intval', json_decode($restaurant_detail->delivery_type, true));

            $restaurant_detail->delivery_type = $integerIDs;
        }




        if ($restaurant_detail->video_title != "null") {
            $video_title = str_replace('"', '', $restaurant_detail->video_title);
            $array1 = explode(',', $video_title);
            $restaurant_detail->video_title = json_decode($restaurant_detail->video_title);
        }

        if ($restaurant_detail->video_url != "null") {
            $video_url = str_replace('"', '', $restaurant_detail->video_url);
            $array2 = explode(',', $video_url);
            $restaurant_detail->video_url = json_decode($restaurant_detail->video_url);
        }

        $document = $this->document->where('document_for', 2)->where('status', 1)->get();

        $delivery_charge_limits = DB::table('delivery_charge_limits')->where('restaurant_id', $id)->get();

        $cuisine_ids = array();
        foreach ($restaurant_detail->Cuisines as $val) {
            $cuisine_ids[] = $val->id;
        }


        if ($restaurant_detail) {
            $restaurant_detail->account_name = $this->secound_name($restaurant_detail->account_name);
            $restaurant_detail->account_address = $this->secound_name($restaurant_detail->account_address);
            $restaurant_detail->account_no =  $this->secound_name($restaurant_detail->account_no);
            $restaurant_detail->bank_name = $this->secound_name($restaurant_detail->bank_name);
            $restaurant_detail->branch_name = $this->secound_name($restaurant_detail->branch_name);
            $restaurant_detail->branch_address = $this->secound_name($restaurant_detail->branch_address);
            $restaurant_detail->swift_code = $this->secound_name($restaurant_detail->swift_code);
            $restaurant_detail->routing_no = $this->secound_name($restaurant_detail->routing_no);
        }
        if ($restaurant_detail->restaurant_name) {
            $restaurant_detail->restaurant_name = $this->secound_name($restaurant_detail->restaurant_name);
        }
        if ($restaurant_detail->restaurant_secondary_name) {
            $restaurant_detail->restaurant_secondary_name = $this->secound_name($restaurant_detail->restaurant_secondary_name);
        }
        $restaurant_detail->cuisine_ids = $cuisine_ids;
        $cuisines_new = DB::table('restaurant_cuisines')->where('restaurant_id', $id)->get();
        $message = "Updated Successfully";
        $status = true;
        $response_Array = json_encode([
            'message' => $message,
            'status' => $status,
            'cuisine_ids' => $cuisine_ids,
            'data' => $restaurant_detail,
            'city' => $city,
            'area' => $area,
            'title' => $title,
            'cuisines' => $cuisines,
            'document' => $document,
            'delivery_charge_limits' => $delivery_charge_limits,
            'brands' => $brands,
            'is_commission_editable' => 1,
            'cuisines_new' => $cuisines_new,
            'business_type' => $business->layout_id,

        ]);
        // dd($response_Array);
        return $response_Array;
    }

    public function edit_my_restaurant(Request $request)
    {

        $id = $request->session()->get('res_id');
        $city = $this->addcity->get();
        $cuisines = $this->cuisines->get();
        $title = trans('constants.edit_restaurant');
        $restaurants = $this->restaurants;
        $restaurant_detail = $restaurants->where('id', $id)->with(['Cuisines', 'Document', 'RestaurantBankDetails'])->first();
        $document = $this->document->where('document_for', 2)->where('status', 1)->get();
        $area = $this->addarea->where('add_city_id', $restaurant_detail->city)->get();
        $delivery_charge_limits = DB::table('delivery_charge_limits')->where('restaurant_id', $id)->get();
        $cuisine_ids = array();
        foreach ($restaurant_detail->Cuisines as $val) {
            $cuisine_ids[] = $val->id;
        }
        $privilege = $request->session()->get('privilege');
        $permission = permission($privilege, 'restaurant-view');
        //  $brand_name =DB::table('brands')->where('id',$restaurant_detail->brand_name)->first();

        if ($restaurant_detail->brand_name != 0) {
            $restaurant_id = $restaurants->where('brand_name', $restaurant_detail->brand_name)->get();
        } else {
            $restaurant_id = $restaurants->where('id', $restaurant_detail->id)->get();
        }
        $res_id = [];
        foreach ($restaurant_id as $ids) {
            $res_id[] = $ids->id;
        }

        // $array =json_decode($restaurant_id);
        // dd ();
        if ($permission == 1 || $privilege == -1) {
            return view('store/add_restaurant_tab', [
                'cuisine_ids' => $cuisine_ids,
                'data' => $restaurant_detail,
                'city' => $city,
                'area' => $area,
                'title' => $title,
                'cuisines' => $cuisines,
                'delivery_charge_limits' => $delivery_charge_limits,
                'is_edit_by_me' => 1,
                'document' => $document,
                'is_delivery_charge_readonly' => 1,
                'res_id' =>  $res_id,
                'id' => $id
            ]);
        } else {
            return view('store/restricted');
        }
    }
    public function edit_my_restaurant_timing(Request $request)
    {

        $id = $request->session()->get('res_id');
        $city = $this->addcity->get();
        $cuisines = $this->cuisines->get();
        $title = trans('constants.edit_restaurant');
        $restaurants = $this->restaurants;
        $restaurant_detail = $restaurants->where('id', $id)->with(['Cuisines', 'Document', 'RestaurantBankDetails'])->first();
        $document = $this->document->where('document_for', 2)->where('status', 1)->get();
        $area = $this->addarea->where('add_city_id', $restaurant_detail->city)->get();
        $delivery_charge_limits = DB::table('delivery_charge_limits')->where('restaurant_id', $id)->get();

        $cuisine_ids = array();
        foreach ($restaurant_detail->Cuisines as $val) {
            $cuisine_ids[] = $val->id;
        }
        $privilege = $request->session()->get('privilege');
        $permission = permission($privilege, 'restaurant-view');
        if ($permission == 1 || $privilege == -1) {
            return view('store/add_restaurant_timing', [
                'cuisine_ids' => $cuisine_ids,
                'data' => $restaurant_detail,
                'city' => $city,
                'area' => $area,
                'title' => $title,
                'cuisines' => $cuisines,
                'delivery_charge_limits' => $delivery_charge_limits,
                'is_edit_by_me' => 1,
                'document' => $document,
                'is_delivery_charge_readonly' => 1
            ]);
        } else {
            return view('store/restricted');
        }
    }

    public function edit_my_restaurant_timing1(Request $request)
    {
        $id = $request->id;
        $timing = DB::table('restaurants')->where('id', $id)->first();
        // dd($timing->holidays );
        $array = explode(',', $timing->holidays);
        // $explode_id = array_map('intval', explode(',', $timing->holidays));
        // $array = explode(',', $timing->holidays);
        $timing->holidays = $array;
        $response_Array = json_encode(['data' => $timing]);
        return $response_Array;
    }
    public function add_to_restaurants_tab1(Request $request)
    {
        $id = $request->id;
        $restaurants = $this->restaurants;
        // $restaurant_update = $restaurants->where('id',$id)->get();
        $restaurant_update = $restaurants::findOrFail($id);
        $custom = $this->custom;
        $name = $this->secound_lang_name($request->name);
        // dd($name);
        $restaurant_secondary_name = $this->secound_lang_name($request->restaurant_secondary_name);
        $email = $request->email;
        $password = $request->password;
        $phone = $request->phone;
        $city = $request->city;
        $address = $request->address;
        $area = $request->area;
        $GeofenceRadius = $request->GeofenceRadius;
        $description = $request->shop_description;
        $packaging_charge = $request->packaging_charge;
        $estimated_delivery_time = $request->estimated_delivery_time;

        $phone_country_calling_code = $request->country_calling_code;
        $phone_country_code = $request->country_code;
        $contact_country_calling_code = $request->country_calling_code_contact;
        $contact_country_code = $request->country_code_contact;
        //dd( $restaurant_update->brand_name);
        if ($request->existing == "undefined" || $request->existing == null) {
            $banner  = basename($request->banner);
        } else {
            if ($request->existing == 1) {
                $banner = $request->banner;
            } else {

                $banner = $custom->restaurant_upload_image_banner($request, 'banner', $id);
            }
        }
        $restaurant_update->banner = $banner;
        $image = $request->image;

        if ($request->existing1 == "undefined" || $request->existing == null) {
            $image  = basename($request->image);
        } else {
            if ($request->existing1 == 1) {
                $image = $request->image;
            } else {

                $image = $custom->restaurant_upload_image_logo($request, 'image', $id);
            }
        }
        $restaurant_update->image = $image;
        $restaurant_update->banner = $banner;


        $admin_commision = $request->admin_commision;
        // $delivery_charge_type = $request->delivery_charge_type ?? 1;
        //$status = (int) ($request->status ?? 0);
        $array_delivery = explode(',', $request->delivery_type);
        // $array_video_title = explode(',', $request->video_title);
        // $array_video_url = explode(',', $request->video_url);
        $video_title = json_decode($request->video_title);
        $video_url = json_decode($request->video_url);

        $delivery_type = $array_delivery;
        // $video_title = $array_video_title;
        // $video_url =$array_video_url;


        $restaurant_update->restaurant_name = $name;
        $restaurant_update->email = $email;
        $restaurant_update->restaurant_secondary_name = $restaurant_secondary_name;
        $restaurant_update->org_password = $password;

        $restaurant_update->password = Hash::make($password);
        $restaurant_update->phone = $phone;
        $restaurant_update->contact = $request->contact;
        $restaurant_update->city = $city;
        $restaurant_update->address = $address;
        $restaurant_update->lat = $request->latitude;
        $restaurant_update->lng = $request->longitude;
        $restaurant_update->shop_description = $description;
        $restaurant_update->area = $area;
        $restaurant_update->GeofenceRadius = $GeofenceRadius;
        $restaurant_update->packaging_charge = $packaging_charge;
        $restaurant_update->admin_commision = $admin_commision;
        $restaurant_update->estimated_delivery_time = $estimated_delivery_time;
        $restaurant_update->delivery_type = json_encode($delivery_type);
        $restaurant_update->video_title = json_encode($video_title);
        $restaurant_update->video_url = json_encode($video_url);
        if ($request->deliveryChargeBasedOn) {
            $restaurants->DeliveryChargeBasedOn = $request->deliveryChargeBasedOn;
        }

        $restaurant_update->phone_country_calling_code = $phone_country_calling_code;
        $restaurant_update->phone_country_code = $phone_country_code;
        $restaurant_update->contact_country_calling_code = $contact_country_calling_code;
        $restaurant_update->contact_country_code = $contact_country_code;

        $restaurant_update->update();
        $originalText = $request->address;
        $convertedText = mb_convert_encoding($originalText, 'HTML-ENTITIES', 'UTF-8');
        // $decodedText = html_entity_decode($convertedText);
        //    dd($decodedText);
        $res_id = DB::table('restaurants')->where('id', $id)
            ->update([
                'address' => $convertedText,
            ]);

        //update cuisine
        $array = explode(',', $request->cuisines);


        $cuisines = $this->cuisines->find($array); //dd($array);
        $restaurant_update->Cuisines()->sync($cuisines);


        // $tab = 'tab2';
        // session()->put('tab', 'tab2');
        // session()->put('elmnt', 'this');
        // session()->put('color', '#409a40e8');
        // return redirect('/store/my_restaurant#tab2')->with('success', "Restaurant Details Updated");

        $layout = DB::table('business_type')->where('id', $restaurant_update->business_type)->first();

        if ($layout->layout_id == 1) {
            $message = "Restaurant Details Updated";
        } else {

            $message = "Store Details Updated";
        }

        // $message = "Restaurant Details Updated";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $request->id]);
        return $response_Array;
    }
    public function add_to_restaurants_tab2(Request $request)
    {
        //  dd($request);
        $id = $request->id;
        $restaurants = $this->restaurants;
        // $restaurant_update = $restaurants->where('id',$id)->get();
        $restaurant_update = $restaurants::findOrFail($id);
        $custom = $this->custom;
        for ($i = 1; $i <= 7; $i++) {
            $opening_time = 'opening_time_' . $i;
            $closing_time = 'closing_time_' . $i;

            $opening_second_time = 'opening_second_time_' . $i;
            $closing_second_time = 'closing_second_time_' . $i;

            $$opening_time = date("H:i:s", strtotime($request->{'opening_time_' . $i}));
            $$closing_time = date("H:i:s", strtotime($request->{'closing_time_' . $i}));
            if ($request->{'opening_time_' . $i} == null) {
                $$opening_time = '';
                $$closing_time = '';
            }

            if (!empty($request->{'opening_second_time_' . $i}) && !empty($request->{'closing_second_time_' . $i})) {
                $$opening_second_time = date("H:i:s", strtotime($request->{'opening_second_time_' . $i}));
                $$closing_second_time = date("H:i:s", strtotime($request->{'closing_second_time_' . $i}));
            } else {
                $$opening_second_time = '';
                $$closing_second_time = '';
            }
        }
        $holidays =  $request->holidays;

        //  $holidays = is_array($request->holidays) ? implode(',', $request->holidays) : '';
        // dd($request->holidays);
        $restaurant_update->opening_time_1 = $opening_time_1;
        $restaurant_update->closing_time_1 = $closing_time_1;

        $restaurant_update->opening_time_2 = $opening_time_2;
        $restaurant_update->closing_time_2 = $closing_time_2;

        $restaurant_update->opening_time_3 = $opening_time_3;
        $restaurant_update->closing_time_3 = $closing_time_3;

        $restaurant_update->opening_time_4 = $opening_time_4;
        $restaurant_update->closing_time_4 = $closing_time_4;

        $restaurant_update->opening_time_5 = $opening_time_5;
        $restaurant_update->closing_time_5 = $closing_time_5;

        $restaurant_update->opening_time_6 = $opening_time_6;
        $restaurant_update->closing_time_6 = $closing_time_6;

        $restaurant_update->opening_time_7 = $opening_time_7;
        $restaurant_update->closing_time_7 = $closing_time_7;

        for ($i = 1; $i <= 7; $i++) {
            $restaurant_update->{'opening_second_time_' . $i} = ${'opening_second_time_' . $i};
            $restaurant_update->{'closing_second_time_' . $i} = ${'closing_second_time_' . $i};
        }

        $restaurant_update->holidays = $holidays;
        $restaurant_update->update();

        if ($request->time_edit == "time") {

            $message = "Timing Updated";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        } else {
            $layout = DB::table('business_type')->where('id', $restaurant_update->business_type)->first();

            if ($layout->layout_id == 1) {
                $message = "Restaurant Timing Updated";
            } else {

                $message = "Store Timing Updated";
            }
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $request->id]);
            return $response_Array;
        }
    }
    public function add_to_restaurants_tab3(Request $request)
    {

        $id = $request->id;
        $restaurants = $this->restaurants;
        $restaurants_det = $restaurants->find($request->id);
        $custom = $this->custom;
        $sync_data = array();
        if ($request->file('document')) {
            foreach ($request->file('document') as $key => $value) {

                if ($_FILES['document']['name'][$key]['document'] != '') {
                    $expiry_date = '';

                    if (isset($request['document'][$key]['date']) && $request['document'][$key]['date'] != null) {
                        $expiry_date = date("Y-m-d", strtotime($request['document'][$key]['date']));
                    }
                    $filename = strtotime(date("Y-m-d")) . basename($_FILES['document']['name'][$key]['document']);

                    $imageName = $custom->move_uploaded_file($value['document']);
                    $sync_data[$key] = ['document' => $imageName, 'expiry_date' => $expiry_date];
                }
            }

            $restaurants_det->Document()->sync($sync_data);
        }
        // session()->put('tab', 'tab4');
        // return redirect('/store/my_restaurant#tab3')->with('success', "Documents updated");
        $layout = DB::table('business_type')->where('id', $restaurants_det->business_type)->first();

        if ($layout->layout_id == 1) {
            $message = "Restaurant Document Updated";
        } else {

            $message = "Store Document Updated";
        }
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $request->id]);
        return $response_Array;
    }
    public function add_to_restaurants_tab4(Request $request)
    {

        $restaurant_bank_details = $this->restaurant_bank_details->where('restaurant_id', $request->id)->first();
        if (empty($restaurant_bank_details)) {
            $restaurant_bank_details = $this->restaurant_bank_details;
            $restaurant_bank_details->restaurant_id = $request->id;
            $restaurant_bank_details->account_name = $request->account_name;
            $restaurant_bank_details->account_address = $request->account_address;
            $restaurant_bank_details->account_no = $request->account_no;
            $restaurant_bank_details->bank_name = $request->bank_name;
            $restaurant_bank_details->branch_name = $request->branch_name;
            $restaurant_bank_details->branch_address = $request->branch_address;
            $restaurant_bank_details->swift_code = $request->swift_code;
            $restaurant_bank_details->routing_no = $request->routing_no;
            $restaurant_bank_details->save();
            // session()->put('tab', 'tab1');
            // return redirect('/store/my_restaurant#tab4')->with('success', "Bank Detail Updated");
            $message = "Store Bank Updated";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $request->id]);
            return $response_Array;
        } else {
            $restaurant_bank_details->account_name = $request->account_name;
            $restaurant_bank_details->account_address = $request->account_address;
            $restaurant_bank_details->account_no = $request->account_no;
            $restaurant_bank_details->bank_name = $request->bank_name;
            $restaurant_bank_details->branch_name = $request->branch_name;
            $restaurant_bank_details->branch_address = $request->branch_address;
            $restaurant_bank_details->swift_code = $request->swift_code;
            $restaurant_bank_details->routing_no = $request->routing_no;
            $restaurant_bank_details->update();
            // session()->put('tab', 'tab1');
            // return redirect('/store/my_restaurant#tab4')->with('success', "Bank details updated");
            $message = "Store Bank Updated";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $request->id]);
            return $response_Array;
        }
    }
    public function add_to_restaurants(Request $request)
    {
        $restaurants = $this->restaurants;
        $custom = $this->custom;
        $name = $request->name;
        $restaurant_secondary_name = $request->restaurant_secondary_name ?? '';
        $email = $request->email;
        $phone = $request->phone;
        $city = $request->city;
        $area = $request->area;
        $discount_type = $request->discount_type ?? 1;
        $target_amount = $request->target_amount;
        $offer_amount = $request->offer_amount;
        $admin_commision = $request->admin_commision;
        $status = (int) ($request->status ?? 0);
        $is_status_changed = 0;

        for ($i = 1; $i <= 7; $i++) {
            $opening_time = 'opening_time_' . $i;
            $closing_time = 'closing_time_' . $i;

            $opening_second_time = 'opening_second_time_' . $i;
            $closing_second_time = 'closing_second_time_' . $i;

            $$opening_time = date("H:i:s", strtotime($request->{'opening_time_' . $i}));
            $$closing_time = date("H:i:s", strtotime($request->{'closing_time_' . $i}));

            if (!empty($request->{'opening_second_time_' . $i}) && !empty($request->{'closing_second_time_' . $i})) {
                $$opening_second_time = date("H:i:s", strtotime($request->{'opening_second_time_' . $i}));
                $$closing_second_time = date("H:i:s", strtotime($request->{'closing_second_time_' . $i}));
            } else {
                $$opening_second_time = '';
                $$closing_second_time = '';
            }
        }

        $holidays =  $request->holidays;
        if ($request->file('banner')) {
            if ($request->banner) {
                $banner = $custom->store_restaurant_upload_image_banner($request, 'banner', $request->id);
            }
        }


        if ($request->file('image')) {
            if ($request->image) {
                $image = $custom->store_restaurant_upload_image_logo($request, 'image', $request->id);
            } else {
                $image = PROFILE_ICON;
            }
        }




        $packaging_charge = $request->packaging_charge;
        //$offer_percentage = $request->offer_percentage;
        if ($request->shop_description) {
            $shop_description = $request->shop_description;
        } else {
            $shop_description = "";
        }
        $estimated_delivery_time = $request->estimated_delivery_time;
        $address = $request->address;

        $restaurantID = null;

        if ($request->id) {
            $restaurantID = $request->id;
            $restaurants_det = $restaurants->find($request->id);

            if (isset($request->status) && $status != $restaurants_det->status) {
                $is_status_changed = 1;
            }

            if (!$request->image) {
                $image = $restaurants_det->image;
            }

            $restaurants_det->restaurant_name = $name;
            $restaurants_det->restaurant_secondary_name = $restaurant_secondary_name;
            if ($request->file('image')) {
                $restaurants_det->image = $image;
            }
            if ($request->file('banner')) {
                $restaurants_det->banner = $banner;
            }
            $restaurants_det->email = $email;
            $restaurants_det->org_password = $request->password;
            $restaurants_det->password = Hash::make($request->password);
            $restaurants_det->phone = $phone;
            $restaurants_det->city = $city;
            $restaurants_det->area = $area;
            $restaurants_det->discount_type = $discount_type;
            $restaurants_det->target_amount = $target_amount;
            $restaurants_det->offer_amount = $offer_amount;
            $restaurants_det->admin_commision = $admin_commision;
            $restaurants_det->restaurant_delivery_charge = $request->restaurant_delivery_charge;
            //$restaurants_det->delivery_charge_type = $delivery_charge_type;
            // $restaurants_det->driver_commision = $request->driver_commision;
            //$restaurants_det->discount = $offer_percentage;
            $restaurants_det->shop_description = $shop_description;
            $restaurants_det->estimated_delivery_time = $estimated_delivery_time;
            $restaurants_det->packaging_charge = $packaging_charge;
            $restaurants_det->address = $address;
            $restaurants_det->lat = $request->latitude;
            $restaurants_det->lng = $request->longitude;

            if ($request->deliveryChargeBasedOn) {
                $restaurants_det->DeliveryChargeBasedOn = $request->deliveryChargeBasedOn;
            }

            //Opening closing time
            $restaurants_det->opening_time_1 = $opening_time_1;
            $restaurants_det->closing_time_1 = $closing_time_1;

            $restaurants_det->opening_time_2 = $opening_time_2;
            $restaurants_det->closing_time_2 = $closing_time_2;

            $restaurants_det->opening_time_3 = $opening_time_3;
            $restaurants_det->closing_time_3 = $closing_time_3;

            $restaurants_det->opening_time_4 = $opening_time_4;
            $restaurants_det->closing_time_4 = $closing_time_4;

            $restaurants_det->opening_time_5 = $opening_time_5;
            $restaurants_det->closing_time_5 = $closing_time_5;

            $restaurants_det->opening_time_6 = $opening_time_6;
            $restaurants_det->closing_time_6 = $closing_time_6;

            $restaurants_det->opening_time_7 = $opening_time_7;
            $restaurants_det->closing_time_7 = $closing_time_7;

            for ($i = 1; $i <= 7; $i++) {
                $restaurants_det->{'opening_second_time_' . $i} = ${'opening_second_time_' . $i};
                $restaurants_det->{'closing_second_time_' . $i} = ${'closing_second_time_' . $i};
            }

            $restaurants_det->holidays = $holidays;
            // -- Opening closing time

            $restaurants_det->status = $request->status ?? $restaurants_det->status;
            $restaurants_det->delivery_type = json_encode($request->delivery_type);
            $restaurants_det->save();

            $cuisines = $this->cuisines->find($request->cuisines);
            //update many to many relationship data
            $restaurants_det->Cuisines()->sync($cuisines);

            //data insert into document many to many
            $sync_data = array();
            if (0 && !empty($request->document)) {
                foreach ($request->document as $key => $value) {
                    if ($_FILES['document']['name'][$key]['document'] != '') {
                        $expiry_date = '';
                        if (isset($value['date']) && $value['date'] != null) {
                            $expiry_date = date("Y-m-d", strtotime($value['date']));
                        }

                        $filename = strtotime(date("Y-m-d")) . basename($_FILES['document']['name'][$key]['document']);
                        move_uploaded_file($_FILES["document"]["tmp_name"][$key]['document'], 'uploads/Restaurant Document/' . $filename);

                        $sync_data[$key] = ['document' => $filename, 'expiry_date' => $expiry_date];

                        // if($expiry_date!='' && $_FILES['document']['name'][$key]['document']!='')
                        //     $sync_data[$key] = ['document' => $filename,'expiry_date'=>$expiry_date];
                        // elseif($expiry_date==''  && $_FILES['document']['name'][$key]['document']!='')
                        //     $sync_data[$key] = ['document' => $filename, ,'expiry_date'=>$expiry_date];
                        // elseif($expiry_date!=''  && $_FILES['document']['name'][$key]['document']=='')
                        //     $sync_data[$key] = ['expiry_date'=>$expiry_date];
                    }
                }
                $restaurants_det->Document()->sync($sync_data);
            }
            $restaurant_bank_details = $this->restaurant_bank_details->where('restaurant_id', $request->id)->first();
            if (empty($restaurant_bank_details)) {
                $restaurant_bank_details = $this->restaurant_bank_details;
            }

            $restaurant_bank_details->account_name = $request->account_name;
            $restaurant_bank_details->account_address = $request->account_address;
            $restaurant_bank_details->account_no = $request->account_no;
            $restaurant_bank_details->bank_name = $request->bank_name;
            $restaurant_bank_details->branch_name = $request->branch_name;
            $restaurant_bank_details->branch_address = $request->branch_address;
            $restaurant_bank_details->swift_code = $request->swift_code;
            $restaurant_bank_details->routing_no = $request->routing_no;
            $restaurants_det->RestaurantBankDetails()->save($restaurant_bank_details);
            $msg = "update_success_msg";

            $delivery_charge_minimum = $request->delivery_charge_minimum;
            $delivery_charge_maximum = $request->delivery_charge_maximum;
            $delivery_charge_value = $request->delivery_charge_value;
            $delivery_charge_type = $request->delivery_charge_type;
            $delivery_charge_limit = $request->delivery_charge_minimum ? count($request->delivery_charge_minimum) : 0;

            DB::table('delivery_charge_limits')->where('restaurant_id', $restaurantID)->delete();
            for ($i = 0; $i < $delivery_charge_limit; $i++) {
                if ($i == 0) {
                    if (empty($delivery_charge_maximum[$i]) || empty($delivery_charge_value[$i]) || empty($delivery_charge_type[$i])) {
                        continue;
                    }
                } else {
                    if (empty($delivery_charge_minimum[$i]) || empty($delivery_charge_maximum[$i]) || empty($delivery_charge_value[$i]) || empty($delivery_charge_type[$i])) {
                        continue;
                    }
                }

                if (trim($delivery_charge_maximum[$i]) == 'Infinite') {
                    $delivery_charge_maximum[$i] = null;
                }

                DB::table('delivery_charge_limits')->insert([
                    'restaurant_id' => $restaurantID,
                    'Minimum' => $delivery_charge_minimum[$i],
                    'Maximum' => $delivery_charge_maximum[$i],
                    'Value' => $delivery_charge_value[$i],
                    'Type' => $delivery_charge_type[$i],
                ]);
            }
        } else {
            $check_email_phone = $restaurants->where('email', $request->email)->orwhere('phone', $request->phone)->first();
            if ($check_email_phone) {
                return back()->with('error', trans('constants.email_phone_already_exists'))->withInput();;
            }
            $restaurants->restaurant_name = $name;



            if ($request->file('image')) {
                $restaurants->image = $image;
            }
            if ($request->file('banner')) {
                $restaurants->banner = $banner;
            }
            $restaurants->email = $email;
            $restaurants->org_password = $request->password;
            $restaurants->password = Hash::make($request->password);
            $restaurants->phone = $phone;
            $restaurants->city = $city;
            $restaurants->area = $area;
            $restaurants->discount_type = $discount_type;
            $restaurants->target_amount = $target_amount;
            $restaurants->offer_amount = $offer_amount;
            $restaurants->admin_commision = $admin_commision;
            $restaurants->restaurant_delivery_charge = $request->restaurant_delivery_charge;
            //$restaurants->delivery_charge_type = $delivery_charge_type;
            // $restaurants->driver_commision = $request->driver_commision;
            //$restaurants->discount = $offer_percentage;
            $restaurants->shop_description = $shop_description;
            $restaurants->is_open = 1;
            $restaurants->estimated_delivery_time = $estimated_delivery_time;
            $restaurants->packaging_charge = $packaging_charge;
            $restaurants->address = $address;
            $restaurants->email_confirmation = md5(time());
            $restaurants->lat = $request->latitude;
            $restaurants->lng = $request->longitude;

            if ($request->deliveryChargeBasedOn) {
                $restaurants->DeliveryChargeBasedOn = $request->deliveryChargeBasedOn;
            }

            //Opening closing time
            $restaurants->opening_time_1 = $opening_time_1;
            $restaurants->closing_time_1 = $closing_time_1;

            $restaurants->opening_time_2 = $opening_time_2;
            $restaurants->closing_time_2 = $closing_time_2;

            $restaurants->opening_time_3 = $opening_time_3;
            $restaurants->closing_time_3 = $closing_time_3;

            $restaurants->opening_time_4 = $opening_time_4;
            $restaurants->closing_time_4 = $closing_time_4;

            $restaurants->opening_time_5 = $opening_time_5;
            $restaurants->closing_time_5 = $closing_time_5;

            $restaurants->opening_time_6 = $opening_time_6;
            $restaurants->closing_time_6 = $closing_time_6;

            $restaurants->opening_time_7 = $opening_time_7;
            $restaurants->closing_time_7 = $closing_time_7;
            // -- Opening closing time

            $restaurants->status = $status;
            $restaurants->delivery_type = json_encode($request->delivery_type);
            $restaurants->save();

            $cuisines = $this->cuisines->find($request->cuisines);
            $restaurants->Cuisines()->attach($cuisines);

            //$food_quantity = $this->document->find($request->food_quantity);
            $sync_data = array();
            if (0 && !empty($request->document)) {
                foreach ($request->document as $key => $value) {
                    if ($_FILES['document']['name'][$key]['document'] != '') {
                        $expiry_date = '';
                        if (isset($value['date'])) {
                            $expiry_date = date("Y-m-d", strtotime($value['date']));
                        }

                        $filename = strtotime(date("Y-m-d")) . basename($_FILES['document']['name'][$key]['document']);
                        move_uploaded_file($_FILES["document"]["tmp_name"][$key]['document'], 'uploads/Restaurant Document/' . $filename);
                        // $filename = $this->base_image_upload($request,'document','Restaurant Documents');

                        $sync_data[$key] = ['document' => $filename, 'expiry_date' => $expiry_date];

                        // if($expiry_date=='')
                        //     $sync_data[$key] = ['document' => $filename];
                        // else
                        //     $sync_data[$key] = ['document' => $filename,'expiry_date'=>$expiry_date];
                    }
                }
                //dd($sync_data);
                $restaurants->Document()->attach($sync_data);
            }

            $this->restaurant_bank_details->account_name = $request->account_name;
            $this->restaurant_bank_details->account_address = $request->account_address;
            $this->restaurant_bank_details->account_no = $request->account_no;
            $this->restaurant_bank_details->bank_name = $request->bank_name;
            $this->restaurant_bank_details->branch_name = $request->branch_name;
            $this->restaurant_bank_details->branch_address = $request->branch_address;
            $this->restaurant_bank_details->swift_code = $request->swift_code;
            $this->restaurant_bank_details->routing_no = $request->routing_no;
            $restaurants->RestaurantBankDetails()->save($this->restaurant_bank_details);

            $msg = "add_success_msg";

            $restaurant_new = $restaurants->where('email', $request->email)->first();
            $html = view('emails.restaurant.restaurant-register')->with(['restaurant' => $restaurant_new]);

            // $Mailer = new Mailer();
            // $Mailer->to         = $restaurant_new->email;
            // $Mailer->toName     = $restaurant_new->restaurant_name;
            // $Mailer->subject     = "Welcome";
            // $Mailer->body       = $html;
            // $Mailer->send();
        }

        if ($request->id && $is_status_changed) {
            sendApprovalStatusEmail2Restaurant($request->id);
        }

        return redirect()->back()->with('success', trans('constants.' . $msg, ['param' => 'Restaurant']));
    }

    public function delete_restaurant($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            return back()->with('error', $error_messages);
        } else {
            $id = $request->id;
            $restaurants = $this->restaurants;
            $foodlist = $this->foodlist;
            $banner = $this->banner;

            $restaurants->where('id', $id)->delete();
            $foodlist->where('restaurant_id', $id)->delete();
            $banner->where('restaurant_id', $id)->delete();

            return redirect('/store/restaurant_list')->with('success', trans('constants.restaurant_removed'));
        }
    }

    public function restaurant_view($id)
    {
        $restaurant_view = $this->restaurants->with(['city_list', 'Area'])->find($id);
        //dd($restaurant_view);

        return view('store/restaurant_view', ['restaurant' => $restaurant_view]);
    }
    public function product_list(Request $request)
    {
        //  $data = DB::table('food_list')->whereBetween('id',[9062,12544])->update(['business_category_id' => 13]);
        $restaurant_id = $request->id;

        $res_details = DB::table('restaurants')->where('id', $restaurant_id)
            ->first();
        $business = DB::table('business_type')->where('id', $res_details->business_type)
            ->first();

        if ($business->layout_id == 2) {
            $data = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                ->leftJoin('category', 'category.id', '=', 'food_list.category_id')
                ->leftJoin('business_category', 'business_category.id', '=', 'food_list.business_category_id')
                ->select('food_list.id as food_id', 'business_category.category_name as business_name', 'food_list.*', 'category.*',  'food_list.status as f_status', 'food_list.out_of_stock as stock_status')
                ->get();
        } else {
            $data = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                ->leftJoin('menu', 'menu.id', '=', 'food_list.menu_id')
                ->leftJoin('business_category', 'business_category.id', '=', 'food_list.business_category_id')
                ->select('food_list.id as food_id', 'business_category.category_name as business_name', 'food_list.*',  'menu.*', 'food_list.status as f_status', 'food_list.out_of_stock as stock_status')
                ->get();
        }
        foreach ($data as $d) {
            $d->name = $this->secondLanguage_store($d->name, $d->secondary_name);
            $d->description = $this->secondLanguage_store($d->description, $d->secondary_description);
        }
        $download_path = BASE_URL . UPLOADS_PATH . $request->id . "_products.csv";
        $response_Array = json_encode(['data' => $data, 'download_path' => $download_path, 'business' => $business->layout_id]);
        return $response_Array;
        // $data->download = BASE_URL.UPLOADS_PATH.$request->id."_products.csv";
        // return response()->json($data);
        // return view('store/product_list', ['data' => $data]);
    }

    public function add_product(Request $request)
    {
        if ($request->session()->get('res_role') == 1) {
            $restaurant = $this->restaurants->get();
            $menu_list = $this->menu->get();
        } else {
            $restaurant = array();
            $restaurant_id = $request->session()->get('res_id');
            $menu_list = $this->menu->where('restaurant_id', $restaurant_id)->get();
        }
        $category_list = $this->category->get();
        $add_ons = $this->add_ons->where('restaurant_id', $request->session()->get('res_id'))->get();
        $food_quantity = $this->food_quantity->get();

        $restaurant_detail = DB::table('restaurants')->where('id', $request->session()->get('res_id'))->first();

        if ($restaurant_detail->brand_name != 0) {
            $restaurant_id = DB::table('restaurants')->where('brand_name', $restaurant_detail->brand_name)->get();
        } else {
            $restaurant_id = DB::table('restaurants')->where('id', $restaurant_detail->id)->get();
        }
        $res_id = [];
        foreach ($restaurant_id as $ids) {
            $res_id[] = $ids->id;
        }
        //dd($res_id);
        $privilege = $request->session()->get('privilege');
        $permission = permission($privilege, 'product-edit');
        if ($permission == 1 || $privilege == -1) {
            return view('store/add_product', ['res_id' => $res_id, 'category' => $category_list, 'menu' => $menu_list, 'add_ons' => $add_ons, 'restaurant' => $restaurant, 'food_quantity' => $food_quantity]);
        } else {
            return view('store/restricted');
        }
    }

    public function add_to_product(Request $request)
    {

        $restaurant_id = $request->res_id;
        $res_details = DB::table('restaurants')->where('id', $restaurant_id)
            ->first();
        $business = DB::table('business_type')->where('id', $res_details->business_type)
            ->first();

        if ($business->layout_id == 2) {
            $validator_primary = Validator::make($request->all(), [
                'name' => 'required',
                'category' => 'required',
                'business_category_id' => 'required',
                'status' => 'required',
                'price' => 'required',
                'bprice' => 'required',
                'initial_price' => 'required',
                'split_payment' => 'required',
                'tax' => 'required',
                'packaging_charge' => 'required',
            ]);
        } elseif ($business->layout_id == 1) {
            $validator_primary = Validator::make($request->all(), [
                'name' => 'required',
                'business_category_id' => 'required',
                'menu' => 'required',
                'status' => 'required',
                'price' => 'required',
                'bprice' => 'required',
                'initial_price' => 'required',
                'split_payment' => 'required',
                'tax' => 'required',
                'packaging_charge' => 'required',
            ]);
        }
        $toggle_status = (int) $request->toggle_status ?? 0;




        if ($toggle_status == 0) {
            if ($validator_primary->fails()) {

                $error_messages = implode(',', $validator_primary->messages()->all());
                $message = $error_messages;
                $status = false;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }
        } else {
            $validator_secondary = Validator::make($request->all(), [
                'namesecondary' => 'required|max:30',
                //'description' => 'required|max:100',
            ]);

            if ($validator_secondary->fails()) {

                $error_messages = implode(',', $validator_secondary->messages()->all());
                $message = $error_messages;
                $status = false;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }
        }

        // if ($request->session()->get('res_role') == 1) {
        //     $restaurant_id = $request->restaurant_name;
        // } else {
        //     $restaurant_id = $request->session()->get('res_id');
        // }
        if (!empty($request->food_quantity_price)) {
            $food_quantity_price = array_filter($request->food_quantity_price, function ($value) {
                return $value !== '';
            });
        }
        if (!empty($request->food_quantity_desc)) {
            $food_quantity_desc = array_filter($request->food_quantity_desc, function ($value) {
                return $value !== '';
            });
        }

        $name =  $this->secound_lang_name($request->name);
        $namesecondary =  $this->secound_lang_name($request->namesecondary);
        $description = $this->secound_lang_name($request->description);
        if ($description == "undefined") {
            $description = '';
        }
        $descriptionsecondary = $this->secound_lang_name($request->descriptionsecondary);
        if ($descriptionsecondary == "undefined") {
            $descriptionsecondary = '';
        }
        $category = $request->category ?? 0;
        $business_category_id = $request->business_category_id ? $request->business_category_id : '';
        $menu = $request->menu ?? 0;

        $status = $request->status;
        $stockstatus = $request->stockstatus;
        $initial_price = $request->initial_price;
        $split_payment = $request->split_payment;
        $price = $request->price;
        $bprice = $request->bprice;
        $tax = $request->tax;
        $packaging_charge = $request->packaging_charge;
        $food_type = (int) $request->food_type;
        $toggle_status = (int) $request->toggle_status;

        if ($request->id) {
            $foodlist = $this->foodlist->find($request->id);
            $foodlist->restaurant_id = $restaurant_id;
            $foodlist->name = $name;
            $foodlist->secondary_name = $namesecondary;
            $foodlist->description = $description;
            $foodlist->secondary_description = $descriptionsecondary;
            $foodlist->category_id = $category;
            $foodlist->business_category_id = $business_category_id;
            $foodlist->menu_id = $menu;
            $foodlist->status = $status;
            $foodlist->out_of_stock = $stockstatus;
            $foodlist->price = $price;
            $foodlist->bprice = $bprice;
            $foodlist->initial_price = $initial_price;
            $foodlist->split_payment = $split_payment;
            $foodlist->tax = $tax;
            $foodlist->packaging_charge = $packaging_charge;
            $foodlist->is_veg = $food_type;
            $foodlist->status = $status;
            $foodlist->is_secondary = $toggle_status;

            $request->existing = json_decode($request->existing);
            if (count(json_decode($request->image)) > 0) {
                if ($request->existing[$request->featured_image] == "undefined") {
                    $product_image_upload  = basename($request->image);
                    $foodlist->image = $product_image_upload;
                } else {
                    if ($request->existing[$request->featured_image] == 1) {
                        $dec_image = json_decode($request->image);
                        $img = $dec_image[$request->featured_image];
                        $product_image_upload = basename($img);
                        $foodlist->image = $product_image_upload ?  $product_image_upload : $foodlist->image;
                        $foodlist->cloudflare_imageid = null;
                    } else {
                        $product_image_upload = $this->custom->common_upload_images_product($request, 'image', $restaurant_id);
                        $json = json_decode($product_image_upload, true);
                        $foodlist->image = $json['imagename'] ?  $json['imagename'] : $foodlist->image;
                        $foodlist->cloudflare_imageid = $json['id'];
                    }
                }
            }

            $foodlist->save();
            $delete_old_size = DB::table('product_images')->where('product_id', $request->id)->delete();
            $req_images = json_decode($request->image);
            if (count($req_images) > 1) {
                $cou = 0;
                foreach ($req_images as $rm) {

                    if ($cou != $request->featured_image) {

                        if ($request->existing[$cou] == "undefined") {
                            $product_image_upload  = basename($rm);
                        } else {
                            if ($request->existing[$cou] == 1) {
                                $product_image_upload = basename($rm);
                                $image_t = $product_image_upload ?  $product_image_upload : $foodlist->image;
                                $cloudflare_imageid_t = null;
                            } else {
                                $product_image_upload = $this->custom->upload_all_images($request, 'image', $cou, $rm);
                                $json = json_decode($product_image_upload, true);
                                $image_t = $json['imagename'] ?  $json['imagename'] : $foodlist->image;
                                $cloudflare_imageid_t = $json['id'];
                            }
                            DB::table('product_images')->insert([
                                'product_id' => $request->id,
                                'image' => $image_t,
                                'cloudflare_image_id' => $cloudflare_imageid_t,
                            ]);
                        }
                    }
                    $cou = $cou + 1;
                }
            }
            $delete_old_size = DB::table('foodlist_foodquantity')->where('foodlist_id', $request->id)->delete();
            if ($request->food_quantity_id) {
                $food_quantity = $this->food_quantity->find($request->food_quantity_id);
                $food_quantity_count = $food_quantity ? count($food_quantity) : 0;
                $sync_data = array();
                $default = 0;
                for ($i = 0; $i < $food_quantity_count; $i++) {
                    if ($i == 0) {
                        $default = 1;
                    } else {
                        $default = 0;
                    }
                    if ($food_quantity_price[$i]) {
                        if ($food_quantity_desc[$i] == "undefined") {
                            $food_quantity_desc[$i] = '';
                        }
                        $sync_data[$food_quantity[$i]->id] = ['price' => $food_quantity_price[$i], 'size_description' => $food_quantity_desc[$i], 'is_default' => $default];
                        DB::table('foodlist_foodquantity')->insert([
                            'foodlist_id' => $request->id,
                            'foodquantity_id' => $food_quantity[$i]->id,
                            'price' => $food_quantity_price[$i],
                            'size_description' => $food_quantity_desc[$i],
                            'is_default' => $default
                        ]);
                    }
                }
                if ($request->addon_type == 0) {

                    for ($i = 0; $i < $food_quantity_count; $i++) {
                        if ($request->size_addons != null && $request->size_addons[$i] != null) {
                            $j = [];
                            for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
                                $dump = json_decode($request->size_addons[$i]);
                                $ad = json_decode($dump[$k]);
                                $j[] = strval($ad->id);
                            }
                            $array_addon = explode(',', $request->size_addons[$i]);
                            $update = DB::table('foodlist_foodquantity')->where('foodlist_id', $request->id)->where('foodquantity_id', $food_quantity[$i]->id)->update([
                                'addons_id' => json_encode($j)
                            ]);
                            for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
                                $dump = json_decode($request->size_addons[$i]);
                                $ad = json_decode($dump[$k]);
                                $delete_old = DB::table('foodlist_foodquantity_addons')->where('food_list', $request->id)->where('food_quantity', $food_quantity[$i]->id)->where('addon', $ad->id)->delete();
                                if (!(isset($ad->stock))) {
                                    $ad->stock = 0;
                                }
                                $update_add = DB::table('foodlist_foodquantity_addons')->insert([
                                    'food_list' => $request->id,
                                    'food_quantity' => $food_quantity[$i]->id,
                                    'addon' => $ad->id,
                                    'price' => $ad->price,
                                    'stock' => $ad->stock
                                ]);
                            }
                        }
                    }
                } else {
                    for ($i = 0; $i < $food_quantity_count; $i++) {
                        if ($request->size_addons != null && $request->size_addons[$i] != null) {
                            $array_addon = explode(',', $request->size_addons[$i]);
                            $j = [];
                            for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
                                $dump = json_decode($request->size_addons[$i]);
                                $ad = json_decode($dump[$k]);
                                $j[] = strval($ad->id);
                            }
                            $array_addon = explode(',', $request->size_addons[$i]);
                            $update = DB::table('foodlist_foodquantity')->where('foodlist_id', $request->id)->where('foodquantity_id', $food_quantity[$i]->id)->update([
                                'addon_group' => json_encode($j)
                            ]);
                            for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
                                $dump = json_decode($request->size_addons[$i]);
                                $ad = json_decode($dump[$k]);
                                $delete_old = DB::table('foodlist_foodquantity_addons')->where('food_list', $request->id)->where('food_quantity', $food_quantity[$i]->id)->where('group_id', $ad->id)->delete();
                                for ($t = 0; $t < count($ad->group_addons); $t++) {
                                    if (!(isset($ad->group_addons[$t]->stock))) {
                                        $ad->group_addons[$t]->stock = 0;
                                    }
                                    $update_add = DB::table('foodlist_foodquantity_addons')->insert([
                                        'food_list' => $request->id,
                                        'food_quantity' => $food_quantity[$i]->id,
                                        'group_id' => $ad->id,
                                        'addon' => $ad->group_addons[$t]->id,
                                        'price' => $ad->group_addons[$t]->price,
                                        'stock' => $ad->group_addons[$t]->stock
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            $delete_old_adds = DB::table('foodlist_addons')->where('foodlist_id', $request->id)->delete();
            if ($request->addon_type == 1 && $request->groups != null) {
                $group_ar = explode(',', $request->groups);
                for ($i = 0; $i < count($group_ar); $i++) {
                    $add = DB::table('foodlist_addons')->insert([
                        'foodlist_id' => $request->id,
                        'addon_group' => $group_ar[$i]
                    ]);
                }
            }
            if ($request->addon_type == 0 && $request->add_ons != null) {
                $add_ar = explode(',', $request->add_ons);
                $add_ons = $this->add_ons->find($add_ar);
                foreach ($add_ons as $ad) {
                    $add = DB::table('foodlist_addons')->insert([
                        'foodlist_id' => $request->id,
                        'addons_id' => $ad->id
                    ]);
                }
            }

            DB::table('product_specification')->where('product_id', $request->id)->delete();
            DB::table('table_type')->where('product_id', $request->id)->delete();

            $specification = json_decode($request->specification);
            if (count($specification)) {

                foreach ($specification as $specs) {
                    $ins_type = DB::table('table_type')->insertGetId([
                        'name' => $specs->name,
                        'product_id' => $request->id
                    ]);
                    foreach ($specs->items as $its) {
                        DB::table('product_specification')->insert([
                            'table_type' => $ins_type,
                            'product_id' => $request->id,
                            'label' => $its->label,
                            'value' => $its->value

                        ]);
                    }
                }
            }


            DB::table('product_faq')->where('product_id', $request->id)->delete();
            $faq = json_decode($request->faq);
            if (count($faq)) {

                foreach ($faq as $faqs) {
                    DB::table('product_faq')
                        ->insert([
                            'product_id' => $request->id,
                            'question' => $faqs->question,
                            'answer' => $faqs->answer
                        ]);
                }
            }
            $trans_msg = "Product Updated Successfully";
        } else {
            $this->foodlist->is_secondary = $toggle_status;
            if ($toggle_status == 0) {
                $this->foodlist->name = $name;
                $this->foodlist->description = $description;
                $this->foodlist->secondary_name = $namesecondary ?? '';
                $this->foodlist->secondary_description = $descriptionsecondary ?? '';
            } else {
                $this->foodlist->secondary_name = $namesecondary ?? '';
                $this->foodlist->secondary_description = $descriptionsecondary ?? '';
            }
            $this->foodlist->restaurant_id = $restaurant_id;
            $this->foodlist->category_id = $category;
            $this->foodlist->business_category_id = $business_category_id;
            $this->foodlist->menu_id = $menu;
            $this->foodlist->status = $status;
            $this->foodlist->out_of_stock = $stockstatus;
            $this->foodlist->bprice = $bprice;
            $this->foodlist->initial_price = $initial_price;
            $this->foodlist->split_payment = $split_payment;
            $this->foodlist->tax = $tax;
            $this->foodlist->packaging_charge = $packaging_charge;
            $this->foodlist->is_veg = $food_type;
            $this->foodlist->status = $status;
            $request->existing = json_decode($request->existing);
            if (count(json_decode($request->image)) > 0) {

                if ($request->existing[$request->featured_image] == "undefined") {
                    $product_image_upload  = basename($request->image);
                } else {
                    if ($request->existing[$request->featured_image] == 1) {
                        $dec_image = json_decode($request->image);
                        $img = $dec_image[$request->featured_image];
                        $product_image_upload = basename($img);
                        $this->foodlist->image = $product_image_upload ?  $product_image_upload : '';
                        $this->foodlist->cloudflare_imageid = null;
                    } else {
                        $product_image_upload = $this->custom->common_upload_images_product($request, 'image', $restaurant_id);
                        $json = json_decode($product_image_upload, true);
                        $this->foodlist->image = $json['imagename'] ?  $json['imagename'] : $foodlist->image;
                        $this->foodlist->cloudflare_imageid = $json['id'];
                    }
                }
            }
            $this->foodlist->save();
            $req_images = json_decode($request->image);
            if (count($req_images) > 1) {
                $cou = 0;
                foreach ($req_images as $rm) {
                    if ($cou != $request->featured_image) {

                        if ($request->existing[$cou] == "undefined") {
                            $product_image_upload  = basename($rm);
                        } else {
                            if ($request->existing[$cou] == 1) {
                                $product_image_upload = basename($rm);
                                $image_t = $product_image_upload ?  $product_image_upload : $foodlist->image;
                                $cloudflare_imageid_t = null;
                            } else {

                                $product_image_upload = $this->custom->upload_all_images($request, 'image', $cou, $rm);

                                $json = json_decode($product_image_upload, true);
                                $image_t = $json['imagename'] ?  $json['imagename'] : $foodlist->image;
                                $cloudflare_imageid_t = $json['id'];
                            }

                            DB::table('product_images')->insert([
                                'product_id' => $this->foodlist->id,
                                'image' => $image_t,
                                'cloudflare_image_id' => $cloudflare_imageid_t,
                            ]);
                        }
                    }
                    $cou = $cou + 1;
                }
            }


            if ($request->food_quantity_id) {
                $food_quantity = $this->food_quantity->find($request->food_quantity_id);
                $food_quantity_count = $food_quantity ? count($food_quantity) : 0;
                $sync_data = array();
                $default = 0;
                for ($i = 0; $i < $food_quantity_count; $i++) {
                    if ($i == 0) {
                        $default = 1;
                    } else {
                        $default = 0;
                    }
                    if ($food_quantity_price[$i]) {
                        $sync_data[$food_quantity[$i]->id] = ['price' => $food_quantity_price[$i], 'size_description' => $food_quantity_desc[$i], 'is_default' => $default];
                    }
                }
                $this->foodlist->FoodQuantity()->attach($sync_data);
                if ($request->addon_type == 0) {
                    for ($i = 0; $i < $food_quantity_count; $i++) {
                        if ($request->size_addons[$i] != null) {
                            $j = [];
                            for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
                                $dump = json_decode($request->size_addons[$i]);
                                $ad = json_decode($dump[$k]);
                                $j[] = strval($ad->id);
                            }
                            $array_addon = explode(',', $request->size_addons[$i]);
                            $update = DB::table('foodlist_foodquantity')->where('foodlist_id', $this->foodlist->id)->where('foodquantity_id', $food_quantity[$i]->id)->update([
                                'addons_id' => json_encode($j)
                            ]);
                            for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
                                $dump = json_decode($request->size_addons[$i]);
                                $ad = json_decode($dump[$k]);
                                if (!(isset($ad->stock))) {
                                    $ad->stock = 0;
                                }
                                $update_add = DB::table('foodlist_foodquantity_addons')->insert([
                                    'food_list' => $this->foodlist->id,
                                    'food_quantity' => $food_quantity[$i]->id,
                                    'addon' => $ad->id,
                                    'price' => $ad->price,
                                    'stock' => $ad->stock
                                ]);
                            }
                        }
                    }
                } else {
                    for ($i = 0; $i < $food_quantity_count; $i++) {
                        if ($request->size_addons[$i] != null) {
                            $j = [];
                            for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
                                $dump = json_decode($request->size_addons[$i]);
                                $ad = json_decode($dump[$k]);
                                $j[] = strval($ad->id);
                            }
                            $array_addon = explode(',', $request->size_addons[$i]);
                            $update = DB::table('foodlist_foodquantity')->where('foodlist_id', $this->foodlist->id)->where('foodquantity_id', $food_quantity[$i]->id)->update([
                                'addon_group' => json_encode($j)
                            ]);
                            for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
                                $dump = json_decode($request->size_addons[$i]);
                                $ad = json_decode($dump[$k]);
                                for ($t = 0; $t < count($ad->group_addons); $t++) {
                                    if (!(isset($ad->group_addons[$t]->stock))) {
                                        $ad->group_addons[$t]->stock = 0;
                                    }
                                    $update_add = DB::table('foodlist_foodquantity_addons')->insert([
                                        'food_list' => $this->foodlist->id,
                                        'food_quantity' => $food_quantity[$i]->id,
                                        'group_id' => $ad->id,
                                        'addon' => $ad->group_addons[$t]->id,
                                        'price' => $ad->group_addons[$t]->price,
                                        'stock' => $ad->group_addons[$t]->stock
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            $specification = json_decode($request->specification);
            if (count($specification)) {

                foreach ($specification as $specs) {
                    $ins_type = DB::table('table_type')->insertGetId([
                        'name' => $specs->name,
                        'product_id' => $this->foodlist->id
                    ]);
                    foreach ($specs->items as $its) {
                        DB::table('product_specification')->insert([
                            'table_type' => $ins_type,
                            'product_id' => $this->foodlist->id,
                            'label' => $its->label,
                            'value' => $its->value

                        ]);
                    }
                }
            }
            $faq = json_decode($request->faq);
            if (count($faq)) {
                foreach ($faq as $faqs) {
                    DB::table('product_faq')
                        ->insert([
                            'product_id' => $this->foodlist->id,
                            'question' => $faqs->question,
                            'answer' => $faqs->answer
                        ]);
                }
            }
            if ($request->addon_type == 1 && $request->groups != null) {
                $group_ar = explode(',', $request->groups);
                for ($i = 0; $i < count($group_ar); $i++) {
                    $add = DB::table('foodlist_addons')->insert([
                        'foodlist_id' => $this->foodlist->id,
                        'addon_group' => $group_ar[$i]
                    ]);
                }
            }
            if ($request->addon_type == 0 && $request->add_ons != null) {
                $add_ar = explode(',', $request->add_ons);
                $add_ons = $this->add_ons->find($add_ar);
                $this->foodlist->Add_ons()->attach($add_ons);
            }
            $trans_msg = "Product Added Successfully";
        }
        $message = $trans_msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }





    /**
     * edit product page with neccessity data
     *
     * @param array $request, int $id
     *
     * @return view page with array
     */
    public function edit_product_list(Request $request)
    {
        $product_list = DB::table('food_list')->where('id', $request->product_id)->first();
        if ($product_list->name) {
            $product_list->name = $this->secound_name($product_list->name);
        }
        if ($product_list->description) {
            $product_list->description = $this->secound_name($product_list->description);
        }
        if ($product_list->secondary_name) {
            $product_list->secondary_name = $this->secound_name($product_list->secondary_name);
        }
        if ($product_list->secondary_description) {
            $product_list->secondary_description = $this->secound_name($product_list->secondary_description);
        }

        $featured[]  = File::exists(public_path('product_image/') . $product_list->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $product_list->image : (File::exists(public_path('restaurant_uploads/') . $product_list->restaurant_id . '/' . 'Product/' . $product_list->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $product_list->restaurant_id . '/' . 'Product/' . $product_list->image : (File::exists(public_path('common_images/') . $product_list->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $product_list->image : ''));

        $other_images = DB::table('product_images')->where('product_id', $request->product_id)->get();
        if (count($other_images) > 0) {
            foreach ($other_images as $oi) {
                $featured[]  = File::exists(public_path('product_image/') . $oi->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $oi->image : (File::exists(public_path('restaurant_uploads/') . $product_list->restaurant_id . '/' . 'Product/' . $oi->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $product_list->restaurant_id . '/' . 'Product/' . $oi->image : (File::exists(public_path('common_images/') . $oi->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $oi->image : ''));
            }
        }
        $product_list->image = $featured;

        // $product_list->image[] = File::exists(public_path('product_image/') . $product_list->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $product_list->image : (File::exists(public_path('restaurant_uploads/') . $product_list->restaurant_id . '/' . 'Product/' . $product_list->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $product_list->restaurant_id . '/' . 'Product/' . $product_list->image : (File::exists(public_path('common_images/') . $product_list->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $product_list->image : ''));
        $cat_details = $this->category->where('id', $product_list->category_id)->first();
        $product_list->cat_details = $cat_details;
        $this_addon = DB::table('foodlist_addons')
            ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
            ->where('foodlist_id', $request->product_id)
            ->select('foodlist_addons.addons_id as id', 'add_ons.name',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at')
            ->get()->toArray();
        if (!$this_addon) {
            $product_list->add_ons = [];
        } else {
            foreach ($this_addon as $addon_key => $each_addon) {
                $this_addon[$addon_key]->restaurant_id = $product_list->restaurant_id;
                $this_addon[$addon_key]->pivot = ['foodlist_id' => $request->product_id, 'addons_id' => $each_addon->id];
            }
        }
        $product_list->add_ons = $this_addon;
        $this_gaddon = DB::table('foodlist_addons')
            ->where('foodlist_id', $request->product_id)
            ->where('addons_id', null)
            ->get();
        $add_group = [];
        foreach ($this_gaddon as $addon_key => $each_addon) {
            if ($each_addon->addon_group != null) {
                $group = DB::table('addon_group')->where('id', $each_addon->addon_group)->first();
                $array_group = json_decode($group->add_ons);
                foreach ($array_group as $ag) {
                    $addon_detail = DB::table('add_ons')->where('id', $ag)->first();
                    $gadd_ons[] = [
                        'id' => $ag,
                        'name' => $addon_detail->name,
                        'secondary_name' => $addon_detail->secondary_name,
                        'price' => $addon_detail->price
                    ];
                }
                $add_group[] = [
                    'id' => $each_addon->addon_group,
                    'name' => $group->name,
                    'min' => $group->min,
                    'max' => $group->max,
                    'allow_multiple' => $group->allow_multiple,
                    'mandatory' => $group->mandatory,
                    'group_addons' => $gadd_ons
                ];
            }
        }
        $product_list->groups = $add_group;
        $this_size = DB::table('foodlist_foodquantity')
            ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
            ->where('foodlist_id', $request->product_id)
            ->select(
                'foodlist_foodquantity.foodquantity_id as id',
                'food_quantity.name',
                'food_quantity.secondary_name',
                'foodlist_foodquantity.price',
                'foodlist_foodquantity.is_default',
                'foodlist_foodquantity.created_at',
                'foodlist_foodquantity.updated_at',
                'foodlist_foodquantity.addons_id',
                'foodlist_foodquantity.addon_group',
                'foodlist_foodquantity.size_description',
            )
            ->get()->toArray();
        if (!$this_size) {
            $product_list->food_quantity = [];
        } else {
            foreach ($this_size as $size_key => $each_size) {
                if ($each_size->addon_group != null) {
                    $sg = $each_size->addon_group;
                    $sg_group = json_decode($sg);
                    $sgadd_ons = [];
                    $sadd_group = [];
                    foreach ($sg_group as $sgg) {
                        $sgg_group = DB::table('addon_group')->where('id', $sgg)->first();
                        $sarray_group = json_decode($sgg_group->add_ons);
                        foreach ($sarray_group as $sag) {
                            $saddon_detail = DB::table('add_ons')->where('id', $sag)->first();
                            $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $request->product_id)->where('food_quantity', $each_size->id)->where('group_id', $sgg)->where('addon', $sag)->first();
                            if ($variant) {
                                $price = $variant->price;
                                $stock = $variant->stock;
                            } else {
                                $price = $saddon_detail->price;
                                $stock = 0;
                            }
                            $sgadd_ons[] = [
                                'id' => $sag,
                                'name' => $saddon_detail->name,
                                'secondary_name' => $saddon_detail->secondary_name,
                                'price' => $price,
                                'stock' => $stock
                            ];
                        }
                        $sadd_group[] = [
                            'id' => $sgg,
                            'name' => $sgg_group->name,
                            'min' => $sgg_group->min,
                            'max' => $sgg_group->max,
                            'allow_multiple' => $sgg_group->allow_multiple,
                            'mandatory' => $sgg_group->mandatory,
                            'group_addons' => $sgadd_ons
                        ];
                    }
                    $this_size[$size_key]->groups = $sadd_group;
                }
                if ($each_size->addons_id != null) {
                    $sa = $each_size->addons_id;
                    $sa_group = json_decode($sa);
                    $saa_addons = [];
                    foreach ($sa_group as $saa) {
                        $sa_detail = DB::table('add_ons')->where('id', $saa)->first();
                        $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $request->product_id)->where('food_quantity', $each_size->id)->where('addon', (int)$saa)->first();
                        if ($variant) {
                            $price = $variant->price;
                            $stock = $variant->stock;
                        } else {
                            $price = $sa_detail->price;
                            $stock = 0;
                        }
                        $saa_addons[] = [
                            'id' => $saa,
                            'name' => $sa_detail->name,
                            'secondary_name' => $sa_detail->secondary_name,
                            'price' => $price,
                            'stock' => $stock
                        ];
                    }
                    $this_size[$size_key]->add_ons = $saa_addons;
                }
                $this_size[$size_key]->pivot = [
                    'foodlist_id' => $request->product_id,
                    'foodquantity_id' => $each_size->id,
                    'price' => $each_size->price,
                    'is_default' => $each_size->is_default
                ];
            }
        }

        $specs = DB::table('product_specification')->where('product_id', $request->product_id)->get();
        $faq = DB::table('product_faq')->where('product_id', $request->product_id)->get();

        $tables = DB::table('table_type')->where('product_id', $request->product_id)->get();
        $table = [];
        foreach ($tables as $td) {
            $rows = DB::table('product_specification')->where('table_type', $td->id)->get();
            $items = [];
            foreach ($rows as $rw) {
                $items[] = [
                    'label' => $rw->label,
                    'value' => $rw->value
                ];
            }
            $table[] = [
                'name' => $td->name,
                'items' => $items
            ];
        }

        $product_list->food_quantity = $this_size;
        $category = $this->category->get();
        $restaurant = array();
        $menu = $this->menu->where('restaurant_id', $request->res_id)->get();
        $add_ons = $this->add_ons->where('restaurant_id', $request->res_id)->get();
        $food_quantity = $this->food_quantity->get();
        $addon_ids = $foodquantity_ids = array();
        $restaurant_detail = DB::table('restaurants')->where('id', $request->res_id)->first();
        $status = true;
        $response_Array = json_encode(['product_list' => $product_list, 'category' => $category, 'menu' => $menu, 'add_ons' => $add_ons, 'addon_ids' => $addon_ids, 'restaurant' => $restaurant, 'food_quantity' => $food_quantity, 'foodquantity_ids' => $foodquantity_ids, 'status' => $status, 'specs' => $specs, 'faq' => $faq, 'table' => $table]);
        return $response_Array;
    }
    public function update_product_list(Request $request)
    {
        $update = $this->foodlist->find($request->id);
        // $update->name = $request->name;
        //$update->description = $request->description;
        $update->category_id = $request->category;
        $update->menu_id = $request->menu;
        $update->status = $request->status;
        $update->price = $request->price;
        $update->tax = $request->tax;
        $update->packaging_charge = $request->packaging_charge;
        $update->is_secondary = (int) $request->toggle_status;

        if ($request->toggle_status == 0) {
            $update->name = $request->name;
            $update->description = $request->description;
        } else {
            $update->secondary_name = $request->namesecondary;
            $update->secondary_description = $request->descriptionsecondary;
        }

        $update->save();

        return redirect('/store/edit_product_list')->with('success', trans('constants.product_updated_successfully'));
    }

    public function delete_product_list($id)
    {
        $delete = $this->foodlist->where('id', $id)->delete();

        // return back()->with('success', trans('constants.product_deleted_successfully'));
        $message = "Product Deleted Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function restaurant_menu(Request $request)
    {



        $restaurant_id = $request->id;

        $data = DB::table('menu')->where('menu.restaurant_id', $restaurant_id)->get();

        foreach ($data as $d) {
            $d->menu_name = $this->secondLanguage_store($d->menu_name, $d->secondary_menu_name);
        }

        $response_Array = json_encode(['data' => $data]);
        return $response_Array;
    }

    public function add_restaurant_menu(Request $request)
    {

        // $toggle_status = (int) $request->toggle_status;
        // $validator_primary = Validator::make($request->all(), [
        //     'menu_name' => 'required',
        //     'from' => 'required',
        //     'to' => 'required',
        //     //'days'   =>  'required_without_all:sevendays,days',
        //     // 'sevendays' =>  'required_without_all:days,sevendays',
        // ]);

        // if ($toggle_status == 0) {
        //     if ($validator_primary->fails()) {

        //         $error_messages = implode(',', $validator_primary->messages()->all());

        //         return back()->with('error', $error_messages);
        //     }
        // }


        $restaurant_id = $request->res_id;
        $menu_name = $this->secound_lang_name($request->menu_name);
        $secondaryname = $this->secound_lang_name($request->secondary_menu_name);


        $restaurant_menu = $this->menu;

        $data_primary = array();
        $data_secondary = array();

        $data_primary[] = array(
            'restaurant_id' => $restaurant_id,
            'secondary_menu_name' =>  $secondaryname,
            'menu_name' => $menu_name,
        );
        // $data_secondary[] = array(
        //     'restaurant_id' => $restaurant_id,
        //     'secondary_menu_name' => $secondaryname,
        // );

        // if ($toggle_status == 0) {

        //     $check = $restaurant_menu->where('restaurant_id', $restaurant_id)->where('menu_name', $menu_name)->count();
        // } else {
        //     $check = $restaurant_menu->where('restaurant_id', $restaurant_id)->where('secondary_menu_name', $secondaryname)->count();
        //  }

        $check = $restaurant_menu->where('restaurant_id', $restaurant_id)->where('menu_name', $menu_name)->count();
        if ($check == 0) {
            // if ($toggle_status == 0) {
            //     $restaurant_menu->insert($data_primary);
            // } else {
            //     $restaurant_menu->insert($data_secondary);
            // }
            $restaurant_menu->insert($data_primary);
        } else {


            $message = "Menu  Already Exist";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;

            // return redirect('/store/restaurant_menu')->with('error', trans('constants.menu_already_exist'));
        }
        // menu timing starts
        $update = $this->menu->orderBy('id', 'desc')->first();
        $seven = $request->sevendays;
        if ($request->from[0] != null) {

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
                    $from[$i] = '';
                    $toDate[$i] = '';
                }

                DB::table('menu_timing')->insert([
                    'menu_id' => $update->id,
                    'all_days' => $all_days,
                    'available_day' => $available_day,
                    'from_time' => $from[$i] ?  date("G:i", strtotime($from[$i])) : NULL,
                    'to_time' => $toDate[$i] ? date("G:i", strtotime($toDate[$i])) : NULL,
                ]);
            }
            // $menu_time = DB::table('menu_timing')->where('menu_id', $restaurant_menu->id)->get();
        }
        $message = "Menu  Add Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
        // return redirect('/store/restaurant_menu')->with('success', trans('constants.menu_added_successfully'));
    }

    public function edit_menu($id)
    {
        $restaurant_id = $id;

        $data = DB::table('menu')->where('id', $restaurant_id)->first();

        $datamenu = DB::table('menu_timing')->where('menu_id', $data->id)->get();
        if (count($datamenu)) {

            if ($datamenu[0]->all_days != 1) {
                $array =  str_replace(array('["', '"]'), '', $datamenu[0]->available_day);
                $array1 = explode(',', $array);
            } else {
                $array1 = null;
            }
        } else {
            $array1 = null;
        }
        if ($data->menu_name) {
            $data->menu_name =  $this->secound_name($data->menu_name);
        }
        if ($data->secondary_menu_name) {
            $data->secondary_menu_name =  $this->secound_name($data->secondary_menu_name);
        }
        $response_Array = json_encode(['data' => $data, 'datamenu' => $datamenu, 'day' => $array1]);
        return $response_Array;
    }
    public function edit_restaurant_menu(Request $request)
    {
        $menu_name = $this->secound_lang_name($request->menu_name);
        $secondaryname = $this->secound_lang_name($request->secondary_menu_name);

        $update = $this->menu->find($request->id);
        $update->menu_name = $menu_name;
        $update->secondary_menu_name =  $secondaryname ?? '';
        $update->save();

        // menu timing starts

        $seven = $request->sevendays;

        $fromDate = $request->from ? count($request->from) : 0;

        $toDate = $request->to;
        $from = $request->from;
        $menu = $request->menu_time_id;
        $update = DB::table('menu_timing')->where('menu_id', $request->id)->delete();
        for ($i = 0; $i < $fromDate; $i++) {
            if ($request->days == 1) { //if days checked not update days
                $all_days =  $request->days;
                $available_day = NULL;
            } else {
                $all_days =  NUll;
                $available_day = $seven;
            }
            if (($from[$i]) == null) {
                $from[$i] = '';
                $toDate[$i] = '';
            }

            if (!empty($menu)) {
                DB::table('menu_timing')->where('id', $menu[$i])->update([
                    'menu_id' => $request->id,
                    'all_days' => $all_days,
                    'available_day' => $available_day,
                    'from_time' => $from[$i] ?  date("G:i", strtotime($from[$i])) : NULL,
                    'to_time' => $toDate[$i] ? date("G:i", strtotime($toDate[$i])) : NULL,
                ]);
            } else {
                DB::table('menu_timing')->insert([
                    'menu_id' => $request->id,
                    'all_days' => $all_days,
                    'available_day' => $available_day,
                    'from_time' =>  $from[$i] ?  date("G:i", strtotime($from[$i])) : NULL,
                    'to_time' => $toDate[$i] ? date("G:i", strtotime($toDate[$i])) : NULL,
                ]);
            }
        }
        $message = "Menu  Updated Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
        // return redirect('/store/restaurant_menu')->with('success', trans('constants.menu_updated_successfully'));
    }

    public function del_restaurant_menu($id)
    {
        $data = DB::table('food_list')
            ->where('menu_id', $id)
            ->get();

        if (count($data) > 0) {
            $message = "Menu  Can't Delete";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $id]);
            return $response_Array;
        } else {


            $delete = $this->menu->where('id', $id)->delete();
            $message = "Menu Deleted Successfully";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $id]);
            return $response_Array;
        }

        // return back()->with('success', 'Menu Deleted Successfully');

    }

    public function dispatcher(Request $request)
    {

        $restaurant_id = $request->session()->get('res_id');
        $pending_orders = DB::table('requests')
            ->where('requests.restaurant_id', $restaurant_id)
            ->where('status', 0)
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('users.name as customer_name', 'users.phone as phone', 'requests.id as request_id', 'users.*', 'requests.*')
            ->get();

        $accepted_orders = DB::table('requests')
            ->where('requests.restaurant_id', $restaurant_id)
            ->where('status', 1)
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('users.name as customer_name', 'users.phone as phone', 'users.*', 'requests.*')
            ->get();

        $ongoing_orders = DB::table('requests')
            ->where('requests.restaurant_id', $restaurant_id)
            ->whereIn('status', [2, 3, 4, 5])
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('users.name as customer_name', 'users.phone as phone', 'users.*', 'requests.*')
            ->get();

        $completed_orders = DB::table('requests')
            ->where('requests.restaurant_id', $restaurant_id)
            ->where('status', 7)
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('users.name as customer_name', 'users.phone as phone', 'users.*', 'requests.*')
            ->get();

        $data1 = DB::table('requests')->where('requests.restaurant_id', $restaurant_id)
            ->join('request_detail', 'request_detail.request_id', '=', 'requests.id')
            ->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
            ->select('food_list.name as food_name', 'request_detail.*', 'food_list.*', 'requests.id as request_id')
            ->get();

        return view('/dispatcher', ['pending_orders' => $pending_orders, 'accepted_orders' => $accepted_orders, 'ongoing_orders' => $ongoing_orders, 'completed_orders' => $completed_orders, 'data1' => $data1]);
    }

    public function restaurant_report(Request $request)
    {
        // $restaurant_id = $request->session()->get('res_id');
        // if ($restaurant_id != null) {

        //     $restaurant_details = DB::table('requests')
        //         ->where('requests.restaurant_id', $restaurant_id)
        //         ->join('users', 'users.id', '=', 'requests.user_id')
        //         ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
        //         ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*')
        //         ->get();
        // } else {

        //     $restaurant_details = DB::table('requests')

        //         ->join('users', 'users.id', '=', 'requests.user_id')
        //         ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
        //         ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*')
        //         ->get();
        // }

        // $month = date('m');
        // $prev_month = date('m', strtotime("-1 month"));

        // $current_month = DB::table('requests')
        //     ->where('requests.restaurant_id', $restaurant_id)
        //     ->whereMonth('requests.created_at', '=', $month)
        //     ->select('restaurant_commision')->sum('restaurant_commision');
        // //print_r($current_month);exit();
        // $last_month = DB::table('requests')
        //     ->where('requests.restaurant_id', $restaurant_id)
        //     ->whereMonth('requests.created_at', '=', $prev_month)
        //     ->select('restaurant_commision')->sum('restaurant_commision');

        // $current_week = DB::table('requests')
        //     ->where('requests.restaurant_id', $restaurant_id)
        //     ->whereBetween('requests.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        //     ->select('restaurant_commision')->sum('restaurant_commision');

        // $previous_week = strtotime("-1 week +1 day");

        // $start_week = strtotime("last sunday midnight", $previous_week);
        // $end_week = strtotime("next saturday", $start_week);
        // $last_week_start = date('Y-m-d', $start_week) . " 00:00:00";
        // $last_week_end = date('Y-m-d', $end_week) . " 23:59:59";

        // $last_week = DB::table('requests')
        //     ->where('requests.restaurant_id', $restaurant_id)
        //     ->whereBetween('requests.created_at', [$last_week_start, $last_week_end])
        //     ->select('restaurant_commision')->sum('restaurant_commision');

        // $year = date('Y');
        // $prev_year1 = date('Y', strtotime("-1 month"));
        // $prev_year2 = date('Y', strtotime("-1 year"));

        // $current_year = DB::table('requests')
        //     ->where('requests.restaurant_id', $restaurant_id)
        //     ->whereYear('requests.created_at', '=', $year)
        //     ->select('restaurant_commision')->sum('restaurant_commision');

        // $last_year = DB::table('requests')
        //     ->where('requests.restaurant_id', $restaurant_id)
        //     ->whereYear('requests.created_at', '=', $prev_year2)
        //     ->select('restaurant_commision')->sum('restaurant_commision');
        // $privilege = $request->session()->get('privilege');
        // $permission = permission($privilege, 'report-view');
        // if ($permission == 1 || $privilege == -1) {
        //     return view('store/restaurant_report', ['restaurant_details' => $restaurant_details, 'current_month' => $current_month, 'last_month' => $last_month, 'current_week' => $current_week, 'last_week' => $last_week, 'current_year' => $current_year, 'last_year' => $last_year]);
        // } else {
        //     return view('store/restricted');
        // }

        $restaurant_id = $request->id;
        if ($restaurant_id != null) {

            $restaurant_details = DB::table('requests')
                ->where('requests.restaurant_id', $restaurant_id)
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*')
                ->get();
        } else {

            $restaurant_details = DB::table('requests')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*')
                ->get();
        }

        return response()->json($restaurant_details);
    }

    public function orderwise_report_pagination(Request $request)
    {
        $restaurant_id = $request->session()->get('res_id');
        $role = $request->session()->get('res_role');

        $query = $this->foodrequest->query();
        $query = $query->when(($role != 1),
            function ($q) use ($restaurant_id) {
                return $q->where('restaurant_id', $restaurant_id);
            }
        );

        $limit = $request->iDisplayLength;
        $offset = $request->iDisplayStart;
        //check limit and offset
        $query = $query->when(($limit != '-1' && isset($offset)),
            function ($q) use ($limit, $offset) {
                return $q->offset($offset)->limit($limit);
            }
        );

        $orderwise_details = $query->get();

        $query1 = $this->foodrequest->query();
        $query1 = $query1->when(($role != 1),
            function ($q) use ($restaurant_id) {
                return $q->where('restaurant_id', $restaurant_id);
            }
        );
        $total_orders = $query1->get();

        $column = array();
        $data = array();
        foreach ($orderwise_details as $key => $value) {
            switch ((int) $value->status) {
                case 0:
                    $status = 'New Order';
                    break;
                case 1:
                    $status = 'Order Accepted';
                    break;
                case 2:
                    $status = 'Delivery boy assigned';
                    break;
                case 3:
                    $status = 'Food delivered to Delivery boy';
                    break;
                case 4:
                    $status = 'Towards Customer';
                    break;
                case 5:
                    $status = 'Reached Customer';
                    break;
                case 6:
                    $status = 'Delivered to Customer';
                    break;
                case 7:
                    $status = 'Completed';
                    break;

                default:
                    $status = ' Cancelled';
                    break;
            }

            $col['id'] = $value->id;
            $col['order_id'] = $value->order_id;
            $col['customer_name'] = isset($value->Users) ? $value->Users->name : "";
            $col['customer_phone'] = isset($value->Users) ? $value->Users->phone : "";
            $col['delivery_boy_name'] = isset($value->Deliverypartners) ? $value->Deliverypartners->name : "";
            $col['delivery_boy_phone'] = isset($value->Deliverypartners) ? $value->Deliverypartners->phone : "";
            $col['restaurant_name'] = isset($value->Restaurants) ? $value->Restaurants->restaurant_name : "";
            $col['item_total'] = $value->item_total;
            $col['tax'] = DEFAULT_CURRENCY_SYMBOL . $value->tax;
            $col['offer_discount'] = DEFAULT_CURRENCY_SYMBOL . $value->offer_discount;
            $col['admin_commision'] = DEFAULT_CURRENCY_SYMBOL . $value->admin_commision;
            $col['delivery_boy_commision'] = DEFAULT_CURRENCY_SYMBOL . $value->delivery_boy_commision;
            $col['restaurant_commision'] = DEFAULT_CURRENCY_SYMBOL . $value->restaurant_commision;
            $col['status'] = $status;

            array_push($column, $col);
        }
        $orderwise_details['sEcho'] = $request->sEcho;
        $orderwise_details['aaData'] = $column;
        $orderwise_details['iTotalRecords'] = count($total_orders);
        $orderwise_details['iTotalDisplayRecords'] = count($total_orders);

        return json_encode($orderwise_details);
    }

    public function restaurant_report_filter(Request $request)
    {

        $start = $request->start . ' 00:00:00';
        $end = $request->end . ' 23:59:59';
        $restaurant_id = $request->session()->get('res_id');
        if ($restaurant_id != null) {

            $restaurant_details = DB::table('requests')
                ->where('requests.restaurant_id', $restaurant_id)
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*')
                ->whereBetween('requests.created_at', [$start, $end])
                ->get();
        } else {

            $restaurant_details = DB::table('requests')

                ->join('users', 'users.id', '=', 'requests.user_id')
                ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*')
                ->whereBetween('requests.created_at', [$start, $end])
                ->get();
        }

        $month = date('m');
        $prev_month = date('m', strtotime("-1 month"));

        $current_month = DB::table('requests')
            ->where('requests.restaurant_id', $restaurant_id)
            ->whereMonth('requests.created_at', '=', $month)
            ->select('restaurant_commision')->sum('restaurant_commision');
        //print_r($current_month);exit();
        $last_month = DB::table('requests')
            ->where('requests.restaurant_id', $restaurant_id)
            ->whereMonth('requests.created_at', '=', $prev_month)
            ->select('restaurant_commision')->sum('restaurant_commision');

        $current_week = DB::table('requests')
            ->where('requests.restaurant_id', $restaurant_id)
            ->whereBetween('requests.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select('restaurant_commision')->sum('restaurant_commision');

        $previous_week = strtotime("-1 week +1 day");

        $start_week = strtotime("last sunday midnight", $previous_week);
        $end_week = strtotime("next saturday", $start_week);
        $last_week_start = date('Y-m-d', $start_week) . " 00:00:00";
        $last_week_end = date('Y-m-d', $end_week) . " 23:59:59";

        $last_week = DB::table('requests')
            ->where('requests.restaurant_id', $restaurant_id)
            ->whereBetween('requests.created_at', [$last_week_start, $last_week_end])
            ->select('restaurant_commision')->sum('restaurant_commision');

        $year = date('Y');
        $prev_year1 = date('Y', strtotime("-1 month"));
        $prev_year2 = date('Y', strtotime("-1 year"));

        $current_year = DB::table('requests')
            ->where('requests.restaurant_id', $restaurant_id)
            ->whereYear('requests.created_at', '=', $year)
            ->select('restaurant_commision')->sum('restaurant_commision');

        $last_year = DB::table('requests')
            ->where('requests.restaurant_id', $restaurant_id)
            ->whereYear('requests.created_at', '=', $prev_year2)
            ->select('restaurant_commision')->sum('restaurant_commision');

        return view('store/restaurant_report', ['restaurant_details' => $restaurant_details, 'current_month' => $current_month, 'last_month' => $last_month, 'current_week' => $current_week, 'last_week' => $last_week, 'current_year' => $current_year, 'last_year' => $last_year]);
    }

    public function admin_restaurant_report(Request $request)
    {

        $restaurant_details = $this->restaurants
            ->join('add_city', 'add_city.id', '=', 'restaurants.city')
            ->join('add_area', 'add_area.id', '=', 'restaurants.area')
            ->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
            ->get();

        // ->join('restaurants','restaurants.id','=','requests.restaurant_id')

        // ->select('requests.*','restaurants.*','restaurants.restaurant_name as restaurant_name','restaurants.id as res_id')

        // ->get();

        //  $restaurant_details = DB::table('restaurants')->select('restaurant_name as restaurant_name','id')->get();

        foreach ($restaurant_details as $key => $value) {
            $value->restaurant_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('restaurant_commision');
            $value->admin_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('admin_commision');

            //print_r($value->delivery_boy);exit();

        }

        // $tempArray = array();
        // $result = array();
        // foreach ($restaurant_details as $key => $value) {
        //     if (!in_array($value->restaurant_id, $tempArray))
        //     {
        //       array_push($tempArray,$value->restaurant_id);
        //       array_push($result,$value);
        //     }
        // }
        // $restaurant_details = $result;

        $month = date('m');
        $prev_month = date('m', strtotime("-1 month"));

        $current_month = DB::table('requests')

            ->whereMonth('requests.created_at', '=', $month)
            ->select('admin_commision')->sum('admin_commision');
        //print_r($current_month);exit();
        $last_month = DB::table('requests')

            ->whereMonth('requests.created_at', '=', $prev_month)
            ->select('admin_commision')->sum('admin_commision');

        $current_week = DB::table('requests')

            ->whereBetween('requests.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select('admin_commision')->sum('admin_commision');

        $previous_week = strtotime("-1 week +1 day");

        $start_week = strtotime("last sunday midnight", $previous_week);
        $end_week = strtotime("next saturday", $start_week);
        $last_week_start = date('Y-m-d', $start_week) . " 00:00:00";
        $last_week_end = date('Y-m-d', $end_week) . " 23:59:59";

        $last_week = DB::table('requests')

            ->whereBetween('requests.created_at', [$last_week_start, $last_week_end])
            ->select('admin_commision')->sum('admin_commision');

        $year = date('Y');
        $prev_year1 = date('Y', strtotime("-1 month"));
        $prev_year2 = date('Y', strtotime("-1 year"));

        $current_year = DB::table('requests')

            ->whereYear('requests.created_at', '=', $year)
            ->select('admin_commision')->sum('admin_commision');

        $last_year = DB::table('requests')

            ->whereYear('requests.created_at', '=', $prev_year2)
            ->select('admin_commision')->sum('admin_commision');

        return view('store/admin_restaurant_report', ['restaurant_details' => $restaurant_details, 'current_month' => $current_month, 'last_month' => $last_month, 'current_week' => $current_week, 'last_week' => $last_week, 'current_year' => $current_year, 'last_year' => $last_year]);
    }

    public function admin_report_view($res_id)
    {
        $admin_view = DB::table('restaurants')->where('id', $res_id)->get();

        $restaurant_total_earnings = DB::table('requests')
            ->where('restaurant_id', $res_id)
            ->where('status', '=', 7)
            ->select('restaurant_commision')->sum('restaurant_commision');

        //print_r($vendor_total_earnings);exit();
        $restaurant_pending_payouts = DB::table('requests')
            ->where('restaurant_id', $res_id)
            ->whereIn('status', [0, 1, 2, 3, 4, 5])
            ->select('restaurant_commision')->sum('restaurant_commision');

        $restaurant_admin_earnings = DB::table('requests')
            ->where('restaurant_id', $res_id)
            ->select('admin_commision')->sum('admin_commision');

        return view('store/admin_report_view', ['admin_view' => $admin_view, 'restaurant_total_earnings' => $restaurant_total_earnings, 'restaurant_pending_payouts' => $restaurant_pending_payouts, 'restaurant_admin_earnings' => $restaurant_admin_earnings]);
    }

    public function delivery_boy_report(Request $request)
    {

        $delivery_boy_details = DB::table('delivery_partners')
            ->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
            ->join('add_city', 'add_city.id', '=', 'delivery_partner_details.city')

            ->join('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
            ->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
            ->get();

        //
        //print_r($delivery_boy_details);exit();

        // $delivery_boy_details = DB::table('delivery_partners')->select('name as delivery_boy_name','id')->get();

        //  foreach ($delivery_boy_details as $key => $value) {
        //   $value->delivery_boy_commision = DB::table('requests')->where('delivery_boy_id',$value->id)->sum('delivery_boy_commision');
        //   $value->admin_commision = DB::table('requests')->where('delivery_boy_id',$value->id)->sum('admin_commision');

        //   //print_r($value->delivery_boy);exit();

        // }

        $month = date('m');
        $prev_month = date('m', strtotime("-1 month"));

        $current_month = DB::table('requests')

            ->whereMonth('requests.created_at', '=', $month)
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');
        //print_r($current_month);exit();
        $last_month = DB::table('requests')

            ->whereMonth('requests.created_at', '=', $prev_month)
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');

        $current_week = DB::table('requests')

            ->whereBetween('requests.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');

        $previous_week = strtotime("-1 week +1 day");

        $start_week = strtotime("last sunday midnight", $previous_week);
        $end_week = strtotime("next saturday", $start_week);
        $last_week_start = date('Y-m-d', $start_week) . " 00:00:00";
        $last_week_end = date('Y-m-d', $end_week) . " 23:59:59";

        $last_week = DB::table('requests')

            ->whereBetween('requests.created_at', [$last_week_start, $last_week_end])
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');

        $year = date('Y');
        $prev_year1 = date('Y', strtotime("-1 month"));
        $prev_year2 = date('Y', strtotime("-1 year"));

        $current_year = DB::table('requests')

            ->whereYear('requests.created_at', '=', $year)
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');

        $last_year = DB::table('requests')

            ->whereYear('requests.created_at', '=', $prev_year2)
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');

        return view('store/delivery_boy_reports', ['delivery_boy_details' => $delivery_boy_details, 'current_month' => $current_month, 'last_month' => $last_month, 'current_week' => $current_week, 'last_week' => $last_week, 'current_year' => $current_year, 'last_year' => $last_year]);
    }
    public function deliveryboy_report_pagination(Request $request)
    {
        //   $restaurant_details = $this->restaurants
        //                        ->join('add_city','add_city.id','=','restaurants.city')
        //                        ->join('add_area','add_area.id','=','restaurants.area')
        //                        ->select('restaurants.*','add_city.city as city','add_area.area as area')
        //                       ->get();
        //dd($request->all());

        $query = $this->deliverypartners->query();
        $limit = $request->iDisplayLength;
        $offset = $request->iDisplayStart;
        //print_r($offset);exit();
        //check limit and offset
        $query = $query->when(($limit != '-1' && isset($offset)),
            function ($q) use ($limit, $offset) {
                return $q->offset($offset)->limit($limit);
            }
        );

        $deliveryboy_report_details = $query->get();
        //dd($deliveryboy_report_details);
        $total_deliveryboys = $this->deliverypartners->get();

        $column = array();
        $data = array();
        foreach ($deliveryboy_report_details as $key => $value) {

            $res_id = $value->id;
            $total_orders = $this->foodrequest->where('delivery_boy_id', $res_id)->count();
            $ratings = $this->order_ratings->with('Foodrequest')
                ->wherehas('Foodrequest', function ($q) use ($res_id) {
                    $q->where('delivery_boy_id', $res_id);
                })
                ->avg('restaurant_rating');
            $payout_done = $this->driver_payout_history->where('delivery_boy_id', $value->id)->sum('payout_amount');

            $col['id'] = $value->id;
            $col['name'] = $value->name;
            $col['email'] = $value->email;
            $col['phone'] = $value->phone;
            //if(isset($value->Deliverypartner_detail->Citylist)) print_r($value->Deliverypartner_detail->Citylist);
            $col['city'] = isset($value->Deliverypartner_detail->Citylist) ? $value->Deliverypartner_detail->Citylist->city : "";
            $col['vehicle_name'] = isset($value->Deliverypartner_detail->Vehicle) ? $value->Deliverypartner_detail->Vehicle->vehicle_name : "";
            $col['address_line_1'] = isset($value->Deliverypartner_detail->address_line_1) ? $value->Deliverypartner_detail->address_line_1 : "";
            $col['ratings'] = $ratings;
            $col['total_orders'] = $total_orders;
            $col['total_earnings'] = DEFAULT_CURRENCY_SYMBOL . round($value->total_earnings, 2);
            $col['pending_payout'] = DEFAULT_CURRENCY_SYMBOL . $value->pending_payout;
            $col['payout_done'] = DEFAULT_CURRENCY_SYMBOL . $value->payout_done;

            array_push($column, $col);
        }
        $deliveryboy_report_details['sEcho'] = $request->sEcho;
        $deliveryboy_report_details['aaData'] = $column;
        $deliveryboy_report_details['iTotalRecords'] = count($total_deliveryboys);
        $deliveryboy_report_details['iTotalDisplayRecords'] = count($total_deliveryboys);

        return json_encode($deliveryboy_report_details);
    }

    public function delivery_boy_report_filter(Request $request)
    {
        $start = $request->start . ' 00:00:00';
        $end = $request->end . ' 23:59:59';

        $delivery_boy_details = DB::table('delivery_partners')
            ->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
            ->join('add_city', 'add_city.id', '=', 'delivery_partner_details.city')

            ->join('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
            ->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
            ->whereBetween('delivery_partners.created_at', [$start, $end])
            ->get();

        //
        //print_r($delivery_boy_details);exit();

        // $delivery_boy_details = DB::table('delivery_partners')->select('name as delivery_boy_name','id')->get();

        //  foreach ($delivery_boy_details as $key => $value) {
        //   $value->delivery_boy_commision = DB::table('requests')->where('delivery_boy_id',$value->id)->sum('delivery_boy_commision');
        //   $value->admin_commision = DB::table('requests')->where('delivery_boy_id',$value->id)->sum('admin_commision');

        //   //print_r($value->delivery_boy);exit();

        // }

        $month = date('m');
        $prev_month = date('m', strtotime("-1 month"));

        $current_month = DB::table('requests')

            ->whereMonth('requests.created_at', '=', $month)
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');
        //print_r($current_month);exit();
        $last_month = DB::table('requests')

            ->whereMonth('requests.created_at', '=', $prev_month)
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');

        $current_week = DB::table('requests')

            ->whereBetween('requests.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');

        $previous_week = strtotime("-1 week +1 day");

        $start_week = strtotime("last sunday midnight", $previous_week);
        $end_week = strtotime("next saturday", $start_week);
        $last_week_start = date('Y-m-d', $start_week) . " 00:00:00";
        $last_week_end = date('Y-m-d', $end_week) . " 23:59:59";

        $last_week = DB::table('requests')

            ->whereBetween('requests.created_at', [$last_week_start, $last_week_end])
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');

        $year = date('Y');
        $prev_year1 = date('Y', strtotime("-1 month"));
        $prev_year2 = date('Y', strtotime("-1 year"));

        $current_year = DB::table('requests')

            ->whereYear('requests.created_at', '=', $year)
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');

        $last_year = DB::table('requests')

            ->whereYear('requests.created_at', '=', $prev_year2)
            ->select('delivery_boy_commision')->sum('delivery_boy_commision');

        return view('store/delivery_boy_reports', ['delivery_boy_details' => $delivery_boy_details, 'current_month' => $current_month, 'last_month' => $last_month, 'current_week' => $current_week, 'last_week' => $last_week, 'current_year' => $current_year, 'last_year' => $last_year]);
    }

    public function restaurant_report_pagination(Request $request)
    {
        //   $restaurant_details = $this->restaurants
        //                        ->join('add_city','add_city.id','=','restaurants.city')
        //                        ->join('add_area','add_area.id','=','restaurants.area')
        //                        ->select('restaurants.*','add_city.city as city','add_area.area as area')
        //                       ->get();
        //dd($request->all());

        $query = $this->restaurants->with(['city_list', 'Area']);
        $limit = $request->iDisplayLength;
        $offset = $request->iDisplayStart;
        //check limit and offset
        $query = $query->when(($limit != '-1' && isset($offset)),
            function ($q) use ($limit, $offset) {
                return $q->offset($offset)->limit($limit);
            }
        );

        $restaurant_details = $query->get();

        $total_restaurant = $this->restaurants->get();

        foreach ($restaurant_details as $key => $value) {
            $value->restaurant_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('restaurant_commision');
            $value->admin_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('admin_commision');
        }

        $column = array();
        $data = array();
        foreach ($restaurant_details as $key => $value) {
            $res_id = $value->id;
            $total_orders = $this->foodrequest->where('restaurant_id', $res_id)->count();
            $ratings = $this->order_ratings->with('Foodrequest')
                ->wherehas('Foodrequest', function ($q) use ($res_id) {
                    $q->where('restaurant_id', $res_id);
                })
                ->avg('restaurant_rating');
            $payout_done = $this->restaurant_payout_history->where('restaurant_id', $value->id)->sum('payout_amount');
            $col['id'] = $value->id;
            $col['restaurant_name'] = $value->restaurant_name;
            $col['email'] = $value->email;
            $col['phone'] = $value->phone;
            $col['rating'] = $ratings;
            $col['address'] = $value->address;
            $col['city'] = isset($value->city_list) ? $value->city_list->city : "";
            $col['area'] = isset($value->Area) ? $value->Area->area : "";
            $col['total_orders'] = $total_orders;
            $col['restaurant_commision'] = DEFAULT_CURRENCY_SYMBOL . round($value->restaurant_commision, 2);
            $col['pending_payout'] = DEFAULT_CURRENCY_SYMBOL . $value->pending_payout;
            $col['payout_done'] = DEFAULT_CURRENCY_SYMBOL . $payout_done;

            array_push($column, $col);
        }
        $restaurant_details['sEcho'] = $request->sEcho;
        $restaurant_details['aaData'] = $column;
        $restaurant_details['iTotalRecords'] = count($total_restaurant);
        $restaurant_details['iTotalDisplayRecords'] = count($total_restaurant);

        return json_encode($restaurant_details);
    }

    public function city_management(Request $request)
    {
        $country = $this->country->get();

        return view('store/add_city', compact('country'));
    }

    public function add_city(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'city' => 'required',
            'admin_commision' => 'required',
            'country' => 'required',
            'state' => 'required',
            'status' => 'required',
            'geofence_latlng' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());
            // dd($error_messages);
            //print_r($validator->messages()); exit;
            return back()->with('error', $error_messages)->withInput();
            // return back()->withErrors($validator);

        } else {

            /*if(!empty($request->geofence_latlng))
            {
            $source_polygon = explode(',',$request->geofence_latlng);
            $j = $k =0;
            for ($i=0; $i <= count($source_polygon)-1 && $k <= count($source_polygon)-1; $i++) {
            $source_coordinates[$j][0] = $source_polygon[$k++];
            $source_coordinates[$j][0] = (double)trim($source_coordinates[$j][0],'[ ');
            $source_coordinates[$j][1] = $source_polygon[$k];
            $source_coordinates[$j][1] = (double)trim($source_coordinates[$j][1],' ]');

            $temp = $source_coordinates[$j][0];
            $source_coordinates[$j][0] = $source_coordinates[$j][1];
            $source_coordinates[$j][1] = $temp;
            $k++;$j++;
            }
            } else {
            $source_coordinates = array();
            }*/
            //echo "<pre>";print_r( $source_coordinates);
            // $first_cordinate = current($source_coordinates);
            // $a = end($source_coordinates);
            // $key = key($source_coordinates);
            // $source_coordinates[$key+1] = $first_cordinate;
            //echo "<pre>";print_r( $source_coordinates);exit;
            $check = DB::table('add_city')->where('city', '=', $request->city)->first();

            if (!$check) {
                $city = $request->city;
                $country_id = $request->country;
                $state_id = $request->state;
                $admin_commision = $request->admin_commision;
                $default_delivery_amount = $request->default_delivery_amount;
                $target_amount = $request->target_amount;
                $driver_base_price = $request->driver_base_price;
                $min_dist_base_price = $request->min_dist_base_price;
                $extra_fee_amount = $request->extra_fee_amount;
                $extra_fee_amount_each = $request->extra_fee_amount_each;
                $night_fare_amount = $request->night_fare_amount;
                $night_driver_share = $request->night_driver_share;
                $surge_fare_amount = $request->surge_fare_amount;
                $surge_driver_share = $request->surge_driver_share;
                $status = $request->status;
                $add_city = $this->addcity;

                $data = array();

                // $data[] = array(

                //     'city'=>$city,
                //     'admin_commision'=>$admin_commision,
                //     'default_delivery_amount'=>$default_delivery_amount,
                //     'target_amount'=>$target_amount,
                //     'driver_base_price'=>$driver_base_price,
                //     'min_dist_base_price'=>$min_dist_base_price,
                //     'extra_fee_amount'=>$extra_fee_amount,
                //     'extra_fee_amount_each'=>$extra_fee_amount_each,
                //     'night_fare_amount'=>$night_fare_amount,
                //     'night_driver_share'=>$night_driver_share,
                //     'surge_fare_amount'=>$surge_fare_amount,
                //     'surge_driver_share'=>$surge_driver_share,
                //     'status'=>$status,

                // );
                $add_city->city = $city;
                $add_city->country_id = $country_id;
                $add_city->state_id = $state_id;
                $add_city->admin_commision = $admin_commision;
                $add_city->default_delivery_amount = $default_delivery_amount;
                $add_city->target_amount = $target_amount;
                $add_city->driver_base_price = $driver_base_price;
                $add_city->min_dist_base_price = $min_dist_base_price;
                $add_city->extra_fee_amount = $extra_fee_amount;
                $add_city->extra_fee_amount_each = $extra_fee_amount_each;
                $add_city->night_fare_amount = $night_fare_amount;
                $add_city->night_driver_share = $night_driver_share;
                $add_city->surge_fare_amount = $surge_fare_amount;
                $add_city->surge_driver_share = $surge_driver_share;
                $add_city->status = $status;
                $city_data = $add_city->save();

                //add data in city_geofencing table
                $geofencing = new City_geofencing();
                $geofencing->city_id = $add_city->id;
                $geofencing->latitude = $request->latitude;
                $geofencing->longitude = $request->longitude;
                $geofencing->polygons = $request->geofence_latlng;
                //$geofencing->save();
                $geofencing->save();
                //$data = $city_id->city_geofencing()->create($geofencing);
            } else {

                return back()->with('error', trans('constants.this_city_was_already_registered'));
            }
        }

        return redirect('/store/city_list')->with('success', trans('constants.city_added_successfully'));
    }

    public function city_list()
    {
        $city_list = DB::table('add_city')->get();

        return response()->json($city_list);
    }
    public function area_list1(Request $request)
    {

        $area_list = DB::table('add_area')->where('add_city_id', $request->city)->get();
        //  dd( $res_list);
        return response()->json($area_list);
    }

    /**
     * add area view page
     *
     * @param int $id
     *
     * @return view page with array $area, int $id
     */
    public function area_setting($id)
    {
        $area = DB::table('add_city')->where('id', $id)->first();
        //print_r($area);exit();

        return view('store/add_areas', ['area' => $area, 'city_id' => $id]);
    }

    /**
     * edit area view page
     *
     * @param int $id
     *
     * @return view page with array $area
     */
    public function edit_area($id)
    {
        $area = $this->addarea->find($id);
        //print_r($area);exit();

        return view('store/edit_areas', ['area' => $area]);
    }

    /**
     * store area and return to view page
     *
     * @param object $request
     *
     * @return view page
     */
    public function add_area(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area' => 'required',
            'status' => 'required',
            'geofence_latlng' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('error', $error_messages)->withInput();
        } else {
            $check = $this->addarea->where('area', '=', $request->area)->first();
            if (isset($check) == 0) {
                $add_city_id = $request->id;
                $area = $request->area;
                $status = $request->status;
                $add_area = $this->addarea;
                $data = array();

                $data[] = array(

                    'area' => $area,
                    'status' => $status,
                    'add_city_id' => $add_city_id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'polygons' => $request->geofence_latlng,

                );
                $add_area->insert($data);
            } else {

                return back()->with('error', trans('constants.this_area_was_already_registered'));
            }
        }

        return redirect('store/view_areas/' . $request->id)->with('success', trans('constants.add_success_msg', ['param' => 'Area']));
    }

    /**
     * get area list and return to view page
     *
     * @param int $id
     *
     * @return view page with array $area_list, int $id
     */
    public function area_list($id)
    {
        $area_list = DB::table('add_area')->where('add_city_id', $id)->get();

        return view('store/view_areas', ['area_list' => $area_list, 'city_id' => $id]);
    }
    /**
     * update area list
     *
     * @param object $request
     *
     * @return view page
     */
    public function update_area_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area' => 'required',
            'status' => 'required',
            'geofence_latlng' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('error', $error_messages)->withInput();
        } else {
            $area = $this->addarea->find($request->id);
            $area->area = $request->area;
            $area->status = $request->status;
            $area->latitude = $request->latitude;
            $area->longitude = $request->longitude;
            $area->polygons = $request->geofence_latlng;
            $area->save();

            return redirect('/store/view_areas/' . $area->add_city_id)->with('success', trans('constants.update_success_msg', ['param' => 'Area']));
        }
    }

    public function delete_area_list($id)
    {
        $delete = $this->addarea->where('id', $id)->delete();

        return back()->with('success', trans('constants.area_deleted_successfully'));
    }

    public function document_management()
    {

        return view('store/add_document');
    }

    public function document_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_for' => 'required',
            'document_name' => 'required',
            'expiry_date_needed' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            return back()->with('error', $error_messages);
        } else {
            $document_for = $request->document_for;
            $document_name = $request->document_name;
            $expiry_date_needed = $request->expiry_date_needed;

            $status = $request->status;
            $add_document = $this->document;

            $data = array();

            $data[] = array(
                'document_for' => $document_for,
                'document_name' => $document_name,
                'expiry_date_needed' => $expiry_date_needed,
                'status' => $status,

            );

            $add_document->insert($data);
        }

        return back()->with('success', trans('constants.document_added_successfully'));
    }

    public function document_list()
    {
        $document_list = $this->document->get();

        return view('store/document_list', ['document_list' => $document_list]);
    }

    public function vehicle_management()
    {

        return view('store/add_vehicle');
    }
    public function vehicle_add(Request $request)
    {

        $rules = array();

        if (!$request->id) {
            $rules['insurance_image'] = 'required|max:2048';
            $rules['rc_image'] = 'required|max:2048';
        }
        $rules['vehicle_name'] = 'required';
        $rules['vehicle_no'] = 'required';
        $rules['insurance_no'] = 'required';
        $rules['insurance_expiry_date'] = 'required';
        $rules['rc_no'] = 'required';
        $rules['rc_expiry_date'] = 'required';
        $rules['status'] = 'required';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            return back()->with('error', $error_messages);
        } else {
            $vehicle_name = $request->vehicle_name;
            $vehicle_no = $request->vehicle_no;
            $insurance_no = $request->insurance_no;
            $insurance_expiry_date = date('Y-m-d', strtotime($request->insurance_expiry_date));
            $rc_no = $request->rc_no;
            $rc_expiry_date = date('Y-m-d', strtotime($request->rc_expiry_date));
            $path = public_path('vehicles/');

            $status = $request->status;
            if ($request->id != "") {
                $add_vehicle = $this->vehicle->find($request->id);
            } else {
                $add_vehicle = new $this->vehicle;
            }
            $add_vehicle->vehicle_name = $vehicle_name;
            $add_vehicle->vehicle_no = $vehicle_no;
            $add_vehicle->insurance_no = $insurance_no;
            $add_vehicle->insurance_expiry_date = date('Y-m-d', strtotime($request->insurance_expiry_date));
            $rc_no = $request->rc_no;
            $add_vehicle->rc_expiry_date = date('Y-m-d', strtotime($request->rc_expiry_date));
            $add_vehicle->status = $status;
            if ($request->insurance_image != "" || $request->insurance_image != null) {
                $add_vehicle->insurance_image = $this->custom->common_upload_images($request, 'insurance_image', $path);
            }
            if ($request->rc_image != "" || $request->rc_image != null) {
                $add_vehicle->rc_image = $this->custom->common_upload_images($request, 'rc_image', $path);
            }
            if ($request->vehicle_image != "" || $request->vehicle_image != null) {
                $add_vehicle->vehicle_image = $this->custom->common_upload_images($request, 'vehicle_image', $path);
            }
            $add_vehicle->save();
        }

        return back()->with('success', trans('constants.vehicle_added_successfully'));
    }

    public function editvehicle($id)
    {
        $data = $this->vehicle->find($id);

        return view('store/add_vehicle', compact('data'));
    }

    public function vehicle_list()
    {
        $vehicle_list = $this->vehicle->get();

        return response()->json($vehicle_list);
    }

    public function cancellation_reason()
    {

        return view('store/cancellation_reason_list');
    }

    public function add_reason(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required',
            'cancellation_for' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            return back()->with('error', $error_messages);
        } else {
            $reason = $request->reason;
            $cancellation_for = $request->cancellation_for;
            $status = $request->status;
            $add_reason = $this->cancellation_reason;

            $data = array();

            $data[] = array(
                'reason' => $reason,
                'cancellation_for' => $cancellation_for,
                'status' => $status,

            );

            $add_reason->insert($data);
        }

        return back()->with('success', trans('constants.cancellation_reason_added_successfully'));
    }

    public function reason_list()
    {
        $reason_list = $this->cancellation_reason->get();

        return view('store/reason_list', ['reason_list' => $reason_list]);
    }

    public function driver()
    {
        $city = $this->addcity->get();
        $vehicle = $this->vehicle->get();
        $restaurants = DB::table('restaurants')->select('id', 'restaurant_name')
            ->where('status', 1)
            ->orderBy('restaurant_name', 'asc')
            ->get();

        return view('store/add_new_driver', ['city' => $city, 'vehicle' => $vehicle, 'area' => [], 'restaurants' => $restaurants]);
    }

    public function generate_random_string()
    {
        return rand(11111111, 99999999);
    }

    public function base_image_upload_license($request, $key)
    {
        $extension = $request->file($key)->getClientOriginalExtension();
        $mime = $request->file($key)->getMimeType();

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
            return null;
        }

        $imageName = $request->file($key)->getClientOriginalName();
        $ext = $request->file($key)->getClientOriginalExtension();
        $imageName = self::generate_random_string() . '.' . $ext;
        $request->file($key)->move(public_path('uploads/License/'), $imageName);
        return $image_url = "uploads/License/" . $imageName;
    }

    public function base_image_upload_profile($request, $key)
    {
        $extension = $request->file($key)->getClientOriginalExtension();
        $mime = $request->file($key)->getMimeType();

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
            return null;
        }

        $imageName = $request->file($key)->getClientOriginalName();
        $ext = $request->file($key)->getClientOriginalExtension();
        $imageName = self::generate_random_string() . '.' . $ext;
        $request->file($key)->move(public_path('uploads/Profile/'), $imageName);
        return $image_url = "uploads/Profile/" . $imageName;
    }

    public function add_driver(Request $request)
    {
        $conditions = [
            'city' => 'required',
            'driver_name' => 'required',
            'vehicle_name' => 'required',
            'phone_no' => 'required',
            'address_line_1' => 'required',
            'address_city' => 'required',
            'state_province' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'license_expiry' => 'required',
            'account_name' => 'required',
            'account_address' => 'required',
            'account_no' => 'required',
            'bank_name' => 'required',
            'branch_name' => 'required',
            'branch_address' => 'required',
            'status' => 'required',
        ];

        if (!$request->id) {
            $conditions['profile_pic'] = 'required';
            $conditions['license'] = 'required';
            $conditions['password'] = 'required';
        }

        $validator = Validator::make($request->all(), $conditions);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('error', $error_messages)->withInput();
        }

        if ($request->file('license')) {
            $license = self::base_image_upload_license($request, 'license');
        }

        if ($request->file('profile_pic')) {
            $profile_picture = self::base_image_upload_profile($request, 'profile_pic');
        }

        if ($request->id) {
            $insert1 = Deliverypartners::find($request->id);

            if ($request->phone_no != $insert1->phone) {
                $duplicate_check = Deliverypartners::where('phone', $request->phone_no)->first();
                if (!empty($duplicate_check)) {
                    return back()->with('error', 'Phone number already exists')->withInput();
                }
            }
        } else {
            $insert1 = new Deliverypartners();
            $duplicate_check = Deliverypartners::where('phone', $request->phone_no)->first();
            if (!empty($duplicate_check)) {
                return back()->with('error', 'Phone number already exists')->withInput();
            }
        }

        $insert1->partner_id = $this->generate_partner_id();
        $insert1->name = $request->driver_name;
        $insert1->restaurant_id = $request->restaurant;
        $insert1->email = $request->email;
        $insert1->phone = $request->phone_no;


        $insert1->expiry_date = $request->license_expiry;

        if (!empty($request->password)) {
            $insert1->password = $this->encrypt_password($request->password);
        }

        $insert1->status = $request->status;

        if ($request->file('license')) {
            $insert1->license = $license;
        }

        if ($request->file('profile_pic')) {
            $insert1->profile_pic = $profile_picture;
        }

        $insert1->save();

        $partner_id = $insert1->id;

        if ($request->id) {
            $insert = DriverList::find($request->driver_id);
        } else {
            $insert = new DriverList();
        }

        if (isset($request->area) && !empty($request->area)) {
            $insert->area = $request->area;
        }

        $insert->delivery_partners_id = $partner_id;
        $insert->city = $request->city;
        $insert->vehicle_name = $request->vehicle_name;
        $insert->address_line_1 = $request->address_line_1;
        $insert->address_line_2 = $request->address_line_2;
        $insert->address_city = $request->address_city;
        $insert->state_province = $request->state_province;
        $insert->country = $request->country;
        $insert->zip_code = $request->zip_code;
        $insert->about = $request->about;
        $insert->account_name = $request->account_name;
        $insert->account_address = $request->account_address;
        $insert->account_no = $request->account_no;
        $insert->bank_name = $request->bank_name;
        $insert->branch_name = $request->branch_name;
        $insert->branch_address = $request->branch_address;
        $insert->swift_code = $request->swift_code;
        $insert->routing_no = $request->routing_no;
        $insert->save();

        $returnMsg = $request->id ? trans('constants.driver_updated_succesfully') :  trans('constants.driver_added_successfully');

        return back()->with('success', $returnMsg);
    }

    public function edit_delivery_boy_details($id, $driver_id, Request $request)
    {
        $deliverypartners = $this->deliverypartners;

        $delivery_partner_detail = $deliverypartners->find($id);
        $profile_pic = BASE_URL . $delivery_partner_detail->profile_pic;

        $delivery_partners_fulldetails = $this->driver_partner_details;

        $delivery_partner_details = "";

        if ($driver_id != 0) {
            $delivery_partner_details = $delivery_partners_fulldetails->find($driver_id);
        }

        $city = $this->addcity->get();
        $vehicle = $this->vehicle->get();
        $area = $delivery_partner_details ? $this->addarea->select('id', 'area')->where('add_city_id', $delivery_partner_details->city)->get() : [];
        $restaurants = DB::table('restaurants')->select('id', 'restaurant_name')
            ->where('status', 1)
            ->orderBy('restaurant_name', 'asc')
            ->get();

        return view('store/add_new_driver', [
            'insert1' => $delivery_partner_detail,
            'insert' => $delivery_partner_details,
            'city' => $city,
            'area' => $area,
            'vehicle' => $vehicle,
            'restaurants' => $restaurants
        ])->with('delivery_partner_commision', $delivery_partner_detail->partner_commision)->with('profile_icon', $profile_pic);
    }

    public function delete_delivery_boy($id)
    {
        $delete = $this->deliverypartners->where('id', $id)->delete();

        $delete1 = DB::table('delivery_partner_details')->where('delivery_partners_id', $id)->delete();

        return back()->with('success', trans('constants.delivery_partner_deleted_successfully'));
    }

    public function driver_list()
    {

        $data = $this->deliverypartners->where('status', '!=', 3)->with('Foodrequest')->get();

        $all_drivers = $this->deliverypartners->count('id');

        $active_drivers = $this->deliverypartners->where('status', 1)->count('id');

        $in_active_drivers = $this->deliverypartners->where('status', 0)->count('id');

        return view('store/driver_list', ['data' => $data, 'all_drivers' => $all_drivers, 'active_drivers' => $active_drivers, 'in_active_drivers' => $in_active_drivers]);
    }

    public function view_deliveryboy_order_details($id)
    {
        $delivery_boy_details = DB::table('requests')
            ->where('delivery_boy_id', $id)
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
            ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
            ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as driver_name', 'restaurants.restaurant_name as restaurant_name', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.*', 'requests.status as status')
            ->get();

        return view('store/view_delivery_boy_order_details', ['delivery_boy_details' => $delivery_boy_details]);
    }

    /**
     * function to get city data
     * @param int $id
     * @return array to blade file
     */
    public function edit_city($id)
    {


        $city_data = $this->addcity->where('id', $id)->with(['Country', 'State'])->first();
        $country = $this->country->get();
        $state = $this->state->where('country_id', $city_data->country_id)->get();
        //get city data based on id

        return view('store/edit_city', compact('city_data', 'country', 'state'));
    }

    public function update_city(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'city' => 'required',
            'country' => 'required',
            'state' => 'required',
            'admin_commision' => 'required',
            'status' => 'required',
            'geofence_latlng' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());
            //print_r($validator->messages()); exit;
            //return back()->with('error', $error_messages);
            return back()->withErrors($validator)->withInput();
        } else {
            /*if(!empty($request->geofence_latlng))
            {
            $source_polygon = explode(',',$request->geofence_latlng);
            $j = $k =0;
            for ($i=0; $i <= count($source_polygon)-1 && $k <= count($source_polygon)-1; $i++) {
            $source_coordinates[$j][0] = $source_polygon[$k++];
            $source_coordinates[$j][0] = (double)trim($source_coordinates[$j][0],'[ ');
            $source_coordinates[$j][1] = $source_polygon[$k];
            $source_coordinates[$j][1] = (double)trim($source_coordinates[$j][1],' ]');

            $temp = $source_coordinates[$j][0];
            $source_coordinates[$j][0] = $source_coordinates[$j][1];
            $source_coordinates[$j][1] = $temp;
            $k++;$j++;
            }
            } else {
            $source_coordinates = array();
            }*/
            //echo "<pre>";print_r( $source_coordinates);
            // $first_cordinate = current($source_coordinates);
            // $a = end($source_coordinates);
            // $key = key($source_coordinates);
            // $source_coordinates[$key+1] = $first_cordinate;
            //echo "<pre>";print_r( $source_coordinates);exit;

            $city = $request->city;
            $country_id = $request->country;
            $state_id = $request->state;
            $admin_commision = $request->admin_commision;
            $default_delivery_amount = $request->default_delivery_amount;
            $target_amount = $request->target_amount;
            $driver_base_price = $request->driver_base_price;
            $min_dist_base_price = $request->min_dist_base_price;
            $extra_fee_amount = $request->extra_fee_amount;
            $extra_fee_amount_each = $request->extra_fee_amount_each;
            $night_fare_amount = $request->night_fare_amount;
            $night_driver_share = $request->night_driver_share;
            $surge_fare_amount = $request->surge_fare_amount;
            $surge_driver_share = $request->surge_driver_share;
            $status = $request->status;
            $add_city = $this->addcity;

            $data = array();

            // $data[] = array(

            //     'city'=>$city,
            //     'admin_commision'=>$admin_commision,
            //     'default_delivery_amount'=>$default_delivery_amount,
            //     'target_amount'=>$target_amount,
            //     'driver_base_price'=>$driver_base_price,
            //     'min_dist_base_price'=>$min_dist_base_price,
            //     'extra_fee_amount'=>$extra_fee_amount,
            //     'extra_fee_amount_each'=>$extra_fee_amount_each,
            //     'night_fare_amount'=>$night_fare_amount,
            //     'night_driver_share'=>$night_driver_share,
            //     'surge_fare_amount'=>$surge_fare_amount,
            //     'surge_driver_share'=>$surge_driver_share,
            //     'status'=>$status,

            // );

            $citydata = $add_city->find($request->id);
            $citydata->city = $city;
            $citydata->country_id = $country_id;
            $citydata->state_id = $state_id;
            $citydata->admin_commision = $admin_commision;
            $citydata->default_delivery_amount = $default_delivery_amount;
            $citydata->target_amount = $target_amount;
            $citydata->driver_base_price = $driver_base_price;
            $citydata->min_dist_base_price = $min_dist_base_price;
            $citydata->extra_fee_amount = $extra_fee_amount;
            $citydata->extra_fee_amount_each = $extra_fee_amount_each;
            $citydata->night_fare_amount = $night_fare_amount;
            $citydata->night_driver_share = $night_driver_share;
            $citydata->surge_fare_amount = $surge_fare_amount;
            $citydata->surge_driver_share = $surge_driver_share;
            $citydata->status = $status;
            $citydata->save();

            //add data in city_geofencing table
            $geofencing = new City_geofencing();
            $geofencingdata = $geofencing->where('city_id', $citydata->id)->first();
            if (empty($geofencingdata)) {
                $geofencingdata = $geofencing;
            }

            $geofencingdata->city_id = $citydata->id;
            $geofencingdata->latitude = !empty($request->latitude) ? $request->latitude : env('DEFAULT_LAT');
            $geofencingdata->longitude = !empty($request->longitude) ? $request->longitude : env('DEFAULT_LNG');
            $geofencingdata->polygons = $request->geofence_latlng;
            //$geofencing->save();
            $geofencingdata->save();
            //$data = $city_id->city_geofencing()->create($geofencing);
        }

        return redirect('/store/city_list')->with('success', 'City  Added Successfully');
    }

    /**
     * get add_ons, menu based on restaurant
     *
     * @param int $id
     *
     * @return json $data
     */
    public function getrestaurant_based_detail($id)
    {
        $data = $this->restaurants->with(['Add_ons', 'Menu'])->find($id);
        //dd($data);
        return $data;
    }

    /**
     * get area based on city
     *
     * @param int $id
     *
     * @return json $data
     */
    public function getcity_area($id)
    {
        $data = $this->addcity->with(['Area'])->find($id);
        //dd($data);
        return $data;
    }



    public function assignRiderList($id, Request $request)
    {

        // $role = session()->get('res_role');
        $admin_id = $id;

        $restaurant = DB::table('restaurants')->where('id', $admin_id)->first();
        $business = DB::table('business_type')->where('id', $restaurant->business_type)->first();

        $request_data = DB::table('requests')->where('id', $request->Order_id)->first();
        $restaurant_id = $request_data->restaurant_id;

        $data = file_get_contents(FIREBASE_URL . "/available_providers/.json");


        $data = json_decode($data);

        if ($data == NULL || $data == "") {
            return '';
        }
        $driver = [];
        $returnData = '<option>Select</option>';

        foreach ($data as $driver_key => $each_driver) {

            if ($each_driver != null) {

                if ($business->layout_id == 3) {
                    $this_driver = DB::table('delivery_partners')
                        ->where('delivery_partners.id', $driver_key)
                        //  ->whereJsonContains('restaurants.carrier_type', $request_data->carrier)
                        //  ->whereJsonContains('delivery_partner_details.services', $business->layout_id)
                        ->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
                        ->select('delivery_partners.*', 'delivery_partners.id as driver_id',  'delivery_partner_details.*')
                        ->first();
                    if ($this_driver) {
                        foreach (json_decode($this_driver->services) as $d) {

                            if ($d == $business->layout_id) {
                                $this_driver = DB::table('delivery_partners')
                                    ->where('delivery_partners.id', $driver_key)
                                    ->where('vehicle.carrier_type', $request_data->carrier)
                                    ->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
                                    ->join('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
                                    ->select('delivery_partners.*', 'vehicle.*', 'delivery_partners.id as driver_id',  'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
                                    ->first();
                                if ($this_driver) {
                                    $restaurant1 = DB::table('restaurants')->where('id', $admin_id)->first();
                                    foreach (json_decode($restaurant1->carrier_type) as $d1) {
                                        if ($d1 == $this_driver->carrier_type) {
                                            $this_driver = DB::table('delivery_partners')
                                                ->where('delivery_partners.id', $driver_key)
                                                ->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
                                                ->select('delivery_partners.*', 'delivery_partners.id as driver_id',  'delivery_partner_details.*')
                                                ->first();
                                            break;
                                        } else {
                                            $this_driver = [];
                                        }
                                    }
                                } else {
                                    $this_driver = [];
                                }
                            } else {

                                $this_driver = [];
                            }
                        }
                    } else {
                        $this_driver = [];
                    }
                } else {
                    // $driver = DB::table('delivery_partners')->where('id', $driver_id)->first();

                    $this_driver = DB::table('delivery_partners')
                        ->where('delivery_partners.id', $driver_key)
                        //  ->whereIn('delivery_partner_details.services', array($business->layout_id))
                        ->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
                        ->select('delivery_partners.*', 'delivery_partners.id as driver_id', 'delivery_partner_details.*')
                        ->first();
                    if ($this_driver) {
                        if (json_decode($this_driver->services) != null) {
                            foreach (json_decode($this_driver->services) as $d) {

                                if ($d == $business->layout_id) {
                                    $this_driver = DB::table('delivery_partners')
                                        ->where('delivery_partners.id', $driver_key)
                                        //  ->whereIn('delivery_partner_details.services', array($business->layout_id))
                                        ->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
                                        ->select('delivery_partners.*', 'delivery_partners.id as driver_id',  'delivery_partner_details.*')
                                        ->first();
                                    break;
                                } else {

                                    $this_driver = [];
                                }
                            }
                        }
                    } else {

                        $this_driver = [];
                    }
                }
                if (!$this_driver) {
                    continue;
                }

                if ($this_driver->restaurant_id != null && $this_driver->restaurant_id != $admin_id) {
                    continue;
                }

                $distance = vincentyGreatCircleDistance($each_driver->lat, $each_driver->lng, $restaurant->lat, $restaurant->lng);
                if ($distance > 0 && $distance <= DEFAULT_RADIUS) {
                    $driver[] = [
                        'name' => $this_driver->name . ' - ' . $distance,
                        'distance' =>  $distance,
                        'id' => $this_driver->driver_id,
                    ];
                }
            }



            // $this_driver = DB::table('delivery_partners')->where('id', $driver_key)->first();


            //echo $distance;echo $this_driver->name;echo "<br>";
            // if($distance != 0){
            // if (!$distance || $distance > DEFAULT_RADIUS) {
            //     continue;
            // }

            // $this_driver->$distance = $distance;
            // }
            // $returnData .= '<option value="' . $this_driver->id . '">' . ucfirst($this_driver->name) . ' ~ ' . $distance . ' km</option>';
        }


        // $response_Array = json_encode(['data'=>$this_driver,'distance'=>$distance]);
        // return $response_Array;

        // return $returnData;
        return response()->json($driver);
    }

    public function driver_map(Request $request)
    {

        $role = 2;
        $admin_id = $request->id;

        $data = file_get_contents(FIREBASE_URL . "/available_providers/.json");
        $data = json_decode($data);

        if (!$data) {
            $data = [];
        }

        $locations = [];
        $i = 1;
        foreach ($data as $driver_key => $each_driver) {
            $this_driver = DB::table('delivery_partners')->where('id', $driver_key)->first();
            if (!$this_driver) {
                continue;
            }

            if ($role == 1) {
            } else if ($role == 2) {
                if ($this_driver->restaurant_id != null && $this_driver->restaurant_id != $admin_id) {
                    continue;
                }
            }

            if (isset($each_driver->lat) == null) {
            } else {
                if ($this_driver->status == 1) {
                    $locations[] = [ucfirst($this_driver->name), $each_driver->lat, $each_driver->lng, $i++];
                }
            }
        }

        $center = [];
        if ($role == 2) {
            $restaurant = DB::table('restaurants')->where('id', $admin_id)->first();
            if ($restaurant) {
                $center = [$restaurant->lat, $restaurant->lng];
            }
        } else {
            if ($locations) {
                $center = [$locations[0][1], $locations[0][2]];
            }
        }


        $def_lat = DB::table('settings')->where('key_word', 'DEFAULT_LATITUDE')->first();
        $def_lng = DB::table('settings')->where('key_word', 'DEFAULT_LONGITUDE')->first();



        if (!$center) {
            $center = [$def_lat->value, $def_lng->value];
        }

        $response_Array = json_encode(['data' =>  $restaurant, 'locations' => $locations, 'center' => $center]);
        return $response_Array;
        // return view('store/driver_map')->with('locations', json_encode($locations))->with('center', $center);
    }

    public function menu_sort(Request $request)
    {

        $authID = $request->id;
        // $role = \Session::get('res_role');
        // if ($role != 2) {
        //     $authID = 0;
        // }
        $menus = DB::table('menu')->where('restaurant_id', $authID)->orderBy('rank', 'asc')->get();;
        $response_Array = json_encode(['data' => $menus]);
        return $response_Array;
        // return view('store/menu_sort')->with('menus', $menus);
    }

    public function menu_sort_save(Request $request)
    {

        $authID = $request->id;
        // $role = \Session::get('res_role');
        // if ($role != 2) {
        //     $authID = 0;
        // }

        $menuRanks = $request->menuRanks;
        //    dd($menuRanks );
        // $menu_ranks = explode(',', $menuRanks);
        $menu_ranks = $menuRanks;

        foreach ($menu_ranks as $rank => $id) {
            DB::table('menu')->where('id', $id)->update(['rank' => $rank]);
        }

        $menus = DB::table('menu')->where('restaurant_id', $authID)->orderBy('rank', 'asc')->get();
        $message = "Menu List Updated";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $menus]);
        return $response_Array;
        // return view('store/menu_sort')->with('menus', $menus);
    }

    public function category_sort(Request $request)
    {

        $categories = get_category_by_rank();

        return view('store/category_sort')->with('categories', $categories);
    }

    public function category_sort_save(Request $request)
    {

        $categoryRanks = $request->categoryRanks;

        $category_ranks = explode(',', $categoryRanks);

        foreach ($category_ranks as $rank => $id) {
            DB::table('category')->where('id', $id)->update(['rank' => $rank]);
        }

        $categories = get_category_by_rank();

        return view('store/category_sort')->with('categories', $categories);
    }

    public function tables(Request $request)
    {

        $restaurant_id = $request->id;

        $data = DB::table('tables')->where('restaurant_id', $restaurant_id)->orderBy('table_name')->get();
        return response()->json($data);
        // return view('store/tables', ['data' => $data]);
    }

    function generateRandomLowerString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = 'abcdefghijklmnopqrstuvwxyz', ceil($length / strlen($x)))), 1, $length);
    }

    public function add_table(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'table_name' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());
            // return back()->with('error', $error_messages)->withInput();
            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        $random = $this->generateRandomLowerString(8);

        DB::table('tables')->insert([
            'restaurant_id' => $request->id,
            'table_name' => $request->table_name,
            'table_code' => $random,
            'qr_image' => '',

        ]);

        $message = "Add Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function print_table(Request $request)
    {

        $table = DB::table('tables')->where('id', $request->code)->first();
        $restaurant = DB::table('restaurants')->where('id', $table->restaurant_id)->first();
        $path = public_path('restaurant_uploads/' . $table->restaurant_id . '/');
        // QR code with text
        $data = QrCode::generate('Welcome to Makitweb');

        // Store QR code for download
        QrCode::generate('Welcome to Makitweb', public_path('restaurant_uploads/' . $restaurant->id . '/'));



        $response_Array = json_encode(['table' => $table, 'restaurant' => $restaurant]);
        return $response_Array;
    }

    public function driver_group(Request $request)
    {

        //$restaurant_id = $request->session()->get('res_id');

        $data = DB::table('driver_groups')
            ->leftJoin('delivery_partners', 'driver_groups.driver_id', '=', 'delivery_partners.id')
            ->select('driver_groups.*', 'delivery_partners.name')
            ->get();

        $drivers = DB::table('delivery_partners')->select('id', 'name')->get();
        $exists_drivers = DB::table('driver_groups')->pluck('driver_id')->toArray(); //->get();
        $avail_drivers = [];
        foreach ($drivers as $each_driver) {
            if (!in_array($each_driver->id, $exists_drivers)) {
                $avail_drivers[] = $each_driver;
            }
        }

        return view('store/driver_group', ['data' => $data, 'avail_drivers' => $avail_drivers]);
    }
    public function add_drivergroup(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'group_name' => 'required',
            'driver_id' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());
            return back()->withErrors($validator);
        }

        DB::table('driver_groups')->insert([
            'group_name' => $request->group_name,
            'driver_id' => $request->driver_id
        ]);

        return back();
    }

    function delivery_people_page()
    {
        return view('store.delivery_people');
    }



    function getCategoryIDByName($name)
    {
        $category = DB::table('category')->where('category_name', $name)->first();
        return $category->id ?? null;
    }
    function getBusinessCategoryIDByName($name)
    {
        $category = DB::table('business_category')->where('category_name', $name)->first();
        return $category->id ?? null;
    }
    function getMenuIDByName($restaurant_id, $name)
    {
        $menu = DB::table('menu')->where('menu_name', $name)->where('restaurant_id', $restaurant_id)->first();
        return $menu->id ?? null;
    }

    function is_product_exists($name, $restaurant_id, $category_id, $menu_id)
    {
        $data = DB::table('food_list')
            ->where('name', $name)
            ->where('restaurant_id', $restaurant_id)
            ->where('category_id', $category_id)
            ->where('menu_id', $menu_id)
            ->first();
        return $data->id ?? null;
    }

    function is_addon_exists($name, $restaurant_id)
    {
        $data = DB::table('add_ons')
            ->where('name', $name)
            ->where('restaurant_id', $restaurant_id)
            ->first();
        return $data->id ?? null;
    }

    function is_addon_mapped_with_product($addonID, $productID)
    {
        $data = DB::table('foodlist_addons')
            ->where('addons_id', $addonID)
            ->where('foodlist_id', $productID)
            ->first();
        return $data->id ?? null;
    }

    function remove_addons_of_product($productID)
    {
        DB::table('foodlist_addons')->where('foodlist_id', $productID)->delete();
    }

    function insert_addons_for_product($restaurant_id, $productID, $addons)
    {

        $addons = explode(',', $addons);
        foreach ($addons as $eachAddon) {
            $addon_id = $this->is_addon_exists($eachAddon, $restaurant_id);
            if ($addon_id && !$this->is_addon_mapped_with_product($addon_id, $productID)) {
                DB::table('foodlist_addons')->insert(['foodlist_id' => $productID, 'addons_id' => $addon_id]);
            }
        }
    }

    function store_product($product)
    {

        if (empty($product['Name'])) {
            return 0;
        }

        if ($this->is_product_exists($product['Name'], $product['RestaurantID'], $product['CategoryID'], $product['MenuID'])) {
            return 0;
        }

        $insertRow = [
            'restaurant_id'         => $product['RestaurantID'],
            'category_id'             => $product['Sub CategoryID'] ? $product['Sub CategoryID'] : 0,
            'business_category_id'             => $product['CategoryID'],
            'menu_id'                 => $product['MenuID'] ? $product['MenuID'] : 0,

            'name'                   => $product['Name'],
            'description'            => $product['Description'],
            'secondary_name'         => $product['Secondary Name'],
            'secondary_description' => $product['Secondary Description'],


            'price'                 => (float) $product['Price'],
            'tax'                     => $product['Tax'],
            'packaging_charge'         => (float) $product['Package Charge'],
            'is_veg'                 => (int) $product['Is Veg'],
            'status'                 => (int) $product['Status'],
            'image'                 => '',
        ];

        DB::table('food_list')->insert($insertRow);

        $food_id = $this->is_product_exists($product['Name'], $product['RestaurantID'], $product['Sub CategoryID'], $product['MenuID']);


        $this->remove_addons_of_product($food_id);
        $this->insert_addons_for_product($product['RestaurantID'], $food_id, $product['Addons']);

        return 1;
    }

    function update_product($product)
    {

        if (empty($product['Name'])) {
            return 0;
        }

        $food_id = $this->is_product_exists($product['Name'], $product['RestaurantID'], $product['CategoryID'], $product['MenuID']);
        if (!$food_id) {
            return 0;
        }

        $updateRow = [
            'category_id'             => $product['Sub CategoryID'] ? $product['Sub CategoryID'] : 0,
            'menu_id'                 => $product['MenuID'] ? $product['MenuID'] : 0,
            'business_category_id'     => $product['CategoryID'],
            'name'                   => $product['Name'],
            'description'            => $product['Description'],
            'secondary_name'         => $product['Secondary Name'],
            'secondary_description' => $product['Secondary Description'],


            'price'                 => (float) $product['Price'],
            'tax'                     => $product['Tax'],
            'packaging_charge'         => (float) $product['Package Charge'],
            'is_veg'                 => (int) $product['Is Veg'],
            'status'                 => (int) $product['Status'],
            // 'image'                 => '',
        ];

        DB::table('food_list')->where('id', $food_id)->update($updateRow);

        $this->remove_addons_of_product($food_id);
        $this->insert_addons_for_product($product['RestaurantID'], $food_id, $product['Addons']);

        return 1;
    }

    function store_product1($product)
    {

        if (empty($product['Name'])) {
            return 0;
        }

        if ($this->is_product_exists($product['Name'], $product['RestaurantID'], $product['CategoryID'], $product['MenuID'])) {
            return 0;
        }

        $insertRow = [
            'restaurant_id'         => $product['RestaurantID'],
            'category_id'             => $product['Sub CategoryID'] ? $product['Sub CategoryID'] : 0,
            'business_category_id'             => $product['CategoryID'],
            'menu_id'                 => $product['MenuID'] ? $product['MenuID'] : 0,

            'name'                   => $product['Name'],
            'description'            => $product['Description'],
            'secondary_name'         => $product['Secondary Name'],
            'secondary_description' => $product['Secondary Description'],


            'price'                 => (float) $product['Price'],
            'tax'                     => $product['Tax'],
            'packaging_charge'         => (float) $product['Package Charge'],
            // 'is_veg'                 => (int) $product['Is Veg'],
            'status'                 => (int) $product['Status'],
            'image'                 => '',
        ];

        DB::table('food_list')->insert($insertRow);

        $food_id = $this->is_product_exists($product['Name'], $product['RestaurantID'], $product['Sub CategoryID'], $product['MenuID']);


        $this->remove_addons_of_product($food_id);
        $this->insert_addons_for_product($product['RestaurantID'], $food_id, $product['Addons']);

        return 1;
    }

    function update_product1($product)
    {

        if (empty($product['Name'])) {
            return 0;
        }

        $food_id = $this->is_product_exists($product['Name'], $product['RestaurantID'], $product['CategoryID'], $product['MenuID']);
        if (!$food_id) {
            return 0;
        }

        $updateRow = [
            'category_id'             => $product['Sub CategoryID'] ? $product['Sub CategoryID'] : 0,
            'menu_id'                 => $product['MenuID'] ? $product['MenuID'] : 0,
            'business_category_id'     => $product['CategoryID'],
            'name'                   => $product['Name'],
            'description'            => $product['Description'],
            'secondary_name'         => $product['Secondary Name'],
            'secondary_description' => $product['Secondary Description'],


            'price'                 => (float) $product['Price'],
            'tax'                     => $product['Tax'],
            'packaging_charge'         => (float) $product['Package Charge'],
            // 'is_veg'                 => (int) $product['Is Veg'],
            'status'                 => (int) $product['Status'],
            // 'image'                 => '',
        ];

        DB::table('food_list')->where('id', $food_id)->update($updateRow);

        $this->remove_addons_of_product($food_id);
        $this->insert_addons_for_product($product['RestaurantID'], $food_id, $product['Addons']);

        return 1;
    }


    function get_addon_list_of_product($productID)
    {
        $addons = DB::table('foodlist_addons')
            ->select('add_ons.name')
            ->leftJoin('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
            ->where('foodlist_id', $productID)->pluck('add_ons.name')->toArray();

        return $addons;
    }

    function toggleStatus(Request $request)
    {

        $id = $request->id;
        $restaurant = DB::table('restaurants')->where('id', $id)->first();

        $is_open = (int) !$restaurant->is_open;
        $now = Carbon::now();

        if ($request->status == 2) {
            DB::table('restaurants')->where('id', $id)->update(['is_open' => $request->status, 'closed_for_today' => $now]);
        } else {
            DB::table('restaurants')->where('id', $id)->update(['is_open' => $request->status, 'closed_for_today' => null]);
        }


        $message = "Status Changed Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'is_open' => $request->status]);
        return $response_Array;
    }
    function productStatus($id)
    {

        $food = DB::table('food_list')->where('id', $id)->first(); //dd($food);

        if ($food->status == 1) {
            $status = 0;
            $msg = "Status Active";
        } else {
            $status = 1;
            $msg = "Status Inactive";
        }

        DB::table('food_list')->where('id', $id)->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    function productStock(Request $request)
    {
        $food = DB::table('food_list')->where('id', $request->id)->first();
        if ($food->out_of_stock == 1) {
            $status = 0;
            $msg = "Product Available";
        } else {
            $status = 1;
            $msg = "Product Set SOLD OUT TODAY";
        }
        //dd($request->id);
        DB::table('food_list')->where('id', $request->id)->update(['out_of_stock' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    function revice_order(Request $request)
    {
        $req_id = $request->id;
        $schedule = session()->get('schedule_date');

        $restaurant_id = $request->rest_id;

        $veg_only = 1;
        $restaurants = $this->restaurants;
        $foodlist = $this->foodlist;

        $menu = $this->menu;
        $cart = $this->cart;
        $user_id = $request->user;
        $ses_user = 1;

        if ($request->table) {
            $request->session()->put('table_code', $request->table);
        } else {
            $request->session()->put('table_code', '');
        }

        $rest = $restaurants::where('restaurants.id', $restaurant_id)->first();
        if (empty($rest)) {
            return redirect('user.home');
        }
        stockRevice($dt = Carbon::now());

        $cuisines = DB::table('restaurant_cuisines')->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
            ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
            ->select('cuisines.name as cuisine_name')
            ->where('restaurants.id', $restaurant_id)
            ->get()->pluck('cuisine_name')->toArray();

        $last_3_reviews = DB::table('order_ratings')
            ->join('requests', 'requests.id', '=', 'order_ratings.request_id')
            ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->where('restaurants.id', $restaurant_id)
            ->select(
                'restaurant_rating',
                'restaurant_feedback',
                'order_ratings.updated_at as reviewd_at',
                'users.name as user_name',
                'users.profile_image as user_image'
            )
            ->orderBy('order_ratings.id', 'desc')
            ->limit(50)
            ->get()->toArray();
        $ratings = DB::table('order_ratings')
            ->join('requests', 'requests.id', '=', 'order_ratings.request_id')
            ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
            ->where('restaurants.id', $restaurant_id)
            ->select(
                DB::raw('SUM(1) as total_raters'),
                DB::raw('SUM(restaurant_rating) as total_ratings')
            )->first();
        $rating_statistics = get_rating_statistics($restaurant_id);

        $restaurant = $restaurants::where('restaurants.id', $restaurant_id)
            ->select(
                '*',
                'restaurants.id as restaurant_id',
                DB::raw('
                    IF( EXISTS(
                            SELECT *
                            FROM favourite_list
                            WHERE `user_id` =' . $user_id . ' AND `restaurant_id` = ' . $restaurant_id . '
                    ) , 1, 0) as is_favourite
                ')
            )
            ->first();

        $restaurant->is_open = restaurant_is_open($restaurant_id);



        $near_by_restaurants = get_nearby_restaurants($restaurant->lat, $restaurant->lng, [$restaurant_id]);


        $food = $foodlist::where('food_list.restaurant_id', $restaurant_id)
            ->where('food_list.status', 1)
            ->join('menu', function ($join) {
                $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                $join->on('menu.id', '=', 'food_list.menu_id');
            })
            ->select('food_list.id as food_id', 'name', 'price', 'description', 'secondary_name', 'secondary_description', 'is_veg', 'tax', 'menu.id', 'menu_name', 'food_list.image as image', 'out_of_stock')
            ->orderBy('rank')
            ->get();
        $food = $this->updateSecondaryLang1($food);
        $foodlist_addons = [];
        foreach ($food as $each_food) {
            $this_addon = DB::table('foodlist_addons')
                ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                ->where('foodlist_id', $each_food->food_id)->select('foodlist_addons.addons_id', 'add_ons.name', 'add_ons.secondary_name',  'add_ons.price')->get()->toArray();

            if (!$this_addon) {
                continue;
            }

            $foodlist_addons[] = ['food_id' => $each_food->food_id, 'addons' => $this_addon];
        }

        $foodlist_addons = ['foodlist_addons' => $foodlist_addons];
        $foodlist_addons = $this->updateSecondaryLangAddon($foodlist_addons, $request);

        $foodlist_sizes = [];
        foreach ($food as $each_food) {
            $this_size = DB::table('foodlist_foodquantity')
                ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select('food_quantity.name', 'food_quantity.secondary_name', 'foodlist_foodquantity.foodquantity_id', 'foodlist_foodquantity.price', 'foodlist_foodquantity.size_description', 'foodlist_foodquantity.is_default')
                ->get()->toArray();

            if (!$this_size) {
                continue;
            }

            $foodlist_sizes[] = ['food_id' => $each_food->food_id, 'sizes' => $this_size];
        }
        $foodlist_sizes = ['foodlist_sizes' => $foodlist_sizes];
        $foodlist_sizes = $this->updateSecondaryLangSizes($foodlist_sizes);

        $cart = $cart::where('user_id', $user_id)->get();
        $amount = 0;
        $quantity = 0;
        foreach ($cart as $key) {
            $food_detail = $foodlist::where('id', $key->food_id)->first();
            $amount = $amount + ($food_detail->price * $key->quantity);
            $quantity = $quantity + $key->quantity;
        }
        $food_cart[] = array(
            'amount' => $amount,
            'quantity' => $quantity,
        );

        $promocode_list_restaurant = [];
        $dt = Carbon::now();
        $promocode_list_restaurant = $this->promocode->where('restaurant_id', $restaurant_id)->where('status', '1')->whereRaw('"' . $dt . '" between `available_from` and `valid_till`')->get();

        $data = $this->foodrequest->where('id', $req_id)
            ->with('Restaurants.city_list')
            ->with('Restaurants.Area')
            ->first();

        $foodlist2 = $this->foodlist;

        $food2 = $foodlist2::where('food_list.restaurant_id', $data->restaurant_id)
            ->where('food_list.status', 1)
            ->join('menu', function ($join) {
                $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                $join->on('menu.id', '=', 'food_list.menu_id');
            })
            ->select('food_list.id as food_id', 'name', 'price', 'description', 'secondary_name', 'secondary_description', 'is_veg', 'tax', 'menu_name', 'food_list.image as image')
            ->orderBy('rank')
            ->get();
        // dd($data);

        if ($request->table) {
            $near_by_restaurants = [];
            return view(
                'user.restaurant-dining',
                compact(
                    'restaurant',
                    'cart',
                    'food',
                    'foodlist_addons',
                    'foodlist_sizes',
                    'food_cart',
                    'cuisines',
                    'last_3_reviews',
                    'ratings',
                    'near_by_restaurants',
                    'rating_statistics',
                    'promocode_list_restaurant'
                )
            );
        }
        // dd($data);
        return view(
            'store.revice',
            compact(
                'restaurant',
                'cart',
                'food',
                'foodlist_addons',
                'foodlist_sizes',
                'food_cart',
                'cuisines',
                'last_3_reviews',
                'ratings',
                'near_by_restaurants',
                'rating_statistics',
                'promocode_list_restaurant',
                'ses_user',
                'data',
                'food2',
            )
        );
    }

    public function fetchorder(Request $request)
    {

        $req_id = $request->id;

        $data = $this->foodrequest->where('id', $req_id)
            ->with('Restaurants.city_list')
            ->with('Restaurants.Area')
            ->get();

        $return_cart = array();
        foreach ($data as $datas) {
            foreach ($datas->Requestdetail as $detail) {
                $add_name = '';
                foreach ($detail->Foodlist->Add_ons as $add) {

                    $add_name = $add_name . $add->name;
                }
                $food_list_image = File::exists(public_path('product_image/') . $detail->Foodlist->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $detail->Foodlist->image : (File::exists(public_path('restaurant_uploads/') . $detail->Foodlist->restaurant_id . '/' . 'Product/' . $detail->Foodlist->image) ? BASE_URL   . RESTAURANT_UPLOADS_PATH . $detail->Foodlist->restaurant_id . '/' . 'Product/' . $detail->Foodlist->image : (File::exists(public_path('common_images/') . $detail->Foodlist->image) ? BASE_URL  . UPLOADS_PATH_COMMON . $detail->Foodlist->image : BASE_URL  . $detail->Foodlist->image));
                $id = $detail->Foodlist->id;
                $name = $detail->Foodlist->name;
                $isveg = $detail->Foodlist->isveg;
                $price = $detail->Foodlist->price;
                $quantity = $detail->quantity;
                $taxperc = $detail->Foodlist->tax;
                $restaurant = $detail->Foodlist->restaurant_id;
                $return_cart[] = array(
                    'id' => $id,
                    'name' => $name,
                    'isveg' => $isveg,
                    'price' => $price,
                    'quantity' => $quantity,
                    'restaurant' => $restaurant,
                    'taxperc' => $taxperc,
                    'addonsName' => "",
                    'addonsPrice' => 0,
                    'restaurant_name' => $datas->restaurant_name,
                    'restaurant_address' => $datas->Restaurants->address,
                    'restaurant_image' => "image",
                    'sizeName' => "",
                    'food_image' => $food_list_image,
                );
            }
        }
        return response()->json([
            'status' => true,
            'items' => $return_cart,
            'data' => $data

        ], 200);
    }

    public function revice(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'item_total' => 'required',
                'restaurant_packaging_charge' => 'required',
                'gst' => 'required',
                'bill_amount' => 'required',
                // 'coupon_code' => 'required',
                'food_id' => 'required',
                'food_qty' => 'required',
                'order_id' => 'required',

            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = ['status' => false, 'error_code' => 101, 'message' => $error_messages];
            return response()->json($response_array, 200);
        }
        $order_id = $request->order_id;
        $delete_old_detail = DB::table('request_detail')->where('request_id', $order_id)->delete();
        $foodrequest = $this->foodrequest;
        $cart = $this->cart;
        $trackorderstatus = $this->trackorderstatus;
        $settings = $this->settings;

        $data = $this->foodrequest->where('id', $order_id)
            ->with('Restaurants.city_list')
            ->with('Restaurants.Area')
            ->first();

        $old_order_amount = $data->bill_amount;

        $user_id = $data->users->id;

        $restaurant_id = $data->restaurants->id;

        $delivery_type = $data->delivery_type;



        $item_total = $request->item_total;


        $restaurant_packaging_charge = $request->restaurant_packaging_charge;
        $gst = $request->gst;
        $bill_amount = $request->bill_amount;


        $user_detail = $this->users::where('id', $user_id)->first();
        if (isset($user_detail) && ($user_detail->device_type == WEB || $request->device_type == WEB)) {

            $food_id = array();
            $food_qty = $food_quantity = $food_quantity_price = array();
            $food_ids = str_replace('"', '', (string) $request->food_id);
            $food_id = explode(',', $food_ids);
            $food_prices = str_replace('"', '', (string) $request->food_price);
            $food_price = explode(',', $food_prices);

            $food_qtys = str_replace('"', '', (string) $request->food_qty);
            $food_qty = explode(',', $food_qtys);

            $food_addons_all = explode('-', $request->addons);
            $food_addons = [];
            foreach ($food_addons_all as $key => $food_addons_of_each_product) {
                $eachProductAddons = explode(' ', $food_addons_of_each_product);

                if (isset($food_id[$key]))
                    $food_addons[$food_id[$key]] = $eachProductAddons;
            };

            $food_sizes_all = explode('-', $request->sizes);
            $food_sizes = [];
            foreach ($food_sizes_all as $key => $food_sizes_of_each_product) {
                $eachProductSizes = explode(' ', $food_sizes_of_each_product);

                if (isset($food_id[$key]))
                    $food_sizes[$food_id[$key]] = $eachProductSizes;
            };


            $food_quantitys = str_replace('"', '', (string) $request->food_quantity);
            $food_quantity = explode(',', $food_quantitys);
            $food_quantity_prices = str_replace('"', '', (string) $request->food_quantity_price);
            $food_quantity_price = explode(',', $food_quantity_prices);
        } else {

            $food_id = $request->food_id;
            $food_qty = $request->food_qty;
            $food_quantity = $request->food_quantity;
            $food_quantity_price = $request->food_quantity_price;
            $food_addons = [];
        }

        $food_ids = str_replace('"', '', (string) $request->food_id);
        $food_id = explode(',', $food_ids);

        $food_qtys = str_replace('"', '', (string) $request->food_qty);
        $food_qty = explode(',', $food_qtys);
        $food_prices = str_replace('"', '', (string) $request->food_price);
        $food_price = explode(',', $food_prices);

        $food_id_size = sizeof($food_id);
        $food_qty_size = sizeof($food_qty);
        $paid_type = $request->paid_type;
        $delivery_address_detail = $this->deliveryaddress::where('user_id', $user_id)->where('is_default', 1)->first();




        $restaurant_details = $this->restaurants->find($restaurant_id);
        if (!empty($restaurant_details->admin_commision)) {
            $admin_commision_setting = $restaurant_details->admin_commision;
        } else {
            $admin_commision_setting = $settings::where('key_word', 'admin_commission')->value('value');
        }

        $admin_commission = ($bill_amount / 100) * $admin_commision_setting;

        $delivery_boy_commission = $data->delivery_boy_commision;


        $restaurant_commission = $bill_amount - ($admin_commission + $delivery_boy_commission);


        $update = DB::table('requests')
            ->where('id', $order_id)
            ->update([
                'item_total' => $item_total,
                'restaurant_packaging_charge' => $restaurant_packaging_charge,
                'tax' => $gst,
                'bill_amount' => $bill_amount,
                'admin_commision' => $admin_commission,
                'restaurant_commision' => $restaurant_commission,
            ]);
        $request_id = $order_id;
        for ($i = 0; $i < $food_id_size; $i++) {
            $request_detail = new Requestdetail;
            $request_detail->request_id = $order_id;
            $request_detail->restaurant_id = $restaurant_id;
            $request_detail->food_id = $food_id[$i];
            $request_detail->quantity = $food_qty[$i];
            $request_detail->addon_list = '0';
            $request_detail->food_quantity_price =  ($food_qty[$i] * $food_price[$i]);
            $request_detail->food_quantity = $food_sizes[$food_id[$i]][0] ?? '';
            $request_detail->save();
            $request_detail_id = $request_detail->id;
            if (isset($food_addons[$food_id[$i]][0])) {
                $addon_ids = explode(',', $food_addons[$food_id[$i]][0]);
                for ($j = 0; $j < count($addon_ids); $j++) {
                    $add_ons = $this->add_ons->find($addon_ids[$j]);
                    if (!isset($add_ons->id) || empty($add_ons->id)) {
                        continue;
                    }
                    $requestdetail_addons = new RequestdetailAddons;
                    $requestdetail_addons->requestdetail_id = $request_detail_id;
                    $requestdetail_addons->addons_id = $add_ons->id;
                    $requestdetail_addons->name = $add_ons->name;
                    $requestdetail_addons->price = $add_ons->price;
                    $requestdetail_addons->save();
                }
            }
        }
        $response_array = [
            'status' => true,
            'data' => $order_id,
            'message' => 'Order reviced successfully'
        ];

        if ($old_order_amount > $bill_amount) {
            $amount = $old_order_amount - $bill_amount;

            $status_entry = array();

            $status_entry[] = array(
                'user_id' => $user_id,
                'amount' => $amount,
                'order_id' => $order_id,
                'topup_code' => '',
                'type' => 1, //1-credit 2-debit
                'status' => 1
            );

            DB::table('user_wallet_history')->insert($status_entry);

            $this->users->find($user_id)->increment('wallet_amount', $amount);
            $user_noti = DB::table('users')->where('id', $user_id)->first();
            $title = "Wallet";
            $message = 'Your wallet is topped up with Rs.' . $amount;
            sendNotification([$user_noti->device_token], $title, $message, ['amount' => $amount]);


            // $html = view('emails.restaurant.revise')->with(['order_id' => $order_id , 'amount' => $amount , 'user_detail'=>$user_detail]); 

            $admin = DB::table('admin')
                ->first();

            // $Mailer             = new Mailer();
            // $Mailer->to         = $admin->email;
            // $Mailer->toName     = $admin->name;
            // $Mailer->subject    = "Order Revised";
            // $Mailer->body       = $html;

            // $Mailer->send();
        }
        return response()->json($response_array, 200);
    }
    function printOrderBill(Request $request, $id)
    {

        $data = DB::table('requests')
            ->where('requests.id', $request->id)
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
            ->select('requests.id as request_id', 'requests.status as order_status', 'users.name as user_name', 'users.email as user_email', 'users.phone as user_phone', 'restaurants.email as restaurants_email', 'requests.*', 'users.*', 'restaurants.*')
            ->first();

        $food_list = DB::table('request_detail')->where('request_id', $request->id)
            ->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
            ->get();
        // dd($food_list);
        $rt_food = [];
        foreach ($food_list as $fl) {
            if (file_exists(public_path('/') . $fl->image)) {
                $img = BASE_URL . $fl->image;
            } else {
                $img = BASE_URL . "fork.svg";
            }

            $rt_food[] = [
                'Name' => $fl->name,
                'description' => $fl->description,
                'quantity' => $fl->quantity,
                'price' => $fl->food_quantity_price,
                'image' => $img,

            ];
        }



        $message = $data;
        $food = $rt_food;
        $status = true;


        $response_Array = json_encode(['message' => $message, 'status' => $status, 'food' => $food]);


        return $response_Array;
    }
    public function category_lists(Request $request)
    {

        $data =  DB::table('category')
            ->where('restaurant_id', $request->restaurant_id)
            ->orwhere('restaurant_id', null)
            ->where('business_category_id', $request->id)
            ->orderby('id', 'desc')
            ->get();

        return response()->json($data);
    }
    public function addons_list(Request $request)
    {

        $data =  DB::table('add_ons')->where('restaurant_id', $request->id)->orderby('id', 'desc')->get();
        foreach ($data as $d) {
            $d->name = $this->secondLanguage_store($d->name, $d->secondary_name);
        }
        return response()->json($data);
    }
    public function food_quantity(Request $request)
    {

        $data =  DB::table('food_quantity')->where('restaurant_id', $request->id)->orwhere('restaurant_id', null)->get();

        return response()->json($data);
    }

    function upload_product_csv(request $request)
    {

        $restaurant_id = $request->id;
        $res_type = DB::table('restaurants')->where('id', $restaurant_id)->first();
        $business_type = DB::table('business_type')->where('id', $res_type->business_type)->first();

        if (!$request->file('csv_file')) {
            // return back()->with('error', 'Please upload a file');
            $message = "Please upload a file";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        $extension = $request->csv_file->getClientOriginalExtension();

        if ($extension != 'csv') {
            // return back()->with('error', 'Please upload a CSV file');
            $message = "Please upload a CSV file";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        $filePath = $request->file('csv_file')->getPathName();

        if (!$filePath) {
            // return back()->with('error', 'Cannot get the file path');
            $message = "Cannot get the file path";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        $csvFile = $request->file('csv_file')->getPathName();
        $csvFileStringUTF = file($csvFile);
        $csvFileString = array_map(function ($text) {
            return str_replace("\r\n", '', $text);
        }, $csvFileStringUTF);
        // $csvFileString = array_map('trim', $csvFileStringUTF);
        $csvContent = array_map('str_getcsv', $csvFileStringUTF);

        $array_keys = array_shift($csvContent);
        $array_keys = array_map('utf8_decode', $array_keys);
        $array_keys = array_map(function ($text) {
            return str_replace("?", '', $text);
        }, $array_keys);
        // $array_keys_template = ["Name", "Secondary Name", "Description", "Secondary Description","Category", "Sub Category","Menu" , "Price", "Package Charge", "Is Veg", "Status", "Addons","Tax"];


        if ($business_type->layout_id == 2) {
            $array_keys_template = ["Name", "Secondary Name", "Description", "Secondary Description", "Category", "Sub Category",  "Price", "Package Charge", "Status", "Addons", "Tax"];
        } else {
            $array_keys_template = ["Name", "Secondary Name", "Description", "Secondary Description", "Category", "Menu", "Price", "Package Charge", "Is Veg", "Status", "Addons", "Tax"];
        }


        if ($array_keys_template != $array_keys) {
            // return back()->with('error', 'Uploaded CSV is not in expected format');
            $message = "Uploaded CSV is not in expected format";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        $products = [];
        $undefined_categories = [];
        $undefined_menus = [];
        $undefined_addons = [];
        $uploaded_addons = '';

        foreach ($csvContent as $key => $eachProduct) {

            $thisProduct = array_combine($array_keys, $eachProduct);

            $business_category_id   = $this->getBusinessCategoryIDByName($thisProduct['Category']);
            if ($business_type->layout_id == 2) {

                if ($thisProduct['Sub Category']) {
                    $category_id         = $this->getCategoryIDByName($thisProduct['Sub Category']);
                    $menu_id             = '';
                    if ($category_id) {
                    } else {
                        $error_msg = "Sub category is missing so  Please create them first or upload with correct values";
                        // return back()->with('error', $error_msg);
                        $message =  $error_msg;
                        $status = false;
                        $response_Array = json_encode(['message' => $message, 'status' => $status]);
                        return $response_Array;
                    }
                } else {
                    $error_msg = "Sub category is missing so  Please create them first or upload with correct values";
                    // return back()->with('error', $error_msg);
                    $message =  $error_msg;
                    $status = false;
                    $response_Array = json_encode(['message' => $message, 'status' => $status]);
                    return $response_Array;
                }
            } else {
                $category_id         = '';
                if ($thisProduct['Menu']) {
                    $menu_id             = $this->getMenuIDByName($restaurant_id, $thisProduct['Menu']);
                } else {
                    $error_msg = "Menu is missing so  Please create them first or upload with correct values";
                    // return back()->with('error', $error_msg);
                    $message =  $error_msg;
                    $status = false;
                    $response_Array = json_encode(['message' => $message, 'status' => $status]);
                    return $response_Array;
                }
            }
            if (!$business_category_id) {
                if (!in_array($thisProduct['Category'], $undefined_categories)) {
                    $undefined_categories[] = $thisProduct['Category'];
                }
            }
            $uploaded_addons     .= ',' . $thisProduct['Addons'];
            $thisProduct['CategoryID']         = $business_category_id;
            $thisProduct['Sub CategoryID']         = $category_id;
            $thisProduct['MenuID']             = $menu_id;
            $thisProduct['RestaurantID']     = $restaurant_id;

            // if (!$category_id || !$menu_id ) {

            //     if (!$category_id) {
            //         if (!in_array($thisProduct['Category'], $undefined_categories)) {
            //             $undefined_categories[] = $thisProduct['Category'];
            //         }
            //     }

            //     if (!$menu_id) {
            //         if (!in_array($thisProduct['Menu'], $undefined_menus)) {
            //             $undefined_menus[] = $thisProduct['Menu'];
            //         }
            //     }

            //     continue;
            // }

            $products[] = $thisProduct;
        }

        $uploaded_addons = array_values(array_unique(array_filter(explode(',', $uploaded_addons))));
        foreach ($uploaded_addons as $each_addon) {
            if (!$this->is_addon_exists($each_addon, $restaurant_id)) {
                $undefined_addons[] = $each_addon;
            }
        }

        $error_msg = '';

        if ($business_type->layout_id == 2) {
            if ($undefined_categories) {
                $error_msg = "The following categories does not exist. --- " . implode(', ', $undefined_categories) . ' --- ';
            }
        } else {
            if ($undefined_categories) {
                $error_msg = "The following categories does not exist. --- " . implode(', ', $undefined_categories) . ' --- ';
            }
            // if (!$error_msg && $undefined_menus) {
            //     $error_msg = "The following menus does not exist. --- " . implode(', ', $undefined_menus) . ' --- ';
            // }
        }


        if (!$error_msg && $undefined_addons) {
            $error_msg = "The following addons does not exist. --- " . implode(', ', $undefined_addons) . ' --- ';
        }

        if ($error_msg) {
            $error_msg = "Bulk upload failed. $error_msg Please create them first or upload with correct values";
            // return back()->with('error', $error_msg);
            $message =  $error_msg;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        $totalProducts = count($products);

        if (!$totalProducts) {
            // return back()->with('error', "It seems the file is empty");
            $message = "It seems the file is empty";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        $uploaded_products = 0;
        if ($business_type->layout_id == 2) {
            foreach ($products as $eachProduct) {
                if ($this->is_product_exists($eachProduct['Name'], $eachProduct['RestaurantID'], $eachProduct['Sub CategoryID'], $eachProduct['MenuID'])) {
                    $this->update_product1($eachProduct);
                } else {
                    $this->store_product1($eachProduct);
                    $uploaded_products++;
                }
            }
        } else {
            foreach ($products as $eachProduct) {
                if ($this->is_product_exists($eachProduct['Name'], $eachProduct['RestaurantID'], $eachProduct['Sub CategoryID'], $eachProduct['MenuID'])) {
                    $this->update_product($eachProduct);
                } else {
                    $this->store_product($eachProduct);
                    $uploaded_products++;
                }
            }
        }


        $existing_products = $totalProducts - $uploaded_products;

        $existing_msg = '';
        if ($existing_products) {
            $existing_msg = "$existing_products product(s) updated";
        }

        if ($uploaded_products == 0) {
            // return back()->with('error', "Cannot upload products. $existing_msg");
        }

        // dd("Success. $uploaded_products Product(s) are uploaded. $existing_msg");
        $message = "Success. $uploaded_products Product(s) are uploaded. $existing_msg";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
        // return back()->with('success', "Success. $uploaded_products Product(s) are uploaded. $existing_msg");
    }

    function generate_product_csv(Request $request)
    {

        $restaurant_id = $request->id;

        $products = DB::table('food_list')
            ->select(
                'food_list.id',
                'food_list.name',
                'food_list.description',
                'food_list.secondary_name',
                'food_list.secondary_description',
                'food_list.price',
                'food_list.tax',
                'food_list.packaging_charge',
                'food_list.is_veg',
                'food_list.status',
                'category_name',
                'menu_name'
            )
            ->leftJoin('category', 'category.id', '=', 'food_list.category_id')
            ->leftJoin('business_category', 'business_category.id', '=', 'food_list.business_category_id')
            ->leftJoin('menu', 'menu.id', '=', 'food_list.menu_id')
            ->where('food_list.restaurant_id', $restaurant_id)->where('food_list.status', 1)
            ->select('category.*', 'menu.*', 'food_list.*', 'business_category.category_name as business_name')->get();

        $productList = [];

        $rest = DB::table('restaurants')->where('id', $restaurant_id)->first();
        $layout = DB::table('business_type')->where('id', $rest->business_type)->first();
        if ($layout->layout_id == 2) {
            $array_keys_template = [
                "Name", "Secondary Name", "Description", "Secondary Description", "Category", "Sub Category", "Price", "Package Charge", "Status", "Addons", "Tax"
            ];
        } else {
            $array_keys_template = [
                "Name", "Secondary Name", "Description", "Secondary Description", "Category", "Menu", "Price", "Package Charge", "Is Veg", "Status", "Addons", "Tax"
            ];
        }

        $productList[] = $array_keys_template;


        foreach ($products as $eachProduct) {
            $addons = $this->get_addon_list_of_product($eachProduct->id);
            $eachProduct->addons = implode(',', $addons);

            // $thisNewProduct = [
            //     'Name'                      => $eachProduct->name,
            //     'Secondary Name'            => $eachProduct->secondary_name,
            //     'Description'               => $eachProduct->description,
            //     'Secondary Description'     => $eachProduct->secondary_description,
            //     'Category'                  => $eachProduct->category_name,
            //     'Menu'                      => $eachProduct->menu_name,
            //     'Price'                     => $eachProduct->price,
            //     'Package Charge'            => $eachProduct->packaging_charge,
            //     'Is Veg'                    => $eachProduct->is_veg,
            //     'Status'                    => $eachProduct->status,
            //     'Addons'                    => $eachProduct->addons,
            // ];
            if ($layout->layout_id == 2) {
                $thisNewProduct = [
                    $eachProduct->name,
                    $eachProduct->secondary_name,
                    $eachProduct->description,
                    $eachProduct->secondary_description,
                    $eachProduct->business_name,
                    $eachProduct->category_name,
                    $eachProduct->price,
                    $eachProduct->packaging_charge,
                    // $eachProduct->is_veg,
                    $eachProduct->status,
                    $eachProduct->addons,
                    $eachProduct->tax
                ];
            } else {
                $thisNewProduct = [
                    $eachProduct->name,
                    $eachProduct->secondary_name,
                    $eachProduct->description,
                    $eachProduct->secondary_description,
                    $eachProduct->business_name,
                    $eachProduct->menu_name,
                    $eachProduct->price,
                    $eachProduct->packaging_charge,
                    $eachProduct->is_veg,
                    $eachProduct->status,
                    $eachProduct->addons,
                    $eachProduct->tax
                ];
            }


            $productList[] = $thisNewProduct;
        }

        $filePath = (public_path() . '/uploads/');
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        $fileName = $filePath . '' . $restaurant_id . '_products.csv';
        $fp = fopen($fileName, 'w+');
        foreach ($productList as $eachRow) {
            fputcsv($fp, $eachRow);
        }
        fclose($fp);

        // return back()->with('success', "CSV generated successfully");

        $message = "CSV generated successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    function toggleStatus1(Request $request)
    {

        $id = $request->id;

        $rest = DB::table('restaurants')->where('id', $id)->first();

        $is_open = $rest->is_open;

        $current_time = date('H:i:s');
        $date = date("Y-m-d");
        $weekday = date('w');
        $weekday = $weekday == 0 ? 7 : $weekday;
        $opening_var = 'opening_time_' . $weekday;
        $closing_var = 'closing_time_' . $weekday;

        $opening_second_var = 'opening_second_time_' . $weekday;
        $closing_second_var = 'closing_second_time_' . $weekday;
        if ($rest->$opening_second_var == "") {
            $noon = 0;
        } else {
            if (
                (strtotime($rest->$opening_second_var) <= strtotime($current_time)) &&
                (strtotime($rest->$closing_second_var) >= strtotime($current_time))
            ) {
                $noon = 0;
            } else {
                $noon = 1;
            }
        }
        $noon = 1;
        $message = "Changed Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'is_open' => $is_open, 'noon' => $noon]);
        return $response_Array;
    }
    public function document_view(Request $request)
    {
        $document_list = DB::table('document')->where('status', 1)->get();
        return response()->json($document_list);
    }
    // public function restaurant_map(Request $request)
    // {
    //     $data = DB::table('restaurants')->where('id', $request->id)->first();
    //     $this_driver = DB::table('delivery_partners')->where('id', $driver_key)->get();
    //     $response_Array = json_encode(['data'=>$data,'this_driver'=>$this_driver]);
    //     return $response_Array;
    //     // return response()->json($data) ;


    // }

    public function get_food_list_by_menu(Request $request)
    {



        $restaurant_id = $request->restaurant_id;
        $foodlist = $this->foodlist;
        $category = $this->category;
        $cart = $this->cart;
        $restaurants = $this->restaurants;
        $order_id = $request->order_id;

        $order_details = DB::table('requests')->where('id', $order_id)->first();

        $user_id = $order_details->user_id;

        if (empty($user_id)) {
            // return response()->json(['status' => false, 'error_code' => 101, 'message' => 'User not found'], 200);
        }

        // $user_id = $request->header('authId');
        $is_veg = $request->is_veg;

        $check_for_cart = $cart::where('user_id', $user_id)->get();
        $rest = DB::table('restaurants')->where('id', $restaurant_id)->first();
        $business_type = DB::table('business_type')->where('id', $rest->business_type)->first();
        if ($business_type->layout_id == 2) {
            $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                ->join('category', 'category.id', 'food_list.category_id')
                ->where('food_list.status', 1)

                ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'category.category_name', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax',  'food_list.image as image')

                ->orderBy('image', 'desc')
                ->get();
        } else {
            $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                ->where('food_list.status', 1)
                ->join(
                    'menu',
                    function ($join) {
                        $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                        $join->on('menu.id', '=', 'food_list.menu_id');
                    }
                )
                ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image')
                //->where('food_list.name', 'BBQ Chicken Wings')
                ->orderBy('rank')->orderBy('image', 'desc')
                ->get();
        }



        foreach ($food_list as $key => $each_food) {
            // if ($each_food->image != "") {
            // $food_list[$key]->image = BASE_URL  . $each_food->image;

            // if(File::exists(public_path('product_image/').$each_food->image)){
            //     $food_list[$key]->image = BASE_URL  . UPLOADS_PATH_PRODUCT.$each_food->image;
            // }elseif(File::exists(public_path('restaurant_uploads/').$restaurant_id.'/'.'Product/'.$each_food->image)){
            //     $food_list[$key]->image = BASE_URL  . BASE_URL  . RESTAURANT_UPLOADS_PATH.$restaurant_id.'/'.'Product/'.$each_food->image 
            // }elseif(File::exists(public_path('common_images/').$each_food->image)){
            //     $food_list[$key]->image = BASE_URL  . UPLOADS_PATH_COMMON.$each_food->image:$each_food->image;
            // }else{

            // }

            //     $food_list[$key]->image = File::exists(public_path('product_image/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $each_food->image) ? BASE_URL  . BASE_URL  . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_COMMON . $each_food->image : BASE_URL  . $each_food->image));
            // } else {
            //     $food_list[$key]->image = BASE_URL  . "fork.svg";
            // }

            if ($each_food->image != "") {


                $food_list[$key]->image = File::exists(public_path('product_image/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $each_food->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_COMMON . $each_food->image : BASE_URL  . $each_food->image));
            } else {
                $food_list[$key]->image = BASE_URL  . "fork.svg";
            }
        }

        foreach ($food_list as $key => $each_food) {
            $this_addon = DB::table('foodlist_addons')
                ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select('foodlist_addons.addons_id as id', 'add_ons.name',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at')
                ->get()->toArray();

            if (!$this_addon) {
                $food_list[$key]->add_ons = [];
                continue;
            }

            foreach ($this_addon as $addon_key => $each_addon) {
                $this_addon[$addon_key]->restaurant_id = $restaurant_id;
                $this_addon[$addon_key]->pivot = ['foodlist_id' => $each_food->food_id, 'addons_id' => $each_addon->id];
            }

            $food_list[$key]->add_ons = $this_addon;
        }

        foreach ($food_list as $key => $each_food) {

            $item_count = 0;
            if (count($check_for_cart) != 0) {
                foreach ($check_for_cart as $check_for_item) {
                    if ($each_food->food_id == $check_for_item->food_id) {
                        $item_count = $check_for_item->quantity;
                    }
                }
            }
            $food_list[$key]->item_count = $item_count;


            $this_size = DB::table('foodlist_foodquantity')
                ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select(
                    'foodlist_foodquantity.foodquantity_id as id',
                    'food_quantity.name',
                    'food_quantity.secondary_name',
                    'foodlist_foodquantity.price',
                    'foodlist_foodquantity.is_default',
                    'foodlist_foodquantity.created_at',
                    'foodlist_foodquantity.updated_at'
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }

            foreach ($this_size as $size_key => $each_size) {
                $this_size[$size_key]->pivot = [
                    'foodlist_id' => $each_food->food_id,
                    'foodquantity_id' => $each_size->id,
                    'price' => $each_size->price,
                    'is_default' => $each_size->is_default
                ];
            }

            $food_list[$key]->food_quantity = $this_size;
        }

        $new_food_list = [];

        foreach ($food_list as $key => $each_food) {

            if ($is_veg == 1 && $each_food->is_veg == 0) {
                continue;
            }
            if ($business_type->layout_id == 2) {
                $new_food_list[$each_food->category_id][] = $each_food;
            } else {
                $new_food_list[$each_food->menu_id][] = $each_food;
            }
        }

        $return_food_list = [];
        foreach ($new_food_list as $key => $each_menu) {
            $available = 0;
            if ($business_type->layout_id == 2) {
            } else {
                $menutime = DB::table('menu_timing')->where('menu_id', $each_menu[0]->menu_id)->get();
                if (sizeof($menutime)) {

                    foreach ($menutime as $key => $time) {
                        $times = Carbon::now();
                        $current_time = $times->toTimeString();
                        $date1 = $current_time;
                        $date2 = $time->from_time;
                        $date3 = $time->to_time;
                        if ($date1 > $date2 && $date1 < $date3) {
                            $available = 1;
                        }
                    }
                } else {
                    $available = 1; //latest feature so some menu dont assume time so set is available
                }
            }

            // checking menu avaible now

            if ($business_type->layout_id == 2) {
                $return_food_list[] = [
                    'menu_id' => $each_menu[0]->category_id,
                    'menu_available' => $available,
                    'menu_name' => $each_menu[0]->category_name,
                    'items' => $each_menu,
                ];
            } else {
                $return_food_list[] = [
                    'menu_id' => $each_menu[0]->menu_id,
                    'menu_available' => $available,
                    'menu_name' => $each_menu[0]->menu_name,
                    'items' => $each_menu,
                ];
            }
        }
        // print_r($return_food_list);
        $restaurant_detail = $restaurants::where('id', $restaurant_id)->select('id', 'restaurant_name', 'image', 'address')->first();
        if (!$restaurant_detail) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Restaurant not found'];
            return response()->json($response_array, 200);
        }

        $return_food_list = $this->updateSecondaryLang($return_food_list, $request);
        return response()->json([
            'status' => true,
            'recommended' => [],
            'items' => $return_food_list,
            'restaurant_detail' => $restaurant_detail
        ], 200);
    }

    public function single_restaurant(Request $request)
    {

        $restaurant_id = $request->restaurant_id;
        $veg_only = $request->veg_only;
        $restaurants = $this->restaurants;
        $foodlist = $this->foodlist;
        $menu = $this->menu;
        $cart = $this->cart;
        $scheduleAt = $request->date . ' ' . $request->time;


        // $user_id = $request->header('authId');

        $rating_statistics = get_rating_statistics($restaurant_id);

        $restaurant_cuisines = DB::table('restaurant_cuisines')
            ->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
            ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
            ->select('restaurant_cuisines.restaurant_id as restaurant_id', 'cuisines.name as cuisine_name', 'restaurants.restaurant_name as restaurant_name')
            ->get();

        $data = $restaurants::where('id', $restaurant_id)->first();
        $promocode_list_restaurant = [];
        $dt = Carbon::now();
        $promocode_list_restaurant = $this->promocode->where('restaurant_id', $restaurant_id)->where('status', '1')->whereRaw('"' . $dt . '" between `available_from` and `valid_till`')->get();
        // $promocode_list_restaurant = ['promocode_list_restaurant'=>$promocode_list_restaurant];

        $food_filter = $foodlist::where('restaurant_id', $restaurant_id)->where('status', 1)->get();

        $restaurant_list = array();
        $rcuisines = array();
        $i = 0;

        foreach ($restaurant_cuisines as $r_cuisines) {
            if ($data->restaurant_name == $r_cuisines->restaurant_name && $i < 2) { // To display only two cuisines
                $rcuisines[] = array(
                    'name' => $r_cuisines->cuisine_name
                );

                $i = $i + 1;
            }
        }

        $foods = array();
        $j = 0;




        $is_open = restaurant_is_open($data->id);
        $video = json_decode($data->video_url);
        $title = json_decode($data->video_title);
        $delivery_charge_limits = DB::table('delivery_charge_limits')->select('Minimum', 'Maximum', 'Value', 'Type')
            ->where('restaurant_id', $data->id)->get()->toArray();
        $tax1 = DB::table('settings')->where('key_word', 'tax_inclusive')->first();
        $tax = $tax1->value;
        if ($request->date && $request->time) {
            $isOpen_schedule = Restaurant::isOpenAt($data->id, $scheduleAt);

            $restaurant_list[] = array(
                'id'            => $data->id,
                'name' => $data->restaurant_name,
                'phone' => $data->phone,
                'email' => $data->email,
                'delivery_type'          => json_decode($data->delivery_type),


                'discount'      => $data->discount,

                'is_open'       => $isOpen_schedule,

                'travel_time'   => $data->estimated_delivery_time,


                'delivery_charge_limits' => $delivery_charge_limits,
                'packaging_charge' => $data->packaging_charge,
                'DeliveryChargeBasedOn' => $data->DeliveryChargeBasedOn,
                'delivery_charge_type' => $data->delivery_charge_type ? $data->delivery_charge_type : 1,
                'tax' => $tax,


            );
        } else {
            $restaurant_list[] = array(
                'id'            => $data->id,
                'name' => $data->restaurant_name,
                'phone' => $data->phone,
                'email' => $data->email,
                'delivery_type'          => json_decode($data->delivery_type),



                'discount'      => $data->discount,

                'is_open'       => $is_open,

                'travel_time'   => $data->estimated_delivery_time,


                'delivery_charge_limits' => json_encode($delivery_charge_limits),
                'packaging_charge' => $data->packaging_charge,
                'DeliveryChargeBasedOn' => $data->DeliveryChargeBasedOn,
                'offer_discount' => $data->offer_discount,
                'delivery_charge_type' => $data->delivery_charge_type ? $data->delivery_charge_type : 1,
                'tax' => $tax,


            );
        }


        $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'promocodes' => $promocode_list_restaurant);

        $response = response()->json($response_array, 200);
        return $response;
    }


    public function search_products(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            ['key_word' => '']
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = ['status' => false, 'error_code' => 101, 'message' => $error_messages];
            return response()->json($response_array, 200);
        }




        $restaurants = $this->restaurants;
        $key_word = $request->key_word;






        $food_list = DB::table('food_list')
            ->where('restaurant_id', $request->rest_id)
            ->where('name', 'like', '%' . $key_word . '%')
            ->join('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
            ->join('category', 'category.id', '=', 'food_list.category_id')
            ->where('food_list.status', 1)
            ->where('restaurants.status', 1)


            ->select(
                'food_list.*',
                'restaurants.restaurant_name',
                'category.category_name'
            )
            ->orderby('restaurants.id', 'asc')
            ->get();





        $rt2 = [];
        foreach ($food_list as $dat) {
            $img = BASE_URL  . $dat->image;
            $rt2[] = [
                'img' => $img,
                'name' => $dat->name,
                'restaurant_name' => $dat->restaurant_name,
                'restaurant_id' => $dat->restaurant_id,
            ];
        }

        $message = $rt2;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'food_list' => $rt2]);
        return $response_Array;
    }


    public function search_products_from_name(Request $request)
    {


        $restaurant_id = $request->restaurant_id;
        $foodlist = $this->foodlist;
        $category = $this->category;
        $cart = $this->cart;
        $restaurants = $this->restaurants;
        $order_id = $request->order_id;





        $is_veg = $request->is_veg;


        $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
            ->where('name', $request->key_word)
            ->where('food_list.status', 1)
            ->join(
                'menu',
                function ($join) {
                    $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                    $join->on('menu.id', '=', 'food_list.menu_id');
                }
            )
            ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image')
            ->orderBy('rank')->orderBy('image', 'desc')
            ->get();

        foreach ($food_list as $key => $each_food) {
            if ($each_food->image != "") {


                $food_list[$key]->image = File::exists(public_path('product_image/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $each_food->image) ? BASE_URL    . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_COMMON . $each_food->image : BASE_URL  . $each_food->image));
            } else {
                $food_list[$key]->image = BASE_URL  . "fork.svg";
            }
        }

        foreach ($food_list as $key => $each_food) {
            $this_addon = DB::table('foodlist_addons')
                ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select('foodlist_addons.addons_id as id', 'add_ons.name',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at')
                ->get()->toArray();

            if (!$this_addon) {
                $food_list[$key]->add_ons = [];
                continue;
            }

            foreach ($this_addon as $addon_key => $each_addon) {
                $this_addon[$addon_key]->restaurant_id = $restaurant_id;
                $this_addon[$addon_key]->pivot = ['foodlist_id' => $each_food->food_id, 'addons_id' => $each_addon->id];
            }

            $food_list[$key]->add_ons = $this_addon;
        }

        foreach ($food_list as $key => $each_food) {

            $item_count = 0;

            $food_list[$key]->item_count = $item_count;


            $this_size = DB::table('foodlist_foodquantity')
                ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select(
                    'foodlist_foodquantity.foodquantity_id as id',
                    'food_quantity.name',
                    'food_quantity.secondary_name',
                    'foodlist_foodquantity.price',
                    'foodlist_foodquantity.is_default',
                    'foodlist_foodquantity.created_at',
                    'foodlist_foodquantity.updated_at'
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }

            foreach ($this_size as $size_key => $each_size) {
                $this_size[$size_key]->pivot = [
                    'foodlist_id' => $each_food->food_id,
                    'foodquantity_id' => $each_size->id,
                    'price' => $each_size->price,
                    'is_default' => $each_size->is_default
                ];
            }

            $food_list[$key]->food_quantity = $this_size;
        }

        $new_food_list = [];

        foreach ($food_list as $key => $each_food) {

            if ($is_veg == 1 && $each_food->is_veg == 0) {
                continue;
            }

            $new_food_list[$each_food->menu_id][] = $each_food;
        }

        $return_food_list = [];
        foreach ($new_food_list as $key => $each_menu) {

            // checking menu avaible now
            $available = 0;
            $menutime = DB::table('menu_timing')->where('menu_id', $each_menu[0]->menu_id)->get();
            if (sizeof($menutime)) {

                foreach ($menutime as $key => $time) {
                    $times = Carbon::now();
                    $current_time = $times->toTimeString();
                    $date1 = $current_time;
                    $date2 = $time->from_time;
                    $date3 = $time->to_time;
                    if ($date1 > $date2 && $date1 < $date3) {
                        $available = 1;
                    }
                }
            } else {
                $available = 1; //latest feature so some menu dont assume time so set is available
            }

            $return_food_list[] = [
                'menu_id' => $each_menu[0]->menu_id,
                'menu_available' => $available,
                'menu_name' => $each_menu[0]->menu_name,
                'items' => $each_menu,
            ];
        }
        // print_r($return_food_list);
        $restaurant_detail = $restaurants::where('id', $restaurant_id)->select('id', 'restaurant_name', 'image', 'address')->first();
        if (!$restaurant_detail) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Restaurant not found'];
            return response()->json($response_array, 200);
        }

        $return_food_list = $this->updateSecondaryLang($return_food_list, $request);
        return response()->json([
            'status' => true,
            'recommended' => [],
            'items' => $return_food_list,
            'restaurant_detail' => $restaurant_detail
        ], 200);
    }

    public function search_users(Request $request)
    {

        $key = $request->key_word;

        $users = DB::table('users')
            ->where('name', 'like', '%' . $key . '%')
            ->orwhere('phone', 'like', '%' . $key . '%')
            ->get();

        $rt2 = [];
        foreach ($users as $dat) {
            $img = BASE_URL  .  'uploads/' . $dat->profile_image;

            $rt2[] = [
                'img' => $img,
                'name' => $dat->name,
                'phone' => $dat->phone,
                'id' => $dat->id,
            ];
        }

        $message = $rt2;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'user_list' => $rt2]);
        return $response_Array;
    }
    public function get_food_list_by_menu_pos(Request $request)
    {



        $restaurant_id = $request->restaurant_id;
        $foodlist = $this->foodlist;
        $category = $this->category;
        $cart = $this->cart;
        $restaurants = $this->restaurants;


        $rest = DB::table('restaurants')->where('id', $restaurant_id)->first();
        $business_type = DB::table('business_type')->where('id', $rest->business_type)->first();


        // $user_id = $request->header('authId');
        $is_veg = $request->is_veg;
        if ($business_type->layout_id == 2) {
            $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                ->join('category', 'category.id', 'food_list.category_id')
                ->where('food_list.status', 1)

                ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'category.category_name', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax',  'food_list.image as image')

                ->orderBy('image', 'desc')
                ->get();
        } else {

            $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                ->where('food_list.status', 1)
                ->join(
                    'menu',
                    function ($join) {
                        $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                        $join->on('menu.id', '=', 'food_list.menu_id');
                    }
                )
                ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image')

                ->orderBy('rank')->orderBy('image', 'desc')
                ->get();
        }



        foreach ($food_list as $key => $each_food) {
            if ($each_food->image != "") {


                $food_list[$key]->image = File::exists(public_path('product_image/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $each_food->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_COMMON . $each_food->image : BASE_URL  . $each_food->image));
            } else {
                $food_list[$key]->image = BASE_URL  . "fork.svg";
            }
        }

        foreach ($food_list as $key => $each_food) {
            $this_addon = DB::table('foodlist_addons')
                ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select('foodlist_addons.addons_id as id', 'add_ons.name',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at')
                ->get()->toArray();

            if (!$this_addon) {
                $food_list[$key]->add_ons = [];
                continue;
            }

            foreach ($this_addon as $addon_key => $each_addon) {
                $this_addon[$addon_key]->restaurant_id = $restaurant_id;
                $this_addon[$addon_key]->pivot = ['foodlist_id' => $each_food->food_id, 'addons_id' => $each_addon->id];
            }

            $food_list[$key]->add_ons = $this_addon;
        }

        foreach ($food_list as $key => $each_food) {

            $item_count = 0;

            $food_list[$key]->item_count = $item_count;


            $this_size = DB::table('foodlist_foodquantity')
                ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
                ->where('foodlist_id', $each_food->food_id)
                ->select(
                    'foodlist_foodquantity.foodquantity_id as id',
                    'food_quantity.name',
                    'food_quantity.secondary_name',
                    'foodlist_foodquantity.price',
                    'foodlist_foodquantity.is_default',
                    'foodlist_foodquantity.created_at',
                    'foodlist_foodquantity.updated_at'
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }

            foreach ($this_size as $size_key => $each_size) {
                $this_size[$size_key]->pivot = [
                    'foodlist_id' => $each_food->food_id,
                    'foodquantity_id' => $each_size->id,
                    'price' => $each_size->price,
                    'is_default' => $each_size->is_default
                ];
            }

            $food_list[$key]->food_quantity = $this_size;
        }

        $new_food_list = [];

        foreach ($food_list as $key => $each_food) {

            if ($is_veg == 1 && $each_food->is_veg == 0) {
                continue;
            }
            if ($business_type->layout_id == 2) {
                $new_food_list[$each_food->category_id][] = $each_food;
            } else {
                $new_food_list[$each_food->menu_id][] = $each_food;
            }
        }

        $return_food_list = [];
        foreach ($new_food_list as $key => $each_menu) {

            // checking menu avaible now
            $available = 0;
            if ($business_type->layout_id == 2) {
            } else {
                $menutime = DB::table('menu_timing')->where('menu_id', $each_menu[0]->menu_id)->get();
                if (sizeof($menutime)) {

                    foreach ($menutime as $key => $time) {
                        $times = Carbon::now();
                        $current_time = $times->toTimeString();
                        $date1 = $current_time;
                        $date2 = $time->from_time;
                        $date3 = $time->to_time;
                        if ($date1 > $date2 && $date1 < $date3) {
                            $available = 1;
                        }
                    }
                } else {
                    $available = 1; //latest feature so some menu dont assume time so set is available
                }
            }

            if ($business_type->layout_id == 2) {
                $return_food_list[] = [
                    'menu_id' => $each_menu[0]->category_id,
                    'menu_available' => $available,
                    'menu_name' => $each_menu[0]->category_name,
                    'items' => $each_menu,
                ];
            } else {
                $return_food_list[] = [
                    'menu_id' => $each_menu[0]->menu_id,
                    'menu_available' => $available,
                    'menu_name' => $each_menu[0]->menu_name,
                    'items' => $each_menu,
                ];
            }
        }
        // print_r($return_food_list);
        $restaurant_detail = $restaurants::where('id', $restaurant_id)->select('id', 'restaurant_name', 'image', 'address')->first();
        if (!$restaurant_detail) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Restaurant not found'];
            return response()->json($response_array, 200);
        }

        $return_food_list = $this->updateSecondaryLang($return_food_list, $request);


        return response()->json([
            'status' => true,
            'recommended' => [],
            'items' => $return_food_list,
            'restaurant_detail' => $restaurant_detail,
            'business_type' => $business_type->layout_id,
        ], 200);
    }

    public function create_pos(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'restaurant_id' => 'required',
                'item_total' => 'required',
                //'offer_discount' => 'required',
                'restaurant_packaging_charge' => 'required',
                'gst' => 'required',
                'delivery_charge' => 'required',
                'bill_amount' => 'required',
                //'coupon_code' => 'required',
                'food_id' => 'required',
                'food_qty' => 'required',
                'paid_type' => 'required',
                // 'delivery_type' => 'required',
                // 'delivery_address' => 'required'
            ]
        );
        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = ['status' => false, 'error_code' => 101, 'message' => $error_messages];
            return response()->json($response_array, 200);
        }
        $is_cod = 1;
        $table_name = null;
        $table_code = ''; //$request->session()->get('table_code');
        $table = DB::table('tables')->where('table_code', $table_code)->first();
        if ($table) {
            $table_name = $table->table_name;
        }
        $foodrequest = $this->foodrequest;
        $cart = $this->cart;
        $trackorderstatus = $this->trackorderstatus;
        $settings = $this->settings;
        if ($request->user_id == -1) {
            $user = DB::table('users')->where('email', 'dining@deliware.app')->first();

            $user_id = $user->id;
        } else {
            $user_id = $request->user_id;
        }
        if ($user_id != -1 && $request->paid_type == 2) {

            $chk_wal = DB::table('users')->where('id', $user_id)->first();

            if ($request->bill_amount > $chk_wal->wallet_amount) {
                $response_array = [
                    'status' => false,
                    'data' => [],
                    'message' => 'Insufficient Wallet Amount'
                ];

                return response()->json($response_array, 200);
            }
        }
        if ($table_name && !$user_id) {
            $user = DB::table('users')->where('email', 'dining@deliware.app')->first();
            if ($user) {
                $user_id = $user->id;
            }
        }
        if ($user_id == 0) {
            $response_array = [
                'status' => false,
                'data' => [],
                'message' => 'Please login to continue'
            ];

            return response()->json($response_array, 200);
        }

        $restaurant_id = $request->restaurant_id;
        $item_total = $request->item_total;
        if (isset($request->offer_discount)) {
            $offer_discount = $request->offer_discount;
        } else {
            $offer_discount = 0;
        }
        $restaurant_packaging_charge = $request->restaurant_packaging_charge;
        $gst = $request->gst;
        $tips = $request->tips;
        $delivery_charge = $request->delivery_charge;
        $bill_amount = $request->bill_amount;
        if (isset($request->coupon_code)) {
            $coupon_code = $request->coupon_code;

            $Coupon = Coupon::findByCode($coupon_code, $request->restaurant_id);

            //check already applied coupon
            $usedCountOfCustomer = DB::table('requests')
                ->where('coupon_code', $coupon_code)
                ->where('user_id', $user_id)
                ->whereNotIn('status', [9, 10, 11, -1])->count(); //echo $usedCountOfCustomer;
            if ($usedCountOfCustomer >= $Coupon->use_per_customer) {
                $response_array = [
                    'status' => false,
                    'data' => [],
                    'message' => 'You have exceeded the maximum usage limit of this coupon'
                ];
                return response()->json($response_array, 200);
            }
        } else {
            $coupon_code = 'NA';
        }
        $delivery_type = 3;

        $user_detail = $this->users::where('id', $user_id)->first();
        if (isset($user_detail) && ($user_detail->device_type == WEB || $request->device_type == WEB)) {
            $food_id = array();
            $food_qty = $food_quantity = $food_quantity_price = array();
            $food_ids = str_replace('"', '', (string) $request->food_id);
            $food_id = explode(',', $food_ids);
            $food_prices = str_replace('"', '', (string) $request->food_price);
            $food_price = explode(',', $food_prices);

            $food_qtys = str_replace('"', '', (string) $request->food_qty);
            $food_qty = explode(',', $food_qtys);

            $food_addons_all = explode('-', $request->addons);
            $food_addons = [];
            foreach ($food_addons_all as $key => $food_addons_of_each_product) {
                $eachProductAddons = explode(' ', $food_addons_of_each_product);

                if (isset($food_id[$key]))
                    $food_addons[$food_id[$key]] = $eachProductAddons;
            };

            $food_sizes_all = explode('-', $request->sizes);
            $food_sizes = [];
            foreach ($food_sizes_all as $key => $food_sizes_of_each_product) {
                $eachProductSizes = explode(' ', $food_sizes_of_each_product);

                if (isset($food_id[$key]))
                    $food_sizes[$food_id[$key]] = $eachProductSizes;
            };


            $food_quantitys = str_replace('"', '', (string) $request->food_quantity);
            $food_quantity = explode(',', $food_quantitys);
            $food_quantity_prices = str_replace('"', '', (string) $request->food_quantity_price);
            $food_quantity_price = explode(',', $food_quantity_prices);
        } else {
            $food_id = $request->food_id;
            $food_qty = $request->food_qty;
            $food_quantity = $request->food_quantity;
            $food_quantity_price = $request->food_quantity_price;
            $food_addons = [];
        }
        $food_ids = str_replace('"', '', (string) $request->food_id);
        $food_id = explode(',', $food_ids);

        $food_qtys = str_replace('"', '', (string) $request->food_qty);
        $food_qty = explode(',', $food_qtys);
        $food_prices = str_replace('"', '', (string) $request->food_price);
        $food_price = explode(',', $food_prices);

        $food_id_size = sizeof($food_id);
        $food_qty_size = sizeof($food_qty);
        $paid_type = $request->paid_type;
        $delivery_address_detail = $this->deliveryaddress::where('user_id', $user_id)->where('is_default', 1)->first();
        $d_lat = "";
        $d_lng = "";
        $delivery_address = '';
        $order_id = $this->generate_booking_id();
        $restaurant_details = $this->restaurants->find($restaurant_id);
        if (!empty($restaurant_details->admin_commision)) {
            $admin_commision_setting = $restaurant_details->admin_commision;
        } else {
            $admin_commision_setting = $settings::where('key_word', 'admin_commission')->value('value');
        }
        $admin_commission = ($bill_amount / 100) * $admin_commision_setting;
        $delivery_boy_commission = $delivery_charge;
        if ($delivery_type == 2) {
            $delivery_boy_commission = 0;
            $takeaway_code = rand(10000, 99999);
        } else {
            $takeaway_code = '';
        }
        $restaurant_commission = $bill_amount - ($admin_commission + $delivery_boy_commission);
        $res_phone = $restaurant_details->phone;
        $order_data = array();

        $order_data[] = array(
            'order_id' => $order_id,
            'user_id' => $user_id,
            'restaurant_id' => $restaurant_id,
            'item_total' => $item_total,
            'offer_discount' => $offer_discount,
            'restaurant_packaging_charge' => $restaurant_packaging_charge,
            'tax' => $gst,
            'tips' => $tips,
            'delivery_charge' => $delivery_charge,
            'bill_amount' => $bill_amount,
            'admin_commision' => $admin_commission,
            'restaurant_commision' => $restaurant_commission,
            'delivery_boy_commision' => $delivery_charge,
            'coupon_code' => $coupon_code,
            'delivery_type' => $delivery_type,
            'table_name' => $table_name,
            'takeaway_code' => $takeaway_code,
            'is_cod' => $is_cod,
            'is_confirmed' => 0,
            'is_paid' => 0,
            'paid_type' => $paid_type,
            'delivery_address' => $delivery_address,
            'd_lat' => $d_lat,
            'd_lng' => $d_lng,
            'ScheduledAt' => '',
            'ordered_time' => date('Y-m-d H:i:s'),
        );
        if ($is_cod == 0) {
            $order_data[0]['status'] = -1;
        }
        $foodrequest::insert($order_data);

        $last_id = $foodrequest::where('user_id', $user_id)->where('restaurant_id', $restaurant_id)->orderBy('id', 'desc')->first();

        $request_id = $last_id->id;
        for ($i = 0; $i < $food_id_size; $i++) {
            $request_detail = new Requestdetail;
            $request_detail->request_id = $last_id->id;
            $request_detail->restaurant_id = $restaurant_id;
            $request_detail->food_id = $food_id[$i];
            $request_detail->quantity = $food_qty[$i];
            $request_detail->addon_list = '0';
            $request_detail->food_quantity_price =  ($food_qty[$i] * $food_price[$i]);
            $request_detail->food_quantity = $food_sizes[$food_id[$i]][0] ?? ''; //taking only first size
            $request_detail->save();

            $request_detail_id = $request_detail->id;
            if (isset($food_addons[$food_id[$i]][0])) { //we are now taking the first addon only
                $addon_ids = explode(',', $food_addons[$food_id[$i]][0]);
                for ($j = 0; $j < count($addon_ids); $j++) {
                    $add_ons = $this->add_ons->find($addon_ids[$j]);
                    if (!isset($add_ons->id) || empty($add_ons->id)) {
                        continue;
                    }

                    $requestdetail_addons = new RequestdetailAddons;
                    $requestdetail_addons->requestdetail_id = $request_detail_id;
                    $requestdetail_addons->addons_id = $add_ons->id;
                    $requestdetail_addons->name = $add_ons->name;
                    $requestdetail_addons->price = $add_ons->price;
                    $requestdetail_addons->save();
                }
            }
        }
        $checkout_url = null;
        $PaymentID = null;
        $ClientSecret = null;
        $status_entry[] = array(
            'request_id' => $request_id,
            'status' => 0,
            'detail' => "Order Placed",
        );

        $trackorderstatus::insert($status_entry);
        if ($table_name) {
            $response_data_array = [
                'redirect_url' => url(''),
            ];
        } else {
            $response_data_array = [
                'checkout_url' => $checkout_url,
                'order_id' => preg_replace("/[^0-9.]/", "", $order_id),
                'PaymentID' => $PaymentID,
                'ClientSecret' => $ClientSecret,
            ];
        }
        $webToken = DB::table('user_fcm_token')->where('userid', $restaurant_id)->where('type', 'web')->value('token');
        if ($webToken) {
            $title = trans('constants.new_order');
            $this->user_send_push_notification($webToken, 'web', $title, "New Order has been placed: $request_id", $request_id);
        }
        $response_array = [
            'status' => true,
            'data' => $response_data_array,
            'message' => 'Order placed successfully'
        ];
        return response()->json($response_array, 200);
    }

    public function add_customer(Request $request)
    {
        $checkm = DB::table('users')->where('email', $request->email)->first();
        $checkp = DB::table('users')->where('phone', $request->phone)->first();


        if ($checkp) {
            $message = "Phone Number Already Exists";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }
        if ($checkm) {
            $message = "Mail Id Already Exists";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        $new_user = [
            'phone' => $request->phone,
            'email' => $request->email,
            'authToken' => '',
            'device_type' => 'web',
            'password' => '',
            'device_token' => '',
            'facebook_id' => '',
            'name' => $request->name,
            'profile_image' => '',
            'login_type' => 1,
            'referral_code' => '',
        ];

        DB::table('users')->insert($new_user);
        $user = DB::table('users')->where('email', $request->email)->first();

        $message = "Customer Added";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $user]);
        return $response_Array;
    }
    public function set_featured(Request $request)
    {
        $id = $request->id;

        $set = DB::table('food_list')->where('id', $id)->first();

        $rest = $set->restaurant_id;

        $rest_c = DB::table('food_list')->where('restaurant_id', $rest)->where('featured', 1)->count();


        if ($set->featured == 0) {
            if ($rest_c >= 10) {
                $message = "Maximum 10 Products can be set as Featured";
                $status = false;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }
            DB::table('food_list')->where('id', $id)->update([
                'featured' => 1
            ]);
            $message = "Product Marked as Featured";
        } else {
            DB::table('food_list')->where('id', $id)->update([
                'featured' => 0
            ]);
            $message = "Product Removed from Featured";
        }



        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function out_of_stock(Request $request)
    {
        $order_id = $request->order_id;
        $items = json_decode($request->items);
        $update_order_status = DB::table('requests')->where('id', $order_id)
            ->update([
                'status' => -3,
                'is_revised' => 1
            ]);
        foreach ($items as $item) {
            $update_orderstock = DB::table('request_detail')->where('request_id', $order_id)->where('food_id', $item->id)
                ->update([
                    'status' => -1
                ]);
            $update_foodstock = DB::table('food_list')->where('id', $item->id)
                ->update([
                    'out_of_stock' => 1
                ]);
        }
        $ord = DB::table('requests')->where('id', $order_id)->first();
        $user_noti = DB::table('users')->where('id', $ord->user_id)->first();
        $title = "Items Out of Stock";
        $message = 'Some Items in your Order is not Available Please change the Items';
        sendNotification([$user_noti->device_token], $title, $message, ['order_id' => $order_id]);

        call_customer($user_noti->phone);
        $message = "Items Marked as Out of Stock";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }
    public function get_default_lat_lng(Request $request)
    {
        $def_lat = DB::table('settings')->where('key_word', 'DEFAULT_LATITUDE')->first();
        $def_lng = DB::table('settings')->where('key_word', 'DEFAULT_LONGITUDE')->first();


        $status = true;
        $response_Array = json_encode(['def_lat' => $def_lat->value, 'def_lng' => $def_lng->value, 'status' => $status]);


        return $response_Array;
    }

    public function current_plan(Request $request)
    {

        $id = $request->id;

        $rest = DB::table('restaurants')->where('id', $id)->first();

        $plan = DB::table('restaurant_plan_history')->where('id', $rest->plan)->first();

        $plan_details = DB::table('plans')->where('id', $plan->plan)->first();

        $status = true;
        $response_Array = json_encode(['plan' => $plan, 'status' => $status, 'plan_details' => $plan_details]);
        return $response_Array;
    }

    public function get_plan_details(Request $request)
    {
        $id = $request->id;

        $plan = DB::table('plans')->where('id', $id)->first();

        $status = true;
        $response_Array = json_encode(['plan' => $plan, 'status' => $status]);
        return $response_Array;
    }

    public function pay_for_plan(Request $request)
    {
        $rest_id = $request->restaurant_id;
        $plan = $request->plan;
        $topay = $request->topay;
        $applied_coupon = $request->applied_coupon;

        $plan_details = DB::table('plans')->where('id', $plan)->first();
        $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
        $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
        $default_currency = DB::table('country')->where('is_default', 1)->first();

        $version = DB::table('settings')->where('key_word', 'stripe_version')->first();
        $stripe = new Stripe($sk->value, $version->value);

        if ($request->applied_coupon == 0 && $request->topay == 0) {

            $request->topay = $rest_plan->price;
        }
        $bill_amount_string = number_format($request->topay, 2, '.', '');
        if ($request->topay != 0) {

            $paymentIntent = $stripe->paymentIntents()->create([
                'amount' => $bill_amount_string,
                'currency' => $default_currency->currency_symbol,
                'payment_method_types' => [
                    'card',
                ],

            ]);
            $PaymentID = $paymentIntent['id'];
            $ClientSecret = $paymentIntent['client_secret'];
        } else {
            $PaymentID = 0;
            $ClientSecret = 0;
        }

        $trans = DB::table('restaurant_transactions')->insertGetId([
            'restaurant' => $rest_id,
            'payment_gateway' => "Stripe",
            'amount' => $bill_amount_string,
            'currency' => $default_currency->currency_symbol,
            'payment_id' => $PaymentID,
            'payment_description' => $rest_id
        ]);

        $get = DB::table('restaurant_plan_history')->insertGetId([
            'restaurant_id' => $rest_id,
            'plan' => $plan,
            'transaction_id' => $trans,
            'is_paid' => 0,
            'status' => -1, //0-new,1-submitted,2-expired
        ]);

        $response_array = [
            'status' => true,
            'message' => "Success",
            'restaurant' => $rest_id,
            'plan' => $plan,
            'paymentID' => $PaymentID,
            'clientsecret' => $ClientSecret,
            'trans' => $trans

        ];
        return response()->json($response_array, 200);
    }

    public function rest_payment_details(Request  $request)
    {

        if ($request->redirect_status == "succeeded") {

            DB::table('restaurant_transactions')->where('id', $request->orderID)->update(['payment_id' => $request->payment_intent, 'is_paid' => 1]);

            $rest =  DB::table('restaurant_transactions')->where('id', $request->orderID)->first();

            $plan_id = DB::table('restaurant_plan_history')->where('restaurant_id', $rest->restaurant)->first();

            $plan_validity = DB::table('plans')->where('id', $plan_id->plan)->first();

            if ($plan_validity->lifetime == 1) {

                $today = Carbon::now();
                $end = -1;
                DB::table('restaurant_plan_history')->where('restaurant_id', $rest->restaurant)->update([
                    'plan_from' => $today,
                    'plan_to' => $end,
                    'is_paid' => 1,

                ]);
            } else {
                $days = $plan_validity->validity;

                $today = Carbon::now();
                $to_add = Carbon::now();

                $end = $to_add->addDays($days);
                DB::table('restaurant_plan_history')->where('restaurant_id', $rest->restaurant)->update([
                    'plan_from' => $today,
                    'plan_to' => $end,
                    'is_paid' => 1,
                ]);
            }


            $update_rest = DB::table('restaurants')->where('id', $plan_id->id)->first();
            $url = "https://www.teerya.com/store/upgrade_plan";


            return redirect($url);
        }
    }

    public function validate_plan(Request $request)
    {
        $rest_plan = DB::table('restaurants')->where('id', $request->id)->first();

        $plan_history = $rest_plan->plan;

        $plan = DB::table('restaurant_plan_history')->where('id', $plan_history)->first();
        $plan_validity = DB::table('plans')->where('id', $plan->plan)->first();

        if ($plan_validity->lifetime == 1) {

            $response_array = [
                'status' => true,

            ];
            return response()->json($response_array, 200);
        } else {

            $today = Carbon::now();

            if ($today > $plan->plan_to) {
                $response_array = [
                    'status' => false,

                ];
                return response()->json($response_array, 200);
            }
        }
        $response_array = [
            'status' => true,

        ];
        return response()->json($response_array, 200);
    }
}
