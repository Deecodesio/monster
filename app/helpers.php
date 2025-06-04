<?php

use App\Http\Controllers\Mailer;
use Carbon\Carbon;
use Twilio\Rest\Client;
use App\Model\Foodlist;
use App\Model\Menu;
use App\Model\MenuTime;

function formatDate($date = '', $format = 'Y-m-d')
{
    if ($date == '' || $date == null) {
        return;
    }

    return date($format, strtotime($date));
}

function testFun()
{
    return "Test";
}
function get_default_latitude()
{
    return env('DEMO_ENV') ? env('DEFAULT_LATITUDE') : '';
};
function get_restaurant_ratings($restaurant_id)
{
    return DB::table('order_ratings')
        ->join('requests', 'requests.id', '=', 'order_ratings.request_id')
        ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
        ->where('restaurants.id', $restaurant_id)
        ->select(
            DB::raw('SUM(1) as total_raters'),
            DB::raw('SUM(restaurant_rating) as total_ratings')
        )->first();
}
function get_default_longitude()
{
    return env('DEMO_ENV') ? env('DEFAULT_LONGITUDE') : '';
};
function get_default_location()
{
    return env('DEMO_ENV') ? env('DEFAULT_LOCATION') : 'San Francisco, CA, USA';
};
function get_nearby_cuisines($lat, $lng)
{
    $data = (array) DB::select("
        select *, sum(1) as cuisines_count from (
                select cuisines.name as cuisine_name,cuisines.id as id,
                (
                    6371 * acos(cos(radians($lat))* cos(radians(`lat`))
                    * cos(radians(`lng`) - radians($lng)) + sin(radians($lat))
                    * sin(radians(`lat`)))
                ) as distance,
                cuisine_image
                from `restaurant_cuisines`
                inner join `cuisines` on `cuisines`.`id` = `restaurant_cuisines`.`cuisine_id` inner join `restaurants` on `restaurants`.`id` = `restaurant_cuisines`.`restaurant_id`
        ) as cuisines where distance <= 5 group by id
    ");
    return $data;
}

function get_all_cuisines()
{
    $data = (array) DB::select("
        select *, sum(1) as cuisines_count from (
                select cuisines.name as cuisine_name,cuisines.id as id,
                cuisine_image
                from `restaurant_cuisines`
                inner join `cuisines` on `cuisines`.`id` = `restaurant_cuisines`.`cuisine_id` inner join `restaurants` on `restaurants`.`id` = `restaurant_cuisines`.`restaurant_id`
                where `restaurants`.`status` = 1 ) as cuisines group by id
    ");
    return $data;
}

function get_categories_by_rank()
{
    $data = DB::table('category')->where('status', 1)->orderBy('rank', 'asc')->get();
    return $data;
}

function get_menu_by_rank($restaurantID)
{
    $data = DB::table('menu')->where('restaurant_id', $restaurantID)->orderBy('rank', 'asc')->get();
    return $data;
}

function get_category_by_rank($is_imageOnly = 0)
{
    if ($is_imageOnly) {
        $data = DB::table('category')->where('status', 1)->where('image', '<>', '')->orderBy('rank', 'asc')->get();
    } else {
        $data = DB::table('category')->where('status', 1)->orderBy('rank', 'asc')->get();
    }

    return $data;
}

function get_category_by_rank_one($business_id)
{
    // if ($is_imageOnly) {
    //     $data = DB::table('business_category')->where('status', 1)->where('business_id', $business_id)->where('image', '<>', '')->get();
    // } else {
    $data = DB::table('business_category')->where('status', 1)->where('business_id', $business_id)->get();
    // }

    return $data;
}

function get_all_categories()
{
    $data = DB::table('category')->where('status', 1)->get();
    foreach ($data as $key => $value) {
        $restIDs = DB::table('food_list')
            ->where('category_id', $value->id)
            ->groupBy('restaurant_id')
            ->pluck('restaurant_id')->toArray();

        $data[$key]->count = count($restIDs);
    }
    return $data;
}

function get_nearby_banners($lat, $lng)
{

    /* $data = DB::table('offers_banner')
        ->where('status', 1)
        ->orderBy('position', 'asc')->get();

    foreach ($data as $k => $d) {
        $data[$k]->is_open = restaurant_is_open($d->restaurant_id);
    }

    return $data;  */

    $data = DB::table('offers_banner')
        ->where('status', 1)
        ->where('featured', 1)
        ->orderBy('position', 'asc')->get();
    $availableRestIDs = [];

    foreach ($data as $k => $d) {
        if (isset($lat)) {
            $rest = DB::table('restaurants')->where('id', $d->restaurant_id)->get();
            foreach ($rest as $r) {
                if (isset($lat) && isset($lng)) {
                    $geofenceRadius = (float) ($r->GeofenceRadius ?? DEFAULT_RADIUS);
                    $distance = vincentyGreatCircleDistance($lat, $lng, $r->lat, $r->lng);
                    if ($distance <= $geofenceRadius) {
                        $datas = DB::table('offers_banner')->where('id', $d->id)->first();
                        $datas->is_open = restaurant_is_open($r->id);
                        $datas->name = $r->restaurant_name;

                        $availableRestIDs[] = $datas;
                    }
                }
            }
        } else {
            $data[$k]->is_open = restaurant_is_open($d->restaurant_id);
            $availableRestIDs = $data;
        }
    }
    //dd($availableRestIDs);
    return $availableRestIDs;
};
function get_nearby_cities($lat, $lng)
{
    return DB::table('restaurants')
        ->join('add_city', 'add_city.id', '=', 'restaurants.city')
        ->selectRaw("(6371 * acos(cos(radians(" . $lat . "))* cos(radians(`lat`))
                            * cos(radians(`lng`) - radians(" . $lng . ")) + sin(radians(" . $lat . "))
                            * sin(radians(`lat`)))) as distance, add_city.city as name, add_city.id as id")
        ->having('distance', '<=', DEFAULT_RADIUS)
        ->groupBy('add_city.id')
        ->orderBy('distance')
        ->get();
}

function restaurant_is_open($restaurant_id)
{

    // if (env('IS_RESTAURANT_OPEN')) {
    //     return 1;
    // }


    $restaurant = DB::table('restaurants')->where('id', $restaurant_id)->first();

    $updaterest = DB::table('restaurants')->where('is_open', 2)->get();
    foreach ($updaterest as $uprest) {
        $close_time = $uprest->closed_for_today;
        $now = Carbon::now()->format('Y-m-d');

        if ($close_time < $now) {
            DB::table('restaurants')->where('id', $uprest->id)->update([
                'is_open' => 1,
                'closed_for_today' => null,
            ]);
        }
    }
    $updaterest2 = DB::table('restaurants')->where('is_open', 3)->get();
    foreach ($updaterest2 as $up2) {
        $current_time = date('H:i:s');
        $date = date('Y-m-d');
        $weekday = date('w');
        $weekday = $weekday == 0 ? 7 : $weekday;
        $opening_var = 'opening_time_' . $weekday;
        $closing_var = 'closing_time_' . $weekday;

        $opening_second_var = 'opening_second_time_' . $weekday;
        $closing_second_var = 'closing_second_time_' . $weekday;
        // dd($current_time);
        if (
            (strtotime($up2->$opening_second_var) <= strtotime($current_time)) &&
            (strtotime($up2->$closing_second_var) >= strtotime($current_time))
        ) {
            DB::table('restaurants')->where('id', $up2->id)->update([
                'is_open' => 1,
                'closed_for_today' => null,
            ]);
        }
    }
    if (!$restaurant || $restaurant->is_open  != 1) {
        return 0;
    }

    $holidays = explode(',', $restaurant->holidays);

    $is_open = 0;
    $current_time = date('H:i:s');
    $date = date("Y-m-d");
    $weekday = date('w');
    $weekday = $weekday == 0 ? 7 : $weekday;

    // dd($current_time);
    if (in_array($weekday, $holidays)) {
        return 0;
    }

    $opening_var = 'opening_time_' . $weekday;
    $closing_var = 'closing_time_' . $weekday;

    $opening_second_var = 'opening_second_time_' . $weekday;
    $closing_second_var = 'closing_second_time_' . $weekday;

    if ($restaurant->status == 0) {
        return 0;
    }

    if (
        !isset($restaurant->$opening_var) || empty($restaurant->$opening_var) ||
        !isset($restaurant->$closing_var) || empty($restaurant->$closing_var)
    ) {
        return 0;
    }

    if (
        (strtotime($restaurant->$opening_var) <= strtotime($current_time)) &&
        (strtotime($restaurant->$closing_var) >= strtotime($current_time))
    ) {
        $is_open = 1;
    }

    if ($restaurant->$opening_var == $restaurant->$closing_var) {
        $is_open = 1;
    }

    if (
        isset($restaurant->$opening_second_var) && !empty($restaurant->$opening_second_var) &&
        isset($restaurant->$closing_second_var) && !empty($restaurant->$closing_second_var)
    ) {

        if ($restaurant->$opening_second_var == $restaurant->$closing_second_var) {
            $is_open = 1;
        }

        if (
            (strtotime($restaurant->$opening_second_var) <= strtotime($current_time)) &&
            (strtotime($restaurant->$closing_second_var) >= strtotime($current_time))
        ) {
            $is_open = 1;
        }
    }

    return $is_open;
}

function get_nearby_restaurants($lat = 0, $lng = 0, $id = [0], $limit = 3)
{

    if (!$lat || !$lng) {
        return [];
    }

    $restaurant_list = DB::table('restaurants')
        ->whereNotIn('restaurants.id', $id)
        ->select('restaurants.*')
        ->selectRaw("(6371 * acos(cos(radians(" . $lat . "))* cos(radians(`lat`))
                            * cos(radians(`lng`) - radians(" . $lng . ")) + sin(radians(" . $lat . "))
                            * sin(radians(`lat`)))) as distance")
        ->having('distance', '<=', DEFAULT_RADIUS)
        ->where('status', 1)
        ->orderBy('distance')
        ->limit($limit)
        ->get();

    foreach ($restaurant_list as $key => $rest) {
        $restaurant_list[$key]->is_open = restaurant_is_open($rest->id);
    }
    return $restaurant_list;
}
function get_listing_url()
{
    return route('user.search_nearby_restaurant', ['lat' => env('DEFAULT_LATITUDE'), 'lng' => env('DEFAULT_LONGITUDE')]);
}
function get_default_restaurant_url()
{
    return route('user.restaurant', env('DEFAULT_RESTAURANT'));
}
function get_rating_statistics($restaurant_id)
{
    $ratings = DB::table('order_ratings')
        ->join('requests', 'requests.id', '=', 'order_ratings.request_id')
        ->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
        ->where(['restaurants.id' => $restaurant_id])
        ->selectRaw('round(restaurant_rating) as stars, sum(restaurant_rating) as star_count, sum(1) as rater_count')->groupBy('stars')->orderBy('stars', 'desc')
        ->get()->toArray();
    $total_reviewer = 0;
    $total_stars = 0;
    $rated_stars = array_map(function ($item) {
        return ceil((int) $item);
    }, array_column($ratings, 'stars'));
    // dd($rated_stars );
    $ratings_fn = [];
    for ($i = 5, $j = 0; $i > 0; $i--) {
        if (!in_array($i, $rated_stars)) {
            $ratings_fn[$i] = (object) array(
                'rater_count' => 0,
                'star_count' => 0,
                'stars' => (float)number_format("$i", 2),
            );
        } else {
            $ratings_fn[$i] = (object) array(
                'rater_count' => $ratings[$j]->rater_count,
                'star_count' => $ratings[$j]->star_count,
                'stars' => $ratings[$j]->stars,
            );
            $total_reviewer += $ratings[$j]->rater_count;
            $total_stars += $ratings[$j]->star_count;
            $j++;
        }
    }
    // dd($ratings_fn);
    $ratings_fn = array_map(function ($item) use ($total_reviewer) {
        // echo ('<br> $item->rater_count = '.$item->rater_count.' $total_reviewer = '.$total_reviewer);
        $item->rater_percent = $total_reviewer === 0 ? 0 : number_format((($item->rater_count / $total_reviewer) * 100), 2);
        return $item;
    }, $ratings_fn);
    // dd($ratings_fn);
    return [
        'total_reviewers' => $total_reviewer,
        'total_stars' => $total_stars,
        'rating_percent' => $total_reviewer === 0 ? 0 : number_format((($total_stars / $total_reviewer)), 2),
        'ratings' => $ratings_fn,
    ];
}

