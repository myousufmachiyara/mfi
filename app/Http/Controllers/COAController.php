<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;

class COAController extends Controller
{
    //
    public function getAccountDetails(Request $request)
    {
        $acc_details = AC::where('ac_code', $request->id)->get();
        return $acc_details;
    }
}
