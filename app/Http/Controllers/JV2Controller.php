<?php

namespace App\Http\Controllers;


use App\Models\AC;
use App\Services\myPDF;
use App\Models\lager;
use App\Models\lager0;
use App\Models\jv2_att;
use App\Models\Sales;
use App\Models\Sales_2;
use App\Models\sales_ageing;
use App\Models\purchase_ageing;
use App\Models\vw_union_sale_1_2_opbal;
use App\Models\vw_union_pur_1_2_opbal;
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
        ->leftjoin('lager', 'lager0.jv_no', '=', 'lager.auto_lager')
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
        $lager0->created_by = session('user_id');

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

        if($request->has('prevInvoices') && $request->prevInvoices!=0)
        {
            for($j=0;$j<$request->totalInvoices;$j++)
            {
                if($request->rec_amount[$j]>0 && $request->rec_amount[$j]!==null)
                {
                    $sales_ageing = new sales_ageing();
                    $sales_ageing->created_by = session('user_id');
                    $sales_ageing->jv2_id=$latest_jv2['jv_no'];
                    $sales_ageing->created_by = session('user_id');
                    $sales_ageing->amount=$request->rec_amount[$j];
                    $sales_ageing->sales_id=$request->invoice_nos[$j];
                    $sales_ageing->sales_prefix=$request->prefix[$j];
                    $sales_ageing->acc_name=$request->customer_name;
                    $sales_ageing->save();
                }
                
            }
        }

        if($request->has('pur_prevInvoices') && $request->pur_prevInvoices==1)
        {
            for($k=0;$k<$request->pur_totalInvoices;$k++)
            {
                if($request->pur_rec_amount[$k]>0 && $request->pur_rec_amount[$k]!==null)
                {
                    $pur_ageing = new purchase_ageing();
                    $pur_ageing->created_by = session('user_id');
                    $pur_ageing->jv2_id=$latest_jv2['jv_no'];
                    $pur_ageing->amount=$request->pur_rec_amount[$k];
                    $pur_ageing->sales_id=$request->pur_invoice_nos[$k];
                    $pur_ageing->sales_prefix=$request->pur_prefix[$k];
                    $pur_ageing->acc_name=$request->pur_customer_name;
                    $pur_ageing->save();
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

        $sales_ageing = SalesAgeing::where('sales_ageing.jv2_id', $id)
        ->join('vw_union_sale_1_2_opbal', function($join) {
            $join->on('vw_union_sale_1_2_opbal.prefix', '=', 'sales_ageing.sales_prefix')
                 ->where('vw_union_sale_1_2_opbal.Sal_inv_no', '=', 'sales_ageing.sales_id');
        })
        ->select('sales_ageing.*', 'vw_union_sale_1_2_opbal.*')
        ->get();
        die(print_r($sales_ageing));
        $purchase_ageing = purchase_ageing::where('purchase_ageing.jv2_id',$id)->get();

        return view('vouchers.jv2-edit',compact('acc','jv2','jv2_items','sales_ageing','purchase_ageing'));
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
        if ($request->has('narration') && $request->narration OR empty($request->narration)) {
            $lager0->narration=$request->narration;
        }

        lager0::where('jv_no', $request->jv_no)->update([
            'jv_date'=>$lager0->jv_date,
            'narration'=>$lager0->narration,
            'updated_by' => session('user_id'),
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
                    if ($request->remarks[$i] OR empty($request->remarks[$i])) {
                        $lager->remarks=$request->remarks[$i];
                    }
                    if ($request->bank_name[$i] OR empty($request->bank_name[$i])) {
                        $lager->bankname=$request->bank_name[$i];
                    }
                    if ($request->instrumentnumber[$i] OR empty($request->instrumentnumber[$i])) {
                        $lager->instrumentnumber=$request->instrumentnumber[$i];
                    }
                    if ($request->chq_date[$i] OR empty($request->chq_date[$i])) {
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
        $lager0 = lager0::where('jv_no', $request->delete_jv_no)->update([
            'status' => '0',
            'updated_by' => session('user_id'),
        ]);
        return redirect()->route('all-jv2');
    }

    public function print($id)
    {

        $jv2 = lager0::where('jv_no',$id)->first();

        $jv2_items= lager::where('auto_lager',$id)
        ->join('ac','lager.account_cod','=','ac.ac_code')
        ->select('lager.*', 'ac.ac_code as acc_code', 'ac.ac_name as acc_name')
        ->get();

        $pdf = new MyPDF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('JV2 # '.$jv2['jv_no']);
        $pdf->SetSubject('JV2 # '.$jv2['jv_no']);
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
        // $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $heading = '<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Journal Voucher 2</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Voucher No: <span style="text-decoration: underline;color:black;">'.$jv2['jv_no'].'</span></td>';
        $html .= '<td style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins;text-align:right"> Date: <span style="color:black;font-weight:normal;">' . \Carbon\Carbon::parse($jv2['jv_date'])->format('d-m-y') . '</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="10%" style="font-size:12px;font-weight:bold;color:#17365D;font-family:poppins">Remarks:</td>';
        $html .= '<td width="78%" style="color:black;font-weight:normal;">'.$jv2['narration'].'</td>';
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
        foreach ($jv2_items as $items) {
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

        // Set text color to #17365D (RGB: 23, 54, 93)
        $pdf->SetTextColor(23, 54, 93);
        // Set font to bold
        $pdf->SetFont('helvetica', 'B', 12);

        // Column 3
        $pdf->SetXY(175, $currentY+5);
        $pdf->MultiCell(28, 5, 'Total', 1, 'C');

        // Reset text color back to default (black) for subsequent cells
        $pdf->SetTextColor(0, 0, 0);


        // Column 3
        $pdf->SetXY(203, $currentY+5);
        $pdf->MultiCell(40, 5, number_format($total_debit), 1, 'C');

        // Column 4
        $pdf->SetXY(243, $currentY+5);
        $pdf->MultiCell(40, 5, number_format($total_credit), 1, 'C');

        $style = array(
            'T' => array('width' => 0.75),  // Only top border with width 0.75
        );

        // Set text color
        $pdf->SetTextColor(23, 54, 93); // RGB values for #17365D
        // First Cell
        $pdf->SetXY(15, $currentY+5);
        $pdf->Cell(50, 0, "Accountant", $style, 1, 'C');

        // Second Cell
        $pdf->SetXY(100, $currentY+5);
        $pdf->Cell(50, 0, "Received By", $style, 1, 'C');
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

    public function pendingInvoice($id){
        // Query to get the results from the view
        $results = vw_union_sale_1_2_opbal::where('account_name', $id)
        ->select('Sal_inv_no', 'b_amt', 'rec_amt', 'account_name','balance','prefix','sa_date')
        ->orderby ('sa_date', 'asc')
        ->get();
    
        return $results;
    }

    public function purpendingInvoice($id){
        // Query to get the results from the view
        $results = vw_union_pur_1_2_opbal::where('account_name', $id)
            ->select('Sal_inv_no', 'b_amt', 'rec_amt', 'account_name','balance','prefix','sa_date')
            ->orderby ('sa_date', 'asc')
            ->get();
    
        return $results;
    }
}
