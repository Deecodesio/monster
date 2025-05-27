<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\api\BaseController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    public function index(Request $request)

    //for super admin dashboard //
    {
        $restaurant_id = $request->session()->get('userid');
        $role = $request->session()->get('role');

        $total_users = DB::table('users')->select('id')->count();

        $total_delivery_partners = DB::table('delivery_partners')->select('id')->count();

        $total_restaurants = DB::table('restaurants')->select('id')->count();

        $total_earnings = DB::table('requests')->where('status', 7)->sum('bill_amount');
        // $total_earnings = DB::table('requests')->sum('bill_amount');

        $today_earnings = DB::table('requests')->whereDate('created_at', Carbon::today())->sum('bill_amount');

        $total_admin_comission = DB::table('requests')->sum('admin_commision');

        $total_restaurant_comission = DB::table('requests')->sum('restaurant_commision');

        $total_delivery_boy_comission = DB::table('requests')->sum('delivery_boy_commision');

        //recent orders
        $query = $this->foodrequest
      ->orderby('id', 'desc')
      ->limit(9);
        $query = $query->when(($role != 1), function ($q) use ($restaurant_id) {
            return $q->where('restaurant_id', $restaurant_id);
        });

        $recent_deliveries = $query->get();

        //for pie chart
        $month = date('m');
        $prev_month = date('m', strtotime('-1 month'));
        $previous_week = strtotime('-1 week +1 day');
        $start_week = strtotime('last sunday midnight', $previous_week);
        $end_week = strtotime('next saturday', $start_week);
        $last_week_start = date('Y-m-d', $start_week).' 00:00:00';
        $last_week_end = date('Y-m-d', $end_week).' 23:59:59';

        $current_month = DB::table('requests')
      ->when(($role != 1), function ($q) use ($restaurant_id) {
          $q->where('restaurant_id', $restaurant_id);
      })
      ->whereMonth('created_at', '=', $month)
      ->select('restaurant_commision')->sum('restaurant_commision');

        $last_month = DB::table('requests')
      ->when(($role != 1), function ($q) use ($restaurant_id) {
          $q->where('restaurant_id', $restaurant_id);
      })
      ->whereMonth('requests.created_at', '=', $prev_month)
      ->select('restaurant_commision')->sum('restaurant_commision');

        $current_week = DB::table('requests')
      ->when(($role != 1), function ($q) use ($restaurant_id) {
          $q->where('restaurant_id', $restaurant_id);
      })
      ->whereBetween('requests.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
      ->select('restaurant_commision')->sum('restaurant_commision');

        $last_week = DB::table('requests')
      ->when(($role != 1), function ($q) use ($restaurant_id) {
          $q->where('restaurant_id', $restaurant_id);
      })
      ->whereBetween('requests.created_at', [$last_week_start, $last_week_end])
      ->select('restaurant_commision')->sum('restaurant_commision');

        $week_number = date('W', strtotime('now')); // ISO-8601 week number
        $year_number = date('o', strtotime('now')); // ISO-8601 year number

        //print_r($week_number);exit();

        if ($week_number <= 9) {
            $week_number = '0'.$week_number;
        }
        $this_year = date('Y');
        $monday = date('Y-m-d', strtotime("$this_year-W$week_number"));
        //print_r($monday);exit();
        $tuesday = date('Y-m-d', strtotime("$monday +1 days"));
        $wednesday = date('Y-m-d', strtotime("$monday +2 days"));
        $thursday = date('Y-m-d', strtotime("$monday +3 days"));
        $friday = date('Y-m-d', strtotime("$monday +4 days"));
        $saturday = date('Y-m-d', strtotime("$monday +5 days"));
        $sunday = date('Y-m-d', strtotime("$monday +6 days"));
        //cod start
        $monday_cod_earnings = DB::table('requests')
      ->whereDate('requests.created_at', '=', $monday)->where('is_cod', 1)
      ->sum('bill_amount');

        $tuesday_cod_earnings = DB::table('requests')->where('is_cod', 1)
      ->whereDate('requests.created_at', '=', $tuesday)
      ->sum('bill_amount');

        $wednesday_cod_earnings = DB::table('requests')->where('is_cod', 1)
      ->whereDate('requests.created_at', '=', $wednesday)
      ->sum('bill_amount');
        $thursday_cod_earnings = DB::table('requests')->where('is_cod', 1)
      ->whereDate('requests.created_at', '=', $thursday)
      ->sum('bill_amount');
        $friday_cod_earnings = DB::table('requests')->where('is_cod', 1)
      ->whereDate('requests.created_at', '=', $friday)
      ->sum('bill_amount');
        $saturday_cod_earnings = DB::table('requests')->where('is_cod', 1)
      ->whereDate('requests.created_at', '=', $saturday)
      ->sum('bill_amount');

        $sunday_cod_earnings = DB::table('requests')->where('is_cod', 1)
      ->whereDate('requests.created_at', '=', $sunday)
      ->sum('bill_amount');

        $days = [$monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday];

        $cod = [$monday_cod_earnings, $tuesday_cod_earnings, $wednesday_cod_earnings, $thursday_cod_earnings, $friday_cod_earnings, $saturday_cod_earnings, $sunday_cod_earnings];
        //cod end

        // online start
        $monday_online_earnings = DB::table('requests')
      ->whereDate('requests.created_at', '=', $monday)->where('is_cod', 0)
      ->sum('bill_amount');

        $tuesday_online_earnings = DB::table('requests')->where('is_cod', 0)
      ->whereDate('requests.created_at', '=', $tuesday)
      ->sum('bill_amount');

        $wednesday_online_earnings = DB::table('requests')->where('is_cod', 0)
      ->whereDate('requests.created_at', '=', $wednesday)
      ->sum('bill_amount');
        $thursday_online_earnings = DB::table('requests')->where('is_cod', 0)
      ->whereDate('requests.created_at', '=', $thursday)
      ->sum('bill_amount');
        $friday_online_earnings = DB::table('requests')->where('is_cod', 0)
      ->whereDate('requests.created_at', '=', $friday)
      ->sum('bill_amount');
        $saturday_online_earnings = DB::table('requests')->where('is_cod', 0)
      ->whereDate('requests.created_at', '=', $saturday)
      ->sum('bill_amount');

        $sunday_online_earnings = DB::table('requests')->where('is_cod', 0)
      ->whereDate('requests.created_at', '=', $sunday)
      ->sum('bill_amount');

        $online = [$monday_online_earnings, $tuesday_online_earnings, $wednesday_online_earnings, $thursday_online_earnings, $friday_online_earnings, $saturday_online_earnings, $sunday_online_earnings];

        // online ends

        $res_monday_admin_earnings = DB::table('requests')
      ->where('restaurant_id', $restaurant_id)
      ->whereDate('requests.created_at', '=', $monday)
      ->sum('admin_commision');
        $res_tuesday_admin_earnings = DB::table('requests')
      ->where('restaurant_id', $restaurant_id)
      ->whereDate('requests.created_at', '=', $tuesday)
      ->sum('admin_commision');
        $res_wednesday_admin_earnings = DB::table('requests')
      ->where('restaurant_id', $restaurant_id)
      ->whereDate('requests.created_at', '=', $wednesday)
      ->sum('admin_commision');
        $res_thursday_admin_earnings = DB::table('requests')
      ->where('restaurant_id', $restaurant_id)
      ->whereDate('requests.created_at', '=', $thursday)
      ->sum('admin_commision');
        $res_friday_admin_earnings = DB::table('requests')
      ->where('restaurant_id', $restaurant_id)
      ->whereDate('requests.created_at', '=', $friday)
      ->sum('admin_commision');
        $res_saturday_admin_earnings = DB::table('requests')
      ->where('restaurant_id', $restaurant_id)
      ->whereDate('requests.created_at', '=', $saturday)
      ->sum('admin_commision');
        $res_sunday_admin_earnings = DB::table('requests')
      ->where('restaurant_id', $restaurant_id)
      ->whereDate('requests.created_at', '=', $sunday)
      ->sum('admin_commision');
        $res_admin = $res_monday_admin_earnings.','.$res_tuesday_admin_earnings.','.$res_wednesday_admin_earnings.','.$res_thursday_admin_earnings.','.$res_friday_admin_earnings
      .','.$res_saturday_admin_earnings.','.$res_sunday_admin_earnings;

        // top restaurant top users
        $top_res = DB::table('requests')->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
      ->select('restaurant_id', DB::raw('count(*) as res_id'), 'restaurant_name', 'total_earnings')
      ->groupBy('restaurant_id')->where('requests.status', 7)->orderbyDesc('res_id')->limit(3)
      ->get();
        $top_user = DB::table('requests')->join('users', 'users.id', '=', 'requests.user_id')
      ->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw('SUM(bill_amount) as paidsum'))
      ->groupBy('user_id')->where('requests.status', 7)->orderbyDesc('users_count')->limit(3)
      ->get();

        $day1 = Carbon::now()->subDays(7);
        $day2 = Carbon::now()->subDays(6);
        $day3 = Carbon::now()->subDays(5);
        $day4 = Carbon::now()->subDays(4);
        $day5 = Carbon::now()->subDays(3);
        $day6 = Carbon::now()->subDays(2);
        $day7 = Carbon::now()->subDays(1);
        $last_7_days = [$day1->toDateString(), $day2->toDateString(), $day3->toDateString(), $day4->toDateString(), $day5->toDateString(), $day6->toDateString(), $day7->toDateString()];

        $sunday_deliverd = DB::table('requests')
                                          ->whereDate('created_at', '=', $day1)
                                          ->where('status', '=', 7)
                                          ->count();

        $monday_deliverd = DB::table('requests')
                        ->whereDate('created_at', '=', $day2)
                        ->where('status', '=', 7)
                        ->count();
        $tuesday_deliverd = DB::table('requests')
                        ->whereDate('created_at', '=', $day3)
                        ->where('status', '=', 7)
                        ->count();
        $wednesday_deliverd = DB::table('requests')
                        ->whereDate('created_at', '=', $day4)
                        ->where('status', '=', 7)
                        ->count();
        $thursday_deliverd = DB::table('requests')
                        ->whereDate('created_at', '=', $day5)
                        ->where('status', '=', 7)
                        ->count();
        $friday_deliverd = DB::table('requests')
                        ->whereDate('created_at', '=', $day6)
                        ->where('status', '=', 7)
                        ->count();

        $saturday_deliverd = DB::table('requests')
                        ->whereDate('created_at', '=', $day7)
                        ->where('status', '=', 7)
                        ->count();

        $cancel_status = [11, 12, 13, 14];

        $sunday_cancelled = DB::table('requests')
                                          ->whereDate('created_at', '=', $day1)
                                          ->whereIn('status', $cancel_status)
                                          ->count();

        $monday_cancelled = DB::table('requests')
                        ->whereDate('created_at', '=', $day2)
                        ->whereIn('status', $cancel_status)
                        ->count();
        $tuesday_cancelled = DB::table('requests')
                        ->whereDate('created_at', '=', $day3)
                        ->whereIn('status', $cancel_status)
                        ->count();
        $wednesday_cancelled = DB::table('requests')
                        ->whereDate('created_at', '=', $day4)
                        ->whereIn('status', $cancel_status)
                        ->count();
        $thursday_cancelled = DB::table('requests')
                        ->whereDate('created_at', '=', $day5)
                        ->whereIn('status', $cancel_status)
                        ->count();
        $friday_cancelled = DB::table('requests')
                        ->whereDate('created_at', '=', $day6)
                        ->whereIn('status', $cancel_status)
                        ->count();

        $saturday_cancelled = DB::table('requests')
                        ->whereDate('created_at', '=', $day7)
                        ->whereIn('status', $cancel_status)
                        ->count();
        $deliverd = [$sunday_deliverd, $monday_deliverd, $tuesday_deliverd, $wednesday_deliverd, $thursday_deliverd, $friday_deliverd, $saturday_deliverd];

        $cancelled = [$sunday_cancelled, $monday_cancelled, $tuesday_cancelled, $wednesday_cancelled, $thursday_cancelled, $friday_cancelled, $saturday_cancelled];

        return view('admin/index', [
            'top_users' => $top_user, 'top_rest' => $top_res, 'total_users' => $total_users,
            'total_delivery_partners' => $total_delivery_partners, 'total_restaurants' => $total_restaurants,
            'total_earnings' => $total_earnings, 'today_earnings' => $today_earnings, 'total_admin_comission' => $total_admin_comission,
            'total_restaurant_comission' => $total_restaurant_comission, 'total_delivery_boy_comission' => $total_delivery_boy_comission,
            'recent_deliveries' => $recent_deliveries, 'cod' => $cod, 'days' => $days, 'online' => $online, 'days' => $days, 'deliverd' => $deliverd, 'cancelled' => $cancelled, 'last_7_days' => $last_7_days,
        ]);
    }

    public function commission_settings(Request $request)
    {
        $admin_commission = DB::table('settings')->where('key_word', 'admin_commission')->first();
        $restaurant_commission = DB::table('settings')->where('key_word', 'restaurant_commission')->first();
        $delivery_boy_commission = DB::table('settings')->where('key_word', 'delivery_boy_commission')->first();

        $ids = [];
        $ids[0] = $admin_commission->id;
        $ids[1] = $restaurant_commission->id;
        $ids[2] = $delivery_boy_commission->id;

        // print_r($ids); exit;
        // echo $admin_commission; exit;

        return view('admin/commission_settings', ['admin_commission' => $admin_commission, 'restaurant_commission' => $restaurant_commission, 'delivery_boy_commission' => $delivery_boy_commission, 'commission_id' => $ids]);
    }

    public function update_commission_settings(Request $request)
    {
        $id = $request->id;

        DB::table('settings')->where('id', $id)->update(['value' => $request->commission_value]);
        if (session()->get('role') == 2) {
            return redirect('/store/commission_settings');
        } else {
            return redirect('/admin/commission_settings');
        }
    }

    public function dashboard_statistics(Request $request)
    {
      $total_user = DB::table('users')->select('id')->count();
      $total_users[] =[
        'subtitle'=>"Total Users",
        'title'=>round($total_user,2),
        'icon'=>"UserIcon",
        'color'=>"light-primary",
      ]; 
      $total_delivery_partner = DB::table('delivery_partners')->select('id')->count();
      $total_delivery_partners[] =[
        'subtitle'=>"Total Delivery Partners",
        'title'=>round($total_delivery_partner,2),
        'icon'=>"TruckIcon",
        'color'=>"light-success",
      ]; 
      $total_restaurant = DB::table('restaurants')->select('id')->count();
      $total_restaurants[] =[
        'subtitle'=>"Total Restaurants",
        'title'=>round($total_restaurant,2),
        'icon'=>"CoffeeIcon",
        'color'=>"light-warning",
      ]; 
      $total_earning = DB::table('requests')->where('status', 7)->sum('bill_amount');
      // $total_earnings = DB::table('requests')->sum('bill_amount');
      $total_earnings[] =[
        'subtitle'=>"Total earnings",
        'title'=>round($total_earning,2),
        'icon'=>"CreditCardIcon",
        'color'=>"light-info",
      ]; 
      $today_earning = DB::table('requests')->whereDate('created_at', Carbon::today())->sum('bill_amount');
      $today_earnings[] =[
        'subtitle'=>"Today Earnings",
        'title'=>round($today_earning,2),
        'icon'=>"TrendingUpIcon",
        'color'=>"light-danger",
      ]; 
      $total_admin_comissions = DB::table('requests')->sum('admin_commision');
      $total_admin_comission[] =[
        'subtitle'=>"Total Admin Comission",
        'title'=>round($total_admin_comissions,2),
        'icon'=>"DollarSignIcon",
        'color'=>"light-info",
      ]; 
      $total_restaurant_comissions = DB::table('requests')->sum('restaurant_commision');
      $total_restaurant_comission[] =[
        'subtitle'=>"Total Restaurant Comission",
        'title'=>round($total_restaurant_comissions,2),
        'icon'=>"GiftIcon",
        'color'=>"light-success",
      ]; 
      $total_delivery_boy_comissions = DB::table('requests')->sum('delivery_boy_commision');
      $total_delivery_boy_comission[] =[
        'subtitle'=>"Total Delivery Boy Comission",
        'title'=>round($total_delivery_boy_comissions,2),
        'icon'=>"ShoppingBagIcon",
        'color'=>"light-danger",
      ]; 
      $val = [$total_users,$total_delivery_partners,$total_restaurants,$total_earnings,$today_earnings,$total_admin_comission,$total_restaurant_comission,$total_delivery_boy_comission];
      // dd($val);
      $ret =[];
      for($i=0;$i<count($val);$i++){

      $ret[] = [
        'color'=>$val[$i][0]['color'],
        'customClass'=>"mb-2",
        'icon'=>$val[$i][0]['icon'],
        'subtitle'=>$val[$i][0]['subtitle'],
        'title'=> round($val[$i][0]['title'],2),
      ];
    }

    $day1 = Carbon::now()->subDays(7);
    $day2 = Carbon::now()->subDays(6);
    $day3 = Carbon::now()->subDays(5);
    $day4 = Carbon::now()->subDays(4);
    $day5 = Carbon::now()->subDays(3);
    $day6 = Carbon::now()->subDays(2);
    $day7 = Carbon::now()->subDays(1);
    $last_7_days = [$day1->toDateString(), $day2->toDateString(), $day3->toDateString(), $day4->toDateString(), $day5->toDateString(), $day6->toDateString(), $day7->toDateString()];

    $sunday_deliverd = DB::table('requests')
                                      ->whereDate('created_at', '=', $day1)
                                      ->where('status', '=', 7)
                                      ->count();

    $monday_deliverd = DB::table('requests')
                    ->whereDate('created_at', '=', $day2)
                    ->where('status', '=', 7)
                    ->count();
    $tuesday_deliverd = DB::table('requests')
                    ->whereDate('created_at', '=', $day3)
                    ->where('status', '=', 7)
                    ->count();
    $wednesday_deliverd = DB::table('requests')
                    ->whereDate('created_at', '=', $day4)
                    ->where('status', '=', 7)
                    ->count();
    $thursday_deliverd = DB::table('requests')
                    ->whereDate('created_at', '=', $day5)
                    ->where('status', '=', 7)
                    ->count();
    $friday_deliverd = DB::table('requests')
                    ->whereDate('created_at', '=', $day6)
                    ->where('status', '=', 7)
                    ->count();

    $saturday_deliverd = DB::table('requests')
                    ->whereDate('created_at', '=', $day7)
                    ->where('status', '=', 7)
                    ->count();

    $data = [$sunday_deliverd, $monday_deliverd, $tuesday_deliverd, $wednesday_deliverd, $thursday_deliverd, $friday_deliverd, $saturday_deliverd];
    $name = "2022";
      $series[]=[
        'data'=>$data,
        'name'=>$name,
      ];
      $s[] = [
        'series'=>$series,
      ];

      $sunday_deliverd1 = DB::table('requests')
      ->whereDate('created_at', '=', $day1)
      
      ->where('requests.status', 7)
      ->get();

      $sun_profit =0;
      if($sunday_deliverd1){
      foreach ($sunday_deliverd1 as $key) {
        $sun_profit = round($sun_profit + $key->admin_commision,2);
      }
      }

      $monday_deliverd1 = DB::table('requests')
      ->whereDate('created_at', '=', $day2)
      ->where('requests.status', 7)
      ->get();


      $mon_profit =0;
      if($monday_deliverd1){
      foreach ($monday_deliverd1 as $key) {
        $mon_profit = round($mon_profit + $key->admin_commision,2);
      }
      }

      $tuesday_deliverd1 = DB::table('requests')
      ->whereDate('created_at', '=', $day3)
      ->where('requests.status', 7)
      ->get();

      $tue_profit =0;
      if($tuesday_deliverd1){
      foreach ($tuesday_deliverd1 as $key) {
        $tue_profit = round($tue_profit + $key->admin_commision,2);
      }
      }

      $wednesday_deliverd1 = DB::table('requests')
      ->whereDate('created_at', '=', $day4)
      ->where('requests.status', 7)
      ->get();

      
      $wed_profit =0;
      if($wednesday_deliverd1){
      foreach ($wednesday_deliverd1 as $key) {
        $wed_profit = round($wed_profit + $key->admin_commision,2);
      }
      }

      $thursday_deliverd1 = DB::table('requests')
      ->whereDate('created_at', '=', $day5)
      ->where('requests.status', 7)
      ->get();

      $thu_profit =0;
      if($thursday_deliverd1){
      foreach ($thursday_deliverd1 as $key) {
        $thu_profit = round($thu_profit + $key->admin_commision,2);
      }
      }

      $friday_deliverd1 = DB::table('requests')
      ->whereDate('created_at', '=', $day6)
      ->where('requests.status', 7)
      ->get();

      $fri_profit =0;
      if($friday_deliverd1){
      foreach ($friday_deliverd1 as $key) {
        $fri_profit = round($fri_profit + $key->admin_commision,2);
      }
      }

      $saturday_deliverd1 = DB::table('requests')
      ->whereDate('created_at', '=', $day7)
      ->where('requests.status', 7)
      ->get();
      $sat_profit =0;
      if($saturday_deliverd1){
      foreach ($saturday_deliverd1 as $key) {
        $sat_profit = round($sat_profit + $key->admin_commision,2);
      }
      }
     

      $data2 = [$sun_profit, $mon_profit, $tue_profit, $wed_profit, $thu_profit, $fri_profit, $sat_profit];
      // $data2=["0","5","9","0","7","12"];
      $name2 = "2022";
        $series2[]=[
          'data'=>$data2,
          'name'=>$name2,
        ];
        $s2[] = [
          'series'=>$series2,
        ];

       
       
        $req = DB::table('requests')->where('status',7)->select(
          DB::raw("(COUNT(*)) as count"),
          DB::raw("MONTHNAME(created_at) as month_name"),
          DB::raw("YEAR(created_at) as year")

      )
      ->groupBy('month_name')
      ->orderby('month_name', 'asc')
      ->get()
      ->toArray();

          $can = [10,11,12,13,14];
          $req2 = DB::table('requests')->orderby('created_at', 'asc')->whereIN('status',$can)->select(
            DB::raw("(COUNT(*)) as count"),
            DB::raw("MONTHNAME(created_at) as month_name")
        )
        ->groupBy('month_name')
        ->get()
        ->toArray();

      

          foreach($req as $rq2){
            $data3[]=$rq2->count;
            $data_month[] = $rq2->month_name;
          }

          foreach($req2 as $rq){
           
            $data4[]=-($rq->count);
          }
   

     $name3 = "2022";
     $name4 = "2022";

     $series3[]=[
       'data'=>$data3,
       'name'=>$name3,
       'months'=>$data_month,
     ];
     $series3[]=[
       'data'=>$data4,
       'name'=>$name4,
     ];
     $s3[] = [
       'series'=>$series3,
     ];

     $s4[]=[
       'revenueReport'=>$s3,
     ];

     $current_month = DB::table('requests')
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->sum('admin_commision');

    $last_month = DB::table('requests')
                 -> whereMonth(
                    'created_at', '=', Carbon::now()->subMonth()->month
                )->sum('admin_commision');

          

     $per = (($current_month - $last_month)/$last_month)*100;

     if($per > 0){
       $text = "more earnings than last month";
     }else{
      $text = "less earnings than last month";
     }

     $this_tot =  DB::table('requests')
                  ->whereMonth('created_at', Carbon::now()->month)
                  ->count();
     $this_del = DB::table('requests')
                ->whereMonth('created_at', Carbon::now()->month)
                ->where('status',7)
                ->count();
    $this_del_per = ($this_del / $this_tot) * 100;

    $this_canc = DB::table('requests')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereIn('status',[9,10,11,12,13,14])
                ->count();
    $this_failed = DB::table('requests')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereIn('status',[-1,-2])
                ->count();

    $this_canc_per = ($this_canc / $this_tot) * 100;
    $this_fail_per = ($this_failed / $this_tot) * 100;

    
     $data3=[round($this_del_per,2),round($this_canc_per,2),round($this_fail_per,2)];

     $s5[]=[
       'series'=>$data3,
       'earnings'=>round($current_month,2),
       'per'=>abs(round($per,2)),
       'text'=> $text
     ];

     $recent = DB::table('requests')->orderby('id', 'desc')->get();
    
     $td = [];
     $i = 0;
     foreach($recent as $re){
       if($i < 5 ){
        if($re->status == 7){
          $avatarVariant = "light-primary";
          $avatar="CheckCircleIcon";
          $deduction = false;

        }elseif($re->status >=9){
          $avatarVariant = "light-danger";
          $avatar="XCircleIcon";
          $deduction = true;


        }elseif($re->status ==  -1 || $re->status == -2){
          $avatarVariant = "light-danger";
          $avatar="AlertTriangleIcon";
          $deduction = true;
        }else{
          $avatarVariant = "light-info";
          $avatar="ClockIcon";
          $deduction = false;

        }
        if($re->status =="0"){
            $status = "New Order";
        }
        if($re->status =="1"){
            $status = "Order Accepted";
        }
        if($re->status =="2"){
            $status = "Driver Assigned";
        }
        if($re->status =="3"){
            $status = "Delivered to Driver";
        }
        if($re->status =="4"){
            $status = "Towards Customer";
        }
        if($re->status =="5"){
            $status = "Reached Customer";
        }
        if($re->status =="6"){
            $status = "Delivered To Customer";
        }
        if($re->status =="7"){
            $status = "Completed";
        }
        if($re->status >= "9"){
            $status = "Cancelled";
        }
        
        if($re->status =="-1"){
            $status = "Failed";
        }
        if($re->status =="-2"){
          $status = "Failed";
      }
        
          $td[]=[
            'avatar'=>$avatar,
            'avatarVariant'=>$avatarVariant,
            'deduction'=>$deduction,
            'mode'=>$re->order_id,
            'payment'=>$re->bill_amount,
            'types'=>$status,
            'order_id'=>$re->id,
          ];
          $i++;
        }
     }

      $response_Array = json_encode(['statisticsItems'=>$ret,'statisticsOrder'=>$s,'statisticsProfit'=>$s2,'revenue'=>$s4,'earningsChart'=>$s5,'transactionData'=>$td]);
     

      return $response_Array;

    }


    public function order_dashboard_statistics(Request $request){

     $total_order = DB::table('requests')->whereDate('created_at', Carbon::today())->count();
     $total_orders[] =[
      'subtitle'=>"Today Orders",
      'title'=>round($total_order,2),
      'icon'=>"CalendarIcon",
      'color'=>"light-warning",
    ]; 

    $total_com = DB::table('requests')->whereDate('created_at', Carbon::today())->where('status',7)->count();
     $total_comp[] =[
      'subtitle'=>"Today Completed Orders",
      'title'=>round($total_com,2),
      'icon'=>"CheckCircleIcon",
      'color'=>"light-success",
    ]; 

    $total_can = DB::table('requests')->whereDate('created_at', Carbon::today())->whereIn('status',[10,11,12,13,14])->count();
     $total_canc[] =[
      'subtitle'=>"Today Cancelled Orders",
      'title'=>round($total_can,2),
      'icon'=>"XCircleIcon",
      'color'=>"light-danger",
    ]; 

    $total_pro = DB::table('requests')->whereDate('created_at', Carbon::today())->whereIn('status',[1,2,3,4,5,6,7,8,9])->count();
    $total_pros[] =[
     'subtitle'=>"Today Processing Orders",
     'title'=>round($total_pro,2),
     'icon'=>"ClockIcon",
     'color'=>"light-info",
   ]; 
   
      $val = [$total_orders,$total_comp,$total_canc, $total_pros];
      // dd($val);
      $ret =[];
      for($i=0;$i<count($val);$i++){

      $ret[] = [
        'color'=>$val[$i][0]['color'],
        'customClass'=>"mb-2",
        'icon'=>$val[$i][0]['icon'],
        'subtitle'=>$val[$i][0]['subtitle'],
        'title'=> round($val[$i][0]['title'],2),
      ];
    }


    $newTicket = DB::table('requests')->whereDate('created_at', Carbon::today())->where('status',0)->count();
    $openTicket =  DB::table('requests')->whereDate('created_at', Carbon::today())->whereIn('status',[1,2,3,4,5,6,7,8,9])->count();
    $responseTime = DB::table('requests')->whereDate('created_at', Carbon::today())->where('status',7)->count();
    $tot = DB::table('requests')->whereDate('created_at', Carbon::today())->count();
    $s1_series[]=[$responseTime];


    $s1[]=[
       'series'=>$s1_series,
     ];

     $recent = DB::table('requests')->orderby('id', 'desc')->get();
    
     $td = [];
     $i = 0;
     foreach($recent as $re){
       if($i < 5 ){
        if($re->status == 7){
          $avatarVariant = "light-primary";
          $avatar="CheckCircleIcon";
          $deduction = false;

        }elseif($re->status >=9){
          $avatarVariant = "light-danger";
          $avatar="XCircleIcon";
          $deduction = true;
        }elseif($re->status ==  -1 || $re->status == -2){
          $avatarVariant = "light-danger";
          $avatar="AlertTriangleIcon";
          $deduction = true;
        }
        else{
          $avatarVariant = "light-info";
          $avatar="ClockIcon";
          $deduction = false;

        }
        if($re->status =="0"){
            $status = "New Order";
        }
        if($re->status =="1"){
            $status = "Order Accepted";
        }
        if($re->status =="2"){
            $status = "Driver Assigned";
        }
        if($re->status =="3"){
            $status = "Delivered to Driver";
        }
        if($re->status =="4"){
            $status = "Towards Customer";
        }
        if($re->status =="5"){
            $status = "Reached Customer";
        }
        if($re->status =="6"){
            $status = "Delivered To Customer";
        }
        if($re->status =="7"){
            $status = "Completed";
        }
        if($re->status >= "9"){
            $status = "Cancelled";
        }
        
        if($re->status =="-1"){
            $status = "Failed";
        }
        if($re->status =="-2"){
          $status = "Failed";
      }
        
          $td[]=[
            'avatar'=>$avatar,
            'avatarVariant'=>$avatarVariant,
            'deduction'=>$deduction,
            'mode'=>$re->order_id,
            'payment'=>$re->bill_amount,
            'types'=>$status,
          ];
          $i++;
        }
     }

    
    $st[] =[
      'newTicket'=>$newTicket,
      'openTicket'=>$openTicket,
      'responseTime'=>$total_can,
      'title'=>"Today Orders",
      'supportTrackerRadialBar'=>$s1,
      'totalTicket'=>$tot
    ] ;
    $response_Array = json_encode(['statisticsItems'=>$ret,'supportTracker'=>$st,'transactionData'=>$td]);
     

    return $response_Array;
    }

    public function new_count(Request $request){
      $c = DB::table('requests')->where('status',0)->count();

      return json_encode($c);
    }
}
