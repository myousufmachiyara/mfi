<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\balance_acc_group;
use App\Exports\PurchaseCombExport;
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
}
