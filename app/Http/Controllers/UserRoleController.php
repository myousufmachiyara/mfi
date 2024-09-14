<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    //
    public function index(){
        return view('users.roles');
    }
}
