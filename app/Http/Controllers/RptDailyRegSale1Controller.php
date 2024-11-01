<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\activite5_sales;
use App\Exports\DailyRegSale1Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptDailyRegSale1Controller extends Controller
{
    public function sale1(Request $request){
        $activite5_sales = activite5_sales::whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->get();

        return $activite5_sales;
    }

    public function sale1Excel(Request $request)
    {
        $activite5_sales = activite5_sales::whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->get();

        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "daily_reg_sale1_report_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new DailyRegSale1Export($activite5_sales), $filename);
    }

    public function sale1Report(Request $request)
    {
        // Validate the request
        $request->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
            'outputType' => 'required|in:download,view',
        ]);
    
        // Retrieve data from the database
        $gd_pipe_pur_by_item_name = gd_pipe_pur_by_item_name::where('item_cod', $request->acc_id)
            ->join('ac', 'gd_pipe_pur_by_item_name.ac_cod', '=', 'ac.ac_code')
            ->join('item_entry2', 'gd_pipe_pur_by_item_name.item_cod', '=', 'item_entry2.it_cod')
            ->whereBetween('pur_date', [$request->fromDate, $request->toDate])
            ->select('gd_pipe_pur_by_item_name.*', 'item_entry2.item_name', 'ac.ac_name', 'item_entry2.item_remark')
            ->get();
    
        // Check if data exists
        if ($gd_pipe_pur_by_item_name->isEmpty()) {
            return response()->json(['message' => 'No records found for the selected date range.'], 404);
        }
    
        // Generate the PDF
        return $this->tstockingeneratePDF($gd_pipe_pur_by_item_name, $request);
    }
}
