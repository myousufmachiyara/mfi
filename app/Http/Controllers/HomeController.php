<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\role_access;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        return view('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_permission = role_access::where('role_id',session('user_role'))
        ->select('module_id','view')
        ->get();
        
        $user_access = $user_permission->toArray();

        session(['user_access' => $user_access]);

        // die(print_r(session('user_access')[0]['module_id']));

        return view('home',);
    }
}
