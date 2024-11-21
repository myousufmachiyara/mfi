<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\balance_all;
use App\Exports\ACGroupBAExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RptAccGrpBAController extends Controller
{

    public function ba(Request $request){
        $balance_all = balance_all::all()->groupBy('heads');

        return $balance_all;
    }

    public function baExcel(Request $request)
    {
        $balance_all = balance_all::all()->groupBy('heads');
        
        // Construct the filename
        $filename = "balance_all.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new ACGroupBAExport($balance_all), $filename);
    }

    public function baReport(Request $request)
    {
        $balance_all = balance_all::all()->groupBy('heads');
 
        // Check if data exists
        if ($balance_all->isEmpty()) {
            return response()->json(['message' => 'No records found for the Account.'], 404);
        }
    
        // Generate the PDF
        return $this->bageneratePDF($balance_all, $request);
    }

    private function bageneratePDF($balance_all, Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-y');
    
        $pdf = new MyPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Balance All Report');
        $pdf->SetSubject('Balance All Report');
        $pdf->SetKeywords('Balance All Report, TCPDF, PDF');
        $pdf->setPageOrientation('P');
    
        // Add a page and set padding
        $pdf->AddPage();
        $pdf->setCellPadding(1.2);
    
        // Report heading
        $heading = '<h1 style="font-size:20px;text-align:center; font-style:italic;text-decoration:underline;color:#17365D">Balance All</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');

        $groupedData = $this->groupByHeadAndSub($balance_all);

        // Table header for data
        // $html = '
        //     <table border="1" style="border-collapse: collapse; text-align: center;">
        //         <tr>
        //             <th style="width:7%;color:#17365D;font-weight:bold;">S/No</th>
        //             <th style="width:8%;color:#17365D;font-weight:bold;">Code</th>
        //             <th style="width:32%;color:#17365D;font-weight:bold;">Account Name</th>
        //             <th style="width:25%;color:#17365D;font-weight:bold;">Address/Phone</th>
        //             <th style="width:14%;color:#17365D;font-weight:bold;">Debit</th>
        //             <th style="width:14%;color:#17365D;font-weight:bold;">Credit</th>
        //         </tr>';
    
        // Iterate through items and add rows
        $count = 1;
        $totalDebit = 0;
        $totalCredit = 0;
        $html = '';
        foreach ($groupedData as $headCount => $heads) {
            $html .= '<table style="width:100%; border: 1px solid #000; border-collapse: collapse; margin-bottom: 10px;">
                        <thead>
                            <tr><th colspan="6" style="text-align:center;font-size:18px; border-bottom: 2px solid #000;">' . $headCount . '</th></tr>
                            <tr><th style="border: 1px solid #000;">S/No</th>
                                <th style="border: 1px solid #000;">AC</th>
                                <th style="border: 1px solid #000;">Account Name</th>
                                <th style="border: 1px solid #000;">Address</th>
                                <th style="border: 1px solid #000;">Debit</th>
                                <th style="border: 1px solid #000;">Credit</th>
                            </tr>
                        </thead>
                        <tbody>';
            $count = 1;
            foreach ($heads as $subHeadCount => $subheads) {
                // Add subhead row
                $html .= '<tr><td colspan="6" style="text-align:center;font-size:15px;font-weight:600; border: 1px solid #000;">' . $subHeadCount . '</td></tr>';
    
                foreach ($subheads as $item) {
                    // Add data row with alternating background colors
                    $backgroundColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff';
    
                    $html .= '<tr style="background-color:' . $backgroundColor . ';">
                                <td style="border: 1px solid #000; text-align:center;">' . $count . '</td>
                                <td style="border: 1px solid #000; text-align:center;">' . $item['ac_code'] . '</td>
                                <td style="border: 1px solid #000;">' . $item['ac_name'] . '</td>
                                <td style="border: 1px solid #000;">' . $item['address'] . ' ' . $item['phone'] . '</td>
                                <td style="border: 1px solid #000; text-align:right;">' . number_format($item['Debit'], 2) . '</td>
                                <td style="border: 1px solid #000; text-align:right;">' . number_format($item['Credit'], 2) . '</td>
                            </tr>';
    
                    $count++;
                }
            }
        }
        // Add totals row
        $html .= '
        <tr style="background-color:#d9edf7; font-weight:bold;">
            <td colspan="4" style="text-align:right;">Total:</td>
            <td style="width:14%;">' . number_format($totalDebit, 0) . '</td>
            <td style="width:14%;">' . number_format($totalCredit, 0) . '</td>
        </tr>';

        // Calculate balance and add balance row
        $balance = $totalDebit + $totalCredit;
        $html .= '
        <tr style="background-color:#d2edc7; font-weight:bold;">
            <td colspan="4" style="text-align:right;">Balance:</td>
            <td colspan="2" style="text-align:center;">' . number_format($balance, 0) . '</td>
        </tr>';

            
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        
        $filename = "balance_all.pdf";

        // Determine output type
        if ($request->outputType === 'download') {
            $pdf->Output($filename, 'D'); // For download
        } else {
            $pdf->Output($filename, 'I'); // For inline view
        }
    }

    private function groupByHeadAndSub($data)
    {
        $groupedData = [];

        // Loop through all available heads (keys in the data object)
        foreach ($data as $head => $items) {
            $groupedData[$head] = [];

            // Loop through each item under the current head (Assets or Liabilities, etc.)
            foreach ($items as $item) {
                $sub = $item['sub']; // Get the subhead from each item
                if (!isset($groupedData[$head][$sub])) {
                    $groupedData[$head][$sub] = []; // Initialize subhead if it doesn't exist
                }
                // Push the item into the appropriate subhead category under the head
                $groupedData[$head][$sub][] = $item;
            }
        }

        return $groupedData;
    }
}
