<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PaymentGateway\EasyCheckout;
use App\Model\Foodrequest;
use App\Model\Requestdetail;
use App\Model\RequestdetailAddons;
use Carbon\Carbon;
use App\Model\Users;
use Cartalyst\Stripe\Stripe;
use DB;
use Illuminate\Http\Request;

class Order extends Controller
{
    /*

    Order status info

    -1 => Request mode
    0  => Order created
    1  => Accepted by restaurant
    2  => Rider assigned
    3  =>
    4  =>
    5  =>
    6  =>
    7  =>
    8  =>
    9  => Cancelled by admin
    10 => Cancelled by restaurant
    11 => Cancelled by customer

    */

    public $OrderID;

    public $flag;

    public $flagMsg;

    public $Items;

    public $Restaurant;

    public $Customer;

    public $PaymentMethod;

    public $DeliveryMethod;

    public $IsCoD = 0;

    public $Address;

    public $Latitude;

    public $Longitude;

    public $Currency;

    public $PaymentGateway;

    public $PaymentID;

    public $CreatedThrough;

    public $OfferCode;

    public $OfferAmount;

    public $WalletAmount;

    public $ItemTotal = 0;

    public $BillAmount = 0;

    public $DeliveryCharge = 0;

    public $Status = -1;

    public static $table_payment = 'payments';

    public static $table = 'requests';

    public static $table_pickup_drop = 'requests';

    public static $table_customer = 'users';

    public function __construct()
    {
        $this->CustomerID = null;
        $this->RestaurantID = null;

        $this->allowedPaymentMethods = ['delivery', 'takeaway', 'online'];
        $this->allowedDeliveryMethods = ['delivery', 'takeaway', 'dining'];

        $this->Currency = env('CURRENCY_CODE');
    }

    private function typecast()
    {
        $this->CustomerID = (int) $this->CustomerID;
        $this->RestaurantID = (int) $this->RestaurantID;

        $typecastedItems = [];
        if (is_array($this->Items)) {
            foreach ($this->Items as $i => $eachItem) {
                if (empty($eachItem)) {
                    return new ReturnData(false, "Item #$i is empty");
                }

                if (!is_array($eachItem)) {
                    return new ReturnData(false, "Item #$i is not an array");
                }

                $eachItemKeys = array_keys($eachItem);
                $diffKeys = array_diff(['FoodID', 'SizeID', 'AddonIDs', 'Quantity', 'addons_quantity'], $eachItemKeys);
                if ($diffKeys) {
                    return new ReturnData(false, "Item #$i expecting following values: " . cimplode($diffKeys));
                }

                if (!is_array($eachItem['AddonIDs'])) {
                    return new ReturnData(false, "Item #$i expecting AddonIDs to be an array");
                }

                $newItem = new \StdClass;
                $newItem->FoodID = (int) $eachItem['FoodID'];
                $newItem->SizeID = $eachItem['SizeID'] ? ((int) $eachItem['SizeID']) : null;
                $newItem->AddonIDs = (array) $eachItem['AddonIDs'];
                $newItem->Quantity = (int) $eachItem['Quantity'];
                $newItem->addons_quantity = (array) $eachItem['addons_quantity'];

                $typecastedItems[] = $newItem;
            }
        }

        $this->Items = $typecastedItems;

        return new ReturnData(true, 'Typecast OK');
    }

    public function validator()
    {
        $typecast = $this->typecast();
        if (!$typecast->flag) {
            return $typecast;
        }

        if (!Customer::isExist($this->CustomerID)) {
            return new ReturnData(false, 'Customer not found');
        }

        $restaurant = Restaurant::isAvailableNow($this->RestaurantID);
        if (!$restaurant->flag) {
            return $restaurant;
        }

        $this->Restaurant = Restaurant::find($this->RestaurantID);

        $items = $this->Restaurant->isProductSetsAvailable($this->Items);

        if (!$items->flag) {
            return new ReturnData(false, $items->flagMsg);
        }

        if (!in_array($this->PaymentMethod, $this->allowedPaymentMethods)) {
            return new ReturnData(false, 'Payment method not found (' . cimplode($this->allowedPaymentMethods) . ')');
        }

        if ($this->PaymentMethod != 'online') {
            $this->IsCoD = 1;
        }

        if (!in_array($this->DeliveryMethod, $this->allowedDeliveryMethods)) {
            return new ReturnData(false, 'Delivery method not found (' . cimplode($this->allowedDeliveryMethods) . ')');
        }

        if (!$this->Restaurant->isDeliveryMethodAllowed($this->DeliveryMethod)) {
            return new ReturnData(false, 'This delivery method is not enabled by the restaurant');
        }

        $this->DeliveryType = array_search($this->DeliveryMethod, $this->allowedDeliveryMethods) + 1;

        if ($this->PaymentMethod == 'delivery' && !$this->isCoDAllowed()) {
            return new ReturnData(false, 'Cash on delivery is disabled');
        }

        if (!in_array($this->CreatedThrough, ['android', 'ios', 'web'])) {
            return new ReturnData(false, 'CreatedThrough field should be one of these (android, ios, web)');
        }

        return new ReturnData(true, 'Validator OK');
    }

