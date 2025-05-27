<?php

namespace App\Http\Controllers\store;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;
use Illuminate\Support\Facades\Auth;
use Hash;

class LoginController extends BaseController
{


    /**
     * return to view login screen
     * 
     * @return view page
     */
    public function index()
    {
       //  dd("res");
        if (Auth::guard('restaurant')->check()) {
            return redirect('store/dashboard');
        } else {
           // dd("store");
             return view('store/login');
        }
    }


    /**
     * admin and restaurant login check
     * 
     * @param object $request
     * 
     * @return view page to home screen
     */
    public function login(Request $request)
    {
         
       
        if (! $request->email || ! $request->password) {
            $message ="Wrong email or password try again";
			$status = false;
			$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
			return $response_Array;
        }

        $credentials = $request->only('email', 'password');
        //dd(Hash::make($request->password));

        $ad = DB::table('restaurants')->where('email',$request->email)->first();
    


      
      
        if($ad && Hash::check($request->password, $ad->password)){
            $res= DB::table('restaurants')->where('id',$ad->id)
            ->first();
            $datas= DB::table('business_type')->where('id',$res->business_type)
        ->first();
       
        $privilege = [];
        $ability = [];
       
       
       

        if( $datas->layout_id == 2)
        {
            $privilege[]=[
                'addon',
                'brands',
                'business',
                'reason',
                'category-view',
                'city-view',
                'city-edit',
                'contents',
                'cuisines-view',
                'document-view',
                'document-edit',
                'driver',
                'food',
                'payout',
                'promocodes',
                'report-view',
                'banners',
                'restaurant-view',
                'restaurant-edit',
                'roles',
                'settings',
                'user-view',
                'vehicles',
                'order-view',
                'dashboard',
                
                'store_order-view',
                
                'store_dashboard',
                'store_pos',
                'store_product',
                'store_promocodes',
                'store_report-view',
                'store_restaurant',
                'store_restaurant_timing',
                'store_map',
               
                
               
            ];
          
            foreach($privilege as $pri){

                $ability[]=[
                    'action'=>"read",
                    'subject'=>$pri
                ];
            }
        }
        elseif ($datas->layout_id == 3)
        {
            $privilege[]=[
                'addon',
                'brands',
                'business',
                'reason',
                'category-view',
                'city-view',
                'city-edit',
                'contents',
                'cuisines-view',
                'document-view',
                'document-edit',
                'driver',
                'food',
                'payout',
                'promocodes',
                'report-view',
                'banners',
                'restaurant-view',
                'restaurant-edit',
                'roles',
                'settings',
                'user-view',
                'vehicles',

                'order-view',
                'dashboard',
                
                'store_order-view',
               
                'store_dashboard',
                'store_promocodes',
                'store_report-view',
                'store_restaurant',
                'store_restaurant_timing',
                'store_map',
                
            ];
            foreach($privilege as $pri){
                $ability[]=[
                    'action'=>"read",
                    'subject'=>$pri
                ];
            }
        }
        else{
            $ability[]=[
                'action'=>"read",
                'subject'=>"all"
            ];
        }
        // $ability[]=[
        //     'action'=>"read",
        //     'subject'=>"all"
        // ];
        
        
            $data[] =[
                'id'=>$ad->id,
                'fullName'=>$ad->restaurant_name,
                'username'=>$ad->restaurant_name,
                'avatar'=>"/img/13-small.d796bffd.png",
                'email'=>$ad->email,
                'ability'=>$ability,
                'role'=>2,
                'privilege'=>-1,
              
            ];
            $message ="Success";
			$status = true;
			$response_Array = json_encode(['message'=>$message,'status'=>$status,'data'=>$data]);
	
	
			return $response_Array;
        }else{
            $message ="Wrong email or password try again";
			$status = false;
			$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
			return $response_Array;
        }
       
    }



    public function logout(Request $request)
    {
       // dd("res log");
        $request->session()->forget('res_id');
       // Auth::logout();
        Auth::guard('restaurant')->logout();
        
            return redirect('/store')->with('success', 'Logout success');
            }

    public function setlocale($locale)
    {

        $locales = explode(',', \Config::get('app.locales'));
        if (in_array($locale, $locales)) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
