<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
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
        $gd_pipe_pur_by_item_name = gd_pipe_pur_by_item_name::where('item_cod',$request->acc_id)
        ->join('ac','gd_pipe_pur_by_item_name.ac_cod','=','ac.ac_code')
        ->whereBetween('pur_date', [$request->fromDate, $request->toDate])
        ->get();

        $currentDate = Carbon::now();

        // Format the date if needed
        $formattedDate = $currentDate->format('d-m-y');
        $formattedFromDate = Carbon::createFromFormat('Y-m-d', $request->fromDate)->format('d-m-y');
        $formattedToDate = Carbon::createFromFormat('Y-m-d', $request->toDate)->format('d-m-y');

        $pdf = new MyPDF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Stock In Report Of Item '.$request->acc_id);
        $pdf->SetSubject('Stock In Report Of Item '.$request->acc_id);
        $pdf->SetKeywords('Stock In Report Of Item, TCPDF, PDF');
        $pdf->setPageOrientation('P');

        // Add a page
        $pdf->AddPage();
        
        $pdf->setCellPadding(1.2); // Set padding for all cells in the table

        // margin top
        $margin_top = '.margin-top {
            margin-top: 10px;
        }';
        // $pdf->writeHTML('<style>' . $margin_top . '</style>', true, false, true, false, '');

        // margin bottom
        $margin_bottom = '.margin-bottom {
            margin-bottom: 5px;
        }';

        $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Stock In Report Of Item</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Item Name: <span style="color:black;">'.$gd_pipe_pur_by_item_name[0]['acc_id'].'</span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> Print Date: <span style="color:black;font-weight:normal;">'.$formattedDate.'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"><span style="color:black;font-weight:normal;"></span></td>';
        // $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Phone No: <span style="color:black;">'.$gd_pipe_pur_by_item_name[0]['phone_no'].'</span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> From Date: <span style="color:black;font-weight:normal;">'.$formattedFromDate.'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"><span style="color:black;font-weight:normal;"></span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> To Date: <span style="color:black;font-weight:normal;">'.$formattedToDate.'</span></td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;text-align:center" >';
        $html .= '<tr>';
        $html .= '<th style="width:7%;color:#17365D;font-weight:bold;">S/No</th>';
        $html .= '<th style="width:14%;color:#17365D;font-weight:bold;">SI Date</th>';
        $html .= '<th style="width:10%;color:#17365D;font-weight:bold;">SI ID.</th>';
        $html .= '<th style="width:10%;color:#17365D;font-weight:bold;">Pur Inv</th>';
        $html .= '<th style="width:22%;color:#17365D;font-weight:bold;">Company Name</th>';
        $html .= '<th style="width:11%;color:#17365D;font-weight:bold;">Gate Pass</th>';
        $html .= '<th style="width:15%;color:#17365D;font-weight:bold;">Remarks</th>';
        $html .= '<th style="width:12%;color:#17365D;font-weight:bold;">Qty In</th>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->setTableHtml($html);

        $count=1;
        $totalAmount=0;

        $html .= '<table cellspacing="0" cellpadding="5" style="text-align:center">';
        foreach ($gd_pipe_pur_by_item_name as $items) {
            if($count%2==0)
            {
                $html .= '<tr style="background-color:#f1f1f1">';
                $html .= '<td style="width:7%;">'.$count.'</td>';
                $html .= '<td style="width:14%;">'.Carbon::createFromFormat('Y-m-d', $items['pur_date'])->format('d-m-y').'</td>';
                $html .= '<td style="width:10%;">'.$items['prefix'].$items['pur_id'].'</td>';
                $html .= '<td style="width:10%;">'.$items['pur_bill_no'].'</td>';
                $html .= '<td style="width:22%;">'.$items['ac_name'].'</td>';
                $html .= '<td style="width:11%;">'.$items['mill_gate_no'].'</td>';
                $html .= '<td style="width:15%;">'.$items['Pur_remarks'].'</td>';
                $html .= '<td style="width:12%;">'.$items['pur_qty'].'</td>';
                $totalAmount=$totalAmount+$items['pur_qty'];
                $html .= '</tr>';
            }
            else{
                $html .= '<tr style="background-color:#f1f1f1">';
                $html .= '<td style="width:7%;">'.$count.'</td>';
                $html .= '<td style="width:14%;">'.Carbon::createFromFormat('Y-m-d', $items['pur_date'])->format('d-m-y').'</td>';
                $html .= '<td style="width:10%;">'.$items['prefix'].$items['pur_id'].'</td>';
                $html .= '<td style="width:10%;">'.$items['pur_bill_no'].'</td>';
                $html .= '<td style="width:22%;">'.$items['ac_name'].'</td>';
                $html .= '<td style="width:11%;">'.$items['mill_gate_no'].'</td>';
                $html .= '<td style="width:15%;">'.$items['Pur_remarks'].'</td>';
                $html .= '<td style="width:12%;">'.$items['pur_qty'].'</td>';
                $totalAmount=$totalAmount+$items['pur_qty'];
                $html .= '</tr>';
            }
            $count++;
        }
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');

        $currentY = $pdf->GetY();

        $pdf->SetFont('helvetica', 'B', 12);

        // Column 3
        $pdf->SetXY(155, $currentY+5);
        $pdf->MultiCell(20, 5, 'Total', 1, 'C');

        $pdf->SetXY(175, $currentY+5);
        $pdf->MultiCell(28, 5, $totalAmount, 1, 'C');

        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');


        $filename = "tstockin_report_{$accId}_from_{$fromDate}_to_{$toDate}.pdf";

        $pdf->Output($filename, 'I');
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
