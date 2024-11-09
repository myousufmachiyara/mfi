<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
use App\Models\Item_Groups;
use App\Models\pipe_stock_all_by_item_group;
use App\Models\gd_pipe_pur_by_item_group;
use App\Models\gd_pipe_sales_by_item_group;
use App\Models\AC;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;
use Illuminate\Validation\Validator;
use App\Exports\GoDownByItemGrpSIExport;

class RptGoDownItemGroupController extends Controller
{

    public function stockAll(Request $request){
        $pipe_stock_all_by_item_group = pipe_stock_all_by_item_group::where('item_group_cod',$request->acc_id)
        ->where('opp_bal', '<>', 0)
        ->get();

        return $pipe_stock_all_by_item_group;
    }
        
    
    
    public function stockin(Request $request){

        $gd_pipe_pur_by_item_group = gd_pipe_pur_by_item_group::where('item_group_cod', $request->acc_id)
        ->join('ac', 'ac.ac_code', '=', 'gd_pipe_pur_by_item_group.account_name')
        ->join('item_entry2', 'item_entry2.it_cod', '=', 'gd_pipe_pur_by_item_group.item_cod')
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->select('gd_pipe_pur_by_item_group.*', 'ac.ac_name', 'item_entry2.item_name')
        ->orderBy('sa_date','asc')
        ->get();

        return $gd_pipe_pur_by_item_group;
    }

    

   

    public function stockout(Request $request){
        $gd_pipe_sales_by_item_group = gd_pipe_sales_by_item_group::where('item_group_cod', $request->acc_id)
        ->join('ac', 'ac.ac_code', '=', 'gd_pipe_sales_by_item_group.account_name')
        ->join('item_entry2', 'item_entry2.it_cod', '=', 'gd_pipe_sales_by_item_group.item_cod')
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->select('gd_pipe_sales_by_item_group.*', 'ac.ac_name', 'item_entry2.item_name')
        ->orderBy('sa_date','asc')
        ->get();

        return $gd_pipe_sales_by_item_group;
    }

  

    public function stockAllT(Request $request){
        $pipe_stock_all_by_item_group = pipe_stock_all_by_item_group::where('item_group_cod',$request->acc_id)
        ->get();

        return $pipe_stock_all_by_item_group;
    }
}
