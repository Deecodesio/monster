<?php

namespace App\Http\Controllers\web;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\BaseController;
use DB;

class TransactionController extends BaseController
{
    /**
     * Get Driver (or) Restaurant Payouts.
     *
     * @param $type Restaurant (or) driver
     *
     * @return response to blade file
     */
    public function Getpayout($type, Request $request)
    {
        if ($type == 'restaurant') {

            $data = $this->restaurants->with('Foodrequest')
            ->with('RestaurantBankDetails')
                ->withcount('Foodrequest')
             
                ->where('pending_payout', '!=', '0')
                ->get();
        } elseif ($type == 'driver') {
            $data = $this->deliverypartners->with('Foodrequest')
                ->withcount('Foodrequest')
               
                ->where('pending_payout', '!=', '0')
                ->get();
        }
        
        return response()->json($data);

    }

    /**
     * Get Driver (or) Restaurant Payout history.
     *
     * @param $type Restaurant (or) driver
     *
     * @return response to blade file
     */
    public function Getpayout_history($type, Request $request)
    {

        if ($type == 'restaurant') {
            $data = DB::table('restaurant_payout_history')
                    ->leftJoin('restaurants','restaurants.id','=','restaurant_payout_history.restaurant_id')
                    ->get();
            // $data = $this->restaurant_payout_history->with('Restaurants')->get();
        } elseif ($type == 'driver') {
            $data = DB::table('driver_payout_history')
            ->leftJoin('delivery_partners','delivery_partners.id','=','driver_payout_history.delivery_boy_id')
            ->get();
        }
        
        return response()->json($data);
    }

    /**
     * Display add-payout Blade file.
     *
     * @param $type, $id
     *
     * @return response view blade file
     */
    public function Getaddpayment($type, $amount, $id, Request $request)
    {
        return view('admin/add-payout', compact('type', 'amount', 'id'));
    }

    /**
     * Post payout to driver or restaurant based on type.
     *
     * @param $type, $id
     *
     * @return response view blade file
     */
    public function addpayment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required',

        ]);

        //$transaction_id = 'Trans-097';

        if ($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());

            $message =$error_messages ;
            $status = false;
            $response_Array = json_encode(['message'=>$message,'status'=>$status]);
    
    
            return $response_Array;
        }
        $type = $request->type;

        if ($type == 'restaurant') {
            $transaction_id = 'Trans-';
            // $requestdetails = $this->foodrequest->where('restaurant_id',$request->id)->update(['restaurant_settlement_status'=>1]);
           
            // $this->restaurants->find($request->id)->decrement('pending_payout', $request->amount);
            $rid = DB::table('restaurant_payout_history')->select('id')->orderBy('id', 'desc')->first();
            $insert = $this->restaurant_payout_history;
            $insert->restaurant_id = $request->id;
            $insert->transaction_id = $transaction_id.' '.$rid->id;
            $insert->payout_amount = $request->amount;
            $insert->description = $request->description;
            $insert->status = 'Success';
            $insert->save();
            $tot_com = DB::table('requests')->where('restaurant_id',$request->id)->where('status',7)->get();
            $amount = 0;
            foreach($tot_com as $tot){
                  $amount += $tot->restaurant_commision ;
            }
            $amount_paid = 0;
            $tot_paid = DB::table('restaurant_payout_history')->where('restaurant_id',$request->id)->get();
            foreach($tot_paid as $tot_p){
                  $amount_paid += $tot_p->payout_amount ;
            }
            $pend = $amount - $amount_paid;
            $up_pend = DB::table('restaurants')->where('id',$request->id)->update(['pending_payout'=>$pend]);

        } elseif ($type == 'driver') {
            $transaction_id = 'Tran-';
            //$requestdetails = $this->foodrequest->where('delivery_boy_id',$request->id)->update(['driver_settlement_status'=>1]);
            $this->deliverypartners->find($request->id)->decrement('pending_payout', $request->amount);
            $did = DB::table('driver_payout_history')->select('id')->orderBy('id', 'desc')->first();
            if($did){
                $dod = $did->id;
            }else{
                $dod = 1;
            }
            $insert = $this->driver_payout_history;
            $insert->delivery_boy_id = $request->id;
            $insert->transaction_id = $transaction_id.' '.$dod;
            $insert->payout_amount = $request->amount;
            $insert->description = $request->description;
            $insert->status = 'Success';
            $insert->save();
        }
       
                $message ="Paid Successfully";
                $status = true;
                $response_Array = json_encode(['message'=>$message,'status'=>$status]);


                return $response_Array;
        
            }
}
