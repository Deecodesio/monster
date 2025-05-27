<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $top_user = DB::table('requests')->join('users', 'users.id', '=', 'requests.user_id')
					->select( 'name','users.phone',DB::raw('count(*) as users_count'),DB::raw("SUM(bill_amount) as paidsum"))
					->groupBy('user_id','name','phone')->where('requests.status', 7)->orderbyDesc('users_count')
                     ->get();
         foreach($top_user as $t)
		{
          $t->paidsum = number_format((float)( $t->paidsum), 2, '.', ',');
		}
        return   $top_user ;
    }

    public function headings(): array
    {
        //Put Here Header Name That you want in your excel sheet 
        return [
            'User',
            'Phone Number',
            'Orders',
            'Amount',
            
        ];
    }
}
