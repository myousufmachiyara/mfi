<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\all_payments_by_party;
use App\Exports\VouchersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptAccNameJVController extends Controller
{

    public function jv(Request $request){
        $all_payments_by_party = all_payments_by_party::where('account_cod',$request->acc_id)
        ->whereBetween('jv_date', [$request->fromDate, $request->toDate])
        ->get();

        return $all_payments_by_party;
    }

    public function jvExcel(Request $request)
    {
        $all_payments_by_party = all_payments_by_party::where('account_cod',$request->acc_id)
        ->whereBetween('jv_date', [$request->fromDate, $request->toDate])
        ->get();

        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "purchase1_report_{$accId}_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new Purchase1Export($all_payments_by_party), $filename);
    }

    public function jvPDF(Request $request)
    {
        $all_payments_by_party = all_payments_by_party::where('account_cod', $request->acc_id)
            ->whereBetween('jv_date', [$request->fromDate, $request->toDate])
            ->leftjoin('ac','ac.ac_code','=','all_payments_by_party.account_cod')
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
        $pdf->SetTitle('Journal Voucher Payments '.$request->acc_id);
        $pdf->SetSubject('Journal Voucher Payments '.$request->acc_id);
        $pdf->SetKeywords('Journal Voucher Payments, TCPDF, PDF');
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

        $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Journal Voucher Payments</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Account Name: <span style="color:black;">'.$all_payments_by_party[0]['ac_name'].'</span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> Print Date: <span style="color:black;font-weight:normal;">'.$formattedDate.'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Phone No: <span style="color:black;">'.$all_payments_by_party[0]['phone_no'].'</span></td>';
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
        $html .= '<th style="width:7%;color:#17365D;font-weight:bold;">Vouc</th>';
        $html .= '<th style="width:11%;color:#17365D;font-weight:bold;">Date</th>';
        $html .= '<th style="width:23%;color:#17365D;font-weight:bold;">Account Name</th>';
        $html .= '<th style="width:27%;color:#17365D;font-weight:bold;">Remarks</th>';
        $html .= '<th style="width:13%;color:#17365D;font-weight:bold;">Debit</th>';
        $html .= '<th style="width:13%;color:#17365D;font-weight:bold;">Credit</th>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->setTableHtml($html);

        $count=1;
        $totalDebit=0;
        $totalCredit=0;

        $html .= '<table cellspacing="0" cellpadding="5" style="text-align:center">';
        foreach ($all_payments_by_party as $items) {
            if($count%2==0)
            {
                $html .= '<tr style="background-color:#f1f1f1">';
                $html .= '<td style="width:7%;">'.$count.'</td>';
                $html .= '<td style="width:7%;">'.$items['entry_of'].'</td>';
                $html .= '<td style="width:11%;">'.Carbon::createFromFormat('Y-m-d', $items['jv_date'])->format('d-m-y').'</td>';
                $html .= '<td style="width:23%;">'.$items['ac2'].'</td>';
                $html .= '<td style="width:27%;">'.$items['Narration'].'</td>';
                $html .= '<td style="width:13%;">'.$items['Debit'].'</td>';
                $html .= '<td style="width:13%;">'.$items['Credit'].'</td>';
                $totalDebit=$totalDebit+$items['Debit'];
                $totalCredit=$totalCredit+$items['Credit'];
                $html .= '</tr>';
            }
            else{
                $html .= '<tr>';
                $html .= '<td style="width:7%;">'.$count.'</td>';
                $html .= '<td style="width:7%;">'.$items['entry_of'].'</td>';
                $html .= '<td style="width:11%;">'.Carbon::createFromFormat('Y-m-d', $items['jv_date'])->format('d-m-y').'</td>';
                $html .= '<td style="width:23%;">'.$items['ac2'].'</td>';
                $html .= '<td style="width:27%;">'.$items['Narration'].'</td>';
                $html .= '<td style="width:13%;">'.$items['Debit'].'</td>';
                $html .= '<td style="width:13%;">'.$items['Credit'].'</td>';
                $totalDebit=$totalDebit+$items['Debit'];
                $totalCredit=$totalCredit+$items['Credit'];
                $html .= '</tr>';
            }
            $count++;
        }
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');

        $currentY = $pdf->GetY();

        $pdf->SetFont('helvetica', 'B', 12);

        $pdf->SetXY(125, $currentY+5);
        $pdf->MultiCell(20, 5, 'Total', 1, 'C');

        $pdf->SetXY(145, $currentY+5);
        $pdf->MultiCell(25, 5, $totalDebit, 1, 'C');

        $pdf->SetXY(180, $currentY+5);
        $pdf->MultiCell(25, 5, $totalCredit, 1, 'C');

        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');

        $filename = "jv_report_{$accId}_from_{$fromDate}_to_{$toDate}.pdf";

        $pdf->Output($filename, 'I');
    }

    public function jvDownload(Request $request)
    {
        $all_payments_by_party = all_payments_by_party::where('account_cod', $request->acc_id)
            ->whereBetween('jv_date', [$request->fromDate, $request->toDate])
            ->leftjoin('ac','ac.ac_code','=','all_payments_by_party.account_cod')
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
        $pdf->SetTitle('Journal Voucher Payments '.$request->acc_id);
        $pdf->SetSubject('Journal Voucher Payments '.$request->acc_id);
        $pdf->SetKeywords('Journal Voucher Payments, TCPDF, PDF');
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

        $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Journal Voucher Payments</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Account Name: <span style="color:black;">'.$all_payments_by_party[0]['ac_name'].'</span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> Print Date: <span style="color:black;font-weight:normal;">'.$formattedDate.'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Phone No: <span style="color:black;">'.$all_payments_by_party[0]['phone_no'].'</span></td>';
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
        $html .= '<th style="width:8%;color:#17365D;font-weight:bold;">Voucher</th>';
        $html .= '<th style="width:14%;color:#17365D;font-weight:bold;">Date</th>';
        $html .= '<th style="width:22%;color:#17365D;font-weight:bold;">Account Name</th>';
        $html .= '<th style="width:25%;color:#17365D;font-weight:bold;">Remarks</th>';
        $html .= '<th style="width:12%;color:#17365D;font-weight:bold;">Debit</th>';
        $html .= '<th style="width:12%;color:#17365D;font-weight:bold;">Credit</th>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->setTableHtml($html);

        $count=1;
        $totalDebit=0;
        $totalCredit=0;

        $html .= '<table cellspacing="0" cellpadding="5" style="text-align:center">';
        foreach ($all_payments_by_party as $items) {
            if($count%2==0)
            {
                $html .= '<tr style="background-color:#f1f1f1">';
                $html .= '<td style="width:7%;">'.$count.'</td>';
                $html .= '<td style="width:8%;">'.$items['entry_of'].'</td>';
                $html .= '<td style="width:14%;">'.Carbon::createFromFormat('Y-m-d', $items['jv_date'])->format('d-m-y').'</td>';
                $html .= '<td style="width:22%;">'.$items['ac2'].'</td>';
                $html .= '<td style="width:25%;">'.$items['Narration'].'</td>';
                $html .= '<td style="width:12%;">'.$items['Debit'].'</td>';
                $html .= '<td style="width:12%;">'.$items['Credit'].'</td>';
                $totalDebit=$totalDebit+$items['Debit'];
                $totalCredit=$totalCredit+$items['Credit'];
                $html .= '</tr>';
            }
            else{
                $html .= '<tr>';
                $html .= '<td style="width:7%;">'.$count.'</td>';
                $html .= '<td style="width:8%;">'.$items['entry_of'].'</td>';
                $html .= '<td style="width:14%;">'.Carbon::createFromFormat('Y-m-d', $items['jv_date'])->format('d-m-y').'</td>';
                $html .= '<td style="width:22%;">'.$items['ac2'].'</td>';
                $html .= '<td style="width:25%;">'.$items['Narration'].'</td>';
                $html .= '<td style="width:12%;">'.$items['Debit'].'</td>';
                $html .= '<td style="width:12%;">'.$items['Credit'].'</td>';
                $totalDebit=$totalDebit+$items['Debit'];
                $totalCredit=$totalCredit+$items['Credit'];
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
        $pdf->MultiCell(20, 5, $totalDebit, 1, 'C');

        $pdf->SetXY(175, $currentY+5);
        $pdf->MultiCell(28, 5, $totalCredit, 1, 'C');

        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');

        $filename = "jv_report_{$accId}_from_{$fromDate}_to_{$toDate}.pdf";

        $pdf->Output($filename, 'D');
    }
}
