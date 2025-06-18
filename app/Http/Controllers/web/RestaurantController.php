<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\api\BaseController;
use App\Model\City_geofencing;
use App\Model\Deliverypartners;
use App\Model\DriverList;
use App\Http\Controllers\Mailer;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Validator;
use App\Model\DriverDocument;
use App\helpers;
use App\Model\Requestdetail;
use App\Model\RequestdetailAddons;
use App\Http\Controllers\Restaurant;
use App\Http\Controllers\Coupon;
use Cartalyst\Stripe\Stripe;
use File;
use DeDmytro\CloudflareImages\Facades\CloudflareApi;

class RestaurantController extends BaseController
{

    public function restaurant_list(Request $request)
    {

        $data = DB::table('restaurants')

            ->orderBy('id', 'desc')->get();
        // $admin = checkAdmin();
        // dd($request);


        // $datas = $this->restaurants->with('Foodrequest')->orderby('id', 'desc');
        // if ($admin) {
        //     $datas->WhereIN('id', $admin);
        // }
        // $data = $datas->get();
        return response()->json($data);
    }

    public function brands_list(Request $request)
    {
        $data = DB::table('brands')

            ->orderBy('id', 'desc')->get();

        return response()->json($data);
    }

    public function vehicle_list()
    {
        $vehicle_list = $this->vehicle->get();

        return response()->json($vehicle_list);
    }
    public function document_list()
    {
        $document_list = $this->document->get();

        return response()->json($document_list);
    }
    public function edit_document(Request $request)
    {
        $document_list = $this->document->find($request->id);


        $message = $document_list;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function reason_list()
    {
        $reason_list = $this->cancellation_reason->get();

        return response()->json($reason_list);
    }

    public function admin_restaurant_report(Request $request)
    {

        $restaurant_details = $this->restaurants
            ->join('add_city', 'add_city.id', '=', 'restaurants.city')
            ->join('add_area', 'add_area.id', '=', 'restaurants.area')
            ->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
            ->get();



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
        // $restaurant_id = $request->session()->get('userid');
        // if ($restaurant_id != null) {

        //     $restaurant_details = DB::table('requests')
        //         ->where('requests.restaurant_id', $restaurant_id)
        //         ->join('users', 'users.id', '=', 'requests.user_id')
        //         ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
        //         ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*')
        //         ->get();
        // } else {

        $restaurant_details = DB::table('requests')
            ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->join('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
            ->select('users.name as customer_name', 'users.phone as phone', 'delivery_partners.name as delivery_boy_name', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.*')
            ->get();
        // }



        return response()->json($restaurant_details);
    }
    public function area_list(Request $request)
    {
        $area_list = DB::table('add_area')->get();

        return response()->json($area_list);
    }

    public function city_list()
    {
        $city_list = DB::table('add_city')->where('status', 1)->get();


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


        if ($request->file('profile_pic')) {

            $profile_picture = $custom->driver_image_upload($request, 'profile_pic');
        }
        $mail_flag = 0;

        if ($request->id) {
            $insert1 = Deliverypartners::find($request->id);
            if ($insert1->status != $request->status) {
                $mail_flag = 1;
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
        $insert1->name = $request->driver_name;
        $insert1->restaurant_id = $request->restaurant;
        $insert1->email = $request->email;
        $insert1->phone = $request->phone_no;



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
        if ($request->license != null) {
            $licenses = $request->license;
            $exp_date = $request->license_expiry;
            // dd($request->exp_date);

            $document_id = $request->doc_id;
            $expdate_id = $request->exp_id;
            for ($i = 0; $i < count($licenses); $i++) {
                $dri_doc = new DriverDocument();
                $license = $custom->base_image_upload_license($licenses[$i]); //dd($license);
                $dri_doc->driver_id = $request->id;
                $dri_doc->document_id = $document_id[$i];
                $dri_doc->document = $license;
                //dd($dri_doc);
                $dri_doc->save();
                for ($e = 0; $e < count($exp_date); $e++) {
                    if ($document_id[$i] ==  $expdate_id[$e]) {
                        //dd($exp_date[$e]);
                        $doc = DB::table('driver_document')->where('driver_id', $request->id)->where('document_id', $document_id[$i])
                            ->update(['expiry_date' => $exp_date[$e]]);
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
        if ($request->did) {
            $insert1 = Deliverypartners::find($request->did);
            $insert = DriverList::find($insert1->id);
        } else {
            $insert = new DriverList();
        }
        // dd($insert);
        $insert->account_name = $request->account_name;
        $insert->account_address = $request->account_address;
        $insert->account_no = $request->account_no;
        $insert->bank_name = $request->bank_name;
        $insert->branch_name = $request->branch_name;
        $insert->branch_address = $request->branch_address;
        $insert->swift_code = $request->swift_code;
        $insert->routing_no = $request->routing_no;
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
        // dd($request->id );
        $vehicle_name = $request->vehicle_name;
        $vehicle_no = $request->vehicle_no;
        $insurance_no = $request->insurance_no;
        $insurance_expiry_date = date('Y-m-d', strtotime($request->insurance_expiry_date));
        $rc_no = $request->rc_no;
        $rc_expiry_date = date('Y-m-d', strtotime($request->rc_expiry_date));
        $path = public_path('vehicles/');

        $status = $request->status;
        if ($request->id != '') {
            $add_vehicle = $this->vehicle->find($request->id);
        } else {
            $add_vehicle = new $this->vehicle;
        }
        $add_vehicle->vehicle_name = $vehicle_name;
        $add_vehicle->vehicle_no = $vehicle_no;
        $add_vehicle->insurance_no = $insurance_no;
        $add_vehicle->insurance_expiry_date = date('Y-m-d', strtotime($request->insurance_expiry_date));
        $add_vehicle->rc_no = $request->rc_no;
        $add_vehicle->rc_expiry_date = date('Y-m-d', strtotime($request->rc_expiry_date));
        $add_vehicle->status = $status;
        if ($request->insurance_image != '' || $request->insurance_image != null) {
            $add_vehicle->insurance_image = $this->custom->common_upload_images($request, 'insurance_image', $path);
        }
        if ($request->rc_image != '' || $request->rc_image != null) {
            $add_vehicle->rc_image = $this->custom->common_upload_images($request, 'rc_image', $path);
        }
        if ($request->vehicle_image != '' || $request->vehicle_image != null) {
            $add_vehicle->vehicle_image = $this->custom->common_upload_images($request, 'vehicle_image', $path);
        }
        $add_vehicle->save();

        $message = "Vehicle Added Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function store_brand(request $request)
    {

        $name = $request->input('name');

        if ($request->id) {
            DB::table('brands')->where('id', $request->input('id'))->update(['name' => $request->input('name'), 'status' => $request->input('status')]);


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
                    'name' => $request->input('name'),
                    'status' => $request->input('status'),
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
            $reason = $request->reason;
            $cancellation_for = $request->cancellation_for;
            $status = $request->status;
            // $add_reason = $this->cancellation_reason;

            $data = [];

            $data[] = [
                'reason' => $reason,
                'cancellation_for' => $cancellation_for,
                'status' => $status,

            ];
            if ($request->id != '') {
                $add_reason = $this->cancellation_reason->find($request->id);
            } else {
                $add_reason = $this->cancellation_reason;
            }

            $add_reason->reason = $reason;
            $add_reason->cancellation_for = $cancellation_for;
            $add_reason->status = $status;


            $add_reason->save();
        }
        $message = "Cancellation Reason Added Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }

    public function edit_reason(Request $request)
    {
        $add_reason = $this->cancellation_reason->find($request->id);

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
        ]);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());

            $message = $error_messages;
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        } else {

            $document_for = $request->document_for;
            $document_name = $request->document_name;
            $expiry_date_needed = $request->expiry_date_needed;

            $status = $request->status;
            $data = [];
            $data[] = [
                'document_for' => $document_for,
                'document_name' => $document_name,
                'expiry_date_needed' => $expiry_date_needed,
                'status' => $status,
            ];
            // dd($request->id);
            if ($request->id) {
                $add_document = $this->document->find($request->id);
            } else {
                $add_document = new $this->document;
            }



            $add_document->document_for = $document_for;
            $add_document->document_name = $document_name;
            $add_document->expiry_date_needed = $expiry_date_needed;

            $add_document->status = $status;


            $add_document->save();
        }

        $message = "Docoument Added Successfully";
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }
    public function edit_brand($brand_id)
    {
        $data = DB::table('brands')->where('id', $brand_id)->first();

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
        $data = $this->vehicle->find($id);

        $message = $data;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status]);


        return $response_Array;
    }
    public function document_view(Request $request)
    {
        $document_list = DB::table('document')->where('status', 1)->get();
        return response()->json($document_list);
    }
    public function add_to_restaurants_tab1(Request $request)
    {


        $id = $request->id;

        if (!empty($id)) {
            $restaurants = $this->restaurants;

            $restaurant_update = $restaurants::findOrFail($id);
            $custom = $this->custom;

            $name = $request->name;
            $restaurant_secondary_name = $request->restaurant_secondary_name ?? '';
            $email = $request->email;
            $password = $request->password;
            $phone = $request->phone;
            $city = $request->city;
            $address = $request->address;
            $area = $request->area;
            $brand_name = $request->brands;
            $GeofenceRadius = $request->GeofenceRadius;
            $description = $request->shop_description;
            $packaging_charge = $request->packaging_charge;
            $estimated_delivery_time = $request->estimated_delivery_time;
            $status = $request->status;
            if ($request->banner) {
                $banner = $custom->restaurant_upload_image_banner($request, 'banner', $request->id);
                $restaurant_update->banner = $banner;
            }
            $image = $request->image;
            if ($request->image) {
                $image = $custom->restaurant_upload_image_logo($request, 'image', $request->id);
                $restaurant_update->image = $image;
            }

            $admin_commision = $request->admin_commision;
            $delivery_charge_type = $request->delivery_charge_type ?? 1;
            //$status = (int) ($request->status ?? 0);
            $delivery_type = json_encode($request->delivery_type); //dd("f0");
            $video_title = json_encode($request->video_title);
            $video_url = json_encode($request->video_url);

            $restaurant_update->restaurant_name = $name;
            $restaurant_update->email = $email;
            $restaurant_update->restaurant_secondary_name = $restaurant_secondary_name;
            $restaurant_update->org_password = $password;

            $restaurant_update->password = Hash::make($password);
            $restaurant_update->phone = $phone;
            $restaurant_update->brand_name = $brand_name;
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
            $restaurant_update->driver_commision = $request->driver_commision;

            $restaurant_update->estimated_delivery_time = $estimated_delivery_time;
            $restaurant_update->delivery_type = $delivery_type;
            $restaurant_update->video_title = $video_title;
            $restaurant_update->video_url = $video_url;
            if ($request->deliveryChargeBasedOn) {
                $restaurant_update->DeliveryChargeBasedOn = $request->deliveryChargeBasedOn;
            }

            //mail for active deactive restarant
            $mfrom = DB::table('settings')->where('key_word', 'mail_from_name')->first();
            // if ($mfrom != null) {
            //     // if (!empty(env('MAIL_FROM_NAME'))) {
            //     if ($restaurant_update->status != $status) {
            //         $restaurant_mail = $restaurants->where('email', $request->email)->first();
            //         $html = view('emails.restaurant.restaurant-welcome')->with(['restaurant' => $restaurant_mail]);

            //         $Mailer = new Mailer();
            //         $Mailer->to = $restaurant_mail->email;
            //         $Mailer->toName = $restaurant_mail->restaurant_name;
            //         $Mailer->subject = 'Welcome';
            //         $Mailer->body = $html;
            //         $Mailer->send();
            //     }
            // }
            $restaurant_update->status = $status;

            $restaurant_update->update();
            $cuisines = $this->cuisines->find($request->cuisines); //dd($request->cuisines);

            $restaurant_update->Cuisines()->sync($cuisines);

            //delivery charge loop
            $delivery_charge_minimum = $request->delivery_charge_minimum;
            $delivery_charge_maximum = $request->delivery_charge_maximum;
            $delivery_charge_value = $request->delivery_charge_value;
            $delivery_charge_type = $request->delivery_charge_type;
            $delivery_charge_limit = $request->delivery_charge_minimum ? count($request->delivery_charge_minimum) : 0;

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

                DB::table('delivery_charge_limits')->insert([
                    'restaurant_id' => $request->id,
                    'Minimum' => $delivery_charge_minimum[$i],
                    'Maximum' => $delivery_charge_maximum[$i],
                    'Value' => $delivery_charge_value[$i],
                    'Type' => $delivery_charge_type[$i],
                ]);
            }
            $tab = 'tab2';
            // session()->put('tab', 'tab2');
            // session()->put('elmnt', 'this');
            // session()->put('color', '#409a40e8');
            $message = "Restaurant Details Updated";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $request->id]);
            return $response_Array;
        } else {
            $restaurants = $this->restaurants;

            $custom = $this->custom;

            $name = $request->name;
            $restaurant_secondary_name = $request->restaurant_secondary_name ?? '';
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


            $admin_commision = $request->admin_commision;
            $delivery_charge_type = $request->delivery_charge_type ?? 1;
            $delivery_type = json_encode($request->delivery_type);
            $video_title = json_encode($request->video_title);
            $video_url = json_encode($request->video_url);

            $restaurants->restaurant_name = $name;
            $restaurants->email = $email;
            $restaurants->restaurant_secondary_name = $restaurant_secondary_name;
            $restaurants->org_password = $password;

            $restaurants->password = Hash::make($password);

            $restaurants->brand_name = $brand_name;
            $restaurants->phone = $phone;
            $restaurants->contact = $request->contact;
            $restaurants->city = $city;
            $restaurants->address = $address;
            $restaurants->lat = $request->latitude;
            $restaurants->lng = $request->longitude;
            $restaurants->shop_description = $description;
            $restaurants->area = $area;
            $restaurants->GeofenceRadius = $GeofenceRadius;
            $restaurants->packaging_charge = $packaging_charge;
            $restaurants->admin_commision = $admin_commision;
            $restaurants->driver_commision = $request->driver_commision;
            $restaurants->estimated_delivery_time = $estimated_delivery_time;
            $restaurants->delivery_type = $delivery_type;
            $restaurants->video_title = $video_title;
            $restaurants->video_url = $video_url;
            if ($request->deliveryChargeBasedOn) {
                $restaurants->DeliveryChargeBasedOn = $request->deliveryChargeBasedOn;
            }

            $restaurants->save();

            $cuisines = $this->cuisines->find($request->cuisines);
            $restaurants->Cuisines()->sync($cuisines);

            $tab = 'tab2';
            // session()->put('tab', 'tab2');
            // session()->put('elmnt', 'this');
            // session()->put('color', '#409a40e8');
            $res_id = DB::table('restaurants')->orderBy('id', 'desc')->first();
            $restaurant_id = $res_id->id;

            $restaurant_update = $restaurants::findOrFail($restaurant_id);
            if ($request->banner) {
                $banner = $custom->restaurant_upload_image_banner($request, 'banner', $restaurant_id);
                $restaurant_update->banner = $banner;
            }
            $image = $request->image;
            if ($request->image) {
                $image = $custom->restaurant_upload_image_logo($request, 'image', $restaurant_id);
                $restaurant_update->image = $image;
            }
            $restaurant_update->update();


            $message = "Restaurant Details Updated";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status, 'id' => $restaurant_id]);
            return $response_Array;
        }
    }

