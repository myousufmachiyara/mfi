<?php
namespace App\Http\Controllers;
use TCPDF;

use Illuminate\Http\Request;
use App\Models\Item_entry;
use App\Models\AC;
use App\Models\purchase;
use App\Models\sales;
use App\Models\sales_2;
use App\Models\purchase_2;
use App\Models\pur1_att;
use Illuminate\Support\Facades\File;
use App\Traits\SaveImage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    //
    use SaveImage;

    public function index()
    {
        $pur1 = purchase::where('purchase.status',1)
        ->join('ac', 'ac.ac_code', '=', 'purchase.ac_cod')
        ->get();
        
        return view('purchase1.index',compact('pur1'));
    }

    public function create(Request $request)
    {
        $items = Item_entry::all();
        $coa = AC::all();
        return view('purchase1.create',compact('items','coa'));
    }

    public function store(Request $request)
    {
        $pur1 = new purchase();

        if ($request->has('pur_date') && $request->pur_date) {
            $pur1->pur_date=$request->pur_date;
        }
        if ($request->has('pur_bill_no') && $request->pur_bill_no) {
            $pur1->pur_bill_no=$request->pur_bill_no;
        }
        if ($request->has('pur_sale_inv') && $request->pur_sale_inv) {
            $pur1->sale_against=$request->pur_sale_inv;
        }
        if ($request->has('ac_cod') && $request->ac_cod) {
            $pur1->ac_cod=$request->ac_cod;
        }
        if ($request->has('cash_saler_name') && $request->cash_saler_name) {
            $pur1->cash_saler_name=$request->cash_saler_name;
        }
        if ($request->has('cash_saler_address') && $request->cash_saler_address) {
            $pur1->cash_saler_address=$request->cash_saler_address;
        }
        if ($request->has('pur_remarks') && $request->pur_remarks) {
            $pur1->pur_remarks=$request->pur_remarks;
        }
        if ($request->has('pur_convance_char') && $request->pur_convance_char) {
            $pur1->pur_convance_char=$request->pur_convance_char;
        }
        if ($request->has('pur_labor_char') && $request->pur_labor_char) {
            $pur1->pur_labor_char=$request->pur_labor_char;
        }
        if ($request->has('bill_discount') && $request->bill_discount) {
            $pur1->pur_discount=$request->bill_discount;
        }
        if ($request->has('total_weight') && $request->total_weight) {
            $pur1->total_weight=$request->total_weight;
        }
        if ($request->has('total_quantity') && $request->total_quantity) {
            $pur1->total_quantity=$request->total_quantity;
        }
        if ($request->has('total_amount') && $request->total_amount) {
            $pur1->bill_amount=$request->total_amount;
        }
        if ($request->has('net_amount') && $request->net_amount) {
            $pur1->net_amount=$request->net_amount;
        }

        $pur1->save();

        $pur_1_id = purchase::latest()->first();

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {

                if(filled($request->item_name[$i]))
                {
                    $purchase_2 = new purchase_2();

                    $purchase_2->pur_cod=$pur_1_id['pur_id'];
                    $purchase_2->item_cod=$request->item_cod[$i];
                    if ($request->remarks[$i]!=null) {
                        $purchase_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur_qty[$i]!=null) {
                        $purchase_2->pur_qty=$request->pur_qty[$i];
                    }
                    if ($request->pur_price[$i]!=null) {
                        $purchase_2->pur_price=$request->pur_price[$i];
                    }
                    if ($request->pur_qty2[$i]!=null) {
                        $purchase_2->pur_qty2=$request->pur_qty2[$i];
                    }
                    $purchase_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $purAtt = new pur1_att();
                $purAtt->pur1_id = $pur_1_id['pur_id'];
                $extension = $file->getClientOriginalExtension();
                $purAtt->att_path = $this->pur1Doc($file,$extension);
                $purAtt->save();
            }
        }

        return redirect()->route('all-purchases1');
    }

    public function edit($id)
    {
        $items = Item_entry::all();
        $acc = AC::all();
        $pur = purchase::where('purchase.pur_id',$id)->first();
        $pur_items = purchase_2::where('purchase_2.pur_cod',$id)->get();

        return view('purchase1.edit',compact('items','acc','pur','pur_items'));
    }

    public function update(Request $request){

        $pur1 = purchase::where('pur_id',$request->pur_id)->get()->first();

        if ($request->has('pur_date') && $request->pur_date) {
            $pur1->pur_date=$request->pur_date;
        }
        if ($request->has('pur_bill_no') && $request->pur_bill_no) {
            $pur1->pur_bill_no=$request->pur_bill_no;
        }
        if ($request->has('pur_sale_inv') && $request->pur_sale_inv) {
            $pur1->sale_against=$request->pur_sale_inv;
        }
        if ($request->has('ac_cod') && $request->ac_cod) {
            $pur1->ac_cod=$request->ac_cod;
        }
        if ($request->has('cash_saler_name') && $request->cash_saler_name) {
            $pur1->cash_saler_name=$request->cash_saler_name;
        }
        if ($request->has('cash_saler_address') && $request->cash_saler_address) {
            $pur1->cash_saler_address=$request->cash_saler_address;
        }
        if ($request->has('pur_remarks') && $request->pur_remarks) {
            $pur1->pur_remarks=$request->pur_remarks;
        }
        if ($request->has('pur_convance_char') && $request->pur_convance_char) {
            $pur1->pur_convance_char=$request->pur_convance_char;
        }
        if ($request->has('pur_labor_char') && $request->pur_labor_char) {
            $pur1->pur_labor_char=$request->pur_labor_char;
        }
        if ($request->has('bill_discount') && $request->bill_discount) {
            $pur1->pur_discount=$request->bill_discount;
        }
        if ($request->has('total_weight') && $request->total_weight) {
            $pur1->total_weight=$request->total_weight;
        }
        if ($request->has('total_quantity') && $request->total_quantity) {
            $pur1->total_quantity=$request->total_quantity;
        }
        if ($request->has('total_amount') && $request->total_amount) {
            $pur1->bill_amount=$request->total_amount;
        }
        if ($request->has('net_amount') && $request->net_amount) {
            $pur1->net_amount=$request->net_amount;
        }

        purchase::where('pur_id', $request->pur_id)->update([
            'pur_date'=>$pur1->pur_date,
            'pur_bill_no'=>$pur1->pur_bill_no,
            'sale_against'=>$pur1->pur_sale_inv,
            'ac_cod'=>$pur1->ac_cod,
            'cash_saler_name'=>$pur1->cash_saler_name,
            'cash_saler_address'=>$pur1->cash_saler_address,
            'pur_remarks'=>$pur1->pur_remarks,
            'pur_convance_char'=>$pur1->pur_convance_char,
            'pur_labor_char'=>$pur1->pur_labor_char,
            'pur_discount'=>$pur1->pur_discount,
            'total_weight'=>$pur1->total_weight,
            'total_quantity'=>$pur1->total_quantity,
            'bill_amount'=>$pur1->total_amount,
            'net_amount'=>$pur1->net_amount
        ]);

        purchase_2::where('pur_cod', $request->pur_id)->delete();

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {

                if(filled($request->item_name[$i]))
                {
                    $purchase_2 = new purchase_2();

                    $purchase_2->pur_cod=$request->pur_id;
                    $purchase_2->item_cod=$request->item_cod[$i];
                    if ($request->remarks[$i]!=null) {
                        $purchase_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur_qty[$i]!=null) {
                        $purchase_2->pur_qty=$request->pur_qty[$i];
                    }
                    if ($request->pur_price[$i]!=null) {
                        $purchase_2->pur_price=$request->pur_price[$i];
                    }
                    if ($request->pur_qty2[$i]!=null) {
                        $purchase_2->pur_qty2=$request->pur_qty2[$i];
                    }
                    $purchase_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $purAtt = new pur1_att();
                $purAtt->pur1_id = $pur_1_id['pur_id'];
                $extension = $file->getClientOriginalExtension();
                $purAtt->att_path = $this->pur1Doc($file,$extension);
                $purAtt->save();
            }
        }

        return redirect()->route('all-purchases1');
    }

    public function destroy(Request $request)
    {
        $purc1 = purchase::where('pur_id', $request->delete_purc1)->update(['status' => '0']);
        return redirect()->route('all-purchases1');
    }

    public function show(string $id)
    {
        $pur = purchase::where('pur_id',$id)
                ->join('ac','purchase.ac_cod','=','ac.ac_code')
                ->first();

        $pur2 = purchase_2::where('pur_cod',$id)
                ->join('item_entry','purchase_2.item_cod','=','item_entry.it_cod')
                ->get();

        return view('purchase1.view',compact('pur','pur2'));
    }

    public function getAttachements(Request $request)
    {
        $pur1_atts = pur1_att::where('pur1_id', $request->id)->get();
        
        return $pur1_atts;
    }

    public function deleteAtt($id)
    {
        $doc=pur1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $pur1_att = pur1_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=pur1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=pur1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }

    public function generatePDF($id)
    {
        $purchase = purchase::where('pur_id',$id)
        ->join('ac','purchase.ac_cod','=','ac.ac_code')
        ->first();

        $purchase_items = purchase_2::where('pur_cod',$id)
                ->join('item_entry','purchase_2.item_cod','=','item_entry.it_cod')
                ->get();

        $pdf = new TCPDF();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Invoice-'.$purchase['pur_id']);
        $pdf->SetSubject('Invoice-'.$purchase['pur_id']);
        $pdf->SetKeywords('Invoice, TCPDF, PDF');
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

        $heading='<h1 style="text-align:center">Purchase Invoice</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');


        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td>Invoice No: <span style="text-decoration: underline;">'.$purchase['pur_id'].'</span></td>';
        $html .= '<td>Date: '.$purchase['pur_date'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;">';
        $html .= '<tr>';
        $html .= '<td width="20%" style="border-right:1px dashed #000">Account Name </td>';
        $html .= '<td width="30%">'.$purchase['ac_name'].'</td>';
        $html .= '<td width="20%">Name Of Person</td>';
        $html .= '<td width="30%">'.$purchase['cash_saler_name'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" >Address </td>';
        $html .= '<td width="30%">'.$purchase['address'].'</td>';
        $html .= "<td width='20%'>Person's Address</td>";
        $html .= '<td width="30%">'.$purchase['cash_Pur_address'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" >Phone </td>';
        $html .= '<td width="30%">'.$purchase['phone_no'].'</td>';
        $html .= "<td width='20%'>Person's Phone</td>";
        $html .= '<td width="30%">'.$purchase['cash_pur_phone'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Remarks </td>';
        $html .= '<td width="80%">'.$purchase['pur_remarks'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;text-align:center" >';
        $html .= '<tr>';
        $html .= '<th style="width:10%;">S/R</th>';
        $html .= '<th style="width:10%">Qty</th>';
        $html .= '<th style="width:20%">Item Name</th>';
        $html .= '<th style="width:24%">Description</th>';
        $html .= '<th style="width:12%">Price</th>';
        $html .= '<th style="width:12%">Weight</th>';
        $html .= '<th style="width:12%">Amount</th>';
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

        foreach ($purchase_items as $items) {
            if($count%2==0)
            {
                $item_table .= '<tr style="background-color:#f1f1f1">';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000;border-left:1px dashed #000">'.$count.'</td>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000">'.$items['pur_qty2'].'</td>';
                $total_quantity=$total_quantity+$items['pur_qty2'];
                $item_table .= '<td style="width:20%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['pur_price'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['pur_qty'].'</td>';
                $total_weight=$total_weight+$items['pur_qty'];
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['pur_qty']*$items['pur_price'].'</td>';
                $total_amount=$total_amount+($items['pur_qty']*$items['pur_price']);
                $item_table .= '</tr>';
            }
            else{
                $item_table .= '<tr>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000;border-left:1px dashed #000">'.$count.'</td>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000">'.$items['pur_qty2'].'</td>';
                $total_quantity=$total_quantity+$items['pur_qty2'];
                $item_table .= '<td style="width:20%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['pur_price'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['pur_qty'].'</td>';
                $total_weight=$total_weight+$items['pur_qty'];
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['pur_qty']*$items['pur_price'].'</td>';
                $total_amount=$total_amount+($items['pur_qty']*$items['pur_price']);
                $item_table .= '</tr>';
            }
            $count++;
        }
        $item_table .= '</table>';
        $pdf->writeHTML($item_table, true, false, true, false, '');

        $currentY = $pdf->GetY();

        // Column 1
        $pdf->SetXY(15, $currentY+10);
        $pdf->MultiCell(30, 5, 'Total Weight(kg)', 1,1);
        $pdf->MultiCell(30, 5, 'Total Quantity', 1,1);

        // Column 2
        $pdf->SetXY(45.1, $currentY+10);
        $pdf->MultiCell(50, 5,  $total_weight, 1, 'R');
        $pdf->SetXY(45.1, $currentY+16.82);
        $pdf->MultiCell(50, 5, $total_quantity, 1,'R');

        // Column 3
        $pdf->SetXY(200, $currentY+10);
        $pdf->MultiCell(40, 5, 'Total Amount', 1,1);
        $pdf->SetXY(200, $currentY+16.82);
        $pdf->MultiCell(40, 5, 'Labour Charges', 1,1);
        $pdf->SetXY(200, $currentY+23.5);
        $pdf->MultiCell(40, 5, 'Convance Charges', 1,1);
        $pdf->SetXY(200, $currentY+30.18);
        $pdf->MultiCell(40, 5, 'Discount(Rs)', 1,1);
        $pdf->SetXY(200, $currentY+36.86);
        $pdf->MultiCell(40, 5, 'Net Amount', 1,1);
        
        // Column 4
        $pdf->SetXY(240, $currentY+10);
        $pdf->MultiCell(40, 5, $total_amount, 1, 'R');
        $pdf->SetXY(240, $currentY+16.82);
        $pdf->MultiCell(40, 5, $purchase['pur_labor_char'], 1, 'R');
        $pdf->SetXY(240, $currentY+23.5);
        $pdf->MultiCell(40, 5, $purchase['pur_convance_char'], 1, 'R');
        $pdf->SetXY(240, $currentY+30.18);
        $pdf->MultiCell(40, 5, $purchase['pur_discount'], 1, 'R');
        $pdf->SetXY(240, $currentY+36.86);
        $net_amount=$total_amount+$purchase['pur_labor_char']+$purchase['pur_convance_char']-$purchase['pur_discount'];
        $pdf->MultiCell(40, 5,  $net_amount, 1, 'R');
        
        // Close and output PDF
        $pdf->Output('invoice_'.$purchase['pur_id'].'.pdf', 'I');
    }
}
