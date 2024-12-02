<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dash_item_of_month_by_weight_pur2;
use App\Models\dash_item_of_month_by_qty_pur2;

class DashboardItemOfTheMonthTabController extends Controller
{
    public function ItemOfMonth(Request $request)
    {
        // Validate the 'month' parameter to ensure it's in the correct format
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $month = $request->month;

        // Define item groups and their respective prefixes
        $itemGroups = [
            7 => 'hr', 
            8 => 'wt', 
            1 => 'crc', 
            5 => 'eco', 
            6 => 'cosmo'
        ];

        $results = [];

        // Fetch data for each item group
        foreach ($itemGroups as $code => $prefix) {
            $results["{$prefix}byweight"] = dash_item_of_month_by_weight_pur2::where('dat', $month)
                ->where('item_group_cod', $code)
                ->get(['item_name', 'weight']);

            $results["{$prefix}byqty"] = dash_item_of_month_by_qty_pur2::where('dat', $month)
                ->where('item_group_cod', $code)
                ->get(['item_name', 'qty']);
        }

        // Return the response as JSON
        return response()->json($results);
    }
}