    public function validator_picku_drop()
    {



        if (!Customer::isExist($this->CustomerID)) {
            return new ReturnData(false, 'Customer not found');
        }

        $restaurant = Restaurant::isAvailableNow($this->RestaurantID);
        if (!$restaurant->flag) {
            return $restaurant;
        }

        $this->Restaurant = Restaurant::find($this->RestaurantID);







        if (!in_array($this->PaymentMethod, $this->allowedPaymentMethods)) {
            return new ReturnData(false, 'Payment method not found (' . cimplode($this->allowedPaymentMethods) . ')');
        }

        if ($this->PaymentMethod != 'online') {
            $this->IsCoD = 1;
        }







        $this->DeliveryType = array_search($this->DeliveryMethod, $this->allowedDeliveryMethods) + 1;

        if ($this->PaymentMethod == 'delivery' && !$this->isCoDAllowed()) {
            return new ReturnData(false, 'Cash on delivery is disabled');
        }

        if (!in_array($this->CreatedThrough, ['android', 'ios', 'web'])) {
            return new ReturnData(false, 'CreatedThrough field should be one of these (android, ios, web)');
        }

        return new ReturnData(true, 'Validator OK');
    }

    private function isCoDAllowed(): bool
    {
        $isCoDEnabled = DB::table('settings')->select('value')->where('key_word', 'cod_enable')->first();

        return $isCoDEnabled->value;
    }

    private function assignPrices()
    {
        $this->calcItemPrices();
        $this->ItemTotal = $this->itemTotal();

        $package_charge = ($this->ItemTotal / 100) * $this->Restaurant->packaging_charge;
        $this->PackingCharge =0 ; // $package_charge;
        // $this->Tax 					= $this->calculateTax();
        $this->DeliveryCharge = $this->DeliveryCharge;
        $this->OfferDiscount = 0;
        $this->TipsAmount = (int) $this->tips;

        $this->AdminCommission = $this->adminCommission();
        $this->RestaurantCommission = $this->restaurantCommission();
        $this->RiderCommission = 0;
        $this->BillAmount = $this->billAmount();
    }

    private function preparePayment(): bool
    {

        $this->PaymentID = '';
        $this->ClientSecret = '';
        $this->PaymentGateway = '';
        $this->CheckoutURL = null;
        $this->Status = $this->PaymentMethod == 'online' ? -1 : 0;

        if ($this->PaymentMethod != 'online') {
            return true;
        }
        $pg = DB::table('settings')->where('key_word', 'PAYMENT_GATEWAY')->first();
        $this->PaymentGateway = "Stripe";

        if ($pg->value == 0) {
            if (!$this->paymentGatewayEasy()) {
                return false;
            }
        } elseif ($pg->value == 1) {
            return $this->stripePaymentIntent($this->BillAmount);
        }

        return true;
    }

    private function preparePaymentw(): bool
    {

        $this->PaymentID         = '';
        $this->ClientSecret     = '';
        $this->PaymentGateway     = '';
        $this->CheckoutURL         = null;
        $this->Status             = $this->PaymentMethod == 'online' ? -1 : 0;


        if ($this->WalletAmount > 0) {
            if ($this->WalletAmount  >= $this->BillAmount) {
                $this->WalletAmount = $this->BillAmount;

                $user_detail = DB::table('users')->where('id', $this->CustomerID)->first();
                // $user_detail = $this->users->find($user_id);
                $status_entry1 = array();
                $status_entry1[] = array(
                    'user_id' => $this->CustomerID,
                    'amount' =>  $this->BillAmount,
                    'topup_code' => '',
                    'type' => 2, //1-credit 2-debit
                    'status' => 1
                );

                $ins = DB::table('user_wallet_history')->insert($status_entry1);
                Users::find($this->CustomerID)->decrement('wallet_amount', $this->BillAmount);
                $this->BillAmount = 0;
            } else {
                //  $BillAmount1 =  $this->BillAmount - $this->WalletAmount;
                $user_detail = DB::table('users')->where('id', $this->CustomerID)->first();
                // $user_detail = $this->users->find($user_id);
                $status_entry1 = array();
                $status_entry1[] = array(
                    'user_id' => $this->CustomerID,
                    'amount' =>  $this->WalletAmount,
                    'topup_code' => '',
                    'type' => 2, //1-credit 2-debit
                    'status' => 1
                );

                $ins = DB::table('user_wallet_history')->insert($status_entry1);
                Users::find($this->CustomerID)->decrement('wallet_amount', $this->WalletAmount);
                $this->BillAmount =  $this->BillAmount - $this->WalletAmount;
            }
        }


        if ($this->PaymentMethod != 'online') {
            return true;
        }
        $pg = DB::table('settings')->where('key_word', 'PAYMENT_GATEWAY')->first();
        $this->PaymentGateway = "Stripe";

        if ($pg->value == 0) {
            if (!$this->paymentGatewayEasy()) {
                return false;
            }
        } else if ($this->PaymentGateway == 1) {
            return $this->stripePaymentIntent($this->BillAmount);
        }

        return true;
    }

