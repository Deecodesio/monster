<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Razorpay\Api\Api;
use Session;
use Redirect;
use DB;
use App\Http\Controllers\api\BaseController;

class RazorpayController extends BaseController
{
    public function razorpay(Request $request)
    {
        $order = DB::table('requests')->where('id', $request->orderID)->first();
        $rk = DB::table('settings')->where('key_word', 'razor_key')->select('value')->first();
        $rs = DB::table('settings')->where('key_word', 'razor_secret')->select('value')->first();
        $default_currency = DB::table('country')->where('is_default', 1)->first();
        $api = new Api($rk->value, $rs->value);
        //print_r($request->orderID);exit();
        $razor_order_id  = $api->order->create([
            'receipt'         => $order->order_id,
            'amount'          => number_format($order->bill_amount, 2, '', ''), // amount in the smallest currency unit
            'currency'        => 'INR', // <a href="/docs/payment-gateway/payments/international-payments/#supported-currencies"  target="_blank">See the list of supported currencies</a>.)
        ]);
        //print_r($razor_order_id);exit();
        // dd($razor_order_id);
        if ($order) {
            $user = DB::table('users')->where('id', $order->user_id)->first();
        }
        $logo = DB::table('settings')->where('key_word', 'site_logo')->first();
        $logo_img = BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;

        $response_array = [
            'status' => true,
            'razor' => $razor_order_id->amount,
            'razor_id' => $razor_order_id->id,
            'order' =>  $order,
            'user' => $user,
            'currency' => $default_currency,
            'razor_key' => $rk->value,
            'logo' => $logo_img
        ];

        return response()->json($response_array, 200);
        // return view('user.razorpay')->with('order', $order)->with('order_info',$razor_order_id);
    }

    public function payment(Request $request)
    {
        $input = $request->all();
        $rk = DB::table('settings')->where('key_word', 'razor_key')->select('value')->first();
        $rs = DB::table('settings')->where('key_word', 'razor_secret')->select('value')->first();
        $api = new Api($rk->value, $rs->value);
        $payment = $api->payment->fetch($request->razorpay_payment_id);

        if ($payment && ($payment->status == "authorized" || $payment->status == "captured")) {
            if ($payment->status != "captured") {
                $response = $api->payment->fetch($request->razorpay_payment_id)->capture(['amount' => $payment['amount']]);
            }

            $order = DB::table('requests')->where('id', $request->order_id)->first();
            $restaurant = DB::table('restaurants')->where('id', $order->restaurant_id)->first();
            DB::table('payments')->where('request_id', $request->order_id)->update(['payment_id' => $request->razorpay_payment_id, 'is_paid' => 1]);
            DB::table('requests')->where('id', $request->order_id)->update(['status' => 0, 'is_paid' => 1]);
            sendNotification(
                [$restaurant->device_token],
                trans('constants.notification_restaurant_new_title'),
                trans('constants.notification_restaurant_new_msg')
            );
            $message = "Success";
            $response_array = [
                'status' => true,
                'message' => $message,
                'order_id' => $order->id,
            ];
        } else {
            DB::table('requests')->where('id', $request->order_id)->update(['status' => -2]);
            $message = "Failed";
            $response_array = [
                'status' => false,
                'message' => $message,
                'order_id' => $request->order_id,
            ];
        }
        // $order = $api->order->fetch($request->razorpay_order_id);
        // $receipt = $order->receipt;
        // if (count($input) && !empty($request->razorpay_payment_id)) {
        //     try {
        //         $response = $api->payment->fetch($request->razorpay_payment_id);
        //         if (!$response->captured) {
        //             $response = $api->payment->fetch($request->razorpay_payment_id)->capture(['amount' => $payment['amount']]);
        //         }
        //         if ($receipt != "") {
        //             $response = $api->payment->fetch($request->razorpay_payment_id);
        //             if ($response->status == "captured") {
        //                 $order = DB::table('requests')->where('order_id', $receipt)->first();
        //                 $restaurant = DB::table('restaurants')->where('id', $order->restaurant_id)->first();
        //                 DB::table('payments')->where('request_id', $receipt)->update(['payment_id' => $request->razorpay_payment_id, 'is_paid' => 1]);
        //                 DB::table('requests')->where('id', $receipt)->update(['status' => 0, 'is_paid' => 1]);
        //                 sendNotification(
        //                     [$restaurant->device_token],
        //                     trans('constants.notification_restaurant_new_title'),
        //                     trans('constants.notification_restaurant_new_msg')
        //                 );
        //                 $message = "Success";
        //                 $response_array = [
        //                     'status' => true,
        //                     'message' => $message,
        //                     'order_id' => $order->id,
        //                 ];
        //             } else {
        //                 DB::table('requests')->where('id', $receipt)->update(['status' => -2]);
        //                 $message = "Failed";
        //                 $response_array = [
        //                     'status' => false,
        //                     'message' => $message,
        //                     'order_id' => $receipt,
        //                 ];
        //             }
        //         } else {
        //             DB::table('requests')->where('id', $receipt)->update(['status' => -2]);
        //             $message = "Failed";
        //             $response_array = [
        //                 'status' => false,
        //                 'message' => $message,
        //                 'order_id' => $receipt,
        //             ];
        //         }
        //     } catch (\Exception $e) {
        //         $message = $e->getMessage();
        //         $response_array = [
        //             'status' => true,
        //             'message' => $message,
        //             'order_id' => $receipt,
        //         ];
        //         return response()->json($response_array, 200);
        //     }
        // }

        return response()->json($response_array, 200);
    }
}
