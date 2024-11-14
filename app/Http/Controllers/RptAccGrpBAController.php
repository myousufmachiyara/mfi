<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\balance_all;
use App\Exports\ACGroupBAExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RptAccGrpBAController extends Controller
{
    public function ba(Request $request){
        $balance_all = balance_all::all()->groupBy('heads');

        return $balance_all;
    }

    public function baExcel(Request $request)
    {
        $balance_all = balance_all::all()->groupBy('heads');
        
        // Construct the filename
        $filename = "balance_all.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new ACGroupBAExport($balance_all), $filename);
    }

    public function baReport(Request $request)
    {
        // Validate the request
        $request->validate([
            'acc_id' => 'required',
            'outputType' => 'required|in:download,view',
        ]);
    
        // Retrieve data from the database
        $balance_sub_head = balance_sub_head::where('balance_sub_head.sub',$request->acc_id)
        ->join('sub_head_of_acc as shoa','shoa.id','=','balance_sub_head.sub')
        ->select('balance_sub_head.*','shoa.sub as shoa_name')
        ->orderBy('ac_name', 'asc')
        ->get();
    
        // Check if data exists
        if ($balance_sub_head->isEmpty()) {
            return response()->json(['message' => 'No records found for the Account.'], 404);
        }
    
        // Generate the PDF
        return $this->shoageneratePDF($balance_sub_head, $request);
    }
}
