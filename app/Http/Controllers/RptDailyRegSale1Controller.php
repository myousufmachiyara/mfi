<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\activite5_sales;
use App\Exports\DailyRegSale1Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\myPDF;
use Carbon\Carbon;

class RptDailyRegSale1Controller extends Controller
{
    public function sale1(Request $request){
        $activite5_sales = activite5_sales::whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->join('ac','ac.ac_code','=','activite5_sales.account_name')
        ->select('activite5_sales.*','ac.ac_name as ac_name') 
        ->get();

        return $activite5_sales;
    }

    public function sale1Excel(Request $request)
    {
        $activite5_sales = activite5_sales::whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->join('ac','ac.ac_code','=','activite5_sales.account_name')
        ->select('activite5_sales.*','ac.ac_name') 
        ->get();

        $fromDate = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        
        // Construct the filename
        $filename = "daily_reg_sale1_report_from_{$fromDate}_to_{$toDate}.xlsx";

        // Return the download response with the dynamic filename
        return Excel::download(new DailyRegSale1Export($activite5_sales), $filename);
    }

    public function sale1Report(Request $request)
    {
        // Validate the request
        $request->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
            'outputType' => 'required|in:download,view',
        ]);
    
        // Retrieve data from the database
        $activite5_sales = activite5_sales::whereBetween('sa_date', [$request->fromDate, $request->toDate])
        ->join('ac','ac.ac_code','=','activite5_sales.account_name')
        ->select('activite5_sales.*','ac.ac_name as acc_name') 
        ->get();
    
        // Check if data exists
        if ($activite5_sales->isEmpty()) {
            return response()->json(['message' => 'No records found for the selected date range.'], 404);
        }
    
        // Generate the PDF
        return $this->sale1generatePDF($activite5_sales, $request);
    }

    private function sale1generatePDF($activite5_sales, Request $request)
    {
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-y');
        $formattedFromDate = Carbon::parse($request->fromDate)->format('d-m-y');
        $formattedToDate = Carbon::parse($request->toDate)->format('d-m-y');

        $pdf = new MyPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Daily Register Sale 1 ' . $request->acc_id);
        $pdf->SetSubject('Daily Register Sale 1');
        $pdf->SetKeywords('Daily Register Sale 1, TCPDF, PDF');
        $pdf->setPageOrientation('P');

        // Add a page and set padding
        $pdf->AddPage();
        $pdf->setCellPadding(1.2);

        // Report heading
        $heading = '<h1 style="font-size:20px;text-align:center; font-style:italic;text-decoration:underline;color:#17365D">Daily Register Sale 1</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');

        // Header details
        $htmlHeaderDetails = '
        <table style="border:1px solid #000; width:100%; padding:6px; border-collapse:collapse;">
            <tr>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:33%;">
                    From Date: <span style="color:black;">' . $formattedFromDate . '</span>
                </td>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left;border-left:1px solid #000; width:34%;">
                    To Date: <span style="color:black;">' . $formattedToDate . '</span>
                </td>
                <td style="font-size:12px; font-weight:bold; color:#17365D; text-align:left; border-bottom:1px solid #000;border-left:1px solid #000; width:33%;">
                    Print Date: <span style="color:black;">' . $formattedDate . '</span>
                </td>
            </tr>
        </table>';
        $pdf->writeHTML($htmlHeaderDetails, true, false, true, false, '');

        // Table headers
        $tableHeader = '<tr>
                            <th style="width:7%;color:#17365D;font-weight:bold;">S/No</th>
                            <th style="width:12%;color:#17365D;font-weight:bold;">Date</th>
                            <th style="width:13%;color:#17365D;font-weight:bold;">Inv No.</th>
                            <th style="width:12%;color:#17365D;font-weight:bold;">Ord No.</th>
                            <th style="width:19%;color:#17365D;font-weight:bold;">Account Name</th>
                            <th style="width:22%;color:#17365D;font-weight:bold;">Remarks</th>
                            <th style="width:15%;color:#17365D;font-weight:bold;">Bill Amount</th>
                        </tr>';

        // Start the table
        $html = '<table border="1" style="border-collapse: collapse;text-align:center">';
        $html .= $tableHeader;

        $count = 1;
        $totalAmount = 0;

        foreach ($activite5_sales as $items) {
            // Check if a new page is needed
            if ($pdf->getY() > 57) { // Adjust 250 based on your page margins
                $html .= '</table>'; // Close the current table
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->AddPage(); // Add a new page
                $html = '<table border="1" style="border-collapse: collapse;text-align:center">';
                $html .= $tableHeader; // Re-add table header
            }

            // Add table rows
            $bgColor = ($count % 2 == 0) ? '#f1f1f1' : '#ffffff';
            $html .= '<tr style="background-color:' . $bgColor . ';">
                        <td>' . $count . '</td>
                        <td>' . Carbon::createFromFormat('Y-m-d', $items['sa_date'])->format('d-m-y') . '</td>
                        <td>' . $items['prefix'] . '' . $items['Sal_inv_no'] . '</td>
                        <td>' . $items['pur_ord_no'] . '</td>
                        <td>' . $items['acc_name'] . '</td>
                        <td>' . $items['Cash_pur_name'] . ' ' . $items['Sales_Remarks'] . '</td>
                        <td>' . number_format($items['bill_amt'], 0) . '</td>
                    </tr>';

            $totalAmount += $items['bill_amt'];
            $count++;
        }

        // Add totals row
        $html .= '<tr style="background-color:#d9edf7; font-weight:bold;">
                    <td colspan="6" style="text-align:right;">Total:</td>
                    <td style="width:15%;">' . number_format($totalAmount, 0) . '</td>
                </tr>';
        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');

        // Prepare filename
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');
        $filename = "daily_reg_sale1_report_from_{$fromDate}_to_{$toDate}.pdf";

        // Determine output type
        if ($request->outputType === 'download') {
            $pdf->Output($filename, 'D'); // For download
        } else {
            $pdf->Output($filename, 'I'); // For inline view
        }
    }


}