function price_format($price)
{
    if (env('IS_PRICE_COMMA_BASED')) {
        return number_format($price, 2, ',', '');
    } else {
        return number_format($price, 2, '.', '');
    }
}

function time_format($time)
{
    if (env('IS_24_HOUR')) {
        return date('H:i', strtotime($time));
    } else {
        return date('h:i A', strtotime($time));
    }
}

function date_time_format($date)
{
    return date('F d, Y', strtotime($date)) . ' ' . time_format($date);
}

function sendNotification($registration_ids, $title, $body, $data = [])
{
    if (!$registration_ids || empty($registration_ids)) {
        return false;
    }

    $notification = [
        'title' => $title,
        'body' => $body,
        'sound' => 'blackberry_phone.mp3'
    ];



    // $data['title']  = $title;
    // $data['body']   = $body;
    // $data['sound']   = "blackberry_phone.mp3";

    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = [
        'registration_ids'  => $registration_ids,
        "notification"      => $notification,
        //  "data"              => $data,
        "priority"          => "high"
    ];
    // \Log::channel('cronlog')->info($fields);

    $FCM_SERVER_KEY = DB::table('settings')->where('key_word', "user_notification_key")->first();
    $headers = [
        'Authorization: key=' .  $FCM_SERVER_KEY->value,
        // 'Authorization: key=' . env('API_KEY'),
        // 'Authorization: key=' . env('API_KEY_APPLE'),
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);

    curl_close($ch);
    return $result;
    if ($result === FALSE) {
        return false;
    }
    return true;
}
function sendNotification_driver($registration_ids, $title, $body, $data = [])
{
    if (!$registration_ids || empty($registration_ids)) {
        return false;
    }

    // $notification = [
    //     'title' => $title,
    //     'body' => $body,
    //     'sound' => 'blackberry_phone.mp3'
    // ];

    $data['title']  = $title;
    $data['body']   = $body;
    $data['sound']   = "blackberry_phone.mp3";

    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = [
        'registration_ids'  => $registration_ids,
        // "notification"      => $notification,
        "data"              => $data,
        "priority"          => "high"
    ];
    $FCM_SERVER_KEY = DB::table('settings')->where('key_word', "user_notification_key")->first();

    $headers = [
        'Authorization: key=' . $FCM_SERVER_KEY->value,
        // 'Authorization: key=' . env('API_KEY'),
        // 'Authorization: key=' . env('API_KEY_APPLE'),
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);

    curl_close($ch);
    return $result;
    if ($result === FALSE) {
        return false;
    }
    return true;
}

function sendNotification_web($registration_ids, $title, $body, $data = [])
{
    if (!$registration_ids || empty($registration_ids)) {
        return false;
    }

    $notification = [
        'title' => $title,
        'body' => $body,
        'sound' => 'blackberry_phone.mp3'
    ];

    // $data['title']  = $title;
    // $data['body']   = $body;
    // $data['sound']   = "blackberry_phone.mp3";


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = [
        'registration_ids'  => $registration_ids,
        "notification"      => $notification,
        // "data"              => $data,
        "priority"          => "high"
    ];


    $FCM_SERVER_KEY = DB::table('settings')->where('key_word', "user_notification_key")->first();

    $headers = [
        'Authorization: key=' . $FCM_SERVER_KEY->value,
        // 'Authorization: key=' . env('API_KEY'),
        // 'Authorization: key=' . env('API_KEY_APPLE'),
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);

    curl_close($ch);
    return $result;
    if ($result === FALSE) {
        return false;
    }
    return true;
}
function sendNewOrderEmail2Restaurant($request_id)
{
    $order = DB::table('requests')
        ->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
        ->select('requests.*', 'restaurants.restaurant_name', 'restaurants.email as restaurant_email')
        ->where('requests.id', $request_id)->first();

    $html = view('emails.restaurant.order-new')->with(['order' => $order]);

    $Mailer             = new Mailer();
    $Mailer->to         = "support@justbiryani.app";
    $Mailer->cc         = ["rajeshben@bytesflow.com", "support@justbiryani.app", "benny@bytesflow.com"];
    $Mailer->toName     = $order->restaurant_name;
    $Mailer->subject    = trans('emails.restaurant_new_order_sub');
    $Mailer->body       = $html;

    $Mailer->send();
}

