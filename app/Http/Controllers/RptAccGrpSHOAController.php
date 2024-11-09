<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\balance_sub_head;
use App\Exports\PurchaseCombExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;


class RptAccGrpSHOAController extends Controller
{
    public function shoa(Request $request){
        $balance_sub_head = balance_sub_head::where('ac_code',$request->acc_id)
        ->get();

        return $balance_sub_head;
    }
}
