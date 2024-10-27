<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
use App\Models\AC;
use App\Models\gd_pipe_pur_by_item_name;
use App\Models\gd_pipe_sale_by_item_name;
use App\Models\gd_pipe_addless_by_item_name;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptGoDownItemNameController extends Controller
{
    //
    public function byGodownItemName()
    {
        $items = Item_entry2::orderBy('item_name', 'asc')->get();
        return view('reports.gd_item_name',compact('items'));
    }

    public function tstockin(Request $request){
        $gd_pipe_pur_by_item_name = gd_pipe_pur_by_item_name::where('item_cod',$request->acc_id)
        ->join('ac','gd_pipe_pur_by_item_name.ac_cod','=','ac.ac_code')
        ->whereBetween('pur_date', [$request->fromDate, $request->toDate])
        ->get();

        return $gd_pipe_pur_by_item_name;
        
    }

    public function tstockout(Request $request){
        $gd_pipe_sale_by_item_name = gd_pipe_sale_by_item_name::where('item_cod',$request->acc_id)
        ->join('ac','gd_pipe_sale_by_item_name.account_name','=','ac.ac_code')
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->get();

        return $gd_pipe_sale_by_item_name;
        
    }

    public function tstockbal(Request $request){
        $gd_pipe_addless_by_item_name = gd_pipe_addless_by_item_name::where('item_cod',$request->acc_id)
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->get();

        return $gd_pipe_addless_by_item_name;
        
    }
}
