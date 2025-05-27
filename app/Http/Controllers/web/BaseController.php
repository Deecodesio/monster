<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Users;
use App\Model\Admin;
use App\Model\Deliverypartners;
use App\Model\Cuisines;
use App\Model\Restaurantcuisines;
use App\Model\Deliveryaddress;
use App\Model\Restaurants;
use App\Model\Favouritelist;
use App\Model\Popularbrands;
use App\Model\Foodlist;
use App\Model\Foodrequest;
use App\Model\Requestdetail;
use App\Model\Trackorderstatus;
use App\Model\Cart;
use App\Model\Category;
use App\Model\Menu;
use App\Model\Banner;
use App\Model\Promocode;
use App\Model\Settings;
use App\Model\Addcity;
use App\Model\Addarea;
use App\Model\Document;
use App\Model\Vehicle;
use App\Model\CancellationReason;
use App\Model\DriverList;
use App\Model\Coupon;
use App\Model\Food;
use App\Model\Add_ons;
use App\Model\FoodQuantity;
use App\Model\RequestdetailAddons;
use App\Model\RestaurantBankDetails;
use App\Model\DriverPayoutHistory;
use App\Model\RestaurantPayoutHistory;
use App\Model\OrderRatings;
use App\Model\Country;
use App\Model\State;
use App\Library\Custom;
use App\Library\Validators;
use Illuminate\Support\Str;
use URL;
use File;


if (!defined('BASE_URL')) define('BASE_URL',URL::to('/').'/');
if (!defined('PROFILE_ICON')) define('PROFILE_ICON','profile_icon.png');
if (!defined('RESTAURANT_UPLOADS_PATH')) define('RESTAURANT_UPLOADS_PATH','restaurant_uploads/');
if (!defined('VEHICLE_UPLOADS_PATH')) define('VEHICLE_UPLOADS_PATH','public/vehicles/');
if (!defined('UPLOADS_PATH')) define('UPLOADS_PATH','uploads/');
if (!defined('WEB')) define('WEB',   'web');
if (!defined('ANDROID')) define('ANDROID',   'android');
if (!defined('IOS')) define('IOS',   'ios');

if (! defined('UPLOADS_PATH_LOGO')) {
    define('UPLOADS_PATH_LOGO', 'restaurant_logo/');
}
if (! defined('UPLOADS_PATH_PRODUCT')) {
    define('UPLOADS_PATH_PRODUCT', 'product_image/');
}
if (! defined('UPLOADS_PATH_COMMON')) {
    define('UPLOADS_PATH_COMMON', 'common_images/');
}
if (! defined('UPLOADS_PATH_BANNER')) {
    define('UPLOADS_PATH_BANNER', 'restaurant_banner/');
}
//if (!defined('ORDER_ID_PREFIX')) define('ORDER_ID_PREFIX',   'EATZILLA');

class BaseController extends Controller
{

	 public function __construct(Admin $admin, Users $users,Custom $custom,Cuisines $cuisines,Deliveryaddress $deliveryaddress,Restaurants $restaurants,Favouritelist $favouritelist,Popularbrands $popularbrands,Foodlist $foodlist,Category $category,Menu $menu,Cart $cart,Foodrequest $foodrequest,Requestdetail $requestdetail,Deliverypartners $deliverypartners,Trackorderstatus $trackorderstatus,Promocode $promocode,Banner $banner,Settings $settings,Restaurantcuisines $restaurantcuisines,Addcity $addcity,Addarea $addarea,Document $document,Vehicle $vehicle,CancellationReason $cancellation_reason,DriverList $driver_list,Coupon $coupon,Food $food, Add_ons $add_ons, FoodQuantity $food_quantity, RequestdetailAddons $requestdetail_addons, RestaurantBankDetails $restaurant_bank_details, DriverPayoutHistory $driver_payout_history, RestaurantPayoutHistory $restaurant_payout_history, OrderRatings $order_ratings, Validators $validators,Country $country,State $state)
    {
    	// $this->validateArrays = $ValidateArrays;
        $this->admin = $admin;
        $this->users = $users;
        $this->custom = $custom;
        $this->cuisines = $cuisines;
        $this->deliveryaddress = $deliveryaddress;
        $this->restaurants = $restaurants;
        $this->favouritelist = $favouritelist;
        $this->popularbrands = $popularbrands;
        $this->foodlist = $foodlist;
        $this->category = $category;
        $this->menu = $menu;
        $this->cart = $cart;
        $this->banner = $banner;
        $this->promocode = $promocode;
        $this->foodrequest = $foodrequest;
        $this->requestdetail = $requestdetail;
        $this->deliverypartners = $deliverypartners;
        $this->trackorderstatus = $trackorderstatus;
        $this->settings = $settings;
        $this->restaurantcuisines = $restaurantcuisines;
        $this->addcity = $addcity;
        $this->addarea = $addarea;
        $this->document = $document;
        $this->vehicle = $vehicle;
        $this->cancellation_reason = $cancellation_reason;
        $this->driver_partner_details = $driver_list;
        $this->coupon = $coupon;
        $this->food = $food;
        $this->add_ons = $add_ons;
        $this->food_quantity = $food_quantity;
        $this->requestdetail_addons = $requestdetail_addons;
        $this->restaurant_bank_details = $restaurant_bank_details;
        $this->driver_payout_history = $driver_payout_history;
        $this->restaurant_payout_history = $restaurant_payout_history;
        $this->order_ratings = $order_ratings;
        $this->validators = $validators;
        $this->country = $country;
        $this->state = $state;

        //get site info
        $site_info = $this->settings->get();
        //dd($site_info);
       /* foreach($site_info as $siteinfo){
            if (!defined(strtoUpper($siteinfo->key_word))) define(strtoUpper($siteinfo->key_word), $siteinfo->value);
        }*/
    }

