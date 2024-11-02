<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\activites9_gen_acas;
use App\Exports\DailyRegJV2Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptDailyRegJV2Controller extends Controller
{
    public function jv2(Request $request){
        $activites9_gen_acas = activites9_gen_acas::whereBetween('jv_date', [$request->fromDate, $request->toDate])
        ->get();

        return $activites9_gen_acas;
    }
}
