<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dash_pur1_mill;

class DashboardGARDERTabController extends Controller
{
    public function GARDER(Request $request)
    {
        $month = $request->month;

        $garder_mill = dash_pur1_mill::where('dat', $month)
            ->where('ac_group_cod', 10)
            ->get(['ac_name', 'weight']); // Only select the necessary fields

        

        return response()->json([
            'garder_mill' => $garder_mill
        ]);
    }
}
