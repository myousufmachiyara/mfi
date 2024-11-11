<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\balance_acc_group;
use App\Exports\ACGroupAGExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptAccGrpAGController extends Controller
{
    public function ag(Request $request){
        $balance_acc_group = balance_acc_group::where('group_cod',$request->acc_id)
        ->get();

        return $balance_acc_group;
    }

    public function agExcel(Request $request)
    {
        $balance_acc_group = balance_acc_group::where('group_cod',$request->acc_id)
        ->select('ac_code', 'ac_name', 'address', 'phone_no', 'Debit', 'Credit')
        ->get();

        $accId = $request->acc_id;
        
        // Construct the filename
        $filename = "acc_group_bal_1_report{$accId}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new ACGroupAGExport($balance_acc_group), $filename);
    }
}
