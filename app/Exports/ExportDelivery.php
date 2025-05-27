<?php

namespace App\Exports;

use App\Models\Use;
use App\Model\OrderRatings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Controllers\api\BaseController;
use DB;
class ExportDelivery implements FromCollection, WithHeadings
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
			$delivery_boy_details = DB::table('delivery_partners')
            ->leftjoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
            ->leftjoin('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
            ->leftjoin('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
            ->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
            ->get();

		}
		elseif($to != null){
			
			$delivery_boy_details = DB::table('delivery_partners')
			->where('delivery_partners.created_at', '>=', $from)
			 ->where('delivery_partners.created_at', '<=',$to)
            ->leftjoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
            ->leftjoin('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
            ->leftjoin('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
            ->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
            ->get();
		}
		else{
			$delivery_boy_details = DB::table('delivery_partners')
			->where('delivery_partners.created_at', '>=', $from)
			
            ->leftjoin('delivery_partner_details', 'delivery_partner_details.delivery_partners_id', '=', 'delivery_partners.id')
            ->leftjoin('add_city', 'add_city.id', '=', 'delivery_partner_details.city')
            ->leftjoin('vehicle', 'vehicle.id', '=', 'delivery_partner_details.vehicle_name')
            ->select('delivery_partners.*', 'vehicle.*', 'add_city.city as city', 'vehicle.vehicle_name as vehicle_name', 'delivery_partner_details.*')
            ->get();

		}
      
        $data = DB::table('country')->where('is_default', 1)->first();
        $data->value =  $data->currency_symbol;
        $date = now()->timestamp;

        $res_fields = [];
     foreach ($delivery_boy_details as $key => $value) {

      $value->delivery_boy_commision = DB::table('requests')->where('delivery_boy_id',$value->id)->sum('delivery_boy_commision');

      $value->admin_commision = DB::table('requests')->where('delivery_boy_id',$value->id)->sum('admin_commision');

      $city = DB::table('add_city')->where('id',$value->city)->first();

      if($city)
      {
        $value->city_name = $city->city;

      }else{
        $value->city_name = '';
      }

      $area = DB::table('add_area')->where('id',$value->area)->first();
        if($area){
      $value->area_name = $area->area;
     }
            $total_orders = DB::table('requests')->where('delivery_boy_id', $value->id)->where('status', 7)->count();
            $value->total_orders =$total_orders;
            $id= $value->id;
            
            $delivery_commision = $data->value.' '.$value->delivery_boy_commision;
            $pending_payout = $data->value.' '.$value->pending_payout;
            $res_fields[] = [
                'name' => $value->name,
                'email' => $value->email,
                'phone' => $value->phone,
                'address' => $value->address,
                'city_name' => $value->city_name,
                'area' => $value->area,
                'total_orders' => $value->total_orders,
                'delivery_commision' => $delivery_commision,
                'pending_payout' => $pending_payout,
                
            ];
    }
    
    

    return collect($res_fields);
    }
    public function headings(): array
    {
        //Put Here Header Name That you want in your excel sheet 
        return [
            'Name',
            'Email',
            'Phone Number',
            'Address',
            'City',
            'Area',
            'Total Orders',
            'Delivery Boy Commision',
            'Pending Payouts',
            
            
        ];
    }
}
