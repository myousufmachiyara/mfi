<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\modules;

class UserRoleController extends Controller
{
    
    public function index(){
        return view('users.roles');
    }

    public function create(){
        $modules = modules::get();
        return view('users.create-role',compact('modules'));
    }

    public function store(Request $request){
        die(print_r($request->all()));
    }

}