function sendNewOrderEmail2Admins($request_id)
{
    $order = DB::table('requests')
        ->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
        ->select('requests.*', 'restaurants.restaurant_name', 'restaurants.email as restaurant_email')
        ->where('requests.id', $request_id)->first();

    $template = DB::table('mail_contents')->where("content_for", "newordermail")->get();
    $cont = [];
    foreach ($template as $temp) {
        $cont[] = $temp->content;
    }
    $logo = DB::table('settings')->where('key_word', "site_logo")->first();
    $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
    $url = BASE_URL . "/admin/new";
    $html = view('emails.restaurant.order-new')->with(['order' => $order, 'template' => $cont, 'logo' => $to_logo, 'url' => $url]);
    $neworder = DB::table('mail_ids')->where("mail_for", "newordermail")->first();

    $emails = json_decode($neworder->mail_ids);
    $title = "New Order" . ' ' . $order->order_id . ' ' . $order->restaurant_name;
    for ($i = 0; $i < count($emails); $i++) {
        $Mailer             = new Mailer();
        $Mailer->to         = $emails[$i];
        $Mailer->toName     = $order->restaurant_name;
        $Mailer->subject    = $title;
        $Mailer->body       = $html;

        $Mailer->send();
    }
}

function sendfailOrderEmail2Admins($request_id)
{
    $order = DB::table('requests')
        ->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
        ->select('requests.*', 'restaurants.restaurant_name', 'restaurants.email as restaurant_email')
        ->where('requests.id', $request_id)->first();

    $template = DB::table('mail_contents')->where("content_for", "failedordermail")->get();
    $cont = [];
    foreach ($template as $temp) {
        $cont[] = $temp->content;
    }
    $logo = DB::table('settings')->where('key_word', "site_logo")->first();
    $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
    $url = BASE_URL . "/admin/new";




    $html = view('emails.restaurant.failed')->with(['order' => $order, 'template' => $cont, 'logo' => $to_logo, 'url' => $url]);
    $neworder = DB::table('mail_ids')->where("mail_for", "failedordermail")->first();

    $emails = json_decode($neworder->mail_ids);

    $title = "Failed Order" . ' ' . $order->order_id . ' ' . $order->restaurant_name;
    for ($i = 0; $i < count($emails); $i++) {
        $Mailer             = new Mailer();
        $Mailer->to         = $emails[$i];
        $Mailer->toName     = $order->restaurant_name;
        $Mailer->subject    = $title;
        $Mailer->body       = $html;

        $Mailer->send();
    }
}


function sendCancelledOrderEmail2Restaurant($request_id)
{
    $order = DB::table('requests')
        ->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
        ->select('requests.*', 'restaurants.restaurant_name', 'restaurants.email as restaurant_email')
        ->where('requests.id', $request_id)->first();

    $template = DB::table('mail_contents')->where("content_for", "cancelledordermail")->get();
    $cont = [];
    foreach ($template as $temp) {
        $cont[] = $temp->content;
    }
    $logo = DB::table('settings')->where('key_word', "site_logo")->first();
    $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
    $url = BASE_URL . "/admin/new";
    $html = view('emails.restaurant.order-cancelled')->with(['order' => $order, 'template' => $cont, 'logo' => $to_logo, 'url' => $url]);

    // $html = view('emails.restaurant.order-cancelled')->with(['order' => $order]);

    $Mailer             = new Mailer();
    $Mailer->to         = $order->restaurant_email;
    $Mailer->toName     = $order->restaurant_name;
    $Mailer->subject    = trans('emails.restaurant_order_cancelled_sub');
    $Mailer->body       = $html;

    $Mailer->send();
}

function sendOrderRejectedByRiderEmail2Restaurant($request_id, $driver_id)
{

    $order = DB::table('requests')
        ->leftJoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
        ->select('requests.*', 'restaurants.restaurant_name', 'restaurants.email as restaurant_email')
        ->where('requests.id', $request_id)->first();

    $driver = DB::table('delivery_partners')->where('id', $driver_id)->first();

    $html = view('emails.restaurant.order-rejected-by-rider')->with(['order' => $order, 'driver' => $driver]);

    $Mailer             = new Mailer();
    $Mailer->to         = $order->restaurant_email;
    $Mailer->toName     = $order->restaurant_name;
    $Mailer->subject    = trans('emails.restaurant_order_rejected_by_driver');
    $Mailer->body       = $html;

    $Mailer->send();
}

function sendApprovalStatusEmail2Restaurant($restaurant_id)
{
    $restaurant = DB::table('restaurants')
        ->where('id', $restaurant_id)->first();

    $html = view('emails.restaurant.approval-status')->with(['restaurant' => $restaurant]);

    $Mailer             = new Mailer();
    $Mailer->to         = $restaurant->email;
    $Mailer->toName     = $restaurant->restaurant_name;
    $Mailer->subject    = $restaurant->status ? trans('emails.restaurant_approved_sub') : trans('emails.restaurant_not_approved_sub');
    $Mailer->body       = $html;

    $Mailer->send();
}

function sendNewRestaurantEmail2Admin($restaurant_id)
{
    // $restaurant = DB::table('restaurants')
    //     ->where('id', $restaurant_id)->first();

    // $html = view('emails.admin.restaurant-new')->with(['restaurant' => $restaurant]); echo $html; return 1;

    // $Mailer             = new Mailer();
    // $Mailer->to         = $order->restaurant_email;
    // $Mailer->toName     = $order->restaurant_name;
    // $Mailer->subject    = "New registration";
    // $Mailer->body       = $html;

    // $Mailer->send();
}

function sendNewOrderEmail2Customer($request_id)
{
    $order = DB::table('requests')
        ->leftJoin('users', 'users.id', '=', 'requests.user_id')
        ->select('requests.*', 'users.name as user_name', 'users.email as user_email')
        ->where('requests.id', $request_id)->first();

    $template = DB::table('mail_contents')->where("content_for", "invoicemail")->get();
    $cont = [];
    foreach ($template as $temp) {
        $cont[] = $temp->content;
    }
    $logo = DB::table('settings')->where('key_word', "site_logo")->first();
    $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
    $url = BASE_URL . "/admin/new";

    $html = view('emails.user.invoice')->with(['order' => $order, 'template' => $cont, 'logo' => $to_logo, 'url' => $url]);
    dd($html);
    $Mailer             = new Mailer();
    $Mailer->to         = $order->user_name;
    $Mailer->toName     = $order->user_email;
    $Mailer->subject    = trans('emails.user_new_order_sub');
    $Mailer->body       = $html;

    $Mailer->send();
}

function sendOrderCancelledEmail2Customer($request_id)
{
    $order = DB::table('requests')
        ->leftJoin('users', 'users.id', '=', 'requests.user_id')
        ->select('requests.*', 'users.name as user_name', 'users.email as user_email')
        ->where('requests.id', $request_id)->first();

    $html = view('emails.user.order-cancelled')->with(['order' => $order]);

    $Mailer             = new Mailer();
    $Mailer->to         = $order->user_name;
    $Mailer->toName     = $order->user_email;
    $Mailer->subject    = trans('emails.user_order_cancelled_sub');
    $Mailer->body       = $html;

    $Mailer->send();
}

function sendForgotPasswordEmail2Restaurant($user_id)
{
    $user = DB::table('users')->where('id', $user_id)->first();

    $html = view('emails.user.forgot')->with(['name' => $user->name, 'id' => $user->id, 'code' => $user->password_reset_key]);

    $Mailer             = new Mailer();
    $Mailer->to         = $user->email;
    $Mailer->toName     = $user->name;
    $Mailer->subject    = trans('emails.user_forgot_password_sub');
    $Mailer->body       = $html;

    $Mailer->send();
}

