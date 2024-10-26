<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\pur_by_account;
use App\Models\pipe_pur_by_account;
use App\Exports\Purchase1Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;

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

        $pdf = new MyPDF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('JV2 # '.$pur_by_account['jv_no']);
        $pdf->SetSubject('JV2 # '.$pur_by_account['jv_no']);
        $pdf->SetKeywords('Journal Voucher, TCPDF, PDF');
        $pdf->setPageOrientation('L');

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

        $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Journal Voucher 2</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Voucher No: <span style="text-decoration: underline;color:black;">'.$pur_by_account['jv_no'].'</span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> Date: <span style="color:black;font-weight:normal;">' . \Carbon\Carbon::parse($pur_by_account['jv_date'])->format('d-m-y') . '</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="10%" style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Remarks:</td>';
        $html .= '<td width="78%" style="color:black;font-weight:normal;">'.$pur_by_account['narration'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;text-align:center" >';
        $html .= '<tr>';
        $html .= '<th style="width:20%;color:#17365D;font-weight:bold;">Account Name</th>';
        $html .= '<th style="width:20%;color:#17365D;font-weight:bold;">Remarks</th>';
        $html .= '<th style="width:15%;color:#17365D;font-weight:bold;">Bank Name</th>';
        $html .= '<th style="width:15%;color:#17365D;font-weight:bold;">Inst #</th>';
        $html .= '<th style="width:15%;color:#17365D;font-weight:bold;">Debit</th>';
        $html .= '<th style="width:15%;color:#17365D;font-weight:bold;">Credit</th>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->setTableHtml($html);

        $count=1;
        $total_credit=0;
        $total_debit=0;

        $html .= '<table cellspacing="0" cellpadding="5" style="text-align:center">';
        foreach ($pur_by_account as $items) {
            if($count%2==0)
            {
                $html .= '<tr style="background-color:#f1f1f1">';
                $html .= '<td style="width:20%;">'.$items['acc_name'].'</td>';
                $html .= '<td style="width:20%;">'.$items['remarks'].'</td>';
                $html .= '<td style="width:15%;">'.$items['bankname'].'</td>';
                $html .= '<td style="width:15%;">'.$items['instrumentnumber'].'</td>';
                $html .= '<td style="width:15%;">'.$items['debit'].'</td>';
                $html .= '<td style="width:15%;">'.$items['credit'].'</td>';
                $total_debit=$total_debit+$items['debit'];
                $total_credit=$total_credit+$items['credit'];
                $html .= '</tr>';
            }
            else{
                $html .= '<tr>';
                $html .= '<td style="width:20%;">'.$items['acc_name'].'</td>';
                $html .= '<td style="width:20%;">'.$items['remarks'].'</td>';
                $html .= '<td style="width:15%;">'.$items['bankname'].'</td>';
                $html .= '<td style="width:15%;">'.$items['instrumentnumber'].'</td>';
                $html .= '<td style="width:15%;">'.$items['debit'].'</td>';
                $html .= '<td style="width:15%;">'.$items['credit'].'</td>';
                $total_debit=$total_debit+$items['debit'];
                $total_credit=$total_credit+$items['credit'];
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

        $pdf->Output('jv2_'.$pur_by_account['jv_no'].'.pdf', 'I');
    }

    public function purchase2(Request $request){
        $pipe_pur_by_account = pipe_pur_by_account::where('ac1',$request->acc_id)
        ->whereBetween('date', [$request->fromDate, $request->toDate])
        ->get();

        return $pipe_pur_by_account;
    }
}
