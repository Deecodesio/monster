<?php

namespace App\Http\Controllers\store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\api\BaseController;

class ApplicationController extends BaseController
{
    public function index()
    {
        $app = DB::table('settings')->where('key_word', "app_name")->first();
        $favicon = DB::table('settings')->where('key_word', "site_favicon")->first();
        $app_name = $app->value;
        $site_favicon = BASE_URL . RESTAURANT_UPLOADS_PATH . $favicon->value;
        $gapi = DB::table('settings')->where('key_word', 'google_api_key')->first();
        $google_key = $gapi->value;
        $meta_tit = DB::table('settings')->where('key_word', "meta_title")->first();
        $meta_de = DB::table('settings')->where('key_word', "meta_description")->first();
        $meta_title = $meta_tit->value;
        $meta_desc = $meta_de->value;
        return view('store', compact('app_name', 'site_favicon', 'google_key', 'meta_title', 'meta_desc'));
    }
}