function vincentyGreatCircleDistance(
    $latitudeFrom,
    $longitudeFrom,
    $latitudeTo,
    $longitudeTo,
    $earthRadius = 6371000
) {

    $latFrom         = deg2rad($latitudeFrom);
    $lonFrom         = deg2rad($longitudeFrom);
    $latTo             = deg2rad($latitudeTo);
    $lonTo             = deg2rad($longitudeTo);

    $lonDelta         = $lonTo - $lonFrom;
    $a                 = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b                 = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

    $angle             = atan2(sqrt($a), $b);

    $distance_in_km = ($angle * $earthRadius) / 1000;
    $distance_in_km = (float) number_format($distance_in_km, 1, '.', '');

    return $distance_in_km;
}

function vincentyGreatCircleDistance2(
    $latitudeFrom,
    $longitudeFrom,
    $latitudeTo,
    $longitudeTo,
    $earthRadius = 6371000
) {

    $latFrom         = deg2rad($latitudeFrom);
    $lonFrom         = deg2rad($longitudeFrom);
    $latTo             = deg2rad($latitudeTo);
    $lonTo             = deg2rad($longitudeTo);

    $lonDelta         = $lonTo - $lonFrom;
    $a                 = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b                 = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

    $angle             = atan2(sqrt($a), $b);
    // dd(($angle * $earthRadius));

    $distance_in_km = ($angle * $earthRadius) / 1000;
    $distance_in_km = (float) number_format($distance_in_km, 1, '.', '');

    return $distance_in_km;
}
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function moneyFormat($amount)
{
    return (string) number_format($amount, 2, '.', '');
}

function moneyWithoutDecimalSeperator($amount)
{
    return (int) number_format($amount, 2, '', '');
}

function noww()
{
    return date('Y-m-d H:i:s');
}

function cimplode($array)
{
    return implode(', ', $array);
}

function shouldDisplayInactiveSidebar(): bool
{

    $authID = (int) (\Session::get('res_id'));
    if ($authID) {
        $store = DB::table('restaurants')->where('id', $authID)->first();
        return $store->status == 0;
    }

    return false;
}
function distance($lat1, $lon1, $lat2, $lon2, $unit = 'k')
{
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
    } else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}

function getAllMenus($restaurantID)
{
    $arr = DB::table('menu')->select('id', 'menu_name')->where('restaurant_id', $restaurantID)->get()->toArray();
    return $arr;
}

function xPercentageOfY(float $percentage, float $value): float
{
    return ($value / 100) * $percentage;
}

function distanceMatrix($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo): float
{


    $q = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$latitudeFrom,$longitudeFrom&destinations=$latitudeTo,$longitudeTo&mode=driving&sensor=false&key=" . GOOGLE_API_KEY;
    $result = file_get_contents($q);
    $details = json_decode($result, TRUE);

    if (isset($details['rows'][0]['elements'][0]['status']) && $details['rows'][0]['elements'][0]['status'] != 'ZERO_RESULTS') {

        $current_distance = $details['rows'][0]['elements'][0]['distance']['value'] ?? 0;
        $current_distance = $current_distance / 1000;

        return $current_distance;
    }

    return 0;
}

function sendtopupmail($code, $amount, $user_detail)
{

    $template = DB::table('mail_contents')->where("content_for", "wallet_mail")->get();
    $cont = [];
    foreach ($template as $temp) {
        $cont[] = $temp->content;
    }
    $logo = DB::table('settings')->where('key_word', "site_logo")->first();
    $to_logo =  BASE_URL . RESTAURANT_UPLOADS_PATH . $logo->value;
    $url = BASE_URL . "/admin/new";
    // $html = view('emails.restaurant.order-cancelled')->with(['order' => $order,'template'=>$cont,'logo'=>$to_logo,'url'=>$url]);
    $data = DB::table('country')->where('is_default', 1)->first();
    $curr =  $data->currency_symbol;

    $html = view('emails.user.wallet')->with(['code' => $code, 'user' => $user_detail, 'amount' => $amount, 'template' => $cont, 'logo' => $to_logo, 'url' => $url, 'currency' => $curr]);
    // $emails = ["mariyal@bytesflow.com","rajeshben"];
    $neworder = DB::table('mail_ids')->where("mail_for", "wallet_otp")->first();

    $emails = json_decode($neworder->mail_ids);
    $title = "Code For TOPUP";
    for ($i = 0; $i < count($emails); $i++) {
        $Mailer             = new Mailer();
        $Mailer->to         = $emails[$i];
        $Mailer->toName     = "TOPUP-code";
        $Mailer->subject    = $title;
        $Mailer->body       = $html;

        $Mailer->send();
    }
}
function stockRevice($tdy)
{
    $date = DB::table('food_list')->where('status', 1)->select('stock_date')->first();
    $tdy_date = $tdy->toDateString();
    $food_date = formatDate($date->stock_date);
    if ($tdy_date == $food_date) {
        return 1;
    } else {
        DB::table('food_list')->update(['stock_date' => $tdy_date, 'out_of_stock' => 0]);
        return 1;
    }
}


function checkAdmin()
{
    $admin = DB::table('admin_role')->where('id', session()->get('role_rest_id'))->first(); //dd($admin);
    if ($admin) {
        $ids = json_decode($admin->restaurant_id);
        return $ids;
    } else {
        return 0;
    }
}

function permission($id, $val)
{
    $permission = DB::table('privileges')->where('role_id', $id)->select('name', 'role_id')->get();
    foreach ($permission as $p) {
        if ($p->name == $val) {
            return 1;
        }
    }
    return 0;
}
function call_customer($number)
{

    $t_sid = DB::table('settings')->where('key_word', "t_sid")->first();
    $t_token = DB::table('settings')->where('key_word', "t_token")->first();
    $t_from = DB::table('settings')->where('key_word', "t_from")->first();

    $sid = $t_sid->value;
    $token = $t_token->value;
    $t_from = $t_from->value;
    $to = "+919585966881";

    try {
        $client = new Client($sid, $token);

        $call = $client->calls
            ->create(
                $number, // to
                $t_from, // from
                [
                    "twiml" => "<Response><Say>Some Items in your Order is in Out of Stock . Please replace the Items</Say></Response>"
                ]
            );
    } catch (\Exception  $e) {
        return 0;
    }

    return 1;
}