    private function stripePaymentIntent($amount)
    {

        $sk = DB::table('settings')->where('key_word', 'stripe_sk_key')->first();
        $pk = DB::table('settings')->where('key_word', 'stripe_pk_key')->first();
        $version = DB::table('settings')->where('key_word', 'stripe_version')->first();
        $default_currency = DB::table('country')->where('is_default', 1)->first();

        $stripe = new Stripe($sk->value, $version->value);
        // $auto = DB::table('settings')->where('key_word','stripe_auto_payout_enable')->first();

        // if($auto->value == 1){
        //     $rest_strip = DB::table('restaurant_bank_details')->where('restaurant_id',$this->RestaurantID)->first();

        // if($rest_strip->connected_stripe_id != NULL){
        //     $paymentIntent = $stripe->paymentIntents()->create([
        //         'amount' => $amount,
        //         'currency' => env('CURRENCY_CODE'),
        //         'payment_method_types' => [
        //             'card',
        //         ],
        //         'application_fee_amount' =>$this->AdminCommission * 100,
        //             'transfer_data' => [
        //                   'destination' => $rest_strip->connected_stripe_id,
        //             ],
        //         // 'capture_method' => 'manual'
        //     ]);
        // }else{
        //         $paymentIntent = $stripe->paymentIntents()->create([
        //         'amount' => $amount,
        //         'currency' => env('CURRENCY_CODE'),
        //         'automatic_payment_methods' => [
        //             'enabled' => 'true',
        //         ],
        //         // 'capture_method' => 'manual'
        //     ]);
        // }

        // }else{


        $paymentIntent = $stripe->paymentIntents()->create([
            'setup_future_usage' => 'off_session',
            'amount' => $amount,
            'currency' => $default_currency->currency_code,
            'automatic_payment_methods' => [
                'enabled' => 'true',
            ],
            'capture_method' => 'automatic',
        ]);
        // }

        if (!isset($paymentIntent['id'])) {
            return false;
        }

        $this->PaymentID = $paymentIntent['id'];
        $this->ClientSecret = $paymentIntent['client_secret'];

        return true;
    }

    private function paymentGatewayEasy(): bool
    {
        $EasyCheckout = new EasyCheckout;
        $EasyCheckout->isAPI = true;
        $checkoutData = $EasyCheckout->createCheckoutURL($this->BillAmount);

        if ($checkoutData->flag) {
            $this->CheckoutURL = $checkoutData->data->hostedPaymentPageUrl;
            $this->PaymentID = $checkoutData->data->paymentId;

            return true;
        }

        return false;
    }

    public function create()
    {

        $validator = $this->validator();
        if (!$validator->flag) {
            return $validator;
        }

        $this->OrderCode = $this->nextOrderCode(); //$this->randomCode();

        $this->assignPrices();

        $orderFailedMsg = 'Something went wrong. Cannot create order';

        if (!$this->preparePayment()) {
            return new ReturnData(false, "$orderFailedMsg (Cannot initiate payment)");
        }
        if (!$this->insertOrder()) {
            return new ReturnData(false, "$orderFailedMsg (Insertion failed)");
        }
        if (!$this->insertItems()) {
            return new ReturnData(false, "$orderFailedMsg (Insertion failed in request_details)");
        }

        if (!$this->insertAddons()) {
            return new ReturnData(false, "$orderFailedMsg (Insertion failed in requestdetail_addons)");
        }

        if (!$this->insertPayment()) {
            return new ReturnData(false, "$orderFailedMsg (Insertion failed in payments)");
        }

        $returnData = new ReturnData(true, 'Order OK');

        return $returnData;
    }
    public function create_pickup_drop()
    {

        $validator = $this->validator_picku_drop();

        if (!$validator->flag) {
            return $validator;
        }

        $this->OrderCode = $this->nextOrderCode(); //$this->randomCode();

        //  $this->assignPrices();

        $orderFailedMsg = 'Something went wrong. Cannot create order';

        if (!$this->preparePayment()) {

            return new ReturnData(false, "$orderFailedMsg (Cannot initiate payment)");
        }
        if (!$this->insertOrder_pickup_order()) {
            return new ReturnData(false, "$orderFailedMsg (Insertion failed)");
        }
        // if (! $this->insertItems_pickup_order()) {
        //     return new ReturnData(false, "$orderFailedMsg (Insertion failed in request_details)");
        // }
        // if (! $this->insertAddons()) {
        //     return new ReturnData(false, "$orderFailedMsg (Insertion failed in requestdetail_addons)");
        // }
        if (!$this->insertPayment()) {
            return new ReturnData(false, "$orderFailedMsg (Insertion failed in payments)");
        }

        $returnData = new ReturnData(true, 'Order OK');

        return $returnData;
    }

