<?php

namespace App\Http\Controllers;

use App\Models\modules;
use App\Models\roles;
use App\Models\role_access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserRoleController extends Controller
{
    
    public function index(){

        $roles = roles::get();

        return view('users.roles',compact('roles'));
    }

    public function create(){
        $modules = modules::get();
        $modulesCount = count($modules); // Count the length of the array

        return view('users.create-role',compact('modules','modulesCount'));
    }

    public function store(Request $request){

        $roles = new roles();

        if ($request->has('role_name') && $request->role_name) {
            $roles->name=$request->role_name;
        }
        if ($request->has('shortcode') && $request->shortcode) {
            $roles->shortcode=$request->shortcode;
        }
        $roles->save();

        $role_id=roles::latest()->select('id')->first()->id;

        for($i=1;$i<=$request->modules_count;$i++){

            $role_access = new role_access();
            $role_access->module_id=$request->module[$i];
            $role_access->role_id=$role_id;

            if ($request->has("create[$i]")) {
                $role_access->add = 1; // Checkbox is checked
            }
            if ($request->has("view[$i]")) {
                $role_access->view = 1; // Checkbox is checked
            }
            if ($request->has("update[$i]")) {
                $role_access->edit = 1; // Checkbox is checked
            }
            if ($request->has("delete[$i]")) {
                $role_access->edit = 1; // Checkbox is checked
            }
            if ($request->has("report[$i]")) {
                $role_access->report = 1; // Checkbox is checked
            }

            $role_access->save();
        }

        return redirect()->route('all-roles');
    }

}
