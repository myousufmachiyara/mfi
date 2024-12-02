<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dash_pur1_mill;
use App\Models\dash_sale_by_item_group_customer;

class DashboardGARDERTabController extends Controller
{
    public function GARDER(Request $request)
    {
        $month = $request->month;

        $garder_mill = dash_pur1_mill::where('dat', $month)
        ->where('ac_group_cod', 10)
        ->get(['ac_name', 'weight']); // Only select the necessary fields

        
        $garder_customer = dash_sale_by_item_group_customer::where('dat', $month)
        ->whereIn('item_group_code', [10, 11]) // Use whereIn for multiple values
        ->get(['ac_name', 'weight']); // Only select the necessary fields
        

        return response()->json([
            'garder_mill' => $garder_mill,
            'garder_customer' => $garder_customer
        ]);
    }
}
