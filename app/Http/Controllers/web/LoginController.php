<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\api\BaseController;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;

class LoginController extends BaseController
{
    /**
     * return to view login screen
     *
     * @return view page
     */
    public function index()
    {
        //  dd("admin");
        if (Auth::check()) {
            // if (Auth::guard('admin')->check()) {
            return redirect('admin/dashboard');
        } else {
            // dd("admin");
            return view('admin/login');
        }
    }

    /**
     * admin and restaurant login check
     *
     * @param  object  $request
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

        $ad = DB::table('admin')->where('email',$request->email)->first();
        $ad2 = DB::table('admin_role')->where('email',$request->email)->first();


      
      
        if($ad && Hash::check($request->password, $ad->password)){


            $ability[]=[
                'action'=>"manage",
                'subject'=>"all"
            ];
        
            $data[] =[
                'id'=>1,
                'fullName'=>$ad->name,
                'username'=>$ad->name,
                'avatar'=>"/img/13-small.d796bffd.png",
                'email'=>$ad->email,
                'ability'=>$ability,
                'role'=>1,
                'privilege'=>-1,
              
            ];
              
            $message ="success";
			$status = true;
			$response_Array = json_encode(['message'=>$message,'status'=>$status,'data'=>$data]);
	
	
			return $response_Array;
        }elseif($ad2 && Hash::check($request->password, $ad2->password)){

            $privilege = DB::table('privileges')->where('role_id',$ad2->role_id)->get();
            $ability = [];
            $ability[]=[
                'action'=>"manage",
                'subject'=>"dashboard"
            ];
            
            foreach($privilege as $pri){
                $ability[]=[
                    'action'=>"read",
                    'subject'=>$pri->name
                ];
            }
         
            $data[] =[
                'id'=>1,
                'fullName'=>$ad2->name,
                'username'=>$ad2->name,
                'avatar'=>"/img/13-small.d796bffd.png",
                'email'=>$ad2->email,
                'ability'=>$ability,
                'role'=>1,
                'privilege'=>$ad2->role_id,
              
            ];

            $message ="success";
			$status = true;
			$response_Array = json_encode(['message'=>$message,'status'=>$status,'data'=>$data]);
	
	
			return $response_Array;
        }else{
            $message ="Wrong email or password try again";
			$status = false;
			$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
			return $response_Array;
        }
        // if (Auth::attempt($credentials)) {
        //     $request->session()->put('userid', auth()->user()->id);
        //     $request->session()->put('user_name', auth()->user()->name);
        //     $request->session()->put('role', 1);
        //     $request->session()->put('admin_url', 'admin');
        //     session()->put('privilege', -1);


        //     $message ="s1";
		// 	$status = true;
		// 	$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
		// 	return $response_Array;
        // } elseif (Auth::guard('role')->attempt($credentials)) {
        //     $admin_role = $this->admin_role->where('email', $request->email)->first();
        //     $request->session()->put('userid', $admin_role->role_id);
        //     $request->session()->put('user_name', $admin_role->name);
        //     $request->session()->put('role', 1);
        //     $request->session()->put('admin_url', 'admin');
        //     session()->put('privilege', $admin_role->role_id);


        //     $message ="s2";
		// 	$status = true;
		// 	$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
		// 	return $response_Array;
        // } else {
        //     $message ="Wrong email or password try again 2";
		// 	$status = false;
		// 	$response_Array = json_encode(['message'=>$message,'status'=>$status]);
	
	
		// 	return $response_Array;
        // }
    }

    public function logout(Request $request)
    {
        //dd("ad log");
        $request->session()->forget('userid');
        Auth::logout();
        //  Auth::guard('restaurant')->logout();

        return redirect('/admin')->with('success', 'Logout success');
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
