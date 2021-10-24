<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model 
{

    protected $table = 'employees';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id', 'first_name', 'last_name', 'email', 'phone_number', 'hire_date'
    ];


}
