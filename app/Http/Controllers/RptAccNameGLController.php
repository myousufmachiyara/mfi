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
                            <th style="width:14%;color:#17365D;font-weight:bold;">Date</th>
                            <th style="width:10%;color:#17365D;font-weight:bold;">Entry Of</th>
                            <th style="width:12%;color:#17365D;font-weight:bold;">Inv No.</th>
                            <th style="width:10%;color:#17365D;font-weight:bold;">Bill no</th>
                            <th style="width:31%;color:#17365D;font-weight:bold;">Detail</th>
                            <th style="width:16%;color:#17365D;font-weight:bold;">Amount</th>
                      </tr>';
                    // Table Rows
                $count = 1;
                $totalAmount = 0;
                foreach ($lager_much_op_bal as $items) {
                    $bgColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff';
                    $html .= "<tr style='background-color:{$bgColor};'>
                                    <td style='width:7%;'>{$count}</td>
                                    <td style='width:14%;'>" . Carbon::createFromFormat('Y-m-d', $items['date'])->format('d-m-y') . "</td>
                                    <td style='width:10%;'>{$items['Entry_of']}</td>
                                    <td style='width:12%;'>{$items['no']}</td>
                                    <td style='width:10%;'>{$items['ac2']}</td>
                                    <td style='width:31%;'>{$items['remarks']}</td>
                                    <td style='width:16%;'>" . number_format($items['dr_amt'], 0) . "</td>
                                </tr>";
        
                        $totalAmount += $items['dr_amt'];
                        $count++;
                        }
              // Add totals row
              $html .= '
                <tr style="background-color:#d9edf7; font-weight:bold;">
                    <td colspan="6" style="text-align:right;">Total:</td>
                    <td style="width:16%;">' . number_format($totalAmount, 0) . '</td>
                </tr>';
                
            $html .= '</table>';
            $pdf->writeHTML($html, true, false, true, false, '');

  
        
  
          // Filename and Output
        $filename = "combine_sale_report_{$lager_much_op_bal[0]['ac_name']}_from_{$formattedFromDate}_to_{$formattedToDate}.pdf";
        $pdf->Output($filename, 'I');

    }
}
