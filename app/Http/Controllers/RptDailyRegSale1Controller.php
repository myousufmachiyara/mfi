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
        ->join('ac','ac.ac_code','=','activite5_sales.account_name')
        ->select('activite5_sales.*','ac.ac_name as ac_name') 
        ->get();

        return $activite5_sales;
    }

    public function sale1Excel(Request $request)
    {
        $activite5_sales = activite5_sales::whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->join('ac','ac.ac_code','=','activite5_sales.account_name')
        ->select('activite5_sales.*','ac.ac_name') 
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
        // Validate request
        $validated = $request->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
            'outputType' => 'required|in:download,view',
        ]);

        // Retrieve data from the database
        $activite5_sales = activite5_sales::whereBetween('sa_date', [$validated['fromDate'], $validated['toDate']])
            ->join('ac', 'ac.ac_code', '=', 'activite5_sales.account_name')
            ->select('activite5_sales.*', 'ac.ac_name as acc_name')
            ->get();

        // Check if data exists
        if ($activite5_sales->isEmpty()) {
            return response()->json(['message' => 'No records found for the selected date range.'], 404);
        }

        // Generate the PDF
        return $this->sale1generatePDF($activite5_sales, $validated);
    }

    public function Header() {
        // Table Headers
        $html = '<table border="1" style="border-collapse: collapse;text-align:center; width:100%;">
                    <tr>
                        <th style="width:7%;color:#17365D;font-weight:bold;">S/No</th>
                        <th style="width:12%;color:#17365D;font-weight:bold;">Date</th>
                        <th style="width:13%;color:#17365D;font-weight:bold;">Inv No.</th>
                        <th style="width:12%;color:#17365D;font-weight:bold;">Ord No.</th>
                        <th style="width:19%;color:#17365D;font-weight:bold;">Account Name</th>
                        <th style="width:22%;color:#17365D;font-weight:bold;">Remarks</th>
                        <th style="width:15%;color:#17365D;font-weight:bold;">Bill Amount</th>
                    </tr>
                </table>';
        $this->writeHTML($html, true, false, false, false, '');
    }

    private function sale1generatePDF($activite5_sales, array $validated)
    {
        $currentDate = Carbon::now()->format('d-m-y');
        $formattedFromDate = Carbon::parse($validated['fromDate'])->format('d-m-y');
        $formattedToDate = Carbon::parse($validated['toDate'])->format('d-m-y');

        $pdf = new MyPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Daily Register Sale 1');
        $pdf->SetSubject('Daily Register Sale 1');
        $pdf->SetKeywords('Daily Register Sale 1, TCPDF, PDF');
        $pdf->setPageOrientation('P');

        // Add the first page
        $pdf->AddPage();
        $pdf->setCellPadding(1.2);

        // Report Heading
        $heading = '<h1 style="font-size:20px;text-align:center; font-style:italic;text-decoration:underline;color:#17365D">Daily Register Sale 1</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');

        // Header Details
        $headerDetails = "
        <table style='border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;'>
            <tr>
                <td style='font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000; width:33%;'>
                    From Date: <span style='color:black;'>$formattedFromDate</span>
                </td>
                <td style='font-size:12px; font-weight:bold; color:#17365D; text-align:left; width:34%;'>
                    To Date: <span style='color:black;'>$formattedToDate</span>
                </td>
                <td style='font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000; width:33%;'>
                    Print Date: <span style='color:black;'>$currentDate</span>
                </td>
            </tr>
        </table>";
        $pdf->writeHTML($headerDetails, true, false, true, false, '');

        // Main Table Rows
        $html = '<table border="1" style="border-collapse: collapse;text-align:center; width:100%;">';
        $count = 1;
        $totalAmount = 0;

        foreach ($activite5_sales as $item) {
            $bgColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff';
            $html .= "
            <tr style='background-color:$bgColor;'>
                <td style='width:7%;'>$count</td>
                <td style='width:12%;'>" . Carbon::parse($item->sa_date)->format('d-m-y') . "</td>
                <td style='width:13%;'>{$item->prefix}{$item->Sal_inv_no}</td>
                <td style='width:12%;'>{$item->pur_ord_no}</td>
                <td style='width:19%;'>{$item->acc_name}</td>
                <td style='width:22%;'>{$item->Cash_pur_name}{$item->Sales_Remarks}</td>
                <td style='width:15%;'>" . number_format($item->bill_amt, 0) . "</td>
            </tr>";
            $totalAmount += $item->bill_amt;
            $count++;
        }

        // Totals Row
        $html .= "
        <tr style='background-color:#d9edf7; font-weight:bold;'>
            <td colspan='6' style='text-align:right;'>Total:</td>
            <td style='width:15%;'>" . number_format($totalAmount, 0) . "</td>
        </tr>";
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');

        // Prepare and Output PDF
        $fromDate = Carbon::parse($validated['fromDate'])->format('Y-m-d');
        $toDate = Carbon::parse($validated['toDate'])->format('Y-m-d');
        $filename = "daily_reg_sale1_report_from_{$fromDate}_to_{$toDate}.pdf";

        if ($validated['outputType'] === 'download') {
            $pdf->Output($filename, 'D'); // Download
        } else {
            $pdf->Output($filename, 'I'); // View Inline
        }
    }



  
    

}
