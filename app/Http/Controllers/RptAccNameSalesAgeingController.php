<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sales_days;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;
use Illuminate\Validation\Validator;

class RptAccNameSalesAgeingController extends Controller
{
    public function salesAgeing(Request $request){
        $sales_days = sales_days::where('account_name',$request->acc_id)
        ->whereBetween('bill_date', [$request->fromDate, $request->toDate])
        ->orderBy('bill_date','asc')
        ->get();

        return $sales_days;
    }

    public function salesAgeingPDF(Request $request)
    {
        $sales_days = sales_days::where('account_name',$request->acc_id)
        ->whereBetween('bill_date', [$request->fromDate, $request->toDate])
        ->leftjoin('ac', 'ac.ac_code', '=', 'sales_days.account_name')
        ->select('sales_days.*', 'ac.ac_name',  'ac.remarks')
        ->orderBy('bill_date','asc')
        ->get();
        
            // Get and format current and report dates
            $currentDate = Carbon::now()->format('d-m-y');
            $formattedFromDate = Carbon::createFromFormat('Y-m-d', $request->fromDate)->format('d-m-y');
            $formattedToDate = Carbon::createFromFormat('Y-m-d', $request->toDate)->format('d-m-y');
    
            // Initialize PDF
            $pdf = new MyPDF();
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('MFI');
            $pdf->SetTitle("Sales Ageing Report Of Account - {$sales_days[0]['ac_name']}");
            $pdf->SetSubject("Sales Ageing Report Of Account - {$sales_days[0]['ac_name']}");
            $pdf->SetKeywords('Sales Ageing Report, TCPDF, PDF');
            $pdf->setPageOrientation('L');
            $pdf->AddPage();
            $pdf->setCellPadding(1);
    
            // Document header
            $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Sales Ageing Report Of Account</h1>';
            $pdf->writeHTML($heading, true, false, true, false, '');
    
            // Account Info Table
            $html = '
                <table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">
                    <tr>
                        <td style="font-size:12px; font-weight:bold; color:#17365D; padding:5px 10px; border-bottom:1px solid #000; width:70%;">
                            Account Name: <span style="color:black;">' . htmlspecialchars($sales_days[0]['ac_name']) . '</span>
                        </td>
                        <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; padding:5px 10px; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                            Print Date: <span style="color:black;">' . htmlspecialchars($currentDate) . '</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px; font-weight:bold; color:#17365D; padding:5px 10px; border-bottom:1px solid #000; width:70%;">
                        Remarks: <span style="color:black;">' . htmlspecialchars($sales_days[0]['ac_remarks']) . '</span>
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
            $html = '<table border="1" style="border-collapse: collapse; text-align:center; width:100%;">
            <tr>
                <th style="width:4%;color:#17365D; font-weight:bold;">S/No</th>
                <th style="width:9%;color:#17365D; font-weight:bold;">Date</th>
                <th style="width:8%;color:#17365D; font-weight:bold;">Inv No.</th>
                <th style="width:19%; color:#17365D; font-weight:bold;">Detail</th>
                <th style="width:10%;color:#17365D; font-weight:bold;">Bill Amount</th>
                <th style="width:10%;color:#17365D; font-weight:bold;">UnPaid Amount</th>
                <th style="width:8%;color:#17365D; font-weight:bold;">1-20 Days</th>
                <th style="width:8%;color:#17365D; font-weight:bold;">21-35 Days</th>
                <th style="width:8%;color:#17365D; font-weight:bold;">36-50 Days</th>
                <th style="width:8%;color:#17365D; font-weight:bold;">Over 50 Days</th>
                <th style="width:8%;color:#17365D; font-weight:bold;">Cleared In Days</th>
            </tr>';
            // Table Rows
            $count = 1;
            foreach ($sales_days as $items) {
                $bgColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff';
                $status = $items['remaining_amount'] == 0 ? 'Cleared' : 'Not Cleared';
            
                // Set text color based on status
                if ($items['remaining_amount'] != 0) {
                    $pdf->SetTextColor(255, 0, 0);  // Red for "Not Cleared"
                } else {
                    $pdf->SetTextColor(0, 0, 0);   // Black for "Cleared"
                }
            
                $html .= "<tr style='background-color:{$bgColor};'>
                            <td>{$count}</td>
                            <td>" . Carbon::createFromFormat('Y-m-d', $items['bill_date'])->format('d-m-y') . "</td>
                            <td>{$items['sale_prefix']}{$items['Sal_inv_no']}</td>
                            <td>{$items['ac2']}{$items['remarks']}</td>
                            <td>" . number_format($items['bill_amount'], 0) . "</td>
                            <td>" . number_format($items['remaining_amount'], 0) . "</td>
                            <td>" . number_format($items['1_20_Days'], 0) . "</td>
                            <td>" . number_format($items['21_35_Days'], 0) . "</td>
                            <td>" . number_format($items['36_50_Days'], 0) . "</td>
                            <td>" . number_format($items['over_50_Days'], 0) . "</td>
                            <td>{$items['max_days']} - {$status}</td>
                        </tr>";
            
                $count++;
            }
            
            $html .= '</table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            

            
            // Filename and Output
        $filename = "Sales_Ageing_report_{$sales_days[0]['ac_name']}_from_{$formattedFromDate}_to_{$formattedToDate}.pdf";
        $pdf->Output($filename, 'I');
    }

