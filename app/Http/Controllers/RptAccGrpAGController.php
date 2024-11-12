<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\balance_acc_group;
use App\Exports\ACGroupAGExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptAccGrpAGController extends Controller
{
    public function ag(Request $request){
        $balance_acc_group = balance_acc_group::where('group_cod',$request->acc_id)
        ->get();

        return $balance_acc_group;
    }

    public function agExcel(Request $request)
    {
        $balance_acc_group = balance_acc_group::where('group_cod',$request->acc_id)
        ->select('ac_code', 'ac_name', 'address', 'phone_no', 'Debit', 'Credit')
        ->get();

        $accId = $request->acc_id;
        
        // Construct the filename
        $filename = "acc_group_bal_1_report{$accId}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new ACGroupAGExport($balance_acc_group), $filename);
    }

    public function agReport(Request $request)
    {
        // Validate the request
        $request->validate([
            'acc_id' => 'required',
            'outputType' => 'required|in:download,view',
        ]);
    
        // Retrieve data from the database
        $balance_acc_group = balance_acc_group::where('group_cod',$request->acc_id)
        ->get();
    
        // Check if data exists
        if ($balance_acc_group->isEmpty()) {
            return response()->json(['message' => 'No records found for the Account.'], 404);
        }
    
        // Generate the PDF
        return $this->aggeneratePDF($balance_acc_group, $request);
    }

    private function aggeneratePDF($balance_acc_group, Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-y');
    
        $pdf = new MyPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Acc Grp Balance 1 Report ' . $request->acc_id);
        $pdf->SetSubject('Acc Grp Balance 1 Report');
        $pdf->SetKeywords('Acc Grp Balance 1 Report, TCPDF, PDF');
        $pdf->setPageOrientation('P');
    
        // Add a page and set padding
        $pdf->AddPage();
        $pdf->setCellPadding(1.2);
    
        // Report heading
        $heading = '<h1 style="font-size:20px;text-align:center; font-style:italic;text-decoration:underline;color:#17365D">Account Recievables & Payables</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
    
        // Header details
        $html = '
        <table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    Group Name:
                </td>

                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    Group Name:
                </td>
            </tr>
            <tr>
                <td style="font-size:14px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    '.$balance_acc_group[0]['group_name'].'
                </td>

                <td style="font-size:14px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:30%;">
                    '.$balance_acc_group[0]['group_name'].'
                </td>
            </tr>
        </table>';

        $pdf->writeHTML($html, true, false, true, false, '');

    
        // Table header for data
        $html = '
            <table border="1" style="border-collapse: collapse; text-align: center;">
                <tr>
                    <th style="width:7%;color:#17365D;font-weight:bold;">S/No</th>
                    <th style="width:8%;color:#17365D;font-weight:bold;">Acc#</th>
                    <th style="width:24%;color:#17365D;font-weight:bold;">Account Name</th>
                    <th style="width:18%;color:#17365D;font-weight:bold;">Address</th>
                    <th style="width:15%;color:#17365D;font-weight:bold;">Phone</th>
                    <th style="width:14%;color:#17365D;font-weight:bold;">Debit</th>
                    <th style="width:14%;color:#17365D;font-weight:bold;">Credit</th>
                </tr>';
    
        // Iterate through items and add rows
        $count = 1;
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($balance_acc_group as $item) {
            $backgroundColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff'; // Alternating row colors
    
            $html .= '
                <tr style="background-color:' . $backgroundColor . ';">
                    <td style="width:7%;">' . $count . '</td>
                    <td style="width:8%;">' . $item['ac_code']. '</td>
                    <td style="width:24%;">' . $item['ac_name'] . '</td>
                    <td style="width:18%;">' . $item['address'] . '</td>
                    <td style="width:15%;">' . $item['phone'] . '</td>
                    <td style="width:14%;">' . $item['Debit'] . '</td>
                    <td style="width:14%;">' . $item['Credit'] . '</td>
                </tr>';
            
            $totalDebit += $item['Debit']; // Accumulate total quantity
            $totalCredit += $item['Credit']; // Accumulate total quantity
            $count++;
        }
    
        // Add totals row
        $html .= '
        <tr style="background-color:#d9edf7; font-weight:bold;">
            <td colspan="5" style="text-align:right;">Total:</td>
            <td style="width:14%;">' . number_format($totalDebit, 0) . '</td>
            <td style="width:14%;">' . number_format($totalCredit, 0) . '</td>
        </tr>';
            
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        
        $accId = $request->acc_id;
        $filename = "acc_group_bal_1_report{$accId}.pdf";

        // Determine output type
        if ($request->outputType === 'download') {
            $pdf->Output($filename, 'D'); // For download
        } else {
            $pdf->Output($filename, 'I'); // For inline view
        }
    }
}
