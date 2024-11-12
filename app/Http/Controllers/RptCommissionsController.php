<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\comm_pipe_rpt;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;
use Illuminate\Validation\Validator;
use App\Exports\TStockInExport;

class RptCommissionsController extends Controller
{
    public function comm(Request $request){
        $comm_pipe_rpt = comm_pipe_rpt::where('item',$request->acc_id)
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->orderBy('ac_name', 'asc')
        ->orderBy('sa_date', 'asc')
        ->get();

        return $comm_pipe_rpt;
    }
        
}
