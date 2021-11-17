<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class UserGroup extends Model
{
    protected $table = 'system_user_group';
    

    protected $fillable = [
        'id','system_user_id', 'system_group_id'
    ];

    protected $hidden = [
        'password',
    ];
}