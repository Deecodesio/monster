<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\api\BaseController;
use DB;
use Illuminate\Http\Request;
use Validator;

class DeliverypeopleController extends BaseController
{
    public function deliverypeople_list(Request $request)
    {
        // echo $request->ip(); exit;
        $data = DB::table('delivery_partners')->where('status', 3)->get();
        //$delete =  $this->driver_partner_details::get();

        foreach ($data as $d) {
            $driver_id = $this->driver_partner_details->where('delivery_partners_id', $d->id)->first();
            if (isset($driver_id)) {
                $d->driver_id = $driver_id->id;
            } else {
                $d->driver_id = 0;
            }

            if(file_exists(public_path('/'). $d->profile_pic)) {
				$d->profile_pic = BASE_URL . $d->profile_pic;
			} else {
				$d->profile_pic= BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
			}
            // $d->profile_pic = BASE_URL.$d->profile_pic;
        }
        
        $message = $data;
        $status = true;
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);


        return $response_Array;
        // $privilege = session()->get('privilege');
        // $permission = permission($privilege, 'driver-view');
        // if ($permission == 1 || $privilege == -1) {
        //     return view('admin/deliverypeople_list', ['data' => $data]);
        // } else {
        //     return view('admin/restricted');
        // }
    }

    public function add_deliverypeople()
    {
        $settings = $this->settings;

        $delivery_partner_commision = $settings->where('key_word', 'delivery_boy_commission')->first();

        $profile_icon = BASE_URL.PROFILE_ICON;

        return view('admin/add_deliverypeople')->with('delivery_partner_commision', $delivery_partner_commision->value)->with('profile_icon', $profile_icon);
    }

    public function edit_delivery_partner($id, Request $request)
    {
        $deliverypartners = $this->deliverypartners;

        $delivery_partner_detail = $deliverypartners->find($id);
        $profile_pic = BASE_URL.$delivery_partner_detail->profile_pic;

        $data = DB::table('delivery_partners')
        ->leftJoin('delivery_partner_details','delivery_partner_details.delivery_partners_id','=','delivery_partners.id')
        ->where('delivery_partners.id',$id)
        ->select('delivery_partner_details.*','delivery_partners.*','delivery_partner_details.bank_name')
        ->first();
        $document = DB::table('driver_document')->where('driver_id',$id)->get();
       
        foreach($document as $documents)
        {
            if ($documents->document != "") {
                $documents->document =  BASE_URL . $documents->document;
            }
            else{
                $documents->document =  BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
            }
        }
       
        if ($data->profile_pic != "") {
            $data->profile_pic =  BASE_URL  . $data->profile_pic;
        }

        if ($data->city == null && $data->city == 0) {
            $data->city =  '';
        }
        else{
            $data->city = (int)$data->city;
        }

        if ($data->vehicle_name == null) {
            $data->vehicle_name =  '';
        }
        else{
            $data->vehicle_name = (int)$data->vehicle_name;
        }

        if ($data->area == null) {
            $data->area =  '';
        }
        else{
            $data->area = (int)$data->area;
        }

        if ($data->restaurant_id == null) {
            $data->restaurant_id =  '';
        }
        if ($data->address_line_1 == null) {
            $data->address_line_1 =  '';
        }
        if ($data->address_line_2 == null) {
            $data->address_line_2 =  '';
        }
        if ($data->status == 0) {
            $data->status =  '';
        }

        if($data->services != null || $data->services!= '')
        {
            $integerIDs = array_map('intval', json_decode($data->services, true));
        
            $data->services =$integerIDs;
        }

        
        if($data)
        {
            $data->second_driver_name = $this->secound_name($data->second_driver_name);
            $data->second_address_line_1 =$this->secound_name($data->second_address_line_1);
            $data->second_address_line_2 =$this->secound_name($data->second_address_line_2);
            $data->second_address_city =$this->secound_name($data->second_address_city);
            $data->second_state_province =$this->secound_name($data->second_state_province);
            $data->second_country =$this->secound_name($data->second_country);

            $data->name =$this->secound_name($data->name);
            $data->account_name =$this->secound_name($data->account_name);
            $data->account_address =$this->secound_name($data->account_address);
            $data->account_no =$this->secound_name($data->account_no);
            $data->bank_name =$this->secound_name($data->bank_name);
            $data->branch_name =$this->secound_name($data->branch_name);
            $data->branch_address =$this->secound_name( $data->branch_address);
            $data->swift_code =$this->secound_name($data->swift_code);
            $data->routing_no =$this->secound_name($data->routing_no);
            $data->address_line_1 =$this->secound_name($data->address_line_1);
            $data->address_line_2 =$this->secound_name($data->address_line_2);
            $data->address_city =$this->secound_name( $data->address_city);
            $data->state_province =$this->secound_name($data->state_province);
            $data->country =$this->secound_name($data->country);
        } 
        $message = "Delivery Partner Removed Successfully";
        $status = true;
        $response_Array = json_encode(['message'=>$message,'status'=>$status,'data' => $data,'document'=>$document]);
        return $response_Array;

        // return view('admin/add_deliverypeople', ['delivery_partner' => $delivery_partner_detail])->with('delivery_partner_commision', $delivery_partner_detail->partner_commision)->with('profile_icon', $profile_pic);
    }

    public function add_to_deliverypeople(Request $request)
    {
        if ($request->id) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:50',
                // 'email' => 'required|email',
                'commision' => 'required',
                'address' => 'required',
                'driving_license_no' => 'required',
                'service_zone' => 'required',
                'bank_name' => 'required',
                'acc_no' => 'required',
                'ifsc_code' => 'required',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:50',
                'phone' => 'required|numeric|digits_between:6,13',
                'country_code' => 'required|numeric',
                // 'email' => 'required|email',
                'commision' => 'required',
                'address' => 'required',
                'driving_license_no' => 'required',
                'service_zone' => 'required',
                'bank_name' => 'required',
                'acc_no' => 'required',
                'ifsc_code' => 'required',
                'profile_pic' => 'required|mimes:jpeg,jpg,bmp,png|max:2048',
            ]);
        }

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());

            return back()->with('error', $error_messages);
        } else {
            $deliverypartners = $this->deliverypartners;
            $custom = $this->custom;
            $name = $request->name;
            $phone = $request->phone;
            $country_code = $request->country_code;
            $service_zone = $request->service_zone;
            $bank_name = $request->bank_name;
            $acc_no = $request->acc_no;
            $ifsc_code = $request->ifsc_code;
            $driving_license_no = $request->driving_license_no;
            $email = $request->email ? $request->email : '';
            $password = $this->encrypt_password($request->password);
            $authToken = '';
            $commision = $request->commision;
            $address = $request->address;
            if ($request->profile_pic) {
                $profile_pic = $custom->restaurant_upload_image($request, 'profile_pic');
            }
            // else
            // {

            // 	$profile_pic=PROFILE_ICON;

            // }

            $data = [];

            if ($request->id) {
                if (! $request->profile_pic) {
                    $check = $deliverypartners->where('id', $request->id)->first();
                    $profile_pic = $check->profile_pic;
                }

                $deliverypartners->where('id', $request->id)->update([
                    'name' => $name, 'email' => $email,
                    'address' => $address,
                    'profile_pic' => $profile_pic,
                    'partner_commision' => $commision,
                    'service_zone' => $service_zone,
                    'driving_license_no' => $driving_license_no,
                    'bank_name' => $bank_name,
                    'acc_no' => $acc_no,
                    'ifsc_code' => $ifsc_code,
                ]);
            } else {
                $data[] = [
                    'partner_id' => $this->generate_partner_id(),
                    'name' => $name,
                    'phone' => $country_code.$phone,
                    'email' => $email,
                    'password' => $password,
                    'address' => $address,
                    'profile_pic' => $profile_pic,
                    'partner_commision' => $commision,
                    'service_zone' => $service_zone,
                    'driving_license_no' => $driving_license_no,
                    'bank_name' => $bank_name,
                    'acc_no' => $acc_no,
                    'ifsc_code' => $ifsc_code,
                ];

                $deliverypartners->insert($data);
            }
            if (session()->get('role') == 2) {
                return redirect('/store/deliverypeople_list')->with('success', 'Delivery Boy Added');
            } else {
                return redirect('/admin/deliverypeople_list')->with('success', 'Delivery Boy Added');
            }
        }
    }

    public function delete_delivery_partner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        // dd($request);
        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());

                $message = $error_messages;
                $status = false;
                $response_Array = json_encode(['message'=>$message,'status'=>$status]);
                return $response_Array;
        } else {
            $id = $request->id;
           
            $deliverypartners = $this->deliverypartners;

            $deliverypartners->where('id', $id)->delete();

            $id1 = $request->driver_id;
            $delivery = $this->driver_partner_details;

            $delivery->where('id', $id1)->delete();
           
                // return redirect('/admin/deliverypeople_list')->with('success', 'Delivery Partner Removed');
                $message = "Delivery Partner Removed Successfully";
                $status = true;
                $response_Array = json_encode(['message'=>$message,'status'=>$status]);
                return $response_Array;
        }
    }
}
