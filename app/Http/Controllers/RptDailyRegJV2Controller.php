<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\activites9_gen_acas;
use App\Exports\DailyRegJV2Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptDailyRegJV2Controller extends Controller
{
    public function jv2(Request $request){
        $activites9_gen_acas = activites9_gen_acas::whereBetween('jv_date', [$request->fromDate, $request->toDate])
        ->get();

        return $activites9_gen_acas;
    }

    public function jv2Excel(Request $request)
    {
        $activites9_gen_acas = activites9_gen_acas::whereBetween('jv_date', [$request->fromDate, $request->toDate])
        ->get();

        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "daily_reg_jv2_report_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new DailyRegJV2Export($activites9_gen_acas), $filename);
    }

    public function jv2Report(Request $request)
    {
        // Validate the request
        $request->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
            'outputType' => 'required|in:download,view',
        ]);
    
        // Retrieve data from the database
        $activites9_gen_acas = activites9_gen_acas::whereBetween('jv_date', [$request->fromDate, $request->toDate])
        ->get();
    
        // Check if data exists
        if ($activites9_gen_acas->isEmpty()) {
            return response()->json(['message' => 'No records found for the selected date range.'], 404);
        }
    
        // Generate the PDF
        return $this->jv2generatePDF($activites9_gen_acas, $request);
    }

    private function jv2generatePDF($activites9_gen_acas, Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-y');
        $formattedFromDate = Carbon::parse($request->fromDate)->format('d-m-y');
        $formattedToDate = Carbon::parse($request->toDate)->format('d-m-y');
    
        $pdf = new MyPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Daily Register JV 2 ' . $request->acc_id);
        $pdf->SetSubject('Daily Register JV 2');
        $pdf->SetKeywords('Daily Register JV 2, TCPDF, PDF');
        $pdf->setPageOrientation('P');
    
        // Add a page and set padding
        $pdf->AddPage();
        $pdf->setCellPadding(1.2);
    
        // Report heading
        $heading = '<h1 style="font-size:20px;text-align:center; font-style:italic;text-decoration:underline;color:#17365D">Daily Register JV 2</h1>';
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
                    <th style="width:8%;color:#17365D;font-weight:bold;">JV No</th>
                    <th style="width:11%;color:#17365D;font-weight:bold;">Date</th>
                    <th style="width:18%;color:#17365D;font-weight:bold;">Account Name</th>
                    <th style="width:12%;color:#17365D;font-weight:bold;">Debit</th>
                    <th style="width:12%;color:#17365D;font-weight:bold;">Credit</th>
                    <th style="width:15%;color:#17365D;font-weight:bold;">Remarks</th>
                    <th style="width:18%;color:#17365D;font-weight:bold;">Narration</th>
                </tr>';
    
        // Iterate through items and add rows
        $count = 1;
        $totalAmount = 0;
    
        foreach ($activites9_gen_acas as $item) {
            $backgroundColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff'; // Alternating row colors
    
            $html .= '
                <tr style="background-color:' . $backgroundColor . ';">
                    <td style="width:7%;">' . $count . '</td>
                    <td style="width:8%;">' . $item['jv_no']. '</td>
                    <td style="width:11%;">' . Carbon::parse($item['jv_date'])->format('d-m-y') . '</td>
                    <td style="width:18%;">' . $item['ac_name'] . '</td>
                    <td style="width:12%;">' . $item['debit'] . '</td>
                    <td style="width:12%;">' . $item['credit'] . '</td>
                    <td style="width:15%;">' . $item['Remark'] . '</td>
                    <td style="width:18%;">' . $item['Narration'] . '</td>
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
        $pdf->MultiCell(28, 5, $totalAmount, 1, 'C');
    
        // Prepare filename for the PDF
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');

        $filename = "daily_reg_jv2_report_from_{$fromDate}_to_{$toDate}.pdf";

        // Determine output type
        if ($request->outputType === 'download') {
            $pdf->Output($filename, 'D'); // For download
        } else {
            $pdf->Output($filename, 'I'); // For inline view
        }
    }
}
