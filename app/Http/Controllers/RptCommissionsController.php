<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\comm_pipe_rpt;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;
use Illuminate\Validation\Validator;
use App\Exports\TStockInExport;

class RptCommissionsController extends Controller
{
    public function comm(Request $request){
        $comm_pipe_rpt = comm_pipe_rpt::where('account_name',$request->acc_id)
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->get();

        return $comm_pipe_rpt;
    }
        
    public function commExcel(Request $request)
    {
        $gd_pipe_addless_by_item_name = gd_pipe_addless_by_item_name::where('item_cod',$request->acc_id)
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->get();
                
        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "tstockbal_report_{$accId}_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new TStockBalExport($gd_pipe_addless_by_item_name), $filename);
    }

    public function commReport(Request $request)
    {
        // Validate the request
        $request->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
            'acc_id' => 'required',
            'outputType' => 'required|in:download,view',
        ]);
    
        // Retrieve data from the database
        $gd_pipe_addless_by_item_name = gd_pipe_addless_by_item_name::where('item_cod', $request->acc_id)
        ->join('item_entry2', 'gd_pipe_addless_by_item_name.item_cod', '=', 'item_entry2.it_cod')
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->select('gd_pipe_addless_by_item_name.*', 'item_entry2.item_name', 'item_entry2.item_remark')
        ->get();
    
        // Check if data exists
        if ($gd_pipe_addless_by_item_name->isEmpty()) {
            return response()->json(['message' => 'No records found for the selected date range.'], 404);
        }
    
        // Generate the PDF
        return $this->stockAllgeneratePDF($gd_pipe_addless_by_item_name, $request);
    }

    private function stockAllgeneratePDF($gd_pipe_addless_by_item_name, Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-y');
        $formattedFromDate = Carbon::parse($request->fromDate)->format('d-m-y');
        $formattedToDate = Carbon::parse($request->toDate)->format('d-m-y');
    
        $pdf = new MyPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Stock Bal Report Of Item  - ' . $gd_pipe_addless_by_item_name[0]['item_name']);
        $pdf->SetSubject('Stock Bal Report');
        $pdf->SetKeywords('Stock Bal Report, TCPDF, PDF');
        $pdf->setPageOrientation('P');
    
        // Add a page and set padding
        $pdf->AddPage();
        $pdf->setCellPadding(1.2);
    
        // Report heading
        $heading = '<h1 style="font-size:20px;text-align:center; font-style:italic;text-decoration:underline;color:#17365D">Stock Balance Report Of Item</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
    
        // Header details
        $html = '
        <table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; border-bottom:1px solid #000; width:70%;">
                    Item Name: <span style="color:black;">' . $gd_pipe_addless_by_item_name[0]['item_name'] . '</span>
                </td>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    Print Date: <span style="color:black;">' . $formattedDate . '</span>
                </td>
            </tr>
            <tr>
                <td style="font-size:12px; color:#17365D; border-bottom:1px solid #000;width:70%;">
                    Item Remarks: <span style="color:black;">' . $gd_pipe_addless_by_item_name[0]['item_remark'] . '</span>
                </td>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    From Date: <span style="color:black;">' . $formattedFromDate . '</span>
                </td>
            </tr>
            <tr>
                <td></td>
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
                    <th style="width:14%;color:#17365D;font-weight:bold;">Date</th>
                    <th style="width:10%;color:#17365D;font-weight:bold;">ID</th>
                    <th style="width:21%;color:#17365D;font-weight:bold;">Reason</th>
                    <th style="width:18%;color:#17365D;font-weight:bold;">Remarks</th>
                    <th style="width:15%;color:#17365D;font-weight:bold;">Qty Add</th>
                    <th style="width:15%;color:#17365D;font-weight:bold;">Qty Less</th>
                </tr>';
    
        // Iterate through items and add rows
        $count = 1;
        $totalAdd = 0;
        $totalLess = 0;
    
        foreach ($gd_pipe_addless_by_item_name as $item) {
            $backgroundColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff'; // Alternating row colors
    
            $html .= '
                <tr style="background-color:' . $backgroundColor . ';">
                    <td style="width:7%;">' . $count . '</td>
                    <td style="width:14%;">' . Carbon::parse($item['sa_date'])->format('d-m-y') . '</td>
                    <td style="width:10%;">' . $item['Sal_inv_no'] . '</td>
                    <td style="width:21%;">' . $item['reason'] . '</td>
                    <td style="width:18%;">' . $item['remarks'] . '</td>
                    <td style="width:15%;">' . $item['pc_add'] . '</td>
                    <td style="width:15%;">' . $item['pc_less'] . '</td>
                </tr>';
            
            $totalAdd += $item['pc_add'];
            $totalLess += $item['pc_less']; // Accumulate total quantity
            $count++;
        }
    
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Display total amount at the bottom
        $cellWidth = 25;
        $currentY = $pdf->GetY();

        // Render $totalLess
        $pdf->SetXY(148, $currentY + 2);
        $pdf->MultiCell($cellWidth, 5, $totalAdd, 1, 'C');

        // Render $totalAdd adjacent to $totalLess
        $pdf->SetXY(148 + $cellWidth, $currentY + 2);
        $pdf->MultiCell($cellWidth, 5, $totalLess, 1, 'C');

    
        // Prepare filename for the PDF
        $accId = $request->acc_id;
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');
        $filename = "tstockbal_report_{$accId}_from_{$fromDate}_to_{$toDate}.pdf";
    
        // Determine output type
        if ($request->outputType === 'download') {
            $pdf->Output($filename, 'D'); // For download
        } else {
            $pdf->Output($filename, 'I'); // For inline view
        }
    }
}
