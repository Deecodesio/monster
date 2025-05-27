<?php

namespace App\Http\Controllers\admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\BaseController;
use DB;

class NationController extends BaseController
{
    /**
     * Get country list.
     *
     * @return value to blade file
     */
    public function Getcountrylist()
    {
        $data = $this->country->get();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Addcountry()
    {

        return view('admin/add-country');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function AddEditcountry($id)
    {
        $data = $this->country->find($id);

            $message =$data;
			$status = true;
			$response_Array = json_encode(['message'=>$message,'status'=>$status,'data'=>$data]);
            return $response_Array;
    }

    /**
     * Update the country.
     * @param  Request $request param
     * @return success response 
     */
    public function Savecountry(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'currency_code' => 'required|max:5',
            'currency_symbol' => 'required|max:3',
            'country_code' => 'required|max:5',
            'country' => 'required',

        ]);
        // dd( $request->id);
        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            $message = $error_messages;
                $status = false;
                $response_Array = json_encode(['message'=>$message,'status'=>$status]);
                return $response_Array;
        }
       
        $id = $request->id;
      
        if ($id != "" || $id != null) {
            $check = $this->country->where('id', '!=', $id)->where('country', $request->country)->count();
           
                $path = 'admin/edit_country/' . $id;
            
            $insert = $this->country->find($id);
        } else {
            $check = $this->country->where('country', $request->country)->count();
           
                $path = 'admin/country_list';
            
            $insert = $this->country;
        }
        if ($check != 0) {
            return back()->with('error', trans('constants.already_exist', ['param' => 'Country is ']))->withInput();
            
        $message ="Already Exist";
        $status = false;
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);
        return $response_Array;
        }

        $insert->country = $request->country;
        $insert->country_code = $request->country_code;
        $insert->currency_code = $request->currency_code;
        $insert->currency_symbol = $request->currency_symbol;
        $insert->save();

        $message ="Saved";
        $status = true;
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);
        return $response_Array;
    }

    /**
     * Set country to default country.
     *
     * @return back to blade file
     */
    public function Defaultcountry($id)
    {
        $this->country->where('is_default', 1)->update(['is_default' => 0]);
        $country_update = $this->country->find($id);
        $country_update->is_default = 1;
        $country_update->save();

        $message ="Default Country Updated";
                $status = false;
                $response_Array = json_encode(['message'=>$message,'status'=>$status]);
                return $response_Array;
    }

    /**
     * Get State list.
     *
     * @return value to blade file
     */
    

    public function Getstatelist()
    {
        $data = DB::table('state')
                ->leftJoin('country','country.id','=','state.country_id')
                ->select('state.*','country.country')
                ->get();
        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Addstate()
    {
        $country = $this->country->get();

        return view('admin/add-state', compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return value to blade file
     */
    public function AddEditstate($id)
    {

        $country = $this->country->get();
        $data = $this->state->find($id);

            $message =$data;
			$status = true;
			$response_Array = json_encode(['message'=>$message,'status'=>$status,'data'=>$data]);
            return $response_Array;
    }

    /**
     * Update the state.
     * @param  Request $request param
     * @return success response 
     */
    public function Savestate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'state' => 'required',

        ]);
        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            $message =$error_messages;
            $status = false;
            $response_Array = json_encode(['message'=>$message,'status'=>$status]);
            return $response_Array;
        }
        $id = $request->id;
        $country_id = $request->country_id;
        $state = $request->state;

        if ($id != "" || $id != null) {

            $check = $this->state->where('id', '!=', $id)
                ->where('country_id', $country_id)
                ->where('state', $state)
                ->count();
           
                $path = 'admin/edit_state/' . $id;
            
            $insert = $this->state->find($id);
        } else {

            $check = $this->state->where('country_id', $country_id)
                ->where('state', $state)
                ->count();
           
                $path = 'admin/state_list';
            
            $insert = $this->state;
        }
        if ($check != 0) {
            $message ="Already Exist";
            $status = false;
            $response_Array = json_encode(['message'=>$message,'status'=>$status]);
            return $response_Array;
        }

        $insert->state = $state;
        $insert->country_id = $country_id;
        $insert->save();

        $message ="Success";
        $status = true;
        $response_Array = json_encode(['message'=>$message,'status'=>$status]);
        return $response_Array;
    }

    /**
     * Get State list.
     *
     * @return json response
     */

    public function get_state_ajax(Request $request, $id)
    {

        $country = $this->state->where('country_id', $id)->get();
        echo json_encode($country);
    }

    public function state_list_by_country(Request $request)
    {
        $state_list = DB::table('state')->where('country_id' ,$request->country)->get();
        
        return response()->json($state_list) ;
       

    }
}
