<?php

namespace App\Http\Controllers\store;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use App\Model\Deliverypartners;
use DB;

class DriverController extends BaseController
{

    public function accept_delivery_boy_details($id, $driver_id, Request $request)
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
       
            return view('admin/accept_deliver_boy', [
                'insert1' => $delivery_partner_detail,
                'insert' => $delivery_partner_details,
                'city' => $city,
                'area' => $area,
                'vehicle' => $vehicle,
                'restaurants' => $restaurants
            ])->with('delivery_partner_commision', $delivery_partner_detail->partner_commision)->with('profile_icon', $profile_pic);
        
    }

    public function accept_driver(Request $request)
    {

        $conditions = [
            'phone_no' => 'required',
            'status' => 'required',
        ];


        $validator = Validator::make($request->all(), $conditions);

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('error', $error_messages)->withInput();
        }


        $insert1 = Deliverypartners::find($request->id);

        $insert1->status = $request->status;
        $insert1->update();
        $returnMsg = $request->id ? trans('constants.driver_updated_succesfully') :  trans('constants.driver_added_successfully');

        return back()->with('success', $returnMsg);
    }
}
