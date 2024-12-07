<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pur2_company_wise_item_group_wise;
use App\Models\dash_chart_for_item_group;

class DashboardIILTabController extends Controller
{
    public function IIL(Request $request)
    {
        $month = $request->month;

        $dash_chart_for_item_group_for_donut = dash_chart_for_item_group::where('dat2',$request->month)
        ->get();

        $dash_chart_for_item_group = dash_chart_for_item_group::where('ac_group_cod',5)->get();

        $item_group_name = dash_chart_for_item_group::where('ac_group_cod',5)
        ->select('item_group_name')
        ->get();

        $crc = pur2_company_wise_item_group_wise::where('dat', $month)
            ->where('item_group_cod', 1)
            ->get(['company_name', 'ttl_weight']); // Only select the necessary fields

        $hrs = pur2_company_wise_item_group_wise::where('dat', $month)
            ->where('item_group_cod', 2)
            ->get(['company_name', 'ttl_weight']);

        $eco = pur2_company_wise_item_group_wise::where('dat', $month)
            ->where('item_group_cod', 5)
            ->get(['company_name', 'ttl_weight']);

        $cosmo = pur2_company_wise_item_group_wise::where('dat', $month)
            ->where('item_group_cod', 6)
            ->get(['company_name', 'ttl_weight']);

        return response()->json([
            'dash_chart_for_item_group_for_donut' => $dash_chart_for_item_group_for_donut,
            'dash_chart_for_item_group' => $dash_chart_for_item_group,
            'CRC' => $crc,
            'HRS' => $hrs,
            'ECO' => $eco,
            'COSMO' => $cosmo,
            'item_group_name' => $item_group_name
        ]);
    }
}