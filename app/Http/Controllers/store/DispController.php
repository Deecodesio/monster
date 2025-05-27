<?php

namespace App\Http\Controllers\store;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;

class DispController extends BaseController
{

	public function disp_managerlist()
	{

		return view('admin/disp_managerlist');
	}
	public function add_dispmanager()
	{

		return view('admin/add_dispmanager');
	}
}
