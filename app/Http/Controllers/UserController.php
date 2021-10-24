<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class UserController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware("auth");
    // }

    public function index()
    {
        return "Anda Berhasil masuk";
    }
}