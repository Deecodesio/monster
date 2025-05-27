<?php

namespace App\Http\Controllers;

									
use Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Log;
use App\Http\Controllers\ReturnData;

class Customer extends Controller {

    static $table = 'users';

    public static function isExist (int $id) {

        return (bool) DB::table(self::$table)->where('id', $id)->count();

    }

}