<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;

class ReportingController extends Controller
{
    //
    public function byAccountName()
    {
        $coa = AC::orderBy('ac_name', 'asc')->get();
        return view('reports.acc_name',compact('coa'));
    }
}
