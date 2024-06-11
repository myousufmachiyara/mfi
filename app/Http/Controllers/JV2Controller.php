<?php

namespace App\Http\Controllers;

use TCPDF;
use App\Models\AC;
use App\Models\lager;
use App\Models\lager0;
use App\Models\jv2_att;
use App\Traits\SaveImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

use App\Models\Sales;
use App\Models\Sales_2;

class JV2Controller extends Controller
{
    use SaveImage;

    public function index()
    {
        $jv2 = lager0::where('lager0.status',1)->get();
        return view('vouchers.jv2',compact('jv2'));
    }

    public function create(Request $request)
    {
        $acc = AC::where('status', 1)->get();
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
        $acc = AC::where('status', 1)->get();

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

    public function print($id)
    {
        $sales = Sales::where('Sal_inv_no',$id)
        ->join('ac','sales.account_name','=','ac.ac_code')
        ->first();

        $sale_items = Sales_2::where('sales_inv_cod',$id)
        ->join('item_entry','sales_2.item_cod','=','item_entry.it_cod')
        ->get();

        $jv2 = lager0::where('jv_no',$id)  
        ->join('lager','lager.auto_lager','=','lager0.jv_no')
        ->join('ac','ac.ac_code','=','lager.account_cod')
        ->first();

        $pdf = new TCPDF();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('JV2 # '.$jv2['jv_no']);
        $pdf->SetSubject('JV2 # '.$jv2['jv_no']);
        $pdf->SetKeywords('Journal Voucher, TCPDF, PDF');
        $pdf->setPageOrientation('L');
               
        // Set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                
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

        $heading='<h1 style="text-align:center">Journal Voucher</h1>';
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
        $html .= '<th style="width:10%;">Ac Code</th>';
        $html .= '<th style="width:30%">Account Name</th>';
        $html .= '<th style="width:30%">Remarks</th>';
        $html .= '<th style="width:15%">Debit</th>';
        $html .= '<th style="width:15%">Credit</th>';
        $html .= '</tr>';
        $html .= '</table>';
        
        // Output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        $item_table = '<table style="text-align:center">';
        $count=1;
        $total_weight=0;
        $total_quantity=0;
        $total_amount=0;
        $net_amount=0;

        foreach ($sale_items as $items) {
            if($count%2==0)
            {
                $item_table .= '<tr style="background-color:#f1f1f1">';
                $item_table .= '<td style="width:10%;">'.$count.'</td>';
                $item_table .= '<td style="width:30%;">'.$items['Sales_qty'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty'];
                $item_table .= '<td style="width:30%;">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['sales_price'].'</td>';
                $total_weight=$total_weight+$items['Sales_qty2'];
                $total_amount=$total_amount+($items['Sales_qty2']*$items['sales_price']);
                $item_table .= '</tr>';
            }
            else{
                $item_table .= '<tr>';
                $item_table .= '<td style="width:10%;">'.$count.'</td>';
                $item_table .= '<td style="width:30%;">'.$items['Sales_qty'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty'];
                $item_table .= '<td style="width:30%;">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:15%;">'.$items['sales_price'].'</td>';
                $total_weight=$total_weight+$items['Sales_qty2'];
                $total_amount=$total_amount+($items['Sales_qty2']*$items['sales_price']);
                $item_table .= '</tr>';
            }
            $count++;
        }
        $item_table .= '</table>';
        $pdf->writeHTML($item_table, true, false, true, false, '');

        $currentY = $pdf->GetY();

        // Column 3
        $pdf->SetXY(172, $currentY+10);
        $pdf->MultiCell(30, 5, 'Total', 1,1);

        // Column 3
        $pdf->SetXY(202, $currentY+10);
        $pdf->MultiCell(40, 5, '', 1,1);
     
        // Column 4
        $pdf->SetXY(242, $currentY+10);
        $pdf->MultiCell(40, 5, '', 1, 'R');
        
        // Close and output PDF
        $pdf->Output('invoice_'.$sales['Sal_inv_no'].'.pdf', 'I');
    }

    public function getAttachements(Request $request)
    {
        $jv2_atts = jv2_att::where('jv2_id', $request->id)->get();
        return $jv2_atts;
    }

}
