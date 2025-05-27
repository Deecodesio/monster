<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Razorpay\Api\Api;
use Session;
use Redirect;
use DB;

class RazorpayController extends Controller
{
    public function razorpay(Request $request)
    {   
        $order = DB::table('requests')->where('id', $request->orderID)->first();
        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
        //print_r($request->orderID);exit();
        $razor_order_id  = $api->order->create([
  'receipt'         => $order->id,
  'amount'          => number_format($order->bill_amount, 2, '', ''), // amount in the smallest currency unit
  'currency'        => 'INR',// <a href="/docs/payment-gateway/payments/international-payments/#supported-currencies"  target="_blank">See the list of supported currencies</a>.)
]);
        //print_r($razor_order_id);exit();
        return view('user.razorpay')->with('order', $order)->with('order_info',$razor_order_id);
    }

    public function payment(Request $request)
    {        
        $input = $request->all();
        print_r($input);
        //exit();
        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
        //exit(env('RAZOR_KEY'));        
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        $order = $api->order->fetch($input['razorpay_order_id']);
        $receipt = $order->receipt;
        //print_r($receipt);   exit();
        if(count($input)  && !empty($input['razorpay_payment_id'])) 
        {
            try 
            {
                $response = $api->payment->fetch($input['razorpay_payment_id']);
                if(!$response->captured)
                    $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));
                //print_r($response);
            } 
            catch (\Exception $e) 
            {
                //print_r($e);
                //return  $e->getMessage();
                \Session::put('error',$e->getMessage());
                return redirect()->back();
            }            
        }
        if ($receipt !="") {
            $order = DB::table('requests')->where('id', $receipt)->first();
            //print_r($order);
            $restaurant = DB::table('restaurants')->where('id', $order->restaurant_id)->first();
            DB::table('payments')->where('request_id', $receipt)->update(['payment_id' => $input['razorpay_payment_id'], 'is_paid' => 1]);
            DB::table('requests')->where('id', $receipt)->update(['status' => 0, 'is_paid' => 1]);
            
            sendNotification(
                [$restaurant->device_token],
                trans('constants.notification_restaurant_new_title'),
                trans('constants.notification_restaurant_new_msg')
            );
            \Session::put('success', 'Payment successful, your order will be despatched in the next 48 hours.');
        }
        //print_r($receipt);exit();
        
        
        return redirect(route('user.orders').'#current-orders');
    }
}