<?php

namespace App\Http\Controllers;

use TCPDF;
use App\Models\AC;

use App\Models\lager;
use App\Models\lager0;
use App\Models\jv2_att;
use App\Models\Sales;
use App\Models\Sales_2;
use App\Models\sales_ageing;
use App\Models\vw_union_sale_1_2_opbal;
use App\Traits\SaveImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;


class JV2Controller extends Controller
{
    use SaveImage;

    public function index()
    {
        $jv2= Lager0::where('lager0.status', 1)
        ->join('lager', 'lager0.jv_no', '=', 'lager.auto_lager')
        ->select(
        'lager0.jv_no','lager0.jv_date','lager0.narration',
        \DB::raw('SUM(lager.debit) as total_debit'),
        \DB::raw('SUM(lager.credit) as total_credit')
        )
        ->groupBy('lager0.jv_no', 'lager0.jv_date', 'lager0.narration')
        ->get();

        return view('vouchers.jv2',compact('jv2'));
    }

    public function create(Request $request)
    {
        $acc = AC::where('status', 1)->orderBy('ac_name', 'asc')->get();
        return view('vouchers.jv2-new',compact('acc'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'debit' => 'required',
            'credit' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lager0 = new lager0();

        if ($request->has('jv_date') && $request->jv_date) {
            $lager0->jv_date=$request->jv_date;
        }
        if ($request->has('narration') && $request->narration) {
            $lager0->narration=$request->narration;
        }
        
        $lager0->save();

        $latest_jv2 = lager0::latest()->first();

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {

                if(filled($request->account_cod[$i]))
                {
                    $lager = new lager();

                    $lager->auto_lager=$latest_jv2['jv_no'];
                    $lager->account_cod=$request->account_cod[$i];
                    if ($request->remarks[$i]!=null) {
                        $lager->remarks=$request->remarks[$i];
                    }
                    if ($request->bank_name[$i]!=null) {
                        $lager->bankname=$request->bank_name[$i];
                    }
                    if ($request->instrumentnumber[$i]!=null) {
                        $lager->instrumentnumber=$request->instrumentnumber[$i];
                    }
                    if ($request->chq_date[$i]!=null) {
                        $lager->chqdate=$request->chq_date[$i];
                    }
                    $lager->debit=$request->debit[$i];
                    $lager->credit=$request->credit[$i];
                    $lager->save();
                }
            }
        }

        $selectedItems = $request->input('selectedItems', []);
        $selectedItemslength = count($selectedItems);

        if($request->has('prevInvoices') && $request->prevInvoices==1)
        {
            for($j=0;$j<$request->totalInvoices;$j++)
            {
                if($request->rec_amount[$j]>0 && $request->rec_amount[$j]!==null)
                {
                    $sales_ageing = new sales_ageing();

                    $sales_ageing->jv2_id=$latest_jv2['jv_no'];
                    $sales_ageing->amount=$request->rec_amount[$j];
                    $sales_ageing->sales_id=$request->invoice_nos[$j];
                    $sales_ageing->sales_prefix=$request->prefix[$j];
                    $sales_ageing->acc_name=$request->customer_name;
                    $sales_ageing->created_by=1;
                    $sales_ageing->save();
                }
                
            }
        }
        
        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $jv2_att = new jv2_att();
                $jv2_att->jv2_id = $latest_jv2['jv_no'];
                $extension = $file->getClientOriginalExtension();
                $jv2_att->att_path = $this->jv2Doc($file,$extension);
                $jv2_att->save();
            }
        }
        return redirect()->route('all-jv2');
    }

    public function edit($id)
    {
        $jv2 = lager0::where('lager0.jv_no',$id)->first();
        $jv2_items = lager::where('lager.auto_lager',$id)->get();
        $acc = AC::where('status', 1)->orderBy('ac_name', 'asc')->get();

        return view('vouchers.jv2-edit',compact('acc','jv2','jv2_items'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'debit' => 'required',
            'credit' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lager0 = lager0::where('jv_no', $request->jv_no)->get()->first();

        if ($request->has('jv_date') && $request->jv_date) {
            $lager0->jv_date=$request->jv_date;
        }
        if ($request->has('narration') && $request->narration) {
            $lager0->narration=$request->narration;
        }

        lager0::where('jv_no', $request->jv_no)->update([
            'jv_date'=>$lager0->jv_date,
            'narration'=>$lager0->narration,
        ]);

        $lager = lager::where('auto_lager', $request->jv_no)->delete();

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {

                if(filled($request->account_cod[$i]))
                {
                    $lager = new lager();

                    $lager->auto_lager=$request->jv_no;
                    $lager->account_cod=$request->account_cod[$i];
                    if ($request->remarks[$i]!=null) {
                        $lager->remarks=$request->remarks[$i];
                    }
                    if ($request->bank_name[$i]!=null) {
                        $lager->bankname=$request->bank_name[$i];
                    }
                    if ($request->instrumentnumber[$i]!=null) {
                        $lager->instrumentnumber=$request->instrumentnumber[$i];
                    }
                    if ($request->chq_date[$i]!=null) {
                        $lager->chqdate=$request->chq_date[$i];
                    }
                    $lager->debit=$request->debit[$i];
                    $lager->credit=$request->credit[$i];
                    $lager->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $jv2_att = new jv2_att();
                $jv2_att->jv2_id = $request->jv_no;
                $extension = $file->getClientOriginalExtension();
                $jv2_att->att_path = $this->jv2Doc($file,$extension);
                $jv2_att->save();
            }
        }
        return redirect()->route('all-jv2');
    }

    public function destroy(Request $request)
    {
        $lager0 = lager0::where('jv_no', $request->delete_jv_no)->update(['status' => '0']);
        return redirect()->route('all-jv2');
    }


    public function print($id)
    {

        $jv2 = lager0::where('jv_no',$id)->first();

        $jv2_items= lager::where('auto_lager',$id)
        ->join('ac','lager.account_cod','=','ac.ac_code')
        ->select('lager.*', 'ac.ac_code as acc_code', 'ac.ac_name as acc_name')
        ->get();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('JV2 # '.$jv2['jv_no']);
        $pdf->SetSubject('JV2 # '.$jv2['jv_no']);
        $pdf->SetKeywords('Journal Voucher, TCPDF, PDF');
        $pdf->setPageOrientation('L');

        $pdf->setPrintFooter(false);

        // Set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                
        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
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
        // $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $heading='<h1 style="text-align:center">Journal Voucher 2</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');


        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td width="50%">Voucher No: <span style="text-decoration: underline;">'.$jv2['jv_no'].'</span></td>';
        $html .= '<td width="50%" style="text-align:right">Date: '.$jv2['jv_date'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td>Narration: '.$jv2['narration'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;text-align:center" >';
        $html .= '<tr>';
        $html .= '<th style="width:20%">Account Name</th>';
        $html .= '<th style="width:20%">Remarks</th>';
        $html .= '<th style="width:15%">Bank Name</th>';
        $html .= '<th style="width:15%">Inst #</th>';
        $html .= '<th style="width:15%">Debit</th>';
        $html .= '<th style="width:15%">Credit</th>';
        $html .= '</tr>';
        $html .= '</table>';
        
        // Output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        $item_table = '<table style="text-align:center">';
        $count=1;
        $total_credit=0;
        $total_debit=0;

        foreach ($jv2_items as $items) {
            if($count%2==0)
            {
                $item_table .= '<tr style="background-color:#f1f1f1">';
                $item_table .= '<td style="width:20%;">'.$items['acc_name'].'</td>';
                $item_table .= '<td style="width:20%;">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['bankname'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['instrumentnumber'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['debit'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['credit'].'</td>';
                $total_debit=$total_debit+$items['debit'];
                $total_credit=$total_credit+$items['credit'];
                $item_table .= '</tr>';
            }
            else{
                $item_table .= '<tr>';
                $item_table .= '<td style="width:20%;">'.$items['acc_name'].'</td>';
                $item_table .= '<td style="width:20%;">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['bankname'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['instrumentnumber'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['debit'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['credit'].'</td>';
                $total_debit=$total_debit+$items['debit'];
                $total_credit=$total_credit+$items['credit'];
                $item_table .= '</tr>';
            }
            $count++;
        }
        $item_table .= '</table>';
        $pdf->writeHTML($item_table, true, false, true, false, '');

        $currentY = $pdf->GetY();

        // Column 3
        $pdf->SetXY(175, $currentY+10);
        $pdf->MultiCell(28, 5, 'Total', 1,'C');

        // Column 3
        $pdf->SetXY(203, $currentY+10);
        $pdf->MultiCell(40, 5, $total_debit, 1,'C');
     
        // Column 4
        $pdf->SetXY(243, $currentY+10);
        $pdf->MultiCell(40, 5, $total_credit, 1, 'C');
        
        $currentY = $pdf->GetY();

        $style = array(
            'T' => array('width' => 0.75),  // Only top border with width 0.75
        );

        // First Cell
        $pdf->SetXY(50, $currentY+50);
        $pdf->Cell(50, 0, "Received By", $style, 1, 'C');

        // Second Cell
        $pdf->SetXY(200, $currentY+50);
        $pdf->Cell(50, 0, "Customer's Signature", $style, 1, 'C');

        // $pdf->SetY(-15);
        // $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        // Close and output PDF
        $pdf->Output('jv2_'.$jv2['jv_no'].'.pdf', 'I');
    }

    public function getAttachements(Request $request)
    {
        $jv2_atts = jv2_att::where('jv2_id', $request->id)->get();
        return $jv2_atts;
    }

    public function view($id)
    {
        $doc=jv2_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=jv2_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }

    public function deleteAtt($id)
    {
        $doc=jv2_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $jv2_att = jv2_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    // public function pendingInvoice($id){

    //     $results = Sales::where('sales.account_name', $id)
    //         ->leftJoin('rec1_able_sal', 'sales.Sal_inv_no', '=', 'rec1_able_sal.Sal_inv_no')
    //         ->leftJoin('rec1_able_rec_voch_s', 'sales.Sal_inv_no', '=', 'rec1_able_rec_voch_s.sales_id')
    //         ->select(
    //             'sales.Sal_inv_no','sales.account_name',
    //             DB::raw('rec1_able_sal.Bill_amount + sales.ConvanceCharges + sales.LaborCharges) as b_amt'),
    //             DB::raw('SUM(rec1_able_rec_voch_s.rec_amt as r_amt)'),
    //             DB::raw('((rec1_able_sal.Bill_amount + sales.ConvanceCharges + sales.LaborCharges) - (rec1_able_rec_voch_s.rec_amt)) as bill_balance'),
    //         )
    //         ->get();

    //     return $results;
    // }

    public function pendingInvoice($id){
        // Query to get the results from the view
        $results = vw_union_sale_1_2_opbal::where('account_name', $id)
            ->select('Sal_inv_no', 'b_amt', 'rec_amt', 'account_name','balance','prefix','sa_date')
            ->orderby ('Sal_inv_no', 'desc')
            ->get();
    
        return $results;
    }
}
