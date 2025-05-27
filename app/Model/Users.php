<?php

namespace App\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Users extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    //
    protected $table = 'users';

    /**
    * set relationship to food request.
    *
    */
    public function Foodrequest()
    {
        return $this->hasMany('App\Model\Foodrequest','user_id','id');
    }

    public function getRememberToken()
    {
        return null; // not supported
    }
    public function setRememberToken($value)
    {
        // not supported
    }
}
