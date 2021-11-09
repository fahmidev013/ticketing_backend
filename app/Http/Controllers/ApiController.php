<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Resolution;
use App\Models\Issue;
use App\Models\Type;
use App\Models\Status;
use App\Models\Component;
use App\Models\Project;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function getCategory()
    {
        $res = Category::all();
        return response()->json($res, 200);
    }

    public function getComponent()
    {
        $res = Component::all();
        return response()->json($res, 200);
    }

    public function getIssue()
    {
        $res = DB::select("SELECT * FROM issue limit 1");
        // $res = Issue::all();
        return response()->json($res, 200);
    }

    public function getPriority()
    {
        $res = Priority::all();
        return response()->json($res, 200);
    }

    public function getProject()
    {
        $res = Project::all();
        return response()->json($res, 200);
    }

    public function getResolution()
    {
        $res = Resolution::all();
        return response()->json($res, 200);
    }

    public function getStatus()
    {
        $res = Status::all();
        return response()->json($res, 200);
    }

    public function getType()
    {
        $res = Type::all();
        return response()->json($res, 200);
    }


    function generateRandomString($length = 80)
    {
        $karakkter = '012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $panjang_karakter = strlen($karakkter);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $karakkter[rand(0, $panjang_karakter - 1)];
        }
        return $str;
    }
}