        public static function generate_booking_id()
    {
        $booking = Foodrequest::orderBy('id','DESC')->first();
        if (!$booking) {
            $booking_code = ORDER_ID_PREFIX.str_pad(1, 3, "0", STR_PAD_LEFT);
        } else {
            $new_id = $booking->id + 1;
            $booking_code = ORDER_ID_PREFIX.str_pad($new_id, 3, "0", STR_PAD_LEFT);
        }
        return $booking_code;
    }

           public static function generate_partner_id()
    {
        $booking =Deliverypartners::orderBy('id','DESC')->first();
        if (!$booking) {
            $booking_code = 'PAT'.str_pad(1, 5, "0", STR_PAD_LEFT);
        } else {
            $new_id = $booking->id + 1;
            $booking_code = 'PAT'.str_pad($new_id, 5, "0", STR_PAD_LEFT);
        }
        return $booking_code;
    }

    public static function send_otp($NUMBER,$OTP)
    {

    	$NAME = "User ";
        $API_KEY = "d37cc8c6-18f7-11e7-9462-00163ef91450";
        $SENDER_ID = "SPRKOT";

        $data = "module=TRANS_SMS&apikey=" . $API_KEY . "&to=" . $NUMBER . "&from=" . $SENDER_ID . "&templatename=" . $SENDER_ID . "&var1=" . $NAME . "&var2=" . $OTP;
        $ch = curl_init('https://2factor.in/API/R1/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); // This is the result from the API
        curl_close($ch);
        return $result;
    }

    public static function check_null($data)
    {
        # code...
        array_walk_recursive($data, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });
        return $data;
    }

    public static function is_near($pickup_lat,$pickup_lng,$user_id)
    {
        $user = Users::where('id',$user_id)->first();
        $distance = Common::get_distance($pickup_lat,$pickup_lng,$user->lat,$user->lng);
        if ($distance < Common::$radius) {
            return $distance;
        } else {
            return false;
        }
        
    }

    public function generateRandomString($length = 16) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	 public function generateRandomString_referral($length = 8) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

    public function encrypt_password($password) {

        $key = hash('sha256', env('HASH_NAME', 'deliware'));
        $iv = substr(hash('sha256', 'HASH_SECRET'), 0, 16);
        $output = openssl_encrypt($password, "AES-256-CBC", $key, 0, $iv);
        $output2 = base64_encode($output);
        return $output2;
    }

    public function decrypt_password($encrypted_password) {

        $key = hash('sha256', 'sparkout');
        $iv = substr(hash('sha256', 'developer'), 0, 16);
        $output1 = openssl_decrypt(base64_decode($encrypted_password), "AES-256-CBC", $key, 0, $iv);
        return $output1;
    }

