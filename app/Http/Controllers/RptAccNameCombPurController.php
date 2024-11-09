<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\both_pur_rpt_by_account;
use App\Exports\PurchaseCombExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptAccNameCombPurController extends Controller
{

    public function combinePurchase(Request $request){
        $both_pur_rpt_by_account = both_pur_rpt_by_account::where('ac1',$request->acc_id)
        ->whereBetween('DATE', [$request->fromDate, $request->toDate])
        ->orderBy('date', 'asc')
        ->get();

        return $both_pur_rpt_by_account;
    }

    public function combinePurchaseExcel(Request $request)
    {
        $both_pur_rpt_by_account = both_pur_rpt_by_account::where('ac1',$request->acc_id)
        ->whereBetween('date', [$request->fromDate, $request->toDate])
        ->get();

        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "purchase_comb_report_{$accId}_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new PurchaseCombExport($both_pur_rpt_by_account), $filename);
    }

    public function combinePurchasePDF(Request $request)
    {
        $both_pur_rpt_by_account = both_pur_rpt_by_account::where('ac1', $request->acc_id)
        ->whereBetween('date', [$request->fromDate, $request->toDate])
        ->leftjoin('ac','ac.ac_code','=','both_pur_rpt_by_account.ac1')
        ->select('both_pur_rpt_by_account.*', 'ac.ac_name', 'ac.remarks as ac_remarks') 
        ->orderBy('date', 'asc')
        ->get();

        
            // Get and format current and report dates
            $currentDate = Carbon::now()->format('d-m-y');
            $formattedFromDate = Carbon::createFromFormat('Y-m-d', $request->fromDate)->format('d-m-y');
            $formattedToDate = Carbon::createFromFormat('Y-m-d', $request->toDate)->format('d-m-y');
    
            // Initialize PDF
            $pdf = new MyPDF();
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('MFI');
            $pdf->SetTitle("Combine Purchase Report Of Account - {$both_pur_rpt_by_account[0]['ac_name']}");
            $pdf->SetSubject("Combine Purchase Report Of Account - {$both_pur_rpt_by_account[0]['ac_name']}");
            $pdf->SetKeywords('Combine Purchase Report, TCPDF, PDF');
            $pdf->setPageOrientation('P');
            $pdf->AddPage();
            $pdf->setCellPadding(1.2);
    
            // Document header
            $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Combine Purchase Report Of Account</h1>';
            $pdf->writeHTML($heading, true, false, true, false, '');
    
            // Account Info Table
            $html = '
                <table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">
                    <tr>
                        <td style="font-size:12px; font-weight:bold; color:#17365D; padding:5px 10px; border-bottom:1px solid #000; width:70%;">
                            Account Name: <span style="color:black;">' . htmlspecialchars($both_pur_rpt_by_account[0]['ac_name']) . '</span>
                        </td>
                        <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; padding:5px 10px; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                            Print Date: <span style="color:black;">' . htmlspecialchars($currentDate) . '</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px; font-weight:bold; color:#17365D; padding:5px 10px; border-bottom:1px solid #000; width:70%;">
                        Remarks: <span style="color:black;">' . htmlspecialchars($both_pur_rpt_by_account[0]['ac_remarks']) . '</span>
                        </td>
                        <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; padding:5px 10px; border-bottom:1px solid #000; border-left:1px solid #000;width:30%;">
                            From Date: <span style="color:black;">' . htmlspecialchars($formattedFromDate) . '</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px 10px; border-bottom:1px solid #000; width:70%;"></td>
                        <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; padding:5px 10px; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                            To Date: <span style="color:black;">' . htmlspecialchars($formattedToDate) . '</span>
                        </td>
                    </tr>
                </table>';
    
            $pdf->writeHTML($html, true, false, true, false, '');
    
    
            // Table Headers
            $html = '<table border="1" style="border-collapse: collapse;text-align:center">
                        <tr>
                            <th style="width:7%;color:#17365D;font-weight:bold;">S/No</th>
                            <th style="width:14%;color:#17365D;font-weight:bold;">Date</th>
                            <th style="width:10%;color:#17365D;font-weight:bold;">Entry Of</th>
                            <th style="width:12%;color:#17365D;font-weight:bold;">Inv No.</th>
                            <th style="width:41%;color:#17365D;font-weight:bold;">Detail</th>
                            <th style="width:16%;color:#17365D;font-weight:bold;">Amount</th>
                        </tr>';
                    // Table Rows
                $count = 1;
                $totalAmount = 0;
                foreach ($both_pur_rpt_by_account as $items) {
                    $bgColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff';
                    $html .= "<tr style='background-color:{$bgColor};'>
                                    <td style='width:7%;'>{$count}</td>
                                    <td style='width:14%;'>" . Carbon::createFromFormat('Y-m-d', $items['date'])->format('d-m-y') . "</td>
                                    <td style='width:10%;'>{$items['entry_of']}</td>
                                    <td style='width:12%;'>{$items['no']}</td>
                                    <td style='width:41%;'>{$items['ac2']} {$items['remarks']}</td>
                                    <td style='width:16%;'>" . number_format($items['cr_amt'], 0) . "</td>
                                </tr>";
        
                        $totalAmount += $items['cr_amt'];
                        $count++;
                        }
                // Add totals row
                $html .= '
                <tr style="background-color:#d9edf7; font-weight:bold;">
                    <td colspan="5" style="text-align:right;">Total:</td>
                    <td style="width:16%;">' . number_format($totalAmount, 0) . '</td>
                </tr>';
                
            $html .= '</table>';
            $pdf->writeHTML($html, true, false, true, false, '');

    
        
    
            // Filename and Output
        $filename = "combine_pur_report_{$both_pur_rpt_by_account[0]['ac_name']}_from_{$formattedFromDate}_to_{$formattedToDate}.pdf";
        $pdf->Output($filename, 'I');
    }

    public function combinePurchaseDownload(Request $request)
    {
        $pur_by_account = both_pur_rpt_by_account::where('ac1', $request->acc_id)
            ->whereBetween('date', [$request->fromDate, $request->toDate])
            ->leftjoin('ac','ac.ac_code','=','both_pur_rpt_by_account.ac1')
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
        $pdf->SetTitle('All Purchase Report Of Account '.$request->acc_id);
        $pdf->SetSubject('All Purchase Report Of Account '.$request->acc_id);
        $pdf->SetKeywords('All Purchase Report Of Account, TCPDF, PDF');
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

        $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">All Purchase Report Of Account</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Account Name: <span style="color:black;">'.$pur_by_account[0]['ac_name'].'</span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> Print Date: <span style="color:black;font-weight:normal;">'.$formattedDate.'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Phone No: <span style="color:black;">'.$pur_by_account[0]['phone_no'].'</span></td>';
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
        $html .= '<th style="width:10%;color:#17365D;font-weight:bold;">Inv No.</th>';
        $html .= '<th style="width:25%;color:#17365D;font-weight:bold;">Bill</th>';
        $html .= '<th style="width:22%;color:#17365D;font-weight:bold;">Details</th>';
        $html .= '<th style="width:11%;color:#17365D;font-weight:bold;">Entry of</th>';
        $html .= '<th style="width:12%;color:#17365D;font-weight:bold;">Amount</th>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->setTableHtml($html);

        $count=1;
        $totalAmount=0;

        $html .= '<table cellspacing="0" cellpadding="5" style="text-align:center">';
        foreach ($pur_by_account as $items) {
            if($count%2==0)
            {
                $html .= '<tr style="background-color:#f1f1f1">';
                $html .= '<td style="width:7%;">'.$count.'</td>';
                $html .= '<td style="width:14%;">'.Carbon::createFromFormat('Y-m-d', $items['date'])->format('d-m-y').'</td>';
                $html .= '<td style="width:10%;">'.$items['no'].'</td>';
                $html .= '<td style="width:25%;">'.$items['ac2'].'</td>';
                $html .= '<td style="width:22%;">'.$items['remarks'].'</td>';
                $html .= '<td style="width:11%;">'.$items['entry_of'].'</td>';
                $html .= '<td style="width:12%;">'.$items['cr_amt'].'</td>';
                $totalAmount=$totalAmount+$items['cr_amt'];
                $html .= '</tr>';
            }
            else{
                $html .= '<tr>';
                $html .= '<td style="width:7%;">'.$count.'</td>';
                $html .= '<td style="width:14%;">'.Carbon::createFromFormat('Y-m-d', $items['date'])->format('d-m-y').'</td>';
                $html .= '<td style="width:10%;">'.$items['no'].'</td>';
                $html .= '<td style="width:25%;">'.$items['ac2'].'</td>';
                $html .= '<td style="width:22%;">'.$items['remarks'].'</td>';
                $html .= '<td style="width:11%;">'.$items['entry_of'].'</td>';
                $html .= '<td style="width:12%;">'.$items['cr_amt'].'</td>';
                $totalAmount=$totalAmount+$items['cr_amt'];
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

        $filename = "purchase_comb_report_{$accId}_from_{$fromDate}_to_{$toDate}.pdf";

        $pdf->Output($filename, 'D');
    }

}
