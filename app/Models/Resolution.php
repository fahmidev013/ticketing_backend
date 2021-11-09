<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Resolution extends Model 
{
    protected $table = 'resolution';
    

    protected $fillable = [
        'description'
    ];


}