<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\api\BaseController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use File;

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
    $last_week_start = date('Y-m-d', $start_week) . ' 00:00:00';
    $last_week_end = date('Y-m-d', $end_week) . ' 23:59:59';

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
      $week_number = '0' . $week_number;
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
    $res_admin = $res_monday_admin_earnings . ',' . $res_tuesday_admin_earnings . ',' . $res_wednesday_admin_earnings . ',' . $res_thursday_admin_earnings . ',' . $res_friday_admin_earnings
      . ',' . $res_saturday_admin_earnings . ',' . $res_sunday_admin_earnings;

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
    $ad = DB::table('admin')->where('email', $request->email)->first();
    $ad2 = DB::table('admin_role')->where('email', $request->email)->first();


    $currency_symbol = DB::table('country')->where('is_default', 1)->first();
    $currency = $currency_symbol->currency_symbol;
    if ($ad) {
      $total_user = DB::table('users')->select('id')->count();
    } else {
      $total_user = 0;
    }
    $total_users[] = [
      'subtitle' => "Total Users",
      'title' => round($total_user, 2),
      'icon' => "UserIcon",
      'color' => "light-primary",
    ];
    if ($ad) {
      $total_delivery_partner = DB::table('delivery_partners')->select('id')->count();
    } else {
      $total_delivery_partner = 0;
    }
    $total_delivery_partners[] = [
      'subtitle' => "Total Delivery Partners",
      'title' => round($total_delivery_partner, 2),
      'icon' => "TruckIcon",
      'color' => "light-success",
    ];
    if ($ad) {
      $total_restaurant = DB::table('restaurants')->select('id')->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $total_restaurant = DB::table('restaurants')
          ->whereIn('id', $datas)
          ->select('id')
          ->count();
      } else {
        $total_restaurant = DB::table('restaurants')
          ->select('id')
          ->count();
      }
    }
    $total_restaurants[] = [
      'subtitle' => "Total Store",
      'title' => round($total_restaurant, 2),
      'icon' => "CoffeeIcon",
      'color' => "light-warning",
    ];
    if ($ad) {
      $total_earning = DB::table('requests')->where('status', 7)->sum('bill_amount');
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $total_earning = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->where('status', 7)
          ->sum('bill_amount');
      } else {
        $total_earning = DB::table('requests')
          ->where('status', 7)
          ->sum('bill_amount');
      }
    }
    $total_earnings[] = [
      'subtitle' => "Total earnings",
      'title' => round($total_earning, 2),
      'icon' => "CreditCardIcon",
      'color' => "light-info",
    ];
    if ($ad) {
      $today_earning = DB::table('requests')->where('status', 7)->whereDate('created_at', Carbon::today())->sum('bill_amount');
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $today_earning = DB::table('requests')->whereIn('requests.restaurant_id', $datas)->where('status', 7)->whereDate('created_at', Carbon::today())->sum('bill_amount');
      } else {
        $today_earning = DB::table('requests')->where('status', 7)->whereDate('created_at', Carbon::today())->sum('bill_amount');
      }
    }
    $today_earnings[] = [
      'subtitle' => "Today Earnings",
      'title' => round($today_earning, 2),
      'icon' => "TrendingUpIcon",
      'color' => "light-danger",
    ];
    if ($ad) {
      $total_admin_comissions = DB::table('requests')->where('status', 7)->sum('admin_commision');
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $total_admin_comissions = DB::table('requests')->whereIn('requests.restaurant_id', $datas)->where('status', 7)->sum('admin_commision');
      } else {
        $total_admin_comissions = DB::table('requests')->where('status', 7)->sum('admin_commision');
      }
    }
    $total_admin_comission[] = [
      'subtitle' => "Total Admin Comission",
      'title' => round($total_admin_comissions, 2),
      'icon' => "DollarSignIcon",
      'color' => "light-info",
    ];
    if ($ad) {
      $total_restaurant_comissions = DB::table('requests')->where('status', 7)->sum('restaurant_commision');
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $total_restaurant_comissions = DB::table('requests')->whereIn('requests.restaurant_id', $datas)->where('status', 7)->sum('restaurant_commision');
      } else {
        $total_restaurant_comissions = DB::table('requests')->where('status', 7)->sum('restaurant_commision');
      }
    }
    $total_restaurant_comission[] = [
      'subtitle' => "Total Store Comission",
      'title' => round($total_restaurant_comissions, 2),
      'icon' => "GiftIcon",
      'color' => "light-success",
    ];
    if ($ad) {
      $total_delivery_boy_comissions = DB::table('requests')->where('status', 7)->sum('delivery_boy_commision');
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $total_delivery_boy_comissions = DB::table('requests')->whereIn('requests.restaurant_id', $datas)->where('status', 7)->sum('delivery_boy_commision');
      } else {
        $total_delivery_boy_comissions = DB::table('requests')->where('status', 7)->sum('delivery_boy_commision');
      }
    }
    $total_delivery_boy_comission[] = [
      'subtitle' => "Total Delivery Boy Comission",
      'title' => round($total_delivery_boy_comissions, 2),
      'icon' => "ShoppingBagIcon",
      'color' => "light-danger",
    ];
    $val = [$total_users, $total_delivery_partners, $total_restaurants, $total_earnings, $today_earnings, $total_admin_comission, $total_restaurant_comission, $total_delivery_boy_comission];
    // dd($val);
    $ret = [];
    for ($i = 0; $i < count($val); $i++) {

      $ret[] = [
        'color' => $val[$i][0]['color'],
        'customClass' => "mb-2",
        'icon' => $val[$i][0]['icon'],
        'subtitle' => $val[$i][0]['subtitle'],
        'title' => round($val[$i][0]['title'], 2),
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

    if ($ad) {
      $sunday_deliverd = DB::table('requests')
        ->whereDate('created_at', '=', $day1)
        ->where('status', '=', 7)
        ->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $sunday_deliverd = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day1)
          ->where('status', '=', 7)
          ->count();
      } else {
        $sunday_deliverd = DB::table('requests')
          ->whereDate('created_at', '=', $day1)
          ->where('status', '=', 7)
          ->count();
      }
    }
    if ($ad) {
      $monday_deliverd = DB::table('requests')
        ->whereDate('created_at', '=', $day2)
        ->where('status', '=', 7)
        ->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $monday_deliverd = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day2)
          ->where('status', '=', 7)
          ->count();
      } else {
        $monday_deliverd = DB::table('requests')
          ->whereDate('created_at', '=', $day2)
          ->where('status', '=', 7)
          ->count();
      }
    }
    if ($ad) {
      $tuesday_deliverd = DB::table('requests')
        ->whereDate('created_at', '=', $day3)
        ->where('status', '=', 7)
        ->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $tuesday_deliverd = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day3)
          ->where('status', '=', 7)
          ->count();
      } else {
        $tuesday_deliverd = DB::table('requests')
          ->whereDate('created_at', '=', $day3)
          ->where('status', '=', 7)
          ->count();
      }
    }
    if ($ad) {
      $wednesday_deliverd = DB::table('requests')
        ->whereDate('created_at', '=', $day4)
        ->where('status', '=', 7)
        ->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $wednesday_deliverd = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day4)
          ->where('status', '=', 7)
          ->count();
      } else {
        $wednesday_deliverd = DB::table('requests')
          ->whereDate('created_at', '=', $day4)
          ->where('status', '=', 7)
          ->count();
      }
    }
    if ($ad) {
      $thursday_deliverd = DB::table('requests')
        ->whereDate('created_at', '=', $day5)
        ->where('status', '=', 7)
        ->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $thursday_deliverd = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day5)
          ->where('status', '=', 7)
          ->count();
      } else {
        $thursday_deliverd = DB::table('requests')
          ->whereDate('created_at', '=', $day5)
          ->where('status', '=', 7)
          ->count();
      }
    }
    if ($ad) {
      $friday_deliverd = DB::table('requests')
        ->whereDate('created_at', '=', $day6)
        ->where('status', '=', 7)
        ->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $friday_deliverd = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day6)
          ->where('status', '=', 7)
          ->count();
      } else {
        $friday_deliverd = DB::table('requests')
          ->whereDate('created_at', '=', $day6)
          ->where('status', '=', 7)
          ->count();
      }
    }
    if ($ad) {
      $saturday_deliverd = DB::table('requests')
        ->whereDate('created_at', '=', $day7)
        ->where('status', '=', 7)
        ->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $saturday_deliverd = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day7)
          ->where('status', '=', 7)
          ->count();
      } else {
        $saturday_deliverd = DB::table('requests')
          ->whereDate('created_at', '=', $day7)
          ->where('status', '=', 7)
          ->count();
      }
    }

    $data = [$sunday_deliverd, $monday_deliverd, $tuesday_deliverd, $wednesday_deliverd, $thursday_deliverd, $friday_deliverd, $saturday_deliverd];
    $name = "2022";
    $series[] = [
      'data' => $data,
      'name' => $name,
    ];
    $s[] = [
      'series' => $series,
    ];

    if ($ad) {
      $sunday_deliverd1 = DB::table('requests')
        ->whereDate('created_at', '=', $day1)

        ->where('requests.status', 7)
        ->get();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $sunday_deliverd1 = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day1)
          ->where('requests.status', 7)
          ->get();
      } else {
        $sunday_deliverd1 = DB::table('requests')
          ->whereDate('created_at', '=', $day1)
          ->where('requests.status', 7)
          ->get();
      }
    }

    $sun_profit = 0;
    if ($sunday_deliverd1) {
      foreach ($sunday_deliverd1 as $key) {
        $sun_profit = round($sun_profit + $key->admin_commision, 2);
      }
    }

    if ($ad) {
      $monday_deliverd1 = DB::table('requests')
        ->whereDate('created_at', '=', $day2)
        ->where('requests.status', 7)
        ->get();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $monday_deliverd1 = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day2)
          ->where('requests.status', 7)
          ->get();
      } else {
        $monday_deliverd1 = DB::table('requests')
          ->whereDate('created_at', '=', $day2)
          ->where('requests.status', 7)
          ->get();
      }
    }

    $mon_profit = 0;
    if ($monday_deliverd1) {
      foreach ($monday_deliverd1 as $key) {
        $mon_profit = round($mon_profit + $key->admin_commision, 2);
      }
    }
    if ($ad) {
      $tuesday_deliverd1 = DB::table('requests')
        ->whereDate('created_at', '=', $day3)
        ->where('requests.status', 7)
        ->get();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $tuesday_deliverd1 = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day3)
          ->where('requests.status', 7)
          ->get();
      } else {
        $tuesday_deliverd1 = DB::table('requests')
          ->whereDate('created_at', '=', $day3)
          ->where('requests.status', 7)
          ->get();
      }
    }

    $tue_profit = 0;
    if ($tuesday_deliverd1) {
      foreach ($tuesday_deliverd1 as $key) {
        $tue_profit = round($tue_profit + $key->admin_commision, 2);
      }
    }
    if ($ad) {
      $wednesday_deliverd1 = DB::table('requests')
        ->whereDate('created_at', '=', $day4)
        ->where('requests.status', 7)
        ->get();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $wednesday_deliverd1 = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day4)
          ->where('requests.status', 7)
          ->get();
      } else {
        $wednesday_deliverd1 = DB::table('requests')
          ->whereDate('created_at', '=', $day4)
          ->where('requests.status', 7)
          ->get();
      }
    }

    $wed_profit = 0;
    if ($wednesday_deliverd1) {
      foreach ($wednesday_deliverd1 as $key) {
        $wed_profit = round($wed_profit + $key->admin_commision, 2);
      }
    }
    if ($ad) {
      $thursday_deliverd1 = DB::table('requests')
        ->whereDate('created_at', '=', $day5)
        ->where('requests.status', 7)
        ->get();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $thursday_deliverd1 = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day5)
          ->where('requests.status', 7)
          ->get();
      } else {
        $thursday_deliverd1 = DB::table('requests')
          ->whereDate('created_at', '=', $day5)
          ->where('requests.status', 7)
          ->get();
      }
    }

    $thu_profit = 0;
    if ($thursday_deliverd1) {
      foreach ($thursday_deliverd1 as $key) {
        $thu_profit = round($thu_profit + $key->admin_commision, 2);
      }
    }
    if ($ad) {
      $friday_deliverd1 = DB::table('requests')
        ->whereDate('created_at', '=', $day6)
        ->where('requests.status', 7)
        ->get();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $friday_deliverd1 = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day6)
          ->where('requests.status', 7)
          ->get();
      } else {
        $friday_deliverd1 = DB::table('requests')
          ->whereDate('created_at', '=', $day6)
          ->where('requests.status', 7)
          ->get();
      }
    }

    $fri_profit = 0;
    if ($friday_deliverd1) {
      foreach ($friday_deliverd1 as $key) {
        $fri_profit = round($fri_profit + $key->admin_commision, 2);
      }
    }
    if ($ad) {
      $saturday_deliverd1 = DB::table('requests')
        ->whereDate('created_at', '=', $day7)
        ->where('requests.status', 7)
        ->get();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $saturday_deliverd1 = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereDate('created_at', '=', $day7)
          ->where('requests.status', 7)
          ->get();
      } else {
        $saturday_deliverd1 = DB::table('requests')
          ->whereDate('created_at', '=', $day7)
          ->where('requests.status', 7)
          ->get();
      }
    }
    $sat_profit = 0;
    if ($saturday_deliverd1) {
      foreach ($saturday_deliverd1 as $key) {
        $sat_profit = round($sat_profit + $key->admin_commision, 2);
      }
    }


    $data2 = [$sun_profit, $mon_profit, $tue_profit, $wed_profit, $thu_profit, $fri_profit, $sat_profit];
    // $data2=["0","5","9","0","7","12"];
    $name2 = "2022";
    $series2[] = [
      'data' => $data2,
      'name' => $name2,
    ];
    $s2[] = [
      'series' => $series2,
    ];


    if ($ad) {
      $req = DB::table('requests')->orderby('created_at', 'asc')->where('status', 7)->select(
        DB::raw("(COUNT(*)) as count"),
        DB::raw("MONTHNAME(created_at) as month_name")
      )
        ->groupBy('month_name')
        ->get()
        ->toArray();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $req = DB::table('requests')->whereIn('requests.restaurant_id', $datas)->orderby('created_at', 'asc')->where('status', 7)->select(
          DB::raw("(COUNT(*)) as count"),
          DB::raw("MONTHNAME(created_at) as month_name")
        )
          ->groupBy('month_name')
          ->get()
          ->toArray();
      } else {
        $req = DB::table('requests')->orderby('created_at', 'asc')->where('status', 7)->select(
          DB::raw("(COUNT(*)) as count"),
          DB::raw("MONTHNAME(created_at) as month_name")
        )
          ->groupBy('month_name')
          ->get()
          ->toArray();
      }
    }


    $can = [10, 11, 12, 13, 14];
    if ($ad) {
      $req2 = DB::table('requests')->orderby('created_at', 'asc')->whereIN('status', $can)->select(
        DB::raw("(COUNT(*)) as count"),
        DB::raw("MONTHNAME(created_at) as month_name")
      )
        ->groupBy('month_name')
        ->get()
        ->toArray();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $req2 = DB::table('requests')->whereIn('requests.restaurant_id', $datas)->orderby('created_at', 'asc')->whereIN('status', $can)->select(
          DB::raw("(COUNT(*)) as count"),
          DB::raw("MONTHNAME(created_at) as month_name")
        )
          ->groupBy('month_name')
          ->get()
          ->toArray();
      } else {
        $req2 = DB::table('requests')->orderby('created_at', 'asc')->whereIN('status', $can)->select(
          DB::raw("(COUNT(*)) as count"),
          DB::raw("MONTHNAME(created_at) as month_name")
        )
          ->groupBy('month_name')
          ->get()
          ->toArray();
      }
    }

    $data3 = [];
    $data4 = [];
    $data_month = [];
    foreach ($req as $rq2) {
      $data3[] = $rq2->count;
      $data_month[] = $rq2->month_name;
    }

    foreach ($req2 as $rq) {

      $data4[] = - ($rq->count);
    }


    if (count($req) <= 0) {
      $data3 = [
        0
      ];
      $data_month[] = [
        'Jan'
      ];
    }

    if (count($req2) <= 0) {
      $data4 = [
        0
      ];
    }

    $name3 = "2022";
    $name4 = "2022";

    $series3[] = [
      'data' => $data3,
      'name' => $name3,
      'months' => $data_month,
    ];
    $series3[] = [
      'data' => $data4,
      'name' => $name4,
    ];
    $s3[] = [
      'series' => $series3,
    ];

    $s4[] = [
      'revenueReport' => $s3,
    ];

    if ($ad) {
      $current_month = DB::table('requests')
        ->where('status', 7)
        ->whereMonth('created_at', Carbon::now()->month)
        ->sum('admin_commision');
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $current_month = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->where('status', 7)
          ->whereMonth('created_at', Carbon::now()->month)
          ->sum('admin_commision');
      } else {
        $current_month = DB::table('requests')
          ->where('status', 7)
          ->whereMonth('created_at', Carbon::now()->month)
          ->sum('admin_commision');
      }
    }
    if ($ad) {
      $last_month = DB::table('requests')
        ->where('status', 7)
        ->whereMonth(
          'created_at',
          Carbon::now()->subMonth()->month
        )->sum('admin_commision');
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $last_month = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->where('status', 7)
          ->whereMonth(
            'created_at',
            Carbon::now()->subMonth()->month
          )->sum('admin_commision');
      } else {
        $last_month = DB::table('requests')
          ->where('status', 7)
          ->whereMonth(
            'created_at',
            Carbon::now()->subMonth()->month
          )->sum('admin_commision');
      }
    }


    if ($last_month == 0) {
      $per = 0;
    } else {
      $per = (($current_month - $last_month) / $last_month) * 100;
    }
    if ($per > 0) {
      $text = "more earnings than last month";
    } else {
      $text = "less earnings than last month";
    }

    if ($ad) {
      $this_tot =  DB::table('requests')
        ->whereMonth('created_at', Carbon::now()->month)
        ->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $this_tot =  DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereMonth('created_at', Carbon::now()->month)
          ->count();
      } else {
        $this_tot =  DB::table('requests')
          ->whereMonth('created_at', Carbon::now()->month)
          ->count();
      }
    }
    if ($ad) {
      $this_del = DB::table('requests')
        ->whereMonth('created_at', Carbon::now()->month)
        ->where('status', 7)
        ->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $this_del = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereMonth('created_at', Carbon::now()->month)
          ->where('status', 7)
          ->count();
      } else {
        $this_del = DB::table('requests')
          ->whereMonth('created_at', Carbon::now()->month)
          ->where('status', 7)
          ->count();
      }
    }

    if ($this_tot > 0) {
      $this_del_per = ($this_del / $this_tot) * 100;
    } else {
      $this_del_per = 0;
    }
    if ($ad) {
      $this_canc = DB::table('requests')
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereIn('status', [9, 10, 11, 12, 13, 14])
        ->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $this_canc = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereMonth('created_at', Carbon::now()->month)
          ->whereIn('status', [9, 10, 11, 12, 13, 14])
          ->count();
      } else {
        $this_canc = DB::table('requests')
          ->whereMonth('created_at', Carbon::now()->month)
          ->whereIn('status', [9, 10, 11, 12, 13, 14])
          ->count();
      }
    }
    if ($ad) {
      $this_failed = DB::table('requests')
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereIn('status', [-1, -2])
        ->count();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $this_failed = DB::table('requests')
          ->whereIn('requests.restaurant_id', $datas)
          ->whereMonth('created_at', Carbon::now()->month)
          ->whereIn('status', [-1, -2])
          ->count();
      } else {
        $this_failed = DB::table('requests')
          ->whereMonth('created_at', Carbon::now()->month)
          ->whereIn('status', [-1, -2])
          ->count();
      }
    }

    if ($this_tot > 0) {
      $this_canc_per = ($this_canc / $this_tot) * 100;
      $this_fail_per = ($this_failed / $this_tot) * 100;
    } else {
      $this_canc_per = 0;
      $this_fail_per = 0;
    }




    $data3 = [round($this_del_per, 2), round($this_canc_per, 2), round($this_fail_per, 2)];
    $now = Carbon::now();
    $now_mon = $now->format('F') . ' ' . $now->format('Y');
    $s5_chartOptions = [
      "colors" => [
        "#ffc10790",
        "#FF000090",
        "#ea5455"

      ],
      "plotOptions" => [
        "radialBar" => [
          "size" => 185,
          "hollow" => [
            "size" => "15%"

          ],
          "track" => [
            "margin" => 15
          ],
          "dataLabels" => [
            "name" => [
              "fontSize" => "1rem",
              "fontFamily" => "Montserrat"
            ],
            "value" => [
              "fontSize" => "1rem",
              "fontFamily" => "Montserrat"
            ],
            "total" => [
              "show" => false,
              "fontSize" => "1rem",
              "label" => "Delivered",
            ],
          ],
        ],
      ],
      "legend" => [
        "show" => true,
        "position" => "bottom",
        "horizontalAlign" => "center"
      ],
      "stroke" => [
        "lineCap" => "round"
      ],
      "labels" => [
        "Delivered",
        "Cancelled",
        "Failed"
      ]
    ];
    $s5[] = [
      'series' => $data3,
      'earnings' => $currency . round($current_month, 2),
      'per' => abs(round($per, 2)),
      'text' => $text,
      'now_mon' => $now_mon,
      'chartOptions' => $s5_chartOptions
    ];

    if ($ad) {
      $recent = DB::table('requests')->orderby('id', 'desc')->get();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      $recent = DB::table('requests')->whereIn('restaurant_id', $datas)->orderby('id', 'desc')->get();
    }

    $td = [];
    $i = 0;
    foreach ($recent as $re) {
      if ($i < 5) {
        if ($re->status == 7) {
          $avatarVariant = "light-primary";
          $avatar = "CheckCircleIcon";
          $deduction = false;
        } elseif ($re->status >= 9) {
          $avatarVariant = "light-danger";
          $avatar = "XCircleIcon";
          $deduction = true;
        } elseif ($re->status ==  -1 || $re->status == -2) {
          $avatarVariant = "light-danger";
          $avatar = "AlertTriangleIcon";
          $deduction = true;
        } else {
          $avatarVariant = "light-info";
          $avatar = "ClockIcon";
          $deduction = false;
        }
        if ($re->status == "0") {
          $status = "New Order";
        }
        if ($re->status == "1") {
          $status = "Order Accepted";
        }
        if ($re->status == "2") {
          $status = "Driver Assigned";
        }
        if ($re->status == "3") {
          $status = "Delivered to Driver";
        }
        if ($re->status == "4") {
          $status = "Towards Customer";
        }
        if ($re->status == "5") {
          $status = "Reached Customer";
        }
        if ($re->status == "6") {
          $status = "Delivered To Customer";
        }
        if ($re->status == "7") {
          $status = "Completed";
        }
        if ($re->status >= "9") {
          $status = "Cancelled";
        }

        if ($re->status == "-1") {
          $status = "Failed";
        }
        if ($re->status == "-2") {
          $status = "Failed";
        }
        if ($re->status == "-3") {
          $status = "On Hold";
        }
        $td[] = [
          'avatar' => $avatar,
          'avatarVariant' => $avatarVariant,
          'deduction' => $deduction,
          'mode' => $re->order_id,
          'payment' => $re->bill_amount,
          'types' => $status,
          'order_id' => $re->id,
        ];
        $i++;
      }
    }
    if ($ad) {
      $top_res = DB::table('requests')->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
        ->select('restaurant_id', DB::raw('count(*) as res_id'), 'restaurant_name', 'total_earnings', 'restaurants.image')
        ->groupBy('restaurant_id')->where('requests.status', 7)->orderbyDesc('res_id')->limit(5)
        ->get();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $top_res = DB::table('requests')->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
          ->whereIn('requests.restaurant_id', $datas)->select('restaurant_id', DB::raw('count(*) as res_id'), 'restaurant_name', 'total_earnings', 'restaurants.image')
          ->groupBy('restaurant_id')->where('requests.status', 7)->orderbyDesc('res_id')->limit(5)
          ->get();
      } else {
        $top_res = DB::table('requests')->join('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
          ->select('restaurant_id', DB::raw('count(*) as res_id'), 'restaurant_name', 'total_earnings', 'restaurants.image')
          ->groupBy('restaurant_id')->where('requests.status', 7)->orderbyDesc('res_id')->limit(5)
          ->get();
      }
    }
    $td2 = [];
    $i = 0;
    foreach ($top_res as $re) {
      if ($i < 5) {
        $image = File::exists(public_path('restaurant_uploads/') . $re->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $re->image : (File::exists(public_path('restaurant_uploads/') . $re->restaurant_id . '/' . 'Logo/' . $re->image) ? BASE_URL . RESTAURANT_UPLOADS_PATH . $re->restaurant_id . '/' . 'Logo/' . $re->image : BASE_URL . UPLOADS_PATH_LOGO . $re->image);

        $td2[] = [
          'avatar' => $image,
          'restaurant_name' => $re->restaurant_name,
          'restaurant_id' => $re->restaurant_id,
          'count' => $re->res_id,
          'earnings' => $currency . number_format((float)$re->total_earnings, 2, '.', '')
        ];
        $i++;
      }
    }

    if ($ad) {
      $top_user = DB::table('requests')->join('users', 'users.id', '=', 'requests.user_id')
        ->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'profile_image')
        ->groupBy('user_id')->where('requests.status', 7)->orderbyDesc('users_count')->limit(5)
        ->get();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));
      if (count($datas) >= 1) {
        $top_user = DB::table('requests')->join('users', 'users.id', '=', 'requests.user_id')->whereIn('requests.restaurant_id', $datas)
          ->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'profile_image')
          ->groupBy('user_id')->where('requests.status', 7)->orderbyDesc('users_count')->limit(5)
          ->get();
      } else {
        $top_user = DB::table('requests')->join('users', 'users.id', '=', 'requests.user_id')
          ->select('user_id', DB::raw('count(*) as users_count'), 'name', DB::raw("SUM(bill_amount) as paidsum"), 'profile_image')
          ->groupBy('user_id')->where('requests.status', 7)->orderbyDesc('users_count')->limit(5)
          ->get();
      }
    }
    $td3 = [];
    $i = 0;
    foreach ($top_user as $re) {
      if ($i < 5) {
        if ($re->profile_image == "") {
          $image = BASE_URL . "13.png";
        } else {
          $image = File::exists(public_path('/') . $re->profile_image) ? BASE_URL . $re->profile_image : $re->profile_image;
        }

        $td3[] = [
          'avatar' => $image,
          'user_name' => $re->name,
          'user_id' => $re->user_id,
          'count' => $re->users_count,
          'earnings' => $currency . number_format((float)$re->paidsum, 2, '.', '')
        ];
        $i++;
      }
    }


    if ($ad) {
      $months_del = DB::table('requests')
        ->where('status', 7)
        ->whereYear('created_at', Carbon::now()->year)

        ->select(
          DB::raw("(COUNT(*)) as count"),
          DB::raw("MONTHNAME(created_at) as month_name")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month_name')
        ->get()
        ->toArray();

      $months_can = DB::table('requests')
        ->whereIn('status', [9, 10, 11, 12, 13, 14])
        ->whereYear('created_at', Carbon::now()->year)

        ->select(
          DB::raw("(COUNT(*)) as count"),
          DB::raw("MONTHNAME(created_at) as month_name")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month_name')
        ->get()
        ->toArray();

      $months_fail = DB::table('requests')
        ->whereYear('created_at', Carbon::now()->year)

        ->whereIn('status', [-1, -2])
        ->select(
          DB::raw("(COUNT(*)) as count"),
          DB::raw("MONTHNAME(created_at) as month_name")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month_name')
        ->get()
        ->toArray();
    } else {
      $datas =  array_map('intval', json_decode($ad2->restaurant_id));

      $months_del = DB::table('requests')
        ->whereYear('created_at', Carbon::now()->year)

        ->where('status', 7)
        ->whereIn('requests.restaurant_id', $datas)
        ->select(
          DB::raw("(COUNT(*)) as count"),
          DB::raw("MONTHNAME(created_at) as month_name")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month_name')
        ->get()
        ->toArray();

      $months_can = DB::table('requests')
        ->whereIn('status', [9, 10, 11, 12, 13, 14])
        ->whereYear('created_at', Carbon::now()->year)

        ->whereIn('requests.restaurant_id', $datas)
        ->select(
          DB::raw("(COUNT(*)) as count"),
          DB::raw("MONTHNAME(created_at) as month_name")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month_name')
        ->get()
        ->toArray();

      $months_fail = DB::table('requests')
        ->whereIn('status', [-1, -2])
        ->whereYear('created_at', Carbon::now()->year)

        ->whereIn('requests.restaurant_id', $datas)
        ->select(
          DB::raw("(COUNT(*)) as count"),
          DB::raw("MONTHNAME(created_at) as month_name")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month_name')
        ->get()
        ->toArray();
    }


    foreach ($months_del as $md) {
      $date = date_parse($md->month_name);
      $md->month_number = $date['month'];
    }
    $key_values = array_column($months_del, 'month_number');
    array_multisort($key_values, SORT_ASC, $months_del);

    foreach ($months_can as $md) {
      $date = date_parse($md->month_name);
      $md->month_number = $date['month'];
    }
    $key_values2 = array_column($months_can, 'month_number');
    array_multisort($key_values2, SORT_ASC, $months_can);

    foreach ($months_fail as $md) {
      $date = date_parse($md->month_name);
      $md->month_number = $date['month'];
    }
    $key_values3 = array_column($months_fail, 'month_number');
    array_multisort($key_values3, SORT_ASC, $months_fail);


    $months_del_arr = [];
    foreach ($months_del as $md) {
      $months_del_arr[] = $md->count;
    }

    $months_can_arr = [];
    foreach ($months_can as $md) {
      $months_can_arr[] = $md->count;
    }


    $months_fail_arr = [];
    foreach ($months_fail as $md) {
      $months_fail_arr[] = $md->count;
    }

    $months_mon_arr = [];
    foreach ($months_del as $md) {
      $months_mon_arr[] = $md->month_name;
    }

    $s60 = [
      'name' => "Delivered",
      'data' => $months_del_arr

    ];
    $s61 = [
      'name' => "Cancelled",
      'data' => $months_can_arr

    ];

    $s62 = [
      'name' => "Failed",
      'data' => $months_fail_arr

    ];
    $rt_series = [
      $s60,
      $s61,
      // $s62
    ];
    $chartOptions = [
      "chart" =>  [
        "toolbar" => [
          "show" => false
        ]
      ],
      "dataLabels" =>  [
        "enabled" => false
      ],
      "stroke" => [
        "show" => false,
        "curve" => "straight"
      ],
      "legend" => [
        "show" => true,
        "position" => "top",
        "horizontalAlign" => "left",
        "fontSize" => "14px",
        "fontFamily" => "Montserrat"
      ],
      "grid" => [
        "xaxis" => [
          "lines" => [
            "show" => true
          ],
        ],
      ],
      "xaxis" => [
        "categories" => $months_mon_arr
      ],
      "yaxis" => [],
      "fill" => [
        "opacity" => 1,
        "type" => "solid"
      ],
      "tooltip" => [
        "shared" => false
      ],
      "colors" => [
        "#ffc10790",
        "#FF000090",
        "#00cfe890"

      ]
    ];

    $final = [
      'series' => $rt_series,
      'chartOptions' => $chartOptions
    ];
    $response_Array = json_encode(['statisticsItems' => $ret, 'statisticsOrder' => $s, 'statisticsProfit' => $s2, 'revenue' => $final, 'earningsChart' => $s5, 'transactionData' => $td, 'top_rest' => $td2, 'top_users' => $td3]);


    return $response_Array;
  }


  public function order_dashboard_statistics(Request $request)
  {

    $total_order = DB::table('requests')->whereDate('created_at', Carbon::today())->count();
    $total_orders[] = [
      'subtitle' => "Today Orders",
      'title' => round($total_order, 2),
      'icon' => "CalendarIcon",
      'color' => "light-warning",
    ];

    $total_com = DB::table('requests')->whereDate('created_at', Carbon::today())->where('status', 7)->count();
    $total_comp[] = [
      'subtitle' => "Today Completed Orders",
      'title' => round($total_com, 2),
      'icon' => "CheckCircleIcon",
      'color' => "light-success",
    ];

    $total_can = DB::table('requests')->whereDate('created_at', Carbon::today())->whereIn('status', [10, 11, 12, 13, 14])->count();
    $total_canc[] = [
      'subtitle' => "Today Cancelled Orders",
      'title' => round($total_can, 2),
      'icon' => "XCircleIcon",
      'color' => "light-danger",
    ];

    $total_pro = DB::table('requests')->whereDate('created_at', Carbon::today())->whereIn('status', [1, 2, 3, 4, 5, 6, 7, 8, 9])->count();
    $total_pros[] = [
      'subtitle' => "Today Processing Orders",
      'title' => round($total_pro, 2),
      'icon' => "ClockIcon",
      'color' => "light-info",
    ];

    $val = [$total_orders, $total_comp, $total_canc, $total_pros];
    // dd($val);
    $ret = [];
    for ($i = 0; $i < count($val); $i++) {

      $ret[] = [
        'color' => $val[$i][0]['color'],
        'customClass' => "mb-2",
        'icon' => $val[$i][0]['icon'],
        'subtitle' => $val[$i][0]['subtitle'],
        'title' => round($val[$i][0]['title'], 2),
      ];
    }


    $newTicket = DB::table('requests')->whereDate('created_at', Carbon::today())->where('status', 0)->count();
    $openTicket =  DB::table('requests')->whereDate('created_at', Carbon::today())->whereIn('status', [1, 2, 3, 4, 5, 6, 7, 8, 9])->count();
    $responseTime = DB::table('requests')->whereDate('created_at', Carbon::today())->where('status', 7)->count();
    $tot = DB::table('requests')->whereDate('created_at', Carbon::today())->count();
    $s1_series[] = [$responseTime];


    $s1[] = [
      'series' => $s1_series,
    ];

    $recent = DB::table('requests')->orderby('id', 'desc')->get();

    $td = [];
    $i = 0;
    foreach ($recent as $re) {
      if ($i < 5) {
        if ($re->status == 7) {
          $avatarVariant = "light-primary";
          $avatar = "CheckCircleIcon";
          $deduction = false;
        } elseif ($re->status >= 9) {
          $avatarVariant = "light-danger";
          $avatar = "XCircleIcon";
          $deduction = true;
        } elseif ($re->status ==  -1 || $re->status == -2) {
          $avatarVariant = "light-danger";
          $avatar = "AlertTriangleIcon";
          $deduction = true;
        } else {
          $avatarVariant = "light-info";
          $avatar = "ClockIcon";
          $deduction = false;
        }
        if ($re->status == "0") {
          $status = "New Order";
        }
        if ($re->status == "1") {
          $status = "Order Accepted";
        }
        if ($re->status == "2") {
          $status = "Driver Assigned";
        }
        if ($re->status == "3") {
          $status = "Delivered to Driver";
        }
        if ($re->status == "4") {
          $status = "Towards Customer";
        }
        if ($re->status == "5") {
          $status = "Reached Customer";
        }
        if ($re->status == "6") {
          $status = "Delivered To Customer";
        }
        if ($re->status == "7") {
          $status = "Completed";
        }
        if ($re->status >= "9") {
          $status = "Cancelled";
        }

        if ($re->status == "-1") {
          $status = "Failed";
        }
        if ($re->status == "-2") {
          $status = "Failed";
        }
        if ($re->status == "-3") {
          $status = "On Hold";
        }

        $td[] = [
          'avatar' => $avatar,
          'avatarVariant' => $avatarVariant,
          'deduction' => $deduction,
          'mode' => $re->order_id,
          'payment' => $re->bill_amount,
          'types' => $status,
          'order_id' => $re->id,
        ];
        $i++;
      }
    }


    $st[] = [
      'newTicket' => $newTicket,
      'openTicket' => $openTicket,
      'responseTime' => $total_can,
      'title' => "Today Orders",
      'supportTrackerRadialBar' => $s1,
      'totalTicket' => $tot
    ];
    $response_Array = json_encode(['statisticsItems' => $ret, 'supportTracker' => $st, 'transactionData' => $td]);


    return $response_Array;
  }

  public function new_count(Request $request)
  {
    $c = DB::table('requests')->where('status', 0)->count();

    return json_encode($c);
  }
}
