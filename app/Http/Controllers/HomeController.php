<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dash_sub_head;
use App\Models\dash_month_sale;
use App\Models\dash_month_purchase;
use App\Models\dash_acc_group;

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
        $receivables = dash_sub_head::where('sub',1)->first();
        $payables  = dash_sub_head::where('sub',7)->first();
        $short_term_loan = dash_sub_head::where('sub',23)->first();
        $long_term_loan = dash_sub_head::where('sub',24)->first();

        return view('home', compact('receivables','payables','short_term_loan','long_term_loan'));
    }
}
