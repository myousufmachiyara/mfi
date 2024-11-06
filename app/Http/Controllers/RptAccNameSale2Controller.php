<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\pipe_sale_by_account;
use App\Exports\Sale2Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptAccNameSale2Controller extends Controller
{
    public function sale2(Request $request){
        $pipe_sale_by_account = pipe_sale_by_account::where('ac1',$request->acc_id)
        ->leftjoin('ac', 'pipe_sale_by_account.company_name', '=', 'ac.ac_code')
        ->whereBetween('date', [$request->fromDate, $request->toDate])
        ->select('pipe_sale_by_account.*', 'ac.ac_name')
        ->orderBy('date','asc')
        ->get();

        return $pipe_sale_by_account;
    }

    public function sale2Excel(Request $request)
    {
        $pipe_sale_by_account = pipe_sale_by_account::where('ac1', $request->acc_id)
            ->whereBetween('date', [$request->fromDate, $request->toDate])
            ->get();

        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "sale2_report_{$accId}_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new Sale2Export($pipe_sale_by_account), $filename);
    }

    public function sale2PDF(Request $request)
    {
        $pipe_sale_by_account = pipe_sale_by_account::where('ac1', $request->acc_id)
        ->whereBetween('date', [$request->fromDate, $request->toDate])
        ->leftjoin('ac as ac1', 'ac1.ac_code', '=', 'pipe_sale_by_account.ac1')  // Alias ac table as ac1
        ->leftjoin('ac as ac2', 'pipe_sale_by_account.company_name', '=', 'ac2.ac_code')  // Alias ac table as ac2
        ->select('pipe_sale_by_account.*', 'ac1.ac_name as ac1_name', 'ac1.remarks', 'ac2.ac_name as ac2_name') 
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
          $pdf->SetTitle("Sale Report Of Account - {$pipe_sale_by_account[0]['ac1_name']}");
          $pdf->SetSubject("Sale Report Of Account - {$pipe_sale_by_account[0]['ac1_name']}");
          $pdf->SetKeywords('Sale Report, TCPDF, PDF');
          $pdf->setPageOrientation('P');
          $pdf->AddPage();
          $pdf->setCellPadding(1.2);
  
          // Document header
          $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Sale Report Of Account</h1>';
          $pdf->writeHTML($heading, true, false, true, false, '');
  
          // Account Info Table
          $html = '
              <table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">
                  <tr>
                      <td style="font-size:12px; font-weight:bold; color:#17365D; padding:5px 10px; border-bottom:1px solid #000; width:70%;">
                          Account Name: <span style="color:black;">' . htmlspecialchars($pipe_sale_by_account[0]['ac1_name']) . '</span>
                      </td>
                      <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; padding:5px 10px; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                          Print Date: <span style="color:black;">' . htmlspecialchars($currentDate) . '</span>
                      </td>
                  </tr>
                  <tr>
                      <td style="font-size:12px; font-weight:bold; color:#17365D; padding:5px 10px; border-bottom:1px solid #000; width:70%;">
                      Remarks: <span style="color:black;">' . htmlspecialchars($pipe_sale_by_account[0]['remarks']) . '</span>
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
                            <th style="width:14%;color:#17365D;font-weight:bold;">Sales Date</th>
                            <th style="width:10%;color:#17365D;font-weight:bold;">Inv No.</th>
                            <th style="width:10%;color:#17365D;font-weight:bold;">Bill</th>
                            <th style="width:22%;color:#17365D;font-weight:bold;">Company Name</th>
                            <th style="width:11%;color:#17365D;font-weight:bold;">Pur Inv</th>
                            <th style="width:15%;color:#17365D;font-weight:bold;">Remarks</th>
                            <th style="width:12%;color:#17365D;font-weight:bold;">Amount</th>
                      </tr>';
                    // Table Rows
                $count = 1;
                $totalAmount = 0;
                foreach ($pipe_sale_by_account as $items) {
                    $bgColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff';
                    $html .= "<tr style='background-color:{$bgColor};'>
                                    <td style='width:7%;'>{$count}</td>
                                    <td style='width:14%;'>" . Carbon::createFromFormat('Y-m-d', $items['date'])->format('d-m-y') . "</td>
                                    <td style='width:10%;'>{$items['NO']}</td>
                                    <td style='width:10%;'>{$items['pur_bill_no']}</td>
                                    <td style='width:22%;'>{$items['ac2_name']}</td>
                                    <td style='width:11%;'>{$items['sal_inv']}</td>
                                    <td style='width:15%;'>{$items['remarks']}</td>
                                    <td style='width:12%;'>{$items['cr_amt']}</td>
                                </tr>";
        
                        $totalAmount += $items['cr_amt'];
                        $count++;
                        }
              // Add totals row
              $html .= '
              <tr style="background-color:#d9edf7; font-weight:bold;">
                  <td colspan="7" style="text-align:right;">Total:</td>
                  <td style="width:12%;">' . $totalAmount . '</td>
              </tr>';
              $html .= '</table>';
             $pdf->writeHTML($html, true, false, true, false, '');
  
        
  
          // Filename and Output
          $filename = "sale1_report_{$pipe_sale_by_account[0]['ac1_name']}_from_{$formattedFromDate}_to_{$formattedToDate}.pdf";
          $pdf->Output($filename, 'I');
    }
  

    public function sale2Download(Request $request)
    {
        $pipe_sale_by_account = pipe_sale_by_account::where('ac1', $request->acc_id)
            ->whereBetween('date', [$request->fromDate, $request->toDate])
            ->leftjoin('ac','ac.ac_code','=','pipe_sale_by_account.ac1')
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
        $pdf->SetTitle('Sale Pipe Report Of Account '.$request->acc_id);
        $pdf->SetSubject('Sale Pipe Report Of Account '.$request->acc_id);
        $pdf->SetKeywords('Sale Pipe Report Of Account, TCPDF, PDF');
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

        $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Sale Pipe Report Of Account</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Account Name: <span style="color:black;">'.$pipe_sale_by_account[0]['ac_name'].'</span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> Print Date: <span style="color:black;font-weight:normal;">'.$formattedDate.'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Phone No: <span style="color:black;">'.$pipe_sale_by_account[0]['phone_no'].'</span></td>';
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
        $html .= '<th style="width:10%;color:#17365D;font-weight:bold;">Bill</th>';
        $html .= '<th style="width:22%;color:#17365D;font-weight:bold;">Company Name</th>';
        $html .= '<th style="width:11%;color:#17365D;font-weight:bold;">Pur Inv</th>';
        $html .= '<th style="width:15%;color:#17365D;font-weight:bold;">Remarks</th>';
        $html .= '<th style="width:12%;color:#17365D;font-weight:bold;">Amount</th>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->setTableHtml($html);

        $count=1;
        $totalAmount=0;

        $html .= '<table cellspacing="0" cellpadding="5" style="text-align:center">';
        foreach ($pipe_sale_by_account as $items) {
            if($count%2==0)
            {
                $html .= '<tr style="background-color:#f1f1f1">';
                $html .= '<td style="width:7%;">'.$count.'</td>';
                $html .= '<td style="width:14%;">'.Carbon::createFromFormat('Y-m-d', $items['date'])->format('d-m-y').'</td>';
                $html .= '<td style="width:10%;">'.$items['NO'].'</td>';
                $html .= '<td style="width:10%;">'.$items['pur_bill_no'].'</td>';
                $html .= '<td style="width:22%;">'.$items['ac2'].'</td>';
                $html .= '<td style="width:11%;">'.$items['sal_inv'].'</td>';
                $html .= '<td style="width:15%;">'.$items['remarks'].'</td>';
                $html .= '<td style="width:12%;">'.$items['cr_amt'].'</td>';
                $totalAmount=$totalAmount+$items['cr_amt'];
                $html .= '</tr>';
            }
            else{
                $html .= '<tr>';
                $html .= '<td style="width:7%;">'.$count.'</td>';
                $html .= '<td style="width:14%;">'.Carbon::createFromFormat('Y-m-d', $items['date'])->format('d-m-y').'</td>';
                $html .= '<td style="width:10%;">'.$items['NO'].'</td>';
                $html .= '<td style="width:10%;">'.$items['pur_bill_no'].'</td>';
                $html .= '<td style="width:22%;">'.$items['ac2'].'</td>';
                $html .= '<td style="width:11%;">'.$items['sal_inv'].'</td>';
                $html .= '<td style="width:15%;">'.$items['remarks'].'</td>';
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

        $filename = "sale2_report_{$accId}_from_{$fromDate}_to_{$toDate}.pdf";

        $pdf->Output($filename, 'D');
    }
}
