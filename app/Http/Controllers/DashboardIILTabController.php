<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pur2_company_wise_item_group_wise;
use App\Models\AC;

class DashboardIILTabController extends Controller
{ 
    public function IIL(Request $request){

        $crc = pur2_company_wise_item_group_wise::where('dat',$request->month)
        ->where('item_group_cod',1)
        ->get();

        $hrs = pur2_company_wise_item_group_wise::where('dat',$request->month)
        ->where('item_group_cod',2)
        ->get();

        $eco = pur2_company_wise_item_group_wise::where('dat',$request->month)
        ->where('item_group_cod',5)
        ->get();

        $cosmo = pur2_company_wise_item_group_wise::where('dat',$request->month)
        ->where('item_group_cod',6)
        ->get();


        return [
            'crc' => $crc,
            'hrs' => $hrs,
            'eco' => $eco,
            'cosmo' => $cosmo,
        ];
    }
}
