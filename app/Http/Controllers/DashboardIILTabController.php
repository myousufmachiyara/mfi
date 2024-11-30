<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pur2_company_wise_item_group_wise;

class DashboardIILTabController extends Controller
{
    public function IIL(Request $request)
    {
        $month = $request->month;

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
            'CRC' => $crc,
            'HRS' => $hrs,
            'ECO' => $eco,
            'COSMO' => $cosmo
        ]);
    }
}