    public function add_to_restaurants_tab2(Request $request)
    {
        $id = $request->id;
        dd($id);
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
                $$opening_time = "00:00:00";
                $$closing_time = "00:00:00";
            }
            if (!empty($request->{'opening_second_time_' . $i}) && !empty($request->{'closing_second_time_' . $i})) {
                $$opening_second_time = date("H:i:s", strtotime($request->{'opening_second_time_' . $i}));
                $$closing_second_time = date("H:i:s", strtotime($request->{'closing_second_time_' . $i}));
            } else {
                $$opening_second_time = '';
                $$closing_second_time = '';
            }
        }


        $holidays = is_array($request->holidays) ? implode(',', $request->holidays) : '';

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
        session()->put('tab', 'tab3');
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
    public function area_list1(Request $request)
    {

        $area_list = DB::table('add_area')->where('add_city_id', $request->city)->get();
        //  dd( $res_list);
        return response()->json($area_list);
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

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->user_id;
        }
        $user_details = DB::table('users')->where('id', $user_id)->first();

        $rating_statistics = get_rating_statistics($restaurant_id);

        $restaurant_cuisines = DB::table('restaurant_cuisines')
            ->join('cuisines', 'cuisines.id', '=', 'restaurant_cuisines.cuisine_id')
            ->join('restaurants', 'restaurants.id', '=', 'restaurant_cuisines.restaurant_id')
            ->select('restaurant_cuisines.restaurant_id as restaurant_id', 'cuisines.name as cuisine_name', 'cuisines.secondary_name as secondary_name', 'restaurants.restaurant_name as restaurant_name')
            ->get();

        $data = $restaurants::where('id', $restaurant_id)->first();
        if ($data !== null && $data->address != "" && $data->address != null) {
            $decodedText = html_entity_decode($data->address);
            $data->address = $decodedText;
        }
        $promocode_list_restaurant = [];
        $dt = Carbon::now();
        $promocode_list_restaurant = $this->promocode->where('restaurant_id', $restaurant_id)->orWhere('restaurant_id', null)->where('status', '1')->whereRaw('"' . $dt . '" between `available_from` and `valid_till`')->get();
        // $promocode_list_restaurant = ['promocode_list_restaurant'=>$promocode_list_restaurant];

        $food_filter = $foodlist::where('restaurant_id', $restaurant_id)->where('status', 1)->get();

        $restaurant_list = array();
        $rcuisines = array();
        $i = 0;

        foreach ($restaurant_cuisines as $r_cuisines) {
            if ($data->restaurant_name == $r_cuisines->restaurant_name && $i < 2) { // To display only two cuisines
                $rcuisines[] = array(
                    'name' => $this->secondLanguage_user($r_cuisines->cuisine_name, $r_cuisines->secondary_name),
                );

                $i = $i + 1;
            }
        }

        $foods = array();
        $j = 0;

        foreach ($food_filter as $f) {
            $cart_count = $cart::where('user_id', $user_id)->where('food_id', $f->id)->first();  // For Cart item quantity

            if ($cart_count) {
                $count = $cart_count->quantity;
            } else {
                $count = 0;
            }

            if ($j < 4) {
                if ($veg_only == 0) {
                    $foods[] = array(
                        'food_id'       => $f->id,
                        'name'          => $f->name,
                        'price'         => $f->price,
                        'description'   => $f->description,
                        'is_veg'        => $f->is_veg,
                        'item_count'    => $count,
                    );
                    $j = $j + 1;
                } else {
                    if ($f->is_veg == 1) {
                        $foods[] = array(
                            'food_id'       => $f->id,
                            'name'          => $f->name,
                            'price'         => $f->price,
                            'description'   => $f->description,
                            'is_veg'        => $f->is_veg,
                            'item_count'    => $count,
                        );
                        $j = $j + 1;
                    }
                }
            }
        }

        $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $data->id)->get();
        if (count($check_favourite) != 0) {
            $is_favourite = 1;
        } else {
            $is_favourite = 0;
        }
        $is_open = restaurant_is_open($data->id);
        $video = json_decode($data->video_url);
        $title = json_decode($data->video_title);
        $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
        if ($cloudflare->value == 1) {
            $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
            $url = $get_url->value;

            $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
            $acc_hash = $get_acchash->value;
        }
        $delivery_charge_limits = DB::table('delivery_charge_limits')->select('Minimum', 'Maximum', 'Value', 'Type')
            ->where('restaurant_id', $data->id)->get()->toArray();
        $tax1 = DB::table('settings')->where('key_word', 'tax_inclusive')->first();
        $tax = $tax1->value;


        if ($cloudflare->value == 1 && $data->cloudflare_imageid == null && $data->image != '' && $data->image != 'undefined') {


            if (File::exists(public_path('restaurant_uploads/') . $data->image)) {
                $image = RESTAURANT_UPLOADS_PATH . $data->image;
                $response = CloudflareApi::images()->upload(public_path($image));
                $cimage = $response->result;
                $image_id = $cimage->id;

                DB::table('restaurants')->where('id', $data->id)->update(['cloudflare_imageid' => $image_id]);
            } elseif (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Logo/' . $data->image)) {
                $image = RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Logo/' . $data->image;
                $response = CloudflareApi::images()->upload(public_path($image));
                $cimage = $response->result;
                $image_id = $cimage->id;

                DB::table('restaurants')->where('id', $data->id)->update(['cloudflare_imageid' => $image_id]);
            }
        }

        if ($cloudflare->value == 1 && $data->cloudflare_bannerid == null  && $data->banner != '' && $data->banner != 'undefined') {

            if (File::exists(public_path('restaurant_uploads/') . $data->banner)) {
                $banner = RESTAURANT_UPLOADS_PATH . $data->banner;
                $response = CloudflareApi::images()->upload(public_path($banner));

                $bimage = $response->result;
                $image_id = $bimage->id;

                DB::table('restaurants')->where('id', $data->id)->update(['cloudflare_bannerid' => $image_id]);
            } elseif (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Banner/' . $data->banner)) {
                $banner =  RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Banner/' . $data->banner;
                $response = CloudflareApi::images()->upload(public_path($banner));

                $bimage = $response->result;
                $image_id = $bimage->id;

                DB::table('restaurants')->where('id', $data->id)->update(['cloudflare_bannerid' => $image_id]);
            }


            // $banner =File::exists(public_path('restaurant_uploads/') . $data->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->banner : (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Banner/' . $data->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Banner/' . $data->banner : BASE_URL . UPLOADS_PATH_BANNER . $data->banner);


        }

        if ($cloudflare->value == 1 && $data->cloudflare_imageid != null && $data->image != '') {

            $fimage = $url . '/' . $acc_hash . '/' . $data->cloudflare_imageid . '/' . "w=300";
        } else {

            $fimage = File::exists(public_path('restaurant_uploads/') . $data->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->image : (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Logo/' . $data->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Logo/' . $data->image : BASE_URL . UPLOADS_PATH_LOGO . $data->image);
        }
        if ($cloudflare->value == 1 && $data->cloudflare_bannerid != null && $data->banner != '') {

            $fbanner = $url . '/' . $acc_hash . '/' . $data->cloudflare_bannerid . '/' . "w=1800";
        } else {
            $fbanner = File::exists(public_path('restaurant_uploads/') . $data->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->banner : (File::exists(public_path('restaurant_uploads/') . $data->id . '/' . 'Banner/' . $data->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data->id . '/' . 'Banner/' . $data->banner : BASE_URL . UPLOADS_PATH_BANNER . $data->banner);
        }

        if ($request->date && $request->time) {
            $isOpen_schedule = Restaurant::isOpenAt($data->id, $scheduleAt);

            $restaurant_list[] = array(
                'id'            => $data->id,
                'name' =>  $this->secondLanguage_user($data->restaurant_name, $data->restaurant_secondary_name),
                'phone' => $data->phone,
                'email' => $data->email,
                'delivery_type'          => json_decode($data->delivery_type),
                'image' => $fimage,
                'banner' => $fbanner,

                // 'image'         => BASE_URL . RESTAURANT_UPLOADS_PATH . $data->image,
                // 'banner'         => BASE_URL . RESTAURANT_UPLOADS_PATH . $data->banner,
                'address'       => $data->address,
                'lat'     => $data->lat,
                'lng'     => $data->lng,
                'video_title'     => $title,
                'video_url'     => $video,
                'discount'      => $data->discount,
                'rating'        => (float) $rating_statistics['rating_percent'],
                //'is_open'       => $data->is_open,     // 1- Open , 0 - Close
                'is_open'       => $isOpen_schedule,
                'cuisines'      => $rcuisines,
                'travel_time'   => $data->estimated_delivery_time,
                'price'         => '',
                'is_favourite'  => $is_favourite,
                'food_list'     => $foods,
                'opening_time_1' => $data->opening_time_1,
                'closing_time_1' => $data->closing_time_1,
                'opening_second_time_1' => $data->opening_second_time_1,
                'closing_second_time_1' => $data->closing_second_time_1,
                'opening_time_2' => $data->opening_time_2,
                'closing_time_2' => $data->closing_time_2,
                'opening_second_time_2' => $data->opening_second_time_2,
                'closing_second_time_2' => $data->closing_second_time_2,
                'opening_time_3' => $data->opening_time_3,
                'closing_time_3' => $data->closing_time_3,
                'opening_second_time_3' => $data->opening_second_time_3,
                'closing_second_time_3' => $data->closing_second_time_3,
                'opening_time_4' => $data->opening_time_4,
                'closing_time_4' => $data->closing_time_4,
                'opening_second_time_4' => $data->opening_second_time_4,
                'closing_second_time_4' => $data->closing_second_time_4,
                'opening_time_5' => $data->opening_time_5,
                'closing_time_5' => $data->closing_time_5,
                'opening_second_time_5' => $data->opening_second_time_5,
                'closing_second_time_5' => $data->closing_second_time_5,
                'opening_time_6' => $data->opening_time_6,
                'closing_time_6' => $data->closing_time_6,
                'opening_second_time_6' => $data->opening_second_time_6,
                'closing_second_time_6' => $data->closing_second_time_6,
                'opening_time_7' => $data->opening_time_7,
                'closing_time_7' => $data->closing_time_7,
                'opening_second_time_7' => $data->opening_second_time_7,
                'closing_second_time_7' => $data->closing_second_time_7,
                'delivery_charge_limits' => $delivery_charge_limits,
                'packaging_charge' => $data->packaging_charge,
                'DeliveryChargeBasedOn' => $data->DeliveryChargeBasedOn,
                'delivery_charge_type' => $data->delivery_charge_type ? $data->delivery_charge_type : 1,
                'tax' => $tax,


            );
        } else {
            $restaurant_list[] = array(
                'id'            => $data->id,
                'name' =>  $this->secondLanguage_user($data->restaurant_name, $data->restaurant_secondary_name),
                'phone' => $data->phone,
                'email' => $data->email,
                'delivery_type'          => json_decode($data->delivery_type),
                'image' => $fimage,
                'banner' => $fbanner,
                // 'image'         => BASE_URL . RESTAURANT_UPLOADS_PATH . $data->image,
                // 'banner'         => BASE_URL . RESTAURANT_UPLOADS_PATH . $data->banner,
                'address'       => $data->address,
                'lat'     => $data->lat,
                'lng'     => $data->lng,
                'video_title'     => $title,
                'video_url'     => $video,
                'discount'      => $data->discount,
                'rating'        => (float) $rating_statistics['rating_percent'],
                //'is_open'       => $data->is_open,     // 1- Open , 0 - Close
                'is_open'       => $is_open,
                'cuisines'      => $rcuisines,
                'travel_time'   => $data->estimated_delivery_time,
                'price'         => '',
                'is_favourite'  => $is_favourite,
                'food_list'     => $foods,
                'opening_time_1' => $data->opening_time_1,
                'closing_time_1' => $data->closing_time_1,
                'opening_second_time_1' => $data->opening_second_time_1,
                'closing_second_time_1' => $data->closing_second_time_1,
                'opening_time_2' => $data->opening_time_2,
                'closing_time_2' => $data->closing_time_2,
                'opening_second_time_2' => $data->opening_second_time_2,
                'closing_second_time_2' => $data->closing_second_time_2,
                'opening_time_3' => $data->opening_time_3,
                'closing_time_3' => $data->closing_time_3,
                'opening_second_time_3' => $data->opening_second_time_3,
                'closing_second_time_3' => $data->closing_second_time_3,
                'opening_time_4' => $data->opening_time_4,
                'closing_time_4' => $data->closing_time_4,
                'opening_second_time_4' => $data->opening_second_time_4,
                'closing_second_time_4' => $data->closing_second_time_4,
                'opening_time_5' => $data->opening_time_5,
                'closing_time_5' => $data->closing_time_5,
                'opening_second_time_5' => $data->opening_second_time_5,
                'closing_second_time_5' => $data->closing_second_time_5,
                'opening_time_6' => $data->opening_time_6,
                'closing_time_6' => $data->closing_time_6,
                'opening_second_time_6' => $data->opening_second_time_6,
                'closing_second_time_6' => $data->closing_second_time_6,
                'opening_time_7' => $data->opening_time_7,
                'closing_time_7' => $data->closing_time_7,
                'opening_second_time_7' => $data->opening_second_time_7,
                'closing_second_time_7' => $data->closing_second_time_7,
                'delivery_charge_limits' => json_encode($delivery_charge_limits),
                'packaging_charge' => $data->packaging_charge,
                'DeliveryChargeBasedOn' => $data->DeliveryChargeBasedOn,
                'offer_discount' => $data->offer_discount,
                'delivery_charge_type' => $data->delivery_charge_type ? $data->delivery_charge_type : 1,
                'tax' => $tax,


            );
        }

        $food_cart = array();

        $check_for_cart = $cart::where('user_id', $user_id)->get();
        $amount = 0;
        $quantity = 0;
        foreach ($check_for_cart as $key) {
            $food_detail = $foodlist::where('id', $key->food_id)->first();
            $amount = $amount + ($food_detail->price * $key->quantity);
            $quantity = $quantity + $key->quantity;
        }

        $food_cart[] = array(
            'amount'     => $amount,
            'quantity'   => $quantity,
        );
        $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'cart' => $food_cart, 'promocodes' => $promocode_list_restaurant, 'user' => $user_details);

        $response = response()->json($response_array, 200);
        return $response;
    }
    public function top_selling(Request $request)
    {


        $restaurant_id = $request->restaurant_id;

        $is_veg = $request->is_veg || 0;
        $restaurants = $this->restaurants;


        $featured = DB::table('food_list')->where('restaurant_id', $restaurant_id)->where('featured', 1)->count();
        if ($featured <= 5) {
            $food_list = DB::table('request_detail')->where('request_detail.restaurant_id', $restaurant_id)->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
                ->where('food_list.status', 1)
                ->join(
                    'menu',
                    function ($join) {
                        $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                        $join->on('menu.id', '=', 'food_list.menu_id');
                    }
                )
                ->select('food_id', DB::raw('count(*) as foodcount'), 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'secondary_menu_name', 'menu.id as menu_id', 'food_list.image as image')
                ->groupBy('food_id')->orderbyDesc('foodcount')
                ->get();
            $title = "Top Selling";
        } else {
            $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                ->where('food_list.status', 1)->where('food_list.featured', 1)
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
            $title = "Featured Products";
        }

        foreach ($food_list as $key => $each_food) {
            if ($each_food->image != "") {
                $food_list[$key]->image = File::exists(public_path('product_image/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $each_food->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $each_food->image : BASE_URL  . $each_food->image));
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

            $this_gaddon = DB::table('foodlist_addons')
                ->where('foodlist_id', $each_food->food_id)
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
                            'name' => $this->secondLanguage_user($addon_detail->name, $addon_detail->secondary_name),
                            'secondary_name' => $addon_detail->secondary_name,
                            'price' => $addon_detail->price

                        ];
                    }
                    $add_group[] = [
                        'id' => $each_addon->addon_group,
                        'name' => $this->secondLanguage_user($group->name, $group->secondary_name),
                        'min' => $group->min,
                        'max' => $group->max,
                        'allow_multiple' => $group->allow_multiple,
                        'mandatory' => $group->mandatory,
                        'group_addons' => $gadd_ons

                    ];
                }
            }
            $food_list[$key]->groups = $add_group;
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
                    'foodlist_foodquantity.updated_at',
                    'foodlist_foodquantity.addons_id',
                    'foodlist_foodquantity.addon_group',
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }

            foreach ($this_size as $size_key => $each_size) {

                if ($each_size->name) {
                    $each_size->name = $this->secondLanguage_user($each_size->name, $each_size->secondary_name);
                }

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


                            $sgadd_ons[] = [
                                'id' => $sag,
                                'name' => $this->secondLanguage_user($saddon_detail->name, $saddon_detail->secondary_name),
                                'secondary_name' => $saddon_detail->secondary_name,
                                'price' => $saddon_detail->price

                            ];
                        }
                        $sadd_group[] = [
                            'id' => $sgg,
                            'name' => $this->secondLanguage_user($sgg_group->name, $sgg_group->secondary_name),
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

                        $saa_addons[] = [
                            'id' => $saa,
                            'name' => $this->secondLanguage_user($sa_detail->name, $sa_detail->secondary_name),
                            'secondary_name' => $sa_detail->secondary_name,
                            'price' => $sa_detail->price
                        ];
                    }
                    $this_size[$size_key]->add_ons = $saa_addons;
                }
                $this_size[$size_key]->pivot = [
                    'foodlist_id' => $each_food->food_id,
                    'foodquantity_id' => $each_size->id,
                    'price' => $each_size->price,
                    'is_default' => $each_size->is_default
                ];
            }

            $food_list[$key]->food_quantity = $this_size;
        }
        foreach ($food_list as $key => $each_food) {
            $available = 0;
            $menutime = DB::table('menu_timing')->where('menu_id', $each_food->menu_id)->get();
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
                $available = 1;
            }
            $food_list[$key]->menu_available = $available;
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
                $available = 1;
            }

            $return_food_list[] = [
                'menu_id' => $each_menu[0]->menu_id,
                'menu_available' => $available,
                'menu_name' => $this->secondLanguage_user($each_menu[0]->menu_name, $each_menu[0]->secondary_menu_name),
                'items' => $each_menu,
            ];
        }

        $restaurant_detail = $restaurants::where('id', $restaurant_id)->select('id', 'restaurant_name', 'image', 'address')->first();
        if (!$restaurant_detail) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Restaurant not found'];
            return response()->json($response_array, 200);
        }

        $return_food_list = $this->updateSecondaryLang_web($return_food_list, $request);
        return response()->json([
            'status' => true,
            'title' => $title,
            'items' => $food_list,

        ], 200);
    }
    private function get_food_list_by_menu(Request $request)
    {



        $restaurant_id = $request->restaurant_id;
        $foodlist = $this->foodlist;
        $category = $this->category;
        $cart = $this->cart;
        $restaurants = $this->restaurants;
        $rest_id = DB::table('restaurants')->where('id', $restaurant_id)->first();
        $business_id = DB::table('business_type')->where('id', $rest_id->business_type)->first();
        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        if (empty($user_id)) {
            // return response()->json(['status' => false, 'error_code' => 101, 'message' => 'User not found'], 200);
        }

        // $user_id = $request->header('authId');
        $is_veg = $request->is_veg;

        $check_for_cart = $cart::where('user_id', $user_id)->get();
        if ($business_id->layout_id == 2) {
            $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                ->where('food_list.status', 1)
                ->join(
                    'category',
                    function ($join) {
                        $join->on('category.id', '=', 'food_list.category_id');
                    }
                )
                ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'category.category_name', 'category.category_secondaryname', 'category.image as cat_image', 'food_list.image as image', 'food_list.cloudflare_imageid as cloudflare_imageid')
                //->where('food_list.name', 'BBQ Chicken Wings')
                ->orderBy('rank')->orderBy('image', 'desc')
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
                ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'secondary_menu_name', 'menu.id as menu_id', 'food_list.image as image', 'food_list.cloudflare_imageid as cloudflare_imageid')
                //->where('food_list.name', 'BBQ Chicken Wings')
                ->orderBy('rank')->orderBy('image', 'desc')
                ->get();
        }

        $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
        $url = "";
        $acc_hash = "";
        if ($cloudflare->value == 1) {
            $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
            $url = $get_url->value;

            $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
            $acc_hash = $get_acchash->value;
        }

        foreach ($food_list as $key => $each_food) {


            if ($each_food->image != "" && $each_food->image != "undefined") {
                if ($cloudflare->value == 1 && $each_food->cloudflare_imageid != null) {


                    $food_list[$key]->image = $url . '/' . $acc_hash . '/' . $each_food->cloudflare_imageid . '/' . "w=500";
                } else {
                    if (str_starts_with($each_food->image, 'http')) {
                        $food_list[$key]->image = $each_food->image;
                    } else {
                        $food_list[$key]->image = File::exists(public_path('product_image/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $each_food->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $each_food->image : BASE_URL  . $each_food->image));
                        if ($business_id->layout_id == 2) {
                            $food_list[$key]->cat_image = BASE_URL  . $each_food->cat_image;
                        }
                    }
                }
            } else {
                $food_list[$key]->image = BASE_URL  . "fork.svg";
                if ($business_id->layout_id == 2) {
                    $food_list[$key]->cat_image = BASE_URL  . "no-storeprod.jpg";
                    $food_list[$key]->image = BASE_URL  . "no-storeprod.jpg";
                } else {
                    $food_list[$key]->image = BASE_URL  . "fork.svg";
                    $food_list[$key]->cat_image = BASE_URL  . "fork.svg";
                }
            }


            // if ($each_food->image != "" && $each_food->image != "undefined") {
            //     $food_list[$key]->image = File::exists(public_path('product_image/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $each_food->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $each_food->image : BASE_URL  . $each_food->image));
            //     if ($business_id->layout_id == 2) {
            //         $food_list[$key]->cat_image = BASE_URL  . $each_food->cat_image;
            //     }

            // } else {
            //     $food_list[$key]->image = BASE_URL  . "fork.svg";
            //     if ($business_id->layout_id == 2) {
            //         $food_list[$key]->cat_image = BASE_URL  . "no-storeprod.jpg";
            //         $food_list[$key]->image = BASE_URL  . "no-storeprod.jpg";
            //     } else {
            //         $food_list[$key]->image = BASE_URL  . "fork.svg";
            //         $food_list[$key]->cat_image = BASE_URL  . "fork.svg";
            //     }
            // }
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

            $this_gaddon = DB::table('foodlist_addons')
                ->where('foodlist_id', $each_food->food_id)
                ->where('addons_id', null)
                ->get();
            $add_group = [];

            foreach ($this_gaddon as $addon_key => $each_addon) {
                $gadd_ons = [];

                if ($each_addon->addon_group != null) {
                    $group = DB::table('addon_group')->where('id', $each_addon->addon_group)->first();
                    $array_group = json_decode($group->add_ons);

                    foreach ($array_group as $ag) {
                        $addon_detail = DB::table('add_ons')->where('id', $ag)->first();


                        $gadd_ons[] = [
                            'id' => $ag,
                            'name' => $this->secondLanguage_user($addon_detail->name, $addon_detail->secondary_name),
                            'secondary_name' => $addon_detail->secondary_name,
                            'price' => $addon_detail->price

                        ];
                    }
                    $add_group[] = [
                        'id' => $each_addon->addon_group,
                        'name' => $this->secondLanguage_user($group->name, $group->secondary_name),
                        'min' => $group->min,
                        'max' => $group->max,
                        'allow_multiple' => $group->allow_multiple,
                        'mandatory' => $group->mandatory,
                        'group_addons' => $gadd_ons

                    ];
                }
            }
            $food_list[$key]->groups = $add_group;
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
                    'foodlist_foodquantity.updated_at',
                    'foodlist_foodquantity.addons_id',
                    'foodlist_foodquantity.addon_group',
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }
            foreach ($this_size as $size_key => $each_size) {
                $sadd_group = [];

                if ($each_size->name) {
                    $each_size->name = $this->secondLanguage_user($each_size->name, $each_size->secondary_name);
                }
                if ($each_size->addon_group != null) {
                    $sg = $each_size->addon_group;
                    $sg_group = json_decode($sg);



                    foreach ($sg_group as $sgg) {
                        $sgadd_ons = [];
                        $sgg_group = DB::table('addon_group')->where('id', $sgg)->first();

                        $sarray_group = json_decode($sgg_group->add_ons);
                        foreach ($sarray_group as $sag) {
                            $saddon_detail = DB::table('add_ons')->where('id', $sag)->first();
                            $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('group_id', $sgg)->where('addon', $sag)->first();
                            if ($variant) {
                                $price = $variant->price;
                                $stock = $variant->stock;
                            } else {
                                $price = $saddon_detail->price;
                                $stock = 0;
                            }
                            $sgadd_ons[] = [
                                'id' => $sag,
                                'name' => $this->secondLanguage_user($saddon_detail->name, $saddon_detail->secondary_name),
                                'secondary_name' => $saddon_detail->secondary_name,
                                'price' => $price,
                                'stock' => $stock
                            ];
                        }
                        $sadd_group[] = [
                            'id' => $sgg,
                            'name' => $this->secondLanguage_user($sgg_group->name, $sgg_group->secondary_name),
                            'min' => $sgg_group->min,
                            'max' => $sgg_group->max,
                            'allow_multiple' => $sgg_group->allow_multiple,
                            'mandatory' => $sgg_group->mandatory,
                            'group_addons' => $sgadd_ons

                        ];
                    }
                }
                $this_size[$size_key]->groups = $sadd_group;
                $saa_addons = [];

                if ($each_size->addons_id != null) {
                    $sa = $each_size->addons_id;
                    $sa_group = json_decode($sa);

                    foreach ($sa_group as $saa) {
                        $sa_detail = DB::table('add_ons')->where('id', $saa)->first();
                        $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('addon', (int)$saa)->first();
                        if ($variant) {
                            $price = $variant->price;
                            $stock = $variant->stock;
                        } else {
                            $price = $sa_detail->price;
                            $stock = 0;
                        }
                        $saa_addons[] = [
                            'id' => $saa,
                            'name' => $this->secondLanguage_user($sa_detail->name, $sa_detail->secondary_name),
                            'secondary_name' => $sa_detail->secondary_name,
                            'price' => $price,
                            'stock' => $stock
                        ];
                    }
                }
                $this_size[$size_key]->add_ons = $saa_addons;

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
        if ($business_id->layout_id == 2) {
            foreach ($food_list as $key => $each_food) {

                if ($is_veg == 1 && $each_food->is_veg == 0) {
                    continue;
                }

                $new_food_list[$each_food->category_id][] = $each_food;
            }
        } else {
            foreach ($food_list as $key => $each_food) {

                if ($is_veg == 1 && $each_food->is_veg == 0) {
                    continue;
                }

                $new_food_list[$each_food->menu_id][] = $each_food;
            }
        }
        $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
        $url = "";
        $acc_hash = "";
        if ($cloudflare->value == 1) {
            $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
            $url = $get_url->value;

            $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
            $acc_hash = $get_acchash->value;
        }


        $return_food_list = [];
        if ($business_id->layout_id == 2) {
            foreach ($new_food_list as $key => $each_menu) {


                $available = 1;


                if ($each_menu[0]->cat_image != "") {
                    if (file_exists(public_path('/')  . $each_menu[0]->cat_image)) {
                        $cat_image =  BASE_URL  . $each_menu[0]->cat_image;
                    } else {
                        $cat_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
                    }
                } else {
                    $cat_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
                }

                $return_food_list[] = [
                    'menu_id' => $each_menu[0]->category_id,
                    'menu_available' => $available,
                    'menu_name' => $this->secondLanguage_user($each_menu[0]->category_name, $each_menu[0]->category_secondaryname),
                    'items' => $each_menu,
                    'cat_image' => $cat_image,
                ];
            }
        } else {
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
                    'menu_name' => $this->secondLanguage_user($each_menu[0]->menu_name, $each_menu[0]->secondary_menu_name),
                    'items' => $each_menu,
                ];
            }
        }

        // dd($return_food_list);
        $restaurant_detail = $restaurants::where('id', $restaurant_id)->select('id', 'restaurant_name', 'image', 'address')->first();
        if (!$restaurant_detail) {
            $response_array = ['status' => false, 'error_code' => 101, 'message' => 'Restaurant not found'];
            return response()->json($response_array, 200);
        }

        $return_food_list = $this->updateSecondaryLang_web($return_food_list, $request);
        return response()->json([
            'status' => true,
            'recommended' => [],
            'items' => $return_food_list,
            'restaurant_detail' => $restaurant_detail,
            'business_id' => $business_id->layout_id,
            'pharmacy_id' => $business_id->p_status
        ], 200);
    }
    public function get_food_list_by_menu_id(Request $request)
    {



        $restaurant_id = $request->restaurant_id;
        $menu_id = $request->menu_id;
        $foodlist = $this->foodlist;
        $category = $this->category;
        $cart = $this->cart;
        $restaurants = $this->restaurants;

        $rest_id = DB::table('restaurants')->where('id', $restaurant_id)->first();
        $business_id = DB::table('business_type')->where('id', $rest_id->business_type)->first();

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        if (empty($user_id)) {
            // return response()->json(['status' => false, 'error_code' => 101, 'message' => 'User not found'], 200);
        }

        // $user_id = $request->header('authId');
        $is_veg = $request->is_veg;

        $check_for_cart = $cart::where('user_id', $user_id)->get();
        if ($business_id->layout_id == 2) {
            $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                ->where('food_list.category_id', $menu_id)
                ->where('food_list.status', 1)
                ->join(
                    'category',
                    function ($join) {
                        $join->on('category.id', '=', 'food_list.category_id');
                    }
                )
                ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'category_name', 'category_secondaryname', 'category.image as cat_image', 'food_list.image as image', 'food_list.cloudflare_imageid as cloudflare_imageid')
                //->where('food_list.name', 'BBQ Chicken Wings')
                ->orderBy('rank')->orderBy('image', 'desc')
                ->get();
        } else {
            $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
                ->where('food_list.menu_id', $menu_id)
                ->where('food_list.status', 1)
                ->join(
                    'menu',
                    function ($join) {
                        $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                        $join->on('menu.id', '=', 'food_list.menu_id');
                    }
                )
                ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'secondary_menu_name', 'menu.id as menu_id', 'food_list.image as image', 'food_list.cloudflare_imageid as cloudflare_imageid')
                //->where('food_list.name', 'BBQ Chicken Wings')
                ->orderBy('rank')->orderBy('image', 'desc')
                ->get();
        }
        $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
        $url = "";
        $acc_hash = "";
        if ($cloudflare->value == 1) {
            $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
            $url = $get_url->value;

            $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
            $acc_hash = $get_acchash->value;
        }

        foreach ($food_list as $key => $each_food) {


            if ($each_food->image != "" && $each_food->image != "undefined") {
                if ($cloudflare->value == 1 && $each_food->cloudflare_imageid != null) {


                    $food_list[$key]->image = $url . '/' . $acc_hash . '/' . $each_food->cloudflare_imageid . '/' . "w=500";
                } else {
                    if (str_starts_with($each_food->image, 'http')) {
                        $food_list[$key]->image = $each_food->image;
                    } else {
                        $food_list[$key]->image = File::exists(public_path('product_image/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $each_food->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $each_food->image : BASE_URL  . $each_food->image));
                        if ($business_id->layout_id == 2) {
                            $food_list[$key]->cat_image = BASE_URL  . $each_food->cat_image;
                        }
                    }
                }
            } else {
                $food_list[$key]->image = BASE_URL  . "fork.svg";
                if ($business_id->layout_id == 2) {
                    $food_list[$key]->cat_image = BASE_URL  . "no-storeprod.jpg";
                    $food_list[$key]->image = BASE_URL  . "no-storeprod.jpg";
                } else {
                    $food_list[$key]->image = BASE_URL  . "fork.svg";
                    $food_list[$key]->cat_image = BASE_URL  . "fork.svg";
                }
            }





            // if ($each_food->image != "") {
            //     $food_list[$key]->image = File::exists(public_path('product_image/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $each_food->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $each_food->image : BASE_URL  . $each_food->image));
            //     if ($business_id->layout_id == 2) {
            //         $food_list[$key]->cat_image = BASE_URL  . $each_food->cat_image;
            //     }

            // } else {
            //     $food_list[$key]->image = BASE_URL  . "fork.svg";
            //     if ($business_id->layout_id == 2) {
            //         $food_list[$key]->cat_image = BASE_URL  . "no-storeprod.jpg";
            //         $food_list[$key]->image = BASE_URL  . "no-storeprod.jpg";
            //     } else {
            //         $food_list[$key]->image = BASE_URL  . "fork.svg";
            //         $food_list[$key]->cat_image = BASE_URL  . "fork.svg";
            //     }
            // }
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

            $this_gaddon = DB::table('foodlist_addons')
                ->where('foodlist_id', $each_food->food_id)
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
                            'name' => $this->secondLanguage_user($addon_detail->name, $addon_detail->secondary_name),
                            'secondary_name' => $addon_detail->secondary_name,
                            'price' => $addon_detail->price

                        ];
                    }
                    $add_group[] = [
                        'id' => $each_addon->addon_group,
                        'name' => $this->secondLanguage_user($group->name, $group->secondary_name),
                        'min' => $group->min,
                        'max' => $group->max,
                        'allow_multiple' => $group->allow_multiple,
                        'mandatory' => $group->mandatory,
                        'group_addons' => $gadd_ons

                    ];
                }
            }
            $food_list[$key]->groups = $add_group;
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
                    'foodlist_foodquantity.updated_at',
                    'foodlist_foodquantity.addons_id',
                    'foodlist_foodquantity.addon_group',
                )
                ->get()->toArray();

            if (!$this_size) {
                $food_list[$key]->food_quantity = [];
                continue;
            }

            foreach ($this_size as $size_key => $each_size) {
                $sadd_group = [];

                if ($each_size->name) {
                    $each_size->name = $this->secondLanguage_user($each_size->name, $each_size->secondary_name);
                }

                if ($each_size->addon_group != null) {
                    $sg = $each_size->addon_group;
                    $sg_group = json_decode($sg);


                    foreach ($sg_group as $sgg) {
                        $sgadd_ons = [];
                        $sgg_group = DB::table('addon_group')->where('id', $sgg)->first();

                        $sarray_group = json_decode($sgg_group->add_ons);
                        foreach ($sarray_group as $sag) {
                            $saddon_detail = DB::table('add_ons')->where('id', $sag)->first();
                            $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('group_id', $sgg)->where('addon', $sag)->first();
                            if ($variant) {
                                $price = $variant->price;
                                $stock = $variant->stock;
                            } else {
                                $price = $saddon_detail->price;
                                $stock = 0;
                            }
                            $sgadd_ons[] = [
                                'id' => $sag,
                                'name' => $this->secondLanguage_user($saddon_detail->name, $saddon_detail->secondary_name),
                                'secondary_name' => $saddon_detail->secondary_name,
                                'price' => $price,
                                'stock' => $stock
                            ];
                        }
                        $sadd_group[] = [
                            'id' => $sgg,
                            'name' => $this->secondLanguage_user($sgg_group->name, $sgg_group->secondary_name),
                            'min' => $sgg_group->min,
                            'max' => $sgg_group->max,
                            'allow_multiple' => $sgg_group->allow_multiple,
                            'mandatory' => $sgg_group->mandatory,
                            'group_addons' => $sgadd_ons

                        ];
                    }
                }
                $this_size[$size_key]->groups = $sadd_group;
                $saa_addons = [];

                if ($each_size->addons_id != null) {
                    $sa = $each_size->addons_id;
                    $sa_group = json_decode($sa);

                    foreach ($sa_group as $saa) {
                        $sa_detail = DB::table('add_ons')->where('id', $saa)->first();
                        $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('addon', (int)$saa)->first();
                        if ($variant) {
                            $price = $variant->price;
                            $stock = $variant->stock;
                        } else {
                            $price = $sa_detail->price;
                            $stock = 0;
                        }
                        $saa_addons[] = [
                            'id' => $saa,
                            'name' => $this->secondLanguage_user($sa_detail->name, $sa_detail->secondary_name),
                            'secondary_name' => $sa_detail->secondary_name,
                            'price' => $price,
                            'stock' => $stock
                        ];
                    }
                }
                $this_size[$size_key]->add_ons = $saa_addons;
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
        if ($business_id->layout_id == 2) {
            foreach ($food_list as $key => $each_food) {

                if ($is_veg == 1 && $each_food->is_veg == 0) {
                    continue;
                }

                $new_food_list[$each_food->category_id][] = $each_food;
            }
        } else {
            foreach ($food_list as $key => $each_food) {

                if ($is_veg == 1 && $each_food->is_veg == 0) {
                    continue;
                }

                $new_food_list[$each_food->menu_id][] = $each_food;
            }
        }


        $return_food_list = [];
        if ($business_id->layout_id == 2) {
            foreach ($new_food_list as $key => $each_menu) {


                $available = 1;


                $return_food_list[] = [
                    'menu_id' => $each_menu[0]->category_id,
                    'menu_available' => $available,
                    'menu_name' => $this->secondLanguage_user($each_menu[0]->category_name, $each_menu[0]->category_secondaryname),
                    'items' => $each_menu,
                ];
            }
        } else {
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
                    'menu_name' => $this->secondLanguage_user($each_menu[0]->menu_name, $each_menu[0]->secondary_menu_name),
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

        $return_food_list = $this->updateSecondaryLang_web($return_food_list, $request);
        return response()->json([
            'status' => true,
            'recommended' => [],
            'items' => $return_food_list,
            'restaurant_detail' => $restaurant_detail,
            'business_id' => $business_id->layout_id,
            'pharmacy_id' => $business_id->p_status
        ], 200);
    }
    public function get_brand_rest(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'brand_id' => 'required',
                'lat' => 'required',
                'lng' => 'required',

            )
        );

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }


        $id = $request->brand_id;

        $source_lat = $request->lat;
        $source_lng = $request->lng;

        $restaurants = $this->restaurants;

        $nearbyRestaurantIDs = Restaurant::nearbrandDistance($source_lat, $source_lng);

        $data = $restaurants
            ->select('restaurants.*')
            ->whereIn('id', $nearbyRestaurantIDs)
            ->where('brand_name', $id)
            ->get();

        $restaurant_list = array();
        $j = 0;
        foreach ($data as $d) {



            $rcuisines = array();
            $i = 0;
            foreach ($d->Cuisines as $r_cuisines) {
                if ($i < 2) {
                    $rcuisines[] = array(
                        'name' => $this->secondLanguage_user($r_cuisines->name, $r_cuisines->secondary_name),
                    );
                    $i = $i + 1;
                }
            }

            $check_favourite = DB::table('favourite_list')->where('user_id', $user_id)->where('restaurant_id', $d->id)->get();
            if (count($check_favourite) != 0) {
                $is_favourite = 1;
            } else {
                $is_favourite = 0;
            }

            //calculate restaurant open time
            $is_open = restaurant_is_open($d->id);

            $rating_statistics = get_rating_statistics($d->id);
            $distance = Restaurant::getrestdistance($source_lat, $source_lng, $d->id);


            $restaurant_list[] = array(
                'id' => $d->id,
                'name' => $this->secondLanguage_user($d->restaurant_name, $d->restaurant_secondary_name),
                'image' => File::exists(public_path('restaurant_uploads/') . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Logo/' . $d->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Logo/' . $d->image : BASE_URL . UPLOADS_PATH_LOGO . $d->image),
                'banner' => File::exists(public_path('restaurant_uploads/') . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner : (File::exists(public_path('restaurant_uploads/') . $d->id . '/' . 'Banner/' . $d->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $d->id . '/' . 'Banner/' . $d->banner : BASE_URL . UPLOADS_PATH_BANNER . $d->banner),
                // 'image' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->image,
                // 'banner' => BASE_URL . RESTAURANT_UPLOADS_PATH . $d->banner,
                'discount' => $d->discount,
                'rating' => (float) $rating_statistics['rating_percent'], //$d->rating,
                'is_open' => $is_open, // 1- Open , 0 - Close
                'cuisines' => $rcuisines,
                'travel_time' => $d->estimated_delivery_time,
                'delivery_type' => json_decode($d->delivery_type),
                'price' => '',
                'is_favourite' => $is_favourite,
                'address' => $d->address,
                'distance' => $distance,

            );
            $j++;

            // }
        }
        $schedule = DB::table('settings')->where('key_word', 'schedule')->value('value');

        $is = array();
        foreach ($restaurant_list as $key => $row) {
            $is[$key] = $row['is_open'];
        }

        array_multisort($is, SORT_DESC, $restaurant_list);
        if ($data) {
            $response_array = array('status' => true, 'restaurants' => $restaurant_list, 'schedule' => $schedule);
        } else {
            $response_array = array('status' => false, 'schedule' => $schedule, 'message' => 'No Restaurants Found');
        }

        $rt = [];
        $co = 0;
        foreach ($restaurant_list as $res_list) {
            if ($co < 10) {
                $img = $res_list['banner'];
                if ($res_list['is_open'] == 1) {
                    $class = "rounded3";
                } else {
                    $class = "rounded3 closed";
                }
                $rt[] = [
                    'id' => $res_list['id'],
                    'img' => $img,
                    'text' => $res_list['name'],
                    'travel_time' => $res_list['travel_time'],
                    'rating' => $res_list['rating'],
                    'icon' => 'StarIcon',
                    'c_icon' => 'ClockIcon',
                    'd_icon' => 'MapPinIcon',
                    'distance' => $res_list['distance'],
                    'cuisines' => $res_list['cuisines'],
                    'is_open' => $res_list['is_open'],
                    'class' => $class,

                ];
            }
            $co++;
        }
        $price = array_column($rt, 'is_open');

        array_multisort($price, SORT_DESC, $rt);
        $message = $rt;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $rt]);
        return $response_Array;
    }

    public function get_category_wise_food_list(Request $request)
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

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $cart = $this->cart;
        $check_for_cart = $cart::where('user_id', $user_id)->get();
        $restaurants = $this->restaurants;
        $key_word = $request->key_word;
        $scheduleAt = $request->date . ' ' . $request->time;

        $source_lat = $request->lat;
        $source_lng = $request->lng;

        $availableRestIDs = [];

        $all_restaurant = $restaurants->where('status', 1)->select('id', 'city', 'lat', 'lng')->get();
        foreach ($all_restaurant as $each_restaurant) {
            if (!empty($source_lat) && !empty($source_lng)) {
                $geofenceRadius = (float) ($each_restaurant->GeofenceRadius ?? 10);
                $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $each_restaurant->lat, $each_restaurant->lng);
                if ($distance && $distance <= $geofenceRadius) {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            } else if (!empty($request->city_id)) {
                if ($each_restaurant->city == $request->city_id) {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            } else {
                $availableRestIDs[] = $each_restaurant->id;
            }
        }




        // $data = $restaurants::where('status', 1)->wherein('id',$availableRestIDs)->where('status', 1)->get();



        // $rt=[];

        // foreach($data as $res_list){
        //         $img = BASE_URL . RESTAURANT_UPLOADS_PATH . $res_list->image;
        //     $rt[]=[
        //         'img'=>$img,
        //         'text'=>$res_list->restaurant_name,
        //         'travel_time'=>$res_list->estimated_delivery_time,
        //         'rating'=>$res_list->rating,
        //         'c_icon'=>'ClockIcon',
        //         'distance'=>$res_list->distance,
        //         'cuisines'=>$res_list->cuisines,
        //         'is_open'=>$res_list->is_open,
        //         'id'=>$res_list->id,
        //     ];


        // }




        $food_list = DB::table('food_list')
            ->Where('food_list.category_id', '=', $key_word)
            ->wherein('restaurant_id', $availableRestIDs)
            ->join('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
            ->where('restaurants.status', 1)
            ->select(
                'food_list.*',
                'restaurants.restaurant_name',
            )
            ->orderby('restaurants.id', 'asc')
            ->get();





        $rt2 = [];
        foreach ($food_list as $dat) {
            $data1 = $restaurants::where('id', $dat->restaurant_id)->first();
            $distance = Restaurant::getrestdistance($source_lat, $source_lng, $dat->restaurant_id);
            $is_open = restaurant_is_open($dat->id);

            $img1 = File::exists(public_path('restaurant_uploads/') . $data1->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data1->banner : (File::exists(public_path('restaurant_uploads/') . $data1->id . '/' . 'Banner/' . $data1->banner) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $data1->id . '/' . 'Banner/' . $data1->banner : BASE_URL . UPLOADS_PATH_BANNER . $data1->banner);
            // $img1 = BASE_URL . RESTAURANT_UPLOADS_PATH . $data1->banner;

            $rt2[] = [
                'img' => $img1,
                'text' => $this->secondLanguage_user($data1->restaurant_name, $data1->restaurant_secondary_name),
                'travel_time' => $data1->estimated_delivery_time,
                'rating' => $data1->rating,
                'c_icon' => 'ClockIcon',
                'distance' => $distance,
                'cuisines' => $data1->cuisines,
                'is_open' => $is_open,
                'id' => $data1->id,
            ];
        }

        $unique_array = [];
        foreach ($rt2 as $element) {
            $hash = $element['id'];
            $unique_array[$hash] = $element;
        }
        $result = array_values($unique_array);
        $price = array_column($result, 'distance');

        array_multisort($price, SORT_ASC, $result);
        $message = $rt2;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $result, 'food_list' => $rt2]);
        return $response_Array;
    }

    public function  get_category_wise_food_list_business(Request $request)
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

        if ($request->header('authId') != "") {
            $user_id = $request->header('authId');
        } else {
            $user_id = $request->authId;
        }

        $cart = $this->cart;
        $check_for_cart = $cart::where('user_id', $user_id)->get();
        $restaurants = $this->restaurants;
        $key_word = $request->key_word;
        $scheduleAt = $request->date . ' ' . $request->time;

        $source_lat = $request->lat;
        $source_lng = $request->lng;

        $availableRestIDs = [];

        $all_restaurant = $restaurants->where('status', 1)->select('id', 'city', 'lat', 'lng')->get();

        foreach ($all_restaurant as $each_restaurant) {
            if (!empty($source_lat) && !empty($source_lng)) {
                $geofenceRadius = (float) ($each_restaurant->GeofenceRadius ?? DEFAULT_RADIUS);
                $distance = vincentyGreatCircleDistance($source_lat, $source_lng, $each_restaurant->lat, $each_restaurant->lng);
                if ($distance && $distance <= $geofenceRadius) {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            } else if (!empty($request->city_id)) {
                if ($each_restaurant->city == $request->city_id) {
                    $availableRestIDs[] = $each_restaurant->id;
                }
            } else {
                $availableRestIDs[] = $each_restaurant->id;
            }
        }




        // $data = $restaurants::where('status', 1)->wherein('id',$availableRestIDs)->where('status', 1)->get();



        // $rt=[];

        // foreach($data as $res_list){
        //         $img = BASE_URL . RESTAURANT_UPLOADS_PATH . $res_list->image;
        //     $rt[]=[
        //         'img'=>$img,
        //         'text'=>$res_list->restaurant_name,
        //         'travel_time'=>$res_list->estimated_delivery_time,
        //         'rating'=>$res_list->rating,
        //         'c_icon'=>'ClockIcon',
        //         'distance'=>$res_list->distance,
        //         'cuisines'=>$res_list->cuisines,
        //         'is_open'=>$res_list->is_open,
        //         'id'=>$res_list->id,
        //     ];


        // }

        $category = DB::table('business_category')->Where('id', $key_word)->first();


        $food_list = DB::table('food_list')
            ->where('food_list.business_category_id', '=', $key_word)
            ->whereIn('restaurant_id', $availableRestIDs)
            ->leftjoin('restaurants', 'restaurants.id', '=', 'food_list.restaurant_id')
            ->where('restaurants.status', 1)
            ->select(
                'food_list.*',
                'restaurants.restaurant_name',
            )
            ->orderby('restaurants.id', 'asc')
            ->get();


        $rt2 = [];
        $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
        $url = "";
        $acc_hash = "";
        if ($cloudflare->value == 1) {
            $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
            $url = $get_url->value;

            $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
            $acc_hash = $get_acchash->value;
        }
        foreach ($food_list as $dat) {
            $data1 = $restaurants::where('id', $dat->restaurant_id)->first();
            $distance = Restaurant::getrestdistance($source_lat, $source_lng, $dat->restaurant_id);
            $is_open = restaurant_is_open($data1->id);

            if ($cloudflare->value == 1 && $data1->cloudflare_bannerid != null) {

                $fbanner = $url . '/' . $acc_hash . '/' . $data1->cloudflare_bannerid . '/' . "w=500,height=136,trim=0;400";
            } else {


                if ($data1->banner != '') {
                    if (File::exists(public_path('restaurant_uploads/') . $data1->banner)) {
                        $fbanner =   BASE_URL . RESTAURANT_UPLOADS_PATH . $data1->banner;
                    } elseif (File::exists(public_path('restaurant_uploads/') . $data1->id . '/' . 'Banner/' . $data1->banner)) {
                        $fbanner =  BASE_URL . RESTAURANT_UPLOADS_PATH . $data1->id . '/' . 'Banner/' . $data1->banner;
                    } else {
                        $fbanner = BASE_URL . "def_banner.jpg";
                    }
                } else {
                    $fbanner = BASE_URL . "def_banner.jpg";
                }
            }





            $rt2[] = [
                'img' => $fbanner,
                'text' => $this->secondLanguage_user($data1->restaurant_name, $data1->restaurant_secondary_name),
                'travel_time' => $data1->estimated_delivery_time,
                'rating' => $data1->rating,
                'c_icon' => 'ClockIcon',
                'distance' => $distance,
                'cuisines' => $data1->cuisines,
                'is_open' => $is_open,
                'id' => $data1->id,
            ];

            foreach ($data1->cuisines as $d) {
                $d->name = $this->secondLanguage_user($d->name, $d->secondary_name);
            }
        }

        $unique_array = [];
        foreach ($rt2 as $element) {
            $hash = $element['id'];
            $unique_array[$hash] = $element;
        }
        $result = array_values($unique_array);
        $price = array_column($result, 'distance');

        array_multisort($price, SORT_ASC, $result);

        $message = $rt2;
        $status = true;
        $response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $result, 'food_list' => $rt2, 'category' => $category]);
        return $response_Array;
    }

    public function get_checkout_details(Request $request)
    {

        $delivery_address = $this->deliveryaddress;
        $restaurant_model = $this->restaurants;

        $user_id = $request->user_id;
        $user = DB::table('users')->where('id', $user_id)->first();
        $user_number = $user ? $user->phone : null;

        if ($user == null) {
            $ses_user = 0;
            $user_wallet_amount =  0;
        } else {
            $ses_user = 1;
            $user_wallet_amount =  $user->wallet_amount;
        }

        $user_id = $user ? $user->id : 0;


        $addresses = $delivery_address->where('user_id', $user_id)->get();
        $restaurant = $restaurant_model->where('id', $request->restaurant_id)->first();
        $delivery_charge_limits = DB::table('delivery_charge_limits')->select('Minimum', 'Maximum', 'Value', 'Type')
            ->where('restaurant_id', $restaurant->id)->get()->toArray();

        $delivery_types = json_decode($restaurant->delivery_type);
        $is_delivery_enabled = 0;
        $is_pickup_enabled = 0;

        if ($delivery_types) {
            foreach ($delivery_types as $each_delivery_type) {
                if ($each_delivery_type == 1) {
                    $is_delivery_enabled = 1;
                }
                if ($each_delivery_type == 2) {
                    $is_pickup_enabled = 1;
                }
            }
        }



        $dt = Carbon::now();
        $promocode_list_restaurant = $this->promocode->where('restaurant_id', $request->restaurant_id)->where('status', '1')->whereRaw('"' . $dt . '" between `available_from` and `valid_till`')->get();
        $gapi = DB::table('settings')->where('key_word', 'google_api_key')->first();
        if (sizeof($addresses)) {

            foreach ($addresses as $add) {

                if ($add->is_default == 1) {

                    $user_lat = $add->lat;
                    $user_lng = $add->lng;

                    $d = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$restaurant->lat,$restaurant->lng&destinations=$user_lat,$user_lng&mode=driving&sensor=false&key=" . $gapi->value;
                    $json = file_get_contents($d); //dd($d);

                    $details = json_decode($json, TRUE);
                    if (isset($details['rows'][0]['elements'][0]['distance'])) {

                        $distance = (float) $details['rows'][0]['elements'][0]['distance']['text'];
                    } else {
                        $message = "Restaurant does not comes under your location";
                        $status = false;
                        $response_Array = json_encode(['message' => $message, 'status' => $status]);
                        return $response_Array;
                    }
                }
            }
        } else {


            $user_lat = $request->lat;
            $user_lng = $request->lng;
            if (empty($user_lat)) {
                $user_lat = $restaurant->lat;
                $user_lng = $restaurant->lng;
            }
            $d = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$restaurant->lat,$restaurant->lng&destinations=$user_lat,$user_lng&mode=driving&sensor=false&key=" . $gapi->value;
            $json = file_get_contents($d);
            $details = json_decode($json, TRUE);


            if (isset($details['rows'][0]['elements'][0]['distance'])) {
                $distance = (float) $details['rows'][0]['elements'][0]['distance']['text'];
            } else {
                $message = "Restaurant does not comes under your location";
                $status = false;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }
        }
        $message = $addresses;
        $status = true;
        $response_Array = json_encode(['addresses' => $addresses, 'status' => $status, 'restaurant' => $restaurant, 'delivery_charge_limits' => $delivery_charge_limits, 'is_delivery_enabled' => $is_delivery_enabled, 'is_pickup_enabled' => $is_pickup_enabled, 'ses_user' => $ses_user, 'user_wallet_amount' => $user_wallet_amount, 'user_number' => $user_number, 'user_id' => $user_id, 'promocode_list_restaurant' => $promocode_list_restaurant, 'distance' => $distance]);

        return $response_Array;
    }
    public function paynow(Request $request)
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
                'delivery_type' => 'required',
                // 'delivery_address' => 'required'
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = ['status' => false, 'error_code' => 101, 'message' => $error_messages];
            return response()->json($response_array, 200);
        }

        $is_cod = $request->is_cod;
        $user_wallet = $request->user_wallet;
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
        $user_id = $request->user_id;
        // $user_id = $user ? $user->id : 0;

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


        // $user_id = $request->header('authId');
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
        //exit;
        $delivery_type = $request->delivery_type;

        $user_detail = $this->users::where('id', $user_id)->first();



        if (isset($user_detail) && ($user_detail->device_type == "WEB" || $request->device_type == "WEB")) {
            $food_id = array();
            $food_qty = $food_quantity = $food_quantity_price = array();
            $food_ids = str_replace('"', '', (string) $request->food_id);
            $food_id = explode(',', $food_ids);
            $food_prices = str_replace('"', '', (string) $request->food_price);
            $food_price = explode(',', $food_prices);

            $food_qtys = str_replace('"', '', (string) $request->food_qty);
            $food_qty = explode(',', $food_qtys);

            $food_addons_all = explode('-', $request->addons);

            $food_addons_qty = explode('-', $request->addonsqty);

            $food_addons = [];
            foreach ($food_addons_all as $key => $food_addons_of_each_product) {
                $eachProductAddons = explode(' ', $food_addons_of_each_product);

                if (isset($food_id[$key]))
                    $food_addons[$food_id[$key]] = $eachProductAddons;
            };

            $food_addons_quantity = [];
            foreach ($food_addons_qty as $key => $food_addons_of_each_products) {
                $eachProductAddonsqty = explode(' ', $food_addons_of_each_products);

                if (isset($food_id[$key]))
                    $food_addons_quantity[$food_id[$key]] = $eachProductAddonsqty;
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
            $food_addons_quantity = [];
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

        if ($delivery_type == 1) {
            $d_lat = $delivery_address_detail->lat ?? 0;
            $d_lng = $delivery_address_detail->lng ?? 0;
            $delivery_address = $delivery_address_detail->address ?? '';
        } else {
            $d_lat = "";
            $d_lng = "";
            $delivery_address = '';
        }

        $order_id = $this->generate_booking_id();

        //get restaurant based commission
        $restaurant_details = $this->restaurants->find($restaurant_id);
        if (!empty($restaurant_details->admin_commision)) {
            $admin_commision_setting = $restaurant_details->admin_commision;
        } else {
            $admin_commision_setting = $settings::where('key_word', 'admin_commission')->value('value');
        }

        /*if (!empty($restaurant_details->driver_commision)) {
            $delivery_boy_commision_setting = $restaurant_details->driver_commision;
        } else {
            $delivery_boy_commision_setting = $settings::where('key_word', 'delivery_boy_commission')->value('value');
        }*/

        //$restaurant_commision_setting = $settings::where('key_word','restaurant_commission')->first();

        if ($bill_amount == 0) {
            $amt = $delivery_charge + $request->tax + $item_total;
            $admin_commission = ($amt / 100) * $admin_commision_setting;
            $delivery_boy_commission = $delivery_charge;
            $restaurant_commission = $bill_amount - ($admin_commission + $delivery_boy_commission);
        } else {
            $admin_commission = ($bill_amount / 100) * $admin_commision_setting;
            $delivery_boy_commission = $delivery_charge;
            $restaurant_commission = $bill_amount - ($admin_commission + $delivery_boy_commission);
        }
        if ($bill_amount == 0) {
            $bill_amount1 =  $delivery_charge + $request->tax + $item_total;
        } else {
            $bill_amount1 = $bill_amount;
        }

        if ($delivery_type == 2) {
            $delivery_boy_commission = 0;
            $takeaway_code = rand(10000, 99999);
        } else {
            $takeaway_code = '';
        }

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

        $amt =  $delivery_charge + $request->tax + $item_total;
        $user_list = DB::table('users')->where('id', $user_id)->first();

        if ($amt > $user_list->wallet_amount) {
            $user_detail = $this->users->find($user_id);
            $status_entry1 = array();
            $status_entry1[] = array(
                'user_id' => $user_id,
                'amount' => $user_list->wallet_amount,
                'topup_code' => '',
                'type' => 2, //1-credit 2-debit
                'status' => 1

            );
            $reduced = $user_list->wallet_amount;

            $ins =     DB::table('user_wallet_history')->insert($status_entry1);
            $this->users->find($user_id)->decrement('wallet_amount', $user_list->wallet_amount);
        } else {
            $amt = $delivery_charge + $request->tax + $item_total;
            $user_detail = $this->users->find($user_id);
            $status_entry1 = array();
            $status_entry1[] = array(
                'user_id' => $user_id,
                'amount' =>  $amt,
                'topup_code' => '',
                'type' => 2, //1-credit 2-debit
                'status' => 1
            );
            $reduced =  $amt;

            $ins = DB::table('user_wallet_history')->insert($status_entry1);
            $this->users->find($user_id)->decrement('wallet_amount',  $amt);
        }


        if ($is_cod == 0 && $bill_amount != 0) {
            $order_data[0]['status'] = -1;
        } else {
            $order_data[0]['status'] = 0;
        }

        $foodrequest::insert($order_data);

        $last_id = $foodrequest::where('user_id', $user_id)->where('restaurant_id', $restaurant_id)->orderBy('id', 'desc')->first();

        $request_id = $last_id->id;
        $update_wallet = DB::table('requests')->where('id', $request_id)->update([
            'wallet_amount' => $reduced
        ]);
        $update_ins = DB::table('user_wallet_history')
            ->where('id', $ins)
            ->update([
                'order_id' => $request_id
            ]);
        $business_id = DB::table('business_type')->where('id', $restaurant_details->business_type)->first();
        if ($business_id->p_status == 1) {


            if ($request->pharmacy_image != "undefined" && $request->pharmacy_image != '') {
                if ($request->pharmacy_image == '[]') {
                    $image = '';
                } else {
                    $image = $this->custom->upload_pharmacy($request, 'pharmacy_image');
                }
            } else {
                $image = '';
            }
            $update_wallet1 = DB::table('requests')->where('id', $request_id)->update([
                'image' => $image,
            ]);
        }
        // $update_wallet = DB::table('requests')->where('id',$request_id)->update([
        //     'wallet_amount'=>$reduced
        // ]);
        // $update_ins = DB::table('user_wallet_history')
        //                 ->where('id',$ins)
        //                 ->update([
        //                     'order_id'=> $request_id
        //                 ]);
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
                $addon_qty = explode(',', $food_addons_quantity[$food_id[$i]][0]);
                for ($j = 0; $j < count($addon_ids); $j++) {
                    $add_ons = $this->add_ons->find($addon_ids[$j]);
                    $add_ons_qty = $this->add_ons->find($addon_ids[$j]);

                    if (!isset($add_ons->id) || empty($add_ons->id)) {
                        continue;
                    }

                    $requestdetail_addons = new RequestdetailAddons;
                    $requestdetail_addons->requestdetail_id = $request_detail_id;
                    $requestdetail_addons->addons_id = $add_ons->id;
                    $requestdetail_addons->name = $add_ons->name;
                    $requestdetail_addons->price = $add_ons->price;
                    $requestdetail_addons->quantity = $addon_qty[$j];

                    $requestdetail_addons->save();
                }
            }
        }

        $checkout_url = null;
        $PaymentID = null;
        $ClientSecret = null;
        if ($is_cod == 0 && $bill_amount != 0) {
            $bill_amount_string = number_format($bill_amount, 2, '.', '');
            $payment_gateway = '';
            $payment_id = '';

            $default_currency = DB::table('country')->where('is_default', 1)->first();

            $payment_gateway = 'Razorpay';
            $checkout_url =  $last_id->id;
            $payment_id = '';

            // $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
            // $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
            // $version = DB::table('settings')->where('key_word', 'stripe_version')->first();
            // $stripe = new Stripe($sk->value, $version->value);
            // $paymentIntent = $stripe->paymentIntents()->create([
            //     'amount' => $bill_amount_string,
            //     'currency' => $default_currency->currency_code,
            //     'payment_method_types' => [
            //         'card',
            //     ],

            // ]);

            // if (!isset($paymentIntent['id'])) {
            //     return false;
            // }
            // $PaymentID = $paymentIntent['id'];
            // $ClientSecret = $paymentIntent['client_secret'];


            DB::table('payments')->insert([
                'request_id' => $last_id->id,
                'payment_gateway' => $payment_gateway,
                'amount' => $bill_amount_string,
                'currency' => $default_currency->currency_code,
                'payment_id' => $payment_id,
                'payment_description' => $order_id
            ]);
        }

        if ($is_cod == 0) {
            // sendNewOrderEmail2Admins($request_id);
            // sendNewOrderEmail2Customer($request_id);
            Restaurant::callRestaurant($res_phone);
        }
        sendNotification(
            [$restaurant_details->device_token],
            trans('constants.notification_restaurant_new_title'),
            trans('constants.notification_restaurant_new_msg')
        );

        $header = array();
        $header[] = 'Content-Type: application/json';
        $postdata = array();
        // $postdata['id'] = $request_id;
        $postdata['user_id'] = $user_id;
        $postdata['request_id'] = $request_id;
        $postdata = json_encode($postdata);

        $ch = curl_init(FIREBASE_URL . "/new_user_request/$user_id.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);

        $header = array();
        $header[] = 'Content-Type: application/json';
        $postdata = array();
        // $postdata['id'] = $request_id;
        $postdata['request_id'] = (string) $request_id;
        $postdata['user_id'] = (float) $user_id;
        $postdata['provider_id'] = "0";
        $postdata['status'] = 0;
        $postdata = json_encode($postdata);

        $ch = curl_init(FIREBASE_URL . "/current_request/$request_id.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);

        //dd("fcm");
        $cart::where('user_id', $user_id)->delete();

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
        //send push notification to user
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

    public function paynow_pickuporder(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'restaurant_id' => 'required',
                'item_total' => 'required',
                //'offer_discount' => 'required',
                'delivery_charge' => 'required',
                'bill_amount' => 'required',
                //'coupon_code' => 'required',
                'paid_type' => 'required',
                'delivery_type' => 'required',
                // 'delivery_address' => 'required'
            ]
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = ['status' => false, 'error_code' => 101, 'message' => $error_messages];
            return response()->json($response_array, 200);
        }

        $is_cod = $request->is_cod;
        $user_wallet = $request->user_wallet;
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
        $user_id = $request->user_id;
        // $user_id = $user ? $user->id : 0;

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


        // $user_id = $request->header('authId');
        $restaurant_id = $request->restaurant_id;
        $item_total = $request->item_total;
        if (isset($request->offer_discount)) {
            $offer_discount = $request->offer_discount;
        } else {
            $offer_discount = 0;
        }


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
        //exit;
        $delivery_type = $request->delivery_type;

        $user_detail = $this->users::where('id', $user_id)->first();

        $paid_type = $request->paid_type;
        $delivery_address_detail = $this->deliveryaddress::where('user_id', $user_id)->where('is_default', 1)->first();

        if ($delivery_type == 1) {
            $d_lat = $delivery_address_detail->lat ?? 0;
            $d_lng = $delivery_address_detail->lng ?? 0;
            $delivery_address = $delivery_address_detail->address ?? '';
        } else {
            $d_lat = "";
            $d_lng = "";
            $delivery_address = '';
        }

        $order_id = $this->generate_booking_id();

        $restaurant_details = $this->restaurants->find($restaurant_id);




        $delivery_boy_commission = $delivery_charge;
        if ($delivery_type == 2) {
            $delivery_boy_commission = 0;
            $takeaway_code = rand(10000, 99999);
        } else {
            $takeaway_code = '';
        }

        $res_phone = $restaurant_details->phone;

        $custom = $this->custom;
        $order_data = array();
        if ($request->image != "undefined") {
            if ($request->image) {
                $image = $custom->upload_pickupdrop($request, 'image');
            } else {
                $image = NULL;
            }
        } else {
            $image = NULL;
        }
        $restaurant_details = $this->restaurants->find($restaurant_id);
        if (!empty($restaurant_details->admin_commision)) {
            $admin_commision_setting = $restaurant_details->admin_commision;
        } else {
            $admin_commision_setting = $settings::where('key_word', 'admin_commission')->value('value');
        }
        $admin_commission = ($bill_amount / 100) * $admin_commision_setting;
        $restaurant_commission = $bill_amount - ($admin_commission + $delivery_boy_commission);


        $order_data[] = array(
            'order_id' => $order_id,
            'user_id' => $user_id,
            'restaurant_id' => $restaurant_id,
            'offer_discount' => $offer_discount,
            'delivery_charge' => $delivery_charge,
            'bill_amount' => $bill_amount,
            'delivery_boy_commision' => $delivery_charge,
            'coupon_code' => $coupon_code,
            'is_cod' => $is_cod,
            'is_confirmed' => 0,
            'is_paid' => 0,
            'paid_type' => $paid_type,
            'delivery_address' => $request->drop_address,
            'd_lat' => $request->to_lat,
            'd_lng' => $request->to_lng,
            'ScheduledAt' => '',
            'pickup_address' => $request->pickup_address,
            'pickup_lat' => $request->pickup_lat,
            'pickup_lng' => $request->pickup_lng,
            'pickup_name' => $request->pickup_name,
            'pickup_phone' => $request->pickup_phone,
            'drop_name' => $request->drop_name,
            'drop_phone' => $request->drop_phone,
            'image' => $image,
            'pickup_inst' => $request->pickup_inst,
            'drop_inst' => $request->drop_inst,
            // 'distance' => $request->distance,
            'instruction' => $request->instruction,
            'carrier' => $request->carrier,
            'package_type' => $request->package_type,
            'admin_commision' => $admin_commission,
            'restaurant_commision' => $restaurant_commission,
            'tax' =>  $request->tax,
            'ordered_time' => date('Y-m-d H:i:s'),
        );


        $del = DB::table('delivery_address')->where('id', $request->from_id)
            ->update([
                'name' => $request->pickup_name,
                'phone_number' => $request->pickup_phone,
                'instruction' => $request->pickup_inst,
            ]);

        $del = DB::table('delivery_address')->where('id', $request->to_id)
            ->update([
                'name' => $request->drop_name,
                'phone_number' => $request->drop_phone,
                'instruction' => $request->drop_inst,
            ]);


        if ($is_cod == 0) {
            $order_data[0]['status'] = -1;
        }

        $foodrequest::insert($order_data);

        $last_id = $foodrequest::where('user_id', $user_id)->where('restaurant_id', $restaurant_id)->orderBy('id', 'desc')->first();

        $request_id = $last_id->id;



        $checkout_url = null;
        $PaymentID = null;
        $ClientSecret = null;
        if ($is_cod == 0) {
            //exit(env('PAYMENT_GATEWAY'));
            $bill_amount_string = number_format($bill_amount, 2, '.', '');
            $payment_gateway = '';
            $payment_id = '';
            // if (env('PAYMENT_GATEWAY') == 'Razorpay') {
            //     $payment_gateway = 'Razorpay';
            //     $checkout_url = url('/razorpay') . '/' . $last_id->id;
            //     $payment_id = '';
            // } else if (env('PAYMENT_GATEWAY') == 'Stripe') {
            //     $payment_gateway = 'Stripe';
            //     $checkout_url = url('/pay-for-order') . '/' . $last_id->id;
            //     $payment_id = '';
            // } else if (env('PAYMENT_GATEWAY') == 'Mollie') {
            //     $payment_gateway = 'Mollie';
            //     $payment = mollie()->payments()->create([
            //         "amount" => [
            //             "currency" => env('CURRENCY_CODE'),
            //             "value" => $bill_amount_string
            //         ],
            //         "description" => $order_id,
            //         "redirectUrl" => route('user.orders') . '#current-orders',
            //         "webhookUrl" => env('MOLLIE_PAYMENT_CALLBACK')
            //     ]);
            //     $checkout_url = $payment->_links->checkout->href ?? null;
            //     $payment_id = $payment->id;
            // }
            $default_currency = DB::table('country')->where('is_default', 1)->first();

            $payment_gateway = 'Stripe';
            $checkout_url =  $last_id->id;
            $payment_id = '';

            $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
            $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
            $version = DB::table('settings')->where('key_word', 'stripe_version')->first();
            $stripe = new Stripe($sk->value, $version->value);
            $paymentIntent = $stripe->paymentIntents()->create([
                'amount' => $bill_amount_string,
                'currency' => $default_currency->currency_code,
                'payment_method_types' => [
                    'card',
                ],

            ]);

            if (!isset($paymentIntent['id'])) {
                return false;
            }
            $PaymentID = $paymentIntent['id'];
            $ClientSecret = $paymentIntent['client_secret'];


            DB::table('payments')->insert([
                'request_id' => $last_id->id,
                'payment_gateway' => $payment_gateway,
                'amount' => $bill_amount_string,
                'currency' => $default_currency->currency_code,
                'payment_id' => $payment_id,
                'payment_description' => $order_id
            ]);
        }

        if ($is_cod == 0) {
            // sendNewOrderEmail2Admins($request_id);
            // sendNewOrderEmail2Customer($request_id);
            Restaurant::callRestaurant($res_phone);
        }
        sendNotification(
            [$restaurant_details->device_token],
            trans('constants.notification_restaurant_new_title'),
            trans('constants.notification_restaurant_new_msg')
        );

        $header = array();
        $header[] = 'Content-Type: application/json';
        $postdata = array();
        // $postdata['id'] = $request_id;
        $postdata['user_id'] = $user_id;
        $postdata['request_id'] = $request_id;
        $postdata = json_encode($postdata);

        $ch = curl_init(FIREBASE_URL . "/new_user_request/$user_id.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);

        $header = array();
        $header[] = 'Content-Type: application/json';
        $postdata = array();
        // $postdata['id'] = $request_id;
        $postdata['request_id'] = (string) $request_id;
        $postdata['user_id'] = (float) $user_id;
        $postdata['provider_id'] = "0";
        $postdata['status'] = 0;
        $postdata = json_encode($postdata);

        $ch = curl_init(FIREBASE_URL . "/current_request/$request_id.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);

        //dd("fcm");
        $cart::where('user_id', $user_id)->delete();

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
        //send push notification to user
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



    public function pay_for_order(Request $request)
    {

        $order = DB::table('requests')->where('id', $request->orderID)->first();

        // $default_currency = DB::table('country')->where('is_default', 1)->first();
        // $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
        // $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
        // $version = DB::table('settings')->where('key_word', 'stripe_version')->first();

        // $stripe = new Stripe($sk->value, $version->value);
        // $user_id = $order->user_id;
        // $delivery_address = $order->delivery_address;
        // $suser = DB::table('users')->where('id', $user_id)->first();

        // if ($suser->stripe_id == null) {
        //     $customer = $stripe->customers()->create([
        //         'name' => $suser->name,
        //         'email' => $suser->email,
        //         'address' => [
        //             'line1' => $delivery_address
        //         ]
        //     ]);

        //     DB::table('users')->where('id', $user_id)->update([
        //         'stripe_id' => $customer['id']
        //     ]);

        //     $stripe_custid = $customer['id'];
        // } else {
        //     $customer = $stripe->customers()->find($suser->stripe_id);
        //     $stripe_custid = $customer['id'];
        // }
        // $pms = $stripe->paymentMethods()->all([
        //     'customer' => $stripe_custid,
        //     'type' => 'card',
        // ]);

        $response_array = [
            'status' => true,
            'data' => $order,
            // 'card' => $pms['data']


        ];
        return response()->json($response_array, 200);
    }

    public function check_pay_for_order(Request $request)
    {
        $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
        $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
        $version = DB::table('settings')->where('key_word', 'stripe_version')->first();
        $default_currency = DB::table('country')->where('is_default', 1)->first();
        $stripe = new Stripe($sk->value, $version->value);

        $order = DB::table('requests')->where('id', $request->orderID)->first();
        $restaurant = DB::table('restaurants')->where('id', $order->restaurant_id)->first();
        $res_phone = $restaurant->phone;
        // $charge = $stripe->charges()->create([
        //     'source' => $request->stripeToken,
        //     'currency' => $default_currency->currency_code,
        //     'amount'   => $order->bill_amount,
        //     'source' => 'tok_visa',
        // ]);

        if ($request->redirect_status == "succeeded") {
            DB::table('payments')->where('request_id', $order->id)->update(['payment_id' => $request->payment_intent, 'is_paid' => 1]);
            DB::table('requests')->where('id', $order->id)->update(['status' => 0, 'is_paid' => 1]);
            $url = "trackorder/" . $request->orderID;
            return redirect($url);
            // sendNotification(
            //     [$restaurant->device_token],
            //     trans('constants.notification_restaurant_new_title'),
            //     trans('constants.notification_restaurant_new_msg')
            // );
            // sendNewOrderEmail2Admins($order->id);
            // sendNewOrderEmail2Customer($order->id);
            // Restaurant::callRestaurant($res_phone);
        } else {
            DB::table('requests')->where('id', $order->id)->update(['status' => -2]);
            // sendfailOrderEmail2Admins($order->id);
            $response_array = [
                'status' => false,

            ];
        }
        $response_array = [
            'status' => true,

        ];
        return response()->json($response_array, 200);
    }
    public function signup_form_submit(Request $request)
    {

        $rules = [
            'name' => 'required|max:50',
            'email' => 'required|unique:restaurants,email',
            'phone' => 'required|unique:restaurants,phone',
            'password' => 'required',
            'area' => 'required',
            'address' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = [
                'status' => false,
                'message' => $error_messages

            ];
            return response()->json($response_array, 200);
        }


        $restaurants = $this->restaurants;
        $custom = $this->custom;
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $city = $request->city ?? null;
        $area = $request->area ?? null;
        $discount_type = ''; //$request->discount_type;
        $target_amount = ''; //$request->target_amount;
        $offer_amount = ''; //$request->offer_amount;
        $admin_commision = ''; //$request->admin_commision;
        //$status = $request->status;
        $business_type = $request->business_type;
        $packaging_charge =  $request->packaging_charge;
        //$offer_percentage = $request->offer_percentage;
        $shop_description = $request->shop_description ? $request->shop_description : '';


        $phone_country_calling_code = $request->phone_country_calling_code;
        $phone_country_code = $request->phone_country_code;
        $contact_country_calling_code = $request->contact_country_calling_code;
        $contact_country_code = $request->contact_country_code;

        $estimated_delivery_time = $request->estimated_delivery_time; //$request->estimated_delivery_time;
        $address = $request->address;

        $check_email_phone = $restaurants->where('email', $request->email)->orwhere('phone', $request->phone)->first();
        if ($check_email_phone) {
            $response_array = [
                'status' => false,
                'message' => "Email/Phone already exists"

            ];
            return response()->json($response_array, 200);
        }

        $restaurants->restaurant_name = $name;
        $restaurants->image = ''; //$image;
        $restaurants->banner = $request->banner ?? ''; //$banner;

        $restaurants->email = $email;
        $restaurants->org_password = $request->password;
        $restaurants->password = Hash::make($request->password);
        $restaurants->email_confirmation = md5(time());
        $restaurants->phone = $phone;
        $restaurants->contact = $request->contact;
        $restaurants->city = $city;
        $restaurants->area = $area;
        $restaurants->discount_type = $discount_type;
        $restaurants->target_amount = $target_amount;
        $restaurants->offer_amount = $offer_amount;
        $restaurants->admin_commision = '0'; //$admin_commision;
        $restaurants->restaurant_delivery_charge = '0'; //$request->restaurant_delivery_charge;
        // $restaurants->driver_commision = '0'; //$request->driver_commision;
        //$restaurants->discount = $offer_percentage;
        $restaurants->shop_description = $shop_description;
        $restaurants->is_open = 0;
        $restaurants->estimated_delivery_time = $estimated_delivery_time;
        $restaurants->packaging_charge = $packaging_charge;
        $restaurants->address = $address;
        $restaurants->lat = $request->latitude;
        $restaurants->lng = $request->longitude;
        $restaurants->business_type = $request->business_type;

        $restaurants->phone_country_calling_code = $phone_country_calling_code;
        $restaurants->phone_country_code = $phone_country_code;
        $restaurants->contact_country_calling_code = $contact_country_calling_code;
        $restaurants->contact_country_code = $contact_country_code;



        //Opening closing time
        $restaurants->opening_time_1 = ''; //$opening_time_1;
        $restaurants->closing_time_1 = ''; //$closing_time_1;

        $restaurants->opening_time_2 = ''; //$opening_time_2;
        $restaurants->closing_time_2 = ''; //$closing_time_2;

        $restaurants->opening_time_3 = ''; //$opening_time_3;
        $restaurants->closing_time_3 = ''; //$closing_time_3;

        $restaurants->opening_time_4 = ''; //$opening_time_4;
        $restaurants->closing_time_4 = ''; //$closing_time_4;

        $restaurants->opening_time_5 = ''; //$opening_time_5;
        $restaurants->closing_time_5 = ''; //$closing_time_5;

        $restaurants->opening_time_6 = ''; //$opening_time_6;
        $restaurants->closing_time_6 = ''; //$closing_time_6;

        $restaurants->opening_time_7 = ''; //$opening_time_7;
        $restaurants->closing_time_7 = ''; //$closing_time_7;
        // -- Opening closing time

        $restaurants->status = '0'; //$status;
        $restaurants->delivery_type = json_encode($request->delivery_type); //json_encode($request->delivery_type);
        $restaurants->save();
        //save cuisine
        // $cuisines = $this->cuisines->find($request->cuisines); //dd($cuisines);
        // $restaurants->Cuisines()->sync($cuisines);

        $restaurant_new = $restaurants->where('email', $request->email)->first();

        $rest_plan = DB::table('plans')->where('id', $request->selected_plan)->first();
        $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
        $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
        $default_currency = DB::table('country')->where('is_default', 1)->first();

        $version = DB::table('settings')->where('key_word', 'stripe_version')->first();
        $stripe =  new \Stripe\StripeClient($sk->value, $version->value);
        // $customer = $stripe->customers->create([
        //     'name' => $restaurant_new->restaurant_name,
        //     'email' => $restaurant_new->email,
        //     'address' => [
        //         'line1' => $restaurant_new->address
        //     ]
        // ]);

        // DB::table('restaurants')->where('id', $restaurant_new->id)->update([
        //     'stripe_customer_id' => $customer['id']
        // ]);

        // $stripe_custid = $customer['id'];

        // $sub = $stripe->subscriptions->create([
        //     'customer' => $stripe_custid,
        //     'items' => [
        //         ['price' => $rest_plan->stripe_price_id],
        //     ],
        // ]);

        // dd($sub);


        $stripe = new Stripe($sk->value, $version->value);
        $PaymentID = 0;
        $ClientSecret = 0;
        if ($rest_plan) {
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
                'restaurant' => $restaurant_new->id,
                'payment_gateway' => "Stripe",
                'amount' => $bill_amount_string,
                'currency' => $default_currency->currency_symbol,
                'payment_id' => $PaymentID,
                'payment_description' => $restaurant_new->id
            ]);

            $get = DB::table('restaurant_plan_history')->insertGetId([
                'restaurant_id' => $restaurant_new->id,
                'plan' => $request->selected_plan,
                'transaction_id' => $trans,
                'is_paid' => 0,
                'status' => 0, //0-new,1-submitted,2-expired
            ]);

            DB::table('restaurants')->where('id', $restaurant_new->id)->update([
                'plan' => $get
            ]);
        }

        $mail_enable = DB::table('settings')->where('key_Word', 'email_enable')->first();
        if ($mail_enable->value == 1) {
            $neworder = DB::table('mail_ids')->where("mail_for", "rest_signup")->first();
            $emails = json_decode($neworder->mail_ids);
            $template = DB::table('mail_contents')->where("content_for", "rest_signup")->get();
            $cont = [];
            foreach ($template as $temp) {
                $cont[] = $temp->content;
            }
            $logo = DB::table('settings')->where('key_word', "site_logo")->first();
            $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
            $url = BASE_URL . "verify-restaurant/" . $restaurant_new->id . "/" . $restaurant_new->email_confirmation;
            $html = view('emails.restaurant.restaurant-welcome')->with(['restaurant' => $restaurant_new, 'template' => $cont, 'logo' => $to_logo, 'url' => $url]);
            $Mailer             = new Mailer();
            $Mailer->to         = $restaurant_new->email;
            $Mailer->toName     = $restaurant_new;
            $Mailer->subject    = "Welcome to" . APP_NAME;
            $Mailer->body       = $html;

            $Mailer->send();
        }

        $response_array = [
            'status' => true,
            'message' => "Your Registration is Successful.",
            'restaurant' => $restaurant_new,
            'plan' => $rest_plan,
            'paymentID' => $PaymentID,
            'clientsecret' => $ClientSecret,
            'trans' => $trans ?? 0

        ];
        return response()->json($response_array, 200);
        // $response_array = [
        //     'status' => true,
        //     'message' => "Your Registration is Successful."

        // ];
        // return response()->json($response_array, 200);
    }

    public function stripe_Response(Request $request)
    {
        print($request);
        dd($request);
    }
    public function validate_token(Request $request)
    {
        $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
        $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
        $version = DB::table('settings')->where('key_word', 'stripe_version')->first();
        $stripe = new Stripe($sk->value, $version->value);
        $stripeCustomerId = "cus_O2gxYbInEbWKnb";
        $req_tokem = json_decode($request->token);

        $order_id = $request->orderID;

        $ord = DB::table('requests')->where('id', $order_id)->first();
        $bill_amount = $ord->bill_amount;
        $bill_amount_string = number_format($bill_amount, 2, '.', '');

        $user_id = $ord->user_id;
        $suser = DB::table('users')->where('id', $user_id)->first();
        if ($suser->stripe_id == null) {
            $customer = $stripe->customers()->create([
                'name' => $suser->name,
                'email' => $suser->email,
                'address' => [
                    'line1' => $ord->delivery_address
                ]
            ]);

            DB::table('users')->where('id', $user_id)->update([
                'stripe_id' => $customer['id']
            ]);

            $stripe_custid = $customer['id'];
        } else {
            $customer = $stripe->customers()->find($suser->stripe_id);
            $stripe_custid = $customer['id'];
        }
        $default_currency = DB::table('country')->where('is_default', 1)->first();
        // $card_chk = $stripe->cards()->find($stripe_custid,  $req_tokem->card->id);
        // $cards = $stripe->cards()->all($stripe_custid);
        // dd($req_tokem);

        $exists = 0;

        // foreach($cards['data'] as $car){
        //     if($car['id'] == $req_tokem->card->id){
        //         $exists = 1;
        //     }
        // }

        $card = $stripe->cards()->create($stripe_custid, $req_tokem->id);
        $charge = $stripe->charges()->create([
            'customer' => $stripe_custid,
            'currency' => $default_currency->currency_code,
            'amount'   => $bill_amount_string,
            'description' => 'Food',
            'shipping' => [
                'name' => $suser->name,
                'address' => [
                    'line1' =>  $ord->delivery_address,
                    'postal_code' => '98140',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'country' => 'US',
                ],
            ],
        ]);

        if ($charge['status'] == "succeeded") {
            $status = true;
            DB::table('payments')->where('request_id', $ord->id)->update(['payment_id' => $charge['id'], 'is_paid' => 1]);
            DB::table('requests')->where('id', $ord->id)->update(['status' => 0, 'is_paid' => 1]);
        } else {
            $status = false;
        }
        $response_array = [
            'status' => $status,
        ];
        return response()->json($response_array, 200);
    }

    public function validate_card(Request $request)
    {

        $token = $request->token;
        $order = $request->orderID;
        $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
        $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
        $version = DB::table('settings')->where('key_word', 'stripe_version')->first();
        $default_currency = DB::table('country')->where('is_default', 1)->first();
        $ord = DB::table('requests')->where('id', $order)->first();
        $bill_amount = $ord->bill_amount;
        $bill_amount_string = number_format($bill_amount, 2, '.', '');
        $user_id = $ord->user_id;

        $suser = DB::table('users')->where('id', $user_id)->first();

        $stripe = new Stripe($sk->value, $version->value);
        $charge = $stripe->charges()->create([
            'customer' => $suser->stripe_id,
            'source' => $token,
            'currency' => 'USD',
            'amount'   => $bill_amount_string,
            'description' => 'Food',
            'shipping' => [
                'name' => $suser->name,
                'address' => [
                    'line1' =>  $ord->delivery_address,
                    'postal_code' => '98140',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'country' => 'US',
                ],
            ],
        ]);
        if ($charge['status'] == "succeeded") {
            $status = true;
            DB::table('payments')->where('request_id', $ord->id)->update(['payment_id' => $charge['id'], 'is_paid' => 1]);
            DB::table('requests')->where('id', $ord->id)->update(['status' => 0, 'is_paid' => 1]);
        } else {
            $status = false;
        }
        $response_array = [
            'status' => $status,
        ];
        return response()->json($response_array, 200);
    }
    public function layout(Request $request)
    {

        $data = DB::table('layout')->where('id', '!=', 4)->get();
        return response()->json($data);
    }
    public function verify_restaurant(Request $request)
    {

        $restaurant = DB::table('restaurants')->where('id', $request->id)->first();

        if ($restaurant->email_confirmation == "") {
            $message = "Email already verified";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        } else if ($restaurant->email_confirmation == $request->code) {
            DB::table('restaurants')->where('id', $restaurant->id)->update(['email_confirmation' => '']);
            $message = "Email verified successfully";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        } else {
            $message = "Email not verified";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        }
    }
    public function verify_driver(Request $request)
    {

        $driver = DB::table('delivery_partners')->where('id', $request->id)->first();

        if ($driver->email_confirmation == "") {
            $message = "Email already verified";
            $status = false;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        } else if ($driver->email_confirmation == $request->code) {
            DB::table('delivery_partners')->where('id', $driver->id)->update(['email_confirmation' => '']);
            $message = "Email verified successfully";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        } else {
            $message = "Email not verified";
            $status = true;
            $response_Array = json_encode(['message' => $message, 'status' => $status]);


            return $response_Array;
        }
    }

    public function rest_payment_details(Request  $request)
    {

        // print($request);
        // dd($request);

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
            $url = "https://www.teerya.com/store/";
            return redirect($url);
            // $update_rest = DB::table('restaurants')->where('id',$plan_id->id)->first();

            // $st =  DB::table('restaurant_bank_details')->where('restaurant_id', $rest->restaurant)->first();

            // if($st->stripe_account == 1){
            //     $url = "https://www.teerya.com/store/";
            //     return redirect($url);

            // }

            // $sk = DB::table('settings')->where('key_word','stripe_sk_key')->first();
            // $pk = DB::table('settings')->where('key_word','stripe_pk_key')->first();
            // $version = DB::table('settings')->where('key_word','stripe_version')->first();
            // $stripe =  new \Stripe\StripeClient($sk->value, $version->value);


            // $acc = $stripe->accounts->create(
            //     [
            //       'country' => 'NL',
            //       'type' => 'express',
            //       'capabilities' => [
            //         'card_payments' => ['requested' => true],
            //         'transfers' => ['requested' => true],
            //       ],
            //       'business_type' => 'individual',


            //     ]
            //   );

            //       $update = DB::table('restaurant_bank_details')->where('restaurant_id',$rest->restaurant)->update([

            //         'stripe'=>$acc->id,
            //         'connect_status'=>0 //0-new,1-verified
            //       ]);

            //   $acc_link =  $stripe->accountLinks->create([
            //     'account' => $acc->id,
            //     'refresh_url' => 'https://www.teerya.com/store/',
            //     'return_url' => 'https://www.teerya.com/store/',
            //     'type' => 'account_onboarding',
            //   ]);

            //   $url = "https://www.teerya.com/store/";
            //   return redirect($url);

            //   $response_array = [
            //     'status' => true,
            //     'url'=>$acc_link->url
            // ];
            // return response()->json($response_array, 200);
            //   $acc_link->url;
        } else {
            $response_array = [
                'status' => false,

            ];
            return response()->json($response_array, 200);
        }
    }

    public function restaurant_subscription(Request $request)
    {
        if ($request->status == 1) {
            DB::table('restaurants')->where('id', $request->id)->update([
                'is_subscribed' => 1
            ]);
        } else {
            DB::table('restaurants')->where('id', $request->id)->update([
                'is_subscribed' => 0
            ]);
        }
        $url = "https://www.teerya.com/store/";

        return redirect($url);
    }

    public function single_restaurant_products(Request $request)
    {
        $business_id = $request->id;
        $source_lat = $request->lat;
        $source_lng = $request->lng;

        if ($source_lat == "null") {
            $source_lat = DEFAULT_LATITUDE;
        }
        if ($source_lng == "null") {
            $source_lng = DEFAULT_LONGITUDE;
        }


        $nearbyRestaurantIDs = Restaurant::nearDistance_business_rest($source_lat, $source_lng, $business_id);
        $restaurants = $this->restaurants;
        $data = $restaurants::where('id', $nearbyRestaurantIDs[0])->first();
        if ($data) {
            $is_open = restaurant_is_open($data->id);
            $rating_statistics = get_rating_statistics($nearbyRestaurantIDs[0]);
            $delivery_charge_limits = DB::table('delivery_charge_limits')->select('Minimum', 'Maximum', 'Value', 'Type')
                ->where('restaurant_id', $data->id)->get()->toArray();
            $tax1 = DB::table('settings')->where('key_word', 'tax_inclusive')->first();
            $tax = $tax1->value;
            $restaurant_list = array(
                'id'            => $data->id,
                'name' =>  $this->secondLanguage_user($data->restaurant_name, $data->restaurant_secondary_name),
                'phone' => $data->phone,
                'email' => $data->email,
                'delivery_type'          => json_decode($data->delivery_type),
                'address'       => $data->address,
                'lat'     => $data->lat,
                'lng'     => $data->lng,
                'discount'      => $data->discount,
                'rating'        => (float) $rating_statistics['rating_percent'],
                'is_open'       => $is_open,
                'travel_time'   => $data->estimated_delivery_time,
                'opening_time_1' => $data->opening_time_1,
                'closing_time_1' => $data->closing_time_1,
                'opening_second_time_1' => $data->opening_second_time_1,
                'closing_second_time_1' => $data->closing_second_time_1,
                'opening_time_2' => $data->opening_time_2,
                'closing_time_2' => $data->closing_time_2,
                'opening_second_time_2' => $data->opening_second_time_2,
                'closing_second_time_2' => $data->closing_second_time_2,
                'opening_time_3' => $data->opening_time_3,
                'closing_time_3' => $data->closing_time_3,
                'opening_second_time_3' => $data->opening_second_time_3,
                'closing_second_time_3' => $data->closing_second_time_3,
                'opening_time_4' => $data->opening_time_4,
                'closing_time_4' => $data->closing_time_4,
                'opening_second_time_4' => $data->opening_second_time_4,
                'closing_second_time_4' => $data->closing_second_time_4,
                'opening_time_5' => $data->opening_time_5,
                'closing_time_5' => $data->closing_time_5,
                'opening_second_time_5' => $data->opening_second_time_5,
                'closing_second_time_5' => $data->closing_second_time_5,
                'opening_time_6' => $data->opening_time_6,
                'closing_time_6' => $data->closing_time_6,
                'opening_second_time_6' => $data->opening_second_time_6,
                'closing_second_time_6' => $data->closing_second_time_6,
                'opening_time_7' => $data->opening_time_7,
                'closing_time_7' => $data->closing_time_7,
                'opening_second_time_7' => $data->opening_second_time_7,
                'closing_second_time_7' => $data->closing_second_time_7,
                'delivery_charge_limits' => json_encode($delivery_charge_limits),
                'packaging_charge' => $data->packaging_charge,
                'DeliveryChargeBasedOn' => $data->DeliveryChargeBasedOn,
                'offer_discount' => $data->offer_discount,
                'delivery_charge_type' => $data->delivery_charge_type ? $data->delivery_charge_type : 1,
                'tax' => $tax,


            );
        } else {
            $restaurant_list = [];
        }
        $featured = featured($nearbyRestaurantIDs[0]);
        $recent = recent($nearbyRestaurantIDs[0]);
        // $recent = [];

        $response_array = [
            'status' => true,
            'featured' => $featured,
            'recent' => $recent,
            'restaurant_details' => $restaurant_list

        ];

        return response()->json($response_array, 200);
    }

    public function get_product_details(Request $request)
    {
        $business_id = $request->business_id;
        $category_id = $request->category_id;
        $source_lat = $request->lat;
        $source_lng = $request->lng;

        if ($source_lat == "null") {
            $source_lat = DEFAULT_LATITUDE;
        }
        if ($source_lng == "null") {
            $source_lng = DEFAULT_LONGITUDE;
        }


        $nearbyRestaurantIDs = Restaurant::nearDistance_business_rest($source_lat, $source_lng, $business_id);
        $restaurants = $this->restaurants;
        $data = $restaurants::where('id', $nearbyRestaurantIDs[0])->first();

        $product_id = $request->id;
        $product = product_details($product_id, $data);
        // $restaurant = $product->restaurant_id;
        $restaurant = $data->id??'';
        $rest_details = DB::table('restaurants')->where('id', $restaurant)->first();
        $specs = DB::table('product_specification')->where('product_id', $product_id)->get();
        $faq = DB::table('product_faq')->where('product_id', $product_id)->get();
        $tables = DB::table('table_type')->where('product_id', $product_id)->get();

        $product_banner = DB::table('offers_banner')
            ->leftjoin('restaurants', 'restaurants.id', '=', 'offers_banner.restaurant_id')
            ->leftjoin('add_city', 'add_city.id', '=', 'offers_banner.city_id')
            ->select('restaurants.restaurant_name as restaurant_name', 'add_city.city as city_name', 'offers_banner.image as banner_image', 'offers_banner.position as banner_position', 'offers_banner.status as banner_status', 'offers_banner.banner_type as banner_type', 'offers_banner.is_suffle as banner_suffle', 'offers_banner.id as banner_id')
            ->where('offers_banner.banner_type', 2)
            ->where('offers_banner.category', $category_id)
            ->get();

        foreach ($product_banner as $d) {
            $d->banner_image = BASE_URL . UPLOADS_PATH . $d->banner_image;
        }

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
        $response_array = [
            'status' => true,
            'product' => $product,
            'product_banner' => $product_banner,
            'restaurant' => $rest_details,
            'specs' => $specs,
            'faq' => $faq,
            'table' => $table,
            'cart_status' => $rest_details->cart??0

        ];
        return response()->json($response_array, 200);
    }

    public function category_products(Request $request)
    {
        $cat_id = $request->id;
        $business_id = $request->business_id;

        $source_lat = $request->lat;
        $source_lng = $request->lng;
        $data = DB::table('business_category')->where('status', 1)->where('id', $cat_id)->first();

        $nearbyRestaurantIDs = Restaurant::nearDistance_business_rest($source_lat, $source_lng, $business_id);

        $restaurants = $this->restaurants;


        $cat_products = cat_products($nearbyRestaurantIDs[0], $cat_id);
        $rt_cat = [];
        $i = 0;
        foreach ($cat_products as $cp) {
            if ($i <= 10) {
                $rt_cat[] = $cp;
            }
            $i++;
        }
        $response_array = [
            'status' => true,
            'cat_products' => $rt_cat,
            'title' => $data->category_name,
            'rest_details' => $rest

        ];
        return response()->json($response_array, 200);
    }

    public function sub_category_products(Request $request)
    {
        $cat_id = $request->id;
        $business_id = $request->business_id;

        $source_lat = $request->lat;
        $source_lng = $request->lng;
        // $data = DB::table('category')->where('id', $cat_id)->first();
        $data = DB::table('business_category')->where('status', 1)->where('id', $cat_id)->first();

        $nearbyRestaurantIDs = Restaurant::nearDistance_business_rest($source_lat, $source_lng, $business_id);

        $restaurants = $this->restaurants;


        $cat_products = get_subcat_products($nearbyRestaurantIDs[0], $cat_id);

        $response_array = [
            'status' => true,
            'cat_products' => $cat_products,
            'title' => $data->category_name

        ];
        return response()->json($response_array, 200);
    }


    public function get_pages(Request $request)
    {
        $pages = DB::table('home_page')
            // ->where('is_editable', 1)
            ->orderby('rank', 'asc')
            ->get();

        $status = true;
        $response_Array = json_encode(['pages' => $pages, 'status' => $status]);
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

    public function enquire_now(Request $request)
    {
        $user_id = $request->user_id;
        $products = explode(',',  $request->food_id);
        $quantity = explode(',',  $request->food_qty);

        $lat = $request->lat;
        $lng = $request->lng;

        $rest = DB::table('restaurants')->first();
        $insert = DB::table('enquiry')->insertGetId([
            'user_id' => $user_id,
            'lat' => $lat,
            'lng' => $lng,
            'products' => $request->food_id,
            'quantity' => $request->food_qty,
            'created_at' => Carbon::now()
        ]);

        $mail_enable = DB::table('settings')->where('key_Word', 'email_enable')->first();
        if ($mail_enable->value == 1) {
            $user = DB::table('users')->where('id', $user_id)->first();
            $rt_food = [];
            $i = 0;
            foreach ($products as $fl) {
                $prod = DB::table('food_list')->where('id', $fl)->first();
                $rt_food[] = [
                    'Name' => $prod->name,
                    'description' => $prod->description,
                    'quantity' => $quantity[$i],
                ];
                $i++;
            }

            $url = BASE_URL . "/admin/new";

            $template = DB::table('mail_contents')->where("content_for", "enquirymail")->get();
            $cont = [];
            foreach ($template as $temp) {
                $cont[] = $temp->content;
            }
            $logo = DB::table('settings')->where('key_word', "site_logo")->first();
            $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
            $html = view('emails.user.enquiry')->with(['user' => $user, 'food' => $rt_food, 'content' => $cont, 'logo' => $to_logo, 'url' => $url]);
            $Mailer = new Mailer();
            $Mailer->to         = $rest->email;
            $Mailer->toName     = $rest->restaurant_name;
            $Mailer->subject     = "Welcome";
            $Mailer->body       = $html;
            $Mailer->send();
        }
        $status = true;
        $response_Array = json_encode(['status' => $status]);
        return $response_Array;
    }

    public function sitemap_xml(Request $request)
    {



        $products = DB::table('food_list')->where('status', 1)->get();
        $return_rest = [];

        foreach ($products as $prd) {

            $c_at = Carbon::parse($prd->updated_at)->toIso8601String();
            $return_rest[] = [
                'name' => $prd->name,
                'slug' => strtolower(BASE_URL . 'product/' . str_replace(' ', '-', $prd->name) . '-' . $prd->id),
                'created_at' => $c_at
            ];
        }

        $s_pages = DB::table('header_page_contents')->where('status', 1)->get();

        foreach ($s_pages as $sp) {
            $c_at = Carbon::parse($sp->updated_at)->toIso8601String();

            $return_rest[] = [
                'name' => $sp->name,
                'slug' => strtolower(BASE_URL . 'pages/' . str_replace(' ', '-', $sp->name) . '-' . $sp->id),
                'created_at' => $c_at
            ];
        }

        return response()->view('sitemap', [
            'restaurants' => $return_rest
        ])->header('Content-Type', 'text/xml');
    }


    public function allCityList()
    {
        // $city_list = DB::table('add_city')->where('status', 1)->get();
         $city_list = DB::table('add_city')
        ->join('restaurants', 'add_city.id', '=', 'restaurants.city')
        ->where('add_city.status', 1)
        ->select('add_city.*')
        ->distinct()
        ->get();

        return response()->json($city_list);
    }

    public function getRestaurants($id)
    {
        $cityId = $id;

        $restaurants = DB::table('restaurants')
            ->where('city', $cityId)
            ->get();

        $restaurantList = [];

        // Loop through each restaurant and prepare an array with desired fields.
        foreach ($restaurants as $data) {
            $restaurantList[] = [
                'id'            => $data->id,
                'name' =>  $this->secondLanguage_user($data->restaurant_name, $data->restaurant_secondary_name),
                'phone' => $data->phone,
                'email' => $data->email,
                'delivery_type'          => json_decode($data->delivery_type),
                'address'       => $data->address,
                'lat'     => $data->lat,
                'lng'     => $data->lng,
                'discount'      => $data->discount,
                'is_open'       => $data->is_open,
                'travel_time'   => $data->estimated_delivery_time,
                'opening_time_1' => $data->opening_time_1,
                'closing_time_1' => $data->closing_time_1,
                'opening_second_time_1' => $data->opening_second_time_1,
                'closing_second_time_1' => $data->closing_second_time_1,
                'opening_time_2' => $data->opening_time_2,
                'closing_time_2' => $data->closing_time_2,
                'opening_second_time_2' => $data->opening_second_time_2,
                'closing_second_time_2' => $data->closing_second_time_2,
                'opening_time_3' => $data->opening_time_3,
                'closing_time_3' => $data->closing_time_3,
                'opening_second_time_3' => $data->opening_second_time_3,
                'closing_second_time_3' => $data->closing_second_time_3,
                'opening_time_4' => $data->opening_time_4,
                'closing_time_4' => $data->closing_time_4,
                'opening_second_time_4' => $data->opening_second_time_4,
                'closing_second_time_4' => $data->closing_second_time_4,
                'opening_time_5' => $data->opening_time_5,
                'closing_time_5' => $data->closing_time_5,
                'opening_second_time_5' => $data->opening_second_time_5,
                'closing_second_time_5' => $data->closing_second_time_5,
                'opening_time_6' => $data->opening_time_6,
                'closing_time_6' => $data->closing_time_6,
                'opening_second_time_6' => $data->opening_second_time_6,
                'closing_second_time_6' => $data->closing_second_time_6,
                'opening_time_7' => $data->opening_time_7,
                'closing_time_7' => $data->closing_time_7,
                'opening_second_time_7' => $data->opening_second_time_7,
                'closing_second_time_7' => $data->closing_second_time_7,
                'packaging_charge' => $data->packaging_charge,
                'DeliveryChargeBasedOn' => $data->DeliveryChargeBasedOn,
            ];
        }

        // Return status true if list is not empty, otherwise false.
        $status = !empty($restaurantList);

        return response()->json([
            'status' => $status,
            'data'   => $restaurantList
        ]);
    }
}
