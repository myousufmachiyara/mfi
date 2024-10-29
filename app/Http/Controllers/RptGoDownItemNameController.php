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
    public function tstockinPDF(Request $request)
    {
        $gd_pipe_pur_by_item_name = gd_pipe_pur_by_item_name::where('item_cod', $request->acc_id)
            ->join('ac', 'gd_pipe_pur_by_item_name.ac_cod', '=', 'ac.ac_code')
            ->join('item_entry2', 'gd_pipe_pur_by_item_name.item_cod', '=', 'item_entry2.it_cod')
            ->whereBetween('pur_date', [$request->fromDate, $request->toDate])
            ->select('gd_pipe_pur_by_item_name.*', 'item_entry2.item_name','ac.ac_name')
            ->get();
    
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-y');
        $formattedFromDate = Carbon::parse($request->fromDate)->format('d-m-y');
        $formattedToDate = Carbon::parse($request->toDate)->format('d-m-y');
    
        $pdf = new MyPDF();
    
        // Set document metadata
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
        $heading = '<h1 style="font-size:20px;text-align:center;
                    font-style:italic;text-decoration:underline;color:#17365D">
                    Stock In Report Of Item
                    </h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
    
        // Header details
        $html = '
            <table>
                <tr>
                    <td style="font-size:12px;font-weight:bold;color:#17365D;">Item Name: 
                        <span style="color:black;">' . $gd_pipe_pur_by_item_name[0]['item_name'] . '</span>
                    </td>
                    <td style="font-size:12px;font-weight:bold;color:#17365D;text-align:right;">
                        Print Date: <span style="color:black;">' . $formattedDate . '</span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td style="font-size:12px;font-weight:bold;color:#17365D;text-align:right;">
                        From Date: <span style="color:black;">' . $formattedFromDate . '</span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td style="font-size:12px;font-weight:bold;color:#17365D;text-align:right;">
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
    
        // Output the PDF
        $pdf->Output($filename, 'D');
    }
    

    public function tstockout(Request $request){
        $gd_pipe_sale_by_item_name = gd_pipe_sale_by_item_name::where('item_cod',$request->acc_id)
        ->join('ac','gd_pipe_sale_by_item_name.account_name','=','ac.ac_code')
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->get();

        return $gd_pipe_sale_by_item_name;
        
    }

    public function tstockbal(Request $request){
        $gd_pipe_addless_by_item_name = gd_pipe_addless_by_item_name::where('item_cod',$request->acc_id)
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->get();

        return $gd_pipe_addless_by_item_name;
        
    }


}
