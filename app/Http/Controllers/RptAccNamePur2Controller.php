<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\pipe_pur_by_account;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptAccNamePur2Controller extends Controller
{
    public function purchase2(Request $request){
        $pipe_pur_by_account = pipe_pur_by_account::where('ac1',$request->acc_id)
        ->whereBetween('date', [$request->fromDate, $request->toDate])
        ->get();

        return $pipe_pur_by_account;
    }
}
