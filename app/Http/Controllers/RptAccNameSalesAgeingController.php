<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sales_days;

class RptAccNameSalesAgeingController extends Controller
{
    public function salesAgeing(Request $request){
        $sales_days = sales_days::where('account_name',$request->acc_id)
        ->whereBetween('bill_date', [$request->fromDate, $request->toDate])
        ->orderBy('bill_date','asc')
        ->get();

        return $sales_days;
    }
}
