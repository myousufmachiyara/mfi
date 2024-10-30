<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
use App\Models\Item_Groups;
use App\Models\AC;
use App\Models\gd_pipe_pur_by_item_name;
use App\Models\gd_pipe_sale_by_item_name;
use App\Models\gd_pipe_addless_by_item_name;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;
use Illuminate\Validation\Validator;

class RptGoDownItemNameController extends Controller
{

    public function tstockin(Request $request){
        $gd_pipe_pur_by_item_name = gd_pipe_pur_by_item_name::where('item_cod',$request->acc_id)
        ->join('ac','gd_pipe_pur_by_item_name.ac_cod','=','ac.ac_code')
        ->whereBetween('pur_date', [$request->fromDate, $request->toDate])
        ->get();

        return $gd_pipe_pur_by_item_name;
    }

    public function tstockinExcel(Request $request)
    {
        $gd_pipe_pur_by_item_name = gd_pipe_pur_by_item_name::where('item_cod',$request->acc_id)
        ->join('ac','gd_pipe_pur_by_item_name.ac_cod','=','ac.ac_code')
        ->whereBetween('pur_date', [$request->fromDate, $request->toDate])
        ->get();

        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "tstockin_report_{$accId}_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new TStockInExport($gd_pipe_pur_by_item_name), $filename);
    }
    
   


    public function tstockinReport(Request $request)
    {
        // Validate the request
        $request->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
            'acc_id' => 'required',
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

    private function tstockingeneratePDF($gd_pipe_pur_by_item_name, Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-y');
        $formattedFromDate = Carbon::parse($request->fromDate)->format('d-m-y');
        $formattedToDate = Carbon::parse($request->toDate)->format('d-m-y');
    
        $pdf = new MyPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Stock In Report Of Item ' . $request->acc_id);
        $pdf->SetSubject('Stock In Report');
        $pdf->SetKeywords('Stock In Report, TCPDF, PDF');
        $pdf->setPageOrientation('P');
    
        // Add a page and set padding
        $pdf->AddPage();
        $pdf->setCellPadding(1.2);
    
        // Report heading
        $heading = '<h1 style="font-size:20px;text-align:center; font-style:italic;text-decoration:underline;color:#17365D">Stock In Report Of Item</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
    
        // Header details
        $html = '
        <table style="border:1px solid #000; width:100%; padding:7px; border-collapse:collapse;">
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; border-bottom:1px solid #000; width:70%;">
                    Item Name: <span style="color:black;">' . $gd_pipe_pur_by_item_name[0]['item_name'] . '</span>
                </td>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    Print Date: <span style="color:black;">' . $formattedDate . '</span>
                </td>
            </tr>
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; border-bottom:1px solid #000;width:70%;">
                    Item Remarks: <span style="color:black;">' . $gd_pipe_pur_by_item_name[0]['item_remark'] . '</span>
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
                    <th style="width:14%;color:#17365D;font-weight:bold;">SI Date</th>
                    <th style="width:10%;color:#17365D;font-weight:bold;">SI ID</th>
                    <th style="width:10%;color:#17365D;font-weight:bold;">Pur Inv</th>
                    <th style="width:22%;color:#17365D;font-weight:bold;">Company Name</th>
                    <th style="width:11%;color:#17365D;font-weight:bold;">Gate Pass</th>
                    <th style="width:15%;color:#17365D;font-weight:bold;">Remarks</th>
                    <th style="width:12%;color:#17365D;font-weight:bold;">Qty In</th>
                </tr>';
    
        // Iterate through items and add rows
        $count = 1;
        $totalAmount = 0;
    
        foreach ($gd_pipe_pur_by_item_name as $item) {
            $backgroundColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff'; // Alternating row colors
    
            $html .= '
                <tr style="background-color:' . $backgroundColor . ';">
                    <td style="width:7%;">' . $count . '</td>
                    <td style="width:14%;">' . Carbon::parse($item['pur_date'])->format('d-m-y') . '</td>
                    <td style="width:10%;">' . $item['prefix'] . $item['pur_id'] . '</td>
                    <td style="width:10%;">' . $item['pur_bill_no'] . '</td>
                    <td style="width:22%;">' . $item['ac_name'] . '</td>
                    <td style="width:11%;">' . $item['mill_gate_no'] . '</td>
                    <td style="width:15%;">' . $item['Pur_remarks'] . '</td>
                    <td style="width:12%;">' . $item['pur_qty'] . '</td>
                </tr>';
            
            $totalAmount += $item['pur_qty']; // Accumulate total quantity
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
        $accId = $request->acc_id;
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');
        $filename = "tstockin_report_{$accId}_from_{$fromDate}_to_{$toDate}.pdf";
    
        // Determine output type
        if ($request->outputType === 'download') {
            $pdf->Output($filename, 'D'); // For download
        } else {
            $pdf->Output($filename, 'I'); // For inline view
        }
    }
    
    public function tstockout(Request $request){
        $gd_pipe_sale_by_item_name = gd_pipe_sale_by_item_name::where('item_cod',$request->acc_id)
        ->join('ac','gd_pipe_sale_by_item_name.account_name','=','ac.ac_code')
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->get();

        return $gd_pipe_sale_by_item_name;
        
    }

    public function tstockoutReport(Request $request)
    {
        // Validate the request
        $request->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
            'acc_id' => 'required',
            'outputType' => 'required|in:download,view',
        ]);
    
        // Retrieve data from the database
        $gd_pipe_sale_by_item_name = gd_pipe_sale_by_item_name::where('item_cod', $request->acc_id)
            ->join('ac', 'gd_pipe_sale_by_item_name.account_name', '=', 'ac.ac_code')
            ->join('item_entry2', 'gd_pipe_sale_by_item_name.item_cod', '=', 'item_entry2.it_cod')
            ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
            ->select('gd_pipe_sale_by_item_name.*', 'item_entry2.item_name', 'ac.ac_name', 'item_entry2.item_remark')
            ->get();
    
        // Check if data exists
        if ($gd_pipe_sale_by_item_name->isEmpty()) {
            return response()->json(['message' => 'No records found for the selected date range.'], 404);
        }
    
        // Generate the PDF
        return $this->tstockoutgeneratePDF($gd_pipe_sale_by_item_name, $request);
    }

