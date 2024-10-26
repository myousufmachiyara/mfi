<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\pur_by_account;
use App\Models\pipe_pur_by_account;
use App\Exports\Purchase1Export;
use Maatwebsite\Excel\Facades\Excel;

class ReportingController extends Controller
{
    //
    public function byAccountName()
    {
        $coa = AC::orderBy('ac_name', 'asc')->get();
        return view('reports.acc_name',compact('coa'));
    }

    public function purchase1(Request $request){
        $pur_by_account = pur_by_account::where('ac1',$request->acc_id)
        ->whereBetween('DATE', [$request->fromDate, $request->toDate])
        ->get();

        return $pur_by_account;
    }

    public function purchase1Excel(Request $request)
    {
        $pur_by_account = pur_by_account::where('ac1', $request->acc_id)
            ->whereBetween('DATE', [$request->fromDate, $request->toDate])
            ->get();

        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "purchase1_report_{$accId}_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new Purchase1Export($pur_by_account), $filename);
    }

    public function purchase2(Request $request){
        $pipe_pur_by_account = pipe_pur_by_account::where('ac1',$request->acc_id)
        ->whereBetween('date', [$request->fromDate, $request->toDate])
        ->get();

        return $pipe_pur_by_account;
    }
}
