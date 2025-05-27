<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    //table name
    protected $table = 'admin';

    //unset remember_token column for authentication
    protected $rememberTokenName = false;
}
