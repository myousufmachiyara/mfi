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
        $itemStockData = ItemGroup::with(['itemEntries.pipeItemStock'])
            ->where('item_group_cod', $request->acc_id)
            ->get()
            ->map(function ($itemGroup) {
                return $itemGroup->itemEntries->map(function ($itemEntry) {
                    // Filter out items with zero opening balance
                    if ($itemEntry->pipeItemStock->opp_bal != 0) {
                        return [
                            'item_group_cod' => $itemGroup->item_group_cod,
                            'it_cod' => $itemEntry->it_cod,
                            'opp_bal' => $itemEntry->pipeItemStock->opp_bal,
                            'wt' => $itemEntry->pipeItemStock->opp_bal * $itemEntry->weight,
                            'item_name' => $itemEntry->item_name,
                            'item_remark' => $itemEntry->item_remark,
                        ];
                    }
                })->filter(); // Remove null values
            })->flatten(1);
    
        return response()->json($itemStockData);
    }
    
    

}
