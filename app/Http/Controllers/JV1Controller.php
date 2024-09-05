<?php

namespace App\Http\Controllers;

use TCPDF;
use App\Models\AC;
use NumberFormatter;
use App\Models\jv1_att;
use App\Models\jvsingel;
use App\Traits\SaveImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class JV1Controller extends Controller
{
    use SaveImage;

    public function index()
    {
        $jv1 = jvsingel::where('jvsingel.status', 1)
                ->leftjoin('ac as d_ac', 'd_ac.ac_code', '=', 'jvsingel.ac_dr_sid')
                ->join('ac as c_ac', 'c_ac.ac_code', '=', 'jvsingel.ac_cr_sid')
                ->select('jvsingel.*', 
                'd_ac.ac_name as debit_account', 
                'c_ac.ac_name as credit_account')
                ->get();
        $acc = AC::where('status', 1)->orderBy('ac_name', 'asc')->get();

        return view('vouchers.jv1',compact('jv1','acc'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ac_dr_sid' => 'required',
            'ac_cr_sid' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jv1 = new jvsingel();

        if ($request->has('ac_dr_sid') && $request->ac_dr_sid) {
            $jv1->ac_dr_sid=$request->ac_dr_sid;
        }
        if ($request->has('ac_cr_sid') && $request->ac_cr_sid) {
            $jv1->ac_cr_sid=$request->ac_cr_sid;
        }
        if ($request->has('amount') && $request->amount OR $request->amount==0 ) {
            $jv1->amount=$request->amount;
        }
        if ($request->has('date') && $request->date) {
            $jv1->date=$request->date;
        }
        if ($request->has('remarks') && $request->remarks  OR empty($request->remarks)) {
            $jv1->remarks=$request->remarks;
        }
        $jv1->save();

        $latest_jv1 = jvsingel::latest()->first();

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $jv1_att = new jv1_att();
                $jv1_att->jv1_id = $latest_jv1['auto_lager'];
                $extension = $file->getClientOriginalExtension();
                $jv1_att->att_path = $this->jv1Doc($file,$extension);
                $jv1_att->save();
            }
        }
        return redirect()->route('all-jv1');
    }
    
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'update_ac_dr_sid' => 'required',
            'update_ac_cr_sid' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $jv1 = jvsingel::where('auto_lager', $request->update_auto_lager)->get()->first();

        if ($request->has('update_ac_dr_sid') && $request->update_ac_dr_sid) {
            $jv1->ac_dr_sid=$request->update_ac_dr_sid;
        }
        if ($request->has('update_ac_cr_sid') && $request->update_ac_cr_sid) {
            $jv1->ac_cr_sid=$request->update_ac_cr_sid;
        }
        if ($request->has('update_amount') && $request->update_amount OR $request->update_amount==0 ) {
            $jv1->amount=$request->update_amount;
        }
        if ($request->has('update_date') && $request->update_date) {
            $jv1->date=$request->update_date;
        }
        if ($request->has('update_remarks') && $request->update_remarks) {
            $jv1->remarks=$request->update_remarks;
        }
    
        jvsingel::where('auto_lager', $request->update_auto_lager)->update([
            'ac_dr_sid'=>$jv1->ac_dr_sid,
            'ac_cr_sid'=>$jv1->ac_cr_sid,
            'amount'=>$jv1->amount,
            'date'=>$jv1->date,
            'remarks'=>$jv1->remarks,
        ]);

        if($request->hasFile('update_att')){
            
            // jv1_att::where('jv1_id', $request->update_auto_lager)->delete();
            $files = $request->file('update_att');
            foreach ($files as $file)
            {
                $jv1_att = new jv1_att();
                $jv1_att->jv1_id =  $request->update_auto_lager;
                $extension = $file->getClientOriginalExtension();
                $jv1_att->att_path = $this->jv1Doc($file,$extension);
                $jv1_att->save();
            }
        }

        return redirect()->route('all-jv1');
    }

    public function destroy(Request $request)
    {
        $jv1 = jvsingel::where('auto_lager', $request->delete_auto_lager)->update(['status' => '0']);
        return redirect()->route('all-jv1');
    }

    public function getAttachements(Request $request)
    {
        $jv1_atts = jv1_att::where('jv1_id', $request->id)->get();
        return $jv1_atts;
    }

    public function getJVDetails(Request $request)
    {
        $jv1_details = jvsingel::where('auto_lager', $request->id)->get()->first();
        return $jv1_details;
    }

    public function view($id)
    {
        $doc=jv1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=jv1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }

    public function deleteAtt($id)
    {
        $doc=jv1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);

        if (File::exists($filePath)) {
            File::delete($filePath);
            $jv1_att = jv1_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }
    }

    public function print($id)
    {

        $jv1 = jvsingel::where('jvsingel.auto_lager', $id)
        ->join('ac as d_ac', 'd_ac.ac_code', '=', 'jvsingel.ac_dr_sid')
        ->join('ac as c_ac', 'c_ac.ac_code', '=', 'jvsingel.ac_cr_sid')
        ->select('jvsingel.*', 
        'd_ac.ac_name as debit_account', 
        'c_ac.ac_name as credit_account')
        ->first();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('JV1 # '.$jv1['auto_lager']);
        $pdf->SetSubject('JV1 # '.$jv1['auto_lager']);
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

        $heading='<h1 style="text-align:center">Journal Voucher 1</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');


        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td width="50%">Voucher No: <span style="text-decoration: underline;">'.$jv1['auto_lager'].'</span></td>';
        $html .= '<td width="50%" style="text-align:right">Date: '.$jv1['date'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td>Remarks: '.$jv1['remarks'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;text-align:center" >';
        $html .= '<tr>';
        $html .= '<th style="width:40%;">Account Debit</th>';
        $html .= '<th style="width:40%">Account Credit</th>';
        $html .= '<th style="width:20%">Amount</th>';
        $html .= '</tr>';
        $html .= '</table>';
        
        // Output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        $count=1;
        $total_credit=0;
        $total_debit=0;

        $item_table =  '<table style="text-align:center">';
        $item_table .= '<tr style="background-color:#f1f1f1">';
        $item_table .= '<td style="width:40%;">'.$jv1['debit_account'].'</td>';
        $item_table .= '<td style="width:40%;">'.$jv1['credit_account'].'</td>';
        $item_table .= '<td style="width:20%;">'.$jv1['amount'].'</td>';
        $item_table .= '</tr>';
        
        $item_table .= '</table>';
        $pdf->writeHTML($item_table, true, false, true, false, '');

        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        // Column 3
        $number = floor($jv1['amount']); // Remove decimals (round down)
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $numberText=$f->format($number);
        $formattedWords = ucwords(strtolower($numberText));
        $words='<h1 style="text-decoration:underline;font-style:italic">'.$formattedWords.' Only</h1>';
        $pdf->writeHTML($words, true, false, true, false, '');


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

        $pdf->Output('jv1_'.$jv1['auto_lager'].'.pdf', 'I');

    }

}
