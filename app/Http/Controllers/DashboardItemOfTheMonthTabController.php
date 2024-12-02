<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dash_item_of_month_by_weight_pur2;
use App\Models\dash_item_of_month_by_qty_pur2;

class DashboardItemOfTheMonthTabController extends Controller
{
    public function ItemOfMonth(Request $request)
    {
        $month = $request->month;

        $hrbyweight = dash_item_of_month_by_weight_pur2::where('dat', $month)
        ->where('item_group_cod', 7)
        ->get(['item_name', 'weight']); // Only select the necessary fields

        $hrbyqty = dash_item_of_month_by_qty_pur2::where('dat', $month)
        ->where('item_group_cod', 7)
        ->get(['item_name', 'qty']);

        $wtbyweight = dash_item_of_month_by_weight_pur2::where('dat', $month)
        ->where('item_group_cod', 8)
        ->get(['item_name', 'weight']); // Only select the necessary fields

        $wtbyqty = dash_item_of_month_by_qty_pur2::where('dat', $month)
        ->where('item_group_cod', 8)
        ->get(['item_name', 'qty']);

        $crcbyweight = dash_item_of_month_by_weight_pur2::where('dat', $month)
        ->where('item_group_cod', 1)
        ->get(['item_name', 'weight']); // Only select the necessary fields

        $crcbyqty = dash_item_of_month_by_qty_pur2::where('dat', $month)
        ->where('item_group_cod', 1)
        ->get(['item_name', 'qty']);

        $ecobyweight = dash_item_of_month_by_weight_pur2::where('dat', $month)
        ->where('item_group_cod', 5)
        ->get(['item_name', 'weight']); // Only select the necessary fields

        $ecobyqty = dash_item_of_month_by_qty_pur2::where('dat', $month)
        ->where('item_group_cod', 5)
        ->get(['item_name', 'qty']);

        $cosmobyweight = dash_item_of_month_by_weight_pur2::where('dat', $month)
        ->where('item_group_cod', 6)
        ->get(['item_name', 'weight']); // Only select the necessary fields

        $cosmobyqty = dash_item_of_month_by_qty_pur2::where('dat', $month)
        ->where('item_group_cod', 6)
        ->get(['item_name', 'qty']);


        return response()->json([
            'hrbyweight' => $hrbyweight,
            'hrbyqty' => $hrbyqty,
            'wtbyweight' => $wtbyweight,
            'wtbyqty' => $wtbyqty,
            'crbyweight' => $crbyweight,
            'crbyqty' => $crbyqty,
            'ecobyweight' => $ecobyweight,
            'ecobyqty' => $ecobyqty,
            'cosmobyweight' => $cosmobyweight,
            'cosmobyqty' => $cosmobyqty
            
        ]);
    }
}
