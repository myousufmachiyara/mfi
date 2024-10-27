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
        $pur_by_account = gd_pipe_pur_by_item_name::where('it_cod',$request->acc_id)
        ->whereBetween('pur_date', [$request->fromDate, $request->toDate])
        ->get();

        return $pur_by_account;
    }
}