    public function createwallet()
    {
        $validator = $this->validator();
        if (!$validator->flag) {
            return $validator;
        }
        //check dunzo service location
        //  $DunzoTask = \App\Http\Controllers\Dunzo::checkquote($this); //print_r($DunzoTask);

        //         if (! $DunzoTask->flag) {
        //             //echo "ji";
        //             return new ReturnData(false, $DunzoTask->flagMsg);
        //         }

        $this->OrderCode = $this->nextOrderCode(); //$this->randomCode();

        $this->assignPrices();

        $orderFailedMsg = 'Something went wrong. Cannot create order';

        if (!$this->preparePaymentw()) {
            return new ReturnData(false, "$orderFailedMsg (Cannot initiate payment)");
        }
        if (!$this->insertOrderw()) {

            return new ReturnData(false, "$orderFailedMsg (Insertion failed)");
        }
        if (!$this->insertItems()) {
            return new ReturnData(false, "$orderFailedMsg (Insertion failed in request_details)");
        }
        if (!$this->insertAddons()) {
            return new ReturnData(false, "$orderFailedMsg (Insertion failed in requestdetail_addons)");
        }
        if (!$this->insertPayment()) {
            return new ReturnData(false, "$orderFailedMsg (Insertion failed in payments)");
        }

        $returnData = new ReturnData(true, 'Order OK');

        return $returnData;
    }

    private function randomCode()
    {
        return generateRandomString(8);
    }

    private function takeawayCode()
    {
        if ($this->DeliveryMethod == 'takeaway') {
            return rand(10000, 99999);
        }
    }

    private function calcItemPrices()
    {

        foreach ($this->Items as $key => $EachItem) {
            $this->Items[$key]->ItemTotal = $this->Restaurant->itemTotal($EachItem);
            $this->Items[$key]->AddonsDetails = $this->Restaurant->addonsDetailsOfItem($EachItem);
        }
    }

    private function itemTotal(): float
    {
        return array_sum(array_column($this->Items, 'ItemTotal'));
    }

    private function billAmount(): float
    {
        $tax = DB::table('settings')->where('key_word', 'tax_inclusive')->first();
        if ($tax->value == 1) {
            return $this->ItemTotal + $this->PackingCharge + $this->DeliveryCharge + $this->TipsAmount - $this->OfferAmount;
        } else {
            return $this->ItemTotal + $this->PackingCharge + $this->DeliveryCharge + $this->tax + $this->TipsAmount - $this->OfferAmount;
        }
    }

    private function insertOrder(): bool
    {
        if ($this->ScheduledAt != null) {
            $scheduledAt = Carbon::createFromFormat('d-m-Y', $this->ScheduledAt)->format('Y-m-d H:i:s');
            $scheduletime = str_replace(' ', '', $this->ScheduledTime);
            $schedule = 1;
        } else {
            $scheduledAt = null;
            $scheduletime = null;
            $schedule = 0;
        }
        $insertOrder = [
            'order_id' => $this->OrderCode,
            'user_id' => $this->CustomerID,
            'restaurant_id' => $this->RestaurantID,

            'status' => -1,
            'CreatedThrough' => $this->CreatedThrough,
            'PaymentMethod' => $this->PaymentMethod,

            'bill_amount' => $this->BillAmount,
            'tips' => $this->tips,
            'item_total' => $this->ItemTotal,
            'delivery_charge' => $this->DeliveryCharge,
            'restaurant_packaging_charge' => $this->PackingCharge,
            'tax' => $this->tax,
            'offer_discount' => $this->OfferAmount,
            'admin_commision' => $this->AdminCommission,
            'restaurant_commision' => $this->RestaurantCommission,
            'delivery_boy_commision' => $this->DeliveryCharge,

            'coupon_code' => $this->OfferCode,
            'delivery_type' => $this->DeliveryType,
            'takeaway_code' => $this->takeawayCode(),
            'is_cod' => $this->IsCoD,
            'cooking_notes' => $this->CookingNotes,
            'is_confirmed' => 0,
            'is_paid' => 0,
            'paid_type' => 1,
            'delivery_address' => $this->Address,
            'd_lat' => $this->Latitude,
            'd_lng' => $this->Longitude,
            'ordered_time' => noww(),

            'ScheduledAt' => $scheduledAt,
            'ScheduledTime' => $scheduletime,
            'schedule' => $schedule,
        ];

        DB::table(self::$table)->insert($insertOrder);

        $saved = DB::table(self::$table)->select('id')->where('order_id', $this->OrderCode)->orderBy('id', 'desc')->first();

        if (!isset($saved->id)) {
            return false;
        }

        $this->OrderID = $saved->id;

        return true;
    }

