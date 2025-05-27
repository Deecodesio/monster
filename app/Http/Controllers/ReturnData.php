<?php

namespace App\Http\Controllers;

class ReturnData extends Controller {
	public $flag;
	public $flagMsg;
	public $data;

	public function __construct ($flag = false, $flagMsg = null, $data = null) {
		$this->flag 	= $flag;
		$this->flagMsg 	= $flagMsg;
		$this->data 	= $data;
	}
}