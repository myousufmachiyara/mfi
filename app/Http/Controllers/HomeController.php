<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dash_sub_head;
use App\Models\dash_month_sale;
use App\Models\dash_month_purchase;
use App\Models\dash_acc_group;
use App\Models\users;

use Carbon\Carbon;

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
        $long_term_loan = dash_sub_head::where('sub',18)->first();

        $pdc = dash_acc_group::where('group_cod',1)->first();
        $banks = dash_acc_group::where('group_cod',2)->first();
        $cash = dash_acc_group::where('group_cod',3)->first();
        $foreign = dash_acc_group::where('group_cod',4)->first();

        $login_users = users::where('is_login', 1)->count();

        $currentDate = Carbon::now();
        $previousMonth = $currentDate->subMonth();
        $previousMonthAndYear = $previousMonth->format('m-Y');
        
        $last_month_purchase = dash_month_purchase::where('month_year',"'$previousMonthAndYear'")->first();
        $last_month_sale = dash_month_sale::where('month_year',$previousMonthAndYear)->first();

        return view('home', compact('receivables','payables','short_term_loan','long_term_loan','pdc','banks','cash','foreign','login_users','last_month_purchase','last_month_sale'));
    }
}