    private function insertOrderw(): bool
    {
        $insertOrder = [
            'order_id'                        => $this->OrderCode,
            'user_id'                        => $this->CustomerID,
            'restaurant_id'                    => $this->RestaurantID,

            'status'                         => -1,
            'CreatedThrough'                => $this->CreatedThrough,
            'PaymentMethod'                 => $this->PaymentMethod,
            // 'gpay' 				            => $this->IsGpay,
            'wallet_amount'                    => $this->WalletAmount,
            'bill_amount'                    => $this->BillAmount,
            'tips'                            => $this->TipsAmount,
            'item_total'                    => $this->ItemTotal,
            'delivery_charge'                => $this->DeliveryCharge,
            'restaurant_packaging_charge'     => $this->PackingCharge,
            'tax'                            => $this->tax,
            'offer_discount'                => $this->OfferAmount,
            'admin_commision'                => $this->AdminCommission,
            'restaurant_commision'            => $this->RestaurantCommission,
            'delivery_boy_commision'        => $this->DeliveryCharge,

            'coupon_code'                    => $this->OfferCode,
            'delivery_type'                    => $this->DeliveryType,
            'takeaway_code'                    => $this->takeawayCode(),
            'is_cod'                         => $this->IsCoD,
            'cooking_notes'                 => $this->CookingNotes,
            'ScheduledAt'                     => $this->ScheduledAt,
            'is_confirmed'                    => 0,
            'is_paid'                        => 0,
            'paid_type'                        => 1,
            'delivery_address'                => $this->Address,
            'd_lat'                            => $this->Latitude,
            'd_lng'                            => $this->Longitude,
            'ordered_time'                    => noww(),
        ];

        DB::table(self::$table)->insert($insertOrder);

        $saved = DB::table(self::$table)->select('id')->where('order_id', $this->OrderCode)->orderBy('id', 'desc')->first();

        if (!isset($saved->id)) {
            return false;
        }

        $this->OrderID = $saved->id;

        return true;
    }

    private function adminCommission()
    {
        $adminCommisionPercentage = $this->Restaurant->admin_commision ?? DB::table('settings')->where('key_word', 'admin_commission')->value('value');
        $adminCommission = (($this->ItemTotal - $this->tax) / 100) * $adminCommisionPercentage;
        $this->AdminCommission = $adminCommission;

        return $adminCommission;
    }

    private function restaurantCommission()
    {
        //$restaurantCommission = $this->BillAmount - ($this->AdminCommission + $this->RiderCommission);

        if (env('IS_TAX_INCLUSIVE')) {
            $restaurantCommission = $this->ItemTotal - $this->AdminCommission;
        } else {
            $restaurantCommission = ($this->ItemTotal + $this->tax) - $this->AdminCommission;
        }

        return $restaurantCommission;
    }

    private function insertItems(): bool
    {

        $flag = true;
        foreach ($this->Items as $key => $EachItem) {
            $request_detail = new Requestdetail;
            $request_detail->request_id = $this->OrderID;
            $request_detail->restaurant_id = $this->RestaurantID;
            $request_detail->food_id = $EachItem->FoodID;
            $request_detail->quantity = $EachItem->Quantity;
            $request_detail->food_quantity_price = $EachItem->ItemTotal;
            $request_detail->food_quantity = $EachItem->SizeID;
            $request_detail->save();

            if (!isset($request_detail->id)) {
                $flag = false;
                break;
            }

            $this->Items[$key]->RequestDetailID = $request_detail->id;
        }

        return $flag;
    }

    private function insertAddons(): bool
    {
        $flag = true;

        foreach ($this->Items as $itemKey => $EachItem) {
            if (is_array($EachItem->AddonsDetails)) {
            foreach ($EachItem->AddonsDetails as $addonKey => $eachAddon) {

                $requestdetail_addons = new RequestdetailAddons;
                $requestdetail_addons->requestdetail_id = $EachItem->RequestDetailID;
                $requestdetail_addons->addons_id = $eachAddon->id;
                $requestdetail_addons->name = $eachAddon->name;
                $requestdetail_addons->price = $eachAddon->price;
                $requestdetail_addons->quantity = $eachAddon->quantity;

                $requestdetail_addons->save();

                if (!isset($requestdetail_addons->id)) {
                    $flag = false;
                    break;
                }
            }
        }

            if (!$flag) {
                break;
            }
        }

        return $flag;
    }
    private function insertOrder_pickup_order(): bool
    {


        $insertOrder = [
            'order_id' => $this->OrderCode,
            'user_id' => $this->CustomerID,
            'restaurant_id' => $this->RestaurantID,
            'business_type' => $this->BusinessType,
            'status' => -1,
            'CreatedThrough' => $this->CreatedThrough,
            'PaymentMethod' => $this->PaymentMethod,

            'bill_amount' => $this->DeliveryCharge,
            'tips' => $this->tips,
            // 'item_total' => $this->ItemTotal,
            'delivery_charge' => $this->DeliveryCharge,

            'tax' => $this->tax,
            'offer_discount' => $this->OfferAmount,
            //  'admin_commission' => $this->AdminCommission,
            // 'restaurant_commision' => $this->RestaurantCommission,
            'delivery_boy_commision' => $this->DeliveryCharge,

            'coupon_code' => $this->OfferCode,
            // 'delivery_type' => $this->DeliveryType,
            'takeaway_code' => $this->takeawayCode(),
            'is_cod' => $this->IsCoD,
            // 'cooking_notes' => $this->CookingNotes,
            'ScheduledAt' => $this->ScheduledAt,
            'is_confirmed' => 0,
            'is_paid' => 0,
            'paid_type' => 1,
            'delivery_address' => $this->Address,
            'd_lat' => $this->Latitude,
            'd_lng' => $this->Longitude,
            'pickup_address' => $this->PickupAddress,
            'pickup_lat' => $this->PickupLatitude,
            'pickup_lng' => $this->PickupLongitude,
            'pickup_name' => $this->PickupName,
            'pickup_phone' => $this->PickupNumber,
            'drop_name' => $this->DropName,
            'drop_phone' => $this->DropNumber,
            'ordered_time' => noww(),
        ];
        DB::table(self::$table_pickup_drop)->insert($insertOrder);

        $saved = DB::table(self::$table_pickup_drop)->select('id')->where('order_id', $this->OrderCode)->orderBy('id', 'desc')->first();

        if (!isset($saved->id)) {
            return false;
        }

        $this->OrderID = $saved->id;

        return true;
    }

