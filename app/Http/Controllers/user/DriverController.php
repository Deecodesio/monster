<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\web\BaseController;
use App\Model\Requestdetail;
use App\Model\RequestdetailAddons;
use App\Http\Controllers\Mailer;
use App\Http\Controllers\PaymentGateway\EasyCheckout;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
//use Validator;
use Mollie\Laravel\Facades\Mollie;
use Cartalyst\Stripe\Stripe;
use Illuminate\Support\Facades\Validator;
use Dotenv\Validator as DotenvValidator;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Validator as ValidationValidator;

class DriverController extends BaseController
{

    public function signup_form(Request $request)
    {

       
        $city = $this->addcity->get();
        $vehicle = $this->vehicle->get();
        $restaurants = DB::table('restaurants')->select('id', 'restaurant_name')
            ->where('status', 1)
            ->orderBy('restaurant_name', 'asc')
            ->get();
        $document = $this->document->where('document_for', 1)->where('status', 1)->get();
       

        $city = $this->addcity->get();
        //$cuisines = $this->cuisines->get();

        return view('user.new-driver', [
            'city' => $city,
            //'area' => $area, 
            'vehicle' => $vehicle, 
            'area' => [], 
            'restaurants' => $restaurants,
            'document' => $document
        ]);
    }
    public function signup_form_submit(Request $request)
    {
        //echo $request;


        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            //'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            //'license' => 'required',
            'password' => 'required'
        ]);


        if ($validator->fails()) {

            //  $error_messages = implode(',',$validator->messages()->all());

            return back()->with('error', " error");
        } else {
            if ($request->phone) {
                $duplicate_check = $this->deliverypartners->where('phone', $request->phone)->first();
                if (!empty($duplicate_check)) {
                    return back()->with('error', 'Phone number already exists')->withInput();
                }
            }
            $deliverypartners = $this->deliverypartners;
            $custom = $this->custom;
            $name = $request->name;
            $phone = $request->phone;
            $email = $request->email;
            $country = $request->country;
            $state_province = $request->state;
            $city = $request->city;
            $address = $request->address;
            $password = $this->encrypt_password_driver($request->password);
            $zipcode = $request->zipcode;
            $authToken = "";
            /* if ($request->license) {
                $license = $custom->restaurant_upload_image($request, 'license');
            }*/
            if ($request->file('profile_pic')) {
                //$profile_picture = self::base_image_upload_profile($request, 'profile_pic');
                $profile_picture = $custom->driver_image_upload($request, 'profile_pic');
                // dd($profile_picture);
            }

            $data = array();

            $data[] = array(
                'partner_id' => $this->generate_partner_id(),
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'password' => $password,
                'address' => $address,
                //'license' => $license,
                'status' => 3,
                //'country'=>$country,
                //'state_province'=>$state_province,
                'profile_pic' => $profile_picture,
            );

            $deliverypartners->insert($data);
            $id =  DB::table('delivery_partners')->orderBy('id', 'desc')->first();
            $id->id;
            if ($id->id) {
                $delivery_detail = $this->driver_partner_details;
                $data1[] = array(
                    'delivery_partners_id' => $id->id,
                    'city' => $city,
                    'state_province' => $state_province,
                    'country' => $country,
                    'zip_code' => $zipcode,
                    'address_line_1' => $address
                );

                $delivery_detail->insert($data1);
                if ($delivery_detail) {

                    $driver_new = $deliverypartners->where('email', $request->email)->first();
                    echo  $driver_new;
                    $html = view('emails.deliverpartner.driver-welcome')->with(['driver' => $driver_new]);

                    $Mailer = new Mailer();
                    $Mailer->to         = $driver_new->email;
                    $Mailer->toName     = $driver_new->name;
                    $Mailer->subject     = "Welcome";
                    $Mailer->body       = $html;
                    $Mailer->send();

                    //admin mail
                    $admin =  DB::table('admin')->orderBy('id', 'asc')->first();

                    $html = view('emails.deliverpartner.driver-active')->with(['driver' => $admin]);

                    $Mailer = new Mailer();
                    $Mailer->to         = $admin->email;
                    $Mailer->toName     = 'Admin';
                    $Mailer->subject     = "Welcome";
                    $Mailer->body       = $html;
                    $Mailer->send();
                }
            }
        }

        return back()->with('success', 1);
    }
}
