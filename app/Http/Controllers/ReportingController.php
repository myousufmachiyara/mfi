<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\pur_by_account;

class ReportingController extends Controller
{
    //
    public function byAccountName()
    {
        $coa = AC::orderBy('ac_name', 'asc')->get();
        return view('reports.acc_name',compact('coa'));
    }

    public function purchase1(Request $request){
        $pur_by_account = pur_by_account::where('ac_cod',354)
        ->whereBetween('pur_date', [$request->fromDate, $request->toDate])
        ->get();

        return $pur_by_account;
    }
}