    private function insertPayment(): bool
    {
        $insertPayment = [
            'request_id' => $this->OrderID,
            'payment_gateway' => $this->PaymentGateway,
            'amount' => $this->BillAmount,
            'currency' => $this->Currency,
            'payment_id' => $this->PaymentID,
            'payment_description' => $this->OrderCode,
        ];

        DB::table('payments')->insert($insertPayment);

        return true;
    }

    public static function find(int $orderID)
    {
        $newObj = new Order;

        $order = DB::table('requests')->where('id', $orderID)->first();

        $newObj->flag = false;

        if ($order) {
            $restaurant = DB::table('restaurants')->where('id', $order->restaurant_id)->first();
            $payment = DB::table(self::$table_payment)->where('request_id', $order->id)->first();

            $newObj->flag = true;
            $newObj->OrderID = $orderID;
            $newObj->OrderCode = $order->id;
            $newObj->CustomerID = $order->user_id;
            $newObj->RestaurantID = $order->restaurant_id;
            $newObj->RiderID = $order->delivery_boy_id;
            $newObj->Status = $order->status;
            $newObj->Restaurant = $restaurant;
            $newObj->Request = $order;
            $newObj->Payment = $payment;
        }

        return $newObj;
    }

    public static function find2(int $orderID)
    {
        $newObj = new Order;

        $order = DB::table('requests')->where('id', $orderID)->first();
        // dd($order);
        $newObj->flag = false;

        if ($order) {
            $restaurant = DB::table('restaurants')->where('id', $order->restaurant_id)->first();
            $payment = DB::table(self::$table_payment)->where('request_id', $order->id)->first();
            $customer = DB::table(self::$table_customer)->where('id', $order->user_id)->first();

            $items = DB::table('request_detail')->where('request_id', $order->id)->get();
            $orderItems = [];

            foreach ($items as $item) {
                $food = DB::table('food_list')->where('id', $item->food_id)->first();
                $sizeName = DB::table('food_quantity')->where('id', $item->food_quantity)->value('name');

                $addons = DB::table('requestdetail_addons')->where('requestdetail_id', $item->id)->get();
                $thisAddons = [];
                $thisAddonNames = [];
                foreach ($addons as $addon) {
                    $thisAddon = new \stdClass();
                    $thisAddon->id = $addon->id;
                    $thisAddon->addonsID = $addon->addons_id;
                    $thisAddon->name = $addon->name;
                    $thisAddon->price = $addon->price;
                    $thisAddons[] = $thisAddon;

                    $thisAddonNames[] = $addon->name;
                }

                $thisItem = new \stdClass();
                $thisItem->id = $item->id;
                $thisItem->foodID = $item->food_id;
                $thisItem->name = $food->name; //dd($item->quantity);
                $thisItem->quantity = $item->quantity;
                $thisItem->price = $item->food_quantity_price;
                $thisItem->addons = $addons;
                $thisItem->addons = $thisAddons;
                $thisItem->addonNames = $thisAddonNames;
                $thisItem->sizeName = $sizeName;

                $orderItems[] = $thisItem;
            }

            $newObj->flag = true;
            $newObj->OrderID = $orderID;
            $newObj->OrderCode = $order->id;
            $newObj->CustomerID = $order->user_id;
            $newObj->RestaurantID = $order->restaurant_id;
            $newObj->RiderID = $order->delivery_boy_id;
            $newObj->Status = $order->status;
            $newObj->WalletAmount = $order->wallet_amount;
            // $newObj->TaskID             = $order->DeliveryTaskID;

            $newObj->Restaurant = $restaurant;
            $newObj->Request = $order;
            $newObj->Payment = $payment;
            $newObj->Customer = $customer;
            $newObj->OrderItems = $orderItems;
        }

        return $newObj;
    }

