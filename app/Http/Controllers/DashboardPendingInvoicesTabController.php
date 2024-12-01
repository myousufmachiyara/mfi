<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dash_sale1_not_final;

class DashboardPendingInvoicesTabController extends Controller
{
    public function PENDING_INVOICES(Request $request)
    {
        // $month = $request->month;

        $sale1_not = dash_sale1_not_final::where('bill_not', 0)
        ->get(['prefix', 'Sal_inv_no', 'sa_date', 'pur_ord_no', 'account_name', 'Cash_pur_name', 'Sales_remarks']);// Only select the necessary fields

        
        return response()->json([
            'sale1_not' => $sale1_not
        ]);
    }
}
