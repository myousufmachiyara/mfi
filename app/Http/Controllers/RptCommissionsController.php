<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\comm_pipe_rpt;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;
use Illuminate\Validation\Validator;
use App\Exports\TStockInExport;

class RptCommissionsController extends Controller
{

    public function comm(Request $request){
        $comm_pipe_rpt = comm_pipe_rpt::where('item',$request->acc_id)
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->orderBy('ac_name', 'asc')
        ->orderBy('sa_date', 'asc')
        ->get();

        return $comm_pipe_rpt;
    }
        
    public function commReport(Request $request)
    {
        // Validate the request
        $request->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
            'outputType' => 'required|in:download,view',
        ]);
    
        $comm_pipe_rpt = comm_pipe_rpt::where('item',$request->acc_id)
        ->whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->orderBy('ac_name', 'asc')
        ->orderBy('sa_date', 'asc')
        ->get();
    
        // Check if data exists
        if ($comm_pipe_rpt->isEmpty()) {
            return response()->json(['message' => 'No records found for the selected date range.'], 404);
        }
    
        // Generate the PDF
        return $this->commgeneratePDF($comm_pipe_rpt, $request);
    }

    private function commgeneratePDF($comm_pipe_rpt, Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-y');
        $formattedFromDate = Carbon::parse($request->fromDate)->format('d-m-y');
        $formattedToDate = Carbon::parse($request->toDate)->format('d-m-y');
    
        $pdf = new MyPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Commission Report Of Item ' . $request->acc_id);
        $pdf->SetSubject('Commission Report');
        $pdf->SetKeywords('Commission Report, TCPDF, PDF');
        $pdf->setPageOrientation('P');
    
        // Add a page and set padding
        $pdf->AddPage();
        $pdf->setCellPadding(1.2);
    
        // Report heading
        $heading = '<h1 style="font-size:20px;text-align:center; font-style:italic;text-decoration:underline;color:#17365D">Commission Report</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
    
        // Header details
        $html = '
        <table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    Print Date: <span style="color:black;">' . $formattedDate . '</span>
                </td>
            </tr>
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    From Date: <span style="color:black;">' . $formattedFromDate . '</span>
                </td>
            </tr>
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left;border-left:1px solid #000; width:30%;">
                    To Date: <span style="color:black;">' . $formattedToDate . '</span>
                </td>
            </tr>
        </table>';

        $pdf->writeHTML($html, true, false, true, false, '');

    
        // Table header for data
        $html = '
            <table border="1" style="border-collapse: collapse; text-align: center;">
                <tr>
                    <th style="width:7%;color:#17365D;font-weight:bold;">S/No</th>
                    <th style="width:10%;color:#17365D;font-weight:bold;">Date</th>
                    <th style="width:8%;color:#17365D;font-weight:bold;">Inv #</th>
                    <th style="width:8%;color:#17365D;font-weight:bold;">Ord #</th>
                    <th style="width:12%;color:#17365D;font-weight:bold;">B-Amount</th>
                    <th style="width:12%;color:#17365D;font-weight:bold;">GST / I-Tax</th>
                    <th style="width:10%;color:#17365D;font-weight:bold;">Comm %</th>
                    <th style="width:12%;color:#17365D;font-weight:bold;">Comm Amt</th>
                    <th style="width:10%;color:#17365D;font-weight:bold;">C.d %</th>
                    <th style="width:12%;color:#17365D;font-weight:bold;">C.d Amt</th>
                </tr>';
    
        // Iterate through items and add rows
        $count = 1;
        $totalAmount = 0;
    
        foreach ($comm_pipe_rpt as $item) {
            $backgroundColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff'; // Alternating row colors
    
            $html .= '
                <tr style="background-color:' . $backgroundColor . ';">
                    <td style="width:7%;">' . $count . '</td>
                    <td style="width:8%;">' . $item['auto_lager']. '</td>
                    <td style="width:11%;">' . Carbon::parse($item['Date'])->format('d-m-y') . '</td>
                    <td style="width:18%;">' . $item['Debit_Acc'] . '</td>
                    <td style="width:18%;">' . $item['Credit_Acc'] . '</td>
                    <td style="width:27%;">' . $item['remarks'] . '</td>
                    <td style="width:12%;">' . $item['Amount'] . '</td>
                </tr>';
            
            $totalAmount += $item['Amount']; // Accumulate total quantity
            $count++;
        }
    
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Display total amount at the bottom
        $currentY = $pdf->GetY();
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetXY(155, $currentY + 5);
        $pdf->MultiCell(20, 5, 'Total', 1, 'C');
        $pdf->SetXY(175, $currentY + 5);
        $pdf->MultiCell(20, 5, $totalAmount, 1, 'C');
    
        // // Prepare filename for the PDF
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');
        $acc_id=$request->acc_id;

        $filename = "commission_report_of_{$acc_id}_{$fromDate}_to_{$toDate}.pdf";

        // Determine output type
        if ($request->outputType === 'download') {
            $pdf->Output($filename, 'D'); // For download
        } else {
            $pdf->Output($filename, 'I'); // For inline view
        }
    }

}
