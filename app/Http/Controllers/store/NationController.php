<?php

namespace App\Http\Controllers\store;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\BaseController;

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

        return view('admin/country-list', compact('data'));
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

        return view('admin/add-country', compact('data'));
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
        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            return back()->with('error', $error_messages)->withInput();
        }
        $id = $request->id;
        if ($id != "" || $id != null) {
            $check = $this->country->where('id', '!=', $id)->where('country', $request->country)->count();

            $path = 'store/edit_country/' . $id;

            $insert = $this->country->find($id);
        } else {
            $check = $this->country->where('country', $request->country)->count();

            $path = 'store/country_list';

            $insert = $this->country;
        }
        if ($check != 0) {
            return back()->with('error', trans('constants.already_exist', ['param' => 'Country is ']))->withInput();
        }

        $insert->country = $request->country;
        $insert->country_code = $request->country_code;
        $insert->currency_code = $request->currency_code;
        $insert->currency_symbol = $request->currency_symbol;
        $insert->save();

        return redirect($path)->with('success', trans('constants.save_success_msg'));
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

        return back()->with('success', trans('constants.update_success_msg_country'));
    }

    /**
     * Get State list.
     *
     * @return value to blade file
     */
    public function Getstatelist()
    {
        $data = $this->state->with('Country')->get();

        return view('admin/state-list', compact('data'));
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

        return view('admin/add-state', compact('data', 'country'));
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

            return back()->with('error', $error_messages)->withInput();
        }
        $id = $request->id;
        $country_id = $request->country_id;
        $state = $request->state;

        if ($id != "" || $id != null) {

            $check = $this->state->where('id', '!=', $id)
                ->where('country_id', $country_id)
                ->where('state', $state)
                ->count();

            $path = 'store/edit_state/' . $id;

            $insert = $this->state->find($id);
        } else {

            $check = $this->state->where('country_id', $country_id)
                ->where('state', $state)
                ->count();

            $path = 'store/state_list';

            $insert = $this->state;
        }
        if ($check != 0) {
            return back()->with('error', trans('constants.already_exist', ['param' => 'State is ']))->withInput();
        }

        $insert->state = $state;
        $insert->country_id = $country_id;
        $insert->save();

        return redirect($path)->with('success', trans('constants.save_success_msg'));
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
}
