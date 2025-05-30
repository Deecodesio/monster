<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\api\BaseController;
use App\Model\City_geofencing;
use App\Model\Deliverypartners;
use App\Model\DriverList;
use App\Model;
use App\Http\Controllers\Mailer;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Validator;
use App\Model\DriverDocument;
use App\Model\FoodListPricing;
use App\helpers;
use File;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends BaseController
{

    public function restaurant_list(Request $request)
    {
        $ad = DB::table('admin')->where('email', $request->user)->first();
        $ad2 = DB::table('admin_role')->where('email', $request->user)->first();
        if ($ad) {
            $data = DB::table('restaurants')
                ->join('business_type', 'business_type.id', '=', 'restaurants.business_type')
                ->select('restaurants.*', 'business_type.name as business_name',)
                ->orderBy('id', 'desc')->get();
        } else {
            $datas =  array_map('intval', json_decode($ad2->restaurant_id));

            if (count($datas) >= 1) {


                $data = DB::table('restaurants')
                    ->whereIn('restaurants.id', $datas)
                    ->join('business_type', 'business_type.id', '=', 'restaurants.business_type')
                    ->select('restaurants.*', 'business_type.name as business_name',)
                    ->orderBy('id', 'desc')->get();
            } else {
                $data = DB::table('restaurants')
                    ->join('business_type', 'business_type.id', '=', 'restaurants.business_type')
                    ->select('restaurants.*', 'business_type.name as business_name',)
                    ->orderBy('id', 'desc')->get();
            }
        }


        foreach ($data as $datas) {
            if ($datas->address != "") {
                $decodedText = html_entity_decode($datas->address);
                $datas->address = $decodedText;
            }
            if ($datas->restaurant_name) {
                $datas->restaurant_name = $this->secondLanguage($datas->restaurant_name, $datas->restaurant_secondary_name);
            }
            $stat = get_rating_statistics($datas->id);
            $datas->rating = (float) $stat['rating_percent'];
        }

        return response()->json($data);
    }

    public function brands_list(Request $request)
    {
        // $data = DB::table('brands')

        //          ->orderBy('id', 'desc')->get();
        $data = DB::table('brands')->orderBy('rank', 'asc')->get();
        return response()->json($data);
    }
    public function brands_list1(Request $request)
    {
        // $data = DB::table('brands')

        //          ->orderBy('id', 'desc')->get();
        $data = DB::table('brands')->where('status', 1)->orderBy('rank', 'asc')->get();
        foreach ($data as $d) {
            $d->name = $this->secondLanguage($d->name, $d->secondary_name);
        }
        return response()->json($data);
    }

    public function vehicle_list()
    {
        $vehicle_list = $this->vehicle->get();

        foreach ($vehicle_list as $key => $val) {

            if ($val->vehicle_image != "") {

                if (file_exists(public_path('/') . VEHICLE_UPLOADS_PATH . $val->vehicle_image)) {
                    $vehicle_list[$key]->vehicle_image =  BASE_URL . VEHICLE_UPLOADS_PATH . $val->vehicle_image;
                } else {
                    $vehicle_list[$key]->vehicle_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
                }
            } else {
                $vehicle_list[$key]->vehicle_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            }

            if ($val->insurance_image != "") {

                if (file_exists(public_path('/') . VEHICLE_UPLOADS_PATH . $val->insurance_image)) {
                    $vehicle_list[$key]->insurance_image =  BASE_URL . VEHICLE_UPLOADS_PATH . $val->insurance_image;
                } else {
                    $vehicle_list[$key]->insurance_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
                }
            } else {
                $vehicle_list[$key]->insurance_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            }

            if ($val->rc_image != "") {
                if (file_exists(public_path('/') . VEHICLE_UPLOADS_PATH . $val->rc_image)) {
                    $vehicle_list[$key]->rc_image =  BASE_URL . VEHICLE_UPLOADS_PATH . $val->rc_image;
                } else {
                    $vehicle_list[$key]->rc_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
                }
            } else {
                $vehicle_list[$key]->rc_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            }
        }

        // dd($vehicle_list);
        return response()->json($vehicle_list);
    }
    public function vehicles_list_drive()
    {
        $vehicle_list = $this->vehicle->where('status', 1)->get();

        // dd($vehicle_list);
        return response()->json($vehicle_list);
    }


    public function document_list()
    {
        $document_list = $this->document->get();
        foreach ($document_list as $d) {
            $d->document_name = $this->secondLanguage($d->document_name, $d->secondary_document_name);
        }

        return response()->json($document_list);
    }
    public function edit_document(Request $request)
    {
        $document_list = $this->document->find($request->id);

        $document_list->document_name = $this->secound_name($document_list->document_name);
        $document_list->secondary_document_name = $this->secound_name($document_list->secondary_document_name);

        $message = $document_list;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function reason_list()
    {
        $reason_list = $this->cancellation_reason->get();
        foreach ($reason_list as $r) {
            $r->reason = $this->secondLanguage($r->reason, $r->secondary_reason);
        }
        return response()->json($reason_list);
    }
    public function reason_status($id)
    {

        $reason = $this->cancellation_reason->where('id', $id)->first();
        if ($reason->status == 1) {
            $status = 2;
            $msg = "Status Inactive";
        } else {
            $status = 1;
            $msg = "Status Active";
        }

        $reason_list = $this->cancellation_reason->where('id', $id)
            ->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function admin_restaurant_report(Request $request)
    {
        $ad = DB::table('admin')->where('email', $request->email)->first();
        $ad2 = DB::table('admin_role')->where('email', $request->email)->first();
        if ($ad) {
            $restaurant_details = $this->restaurants
                ->join('add_city', 'add_city.id', '=', 'restaurants.city')
                ->join('add_area', 'add_area.id', '=', 'restaurants.area')
                ->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
                ->get();


            foreach ($restaurant_details as $datas) {
                if ($datas->address != "") {
                    $decodedText = html_entity_decode($datas->address);
                    $datas->address = $decodedText;
                }
            }
            foreach ($restaurant_details as $key => $value) {
                $restaurant_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('restaurant_commision');
                $value->restaurant_commision = round($restaurant_commision, 2);
                $admin_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('admin_commision');
                $value->admin_commision = round($admin_commision, 2);

                $payout_done = $this->restaurant_payout_history->where('restaurant_id', $value->id)->sum('payout_amount');
                $value->payout_done = $payout_done;
                $total_orders = $this->foodrequest->where('restaurant_id', $value->id)->where('status', 7)->count();
                $value->total_orders = $total_orders;
            }
        } else {
            $datas =  array_map('intval', json_decode($ad2->restaurant_id));
            if (count($datas) >= 1) {
                $restaurant_details = $this->restaurants
                    ->whereIn('restaurants.id', $datas)
                    ->join('add_city', 'add_city.id', '=', 'restaurants.city')
                    ->join('add_area', 'add_area.id', '=', 'restaurants.area')
                    ->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
                    ->get();
            } else {
                $restaurant_details = $this->restaurants
                    ->join('add_city', 'add_city.id', '=', 'restaurants.city')
                    ->join('add_area', 'add_area.id', '=', 'restaurants.area')
                    ->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
                    ->get();
            }



            foreach ($restaurant_details as $datas) {
                if ($datas->address != "") {
                    $decodedText = html_entity_decode($datas->address);
                    $datas->address = $decodedText;
                }
            }
            foreach ($restaurant_details as $key => $value) {
                $restaurant_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('restaurant_commision');
                $value->restaurant_commision = round($restaurant_commision, 2);
                $admin_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('admin_commision');
                $value->admin_commision = round($admin_commision, 2);

                $payout_done = $this->restaurant_payout_history->where('restaurant_id', $value->id)->sum('payout_amount');
                $value->payout_done = $payout_done;
                $total_orders = $this->foodrequest->where('restaurant_id', $value->id)->where('status', 7)->count();
                $value->total_orders = $total_orders;
            }
        }
        return response()->json($restaurant_details);
    }

    public function delivery_boy_report(Request $request)
    {

        $delivery_boy_details = DB::table('delivery_partners')
            ->join('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
            ->join('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
            ->join('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
            ->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
            ->get();



        foreach ($delivery_boy_details as $key => $value) {

            $value->delivery_boy_commision = DB::table('requests')->where('delivery_boy_id', $value->id)->sum('delivery_boy_commision');

            $value->admin_commision = DB::table('requests')->where('delivery_boy_id', $value->id)->sum('admin_commision');

            $city = DB::table('add_city')->where('id', $value->city)->first();

            $value->city_name = $city->city;

            $area = DB::table('add_area')->where('id', $value->area)->first();
            if ($area) {
                $value->area_name = $area->area;
            }
            $total_orders = $this->foodrequest->where('delivery_boy_id', $value->id)->where('status', 7)->count();
            $value->total_orders = $total_orders;
            $id = $value->id;
            $ratings = $this->order_ratings->with('Foodrequest')
                ->wherehas('Foodrequest', function ($q) use ($id) {
                    $q->where('delivery_boy_id', $id);
                })
                ->avg('restaurant_rating');

            $value->ratings = $ratings;
        }

        return response()->json($delivery_boy_details);
    }
    public function restaurant_report(Request $request)
    {
        $ad = DB::table('admin')->where('email', $request->email)->first();
        $ad2 = DB::table('admin_role')->where('email', $request->email)->first();
        if ($ad) {
            $restaurant_details = DB::table('requests')
                ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'requests.status as order_status', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.*')
                ->get();
        } else {
            $datas =  array_map('intval', json_decode($ad2->restaurant_id));
            if (count($datas) >= 1) {
                $restaurant_details = DB::table('requests')
                    ->whereIn('requests.restaurant_id', $datas)

                    ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
                    ->join('users', 'users.id', '=', 'requests.user_id')
                    ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                    ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'requests.status as order_status', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.*')
                    ->get();
            } else {
                $restaurant_details = DB::table('requests')

                    ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
                    ->join('users', 'users.id', '=', 'requests.user_id')
                    ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
                    ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'requests.status as order_status', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.*')
                    ->get();
            }
        }



        return response()->json($restaurant_details);
    }
    public function area_list(Request $request)
    {
        $area_list = DB::table('add_area')->get();

        return response()->json($area_list);
    }

    public function city_list()
    {
        $city_list = DB::table('add_city')->get();
        //  dd( $city_list );

        return response()->json($city_list);
    }
    public function city_list1()
    {
        $city_list = DB::table('add_city')->where('status', 1)->get();
        //  dd( $city_list );

        return response()->json($city_list);
    }

    public function stateWiseCityList($id)
    {
        $city_list = DB::table('add_city')->where('status', 1)->where('state_id', $id)->get();
        //  dd( $city_list );

        return response()->json($city_list);
    }

    public function add_driver(Request $request)
    {
        // dd($request);
        $custom = $this->custom;


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
            'gender' => 'required',



        ];

        if (!$request->id) {
            $conditions['profile_pic'] = 'required';
            $conditions['password'] = 'required';
        }

        $validator = Validator::make($request->all(), $conditions);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            // return back()->with('error', $error_messages)->withInput();
            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        $second_driver_name = $this->secound_lang_name($request->second_driver_name);
        $second_address_line_1 = $this->secound_lang_name($request->second_address_line_1);
        $second_address_line_2 = $this->secound_lang_name($request->second_address_line_2);
        $second_address_city = $this->secound_lang_name($request->second_address_city);
        $second_country = $this->secound_lang_name($request->second_country);
        $second_state_province = $this->secound_lang_name($request->second_state_province);

        $driver_name = $this->secound_lang_name($request->driver_name);
        $address_line_1 = $this->secound_lang_name($request->address_line_1);
        $address_line_2 = $this->secound_lang_name($request->address_line_2);
        $address_city = $this->secound_lang_name($request->address_city);
        $country = $this->secound_lang_name($request->country);
        $state_province = $this->secound_lang_name($request->state_province);

        if ($request->address_line_2 == "undefined") {

            $request->address_line_2 =  NULL;
        } else {

            $request->address_line_2 =  $request->address_line_2;
        }
        if ($request->file('profile_pic')) {

            $profile_picture = $custom->driver_image_upload($request, 'profile_pic');
        }
        $mail_flag = 0;

        if ($request->id) {

            $insert1 = Deliverypartners::find($request->id);
            if ($insert1->status != $request->status) {
                $mail_flag = 1;
            }
            if ($insert1->is_activated == 0 && $request->status == 1 && $insert1->status != 1) {
                $rider_new = DB::table('delivery_partners')->where('id', $request->id)->first();
                $mail_enable = DB::table('settings')->where('key_Word', 'email_enable')->first();
                if ($mail_enable->value == 1) {
                    $template = DB::table('mail_contents')->where("content_for", "driver_activation")->get();
                    $cont = [];
                    foreach ($template as $temp) {
                        $cont[] = $temp->content;
                    }
                    $logo = DB::table('settings')->where('key_word', "site_logo")->first();
                    $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
                    $url = "https://www.teerya.com/";
                    $url2 = "https://www.teerya.com/";

                    $html = view('emails.deliverpartner.driver-activate')->with(['driver' => $rider_new, 'template' => $cont, 'logo' => $to_logo, 'url' => $url, 'url2' => $url2]);
                    $Mailer = new Mailer();
                    $Mailer->to         = $rider_new->email;
                    $Mailer->toName     = $rider_new->name;
                    $Mailer->subject     = "Welcome";
                    $Mailer->body       = $html;
                    $Mailer->send();
                }
                $insert1->is_activated = 1;
            }
            if ($request->phone_no != $insert1->phone) {
                $duplicate_check = Deliverypartners::where('phone', $request->phone_no)->first();
                if (!empty($duplicate_check)) {
                    // return back()->with('error', 'Phone number already exists')->withInput();
                    $message = "Phone number already exists";
                    $status = false;
                    $response_Array = json_encode(['message' => $message, 'status' => $status]);
                    return $response_Array;
                }
            }
        } else {

            $mail_flag = 1;
            $insert1 = new Deliverypartners();
            $duplicate_check = Deliverypartners::where('phone', $request->phone_no)->first();
            if (!empty($duplicate_check)) {
                // return back()->with('error', 'Phone number already exists')->withInput();
                $message = "Phone number already exists";
                $status = false;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }
        }
        if ($request->id) {

            $insert1->partner_id = $insert1->partner_id;
        } else {

            $insert1->partner_id = $this->generate_partner_id();
        }
        // $insert1->name = $request->driver_name;

        if ($request->restaurant == "undefined") {

            $insert1->restaurant_id =  NULL;
        } else {

            $insert1->restaurant_id = $request->restaurant;
        }

        $insert1->email = $request->email;
        $insert1->phone = $request->phone_no;
        $insert1->name = $driver_name ?? '';
        $insert1->second_driver_name = $second_driver_name ?? '';
        $insert1->country_code = $request->phone_country_code;
        $insert1->country_calling_code = $request->phone_country_calling_code;

        if (!empty($request->password)) {
            $insert1->org_password = $request->password;
        }
        if (!empty($request->password)) {
            $insert1->password = $this->encrypt_password($request->password);
        }

        $insert1->status = $request->status;
        $insert1->license = null;

        if ($request->file('profile_pic')) {
            $insert1->profile_pic = $profile_picture;
        }


        $insert1->save();
        $partner_id = $insert1->id;


        if ($request->id) {
            $insert1 = DB::table('delivery_partner_details')->where('delivery_partners_id', $request->id)->first();
            $insert = DriverList::find($insert1->id);
        } else {
            $insert = new DriverList();
        }

        if (isset($request->area) && !empty($request->area)) {

            $insert->area = $request->area;
        }



        $insert->delivery_partners_id = $partner_id;
        $insert->city = $request->city;
        $insert->vehicle_name = $request->vehicle_name;
        $insert->address_line_1 = $address_line_1 ?? '';
        $insert->address_line_2 = $address_line_2 ?? '';
        $insert->address_city = $address_city ?? '';
        $insert->state_province = $state_province ?? '';
        $insert->country = $country ?? '';
        $insert->zip_code = $request->zip_code;
        $insert->about = $request->about;
        $insert->gender = $request->gender;
        $insert->services =  '["1"]';

        $insert->second_address_line_1 = $second_address_line_1 ?? '';
        $insert->second_address_line_2 = $second_address_line_2 ?? '';
        $insert->second_address_city = $second_address_city ?? '';
        $insert->second_state_province = $second_state_province ?? '';
        $insert->second_country = $second_country ?? '';
        // $insert->account_name = $request->account_name;
        // $insert->account_address = $request->account_address;
        // $insert->account_no = $request->account_no;
        // $insert->bank_name = $request->bank_name;
        // $insert->branch_name = $request->branch_name;
        // $insert->branch_address = $request->branch_address;
        // $insert->swift_code = $request->swift_code;
        // $insert->routing_no = $request->routing_no;
        $insert->save();
        $detail = $insert->id;



        // if ($mail_flag == 1) {

        //     $insert1 = Deliverypartners::find($partner_id);
        //     $html = view('emails.deliverpartner.activated')->with(['driver' => $insert1]);

        //     $Mailer = new Mailer();
        //     $Mailer->to         = $insert1->email;
        //     $Mailer->toName     = 'Admin';
        //     $Mailer->subject     = "Welcome";
        //     $Mailer->body       = $html;
        //     $Mailer->send();
        // }
        //   session()->put('tab', 'tab2');
        // $returnMsg = $request->id ? trans('constants.driver_updated_succesfully') :  trans('constants.driver_added_successfully');
        $message = "Added Sucessfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $partner_id]);
        return $response_Array;

        //    return response()->json();
    }
    public function add_driver_tab1(Request $request)
    {

        $custom = $this->custom;
        //driver document upload
        if ($request->file('license')) {
            if ($request->license != null) {
                $licenses = $request->license;
                $exp_date = $request->license_expiry;
                $document_id = $request->doc_id;
                $expdate_id = $request->exp_id;
                DB::table('driver_document')->where('driver_id', $request->id)->delete();

                for ($i = 0; $i < count($request->file('license')); $i++) {
                    $dri_doc = new DriverDocument();
                    $license = $custom->base_image_upload_license($licenses[$i]); //dd($license);
                    $dri_doc->driver_id = $request->id;
                    $dri_doc->document_id = $document_id[$i];
                    $dri_doc->document = $license;
                    //dd($dri_doc);
                    $dri_doc->save();
                    if ($exp_date) {
                        for ($e = 0; $e < count($exp_date); $e++) {
                            if ($document_id[$i] ==  $expdate_id[$e]) {
                                //dd($exp_date[$e]);
                                $doc = DB::table('driver_document')->where('driver_id', $request->id)->where('document_id', $document_id[$i])
                                    ->update(['expiry_date' => $exp_date[$e]]);
                            }
                        }
                    }
                }
            }
        }
        $message = "Added Sucessfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $request->id]);
        return $response_Array;
    }
    public function add_driver_tab(Request $request)
    {

        if ($request->id) {
            $insert1 = DB::table('delivery_partner_details')->where('delivery_partners_id', $request->id)->first();
            $insert = DriverList::find($insert1->id);
        } else {
            $insert = new DriverList();
        }
        $account_name = $this->secound_lang_name($request->account_name);
        $account_address = $this->secound_lang_name($request->account_address);
        $account_no = $this->secound_lang_name($request->account_no);
        $bank_name = $this->secound_lang_name($request->bank_name);
        $branch_name = $this->secound_lang_name($request->branch_name);
        $branch_address = $this->secound_lang_name($request->branch_address);
        $swift_code = $this->secound_lang_name($request->swift_code);
        $routing_no = $this->secound_lang_name($request->routing_no);

        $insert->account_name = $account_name;
        $insert->account_address = $account_address;
        $insert->account_no = $account_no;
        $insert->bank_name = $bank_name;
        $insert->branch_name = $branch_name;
        $insert->branch_address = $branch_address;
        $insert->swift_code = $swift_code;
        $insert->routing_no = $routing_no;
        $insert->save();


        $message = "Added Sucessfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function res_list(Request $request)
    {
        $res_list = DB::table('restaurants')->get();
        return response()->json($res_list);
    }

    public function add_city(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'city' => 'required',
            // 'admin_commision' => 'required',
            'country' => 'required',
            'state' => 'required',
            'status' => 'required',
            // 'geofence_latlng' => 'required',


        ]);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());

            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        } else {
            $lat = 1;
            $lng = 1;
            $lg = 1;
            $admin_commision = 0;
            if ($request->id) {
                $custom = $this->custom;

                $city = $request->city;
                $country_id = $request->country;
                $state_id = $request->state;
                $admin_commision = $admin_commision;
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

                $data = [];


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

                $geofencing = new City_geofencing();
                $geofencingdata = $geofencing->where('city_id', $citydata->id)->first();
                if (empty($geofencingdata)) {
                    $geofencingdata = $geofencing;
                }

                $geofencingdata->city_id = $citydata->id;
                $geofencingdata->latitude = !empty($lat) ? $lat : env('DEFAULT_LAT');
                $geofencingdata->longitude = !empty($lat) ? $lat : env('DEFAULT_LNG');
                $geofencingdata->polygons = $lat;
                $geofencingdata->save();
            } else {
                $check = DB::table('add_city')->where('city', '=', $request->city)->first();

                if (!$check) {

                    $city = $request->city;
                    $country_id = $request->country;
                    $state_id = $request->state;
                    $admin_commision = $admin_commision;
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

                    $data = [];


                    $custom = $this->custom;


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


                    $geofencing = new City_geofencing();
                    $geofencing->city_id = $add_city->id;
                    $geofencing->latitude = $lat;
                    $geofencing->longitude = $lng;
                    $geofencing->polygons = $lg;

                    $geofencing->save();
                } else {

                    $message = "Already Exist";
                    $status = false;
                    $response_Array = json_encode(['message' => $message, 'status' => $status]);
                    return $response_Array;
                }
            }
        }
        $message = "Added Sucessfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function country_list(Request $request)
    {

        $country = DB::table('country')->get();

        return response()->json($country);
    }


    public function vehicle_add(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'vehicle_name' => 'required',
            'vehicle_no' => 'required',
            'insurance_no' => 'required',
            'insurance_expiry_date' => 'required',
            'rc_no' => 'required',
            'rc_expiry_date' => 'required',
            'status' => 'required',
            'carrier_type' => 'required',

        ]);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());

            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }
        $carrier_type = $request->carrier_type;
        $vehicle_name = $request->vehicle_name;
        $vehicle_no = $request->vehicle_no;
        $insurance_no = $request->insurance_no;
        $insurance_expiry_date = date('Y-m-d', strtotime($request->insurance_expiry_date));
        $rc_no = $request->rc_no;
        $rc_expiry_date = date('Y-m-d', strtotime($request->rc_expiry_date));
        $path = public_path('vehicles/');

        $status = $request->status;
        if ($request->id == '' ||  $request->id == 'undefined') {
            $add_vehicle = new $this->vehicle;
            $msg = "Vehicle Added Successfully";
        } else {
            $add_vehicle = $this->vehicle->find($request->id);
            $msg = "Vehicle Updated Successfully";
        }
        // dd( $add_vehicle);
        $add_vehicle->carrier_type = $carrier_type;
        $add_vehicle->vehicle_name = $vehicle_name;
        $add_vehicle->vehicle_no = $vehicle_no;
        $add_vehicle->insurance_no = $insurance_no;
        $add_vehicle->insurance_expiry_date = date('Y-m-d', strtotime($request->insurance_expiry_date));
        $add_vehicle->rc_no = $request->rc_no;
        $add_vehicle->rc_expiry_date = date('Y-m-d', strtotime($request->rc_expiry_date));
        $add_vehicle->status = $status;
        if ($request->insurance_image != '' || $request->insurance_image != null) {
            if ($request->file('insurance_image')) {
                //   dd($request->file('insurance_image'));
                $add_vehicle->insurance_image = $this->custom->common_upload_images($request, 'insurance_image', $path);
            }
        }
        if ($request->rc_image != '' || $request->rc_image != null) {
            if ($request->file('rc_image')) {

                $add_vehicle->rc_image = $this->custom->common_upload_images($request, 'rc_image', $path);
            }
        }
        if ($request->vehicle_image != '' || $request->vehicle_image != null) {
            if ($request->file('vehicle_image')) {
                $add_vehicle->vehicle_image = $this->custom->common_upload_images($request, 'vehicle_image', $path);
            }
        }



        $add_vehicle->save();



        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function store_brand(request $request)
    {


        if ($request->status == "undefined" || $request->status == "null") {

            $error_messages = "Select Status";

            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }
        // dd($request);
        $name = $request->name;

        if ($request->id != "undefined") {
            DB::table('brands')->where('id', $request->id)->update([
                'name' => $this->secound_lang_name($name),
                'secondary_name' => $this->secound_lang_name($request->secondary_name),
                'status' => $request->status
            ]);


            $message = "Brand Added Successfully";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        } else {
            $data = DB::table('brands')->where('name', '=', $name)->count();

            if ($data > 0) {

                $message = "Brand Added Exists";
                $status = false;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);


                return $response_Array;
            } else {
                DB::table('brands')->insert([
                    'name' => $this->secound_lang_name($name),
                    'secondary_name' => $this->secound_lang_name($request->secondary_name),
                    'status' => $request->status,
                ]);


                $message = "Brand Added Successfully";
                $status = true;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);


                return $response_Array;
            }
        }
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
            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        } else {
            $reason = $this->secound_lang_name($request->reason);
            $secondary_reason = $this->secound_lang_name($request->secondary_reason);
            $cancellation_for = $request->cancellation_for;
            $status = $request->status;
            // $add_reason = $this->cancellation_reason;

            $data = [];

            $data[] = [
                'reason' => $reason,
                'secondary_reason' => $secondary_reason,
                'cancellation_for' => $cancellation_for,
                'status' => $status,

            ];
            if ($request->id != '') {
                $add_reason = $this->cancellation_reason->find($request->id);
                $msg = "Cancellation Reason Updated Successfully";
            } else {
                $add_reason = $this->cancellation_reason;
                $msg = "Cancellation Reason Added Successfully";
            }

            $add_reason->reason = $reason;
            $add_reason->secondary_reason = $secondary_reason ?? '';
            $add_reason->cancellation_for = $cancellation_for;
            $add_reason->status = $status;


            $add_reason->save();
        }
        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function edit_reason(Request $request)
    {
        $add_reason = $this->cancellation_reason->find($request->id);
        if ($add_reason->secondary_reason) {
            $add_reason->secondary_reason = $this->secound_name($add_reason->secondary_reason);
        }
        if ($add_reason->reason) {
            $add_reason->reason = $this->secound_name($add_reason->reason);
        }
        $message = $add_reason;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);

        return $response_Array;
    }


    public function document_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_for' => 'required',
            'document_name' => 'required',
            'expiry_date_needed' => 'required',
            'status' => 'required',
            'layout_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());

            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        } else {

            $document_for = $request->document_for;
            $document_name = $this->secound_lang_name($request->document_name);
            $secondary_document_name = $this->secound_lang_name($request->secondary_document_name);
            $expiry_date_needed = $request->expiry_date_needed;
            $layout_id = $request->layout_id;

            $status = $request->status;
            $data = [];
            $data[] = [
                'document_for' => $document_for,
                'document_name' => $document_name,
                'secondary_document_name' => $secondary_document_name ?? '',
                'expiry_date_needed' => $expiry_date_needed,
                'status' => $status,
            ];
            // dd($request->id);
            if ($request->id != '') {
                $add_document = $this->document->find($request->id);
                $msg = "Docoument Updated Successfully";
            } else {
                $add_document = new $this->document;
                $msg = "Docoument Added Successfully";
            }



            $add_document->document_for = $document_for;
            $add_document->document_name = $document_name;
            $add_document->expiry_date_needed = $expiry_date_needed;
            $add_document->secondary_document_name = $secondary_document_name ?? '';

            $add_document->status = $status;
            $add_document->layout_id = $layout_id;


            $add_document->save();
        }

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }
    public function edit_brand($brand_id)
    {
        $data = DB::table('brands')->where('id', $brand_id)->first();
        $data->name =  $this->secound_name($data->name);
        $data->secondary_name =  $this->secound_name($data->secondary_name);
        $message = $data;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function edit_city($id)
    {
        $city_data = $this->addcity->where('id', $id)->with(['Country', 'State'])->first();
        $country = $this->country->get();
        $state = $this->state->where('country_id', $city_data->country_id)->get();
        //get city data based on id


        $message = $city_data;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function editvehicle($id)
    {
        $data = DB::table('vehicle')->where('id', $id)->first();
        // $data = $this->vehicle->find($id);
        // dd($data);


        if ($data->vehicle_image != "") {
            if (file_exists(public_path('/') . VEHICLE_UPLOADS_PATH . $data->vehicle_image)) {
                $data->vehicle_image = BASE_URL . VEHICLE_UPLOADS_PATH . $data->vehicle_image;
            } else {
                $data->vehicle_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            }
        } else {
            $data->vehicle_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
        }

        if ($data->insurance_image != "") {

            if (file_exists(public_path('/') . VEHICLE_UPLOADS_PATH . $data->insurance_image)) {
                $data->insurance_image = BASE_URL . VEHICLE_UPLOADS_PATH . $data->insurance_image;
            } else {
                $data->insurance_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            }
        } else {
            $data->insurance_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
        }

        if ($data->rc_image != "") {
            if (file_exists(public_path('/') . VEHICLE_UPLOADS_PATH . $data->rc_image)) {
                $data->rc_image = BASE_URL . VEHICLE_UPLOADS_PATH . $data->rc_image;
            } else {
                $data->rc_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            }
        } else {
            $data->rc_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
        }


        if ($data->carrier_type) {

            $data->carrier_type = (int)$data->carrier_type;
        }

        // $message = $data;
        // $status = true;
        // $response_Array = json_encode(['message'=>$message,'status'=>$status]);


        // return $response_Array;
        return response()->json($data);
    }
    public function document_view(Request $request)
    {
        $document_list = DB::table('document')->where('status', 1)->get();
        return response()->json($document_list);
    }
    public function add_to_restaurants_tab1(Request $request)
    {


        $id = $request->id;

        if ($request->city == "undefined" || $request->city == "NaN" || !$request->city) {
            $message = "City Field is Required";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        if ($request->area == "undefined" || $request->area == "NaN" || !$request->area) {
            $message = "Area Field is Required";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        if (!empty($id)) {
            $restaurants = $this->restaurants;
            //   dd(  $restaurants);
            $restaurant_update = $restaurants::findOrFail($id);
            if ($restaurant_update->is_activated == 0 && $request->status == 1 && $restaurant_update->status != 1) {
                $restm = DB::table('restaurants')->where('id', $request->id)->first();

                $template = DB::table('mail_contents')->where("content_for", "rest_activation")->get();
                $cont = [];
                foreach ($template as $temp) {
                    $cont[] = $temp->content;
                }
                $logo = DB::table('settings')->where('key_word', "site_logo")->first();
                $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
                $url = BASE_URL . "store/";
                $html = view('emails.restaurant.rest-activate')->with(['restaurant' => $restm, 'template' => $cont, 'logo' => $to_logo, 'url' => $url]);
                $Mailer = new Mailer();
                $Mailer->to         = $restm->email;
                $Mailer->toName     = $restm->restaurant_name;
                $Mailer->subject     = "Welcome";
                $Mailer->body       = $html;
                $Mailer->send();

                $restaurant_update->is_activated = 1;
            }
            $custom = $this->custom;

            $name = $this->secound_lang_name($request->name);
            $restaurant_secondary_name = $this->secound_lang_name($request->restaurant_secondary_name);
            $email = $request->email;
            $password = $request->password;
            $phone = $request->phone;
            $city = $request->city;
            $address = $request->address;
            $area = $request->area;
            $brand_name = $request->brands ?? '';
            $GeofenceRadius = $request->GeofenceRadius;
            $description = $request->shop_description;
            $packaging_charge = $request->packaging_charge;
            $estimated_delivery_time = $request->estimated_delivery_time;
            $status = $request->status;
            $business_type = $request->business_type;
            $banner = $request->banner ?? '';
            $tax = $request->tax ?? '';
            $carrier_type = explode(',',  $request->carrier_type);

            $phone_country_calling_code = $request->country_calling_code;


            $phone_country_code = $request->country_code;


            $contact_country_calling_code = $request->country_calling_code_contact;

            $is_cart = $request->cart;

            $contact_country_code = $request->country_code_contact;

            $delivery_states = explode(',',  $request->delivering_states);


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
            $restaurant_update->cloudflare_imageid = null;
            $restaurant_update->cloudflare_bannerid = null;




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
            $restaurant_update->business_type =  $business_type;
            $restaurant_update->restaurant_name = $name;
            $restaurant_update->email = $email;
            $restaurant_update->restaurant_secondary_name = $restaurant_secondary_name;
            $restaurant_update->org_password = $password;

            $restaurant_update->password = Hash::make($password);
            $restaurant_update->phone = $phone;
            $restaurant_update->brand_name = $brand_name;
            $restaurant_update->contact = $request->contact;
            $restaurant_update->city = $city;
            // $restaurant_update->address = $address;
            $restaurant_update->lat = $request->latitude;
            $restaurant_update->lng = $request->longitude;
            $restaurant_update->shop_description = $description;
            $restaurant_update->area = $area;
            $restaurant_update->GeofenceRadius = $GeofenceRadius;
            $restaurant_update->packaging_charge = $packaging_charge;
            $restaurant_update->admin_commision = $admin_commision;
            $restaurant_update->driver_commision = $request->driver_commision;
            $restaurant_update->tax = $request->tax;
            $restaurant_update->carrier_type = json_encode($carrier_type);

            $restaurant_update->phone_country_calling_code = $phone_country_calling_code;
            $restaurant_update->phone_country_code = $phone_country_code;
            $restaurant_update->contact_country_calling_code = $contact_country_calling_code;
            $restaurant_update->contact_country_code = $contact_country_code;

            $restaurant_update->estimated_delivery_time = $estimated_delivery_time;

            $restaurant_update->delivery_type = json_encode($delivery_type);
            $restaurant_update->cart = $is_cart;

            $restaurant_update->video_title = json_encode($video_title);
            $restaurant_update->video_url = json_encode($video_url);
            if ($request->deliveryChargeBasedOn) {
                $restaurant_update->DeliveryChargeBasedOn = $request->deliveryChargeBasedOn;
            }
            $restaurant_update->delivery_states = $delivery_states;

            //mail for active deactive restarant
            $mfrom = DB::table('settings')->where('key_word', 'mail_from_name')->first();

            $restaurant_update->status = $status;

            $restaurant_update->update();
            // explode(" ", $pizza);
            $array = explode(',', $request->cuisines);


            $cuisines = $this->cuisines->find($array); //dd($array);
            $restaurant_update->Cuisines()->sync($cuisines);

            $originalText = $request->address;
            $convertedText = mb_convert_encoding($originalText, 'HTML-ENTITIES', 'UTF-8');
            $decodedText = html_entity_decode($convertedText);
            //    dd($decodedText);


            $res_id = DB::table('restaurants')->where('id', $id)
                ->update([
                    'address' => $convertedText,
                ]);





            $layout = DB::table('business_type')->where('id', $business_type)->first();
            if ($layout->layout_id == 1) {
                $message = "Restaurant Details Updated";
            } else {

                $message = "Store Details Updated";
            }

            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $request->id]);
            return $response_Array;
        } else {



            $restaurants = $this->restaurants;
            $check_email_phone = $restaurants->where('email', $request->email)->orwhere('phone', $request->phone)->first();
            if ($check_email_phone) {
                $response_array = [
                    'status' => false,
                    'message' => "Email/Phone already exists"

                ];
                return response()->json($response_array, 200);
            }
            $custom = $this->custom;

            $name = $this->secound_lang_name($request->name);
            $restaurant_secondary_name = $this->secound_lang_name($request->restaurant_secondary_name);
            $email = $request->email;
            $password = $request->password;
            $phone = $request->phone;
            $brand_name = $request->brands;
            $city = $request->city;
            $address = $request->address;
            $area = $request->area;
            $GeofenceRadius = $request->GeofenceRadius;
            $description = $request->shop_description;
            $packaging_charge = $request->packaging_charge;
            $estimated_delivery_time = $request->estimated_delivery_time;
            $status = $request->status;
            $business_type = $request->business_type;
            $tax = $request->tax;
            $carrier_type = explode(',', $request->carrier_type);
            $admin_commision = $request->admin_commision;
            $delivery_charge_type = $request->delivery_charge_type ?? 1;
            $array_delivery = explode(',', $request->delivery_type);
            // $array_video_title = explode(',', $request->video_title);
            // $array_video_url = explode(',', $request->video_url);

            $delivery_type = $array_delivery;
            // $video_title = $array_video_title;
            // $video_url =$array_video_url;

            $video_title = json_decode($request->video_title);
            $video_url = json_decode($request->video_url);

            $is_cart = $request->cart;

            $phone_country_calling_code = $request->country_calling_code;
            $phone_country_code = $request->country_code;
            $contact_country_calling_code = $request->country_calling_code_contact;
            $contact_country_code = $request->country_code_contact;

            $delivery_states = explode(',',  $request->delivering_states);


            $restaurants->business_type =  $business_type;
            $restaurants->restaurant_name = $name;
            $restaurants->email = $email;
            $restaurants->restaurant_secondary_name = $restaurant_secondary_name;
            $restaurants->org_password = $password;

            $restaurants->password = Hash::make($password);

            $restaurants->brand_name = $brand_name;
            $restaurants->phone = $phone;

            $restaurants->phone_country_calling_code = $phone_country_calling_code;
            $restaurants->phone_country_code = $phone_country_code;
            $restaurants->contact_country_calling_code = $contact_country_calling_code;
            $restaurants->contact_country_code = $contact_country_code;


            $restaurants->contact = $request->contact;
            $restaurants->city = $city;
            // $restaurants->address = $address;
            $restaurants->lat = $request->latitude;
            $restaurants->lng = $request->longitude;
            $restaurants->shop_description = $description;
            $restaurants->area = $area;
            $restaurants->GeofenceRadius = $GeofenceRadius;
            $restaurants->packaging_charge = $packaging_charge;
            $restaurants->admin_commision = $admin_commision;
            $restaurants->driver_commision = $request->driver_commision;
            $restaurants->estimated_delivery_time = $estimated_delivery_time;
            $restaurants->delivery_type = json_encode($delivery_type);
            $restaurants->video_title = json_encode($video_title);
            $restaurants->video_url = json_encode($video_url);
            $restaurants->tax = $tax;
            $restaurants->carrier_type = json_encode($carrier_type);
            // $restaurants->delivery_states = $delivery_states;

            $restaurants->cart = $is_cart;


            if ($request->deliveryChargeBasedOn) {
                $restaurants->DeliveryChargeBasedOn = $request->deliveryChargeBasedOn;
            }
            $originalText = $request->address;
            $convertedText = mb_convert_encoding($originalText, 'HTML-ENTITIES', 'UTF-8');
            // $decodedText = html_entity_decode($convertedText);
            //    dd($decodedText);
            $restaurants->address = $convertedText;

            $restaurants->save();

            $array = explode(',', $request->cuisines);

            $cuisines = $this->cuisines->find($array);

            $restaurants->Cuisines()->sync($cuisines);

            $tab = 'tab2';
            // session()->put('tab', 'tab2');
            // session()->put('elmnt', 'this');
            // session()->put('color', '#409a40e8');
            $res_id = DB::table('restaurants')->orderBy('id', 'desc')->first();
            $restaurant_id = $res_id->id;

            $restaurant_update = $restaurants::findOrFail($restaurant_id);
            $banner = $request->banner;

            if ($request->existing == "undefined" || $request->existing == null) {
                $banner  = basename($request->banner);
            } else {
                if ($request->existing == 1) {
                    $banner = $request->banner;
                } else {

                    $banner = $custom->restaurant_upload_image_banner($request, 'banner', $restaurant_id);
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

                    $image = $custom->restaurant_upload_image_logo($request, 'image', $restaurant_id);
                }
            }

            $restaurant_update->image = $image;

            $restaurant_update->update();


            $layout = DB::table('business_type')->where('id', $business_type)->first();
            if ($layout->layout_id == 1) {
                $message = "Restaurant Details Updated";
            } else {

                $message = "Store Details Updated";
            }
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $restaurant_id]);
            return $response_Array;
        }
    }

    public function add_to_restaurants_tab2(Request $request)
    {

        $id = $request->id;
        $restaurants = $this->restaurants;
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
        //dd($closing_time_7);
        for ($i = 1; $i <= 7; $i++) {
            $restaurant_update->{'opening_second_time_' . $i} = ${'opening_second_time_' . $i};
            $restaurant_update->{'closing_second_time_' . $i} = ${'closing_second_time_' . $i};
        }
        $restaurant_update->holidays = $holidays;
        $restaurant_update->update();
        // session()->put('tab', 'tab3');
        if ($request->time_edit == "time") {

            $message = "Timing Updated";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        } else {
            $message = "Restaurant timing Updated";
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

        $message = "Restaurant Document Updated";
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
            $restaurant_bank_details->account_name = $this->secound_lang_name($request->account_name);
            $restaurant_bank_details->account_address = $this->secound_lang_name($request->account_address);
            $restaurant_bank_details->account_no = $this->secound_lang_name($request->account_no);
            $restaurant_bank_details->bank_name = $this->secound_lang_name($request->bank_name);
            $restaurant_bank_details->branch_name = $this->secound_lang_name($request->branch_name);
            $restaurant_bank_details->branch_address = $this->secound_lang_name($request->branch_address);
            $restaurant_bank_details->swift_code = $this->secound_lang_name($request->swift_code);
            $restaurant_bank_details->routing_no = $this->secound_lang_name($request->routing_no);
            $restaurant_bank_details->save();
            // session()->put('tab', 'tab1');
            // return redirect('/admin/edit_restaurant/' . $request->id . '')->with('success', "Bank details updated");
            //return redirect('/admin/my_restaurant#tab4')->with('success', "Bank Detail Updated");
            $message = "Restaurant BankDetails Saved";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $request->id]);
            return $response_Array;
        } else {
            $restaurant_bank_details->account_name = $this->secound_lang_name($request->account_name);
            $restaurant_bank_details->account_address = $this->secound_lang_name($request->account_address);
            $restaurant_bank_details->account_no = $this->secound_lang_name($request->account_no);
            $restaurant_bank_details->bank_name = $this->secound_lang_name($request->bank_name);
            $restaurant_bank_details->branch_name = $this->secound_lang_name($request->branch_name);
            $restaurant_bank_details->branch_address = $this->secound_lang_name($request->branch_address);
            $restaurant_bank_details->swift_code = $this->secound_lang_name($request->swift_code);
            $restaurant_bank_details->routing_no = $this->secound_lang_name($request->routing_no);
            $restaurant_bank_details->update();
            // session()->put('tab', 'tab1');
            // return redirect('/admin/edit_restaurant/' . $request->id . '')->with('success', "Bank details updated");

            //     return redirect('/admin/my_restaurant#tab4')->with('success', "form 4 updated");
            $message = "Restaurant BankDetails Updated";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $request->id]);
            return $response_Array;
        }
    }

    public function add_to_restaurants_tab5(Request $request)
    {


        $restaurants = $this->restaurants;
        $restaurant_bank_details = $restaurants::findOrFail($request->id);
        // $restaurant_bank_details = $this->restaurant_bank_details->where('restaurant_id', $request->id)->first();
        if ($request->deliveryChargeBasedOn) {
            $restaurant_bank_details->DeliveryChargeBasedOn = $request->deliveryChargeBasedOn;
        }
        $restaurant_bank_details->update();

        $delivery_charge_minimum1 = explode(',', $request->delivery_charge_minimum);
        $delivery_charge_maximum1 = explode(',', $request->delivery_charge_maximum);
        $delivery_charge_value1 = explode(',', $request->delivery_charge_value);
        $delivery_charge_type1 = explode(',', $request->delivery_charge_type);
        $carrier_type = explode(',', $request->carrier_type);
        $delivery_charge_minimum = $delivery_charge_minimum1;
        $delivery_charge_maximum = $delivery_charge_maximum1;
        $delivery_charge_value = $delivery_charge_value1;
        $delivery_charge_type = $delivery_charge_type1;
        $delivery_charge_limit = $delivery_charge_minimum ? count($delivery_charge_minimum1) : 0;

        DB::table('delivery_charge_limits')->where('restaurant_id', $request->id)->delete();
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
            $res = DB::table('restaurants')->where('id', $request->id)->first();
            $bus = DB::table('business_type')->where('id', $res->business_type)->first();
            if ($bus->layout_id == 3) {
                DB::table('delivery_charge_limits')->insert([
                    'restaurant_id' => $request->id,
                    'Minimum' => $delivery_charge_minimum[$i],
                    'Maximum' => $delivery_charge_maximum[$i],
                    'Value' => $delivery_charge_value[$i],
                    'Type' => $delivery_charge_type[$i],
                    'carrier_type' => $carrier_type[$i],
                ]);
            } else {
                DB::table('delivery_charge_limits')->insert([
                    'restaurant_id' => $request->id,
                    'Minimum' => $delivery_charge_minimum[$i],
                    'Maximum' => $delivery_charge_maximum[$i],
                    'Value' => $delivery_charge_value[$i],
                    'Type' => $delivery_charge_type[$i],

                ]);
            }
        }
    }

    public function add_to_restaurants_tab6(Request $request)
    {
        if ($request->plan == "undefined") {
            $response_array = [
                'status' => false,
                'message' => "Please Select a Plan",
            ];
            return response()->json($response_array, 200);
        }
        $restaurant_id = $request->id;
        $plan_id = $request->plan;
        $plan_details = DB::table('plans')->where('id', $plan_id)->first();
        if ($plan_details->lifetime == 1) {
            $today = Carbon::now();
            $end = -1;
        } else {
            $days = $plan_details->validity;
            $today = Carbon::now();
            $to_add = Carbon::now();
            $end = $to_add->addDays($days);
        }
        $get = DB::table('restaurant_plan_history')->insertGetId([
            'restaurant_id' => $restaurant_id,
            'plan' => $plan_id,
            'is_paid' => 1,
            'status' => -1, //0-new,1-submitted,2-expired
            'plan_from' => $today,
            'plan_to' => $end,
        ]);

        DB::table('restaurants')->where('id', $restaurant_id)->update([
            'plan' => $get,
            'plan_from' => $today,
            'plan_to' => $end,
        ]);
        $response_array = [
            'status' => true,
            'message' => "Plan Details Added Successfully",
        ];
        return response()->json($response_array, 200);
    }
    public function area_list1(Request $request)
    {

        $area_list = DB::table('add_area')->where('add_city_id', $request->city)->get();
        return response()->json($area_list);
    }
    public function area_list2(Request $request)
    {

        $area_list = DB::table('add_area')->where('add_city_id', $request->city)->where('status', 1)->get();
        return response()->json($area_list);
    }

    public function store_login(Request $request)
    {
        $id = $request->id;
        $res_id = DB::table('restaurants')->where('id', $id)->first();



        $ad = DB::table('restaurants')->where('id', $id)->first();


        $res = DB::table('restaurants')->where('id', $ad->id)
            ->first();
        $datas = DB::table('business_type')->where('id', $res->business_type)
            ->first();

        $privilege = [];
        $ability = [];


        if ($datas->layout_id == 2) {
            $privilege[] = [
                'addon',
                'brands',
                'business',
                'reason',
                'category-view',
                'category-edit',
                'city-view',
                'city-edit',
                'contents',
                'cuisines-view',
                'document-view',
                'document-edit',
                'driver',
                'food',
                'payout',
                'promocodes',
                'report-view',
                'banners',
                'restaurant-view',
                'restaurant-edit',
                'roles',
                'settings',
                'user-view',
                'vehicles',
                'order-view',
                'dashboard',
                'store_category-view',
                'store_order-view',

                'store_dashboard',
                'store_pos',
                'store_product',
                'store_promocodes',
                'store_report-view',
                'store_restaurant',
                'store_restaurant_timing',
                'store_map',



            ];

            foreach ($privilege as $pri) {

                $ability[] = [
                    'action' => "read",
                    'subject' => $pri
                ];
            }
        } elseif ($datas->layout_id == 3) {
            $privilege[] = [
                'addon',
                'brands',
                'business',
                'reason',
                'category-view',
                'category-edit',
                'city-view',
                'city-edit',
                'contents',
                'cuisines-view',
                'document-view',
                'document-edit',
                'driver',
                'food',
                'payout',
                'promocodes',
                'report-view',
                'banners',
                'restaurant-view',
                'restaurant-edit',
                'roles',
                'settings',
                'user-view',
                'vehicles',

                'order-view',
                'dashboard',

                'store_order-view',

                'store_dashboard',
                'store_promocodes',
                'store_report-view',
                'store_restaurant',
                'store_restaurant_timing',
                'store_map',

            ];
            foreach ($privilege as $pri) {
                $ability[] = [
                    'action' => "read",
                    'subject' => $pri
                ];
            }
        } else {
            $ability[] = [
                'action' => "read",
                'subject' => "all"
            ];
        }

        // $ability[]=[
        //     'action'=>"read",
        //     'subject'=>"all"
        // ];


        $data[] = [
            'id' => $ad->id,
            'fullName' => $ad->restaurant_name,
            'username' => $ad->restaurant_name,
            'avatar' => "/img/13-small.d796bffd.png",
            'email' => $ad->email,
            'ability' => $ability,
            'role' => 2,
            'privilege' => -1,

        ];

        $message = "Success";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $data]);


        return $response_Array;
    }
    public function add_area(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area' => 'required',
            'status' => 'required',
            // 'geofence_latlng' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
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
                    // 'polygons' => $request->geofence_latlng,

                );
                $add_area->insert($data);
            } else {

                $message = "This Area Was Already Registered";
                $status = false;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;

                // return back()->with('error', trans('constants.this_area_was_already_registered'));
            }
        }
        $message = "Add Successfully";
        $status = true;
        $data = $request->id;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $data]);
        return $response_Array;
        // return redirect('admin/view_areas/' . $request->id)->with('success', trans('constants.add_success_msg', ['param' => 'Area']));
    }

    public function edit_area($id)
    {
        $area = $this->addcity->where('id', $id)->first();

        return response()->json($area);
    }
    public function area_edit($id)
    {
        $area = $this->addarea->find($id);

        return response()->json($area);
    }
    public function delete_area_list($id)
    {
        $delete = $this->addarea->where('id', $id)->delete();

        $message = "Area Deleted Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }

    public function update_area_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area' => 'required',
            'status' => 'required',
            // 'geofence_latlng' => 'required',
        ]);

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        } else {
            $area = $this->addarea->find($request->id);
            $area->area = $request->area;
            $area->status = $request->status;
            $area->latitude = $request->latitude;
            $area->longitude = $request->longitude;
            // $area->polygons = $request->geofence_latlng;
            $area->save();

            $message = "Updated Successfully";
            $status = true;
            $data = $area->add_city_id;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $data]);
            return $response_Array;

            // return redirect('/admin/view_areas/' . $area->add_city_id)->with('success', trans('constants.update_success_msg', ['param' => 'Area']));
        }
    }
    public function category_sort_save(Request $request)
    {

        $categoryRanks = $request->categoryRanks;

        // $category_ranks = explode(',', $categoryRanks);

        foreach ($categoryRanks as $rank => $id) {
            DB::table('category')->where('id', $id)->update(['rank' => $rank]);
        }

        // $categories = get_category_by_rank();
        $categories = DB::table('category')->orderBy('rank', 'asc')->get();
        $message = "Updated Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $categories]);
        return $response_Array;
        // return view('admin/category_sort')->with('categories', $categories);
    }
    public function brand_sort_save(Request $request)
    {

        $brandranking = $request->brandRanks;

        // $brand_ranks = explode(',', $brandranking);

        foreach ($brandranking as $rank => $id) {
            DB::table('brands')->where('id', $id)->update(['rank' => $rank]);
        }
        $brandlist = DB::table('brands')->orderBy('rank', 'asc')->get();

        $message = "Updated Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $brandlist]);
        return $response_Array;
        // return view('admin/brand_sort')->with('brandlist', $brandlist);
    }
    function riderStatus(Request $request)
    {
        $rider = DB::table('delivery_partners')->where('id', $request->id)->first();
        if ($rider->status == 1) {
            $status = 0;
            $msg = "Status Inactive";
        } else {
            $status = 1;
            $msg = "Status Active";
        }

        DB::table('delivery_partners')->where('id', $request->id)->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;
    }
    public function view_deliveryboy_order_details($id)
    {
        // dd($id);
        $data = DB::table('requests')
            ->where('delivery_boy_id', $id)
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
            ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
            ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as driver_name', 'restaurants.restaurant_name as restaurant_name', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.*', 'requests.status as status')
            ->get();

        return response()->json($data);
        //   dd($data);

        // return view('admin/view_delivery_boy_order_details', ['delivery_boy_details' => $delivery_boy_details]);
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

        // foreach ($restaurant_detail as $key => $val) {
        // if ($restaurant_detail->delivery_type != "")
        // {
        //     $type= preg_replace('/\([^)]*\)|[()]/', '', $restaurant_detail->delivery_type);
        //     dd( $type);
        //     $restaurant_detail->delivery_type = $restaurant_detail->delivery_type;
        // }

        // if($restaurant_detail->password != "")
        // {
        //     $decrypted = Crypt::decryptString($restaurant_detail->password);
        //     $restaurant_detail->password =$decrypted;

        // }
        if ($restaurant_detail->password) {
            $restaurant_detail->password = $restaurant_detail->org_password;
        }

        if ($restaurant_detail->holidays != null || $restaurant_detail->holidays != '') {
            $array = explode(',', $restaurant_detail->holidays);
            $restaurant_detail->holidays = $array;
        } else {
            $restaurant_detail->holidays = null;
        }
        if ($restaurant_detail->address != "") {
            $decodedText = html_entity_decode($restaurant_detail->address);
            $restaurant_detail->address = $decodedText;
        }

        $area = $this->addarea->where('add_city_id', $restaurant_detail->city)->get();

        if ($restaurant_detail->brand_name == 0) {
            $restaurant_detail->brand_name = '';
        }
        if ($restaurant_detail->banner != "") {



            if (file_exists(public_path('/') . RESTAURANT_BANNER . $restaurant_detail->banner)) {
                $restaurant_detail->banner =  BASE_URL . RESTAURANT_BANNER . $restaurant_detail->banner;
            } elseif (file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH . $restaurant_detail->banner)) {
                $restaurant_detail->banner =  BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->banner;
            } elseif (file_exists(public_path('/') . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . "/Banner" . "/" . $restaurant_detail->banner)) {
                $restaurant_detail->banner =  BASE_URL . RESTAURANT_UPLOADS_PATH . $restaurant_detail->id . "/Banner" . "/" . $restaurant_detail->banner;
            } else {
                $restaurant_detail->banner = "";
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
                $restaurant_detail->image = "";
            }
        } else {
            $restaurant_detail->image = "";
        }



        foreach ($restaurant_detail->document as $val) {


            $val->id = (string)$val->id;
            $val->pivot->document = BASE_URL . UPLOADS_PATH . $val->pivot->document;
        }


        $res = DB::table('restaurants')->where('id', $id)->first();
        $bus = DB::table('business_type')->where('id', $res->business_type)->first();
        if ($bus->layout_id == 3) {
            if ($restaurant_detail->carrier_type != "null" || $restaurant_detail->carrier_type != '') {
                $integerIDs = array_map('intval', json_decode($restaurant_detail->carrier_type, true));

                $restaurant_detail->carrier_type = $integerIDs;
            }
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

        $res = DB::table('restaurants')->where('id', $id)->first();
        $bus = DB::table('business_type')->where('id', $res->business_type)->first();

        $document = $this->document->where('document_for', 2)->where('status', 1)->where('layout_id', $bus->layout_id)->get();


        if ($bus->layout_id == 3) {
            $carrier = DB::table('carrier_package')->whereIn('id', $restaurant_detail->carrier_type)->where('status', 1)->get();


            $delivery_charge_limits = [];
            foreach ($carrier as $d) {

                $datas = DB::table('delivery_charge_limits')
                    ->where('restaurant_id', $id)
                    ->where('carrier_type', $d->id)
                    ->get();

                if ($d->package_name) {
                    $d->package_name = $this->secondLanguage($d->package_name, $d->secondary_package_name);
                }

                if (count($datas) > 0) {
                    $delivery_charge_limits[] = [
                        'package_name' => $d->package_name,
                        'id' => $d->id,
                        'invoice' =>  $datas
                    ];
                } else {
                    $datass = DB::table('delivery_charge_limits')
                        ->where('restaurant_id', $id)
                        ->where('carrier_type', $d->id)
                        ->insert([
                            'restaurant_id' => $id,
                            'Minimum' => 0,
                            'Maximum' => '', //1-credit 2-debit
                            'Value' => '',
                            'Type' => 1, //1-credit 2-debit
                            'carrier_type' =>  $d->id
                        ]);

                    $datas = DB::table('delivery_charge_limits')
                        ->where('restaurant_id', $id)
                        ->where('carrier_type', $d->id)
                        ->get();

                    $delivery_charge_limits[] = [
                        'package_name' => $d->package_name,
                        'id' => $d->id,
                        'invoice' => $datas
                    ];
                }
            }
        } else {

            $delivery_charge_limits = DB::table('delivery_charge_limits')->where('restaurant_id', $id)->get();
        }

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

        $ds = [];
        // foreach (json_decode($restaurant_detail->delivery_states) as $rds) {
        //     $ds[] = (int) $rds;
        // }

        // $restaurant_detail->delivery_states = $ds;


        $plan_details = DB::table('restaurants')->where('id', $id)->first();
        $rt_plan = [];
        if ($plan_details->plan != null) {
            $plan_hist = DB::table('restaurant_plan_history')->where('id', $plan_details->plan)->first();
            $rt_plan = [
                'plan' => $plan_hist->plan,
                'plan_from' => $plan_hist->plan_from,
                'plan_to' => $plan_hist->plan_to
            ];
        }
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
            'plan' => $rt_plan
        ]);
        // dd($response_Array);
        return $response_Array;
    }

    public function delete_restaurant(Request $request)
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
            $restaurants = $this->restaurants;
            $foodlist = $this->foodlist;
            $banner = $this->banner;


            try {
                $res = $restaurants->where('id', $id)->delete();
            } catch (\Exception $e) {
                $message = "Please remove the products from store before deleting";
                $status = true;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }

            try {
                $foodlist->where('restaurant_id', $id)->delete();
            } catch (\Exception $e) {
                $message = "Please remove the products from store before deleting";
                $status = true;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }
            try {
                $banner->where('restaurant_id', $id)->delete();
            } catch (\Exception $e) {
                $message = "Please remove the Banner from store before deleting";
                $status = true;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }


            $message = "Store Removed Successfully";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
            // return redirect('/admin/restaurant_list')->with('success', trans('constants.restaurant_removed'));

        }
    }

    // public function restaurant_view($id)
    // {
    //     $restaurant_view = $this->restaurants->with(['city_list', 'Area'])->find($id);
    //     //dd($restaurant_view);
    //     $message = "Restaurat View";
    //     $status = false;
    //     $response_Array = json_encode(['message'=>$message,'status'=>$status,'restaurant' => $restaurant_view]);
    //     return $response_Array;
    //     // return view('admin/restaurant_view', ['restaurant' => $restaurant_view]);
    // }

    public function document_view_doc(Request $request)
    {
        // dd($request);
        $document_list = DB::table('restaurants_document')
            ->where('restaurants_id', $request->res_id)
            ->where('document_id', $request->doc_id)->get();

        return response()->json($document_list);
    }

    public function view_delivery_boy($id, Request $request)
    {

        $driver = DB::table('delivery_partners')->where('id', $id)->first();
        $details = DB::table('delivery_partner_details')
            ->join('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
            ->where('delivery_partners_id', $id)->first();
        $document = DB::table('driver_document')
            ->join('document', 'document.id', '=', 'driver_document.document_id')
            ->where('driver_id', $id)->get();

        $response_Array = json_encode(['driver' => $driver, 'details' => $details, 'document' => $document]);
        return $response_Array;
        // return view('admin/view_driver', ['driver' => $driver, 'details' => $details, 'document' => $document]);
    }

    public function restaurant_view($id)
    {

        $data = $this->restaurants->with(['city_list', 'Area'])->find($id);

        return response()->json($data);
        // return view('admin/restaurant_view', ['restaurant' => $restaurant_view]);
    }

    public function delete_category($id)
    {

        // $data = $this->restaurants->with(['city_list', 'Area'])->find($id);
        $data = DB::table('food_list')->where('category_id', $id)->get()->count();

        if ($data == 0) {
            $data = DB::table('category')->where('id', $id)->delete();
            $message = "Deleted Successfully";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        } else {
            $message = "Please Remove Product";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);
            return $response_Array;
        }

        // return response()->json($data) ;
        // return view('admin/restaurant_view', ['restaurant' => $restaurant_view]);
    }

    public function delete_reason($id)
    {

        // $data = $this->restaurants->with(['city_list', 'Area'])->find($id);
        $data = DB::table('cancellation_reason')->where('id', $id)->delete();


        $message = "Deleted Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);
        return $response_Array;


        // return response()->json($data) ;
        // return view('admin/restaurant_view', ['restaurant' => $restaurant_view]);
    }


    public function restaurant_status($id)
    {

        $reason = $this->restaurants->where('id', $id)->first();
        if ($reason->status == 1) {
            $status = 0;
            $msg = "Status Inactive";
        } else {
            $status = 1;
            $msg = "Status Active";
        }

        $reason_list = $this->restaurants->where('id', $id)
            ->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function banner_status($id)
    {

        $reason = $this->banner->where('id', $id)->first();
        if ($reason->status == 1) {
            $status = 2;
            $msg = "Status Inactive";
        } else {
            $status = 1;
            $msg = "Status Active";
        }

        $banner_list = $this->banner->where('id', $id)
            ->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function city_status($id)
    {

        $reason = DB::table('add_city')->where('id', $id)->first();
        if ($reason->status == 1) {
            $status = 2;
            $msg = "Status Inactive";
        } else {
            $status = 1;
            $msg = "Status Active";
        }

        $banner_list = DB::table('add_city')->where('id', $id)
            ->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function area_status($id)
    {

        $area_list = DB::table('add_area')->where('id', $id)->first();
        if ($area_list->status == 1) {
            $status = 2;
            $msg = "Status Inactive";
        } else {
            $status = 1;
            $msg = "Status Active";
        }

        $banner_list = DB::table('add_area')->where('id', $id)
            ->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function vehicle_status($id)
    {

        $area_list = DB::table('vehicle')->where('id', $id)->first();
        if ($area_list->status == 1) {
            $status = 2;
            $msg = "Status Inactive";
        } else {
            $status = 1;
            $msg = "Status Active";
        }

        $banner_list = DB::table('vehicle')->where('id', $id)
            ->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function brand_status($id)
    {

        $area_list = DB::table('brands')->where('id', $id)->first();
        if ($area_list->status == 1) {
            $status = 2;
            $msg = "Status Inactive";
        } else {
            $status = 1;
            $msg = "Status Active";
        }

        $banner_list = DB::table('brands')->where('id', $id)
            ->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function document_status($id)
    {

        $area_list = DB::table('document')->where('id', $id)->first();
        if ($area_list->status == 1) {
            $status = 2;
            $msg = "Status Inactive";
        } else {
            $status = 1;
            $msg = "Status Active";
        }

        $banner_list = DB::table('document')->where('id', $id)
            ->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function promocode_status($id)
    {

        $area_list = DB::table('coupon_code')->where('id', $id)->first();
        if ($area_list->status == 1) {
            $status = 0;
            $msg = "Status Inactive";
        } else {
            $status = 1;
            $msg = "Status Active";
        }

        $banner_list = DB::table('coupon_code')->where('id', $id)
            ->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }
    public function category_status($id)
    {

        $area_list = DB::table('category')->where('id', $id)->first();
        if ($area_list->status == 1) {
            $status = 0;
            $msg = "Status Inactive";
        } else {
            $status = 1;
            $msg = "Status Active";
        }

        $banner_list = DB::table('category')->where('id', $id)
            ->update(['status' => $status]);

        $message = $msg;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function eagle_view(Request $request)
    {

        $rests = DB::table('restaurants')->where('status', 1)->select()->get();
        $rest = [];
        $j = 1;

        foreach ($rests as $res) {
            $rest[] = [ucfirst($res->restaurant_name), $res->lat, $res->lng, $j++];
        }

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



            if (isset($each_driver->lat) == null) {
            } else {
                if ($this_driver->status == 1) {
                    $locations[] = [ucfirst($this_driver->name), $each_driver->lat, $each_driver->lng, $i++];
                }
            }
        }

        $status = true;
        $response_Array = json_encode(['restaurant' => $rest, 'driver' => $locations, 'status' => $status]);


        return $response_Array;
    }

    public function set_featured(Request $request)
    {
        $id = $request->id;

        $set = DB::table('offers_banner')->where('id', $id)->first();
        $rest_c = DB::table('offers_banner')->where('featured', 1)->count();


        if ($set->featured == 0) {
            // if($rest_c >= 10){
            //     $message ="Maximum 10 Banner can be set as Featured";
            //     $status = false;
            //     $response_Array = json_encode(['message'=>$message,'status'=>$status]);
            //     return $response_Array;
            // }
            DB::table('offers_banner')->where('id', $id)->update([
                'featured' => 1
            ]);
            $message = "Banner Marked as Featured";
        } else {
            DB::table('offers_banner')->where('id', $id)->update([
                'featured' => 0
            ]);
            $message = "Banner Removed from Featured";
        }



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

    public function get_place_details(Request $request)
    {
        // dd($request->id);

        $q = "https://maps.googleapis.com/maps/api/place/details/json?place_id=$request->id&key=" . GOOGLE_API_KEY;
        $json = file_get_contents($q);
        $details = json_decode($json, TRUE);
        $avail_photos = [];
        if (
            $details['result']['photos'] &&
            count($details['result']['photos']) > 0
        ) {
            foreach ($details['result']['photos'] as $pd) {
                $pr = $pd['photo_reference'];
                $q2 = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photo_reference=$pr&key=" . GOOGLE_API_KEY;

                $json2 = file_get_contents($q2);
                $details2 = base64_encode($json2);

                $avail_photos[] = $details2;
            }
        }

        // dd($avail_photos[2]);



        return response()->json([
            'message' => 'Fetched sight!',
            'image' => $avail_photos
        ], 201);

        // dd($details2);
    }

    public function get_header_categories(Request $request)
    {
        $hc = DB::table('header_category')->orderby('rank', 'asc')->get();

        $response_Array = json_encode(['categories' => $hc]);


        return $response_Array;
    }

    public function get_header_sub_categories(Request $request)
    {
        $hc = DB::table('header_sub_category')->where('header_category', $request->id)->orderby('rank', 'asc')->get();

        $response_Array = json_encode(['sub_categories' => $hc]);


        return $response_Array;
    }

    public function add_new_header_page(Request $request)
    {


        $name = $request->name;
        $category = $request->category;
        $sub_category = $request->sub_category;

        $meta_title = $request->meta_title;
        $meta_desc = $request->meta_desc;
        $slug = $request->slug;
        $custom = $this->custom;
        if ($request->meta_image) {
            if ($request->existing == "undefined") {
                $metaimage  = basename($request->meta_image);
            } else {
                if ($request->existing == 1) {
                    $metaimage  = basename($request->meta_image);
                } else {

                    $metaimage = $custom->upload_image_ques($request, 'meta_image');
                }
            }
        } else {
            $metaimage = null;
        }


        if ($request->id) {
            $insert = DB::table('header_page_contents')->where('id', $request->id)->update([
                'name' => $name,
                'header_category' => $category,
                'header_sub_category' => $sub_category,
                'meta_title' => $meta_title,
                'meta_desc' => $meta_desc,
                'slug' => $slug,
                'meta_image' => $metaimage

            ]);

            $delete_old = DB::table('header_page_segments')->where('page_id', $request->id)->delete();

            if (count($request->segments)) {
                foreach ($request->segments as $req_segs) {
                    if (array_key_exists('image', $req_segs)) {
                        if ($req_segs['image'] != '') {
                            if ($req_segs['existing'] == "undefined") {
                                $image  = basename($req_segs['image']);
                            } else {
                                if ($req_segs['existing'] == 1) {
                                    $image = basename($req_segs['image']);
                                } else {

                                    $image = $custom->upload_image_ques($req_segs, 'image');
                                }
                            }
                            $image = $image;
                        } else {
                            $image = null;
                        }
                    } else {
                        $image = null;
                    }
                    $content_page = "page" . $name . rand(0, 100) . ".rtf";

                    \File::put(public_path() . '/' . 'page_files/' . $content_page, $req_segs['content']);


                    DB::table('header_page_segments')->insert([
                        'page_id' => $request->id,
                        'type' => $req_segs['type'],
                        'image' => $image,
                        'content' => $content_page
                    ]);
                }
            }
            $message = "Page Edited Successfully";
        } else {
            $count = DB::table('header_page_contents')->get();
            $nxt_rank = count($count);
            $insert = DB::table('header_page_contents')->insertGetId([
                'name' => $name,
                'header_category' => $category,
                'header_sub_category' => $sub_category,
                'rank' => $nxt_rank + 1,
                'meta_title' => $meta_title,
                'meta_desc' => $meta_desc,
                'slug' => $slug,
                'meta_image' => $metaimage



            ]);

            if (count($request->segments)) {
                foreach ($request->segments as $req_segs) {
                    if (array_key_exists('image', $req_segs)) {
                        if ($req_segs['image'] != '') {
                            if ($req_segs['existing'] == "undefined") {
                                $image  = basename($req_segs['image']);
                            } else {
                                if ($req_segs['existing'] == 1) {
                                    $image = basename($req_segs['image']);
                                } else {

                                    $image = $custom->upload_image_ques($req_segs, 'image');
                                }
                            }
                            $image = $image;
                        } else {
                            $image = null;
                        }
                    } else {
                        $image = null;
                    }
                    $content_page = "page" . $name . rand(0, 100) . ".rtf";

                    \File::put(public_path() . '/' . 'page_files/' . $content_page, $req_segs['content']);


                    DB::table('header_page_segments')->insert([
                        'page_id' => $insert,
                        'type' => $req_segs['type'],
                        'image' => $image,
                        'content' => $content_page
                    ]);
                }
            }
            $message = "Page Added Successfully";
        }



        $status = true;
        $response_Array = json_encode(['status' => $status, 'message' => $message]);
        return $response_Array;
    }

    public function get_header_pages(Request $request)
    {
        $header_pages = DB::table('header_page_contents')
            ->leftJoin('header_category', 'header_category.id', '=', 'header_category')
            ->leftJoin('header_sub_category', 'header_sub_category.id', '=', 'header_sub_category')
            ->select('header_page_contents.id as pid', 'header_page_contents.name as page_name', 'header_page_contents.status as page_status', 'header_page_contents.*', 'header_category.name as header_category_name', 'header_category.*', 'header_sub_category.name as header_sub_category_name')
            ->get();
        $status = true;
        $response_Array = json_encode(['status' => $status, 'header_pages' => $header_pages]);
        return $response_Array;
    }

    public function change_page_status(Request $request)
    {
        $stat = DB::table('header_page_contents')
            ->where('id', $request->id)->first();

        if ($stat->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        DB::table('header_page_contents')
            ->where('id', $request->id)->update([
                'status' => $status
            ]);

        $status = true;
        $response_Array = json_encode(['status' => $status, 'message' => "Page Status Changed"]);
        return $response_Array;
    }

    public function fetch_header_page(Request $request)
    {
        $header_pages = DB::table('header_page_contents')
            ->where('header_page_contents.id', $request->id)
            ->leftJoin('header_category', 'header_category.id', '=', 'header_category')
            ->leftJoin('header_sub_category', 'header_sub_category.id', '=', 'header_sub_category')
            ->select('header_page_contents.name as page_name', 'header_page_contents.status as page_status', 'header_page_contents.*', 'header_category.name as header_category_name', 'header_category.*', 'header_sub_category.name as header_sub_category_name')
            ->first();
        $header_pages->meta_image =  BASE_URL . "uploads/" . $header_pages->meta_image;
        $page_contents = DB::table('header_page_segments')->where('page_id', $request->id)->get();

        if (count($page_contents)) {
            foreach ($page_contents as $pc) {

                $pc->image = BASE_URL . "uploads/" . $pc->image;
                $content = fopen(public_path() . '/' . 'page_files/' . $pc->content, 'r');
                while (!feof($content)) {
                    $line = fgets($content);
                }
                fclose($content);
                $data[] = array(
                    'data' => $line,
                );
                $pc->content = $data;
            }
        }
        $status = true;
        $response_Array = json_encode(['status' => $status, 'header_pages' => $header_pages, 'page_contents' => $page_contents]);
        return $response_Array;
    }

    public function add_new_header_category(Request $request)
    {

        if ($request->id) {
            $update = DB::table('header_category')->where('id', $request->id)->update([
                'name' => $request->name,

            ]);
            $message = "Category Edited Successfully";
        } else {
            $count = DB::table('header_category')->get();
            $nxt_rank = count($count);
            $insert = DB::table('header_category')->insert([
                'name' => $request->name,
                'rank' => $nxt_rank + 1
            ]);

            $message = "Category Added Successfully";
        }

        $status = true;
        $response_Array = json_encode(['status' => $status, 'message' => $message]);
        return $response_Array;
    }

    public function change_header_cat_status(Request $request)
    {
        $stat = DB::table('header_category')
            ->where('id', $request->id)->first();

        if ($stat->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        DB::table('header_category')
            ->where('id', $request->id)->update([
                'status' => $status
            ]);

        $status = true;
        $response_Array = json_encode(['status' => $status, 'message' => "Category Status Changed"]);
        return $response_Array;
    }

    public function get_header_subcategories(Request $request)
    {
        $subs = DB::table('header_sub_category')
            ->leftJoin('header_category', 'header_category.id', '=', 'header_sub_category.header_category')
            ->select('header_category.name as header_category_name', 'header_sub_category.name as header_sub_category_name', 'header_sub_category.*')
            ->get();

        $status = true;
        $response_Array = json_encode(['status' => $status, 'subs' => $subs]);
        return $response_Array;
    }

    public function add_new_header_subcategory(Request $request)
    {
        if ($request->id) {
            $update = DB::table('header_sub_category')->where('id', $request->id)->update([
                'name' => $request->name,
                'header_category' => $request->category
            ]);
            $message = "Subcategory Edited Successfully";
        } else {
            $count = DB::table('header_sub_category')->get();
            $nxt_rank = count($count);
            $insert = DB::table('header_sub_category')->insert([
                'name' => $request->name,
                'rank' => $nxt_rank + 1,
                'header_category' => $request->category
            ]);
            $message = "Subcategory Added Successfully";
        }
        $status = true;
        $response_Array = json_encode(['status' => $status, 'message' => $message]);
        return $response_Array;
    }

    public function change_header_subcat_status(Request $request)
    {
        $stat = DB::table('header_sub_category')
            ->where('id', $request->id)->first();

        if ($stat->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        DB::table('header_sub_category')
            ->where('id', $request->id)->update([
                'status' => $status
            ]);

        $status = true;
        $response_Array = json_encode(['status' => $status, 'message' => "Sub Category Status Changed"]);
        return $response_Array;
    }

    public function header_cat_sort_save(Request $request)
    {

        $categoryRanks = $request->categoryRanks;


        foreach ($categoryRanks as $rank => $id) {
            DB::table('header_category')->where('id', $id)->update(['rank' => $rank]);
        }

        $categories = DB::table('header_category')->orderBy('rank', 'asc')->get();
        $message = "Updated Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $categories]);
        return $response_Array;
    }

    public function subcat_sort_save(Request $request)
    {
        $categoryRanks = $request->categoryRanks;


        foreach ($categoryRanks as $rank => $id) {
            DB::table('header_sub_category')->where('id', $id)->update(['rank' => $rank]);
        }

        $categories = DB::table('header_sub_category')->orderBy('rank', 'asc')->get();
        $message = "Updated Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $categories]);
        return $response_Array;
    }

    public function get_products(Request $request)
    {
        $products = DB::table('food_list')->where('status', 1)->get();
        $status = true;
        $response_Array = json_encode(['status' => $status, 'products' => $products]);
        return $response_Array;
    }

    public function fetch_header_menu(Request $request)
    {
        $header_category = DB::table('header_category')->where('id', $request->id)->first();
        $pages = DB::table('header_page_contents')->where('header_category', '!=', $request->id)->Orwhere('header_category', null)->get();
        $in_pages = DB::table('header_page_contents')->where('header_category', $request->id)->get();
        $status = true;
        $response_Array = json_encode(['status' => $status, 'header_category' => $header_category, 'pages' => $pages, 'in_pages' => $in_pages]);
        return $response_Array;
    }

    public function save_page_category(Request $request)
    {
        $pages = json_decode($request->pages);

        $category = $request->cat;
        DB::table('header_page_contents')->where('header_category', $category)->update([
            'header_category' => null
        ]);
        if (count($pages)) {
            foreach ($pages as $page) {
                $page_id = $page->id;
                DB::table('header_page_contents')->where('id', $page_id)->update([
                    'header_category' => $category
                ]);
            }
        }

        $status = true;
        $response_Array = json_encode(['status' => $status, 'message' => "Pages Updated"]);
        return $response_Array;
    }

    public function categoryWiseProductList($categoryid)
    {
        $productList = DB::table('food_list')
            ->leftJoin('business_category', 'business_category.id', '=', 'food_list.business_category_id')
            ->where('food_list.business_category_id', $categoryid) // changed condition
            ->select(
                'food_list.id as food_id',
                'business_category.category_name as business_name',
                'food_list.*',
                'food_list.status as f_status',
                'food_list.out_of_stock as stock_status'
            )
            ->get();


        return response()->json($productList);
    }


    public function product_list(Request $request)
    {
        // $restaurant_id = $request->id;

        // $res_details = DB::table('restaurants')->where('id', $restaurant_id)
        //     ->first();
        // $business = DB::table('business_type')->where('id', $res_details->business_type)
        //     ->first();

        // if ($business->layout_id == 2) {
        //     $data = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
        //         ->leftJoin('category', 'category.id', '=', 'food_list.category_id')
        //         ->leftJoin('business_category', 'business_category.id', '=', 'food_list.business_category_id')
        //         ->select('food_list.id as food_id', 'business_category.category_name as business_name', 'food_list.*', 'category.*',  'food_list.status as f_status', 'food_list.out_of_stock as stock_status')
        //         ->get();
        // } else {
        //     $data = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
        //         ->leftJoin('menu', 'menu.id', '=', 'food_list.menu_id')
        //         ->leftJoin('business_category', 'business_category.id', '=', 'food_list.business_category_id')
        //         ->select('food_list.id as food_id', 'business_category.category_name as business_name', 'food_list.*',  'menu.*', 'food_list.status as f_status', 'food_list.out_of_stock as stock_status')
        //         ->get();
        // }
        //  $download_path = BASE_URL . UPLOADS_PATH . $request->id . "_products.csv";
        // $response_Array = json_encode(['data' => $data, 'download_path' => $download_path, 'business' => $business->layout_id]);
        // return $response_Array;

        $data = DB::table('food_list')
            ->leftJoin('menu', 'menu.id', '=', 'food_list.menu_id')
            ->leftJoin('business_category', 'business_category.id', '=', 'food_list.business_category_id')
            ->select(
                'food_list.id as food_id',
                'business_category.category_name as business_name',
                'food_list.*',
                'menu.*',
                'food_list.status as f_status',
                'food_list.out_of_stock as stock_status'
            )
            ->get();

        foreach ($data as $d) {
            $imagePath = 'product_image/' . $d->image;
            if (file_exists(public_path($imagePath))) {
                $d->image = BASE_URL .  $imagePath;
            } else {
                $d->image = BASE_URL .  "def_logo.jpg";
            }
            $d->name = $this->secondLanguage_store($d->name, $d->secondary_name);
            $d->description = $this->secondLanguage_store($d->description, $d->secondary_description);
        }
        $download_path = BASE_URL . UPLOADS_PATH . "_products.csv";
        $response_Array = json_encode(['data' => $data, 'download_path' => $download_path, 'business' => '']);
        return $response_Array;
    }

    public function add_to_product(Request $request)
    {

        $validator_primary = Validator::make($request->all(), [
            'name' => 'required',
            'business_category_id' => 'required',
            'status' => 'required',
            'packaging_charge' => 'required',
            'product' => 'required',
        ]);

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

        // if (!empty($request->food_quantity_price)) {
        //     $food_quantity_price = array_filter($request->food_quantity_price, function ($value) {
        //         return $value !== '';
        //     });
        // }
        // if (!empty($request->food_quantity_desc)) {
        //     $food_quantity_desc = array_filter($request->food_quantity_desc, function ($value) {
        //         return $value !== '';
        //     });
        // }

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
        // $category = $request->category ?? 0;
        $business_category_id = $request->business_category_id ? $request->business_category_id : '';
        $menu = $request->menu ?? 0;

        $status = $request->status;
        $stockstatus = $request->stockstatus;
        // $initial_price = $request->initial_price;
        // $split_payment = $request->split_payment;
        // $price = $request->price;
        // $bprice = $request->bprice;
        // $tax = $request->tax;
        $packaging_charge = $request->packaging_charge;
        $food_type = (int) $request->food_type;
        $toggle_status = (int) $request->toggle_status;

        if ($request->id) {
            $foodlist = $this->foodlist->find($request->id);
            // $foodlist->restaurant_id = $restaurant_id;
            $foodlist->name = $name;
            $foodlist->secondary_name = $namesecondary;
            $foodlist->description = $description;
            $foodlist->secondary_description = $descriptionsecondary;
            // $foodlist->category_id = $category;
            $foodlist->business_category_id = $business_category_id;
            $foodlist->menu_id = $menu;
            $foodlist->status = $status;
            $foodlist->out_of_stock = $stockstatus;
            // $foodlist->price = $price;
            // $foodlist->bprice = $bprice;
            // $foodlist->initial_price = $initial_price;
            // $foodlist->split_payment = $split_payment;
            // $foodlist->tax = $tax;
            $foodlist->packaging_charge = $packaging_charge;
            $foodlist->is_veg = $food_type;
            $foodlist->status = $status;
            $foodlist->is_secondary = $toggle_status;

            $request->existing = json_decode($request->existing, true);
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
                        // $product_image_upload = $this->custom->common_upload_images_product($request, 'image', $restaurant_id);
                        $product_image_upload = $this->custom->common_upload_images_product($request, 'image');
                        $json = json_decode($product_image_upload, true);
                        $foodlist->image = $json['imagename'] ?  $json['imagename'] : $foodlist->image;
                        $foodlist->cloudflare_imageid = $json['id'];
                    }
                }
            }

            $foodlist->save();

            $foodId = $foodlist->id;

            // Delete existing pricing data for this product
            FoodListPricing::where('product_id', $foodId)->delete();

            $product_list = json_decode($request->product, true);

            foreach ($product_list as $group) {
                $districtIds = $group['district'];
                $price = $group['price'];
                $state = $group['state'];
                $tax = $group['tax'] ?? 0;
                $label = $group['label']; // from request

                // Generate simple random group_id (no state logic)
                $groupId = strtoupper(Str::random(10)); // e.g., 'A3D9F1X0TY'
                $food_pri = new FoodListPricing();
                $food_pri->product_id = $foodId;
                $food_pri->state_id = $state;
                $food_pri->price = $price;
                $food_pri->tax = $tax;
                $food_pri->label = $label;
                $food_pri->group_id = $groupId;
                $food_pri->save();


                // Attach districts via pivot table
                $food_pri->districts()->sync($districtIds);
            }

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
            // $delete_old_size = DB::table('foodlist_foodquantity')->where('foodlist_id', $request->id)->delete();
            // if ($request->food_quantity_id) {
            //     $food_quantity = $this->food_quantity->find($request->food_quantity_id);
            //     $food_quantity_count = $food_quantity ? count($food_quantity) : 0;
            //     $sync_data = array();
            //     $default = 0;
            //     for ($i = 0; $i < $food_quantity_count; $i++) {
            //         if ($i == 0) {
            //             $default = 1;
            //         } else {
            //             $default = 0;
            //         }
            //         if ($food_quantity_price[$i]) {
            //             if ($food_quantity_desc[$i] == "undefined") {
            //                 $food_quantity_desc[$i] = '';
            //             }
            //             $sync_data[$food_quantity[$i]->id] = ['price' => $food_quantity_price[$i], 'size_description' => $food_quantity_desc[$i], 'is_default' => $default];
            //             DB::table('foodlist_foodquantity')->insert([
            //                 'foodlist_id' => $request->id,
            //                 'foodquantity_id' => $food_quantity[$i]->id,
            //                 'price' => $food_quantity_price[$i],
            //                 'size_description' => $food_quantity_desc[$i],
            //                 'is_default' => $default
            //             ]);
            //         }
            //     }
            //     if ($request->addon_type == 0) {

            //         for ($i = 0; $i < $food_quantity_count; $i++) {
            //             if ($request->size_addons != null && $request->size_addons[$i] != null) {
            //                 $j = [];
            //                 for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
            //                     $dump = json_decode($request->size_addons[$i]);
            //                     $ad = json_decode($dump[$k]);
            //                     $j[] = strval($ad->id);
            //                 }
            //                 $array_addon = explode(',', $request->size_addons[$i]);
            //                 $update = DB::table('foodlist_foodquantity')->where('foodlist_id', $request->id)->where('foodquantity_id', $food_quantity[$i]->id)->update([
            //                     'addons_id' => json_encode($j)
            //                 ]);
            //                 for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
            //                     $dump = json_decode($request->size_addons[$i]);
            //                     $ad = json_decode($dump[$k]);
            //                     $delete_old = DB::table('foodlist_foodquantity_addons')->where('food_list', $request->id)->where('food_quantity', $food_quantity[$i]->id)->where('addon', $ad->id)->delete();
            //                     if (!(isset($ad->stock))) {
            //                         $ad->stock = 0;
            //                     }
            //                     $update_add = DB::table('foodlist_foodquantity_addons')->insert([
            //                         'food_list' => $request->id,
            //                         'food_quantity' => $food_quantity[$i]->id,
            //                         'addon' => $ad->id,
            //                         'price' => $ad->price,
            //                         'stock' => $ad->stock
            //                     ]);
            //                 }
            //             }
            //         }
            //     } else {
            //         for ($i = 0; $i < $food_quantity_count; $i++) {
            //             if ($request->size_addons != null && $request->size_addons[$i] != null) {
            //                 $array_addon = explode(',', $request->size_addons[$i]);
            //                 $j = [];
            //                 for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
            //                     $dump = json_decode($request->size_addons[$i]);
            //                     $ad = json_decode($dump[$k]);
            //                     $j[] = strval($ad->id);
            //                 }
            //                 $array_addon = explode(',', $request->size_addons[$i]);
            //                 $update = DB::table('foodlist_foodquantity')->where('foodlist_id', $request->id)->where('foodquantity_id', $food_quantity[$i]->id)->update([
            //                     'addon_group' => json_encode($j)
            //                 ]);
            //                 for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
            //                     $dump = json_decode($request->size_addons[$i]);
            //                     $ad = json_decode($dump[$k]);
            //                     $delete_old = DB::table('foodlist_foodquantity_addons')->where('food_list', $request->id)->where('food_quantity', $food_quantity[$i]->id)->where('group_id', $ad->id)->delete();
            //                     for ($t = 0; $t < count($ad->group_addons); $t++) {
            //                         if (!(isset($ad->group_addons[$t]->stock))) {
            //                             $ad->group_addons[$t]->stock = 0;
            //                         }
            //                         $update_add = DB::table('foodlist_foodquantity_addons')->insert([
            //                             'food_list' => $request->id,
            //                             'food_quantity' => $food_quantity[$i]->id,
            //                             'group_id' => $ad->id,
            //                             'addon' => $ad->group_addons[$t]->id,
            //                             'price' => $ad->group_addons[$t]->price,
            //                             'stock' => $ad->group_addons[$t]->stock
            //                         ]);
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }
            // $delete_old_adds = DB::table('foodlist_addons')->where('foodlist_id', $request->id)->delete();
            // if ($request->addon_type == 1 && $request->groups != null) {
            //     $group_ar = explode(',', $request->groups);
            //     for ($i = 0; $i < count($group_ar); $i++) {
            //         $add = DB::table('foodlist_addons')->insert([
            //             'foodlist_id' => $request->id,
            //             'addon_group' => $group_ar[$i]
            //         ]);
            //     }
            // }
            // if ($request->addon_type == 0 && $request->add_ons != null) {
            //     $add_ar = explode(',', $request->add_ons);
            //     $add_ons = $this->add_ons->find($add_ar);
            //     foreach ($add_ons as $ad) {
            //         $add = DB::table('foodlist_addons')->insert([
            //             'foodlist_id' => $request->id,
            //             'addons_id' => $ad->id
            //         ]);
            //     }
            // }

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
            // $this->foodlist->restaurant_id = $restaurant_id;
            // $this->foodlist->category_id = $category;
            $this->foodlist->business_category_id = $business_category_id;
            $this->foodlist->menu_id = $menu;
            $this->foodlist->status = $status;
            $this->foodlist->out_of_stock = $stockstatus;
            // $this->foodlist->bprice = $bprice;
            // $this->foodlist->initial_price = $initial_price;
            // $this->foodlist->split_payment = $split_payment;
            // $this->foodlist->tax = $tax;
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
                        // $product_image_upload = $this->custom->common_upload_images_product($request, 'image', $restaurant_id);
                        $product_image_upload = $this->custom->common_upload_images_product($request, 'image');
                        $json = json_decode($product_image_upload, true);
                        $this->foodlist->image = $json['imagename'] ?  $json['imagename'] : $foodlist->image;
                        $this->foodlist->cloudflare_imageid = $json['id'];
                    }
                }
            }
            $this->foodlist->save();

            $foodId = $this->foodlist->id;
            $product_list = json_decode($request->product, true);
            foreach ($product_list as $group) {
                $districtIds = $group['district'];
                $price = $group['price'];
                $state = $group['state'];
                $tax = $group['tax'] ?? 0;
                $label = $group['label']; // from request

                // Generate simple random group_id (no state logic)
                $groupId = strtoupper(Str::random(10)); // e.g., 'A3D9F1X0TY'
                $food_pri = new FoodListPricing();
                $food_pri->product_id = $foodId;
                $food_pri->state_id = $state;
                $food_pri->price = $price;
                $food_pri->tax = $tax;
                $food_pri->label = $label;
                $food_pri->group_id = $groupId;
                $food_pri->save();


                // Attach districts via pivot table
                $food_pri->districts()->sync($districtIds);
            }

            $req_images = json_decode($request->image, true);
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


            // if ($request->food_quantity_id) {
            //     $food_quantity = $this->food_quantity->find($request->food_quantity_id);
            //     $food_quantity_count = $food_quantity ? count($food_quantity) : 0;
            //     $sync_data = array();
            //     $default = 0;
            //     for ($i = 0; $i < $food_quantity_count; $i++) {
            //         if ($i == 0) {
            //             $default = 1;
            //         } else {
            //             $default = 0;
            //         }
            //         if ($food_quantity_price[$i]) {
            //             $sync_data[$food_quantity[$i]->id] = ['price' => $food_quantity_price[$i], 'size_description' => $food_quantity_desc[$i], 'is_default' => $default];
            //         }
            //     }
            //     $this->foodlist->FoodQuantity()->attach($sync_data);
            //     if ($request->addon_type == 0) {
            //         for ($i = 0; $i < $food_quantity_count; $i++) {
            //             if ($request->size_addons[$i] != null) {
            //                 $j = [];
            //                 for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
            //                     $dump = json_decode($request->size_addons[$i]);
            //                     $ad = json_decode($dump[$k]);
            //                     $j[] = strval($ad->id);
            //                 }
            //                 $array_addon = explode(',', $request->size_addons[$i]);
            //                 $update = DB::table('foodlist_foodquantity')->where('foodlist_id', $this->foodlist->id)->where('foodquantity_id', $food_quantity[$i]->id)->update([
            //                     'addons_id' => json_encode($j)
            //                 ]);
            //                 for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
            //                     $dump = json_decode($request->size_addons[$i]);
            //                     $ad = json_decode($dump[$k]);
            //                     if (!(isset($ad->stock))) {
            //                         $ad->stock = 0;
            //                     }
            //                     $update_add = DB::table('foodlist_foodquantity_addons')->insert([
            //                         'food_list' => $this->foodlist->id,
            //                         'food_quantity' => $food_quantity[$i]->id,
            //                         'addon' => $ad->id,
            //                         'price' => $ad->price,
            //                         'stock' => $ad->stock
            //                     ]);
            //                 }
            //             }
            //         }
            //     } else {
            //         for ($i = 0; $i < $food_quantity_count; $i++) {
            //             if ($request->size_addons[$i] != null) {
            //                 $j = [];
            //                 for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
            //                     $dump = json_decode($request->size_addons[$i]);
            //                     $ad = json_decode($dump[$k]);
            //                     $j[] = strval($ad->id);
            //                 }
            //                 $array_addon = explode(',', $request->size_addons[$i]);
            //                 $update = DB::table('foodlist_foodquantity')->where('foodlist_id', $this->foodlist->id)->where('foodquantity_id', $food_quantity[$i]->id)->update([
            //                     'addon_group' => json_encode($j)
            //                 ]);
            //                 for ($k = 0; $k < count(json_decode($request->size_addons[$i])); $k++) {
            //                     $dump = json_decode($request->size_addons[$i]);
            //                     $ad = json_decode($dump[$k]);
            //                     for ($t = 0; $t < count($ad->group_addons); $t++) {
            //                         if (!(isset($ad->group_addons[$t]->stock))) {
            //                             $ad->group_addons[$t]->stock = 0;
            //                         }
            //                         $update_add = DB::table('foodlist_foodquantity_addons')->insert([
            //                             'food_list' => $this->foodlist->id,
            //                             'food_quantity' => $food_quantity[$i]->id,
            //                             'group_id' => $ad->id,
            //                             'addon' => $ad->group_addons[$t]->id,
            //                             'price' => $ad->group_addons[$t]->price,
            //                             'stock' => $ad->group_addons[$t]->stock
            //                         ]);
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }
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
            // if ($request->addon_type == 1 && $request->groups != null) {
            //     $group_ar = explode(',', $request->groups);
            //     for ($i = 0; $i < count($group_ar); $i++) {
            //         $add = DB::table('foodlist_addons')->insert([
            //             'foodlist_id' => $this->foodlist->id,
            //             'addon_group' => $group_ar[$i]
            //         ]);
            //     }
            // }
            // if ($request->addon_type == 0 && $request->add_ons != null) {
            //     $add_ar = explode(',', $request->add_ons);
            //     $add_ons = $this->add_ons->find($add_ar);
            //     $this->foodlist->Add_ons()->attach($add_ons);
            // }
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

        $food = $this->foodlist::with(['pricings.districts'])->findOrFail($request->product_id);

        $product_list->product = $food->pricings->map(function ($pricing) {
            return [
                'district' => $pricing->districts->pluck('id'), // List of district IDs
                'price' => $pricing->price,
                'state' => $pricing->state_id,
                'tax' => $pricing->tax,
                'label' => $pricing->label,
                'group_id' => $pricing->group_id,
            ];
        });

        // $product_list->image[] = File::exists(public_path('product_image/') . $product_list->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $product_list->image : (File::exists(public_path('restaurant_uploads/') . $product_list->restaurant_id . '/' . 'Product/' . $product_list->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $product_list->restaurant_id . '/' . 'Product/' . $product_list->image : (File::exists(public_path('common_images/') . $product_list->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $product_list->image : ''));
        // $cat_details = $this->category->where('id', $product_list->category_id)->first();
        // $product_list->cat_details = $cat_details;
        // $this_addon = DB::table('foodlist_addons')
        //     ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
        //     ->where('foodlist_id', $request->product_id)
        //     ->select('foodlist_addons.addons_id as id', 'add_ons.name',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at')
        //     ->get()->toArray();
        // if (!$this_addon) {
        //     $product_list->add_ons = [];
        // } else {
        //     foreach ($this_addon as $addon_key => $each_addon) {
        //         $this_addon[$addon_key]->restaurant_id = $product_list->restaurant_id;
        //         $this_addon[$addon_key]->pivot = ['foodlist_id' => $request->product_id, 'addons_id' => $each_addon->id];
        //     }
        // }
        // $product_list->add_ons = $this_addon;
        // $this_gaddon = DB::table('foodlist_addons')
        //     ->where('foodlist_id', $request->product_id)
        //     ->where('addons_id', null)
        //     ->get();
        // $add_group = [];
        // foreach ($this_gaddon as $addon_key => $each_addon) {
        //     if ($each_addon->addon_group != null) {
        //         $group = DB::table('addon_group')->where('id', $each_addon->addon_group)->first();
        //         $array_group = json_decode($group->add_ons);
        //         foreach ($array_group as $ag) {
        //             $addon_detail = DB::table('add_ons')->where('id', $ag)->first();
        //             $gadd_ons[] = [
        //                 'id' => $ag,
        //                 'name' => $addon_detail->name,
        //                 'secondary_name' => $addon_detail->secondary_name,
        //                 'price' => $addon_detail->price
        //             ];
        //         }
        //         $add_group[] = [
        //             'id' => $each_addon->addon_group,
        //             'name' => $group->name,
        //             'min' => $group->min,
        //             'max' => $group->max,
        //             'allow_multiple' => $group->allow_multiple,
        //             'mandatory' => $group->mandatory,
        //             'group_addons' => $gadd_ons
        //         ];
        //     }
        // }
        // $product_list->groups = $add_group;
        // $this_size = DB::table('foodlist_foodquantity')
        //     ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
        //     ->where('foodlist_id', $request->product_id)
        //     ->select(
        //         'foodlist_foodquantity.foodquantity_id as id',
        //         'food_quantity.name',
        //         'food_quantity.secondary_name',
        //         'foodlist_foodquantity.price',
        //         'foodlist_foodquantity.is_default',
        //         'foodlist_foodquantity.created_at',
        //         'foodlist_foodquantity.updated_at',
        //         'foodlist_foodquantity.addons_id',
        //         'foodlist_foodquantity.addon_group',
        //         'foodlist_foodquantity.size_description',
        //     )
        //     ->get()->toArray();
        // if (!$this_size) {
        //     $product_list->food_quantity = [];
        // } else {
        //     foreach ($this_size as $size_key => $each_size) {
        //         if ($each_size->addon_group != null) {
        //             $sg = $each_size->addon_group;
        //             $sg_group = json_decode($sg);
        //             $sgadd_ons = [];
        //             $sadd_group = [];
        //             foreach ($sg_group as $sgg) {
        //                 $sgg_group = DB::table('addon_group')->where('id', $sgg)->first();
        //                 $sarray_group = json_decode($sgg_group->add_ons);
        //                 foreach ($sarray_group as $sag) {
        //                     $saddon_detail = DB::table('add_ons')->where('id', $sag)->first();
        //                     $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $request->product_id)->where('food_quantity', $each_size->id)->where('group_id', $sgg)->where('addon', $sag)->first();
        //                     if ($variant) {
        //                         $price = $variant->price;
        //                         $stock = $variant->stock;
        //                     } else {
        //                         $price = $saddon_detail->price;
        //                         $stock = 0;
        //                     }
        //                     $sgadd_ons[] = [
        //                         'id' => $sag,
        //                         'name' => $saddon_detail->name,
        //                         'secondary_name' => $saddon_detail->secondary_name,
        //                         'price' => $price,
        //                         'stock' => $stock
        //                     ];
        //                 }
        //                 $sadd_group[] = [
        //                     'id' => $sgg,
        //                     'name' => $sgg_group->name,
        //                     'min' => $sgg_group->min,
        //                     'max' => $sgg_group->max,
        //                     'allow_multiple' => $sgg_group->allow_multiple,
        //                     'mandatory' => $sgg_group->mandatory,
        //                     'group_addons' => $sgadd_ons
        //                 ];
        //             }
        //             $this_size[$size_key]->groups = $sadd_group;
        //         }
        //         if ($each_size->addons_id != null) {
        //             $sa = $each_size->addons_id;
        //             $sa_group = json_decode($sa);
        //             $saa_addons = [];
        //             foreach ($sa_group as $saa) {
        //                 $sa_detail = DB::table('add_ons')->where('id', $saa)->first();
        //                 $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $request->product_id)->where('food_quantity', $each_size->id)->where('addon', (int)$saa)->first();
        //                 if ($variant) {
        //                     $price = $variant->price;
        //                     $stock = $variant->stock;
        //                 } else {
        //                     $price = $sa_detail->price;
        //                     $stock = 0;
        //                 }
        //                 $saa_addons[] = [
        //                     'id' => $saa,
        //                     'name' => $sa_detail->name,
        //                     'secondary_name' => $sa_detail->secondary_name,
        //                     'price' => $price,
        //                     'stock' => $stock
        //                 ];
        //             }
        //             $this_size[$size_key]->add_ons = $saa_addons;
        //         }
        //         $this_size[$size_key]->pivot = [
        //             'foodlist_id' => $request->product_id,
        //             'foodquantity_id' => $each_size->id,
        //             'price' => $each_size->price,
        //             'is_default' => $each_size->is_default
        //         ];
        //     }
        // }

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

        // $product_list->food_quantity = $this_size;
        // $category = $this->category->get();
        // $restaurant = array();
        // $menu = $this->menu->where('restaurant_id', $request->res_id)->get();
        // $add_ons = $this->add_ons->where('restaurant_id', $request->res_id)->get();
        // $food_quantity = $this->food_quantity->get();
        // $addon_ids = $foodquantity_ids = array();
        // $restaurant_detail = DB::table('restaurants')->where('id', $request->res_id)->first();
        $status = true;
        // $response_Array = json_encode(['product_list' => $product_list, 'category' => $category, 'menu' => $menu, 'add_ons' => $add_ons, 'addon_ids' => $addon_ids, 'restaurant' => $restaurant, 'food_quantity' => $food_quantity, 'foodquantity_ids' => $foodquantity_ids, 'status' => $status, 'specs' => $specs, 'faq' => $faq, 'table' => $table]);
        $response_Array = json_encode(['product_list' => $product_list, 'status' => $status, 'specs' => $specs, 'faq' => $faq, 'table' => $table]);
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
}
