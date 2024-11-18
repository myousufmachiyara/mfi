<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\lager_much_op_bal;
use App\Models\lager_much_all;
use App\Exports\ACNameGLExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptAccNameGLController extends Controller
{
    public function gl(Request $request){
        $lager_much_op_bal = lager_much_op_bal::where('ac1', $request->acc_id)
        ->where('date', '<', $request->fromDate)
        ->get();

        $lager_much_all = lager_much_all::where('account_cod', $request->acc_id)
        ->whereBetween('jv_date', [$request->fromDate, $request->toDate])
        ->orderBy('jv_date','asc')
        ->get();
    
        $response = [
            'lager_much_op_bal' => $lager_much_op_bal,
            'lager_much_all' => $lager_much_all,
        ];

        return response()->json($response);
    }

    public function glr(Request $request){
        $lager_much_op_bal = lager_much_op_bal::where('ac1', $request->acc_id)
        ->where('date', '<', $request->fromDate)
        ->get();

        $lager_much_all = lager_much_all::where('account_cod', $request->acc_id)
        ->whereBetween('jv_date', [$request->fromDate, $request->toDate])
        ->orderBy('jv_date','asc')
        ->get();
    
        $response = [
            'lager_much_op_bal' => $lager_much_op_bal,
            'lager_much_all' => $lager_much_all,
        ];

        return response()->json($response);
    }

    public function glPDF(Request $request){
        $lager_much_op_bal = lager_much_op_bal::where('ac1', $request->acc_id)
        ->join('ac','ac.ac_code','=','lager_much_op_bal.ac1')
        ->where('date', '<', $request->fromDate)
        ->get();

        $lager_much_all = lager_much_all::where('account_cod', $request->acc_id)
        ->whereBetween('jv_date', [$request->fromDate, $request->toDate])
        ->orderBy('jv_date','asc')
        ->get();

        $SOD = 0;
        $SOC = 0;

        // Calculate sum of SumOfDebit and SumOfrec_cr
        foreach ($lager_much_op_bal as $record) {
            $SOD += $record->SumOfDebit ?? 0;
            $SOC += $record->SumOfrec_cr ?? 0;
        }
       
        $opening_bal = $SOD - $SOC;

        $balance = $request->opening_bal ?? 0;
        $totalDebit = 0;
        $totalCredit = 0;

        // Get and format current and report dates
        $currentDate = Carbon::now()->format('d-m-y');
        $formattedFromDate = Carbon::createFromFormat('Y-m-d', $request->fromDate)->format('d-m-y');
        $formattedToDate = Carbon::createFromFormat('Y-m-d', $request->toDate)->format('d-m-y');
  
        // Initialize PDF
        $pdf = new MyPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle("General Ledger");
        $pdf->SetSubject("General Ledger");
        $pdf->SetKeywords('General Ledger, TCPDF, PDF');
        $pdf->setPageOrientation('P');
        $pdf->AddPage();
        $pdf->setCellPadding(1.2);
  
        // Document header
        $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">General Ledger</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
  
        // Account Info Table
        $html = '
            <table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">
                <tr>
                    <td style="font-size:12px; font-weight:bold; color:#17365D; padding:5px 10px; border-bottom:1px solid #000; width:70%;">
                        Account Name: <span style="color:black;">' . htmlspecialchars($lager_much_op_bal[0]['ac_name']) . '</span>
                    </td>
                    <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; padding:5px 10px; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                        Print Date: <span style="color:black;">' . htmlspecialchars($currentDate) . '</span>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px; font-weight:bold; color:#17365D; padding:5px 10px; border-bottom:1px solid #000; width:70%;">
                    Remarks: <span style="color:black;">' . htmlspecialchars($lager_much_op_bal[0]['ac_remarks']) . '</span>
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
                        <th style="width:7%;color:#17365D;font-weight:bold;">R/No</th>
                        <th style="width:11%;color:#17365D;font-weight:bold;">Voucher</th>
                        <th style="width:11%;color:#17365D;font-weight:bold;">Date</th>
                        <th style="width:25%;color:#17365D;font-weight:bold;">Account Name</th>
                        <th style="width:13%;color:#17365D;font-weight:bold;">Debit</th>
                        <th style="width:13%;color:#17365D;font-weight:bold;">Credit</th>
                        <th style="width:13%;color:#17365D;font-weight:bold;">Balance</th>
                    </tr>
                    <tr>
                        <th colspan="7" style="text-align: right">------Opening Balance------</th>
                        <th style="text-align: left">'. $opening_bal .'</th>
                    </tr>';
                // Table Rows
            $count = 1;
            $totalAmount = 0;
            foreach ($lager_much_all as $items) {
                $bgColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff';
                if ($items->Debit && !is_nan($items->Debit)) {
                    $balance += $items->Debit;
                    $totalDebit += $items->Debit;
                }

                if ($items->Credit && !is_nan($items->Credit)) {
                    $balance -= $items->Credit;
                    $totalCredit += $items->Credit;
                }
                $html .= "<tr style='background-color:{$bgColor};'>
                                <td style='width:7%;'>{$count}</td>
                                <td style='width:7%;'>{$items['auto_lager']}</td>
                                <td style='width:11%;'>{$items['entry_of']}</td>
                                <td style='width:11%;'>" . Carbon::createFromFormat('Y-m-d', $items['jv_date'])->format('d-m-y') . "</td>
                                <td style='width:25%;'>{$items['ac2']}</td>
                                <td style='width:13%;'>" . number_format($items['Debit'], 0) . "</td>
                                <td style='width:13%;'>" . number_format($items['Credit'], 0) . "</td>
                                <td style='width:13%;'>" . number_format($balance, 0) . "</td>
                            </tr>";
                $count++;
            }
            // Add totals row
            $html .= '
            <tr style="background-color:#d9edf7; font-weight:bold;">
                <td colspan="5" style="text-align:right;">Total:</td>
                <td style="width:13%;">' . number_format($totalDebit, 0) . '</td>
                <td style="width:13%;">' . number_format($totalCredit, 0) . '</td>
                <td style="width:13%;">' . number_format($totalDebit-$totalCredit, 0) . '</td>
            </tr>';
            
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');


        // Filename and Output
        $filename = "general_ledger_of_{$lager_much_op_bal[0]['ac_name']}_from_{$formattedFromDate}_to_{$formattedToDate}.pdf";
        $pdf->Output($filename, 'I');

    }
}
