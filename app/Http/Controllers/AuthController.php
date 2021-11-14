<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:6'
        ]);

        $email = $request->input("email");
        $password = $request->input("password");

        // $hashPwd = Hash::make($password);
        $hashPwd = md5($password);

        $data = [
            "email" => $email,
            "password" => $hashPwd
        ];



        if (User::create($data)) {
            $out = [
                "message" => "register_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "vailed_regiser",
                "code"   => 404,
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'login' => 'required',
            'password' => 'required|min:6'
        ]);

        $login = $request->input("login");
        $password = md5($request->input("password"));

        // $user = User::where("email", $email)->first();
        $user = DB::connection("mysql2")->table("system_user")->where('login', $login )->first();
        $user_role = DB::connection("mysql2")->table("system_user_group")
        ->leftjoin("system_user", "system_user.id", "=", "system_user_group.system_user_id")
        ->leftjoin("system_group", "system_group.id", "=", "system_user_group.system_group_id")
        ->select ("system_user.id", "system_user.name", "system_user.password", "system_group.name as nama", "system_group.id as id_group")
        ->where("system_user_group.system_user_id", "=", $user->id)
        ->get();



        if (!$user) {
            $out = null;
            return response()->json($out, 401);
        }


        if ($password == $user->password) {
            // $newtoken  = $this->generateRandomString();

            // $user->update([
            //     'token' => $newtoken
            // ]);
            $groupArr = array();
            foreach($user_role as $item){
                array_push($groupArr, [
                    "group_id" => $item->id_group, 
                    "group_name" => substr(str_replace(array("»GROUP", "-"), " ", $item->nama ), 3)
                ]);
            };
            $out = [
                
                    "user_id" => $user->id,
                    "user_name" => $user->name,
                    "user_login" => $user->login,
                    "user_email" => $user->email,
                    "user_picture" => $user->picture,
                    "user_group" => $groupArr,
                
            ];
            return response()->json($out, 201);
        } else {
            $out = [];
            return response()->json($out, 401);
        }

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
