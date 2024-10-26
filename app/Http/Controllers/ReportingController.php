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
        $pur_by_account = PurByAccount::where('ac1', $request->acc_id)
            ->whereBetween('DATE', [$request->fromDate, $request->toDate])
            ->get();

        return Excel::download(new Purchase1Export($pur_by_account), 'custom_data.xlsx');
    }

    public function purchase2(Request $request){
        $pipe_pur_by_account = pipe_pur_by_account::where('ac1',$request->acc_id)
        ->whereBetween('date', [$request->fromDate, $request->toDate])
        ->get();

        return $pipe_pur_by_account;
    }
}
