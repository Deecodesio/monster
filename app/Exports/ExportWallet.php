<?php

namespace App\Exports;

use App\Models\Use;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
class ExportWallet implements FromCollection, WithHeadings
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
			$wallet = DB::table('users')
								->where('wallet_amount','>',0)
								->orderbyDesc('wallet_amount')
								->select('name','wallet_amount')->get();


			
		}
		elseif($to != null){
			$wallet = DB::table('users')
			->where('wallet_amount','>',0)
			->where('created_at', '>=', $from)
			 ->where('created_at', '<=',$to)
				->orderbyDesc('wallet_amount')
                ->select('name','wallet_amount')->get();
		}
		else{
           
			$wallet = DB::table('users')
			->where('wallet_amount','>',0)
			->where('created_at', '>=', $from)
				->orderbyDesc('wallet_amount')
                ->select('name','wallet_amount')->get();

		}
        return $wallet;
    }
    public function headings(): array
    {
        //Put Here Header Name That you want in your excel sheet 
        return [
            'User',
            'Wallet Amount',
            
        ];
    }
}