    public function isAcceptedByRestaurant()
    {
        if ($this->Status > 0) {
            return true;
        }
    }

    public function acceptByRestaurant()
    {
        if ($this->isAcceptedByRestaurant()) {
            return true;
        }

        $notifMsg = "{$this->Restaurant->restaurant_name} has started preparing your order. Our delivery executive will pick it up soon.";

        $this->sendNotification2Customer(trans('constants.order_confirmed'), $notifMsg);

        $this->updateStatus(1);

        //after confirm order send mail to user
        $user = DB::table('users')->find($this->CustomerID);
        $order = DB::table('requests')->find($this->OrderID);
        $rest = DB::table('restaurants')->find($this->RestaurantID);
        if ($this->OrderID) {
            $food_list = DB::table('request_detail')->where('request_id', $this->OrderID)
                ->join('food_list', 'food_list.id', '=', 'request_detail.food_id')
                ->get();
            $rt_food = [];
            foreach ($food_list as $fl) {
                $rt_food[] = [
                    'Name' => html_entity_decode($fl->name),
                    'description' => html_entity_decode($fl->description),
                    'quantity' => $fl->quantity,
                    'price' => $fl->food_quantity_price
                ];
            }
        }


        $template = DB::table('mail_contents')->where("content_for", "invoicemail")->get();
        $cont = [];
        foreach ($template as $temp) {
            $cont[] = $temp->content;
        }
        $logo = DB::table('settings')->where('key_word', "site_logo")->first();
        $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
        $url = BASE_URL . "/admin/new";

        $html = view('emails.user.invoice')->with(['user' => $user, 'food' => $rt_food, 'order' => $order, 'content' => $cont, 'logo' => $to_logo, 'url' => $url]);

        $Mailer             = new Mailer();
        $Mailer->to         = $user->email;
        $Mailer->toName     = $rest->restaurant_name;
        $Mailer->subject    = trans('emails.order_confirmed');
        $Mailer->body       = $html;

        $Mailer->send();
        // $html = view('emails.user.order-confirm')->with(['user' => $user, 'order' => $order]);

        // $Mailer = new Mailer();
        // $Mailer->to = $user->email;
        // $Mailer->toName = $rest->restaurant_name;
        // $Mailer->subject = trans('constants.order_confirmed');
        // $Mailer->body = $html;
        // $Mailer->send();
        $this->updateStatus(1);
        $this->insertTrackOrderStatus(1, 'Order accepted');
        return true;
    }

    public function delay(int $delayMinutes)
    {

        // if ($delayMinutes <= 0 || $this->Request->delay_time > 0) {
        // 	return 0;
        // }

        $this->sendNotification2Customer(trans('constants.delay_order'), "Your order will arrive $delayMinutes minutes late.");
        $this->storeOrderDelay($delayMinutes);

        return true;
    }

    private function storeOrderDelay($delayMinutes)
    { //dd($delayMinutes);
        $delay = DB::table('requests')->where('id', $this->OrderID)->update(['delay_time' => $delayMinutes]); //dd($delay);
        $this->Request->delay_minutes = $delayMinutes;
    }

    public function cancelByAdmin($reason)
    {
        return $this->cancel(9, $reason);
    }

    public function cancelByRestaurant($reason)
    {
        return $this->cancel(10, $reason);
    }

    public function cancelByUser($reason)
    {
        return $this->cancel(11, $reason);
    }

    private function cancel($cancelledBy, $reason)
    {
        if ($this->isAcceptedByRestaurant()) {
            return false;
        }
        if ($cancelledBy == 11) {
            $this->sendNotification2Customer(trans('constants.order_cancelled'), 'Cancellation reason: ' . $reason);
        } else {
            $this->sendNotification2Customer(trans('constants.order_cancelled'), 'Cancellation reason: ' . $reason);
        }
        $this->updateStatus($cancelledBy);

        $this->insertTrackOrderStatus($cancelledBy, $reason);

        return true;
    }

    public function sendNotification2Customer($title, $message)
    {
        $Customer = DB::table('users')->where('id', $this->CustomerID)->first();

        if (isset($Customer->device_token)) {
            sendNotification([$Customer->device_token], $title, $message, ['order_id' => $this->OrderID]);
        }
    }

    public function sendNotification2Restaurant($title, $message)
    {
        sendNotification([$this->Restaurant->device_token], $title, $message, ['order_id' => $this->OrderID]);
    }

    public function sendNewOrderEmail2Restaurant()
    {
        sendNewOrderEmail2Restaurant($this->OrderID);
    }

    public function sendNewOrderEmail2Customer()
    {
        sendNewOrderEmail2Customer($this->OrderID);
    }

    private function updateStatus(int $status)
    {
        DB::table('requests')->where('id', $this->OrderID)->update(['status' => $status]);
        $this->Status = $status;
        $this->Request->status = $status;
    }

    private function insertTrackOrderStatus($status, $detail)
    {
        $status_entry = [];
        $status_entry[] = [
            'request_id' => $this->OrderID,
            'status' => $status,
            'detail' => $detail,
        ];

        DB::table('track_order_status')->insert($status_entry);
    }