    public function purchase2Download(Request $request)
    {
        $sales_days = sales_days::where('ac1', $request->acc_id)
        ->whereBetween('date', [$request->fromDate, $request->toDate])
        ->leftjoin('ac','ac.ac_code','=','sales_days.ac1')
        ->select('sales_days.*', 'ac.ac_name', 'ac.remarks as ac_remarks') 
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
            $pdf->SetTitle("Sales Ageing Report Of Account - {$sales_days[0]['ac_name']}");
            $pdf->SetSubject("Sales Ageing Report Of Account - {$sales_days[0]['ac_name']}");
            $pdf->SetKeywords('Sales Ageing Report, TCPDF, PDF');
            $pdf->setPageOrientation('P');
            $pdf->AddPage();
            $pdf->setCellPadding(1.2);
    
            // Document header
            $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Sales Ageing Report Of Account</h1>';
            $pdf->writeHTML($heading, true, false, true, false, '');
    
            // Account Info Table
            $html = '
                <table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">
                    <tr>
                        <td style="font-size:12px; font-weight:bold; color:#17365D; padding:5px 10px; border-bottom:1px solid #000; width:70%;">
                            Account Name: <span style="color:black;">' . htmlspecialchars($sales_days[0]['ac_name']) . '</span>
                        </td>
                        <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; padding:5px 10px; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                            Print Date: <span style="color:black;">' . htmlspecialchars($currentDate) . '</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px; font-weight:bold; color:#17365D; padding:5px 10px; border-bottom:1px solid #000; width:70%;">
                        Remarks: <span style="color:black;">' . htmlspecialchars($sales_days[0]['ac_remarks']) . '</span>
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
                            <th style="width:12%;color:#17365D;font-weight:bold;">Inv No.</th>
                            <th style="width:12%;color:#17365D;font-weight:bold;">Mill Inv</th>
                            <th style="width:16%;color:#17365D;font-weight:bold;">Dipatch To</th>
                            <th style="width:10%;color:#17365D;font-weight:bold;">Sale Inv</th>
                            <th style="width:13%;color:#17365D;font-weight:bold;">Remarks</th>
                            <th style="width:16%;color:#17365D;font-weight:bold;">Amount</th>
                        </tr>';
                    // Table Rows
                $count = 1;
                $totalAmount = 0;
                foreach ($sales_days as $items) {
                    $bgColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff';
                    $html .= "<tr style='background-color:{$bgColor};'>
                                    <td style='width:7%;'>{$count}</td>
                                    <td style='width:14%;'>" . Carbon::createFromFormat('Y-m-d', $items['date'])->format('d-m-y') . "</td>
                                    <td style='width:12%;'>{$items['no']}</td>
                                    <td style='width:12%;'>{$items['pur_ord_no']}</td>
                                    <td style='width:16%;'>{$items['ac2']}</td>
                                    <td style='width:10%;'>{$items['sal_inv']}</td>
                                    <td style='width:13%;'>{$items['remarks']}</td>
                                    <td style='width:16%;'>" . number_format($items['cr_amt'], 0) . "</td>
                                </tr>";
        
                        $totalAmount += $items['cr_amt'];
                        $count++;
                        }
                // Add totals row
                $html .= '
                <tr style="background-color:#d9edf7; font-weight:bold;">
                    <td colspan="7" style="text-align:right;">Total:</td>
                    <td style="width:16%;">' . number_format($totalAmount, 0) . '</td>
                </tr>';
                
            $html .= '</table>';
            $pdf->writeHTML($html, true, false, true, false, '');

    
        
    
            // Filename and Output
        $filename = "pur2_report_{$sales_days[0]['ac_name']}_from_{$formattedFromDate}_to_{$formattedToDate}.pdf";
        $pdf->Output($filename, 'D');
    }


}
