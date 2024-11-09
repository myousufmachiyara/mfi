<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\balance_all;
use App\Models\AC;
use App\Exports\PurchaseCombExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RptAccGrpBAController extends Controller
{
    public function ba(Request $request){
        $balance_all = balance_all::join('ac', 'ac.ac_code', '=', 'balance_all.ac_code')
        ->groupBy('balance_all.heads')  // Include other fields from SELECT that are not aggregated
        ->get();

        return $balance_all;
    }
}
