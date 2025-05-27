<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Socialite;
use DB;
use Auth;
use App\Model\Users;
use App\Services\SocialGoogleAccountService;

class SocialAuthGoogleController extends Controller
{
    /**
     * Create a redirect method to facebook api.
     *
     * @return void
     */
    public function redirect()
    {
        //dd(Socialite::driver('google'));      
        return Socialite::driver('google')->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function callback()
    {
        //dd("call");
        $user = Socialite::driver('google')->user();

        $check_email = Users::where('email', $user->email)->first();
        if ($check_email && !empty($check_email)) {
            Auth::guard('user')->login($check_email);
            if (session()->get('modal_url')) {
                return redirect()->to(session()->get('modal_url'));
            } else {
                return redirect()->route('user.home');
            }
        }

        $new_user = [
            'phone' => '',
            'email' => $user->email ?? '',
            'authToken' => '',
            'device_type' => 'web',
            'password' => '',
            'device_token' => '',
            'facebook_id' => '',
            'name' => $user->name,
            'profile_image' => $user->avatar,
            'login_type' => 1,
            'referral_code' => ''
        ];

        DB::table('users')->insert($new_user);
        $user = Users::where('email', $user->email)->first();

        Auth::guard('user')->login($user);
        if (session()->get('modal_url')) {
            return redirect()->to(session()->get('modal_url'));
        } else {
            return redirect()->route('user.home');
        }
    }
}