    private function tstockoutgeneratePDF($gd_pipe_sale_by_item_name, Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-y');
        $formattedFromDate = Carbon::parse($request->fromDate)->format('d-m-y');
        $formattedToDate = Carbon::parse($request->toDate)->format('d-m-y');
    
        $pdf = new MyPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Stock Out Report Of Item ' . $request->acc_id);
        $pdf->SetSubject('Stock Out Report');
        $pdf->SetKeywords('Stock Out Report, TCPDF, PDF');
        $pdf->setPageOrientation('P');
    
        // Add a page and set padding
        $pdf->AddPage();
        $pdf->setCellPadding(1.2);
    
        // Report heading
        $heading = '<h1 style="font-size:20px;text-align:center; font-style:italic;text-decoration:underline;color:#17365D">Stock Out Report Of Item</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
    
        // Header details
        $html = '
        <table style="border:1px solid #000; width:100%; padding:7px; border-collapse:collapse;">
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; border-bottom:1px solid #000; width:70%;">
                    Item Name: <span style="color:black;">' . $gd_pipe_sale_by_item_name[0]['item_name'] . '</span>
                </td>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    Print Date: <span style="color:black;">' . $formattedDate . '</span>
                </td>
            </tr>
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; border-bottom:1px solid #000;width:70%;">
                    Item Remarks: <span style="color:black;">' . $gd_pipe_sale_by_item_name[0]['item_remark'] . '</span>
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
                    <th style="width:14%;color:#17365D;font-weight:bold;">SO Date</th>
                    <th style="width:10%;color:#17365D;font-weight:bold;">SO ID</th>
                    <th style="width:10%;color:#17365D;font-weight:bold;">Sal Inv</th>
                    <th style="width:22%;color:#17365D;font-weight:bold;">Customer Name</th>
                    <th style="width:11%;color:#17365D;font-weight:bold;">Gate Pass</th>
                    <th style="width:15%;color:#17365D;font-weight:bold;">Remarks</th>
                    <th style="width:12%;color:#17365D;font-weight:bold;">Qty In</th>
                </tr>';
    
        // Iterate through items and add rows
        $count = 1;
        $totalAmount = 0;
    
        foreach ($gd_pipe_sale_by_item_name as $item) {
            $backgroundColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff'; // Alternating row colors
    
            $html .= '
                <tr style="background-color:' . $backgroundColor . ';">
                    <td style="width:7%;">' . $count . '</td>
                    <td style="width:14%;">' . Carbon::parse($item['sa_date'])->format('d-m-y') . '</td>
                    <td style="width:10%;">' . $item['prefix'] . $item['Sal_inv_no'] . '</td>
                    <td style="width:10%;">' . $item['pur_inv'] . '</td>
                    <td style="width:22%;">' . $item['ac_name'] . '</td>
                    <td style="width:11%;">' . $item['mill_gate'] . '</td>
                    <td style="width:15%;">' . $item['remarks'] . '</td>
                    <td style="width:12%;">' . $item['sales_qty'] . '</td>
                </tr>';
            
            $totalAmount += $item['sales_qty']; // Accumulate total quantity
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
        $accId = $request->acc_id;
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');
        $filename = "tstockout_report_{$accId}_from_{$fromDate}_to_{$toDate}.pdf";
    
        // Determine output type
        if ($request->outputType === 'download') {
            $pdf->Output($filename, 'D'); // For download
        } else {
            $pdf->Output($filename, 'I'); // For inline view
        }
    }
    

    public function tstockbal(Request $request){
        $gd_pipe_addless_by_item_name = gd_pipe_addless_by_item_name::where('item_cod',$request->acc_id)
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->get();

        return $gd_pipe_addless_by_item_name;
        
    }

}
