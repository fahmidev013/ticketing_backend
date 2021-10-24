<?php

namespace App\Http\Controllers;

use App\Models\Employees;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function coba($id)
    {
        $emp = Employees::where('employee_id', $id)->first();
        if ($emp == null) {
            return 'KOSONG';
        } else {
            return response()->json($emp, 200);
            // return ('<div>'.$emp->first_name . '<br>' . $emp->email . '<br>' . $emp->hire_date . '</div>');
        }

    }

    //
}