    /**
     * to send push notifications
     * 
     */
    public function user_send_push_notification($device_token, $device_type, $title, $message,$id=0) 
    {
        // $device_token = "f_ZXeVPxK5k:APA91bE3FxmrPDQAeTc17j17CHyliLQ3D0iOhnQfsQz4coqyBfeHPYF6zMeJKDfX1wrwLWzp6bAkGCYRQ3Z_VUv0Z6xyUBKurpfXAT4-vJLO_X6PtlIyHE4UtKdZwdsy1ua8c_3V4zRZ";
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields=array();
        if($device_type == 'android'){
            $fields = array(
                'registration_ids' => array(
                    $device_token
                ),
                'data' => array(
                    "title" => $title,
                    "message" => $message,
                    'request_id' => $id
                )
            );
        }
        if($device_type == 'ios'){
            $fields = array(
                'to' => $device_token,
                'notification' => array(
                        "title" => $title,
                        "text" => $message,
                        'request_id' => $id
                ),
                'data'=>array(
                    'request_id' => $id
                ),
            );
        }else{
            $fields = array(
                'to' => $device_token,
                'fcm_options' => ['link' => "/admin/view_order/$id"],
                'data'=>array(
                    'notification' => array(
                        "title" => $title,
                        "body" => $message,
                        "icon" => "/img/logo.png",
                        "sound" => "blackberry_phone.mp3",
                        "request_id" => $id,
                        "actions"=> [["action"=>"open", "title"=> "Open"]]
                    )
                ),
            );
        }
        // var_dump($fields);
        $fields = json_encode($fields);
        $headers = array(
            'Authorization: key= '.env('FCM_SERVER_KEY'),
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);

        //print_r($result);
        curl_close($ch);

    }

   
    /**
     * to update firebase db in common
     * 
     * @param array $postdata, string $node, string $key
     * 
     */
    public function update_firebase($postdata, $node, $key)
    {
        $header = array();
        $header[] = 'Content-Type: application/json';

        $ch = curl_init(FIREBASE_URL."/".$node."/$key.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch); 
        curl_close($ch); 

    }


    /**
     * check whether the given date is weekend or not
     * 
     * @param string $date
     * 
     * @return boolean 
     */
    public function isWeekend($date) {
        return (date('N', strtotime($date)) >= 6);
    }

    public function setlocale ($locale) {
        
        $locales = explode(',', \Config::get('app.locales'));
        if (in_array($locale, $locales)) {
            session(['locale' => $locale]);
        }
        
        return redirect()->back();
    }
	
	
    /* NEW Controller */

    public function getDynamicLocale($obj, $key){
        $locale = session('locale');
        $secondary = env('SECONDARY_LANGUAGE', 'en');

        if($locale == $secondary ){
            $value = $obj->{'secondary_'.$key};
            if ($value)
                return $value;
        }
        return $obj->{$key};
    }

    public function updateSecondaryLang($obj){
        $json_obj = json_decode(json_encode($obj));
        foreach ($json_obj as  $index=>$item) {
            $keys = array_keys((array)$item);
            foreach ($keys as $key) {
                if(Str::startsWith($key, 'secondary_')){
                    $original_key = Str::substr($key, strlen('secondary_'));
                    $json_obj[$index]->{$original_key} = $this->getDynamicLocale($item, $original_key);
                }
            }

        }

        return $json_obj;
    }

    public function updateSecondaryLangAddon($obj){
        $json_obj = json_decode(json_encode($obj));
        foreach ($json_obj->foodlist_addons as  $index=>$item) {
            foreach ($item->addons as $inx => $addon) {
            $keys = array_keys((array)$addon);
            foreach ($keys as $key) {
                if(Str::startsWith($key, 'secondary_')){
                    $original_key = Str::substr($key, strlen('secondary_'));
                    $json_obj->foodlist_addons[$index]->addons[$inx]->{$original_key} = $this->getDynamicLocale($addon, $original_key);

                }
            }
            }
        }
        return $json_obj;
    }

    public function updateSecondaryLangSizes($obj){
        $json_obj = json_decode(json_encode($obj));
        foreach ($json_obj->foodlist_sizes as  $index=>$item) {
            foreach ($item->sizes as $inx => $sizes) {
                $keys = array_keys((array)$sizes);
                foreach ($keys as $key) {
                    if(Str::startsWith($key, 'secondary_')){
                        $original_key = Str::substr($key, strlen('secondary_'));
                        $json_obj->foodlist_sizes[$index]->sizes[$inx]->{$original_key} = $this->getDynamicLocale($sizes, $original_key);

                    }
                }
            }
        }
        return $json_obj;
    }
	
	 public function updateRestaurantName($obj){
        $locale = session('locale');
        $secondary = env('SECONDARY_LANGUAGE', 'en');

        if($locale == $secondary ){
            $value = $obj->restaurant_secondary_name;
            if ($value)
                return $value;
        }
        return $obj->restaurant_name;
    }
}