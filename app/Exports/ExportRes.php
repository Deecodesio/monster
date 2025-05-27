<?php

namespace App\Exports;

use App\Models\Use;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Controllers\api\BaseController;
use DB;
class ExportRes implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $from;
    protected $to;
    public function __construct($from,$to)
    {
        $this->from = $from;
        $this->to = $to;
    }
    public function collection()
    {

        $from = $this->from; 
        $to = $this->to;  
        if($from == null && $to == null)
		{
			$restaurant_details = DB::table('restaurants')
            ->leftjoin('add_city', 'add_city.id', '=', 'restaurants.city')
            ->leftjoin('add_area', 'add_area.id', '=', 'restaurants.area')
            ->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
            ->get();


			
		}
		elseif($to != null){
			
			$restaurant_details = DB::table('restaurants')
			->where('restaurants.created_at', '>=', $from)
			 ->where('restaurants.created_at', '<=',$to)
            ->leftjoin('add_city', 'add_city.id', '=', 'restaurants.city')
            ->leftjoin('add_area', 'add_area.id', '=', 'restaurants.area')
            ->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
            ->get();
		}
		else{
			$restaurant_details = DB::table('restaurants')
			->where('restaurants.created_at', '>=', $from)
            ->leftjoin('add_city', 'add_city.id', '=', 'restaurants.city')
            ->leftjoin('add_area', 'add_area.id', '=', 'restaurants.area')
            ->select('restaurants.*', 'add_city.city as city', 'add_area.area as area')
            ->get();

		}
            foreach($restaurant_details as $datas)
            {
              if ($datas->address != "") {
                  $decodedText = html_entity_decode($datas->address);
                  $datas->address = $decodedText;
                }
            }

			$data = DB::table('country')->where('is_default', 1)->first();
			$data->value =  $data->currency_symbol;
			$date = now()->timestamp;
			
			$res_fields = [];
        foreach ($restaurant_details as $key => $value) {
           $restaurant_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('restaurant_commision');
            $value->restaurant_commision = round($restaurant_commision, 2);
            $admin_commision = DB::table('requests')->where('restaurant_id', $value->id)->sum('admin_commision');
            $value->admin_commision = round($admin_commision, 2);

            $payout_done = DB::table('restaurant_payout_history')->where('restaurant_id', $value->id)->sum('payout_amount');
            $value->payout_done =$payout_done;
            $total_orders = DB::table('requests')->where('restaurant_id', $value->id)->where('status', 7)->count();
            $value->total_orders =$total_orders;
        

			$restaurant_commision = $data->value.' '. $value->restaurant_commision;
			$pending_payout = $data->value.' '.$value->pending_payout;
			$payout_done = $data->value.' '.$value->payout_done;
			
			$res_fields[] = [
                'restaurant_name' => $value->restaurant_name,
                'email' => $value->email,
                'contact' => $value->contact,
				'address' => $value->address,
                'city' => $value->city,
                'area' => $value->area,
				'total_orders' => $value->total_orders,
                'restaurant_commision' => $restaurant_commision,
                'pending_payout' => $pending_payout,
				'payout_done' => $payout_done,
               

            ];
			
			
		}
        return collect($res_fields);
    }

    public function headings(): array
    {
        //Put Here Header Name That you want in your excel sheet 
        return [
            'Restaurant',
            'Email',
            'Phone Number',
            'Address',
            'City',
            'Area',
            'Total Orders',
            'Total Restaurant Earnings',
            'Pending Payouts',
            'Payouts Completed',
            
        ];
    }
}
