<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\annual_sale;
use App\Models\annual_pur;

class DashboardANNUALTabController extends Controller
{
    public function ANNUAL(Request $request)
    {
        $fromMonth = $request->from;
        $toMonth = $request->to;

        $annual_sale = annual_sale::whereBetween('dat', [$fromMonth, $toMonth])
            ->get(['sale_type', 'total_dr_amt', 'ttl_weight']); // Adjust query for date range

        $annual_pur = annual_pur::whereBetween('dat', [$fromMonth, $toMonth])
            ->get(['pur_type', 'total_cr_amt', 'ttl_weight']); // Adjust query for date range

        return response()->json([
            'annual_sale' => $annual_sale,
            'annual_pur' => $annual_pur
        ]);
    }

}
