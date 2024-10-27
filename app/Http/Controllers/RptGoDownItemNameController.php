<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
use App\Models\AC;
use App\Models\gd_pipe_pur_by_item_name;
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
        >join('ac','gd_pipe_pur_by_item_name.ac_cod','=','ac.ac_code')
        ->whereBetween('pur_date', [$request->fromDate, $request->toDate])
        ->get();

        return $gd_pipe_pur_by_item_name;
    }
}
