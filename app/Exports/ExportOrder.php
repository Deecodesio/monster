<?php

namespace App\Exports;

use App\Models\Use;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
class ExportOrder implements FromCollection, WithHeadings
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
			$restaurant_details = DB::table('requests')
		->leftjoin('restaurants','restaurants.id','=','requests.restaurant_id')
	   ->leftjoin('users', 'users.id', '=', 'requests.user_id')
	   ->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
	   ->select('users.name as customer_name', 'users.phone as phones','requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*','restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
	   ->get();


			
		}
		elseif($to != null){
			

			$restaurant_details = DB::table('requests')
			->where('requests.created_at', '>=', $from)
			 ->where('requests.created_at', '<=',$to)
		->leftjoin('restaurants','restaurants.id','=','requests.restaurant_id')
	   ->leftjoin('users', 'users.id', '=', 'requests.user_id')
	   ->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
	   ->select('users.name as customer_name', 'users.phone as phones','requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*','restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
	   ->get();
		}
		else{
			$restaurant_details = DB::table('requests')
			->where('requests.created_at', '>=', $from)
		->leftjoin('restaurants','restaurants.id','=','requests.restaurant_id')
	   ->leftjoin('users', 'users.id', '=', 'requests.user_id')
	   ->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
	   ->select('users.name as customer_name', 'users.phone as phones','requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*','restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
	   ->get();

		}
		
		$data = DB::table('country')->where('is_default', 1)->first();
        $data->value =  $data->currency_symbol;
		$date = now()->timestamp;

		$res_fields = [];
        foreach ($restaurant_details as $eachProduct) {
			if($eachProduct->order_status =="0"){
				$status = "New Order";
			}
			if($eachProduct->order_status =="1"){
				$status = "Order Accepted";
			}
			if($eachProduct->order_status =="2"){
				$status = "Driver Assigned";
			}
			if($eachProduct->order_status =="3"){
				$status = "Delivered to Driver";
			}
			if($eachProduct->order_status =="4"){
				$status = "Towards Customer";
			}
			if($eachProduct->order_status =="5"){
				$status = "Reached Customer";
			}
			if($eachProduct->order_status =="6"){
				$status = "Delivered To Customer";
			}
			if($eachProduct->order_status =="7"){
				$status = "Completed";
			}
			if($eachProduct->order_status >= "9"){
				$status = "Cancelled";
			}
			
			if($eachProduct->order_status =="-1"){
				$status = "Failed";
			}
			if($eachProduct->order_status =="-2"){
			  $status = "Failed";
		  }
			$tax = $data->value.' '.$eachProduct->tax;
			$offer_discount = $data->value.' '.$eachProduct->offer_discount;
			$admin_commision = $data->value.' '.$eachProduct->admin_commision;
			$delivery_boy_commision = $data->value.' '.$eachProduct->delivery_boy_commision;
			$restaurant_commision = $data->value.' '.$eachProduct->restaurant_commision;
			$item_total = $data->value.' '.$eachProduct->item_total;


			$res_fields[] = [
				'order_id' => $eachProduct->order_id,
				'ordered_time' => $eachProduct->ordered_time,
				'customer_name' => $eachProduct->customer_name,
				'phones' => $eachProduct->phones,
				'driver_name' => $eachProduct->delivery_boy_name,
				'driver_phone' => $eachProduct->delivery_boy_phone,
				'restaurant_name' => $eachProduct->restaurant_name,
				'item_total' => $item_total,
				'tax' => $tax,

				'offer_discount' => $offer_discount,
				'admin_commision' => $admin_commision,
				'delivery_boy_commision' => $delivery_boy_commision,
				'restaurant_commision' => $restaurant_commision,
				'status' => $status,
				
			];




          
        }
        return collect($res_fields);
    }
    public function headings(): array
    {
        //Put Here Header Name That you want in your excel sheet 
        return [
            'Order Id',
            'Order Time',
            'Customer Name',
            'Phone Number',
            'DeliveryBoy Name',
            'DeliveryBoy Phone',
            'Restaurant Name',
            'Item Totals',
            'Tax',
            'Offer Discount',
            'Admin Commision',
            'DeliveryBoy Commision',
            'Restaurant Commision',
            'Status',
        ];
    }
}
