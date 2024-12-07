<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sales_ageing;
use App\Models\dash_pur1_not;

class DashboardUnAdjustedVouchersTabController extends Controller
{
    public function UV(Request $request)
    {
      
        $sales_ageing = sales_ageing::leftJoin('ac', 'ac.ac_code', '=', 'sales_ageing.acc_name')
        ->where('status', 1)
        ->get(['jv2_id', 'sales_prefix', 'sales_id', 'ac_name', 'amount']);
    

        

        return response()->json([
            'sales_ageing' => $sales_ageing
        ]);
    }
}
