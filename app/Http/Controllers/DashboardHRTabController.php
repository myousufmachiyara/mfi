<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dash_pur_2_summary_monthly_companywise;
use App\Models\sale_pipe_summary_of_party_by_mill;
use App\Models\AC;

class DashboardHRTabController extends Controller
{
    public function HR(Request $request){
        $dash_pur_2_summary_monthly_companywise = dash_pur_2_summary_monthly_companywise::where('dat2',$request->month)
        ->get();

        $coa = AC::where('status', 1)->get();

        return [
            'dash_pur_2_summary_monthly_companywise' => $dash_pur_2_summary_monthly_companywise,
            'coa' => $coa,
        ];
    }

    public function monthlyTonage(Request $request){
        $dash_pur_2_summary_monthly_companywise = dash_pur_2_summary_monthly_companywise::where('dat2',$request->month)
        ->get();

        return $dash_pur_2_summary_monthly_companywise;
    }

    public function monthlyTonageOfCustomer(Request $request){
        $sale_pipe_summary_of_party_by_mill = sale_pipe_summary_of_party_by_mill::where('dat2',$request->month)
        ->where('account_name',$request->month)
        ->get();

        return $sale_pipe_summary_of_party_by_mill;
    }
}
