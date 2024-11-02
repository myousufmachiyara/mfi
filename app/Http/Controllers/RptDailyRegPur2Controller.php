<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\activite13_pur_pipe;
use App\Exports\DailyRegPur2Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptDailyRegPur2Controller extends Controller
{
    public function pur2(Request $request){
        $activite13_pur_pipe = activite13_pur_pipe::whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->join('ac','ac.ac_code','=','activite13_pur_pipe.account_name')
        ->join('ac as cust_acc','cust_acc.ac_code','=','activite13_pur_pipe.Cash_pur_name_ac')
        ->select('activite13_pur_pipe.*','ac.ac_name as acc_name','cust_acc.ac_name as cust_name') 
        ->get();

        return $activite13_pur_pipe;
    }
}
