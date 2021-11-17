<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Issue extends Model 
{
    protected $table = 'issue';
    

    protected $fillable = [
        'id_user', 'id_status', 'id_project', 'id_priority', 'id_category', 'id_resolution', 
        'id_member', 'id_component', 'id_type', 'id_resolution', 'register_date',
        'close_date', 'title', 'environtment', 'description', 'target_solved',
        'downtime', 'labels'
    ];




}