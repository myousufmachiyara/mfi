<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\activite13_pur_pipe;
use App\Exports\DailyRegPur2Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptDailyRegPur2Controller extends Controller
{
    public function pur2(Request $request){
        $activite13_pur_pipe = activite13_pur_pipe::whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->join('ac','ac.ac_code','=','activite13_pur_pipe.account_name')
        ->join('ac as cust_acc','cust_acc.ac_code','=','activite13_pur_pipe.Cash_pur_name_ac')
        ->select('activite13_pur_pipe.*','ac.ac_name as acc_name','cust_acc.ac_name as cust_name') 
        ->get();

        return $activite13_pur_pipe;
    }

    public function pur2Excel(Request $request)
    {
        $activite13_pur_pipe = activite13_pur_pipe::whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->join('ac','ac.ac_code','=','activite13_pur_pipe.account_name')
        ->join('ac as cust_acc','cust_acc.ac_code','=','activite13_pur_pipe.Cash_pur_name_ac')
        ->select('activite13_pur_pipe.*','ac.ac_name as acc_name','cust_acc.ac_name as cust_name') 
        ->get();

        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "daily_reg_pur2_report_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new DailyRegPur2Export($activite13_pur_pipe), $filename);
    }

    public function pur2Report(Request $request)
    {
        // Validate the request
        $request->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
            'outputType' => 'required|in:download,view',
        ]);
    
        // Retrieve data from the database
        $activite13_pur_pipe = activite13_pur_pipe::whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->join('ac','ac.ac_code','=','activite13_pur_pipe.account_name')
        ->join('ac as cust_acc','cust_acc.ac_code','=','activite13_pur_pipe.Cash_pur_name_ac')
        ->select('activite13_pur_pipe.*','ac.ac_name as acc_name','cust_acc.ac_name as cust_name') 
        ->get();
    
        // Check if data exists
        if ($activite13_pur_pipe->isEmpty()) {
            return response()->json(['message' => 'No records found for the selected date range.'], 404);
        }
    
        // Generate the PDF
        return $this->pur2generatePDF($activite13_pur_pipe, $request);
    }

    private function pur2generatePDF($activite13_pur_pipe, Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-y');
        $formattedFromDate = Carbon::parse($request->fromDate)->format('d-m-y');
        $formattedToDate = Carbon::parse($request->toDate)->format('d-m-y');
    
        $pdf = new MyPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Daily Register Purchase Pipe ' . $request->acc_id);
        $pdf->SetSubject('Daily Register Purchase Pipe');
        $pdf->SetKeywords('Daily Register Purchase Pipe, TCPDF, PDF');
        $pdf->setPageOrientation('P');
    
        // Add a page and set padding
        $pdf->AddPage();
        $pdf->setCellPadding(1.2);
    
        // Report heading
        $heading = '<h1 style="font-size:20px;text-align:center; font-style:italic;text-decoration:underline;color:#17365D">Daily Register Purchase Pipe</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
    
        // Header details
        $html = '
        <table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    Print Date: <span style="color:black;">' . $formattedDate . '</span>
                </td>
            </tr>
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    From Date: <span style="color:black;">' . $formattedFromDate . '</span>
                </td>
            </tr>
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left;border-left:1px solid #000; width:30%;">
                    To Date: <span style="color:black;">' . $formattedToDate . '</span>
                </td>
            </tr>
        </table>';

        $pdf->writeHTML($html, true, false, true, false, '');

    
        // Table header for data
        $html = '
            <table border="1" style="border-collapse: collapse; text-align: center;">
                <tr>
                    <th style="width:7%;color:#17365D;font-weight:bold;">S/No</th>
                    <th style="width:10%;color:#17365D;font-weight:bold;">Date</th>
                    <th style="width:10%;color:#17365D;font-weight:bold;">Inv No.</th>
                    <th style="width:10%;color:#17365D;font-weight:bold;">Ord No.</th>
                    <th style="width:22%;color:#17365D;font-weight:bold;">Account Name</th>
                    <th style="width:15%;color:#17365D;font-weight:bold;">Customer Name</th>
                    <th style="width:15%;color:#17365D;font-weight:bold;">Remarks</th>
                    <th style="width:12%;color:#17365D;font-weight:bold;">Bill Amt</th>
                </tr>';
    
        // Iterate through items and add rows
        $count = 1;
        $totalAmount = 0;
    
        foreach ($activite13_pur_pipe as $item) {
            $backgroundColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff'; // Alternating row colors
    
            $html .= '
                <tr style="background-color:' . $backgroundColor . ';">
                    <td style="width:7%;">' . $count . '</td>
                    <td style="width:10%;">' . Carbon::parse($item['sa_date'])->format('d-m-y') . '</td>
                    <td style="width:10%;">' . $item['Sale_inv_no']. '</td>
                    <td style="width:10%;">' . $item['pur_ord_no'] . '</td>
                    <td style="width:22%;">' . $item['acc_name'] . '</td>
                    <td style="width:15%;">' . $item['cust_name'] . '</td>
                    <td style="width:15%;">' . $item['Sales_Remarks'] . '</td>
                    <td style="width:12%;">' . $item['bill_amt'] . '</td>
                </tr>';
            
            $totalAmount += $item['bill_amt']; // Accumulate total quantity
            $count++;
        }
    
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Display total amount at the bottom
        $currentY = $pdf->GetY();
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetXY(155, $currentY + 5);
        $pdf->MultiCell(20, 5, 'Total', 1, 'C');
        $pdf->SetXY(175, $currentY + 5);
        $pdf->MultiCell(28, 5, $totalAmount, 1, 'C');
    
        // Prepare filename for the PDF
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');

        $filename = "daily_reg_pur2_report_from_{$fromDate}_to_{$toDate}.pdf";

        // Determine output type
        if ($request->outputType === 'download') {
            $pdf->Output($filename, 'D'); // For download
        } else {
            $pdf->Output($filename, 'I'); // For inline view
        }
    }
}
