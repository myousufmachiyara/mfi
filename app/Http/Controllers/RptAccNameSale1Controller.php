<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\sale_by_account;
use App\Exports\Sale1Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptAccNameSale1Controller extends Controller
{
    
    public function sale1(Request $request){
        $sale_by_account = sale_by_account::where('ac1',$request->acc_id)
        ->whereBetween('date', [$request->fromDate, $request->toDate])
        ->get();

        return $sale_by_account;
    }

    public function sale1Excel(Request $request)
    {
        $sale_by_account = sale_by_account::where('ac1', $request->acc_id)
            ->whereBetween('date', [$request->fromDate, $request->toDate])
            ->get();

        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "sale1_report_{$accId}_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new Sale1Export($sale_by_account), $filename);
    }

    public function sale1PDF(Request $request)
    {
        $sale_by_account = sale_by_account::where('ac1', $request->acc_id)
            ->whereBetween('date', [$request->fromDate, $request->toDate])
            ->leftjoin('ac','ac.ac_code','=','sale_by_account.ac1')
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
        $pdf->SetTitle('Sale Report Of Account '.$request->acc_id);
        $pdf->SetSubject('Sale Report Of Account '.$request->acc_id);
        $pdf->SetKeywords('Sale Report Of Account, TCPDF, PDF');
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

        $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Sale Report Of Account</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Account Name: <span style="color:black;">'.$sale_by_account[0]['ac_name'].'</span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> Print Date: <span style="color:black;font-weight:normal;">'.$formattedDate.'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"><span style="color:black;font-weight:normal;"></span></td>';
        // $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Phone No: <span style="color:black;">'.$sale_by_account[0]['phone_no'].'</span></td>';
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
        $html .= '<th style="width:14%;color:#17365D;font-weight:bold;">Sales Date</th>';
        $html .= '<th style="width:16%;color:#17365D;font-weight:bold;">Inv No.</th>';
        $html .= '<th style="width:11%;color:#17365D;font-weight:bold;">Bill</th>';
        $html .= '<th style="width:22%;color:#17365D;font-weight:bold;">Name/Address</th>';
        $html .= '<th style="width:15%;color:#17365D;font-weight:bold;">Remarks</th>';
        $html .= '<th style="width:15%;color:#17365D;font-weight:bold;">Amount</th>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->setTableHtml($html);

        $count = 1;
        $totalAmount = 0;

        $html = '<table cellspacing="0" cellpadding="5" style="text-align:center">';

        foreach ($sale_by_account as $items) {
            // Apply alternate row color
            $backgroundColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff';
            $html .= '<tr style="background-color:' . $backgroundColor . '">';
            $html .= '<td style="width:7%;">' . $count . '</td>';
            $html .= '<td style="width:14%;">' . Carbon::createFromFormat('Y-m-d', $items['date'])->format('d-m-y') . '</td>';
            $html .= '<td style="width:16%;">' . $items['NO'] . '</td>';
            $html .= '<td style="width:11%;">' . $items['bill'] . '</td>';
            $html .= '<td style="width:22%;">' . $items['ac2'] . '</td>';
            $html .= '<td style="width:15%;">' . $items['remarks'] . '</td>';
            $html .= '<td style="width:15%;">' . $items['cr_amt'] . '</td>';
            $html .= '</tr>';

            $totalAmount += $items['cr_amt'];
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


        $filename = "sale1_report_{$accId}_from_{$fromDate}_to_{$toDate}.pdf";

        $pdf->Output($filename, 'I');
    }

    public function sale1Download(Request $request)
    {
        $sale_by_account = sale_by_account::where('ac1', $request->acc_id)
            ->whereBetween('date', [$request->fromDate, $request->toDate])
            ->leftjoin('ac','ac.ac_code','=','sale_by_account.ac1')
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
        $pdf->SetTitle('Sale Report Of Account '.$request->acc_id);
        $pdf->SetSubject('Sale Report Of Account '.$request->acc_id);
        $pdf->SetKeywords('Sale Report Of Account, TCPDF, PDF');
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

        $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Sale Report Of Account</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Account Name: <span style="color:black;">'.$sale_by_account[0]['ac_name'].'</span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> Print Date: <span style="color:black;font-weight:normal;">'.$formattedDate.'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Phone No: <span style="color:black;">'.$sale_by_account[0]['phone_no'].'</span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> From Date: <span style="color:black;font-weight:normal;">'.$formattedFromDate.'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"><span style="color:black;font-weight:normal;"></span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> To Date: <span style="color:black;font-weight:normal;">'.$formattedToDate.'</span></td>';
        $html .= '</tr>';
        $html .= '</table>';

         // Header details
         $html = '
         <table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">
             <tr>
                 <td style="font-size:12px; font-weight:bold; color:#17365D; border-bottom:1px solid #000; width:70%;">
                     Item Name: <span style="color:black;">' . $gd_pipe_sale_by_item_name[0]['item_name'] . '</span>
                 </td>
                 <td style="font-size:12px; font-weight:bold;color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                     Print Date: <span style="color:black;">' . $formattedDate . '</span>
                 </td>
             </tr>
             <tr>
                 <td style="font-size:12px; color:#17365D; border-bottom:1px solid #000;width:70%;">
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
        
        $html = '<table border="1" style="border-collapse: collapse;text-align:center" >';
        $html .= '<tr>';
        $html .= '<th style="width:7%;color:#17365D;font-weight:bold;">S/No</th>';
        $html .= '<th style="width:14%;color:#17365D;font-weight:bold;">Sales Date</th>';
        $html .= '<th style="width:16%;color:#17365D;font-weight:bold;">Inv No.</th>';
        $html .= '<th style="width:11%;color:#17365D;font-weight:bold;">Bill</th>';
        $html .= '<th style="width:22%;color:#17365D;font-weight:bold;">Name/Address</th>';
        $html .= '<th style="width:15%;color:#17365D;font-weight:bold;">Remarks</th>';
        $html .= '<th style="width:15%;color:#17365D;font-weight:bold;">Amount</th>';
        $html .= '</tr>';
        $html .= '</table>';
        

        $pdf->setTableHtml($html);

        $count = 1;
        $totalAmount = 0;

        $html = '<table cellspacing="0" cellpadding="5" style="text-align:center">';

        foreach ($sale_by_account as $items) {
            // Apply alternate row color
            $backgroundColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff';
            $html .= '<tr style="background-color:' . $backgroundColor . '">';
            $html .= '<td style="width:7%;">' . $count . '</td>';
            $html .= '<td style="width:14%;">' . Carbon::createFromFormat('Y-m-d', $items['date'])->format('d-m-y') . '</td>';
            $html .= '<td style="width:16%;">' . $items['NO'] . '</td>';
            $html .= '<td style="width:11%;">' . $items['bill'] . '</td>';
            $html .= '<td style="width:22%;">' . $items['ac2'] . '</td>';
            $html .= '<td style="width:15%;">' . $items['remarks'] . '</td>';
            $html .= '<td style="width:15%;">' . $items['cr_amt'] . '</td>';
            $html .= '</tr>';

            $totalAmount += $items['cr_amt'];
            $count++;
        }

        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->SetFont('helvetica', 'B', 12);

        // Column 3
        $pdf->SetXY(155, $currentY+5);
        $pdf->MultiCell(20, 5, 'Total', 1, 'C');

        $pdf->SetXY(175, $currentY+5);
        $pdf->MultiCell(28, 5, $totalAmount, 1, 'C');

        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');

        $filename = "sale1_report_{$accId}_from_{$fromDate}_to_{$toDate}.pdf";

        $pdf->Output($filename, 'D');
    }
}
