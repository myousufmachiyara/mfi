<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
use App\Models\Item_Groups;
use App\Models\gd_pipe_item_stock9_much;
use App\Models\AC;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;
use Illuminate\Validation\Validator;

class RptGoDownItemGroupController extends Controller
{
    public function tstockall(Request $request)
    {

        $gd_pipe_item_stock9_much = Item_Groups::where('item_groups.item_group_cod', $request->acc_id)
            ->join('item_entry2', 'item_groups.item_group_cod', '=', 'item_entry2.item_group')
            ->join('gd_pipe_item_stock9_much', 'item_entry2.it_cod', '=', 'gd_pipe_item_stock9_much.it_cod')
            ->select(
                'item_groups.item_group_cod',
                'gd_pipe_item_stock9_much.it_cod',
                'gd_pipe_item_stock9_much.opp_bal',
                DB::raw('gd_pipe_item_stock9_much.opp_bal * item_entry2.weight AS wt'),
                'item_entry2.item_name',
                'item_entry2.item_remark'
            )
            ->where('gd_pipe_item_stock9_much.opp_bal', '<>', 0)
            ->get();

         return response()->json($gd_pipe_item_stock9_much);
     }
    

}