function secondLanguage_user($name, $second)
{

    $locale = session('locale_user');

    $sec =  DB::table('settings')->where('key_word', 'secondary_language')->first();
    $secondary = $sec->value;
    if ($locale == $secondary) {

        $value = html_entity_decode($second);

        if ($value) {
            return $value;
        }
    }
    if ($name) {
        $name  = html_entity_decode($name);
    }

    return $name;
}
function get_image($each_food, $restaurant_id)
{

    $rest_id = DB::table('restaurants')->where('id', $restaurant_id)->first();
    $business_id = DB::table('business_type')->where('id', $rest_id->business_type)->first();
    $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
    $url = "";
    $acc_hash = "";

    if ($cloudflare->value == 1) {
        $get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
        $url = $get_url->value;

        $get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
        $acc_hash = $get_acchash->value;
        if ($each_food->image != "" && $each_food->image != "undefined") {

            if ($cloudflare->value == 1 && $each_food->cloudflare_imageid != null) {

                $image[0] = $url . '/' . $acc_hash . '/' . $each_food->cloudflare_imageid . '/' . "w=500";
            } else {

                if (str_starts_with($each_food->image, 'http')) {
                    $image[0] = $each_food->image;
                } else {
                    $image[0] = File::exists(public_path('product_image/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $each_food->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $each_food->image : BASE_URL  . $each_food->image));
                }
            }
        } else {
            $image[0] = BASE_URL  . "fork.svg";
            if ($business_id->layout_id == 2) {
                $image[0] = BASE_URL  . "no-storeprod.jpg";
            } else {
                $image[0] = BASE_URL  . "fork.svg";
            }
        }
    }

    if ($each_food->image != "") {
        $image[0] = File::exists(public_path('product_image/') . $each_food->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $each_food->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $each_food->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $each_food->image : (File::exists(public_path('common_images/') . $each_food->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $each_food->image : BASE_URL  . $each_food->image));
    } else {
        $image[0] = BASE_URL  . "fork.svg";
    }

    return $image;
}

function get_more_image($each_food, $restaurant_id)
{
    $cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
    $url = "";
    $acc_hash = "";
    $featured = [];
    $featured[] = get_image($each_food, $restaurant_id)[0];
    $other_images = DB::table('product_images')->where('product_id', $each_food->food_id)->get();
    if (count($other_images) > 0) {
        foreach ($other_images as $oi) {
            $featured[]  = File::exists(public_path('product_image/') . $oi->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $oi->image : (File::exists(public_path('restaurant_uploads/') . $restaurant_id . '/' . 'Product/' . $oi->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $restaurant_id . '/' . 'Product/' . $oi->image : (File::exists(public_path('common_images/') . $oi->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $oi->image : ''));
        }
    }

    return $featured;
}

function get_addons($each_food, $restaurant_id)
{
    $this_addon = DB::table('foodlist_addons')
        ->join('add_ons', 'add_ons.id', '=', 'foodlist_addons.addons_id')
        ->where('foodlist_id', $each_food->food_id)
        ->select('foodlist_addons.addons_id as id', 'add_ons.name',  'add_ons.secondary_name', 'add_ons.price', 'foodlist_addons.created_at', 'foodlist_addons.updated_at')
        ->get()->toArray();

    if (!$this_addon) {
        return [];
    }

    foreach ($this_addon as $addon_key => $each_addon) {
        $this_addon[$addon_key]->restaurant_id = $restaurant_id;
        $this_addon[$addon_key]->pivot = ['foodlist_id' => $each_food->food_id, 'addons_id' => $each_addon->id];
    }
    return $this_addon;
}

function get_groups($each_food, $restaurant_id)
{
    $this_gaddon = DB::table('foodlist_addons')
        ->where('foodlist_id', $each_food->food_id)
        ->where('addons_id', null)
        ->get();
    $add_group = [];

    foreach ($this_gaddon as $addon_key => $each_addon) {
        $gadd_ons = [];

        if ($each_addon->addon_group != null) {
            $group = DB::table('addon_group')->where('id', $each_addon->addon_group)->first();
            $array_group = json_decode($group->add_ons);

            foreach ($array_group as $ag) {
                $addon_detail = DB::table('add_ons')->where('id', $ag)->first();


                $gadd_ons[] = [
                    'id' => $ag,
                    'name' => secondLanguage_user($addon_detail->name, $addon_detail->secondary_name),
                    'secondary_name' => $addon_detail->secondary_name,
                    'price' => $addon_detail->price

                ];
            }
            $add_group[] = [
                'id' => $each_addon->addon_group,
                'name' => secondLanguage_user($group->name, $group->secondary_name),
                'min' => $group->min,
                'max' => $group->max,
                'allow_multiple' => $group->allow_multiple,
                'mandatory' => $group->mandatory,
                'group_addons' => $gadd_ons

            ];
        }
    }
    return $add_group;
}
function get_food_quantity($each_food, $restaurant_id)
{




    $this_size = DB::table('foodlist_foodquantity')
        ->join('food_quantity', 'food_quantity.id', '=', 'foodlist_foodquantity.foodquantity_id')
        ->where('foodlist_id', $each_food->food_id)
        ->select(
            'foodlist_foodquantity.foodquantity_id as id',
            'food_quantity.name',
            'food_quantity.secondary_name',
            'foodlist_foodquantity.price',
            'foodlist_foodquantity.is_default',
            'foodlist_foodquantity.created_at',
            'foodlist_foodquantity.updated_at',
            'foodlist_foodquantity.addons_id',
            'foodlist_foodquantity.addon_group',
        )
        ->get()->toArray();

    if (!$this_size) {
        return [];
    }
    foreach ($this_size as $size_key => $each_size) {
        $sadd_group = [];

        if ($each_size->name) {
            $each_size->name = secondLanguage_user($each_size->name, $each_size->secondary_name);
        }
        if ($each_size->addon_group != null) {
            $sg = $each_size->addon_group;
            $sg_group = json_decode($sg);



            foreach ($sg_group as $sgg) {
                $sgadd_ons = [];
                $sgg_group = DB::table('addon_group')->where('id', $sgg)->first();

                $sarray_group = json_decode($sgg_group->add_ons);
                foreach ($sarray_group as $sag) {
                    $saddon_detail = DB::table('add_ons')->where('id', $sag)->first();
                    $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('group_id', $sgg)->where('addon', $sag)->first();
                    if ($variant) {
                        $price = $variant->price;
                        $stock = $variant->stock;
                    } else {
                        $price = $saddon_detail->price;
                        $stock = 0;
                    }
                    $sgadd_ons[] = [
                        'id' => $sag,
                        'name' => secondLanguage_user($saddon_detail->name, $saddon_detail->secondary_name),
                        'secondary_name' => $saddon_detail->secondary_name,
                        'price' => $price,
                        'stock' => $stock
                    ];
                }
                $sadd_group[] = [
                    'id' => $sgg,
                    'name' => secondLanguage_user($sgg_group->name, $sgg_group->secondary_name),
                    'min' => $sgg_group->min,
                    'max' => $sgg_group->max,
                    'allow_multiple' => $sgg_group->allow_multiple,
                    'mandatory' => $sgg_group->mandatory,
                    'group_addons' => $sgadd_ons

                ];
            }
        }
        $this_size[$size_key]->groups = $sadd_group;
        $saa_addons = [];

        if ($each_size->addons_id != null) {
            $sa = $each_size->addons_id;
            $sa_group = json_decode($sa);

            foreach ($sa_group as $saa) {
                $sa_detail = DB::table('add_ons')->where('id', $saa)->first();
                $variant = DB::table('foodlist_foodquantity_addons')->where('food_list', $each_food->food_id)->where('food_quantity', $each_size->id)->where('addon', (int)$saa)->first();
                if ($variant) {
                    $price = $variant->price;
                    $stock = $variant->stock;
                } else {
                    $price = $sa_detail->price;
                    $stock = 0;
                }
                $saa_addons[] = [
                    'id' => $saa,
                    'name' => secondLanguage_user($sa_detail->name, $sa_detail->secondary_name),
                    'secondary_name' => $sa_detail->secondary_name,
                    'price' => $price,
                    'stock' => $stock
                ];
            }
        }
        $this_size[$size_key]->add_ons = $saa_addons;

        $this_size[$size_key]->pivot = [
            'foodlist_id' => $each_food->food_id,
            'foodquantity_id' => $each_size->id,
            'price' => $each_size->price,
            'is_default' => $each_size->is_default
        ];
    }
    return $this_size;
}
function updateMenuName_web($obj)
{

    $locale = session('locale');
    $sec =  DB::table('settings')->where('key_word', 'secondary_language')->first();
    $secondary = $sec->value;

    if ($locale == $secondary) {
        $value =  html_entity_decode($obj->secondary_menu_name);

        if ($value) {
            return $value;
        }
    }
    if ($obj->menu_name) {
        $obj->menu_name = html_entity_decode($obj->menu_name);
    }
    return $obj->menu_name;
}
function featured($restaurant_id)
{
    $is_veg = 0;
    $rest_id = DB::table('restaurants')->where('id', $restaurant_id)->first();
    $business_id = DB::table('business_type')->where('id', $rest_id->business_type)->first();

    $city_id = $rest_id->city;

    // Step 1: Build pricing subquery with correct district filter
    $pricingSubquery = DB::table('food_list_pricing_district')
        ->join('food_list_pricing', 'food_list_pricing.id', '=', 'food_list_pricing_district.product_pricing_id')
        ->where('food_list_pricing_district.district_id', $city_id) // ✅ Correct district filtering
        ->select(
            'food_list_pricing.product_id',
            'food_list_pricing.price',
            'food_list_pricing.tax',
            'food_list_pricing.label'
        );

    // Step 2: Main query
    $food_list = DB::table('food_list')
        ->where('food_list.status', 1)
        ->where('food_list.featured', 1)

        // Menu join
        ->leftJoin('menu', 'menu.id', '=', 'food_list.menu_id')

        // ✅ Correct pricing subquery join
        ->leftJoinSub($pricingSubquery, 'pricing', function ($join) {
            $join->on('pricing.product_id', '=', 'food_list.id');
        })

        // Select with pricing override
        ->select(
            'food_list.id as food_id',
            'food_list.name',
            'food_list.secondary_name',
            'pricing.price', // district-based price
            'food_list.secondary_description',
            'food_list.description',
            'food_list.category_id',
            'food_list.is_veg',
            'pricing.tax as item_tax', // district-based tax
            'menu.menu_name',
            'menu.id as menu_id',
            'food_list.image',
            // DB::raw('COALESCE(food_list.initial_price, 0) as initial_price'),
            // DB::raw('COALESCE(food_list.split_payment, 0) as split_payment'),
            // DB::raw('COALESCE(food_list.bprice, 0) as bprice'),
            'food_list.out_of_stock',
            'pricing.label'
        )

        // Sort
        ->orderBy('rank')
        ->orderBy('image', 'desc')
        ->get();

    // if ($business_id->layout_id == 2) {
    //     $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
    //         ->where('food_list.status', 1)->where('food_list.featured', 1)
    //         ->join(
    //             'category',
    //             function ($join) {
    //                 $join->on('category.id', '=', 'food_list.category_id');
    //             }
    //         )
    //         ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'category.category_name', 'category.category_secondaryname', 'category.image as cat_image', 'food_list.image as image', 'food_list.cloudflare_imageid as cloudflare_imageid', 'food_list.initial_price', 'food_list.split_payment', 'food_list.bprice', 'food_list.out_of_stock')
    //         ->orderBy('rank')->orderBy('image', 'desc')
    //         ->get();
    // } else {
    //     $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
    //         ->where('food_list.status', 1)->where('food_list.featured', 1)
    //         ->leftjoin(
    //             'menu',
    //             function ($join) {
    //                 $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
    //                 $join->on('menu.id', '=', 'food_list.menu_id');
    //             }
    //         )
    //         ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image', 'food_list.initial_price', 'food_list.split_payment', 'food_list.bprice', 'food_list.out_of_stock')
    //         ->orderBy('rank')->orderBy('image', 'desc')
    //         ->get();
    // }


    // $rest_details = DB::table('restaurants')->where('id', $restaurant_id)->first();

    foreach ($food_list as $key => $each_food) {
        $each_food->image = get_image($each_food, $restaurant_id);
        $food_list[$key]->add_ons = []; // get_addons($each_food, $restaurant_id);
        $food_list[$key]->groups = []; // get_groups($each_food, $restaurant_id);
        $food_list[$key]->food_quantity = []; // get_food_quantity($each_food, $restaurant_id);
        $food_list[$key]->restaurant = $rest_id->id;
        $food_list[$key]->restaurant_name = $rest_id->restaurant_name;
        $food_list[$key]->restaurant_address = $rest_id->address;
        $food_list[$key]->restaurant_image = $rest_id->image;
        $food_list[$key]->restaurant_packaging_charge = $rest_id->packaging_charge;
        $food_list[$key]->id = $food_list[$key]->food_id;
        $food_list[$key]->isveg = $food_list[$key]->is_veg;

        $food_list[$key]->cart_status = $rest_id->cart;


        $each_food->slug = strtolower(str_replace(' ', '-', $each_food->name) . '-' . $food_list[$key]->id);
        // if ($each_food->price < $each_food->bprice) {
        //     $each_food->disc_value = number_format((100 - ($each_food->price / $each_food->bprice) * 100), 2);
        // } else {
        $each_food->disc_value = "0";
        // }
    }

    if ($business_id->layout_id == 2) {

        foreach ($food_list as $key => $each_menu) {


            if ($food_list[$key]->out_of_stock == 1) {
                $food_list[$key]->menu_available = 0;
            } else {
                $food_list[$key]->menu_available = 1;
            }
        }
    } else {

        foreach ($food_list as $key => $each_menu) {
            $food_list[$key]->menu_available = menu_availability($each_menu);
        }
    }
    $rt_list = [];
    $count = 0;
    foreach ($food_list as $fl) {
        if ($count < 8) {
            $rt_list[] = $fl;
        }

        $count++;
    }
    return $rt_list;
}

function recent($restaurant_id)
{
    $is_veg = 0;
    $rest_id = DB::table('restaurants')->where('id', $restaurant_id)->first();
    $business_id = DB::table('business_type')->where('id', $rest_id->business_type)->first();

    $city_id = $rest_id->city;

    // Step 1: Build pricing subquery with correct district filter
    $pricingSubquery = DB::table('food_list_pricing_district')
        ->join('food_list_pricing', 'food_list_pricing.id', '=', 'food_list_pricing_district.product_pricing_id')
        ->where('food_list_pricing_district.district_id', $city_id) // ✅ Correct district filtering
        ->select(
            'food_list_pricing.product_id',
            'food_list_pricing.price',
            'food_list_pricing.tax',
            'food_list_pricing.label'
        );

    // Step 2: Main query
    $food_list = DB::table('food_list')
        ->where('food_list.status', 1)
        ->where('food_list.featured', 1)

        // Menu join
        ->leftJoin('menu', 'menu.id', '=', 'food_list.menu_id')

        // ✅ Correct pricing subquery join
        ->leftJoinSub($pricingSubquery, 'pricing', function ($join) {
            $join->on('pricing.product_id', '=', 'food_list.id');
        })

        // Select with pricing override
        ->select(
            'food_list.id as food_id',
            'food_list.name',
            'food_list.secondary_name',
            'pricing.price', // district-based price
            'food_list.secondary_description',
            'food_list.description',
            'food_list.category_id',
            'food_list.is_veg',
            'pricing.tax as item_tax', // district-based tax
            'menu.menu_name',
            'menu.id as menu_id',
            'food_list.image',
            // DB::raw('COALESCE(food_list.initial_price, 0) as initial_price'),
            // DB::raw('COALESCE(food_list.split_payment, 0) as split_payment'),
            // DB::raw('COALESCE(food_list.bprice, 0) as bprice'),
            'food_list.out_of_stock',
            'pricing.label'
        )

        // Sort
        ->orderBy('rank')
        ->orderBy('image', 'desc')
        ->get();


    // if ($business_id->layout_id == 2) {
    //     $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
    //         ->where('food_list.status', 1)
    //         ->join(
    //             'category',
    //             function ($join) {
    //                 $join->on('category.id', '=', 'food_list.category_id');
    //             }
    //         )
    //         ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'category.category_name', 'category.category_secondaryname', 'category.image as cat_image', 'food_list.image as image', 'food_list.cloudflare_imageid as cloudflare_imageid', 'food_list.initial_price', 'food_list.split_payment', 'food_list.bprice', 'food_list.out_of_stock')
    //         ->orderBy('rank')->orderBy('image', 'desc')
    //         ->get();
    // } else {
    //     $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
    //         ->where('food_list.status', 1)
    //         ->leftjoin(
    //             'menu',
    //             function ($join) {
    //                 $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
    //                 $join->on('menu.id', '=', 'food_list.menu_id');
    //             }
    //         )
    //         ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image', 'food_list.initial_price', 'food_list.split_payment', 'food_list.bprice', 'food_list.out_of_stock')
    //         ->orderBy('rank')->orderBy('image', 'desc')
    //         ->get();
    // }

    // $rest_details = DB::table('restaurants')->where('id', $restaurant_id)->first();

    foreach ($food_list as $key => $each_food) {
        $each_food->image = get_image($each_food, $restaurant_id);
        $food_list[$key]->add_ons = []; // get_addons($each_food, $restaurant_id);
        $food_list[$key]->groups = []; // get_groups($each_food, $restaurant_id);
        $food_list[$key]->food_quantity = []; // get_food_quantity($each_food, $restaurant_id);
        $food_list[$key]->restaurant = $rest_id->id;
        $food_list[$key]->restaurant_name = $rest_id->restaurant_name;
        $food_list[$key]->restaurant_address = $rest_id->address;
        $food_list[$key]->restaurant_image = $rest_id->image;

        $food_list[$key]->id = $food_list[$key]->food_id;
        $food_list[$key]->isveg = $food_list[$key]->is_veg;
        $food_list[$key]->cart_status = $rest_id->cart;

        $each_food->slug = strtolower(str_replace(' ', '-', $each_food->name) . '-' . $food_list[$key]->id);

        // if ($each_food->price < $each_food->bprice) {
        //     $each_food->disc_value = number_format((100 - ($each_food->price / $each_food->bprice) * 100), 2);
        // } else {
        $each_food->disc_value = "0";
        // }
    }
    if ($business_id->layout_id == 2) {
        foreach ($food_list as $key => $each_menu) {
            if ($food_list[$key]->out_of_stock == 1) {
                $food_list[$key]->menu_available = 0;
            } else {
                $food_list[$key]->menu_available = 1;
            }
        }
    } else {

        foreach ($food_list as $key => $each_menu) {

            $food_list[$key]->menu_available = menu_availability($each_menu);
        }
    }
    $rt_list = [];
    $count = 0;
    foreach ($food_list as $fl) {
        if ($count < 8) {
            $rt_list[] = $fl;
        }

        $count++;
    }
    return $rt_list;
}
function menu_availability($food_list)
{
    if ($food_list->out_of_stock == 1) {
        return 0;
    }
    $menu_id = $food_list->menu_id;

    $available = 0;
    $next_available = 0;
    $weekMap = [
        0 => 'SUNDAY',
        1 => 'MONDAY',
        2 => 'TUESDAY',
        3 => 'WEDNESDAY',
        4 => 'THURSDAY',
        5 => 'FRIDAY',
        6 => 'SATURDAY',
    ];
    $menutime = DB::table('menu_timing')->where('menu_id', $menu_id)->get();
    if (sizeof($menutime)) {
        foreach ($menutime as $key => $time) {
            if ($available != 1) {
                $available_day = json_decode($time->available_day);
                if ($available_day != null) {
                    $times = Carbon::now();
                    $today = Carbon::now()->dayOfWeek;
                    $yes = in_array($today, $available_day);
                    if ($yes == false) {
                        $available = 0;
                        if ((int)$today == 6) {
                            $nt_date = $weekMap[$available_day[0]];
                            $current_time = $times->toTimeString();
                            $date1 = $current_time;
                            $date2 = $time->from_time;
                            $tt = Carbon::parse($date2);
                            $next_available = $nt_date . "  " . $tt->format('g:i A');
                        } else {
                            foreach ($available_day as $ad) {
                                if ((int)$ad > (int)$today) {
                                    $nt_date = $weekMap[$ad];
                                    $current_time = $times->toTimeString();
                                    $date1 = $current_time;
                                    $date2 = $time->from_time;
                                    $tt = Carbon::parse($date2);

                                    $next_available = $nt_date . "  " . $tt->format('g:i A');
                                }
                            }
                        }
                    } else {
                        $current_time = $times->toTimeString();
                        $date1 = $current_time;
                        $date2 = $time->from_time;
                        $date3 = $time->to_time;
                        if ($date1 > $date2 && $date1 < $date3) {
                            $available = 1;
                        } else {
                            $available = 0;
                            $next_available = $date2;
                        }
                    }
                } elseif ($time->all_days == 1) {
                    $times = Carbon::now();
                    $current_time = $times->toTimeString();
                    $date1 = $current_time;
                    $date2 = $time->from_time;
                    $date3 = $time->to_time;
                    if ($date1 > $date2 && $date1 < $date3) {
                        $available = 1;
                    } else {
                        $available = 0;
                        $next_available = $date2;
                    }
                } else {
                    $available = 1;
                }
            }
        }
    } else {
        $available = 1;
    }
    return 1;
}
function product_details($id, $res)
{
    $city_id = $res->city;
    $res_id = $res->id;
    $pricingSubquery = DB::table('food_list_pricing_district')
        ->join('food_list_pricing', 'food_list_pricing.id', '=', 'food_list_pricing_district.product_pricing_id')
        ->where('food_list_pricing_district.district_id', $city_id) // ✅ Correct district filtering
        ->select(
            'food_list_pricing.product_id',
            'food_list_pricing.price',
            'food_list_pricing.tax',
            'food_list_pricing.label'
        );

    // Step 2: Main query
    $food_list = DB::table('food_list')
        ->where('food_list.id', $id)

        // Menu join
        ->leftJoin('menu', 'menu.id', '=', 'food_list.menu_id')

        // ✅ Correct pricing subquery join
        ->leftJoinSub($pricingSubquery, 'pricing', function ($join) {
            $join->on('pricing.product_id', '=', 'food_list.id');
        })

        // Select with pricing override
        ->select(
            'food_list.id as food_id',
            'food_list.name',
            'food_list.secondary_name',
            'pricing.price', // district-based price
            'food_list.secondary_description',
            'food_list.description',
            'food_list.category_id',
            'food_list.is_veg',
            'pricing.tax as item_tax', // district-based tax
            'menu.menu_name',
            'menu.id as menu_id',
            'food_list.image',
            // DB::raw('COALESCE(food_list.initial_price, 0) as initial_price'),
            // DB::raw('COALESCE(food_list.split_payment, 0) as split_payment'),
            // DB::raw('COALESCE(food_list.bprice, 0) as bprice'),
            'food_list.out_of_stock',
            'pricing.label'
        )

        // Sort
        ->orderBy('rank')
        ->orderBy('image', 'desc')
        ->first();

    // $food_list = DB::table('food_list')->where('food_list.id', $id)
    //     ->leftjoin(
    //         'menu',
    //         function ($join) {
    //             $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
    //             $join->on('menu.id', '=', 'food_list.menu_id');
    //         }
    //     )
    //     ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image', 'food_list.restaurant_id as restaurant_id', 'food_list.initial_price', 'food_list.split_payment', 'food_list.bprice', 'food_list.out_of_stock')
    //     ->orderBy('rank')->orderBy('image', 'desc')
    //     ->first();
    $food_list2 = DB::table('food_list')->where('food_list.id', $id)->first();

    // print_r($food_list);
    // exit;

    if ($food_list && $food_list2) {
        // $food_list->image = get_image($food_list, $food_list->restaurant_id);

        // $food_list->restaurant = $food_list->restaurant_id;
        $food_list->add_ons = get_addons($food_list, $res_id);
        $food_list->groups = get_groups($food_list, $res_id);
        $food_list->food_quantity = get_food_quantity($food_list, $res_id);
        $food_list->menu_available = menu_availability($food_list);
        $food_list->image = get_more_image($food_list, $res_id);

        // $food_list->restaurant = $food_list->restaurant_id;
        // $food_list->add_ons = get_addons($food_list, $food_list->restaurant_id);
        // $food_list->groups = get_groups($food_list, $food_list->restaurant_id);
        // $food_list->food_quantity = get_food_quantity($food_list, $food_list->restaurant_id);
        // $food_list->menu_available = menu_availability($food_list);
        // $food_list->image = get_more_image($food_list, $food_list->restaurant_id);

        $category = DB::table('business_category')->where('id', $food_list2->business_category_id)->first();
        // $subcategory = DB::table('category')->where('id', $food_list2->category_id)->first();

        if ($category) {
            $food_list->category_name = $category->category_name;
            $food_list->cat_id = $category->id;
            $food_list->slug =  strtolower(str_replace(' ', '-', $category->category_name) . '-' . $category->id);
            // $food_list->sub_slug =  strtolower(str_replace(' ', '-', $category->category_name) . '-' . $category->id . '-' . $subcategory->id);
            $food_list->sub_slug =  strtolower(str_replace(' ', '-', $category->category_name) . '-' . $category->id . '-' . $id);
        }


        // if ($subcategory) {
        //     $food_list->sub_category_name = $subcategory->category_name;
        //     $food_list->sub_category_id = $subcategory->id;
        // }

        // if ($food_list->price < $food_list->bprice) {
        //     $food_list->disc_value = number_format((100 - ($food_list->price / $food_list->bprice) * 100), 2);
        // } else {
        $food_list->disc_value = "0";
        // }

        // if ($food_list->split_payment == 1) {
        //     $food_list->pending_payment = $food_list->price - $food_list->initial_price;
        // } else {
        $food_list->pending_payment = 0;
        // }

        $pending_payment_text = DB::table('settings')->where('key_word', 'pending_payment_text')->first();
        $food_list->pending_payment_text = $pending_payment_text->value;
        $food_list->item_count = 0;
    }


    return $food_list;
}

function cat_products($restaurant_id, $cat_id)
{
    $is_veg = 0;
    $rest_id = DB::table('restaurants')->where('id', $restaurant_id)->first();

    $business_id = DB::table('business_type')->where('id', $rest_id->business_type)->first();
    $food_list = DB::table('food_list')->where('food_list.restaurant_id', $restaurant_id)
        ->where('food_list.status', 1)
        ->where('food_list.business_category_id', $cat_id)
        ->leftjoin(
            'menu',
            function ($join) {
                $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
                $join->on('menu.id', '=', 'food_list.menu_id');
            }
        )
        ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image', 'food_list.initial_price', 'food_list.split_payment', 'food_list.bprice', 'food_list.out_of_stock')
        ->orderBy('rank')->orderBy('image', 'desc')
        ->get();

    $rest_details = DB::table('restaurants')->where('id', $restaurant_id)->first();

    foreach ($food_list as $key => $each_food) {
        $each_food->image = get_image($each_food, $restaurant_id);
        $food_list[$key]->add_ons = get_addons($each_food, $restaurant_id);
        $food_list[$key]->groups = get_groups($each_food, $restaurant_id);
        $food_list[$key]->food_quantity = get_food_quantity($each_food, $restaurant_id);
        $food_list[$key]->restaurant = $rest_details->id;
        $food_list[$key]->restaurant_name = $rest_details->restaurant_name;
        $food_list[$key]->restaurant_address = $rest_details->address;
        $food_list[$key]->restaurant_image = $rest_details->image;
        $food_list[$key]->id = $food_list[$key]->food_id;
        $food_list[$key]->isveg = $food_list[$key]->is_veg;
        $food_list[$key]->cart_status = $rest_id->cart;

        $each_food->slug = strtolower(str_replace(' ', '-', $each_food->name) . '-' . $food_list[$key]->id);
    }
    if ($business_id->layout_id == 2) {
        foreach ($food_list as $key => $each_menu) {
            if ($food_list[$key]->out_of_stock == 1) {
                $food_list[$key]->menu_available = 0;
            } else {
                $food_list[$key]->menu_available = 1;
            }
        }
    } else {

        foreach ($food_list as $key => $each_menu) {
            $food_list[$key]->menu_available = menu_availability($each_menu);
        }
    }
    foreach ($food_list as $key => $each_menu) {
        if ($food_list[$key]->price < $food_list[$key]->bprice) {
            $food_list[$key]->disc_value = number_format((100 - ($food_list[$key]->price / $food_list[$key]->bprice) * 100), 2);
        } else {
            $food_list[$key]->disc_value = "0";
        }
    }
    return $food_list;
}

function get_subcat_products($res_id, $id)
{
    $is_veg = 0;
    $rest_id = DB::table('restaurants')->where('id', $res_id)->first();
    $restaurant_id = $rest_id->id;
    $business_id = DB::table('business_type')->where('id', $rest_id->business_type)->first();

    $city_id = $rest_id->city;

    // Step 1: Build pricing subquery with correct district filter
    $pricingSubquery = DB::table('food_list_pricing_district')
        ->join('food_list_pricing', 'food_list_pricing.id', '=', 'food_list_pricing_district.product_pricing_id')
        ->where('food_list_pricing_district.district_id', $city_id) // ✅ Correct district filtering
        ->select(
            'food_list_pricing.product_id',
            'food_list_pricing.price',
            'food_list_pricing.tax',
            'food_list_pricing.label'
        );

    // Step 2: Main query
    $food_list = DB::table('food_list')
        ->where('food_list.status', 1)
        ->where('food_list.business_category_id', $id)

        // Menu join
        ->leftJoin('menu', 'menu.id', '=', 'food_list.menu_id')

        // ✅ Correct pricing subquery join
        ->leftJoinSub($pricingSubquery, 'pricing', function ($join) {
            $join->on('pricing.product_id', '=', 'food_list.id');
        })

        // Select with pricing override
        ->select(
            'food_list.id as food_id',
            'food_list.name',
            'food_list.secondary_name',
            'pricing.price', // district-based price
            'food_list.secondary_description',
            'food_list.description',
            'food_list.category_id',
            'food_list.is_veg',
            'pricing.tax as item_tax', // district-based tax
            'menu.menu_name',
            'menu.id as menu_id',
            'food_list.image',
            // DB::raw('COALESCE(food_list.initial_price, 0) as initial_price'),
            // DB::raw('COALESCE(food_list.split_payment, 0) as split_payment'),
            // DB::raw('COALESCE(food_list.bprice, 0) as bprice'),
            'food_list.out_of_stock',
            'pricing.label'
        )

        // Sort
        ->orderBy('rank')
        ->orderBy('image', 'desc')
        ->get();

    // $food_list = DB::table('food_list')
    //     ->where('food_list.restaurant_id', $res_id)
    //     ->where('food_list.status', 1)
    //     // ->where('food_list.category_id', $id)
    //     ->where('food_list.business_category_id', $id)
    //     ->leftjoin(
    //         'menu',
    //         function ($join) {
    //             $join->on('menu.restaurant_id', '=', 'food_list.restaurant_id');
    //             $join->on('menu.id', '=', 'food_list.menu_id');
    //         }
    //     )
    //     ->select('food_list.id as food_id', 'name', 'secondary_name', 'price', 'secondary_description',  'description', 'food_list.category_id', 'is_veg', 'tax as item_tax', 'menu_name', 'menu.id as menu_id', 'food_list.image as image', 'food_list.initial_price', 'food_list.split_payment', 'food_list.bprice', 'food_list.out_of_stock')
    //     ->orderBy('rank')->orderBy('image', 'desc')
    //     ->get();
    // $rest_details = DB::table('restaurants')->where('id', $restaurant_id)->first();

    foreach ($food_list as $key => $each_food) {
        $each_food->image = get_image($each_food, $restaurant_id);
        $food_list[$key]->add_ons = []; // get_addons($each_food, $restaurant_id);
        $food_list[$key]->groups = []; // get_groups($each_food, $restaurant_id);
        $food_list[$key]->food_quantity = []; // get_food_quantity($each_food, $restaurant_id);
        $food_list[$key]->restaurant = $rest_id->id;
        $food_list[$key]->restaurant_name = $rest_id->restaurant_name;
        $food_list[$key]->restaurant_address = $rest_id->address;
        $food_list[$key]->restaurant_image = $rest_id->image;
        $food_list[$key]->restaurant_packaging_charge = $rest_id->packaging_charge;
        $food_list[$key]->id = $food_list[$key]->food_id;
        $food_list[$key]->isveg = $food_list[$key]->is_veg;
        $food_list[$key]->cart_status = $rest_id->cart;
        $each_food->slug = strtolower(str_replace(' ', '-', $each_food->name) . '-' . $food_list[$key]->id);
        $food_list[$key]->menu_available = menu_availability($each_food);
    }

    foreach ($food_list as $key => $each_menu) {
        // if ($food_list[$key]->price < $food_list[$key]->bprice) {
        //     $food_list[$key]->disc_value = number_format((100 - ($food_list[$key]->price / $food_list[$key]->bprice) * 100), 2);
        // } else {
        $food_list[$key]->disc_value = "0";
        // }
    }
    return $food_list;
}
function check_menu_time($food, $current_time, $today)
{
    $food_detail = Foodlist::where('id', (int)$food)->first();
    $menu_detail = Menu::where('id', $food_detail->menu_id)->first();
    $menutime = MenuTime::where('menu_id', $food_detail->menu_id)->get();
    $available = 0;
    if (sizeof($menutime)) {
        foreach ($menutime as $key => $time) {

            if ($available != 1) {
                $available_day = json_decode($time->available_day);
                if ($available_day != null) {
                    $yes = in_array($today, $available_day);
                    if ($yes == false) {
                        $available = 0;
                    } else {
                        $date1 = $current_time;
                        $date2 = $time->from_time;
                        $date3 = $time->to_time;
                        if ($date1 >= $date2 && $date1 < $date3) {
                            $available = 1;
                        } else {
                            $available = 0;
                        }
                    }
                } elseif ($time->all_days == 1) {

                    $date1 = $current_time;
                    $date2 = $time->from_time;

                    $date3 = $time->to_time;
                    if ($date1 >= $date2 && $date1 < $date3) {
                        $available = 1;
                    } else {
                        $available = 0;
                    }
                } else {
                    $available = 1;
                }
            }
        }
    } else {
        $available = 1;
    }
    $return_food_list = [
        'menu_id' => $food_detail->menu_id,
        'menu_available' => $available,
        'menu_name' => $menu_detail->menu_name,
        'item_id' => $food_detail->id,
        'item_name' => $food_detail->name,
    ];
    return $return_food_list;
}
