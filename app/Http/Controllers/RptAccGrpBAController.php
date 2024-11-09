<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\balance_all;
use App\Exports\PurchaseCombExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptAccGrpBAController extends Controller
{
    public function ba(Request $request){
        $balance_all = balance_all::where('ac_code',$request->acc_id)
        ->get();

        return $balance_all;
    }
}
