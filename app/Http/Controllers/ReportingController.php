<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\pur_by_account;
use App\Models\pipe_pur_by_account;
use App\Exports\Purchase1Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class ReportingController extends Controller
{
    // By Account Name
    public function byAccountName()
    {
        $coa = AC::orderBy('ac_name', 'asc')->get();
        return view('reports.acc_name',compact('coa'));
    }

    public function purchase1(Request $request){
        $pur_by_account = pur_by_account::where('ac1',$request->acc_id)
        ->whereBetween('DATE', [$request->fromDate, $request->toDate])
        ->get();

        return $pur_by_account;
    }

    public function purchase1Excel(Request $request)
    {
        $pur_by_account = pur_by_account::where('ac1', $request->acc_id)
            ->whereBetween('DATE', [$request->fromDate, $request->toDate])
            ->get();

        $accId = $request->acc_id;
        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "purchase1_report_{$accId}_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new Purchase1Export($pur_by_account), $filename);
    }

    public function purchase1PDF(Request $request)
    {
        $pur_by_account = pur_by_account::where('ac1', $request->acc_id)
            ->whereBetween('DATE', [$request->fromDate, $request->toDate])
            ->get();

        $currentDate = Carbon::now();

        // Format the date if needed
        $formattedDate = $currentDate->format('d-m-y');
        $formattedFromDate = $request->fromDate;
        $formattedToDate = $request->toDate;

        $pdf = new MyPDF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Purchase Report Of Account '.$request->acc_id);
        $pdf->SetSubject('Purchase Report Of Account '.$request->acc_id);
        $pdf->SetKeywords('Purchase Report Of Account, TCPDF, PDF');
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

        $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Purchase Report Of Account</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Account Name: <span style="text-decoration: underline;color:black;"></span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> Print Date: <span style="color:black;font-weight:normal;">'.$formattedDate.'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Phone No: <span style="text-decoration: underline;color:black;"></span></td>';
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
        $html .= '<th style="width:10%;color:#17365D;font-weight:bold;">Mill No.</th>';
        $html .= '<th style="width:22%;color:#17365D;font-weight:bold;">Dispatch To Party</th>';
        $html .= '<th style="width:11%;color:#17365D;font-weight:bold;">Sale Inv</th>';
        $html .= '<th style="width:15%;color:#17365D;font-weight:bold;">Remarks</th>';
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
                $html .= '<td style="width:14%;">'.$items['DATE'].'</td>';
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
                $html .= '<td style="width:14%;">'.$items['DATE'].'</td>';
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

        if(($pdf->getPageHeight()-$pdf->GetY())<50){
            $pdf->AddPage();
            $currentY = $pdf->GetY();
        }

        $pdf->Output('jv2.pdf', 'I');
    }

    public function purchase2(Request $request){
        $pipe_pur_by_account = pipe_pur_by_account::where('ac1',$request->acc_id)
        ->whereBetween('date', [$request->fromDate, $request->toDate])
        ->get();

        return $pipe_pur_by_account;
    }
}