    public static function fetchOrderIDOfPayment($paymentID)
    {
        $payment = DB::table(self::$table_payment)->where('payment_id', $paymentID)->first();

        return $payment->request_id ?? 0;
    }

    public static function findByPaymentID($paymentID)
    {
        $paymentID = self::fetchOrderIDOfPayment($paymentID);

        return self::find($paymentID);
    }

    public function isPaymentSuccess(): bool
    {
        if ($this->Payment->payment_gateway == 'Easy') {
            $EasyCheckout = new EasyCheckout();

            return $EasyCheckout->isPaymentSuccess($this->Payment->payment_id);
        } elseif ($this->Payment->payment_gateway == 'Stripe') {
            return true;
        }

        return false;
    }

    private function update($arr)
    {
        DB::table(self::$table)->where('id', $this->OrderID)->update($arr);

        foreach ($arr as $field => $value) {
            $this->Request->{$field} = $value;
        }
    }

    private function updatePayment($arr)
    {
        DB::table(self::$table_payment)->where('request_id', $this->OrderID)->update($arr);

        foreach ($arr as $field => $value) {
            $this->Payment->{$field} = $value;
        }
    }

    public function isPaid()
    {
        if ($this->Payment->is_paid) {
            return true;
        }

        if ($this->isPaymentSuccess()) {
            $this->update(['is_paid' => 1]);
            $this->updatePayment(['is_paid' => 1, 'paid_at' => noww()]);

            return true;
        }

        return false;
    }

    public function confirmOrder()
    {
        if ($this->Status >= 0) {
            return new ReturnData(true, 'Order already confirmed');
        }

        if (!$this->Request->is_cod && !$this->isPaid()) {
            return new ReturnData(false, 'Payment not confirmed');
        }

        $this->updateStatus(0);
        $this->insertTrackOrderStatus(0, 'Order created');

        $this->sendNotification2Restaurant(trans('constants.notification_restaurant_new_title'), trans('constants.notification_restaurant_new_msg'));

        $this->sendNewOrderEmail2Admins();
        // if (! empty(env('MAIL_FROM_NAME'))) {
        //     $this->sendNewOrderEmail2Restaurant();
        //     $this->sendNewOrderEmail2Customer();
        // }
        $this->updateOrder2Firebase();

        return new ReturnData(true, trans('constants.order_confirmed'));
    }
    public function sendNewOrderEmail2Admins()
    {
        sendNewOrderEmail2Admins($this->OrderID);
    }
    public function updateOrder2Firebase()
    {
        $params = [
            'request_id' => $this->OrderID,
            'user_id' => $this->Request->user_id,
            'provider_id' => $this->Request->delivery_boy_id,
            'status' => $this->Status,
        ];

        $curl = cURL::url(FIREBASE_URL . "/current_request/{$this->OrderID}.json")->json($params)->put();
    }

    public function nextOrderCode()
    {
        $booking = Foodrequest::orderBy('id', 'DESC')->first();
        if (!$booking) {
            $booking_code = ORDER_ID_PREFIX . str_pad(1, 3, '0', STR_PAD_LEFT);
        } else {
            $new_id = $booking->id + 1;
            $booking_code = ORDER_ID_PREFIX . str_pad($new_id, 3, '0', STR_PAD_LEFT);
        }

        return $booking_code;
    }

    public function scheduleNotification()
    {
        $orders_list = DB::table('requests')->where(['status' => 0])->where('ScheduledAt', '!=', null)->get();
        foreach ($orders_list as $o) {
            $current_time = Carbon::now()->timestamp;
            $schedule = Carbon::parse($o->ScheduledAt)->timestamp;
            $time = $schedule - $current_time;
            echo $noti_time = $time / 60;
            echo '<BR>';
            $this->Restaurant = Restaurant::find($o->restaurant_id);
            if ($noti_time == 30) {
                $this->sendNotification2Restaurant(trans('constants.schedule_time'), trans('constants.schdule_alert'));
                sendNotification([$this->Restaurant->device_token], trans('constants.schedule_time'), trans('constants.schdule_alert'), ['order_id' => $o->id]);
            }
            if ($noti_time == 120) {
                $this->sendNotification2Restaurant(trans('constants.schedule_time'), trans('constants.schdule_alert2'));
                sendNotification([$this->Restaurant->device_token], trans('constants.schedule_time'), trans('constants.schdule_alert'), ['order_id' => $o->id]);
            }
            $restaurant_mail = DB::table('restaurants')->where('id', $o->restaurant_id)->first();
            $html = view('emails.restaurant.schedule-mail')->with(['restaurant' => $restaurant_mail]);

            $Mailer = new Mailer();
            $Mailer->to = $restaurant_mail->email;
            $Mailer->toName = $restaurant_mail->restaurant_name;
            $Mailer->subject = 'Welcome';
            $Mailer->body = $html;
            $Mailer->send();
        }
    }
}
