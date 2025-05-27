<?php

namespace App\Http\Controllers\web;
                                    
use Validator;
use Hash;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\web\BaseController;
use App\Model\DriverDocument;
use App\Http\Controllers\Mailer;

class DriverController extends BaseController
{

    public function driver_list(Request $request){

        $data = DB::table('delivery_partners')
                ->get();

        return response()->json($data);

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
            // 'license' => 'required',
            'password' => 'required'
        ]);


        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());
            $response_array = [
                'status' => false,
                'message' => $error_messages
                
            ];
            return response()->json($response_array, 200);
        } else {
            $old = DB::table('delivery_partners')->where('email', $request->email)->first();
            if (!empty($old)) {
                // return back()->with('error', 'Phone number already exists')->withInput();
                $message = "Email already exists";
                $status = false;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }

            $oldp = DB::table('delivery_partners')->where('phone', $request->phone)->first();

            if (!empty($oldp)) {
                // return back()->with('error', 'Phone number already exists')->withInput();
                $message = "Phone number already exists";
                $status = false;
                $response_Array = json_encode(['message' => $message, 'status' => $status]);
                return $response_Array;
            }
            $deliverypartners = $this->deliverypartners;
            $custom = $this->custom;
            $name = $request->name;
            $phone = $request->phone;
            $country_code = $request->phone_country_code;
            $country_calling_code = $request->phone_country_calling_code;
            $email = $request->email;
            $country = $request->country;
            $state_province = $request->state;
            $city = $request->city;
            $address = $request->address;
            $password = $this->encrypt_password($request->password);
            $org_password = $request->password;
            $zipcode = $request->zipcode;
            $authToken = "";
            $license = "";
            $gender = $request->gender;
            $array_delivery = explode(',', $request->services);
            $service = json_encode($array_delivery);

         

            $data = array();

            $data[] = array(
                'partner_id' => $this->generate_partner_id(),
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'password' => $password,
                'org_password' => $org_password,
                'address' => $address,
                'license' => $license,
                'country_code' => $country_code,
                'country_calling_code' => $country_calling_code,
                'status' => 3,
                'email_confirmation' => md5(time())
                //'country'=>$country,
                //'state_province'=>$state_province,
            );

            $deliverypartners->insert($data);
            $id =  DB::table('delivery_partners')->orderBy('id', 'desc')->first();
            $id->id;
            if ($id->id) {
                $delivery_detail = $this->driver_partner_details;
                $data1[] = array(
                    'delivery_partners_id' => $id->id,
                    'address_city' => $city,
                    'state_province' => $state_province,
                    'country' => $country,
                    'zip_code' => $zipcode,
                    'address_line_1' => $address,
                    'gender'=>$gender,
                    'services' =>  $service,
                );

                $delivery_detail->insert($data1); 
              
                if ($request->license) {
                    $licenses = $request->license;
                    $exp_date = $request->license_expiry;
                    $document_id = $request->doc_id;
                    $expdate_id = $request->exp_id;
                    for ($i = 0; $i < count($licenses); $i++) {
                        $dri_doc = new DriverDocument();
                        if($licenses[$i] != 'undefined')
                        {
                            $license = $custom->base_image_upload_license($licenses[$i]); //dd($license);

                        }
                        $dri_doc->driver_id =  $id->id;
                        $dri_doc->document_id = $document_id[$i];
                        $dri_doc->document = $license;
                        //dd($dri_doc);
                        $dri_doc->save();
                        if($exp_date != null){

                       
                        for ($e = 0; $e < count($exp_date); $e++) {
                            if ($document_id[$i] ==  $expdate_id[$e]) {
                                //dd($exp_date[$e]);
                                $doc = DB::table('driver_document')->where('driver_id', $id->id)->where('document_id', $document_id[$i])
                                    ->update(['expiry_date' => $exp_date[$e]]);
                         }
                        }
                    }
                    }
                }
                if ($delivery_detail) {
                    $mail_enable = DB::table('settings')->where('key_Word','email_enable')->first();
                    if($mail_enable->value == 1){
                        $neworder = DB::table('mail_ids')->where("mail_for", "driver_signup")->first();
                        $emails = json_decode($neworder->mail_ids);
                        $template = DB::table('mail_contents')->where("content_for", "driver_signup")->get();
                        $cont = [];
                        foreach ($template as $temp) {
                            $cont[] = $temp->content;
                        }
    
                        $logo = DB::table('settings')->where('key_word', "site_logo")->first();
                        $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
                        $driver_new = $deliverypartners->where('email', $request->email)->first();
                        $url = BASE_URL . "rider-verify/" . $driver_new->id . "/" . $driver_new->email_confirmation;
    
                        $html = view('emails.deliverpartner.driver-welcome')->with(['driver' => $driver_new, 'template' => $cont, 'logo' => $to_logo, 'url' => $url]);
    
                        $Mailer = new Mailer();
                        $Mailer->to         = $driver_new->email;
                        $Mailer->toName     = $driver_new->name;
                        $Mailer->subject     = "Welcome";
                        $Mailer->body       = $html;
                        $Mailer->send();
                    }
                    
                }
            }
        }

        $response_array = [
            'status' => true,
            'message' => "Your Registration is Successful"
            
        ];
        return response()->json($response_array, 200);
    }

}