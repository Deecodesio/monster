<?php

namespace App\Http\Controllers\admin;
                                    
use Validator;
use Hash;
use DB;
use Illuminate\Http\Request;
use App\Model\deliverypartners;
use App\Model\driver_partner_details;
use App\Model;


use App\Http\Controllers\Controller;
use App\Http\Controllers\api\BaseController;

class DriverController extends BaseController
{

    public function driver_list(Request $request){

        // $data = DB::table('delivery_partners')
        //         ->get();


                
        $data = $this->deliverypartners->where('status', '!=', 3)->with('Foodrequest')->get();
        
        foreach ($data as $d) {
            $driver_id = $this->driver_partner_details->where('delivery_partners_id', $d->id)->first();
            if (isset($driver_id)) {
                $d->driver_id = $driver_id->id;
            } else {
                $d->driver_id = 0;
            }
            if(file_exists(public_path('/') .$d->profile_pic)) {
				$d->profile_pic = BASE_URL . $d->profile_pic;
			} else {
				$d->profile_pic = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
			}
            $d->name =$this->secondLanguage( $d->name,$d->second_driver_name);
        }

        $all_drivers = $this->deliverypartners->count('id');

        $active_drivers = $this->deliverypartners->where('status', 1)->count('id');

        $in_active_drivers = $this->deliverypartners->where('status', 0)->count('id');
       
        // dd($data);
            // return view('admin/driver_list', ['data' => $data, 'all_drivers' => $all_drivers, 'active_drivers' => $active_drivers, 'in_active_drivers' => $in_active_drivers]);
            $message = "Add Successfully";
            $status = true;
            // $data = $request->id;
            $response_Array = json_encode(['message'=>$message,'status'=>$status,'data'=> $data, 'all_drivers' => $all_drivers, 'active_drivers' => $active_drivers, 'in_active_drivers' => $in_active_drivers]);
            return $response_Array;

    }

}