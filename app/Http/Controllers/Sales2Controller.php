<?php

namespace App\Http\Controllers;

use TCPDF;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
use App\Models\Item_Groups;
use App\Models\AC;
use App\Models\tsales;
use App\Models\tsales_2;
use App\Models\sale2_att;
use App\Models\tpurchase;
use App\Models\tstock_out;
use Illuminate\Support\Facades\File;
use App\Traits\SaveImage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class Sales2Controller extends Controller
{
    use SaveImage;

    public function index()
    {
        $pur2 = tsales::where('tsales.status',1)
        ->join ('tsales_2', 'tsales_2.sales_inv_cod' , '=', 'tsales.Sal_inv_no')
        ->join('ac as acc_name', 'acc_name.ac_code', '=', 'tsales.account_name')
        ->join('ac as comp_acc', 'comp_acc.ac_code', '=', 'tsales.company_name')
        ->select(
            'tsales.Sal_inv_no','tsales.sa_date','acc_name.ac_name as acc_name','tsales.pur_ord_no',
            'comp_acc.ac_name as comp_account','tsales.company_name','tsales.Sales_Remarks','tsales.pur_against','tsales.prefix',
            'tsales.ConvanceCharges','tsales.LaborCharges','tsales.Bill_discount', 'tsales.pur_against',
            \DB::raw('SUM(tsales_2.weight_pc * tsales_2.Sales_qty2) as weight_sum'),
            \DB::raw('SUM(((tsales_2.Sales_qty2 * tsales_2.sales_price) + ((tsales_2.Sales_qty2 * tsales_2.sales_price) * (tsales_2.discount/100))) * tsales_2.length) as total_bill')
        )
        ->groupby('tsales.Sal_inv_no','tsales.sa_date','acc_name','tsales.pur_ord_no',
            'comp_account','tsales.company_name','tsales.Sales_Remarks','tsales.pur_against','tsales.prefix',
            'tsales.ConvanceCharges','tsales.LaborCharges','tsales.Bill_discount', 'tsales.pur_against',)
        ->get();

        return view('sale2.index',compact('pur2'));
    }

    public function create(Request $request)
    {
        $items = Item_entry2::all();
        $item_group = Item_Groups::all();
        $coa = AC::all();
        return view('sale2.create',compact('items','coa','item_group'));
    }

    public function store(Request $request)
    {
        
        $pur2 = new tsales();

        if ($request->has('sa_date') && $request->sa_date) {
            $pur2->sa_date=$request->sa_date;
        }
        if ($request->has('pur_ord_no') && $request->pur_ord_no) {
            $pur2->pur_ord_no=$request->pur_ord_no;
        }
        if ($request->has('sales_against') && $request->sales_against) {
            $pur2->pur_against=$request->sales_against;
        }
        if ($request->has('account_name') && $request->account_name) {
            $pur2->account_name=$request->account_name;
        }
        if ($request->has('disp_account_name') && $request->disp_account_name) {
            $pur2->company_name=$request->disp_account_name;
        }
        if ($request->has('Cash_pur_name') && $request->Cash_pur_name) {
            $pur2->Cash_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_Pur_address') && $request->cash_Pur_address) {
            $pur2->cash_Pur_address=$request->cash_Pur_address;
        }
        if ($request->has('Sales_Remarks') && $request->Sales_Remarks) {
            $pur2->Sales_Remarks=$request->Sales_Remarks;
        }
        if ($request->has('ConvanceCharges') && $request->ConvanceCharges) {
            $pur2->ConvanceCharges=$request->ConvanceCharges;
        }
        if ($request->has('LaborCharges') && $request->LaborCharges) {
            $pur2->LaborCharges=$request->LaborCharges;
        }
        if ($request->has('Bill_discount') && $request->Bill_discount) {
            $pur2->Bill_discount=$request->Bill_discount;
        }
        $pur2->created_by=1;

        $pur2->save();

        $pur_2_id = tsales::latest()->first();
        
        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $tsales_2 = new tsales_2();

                    $tsales_2->sales_inv_cod=$pur_2_id['Sal_inv_no'];
                    $tsales_2->item_cod=$request->item_cod[$i];

                    if ($request->remarks[$i]!=null) {
                        $tsales_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur2_qty2[$i]!=null) {
                        $tsales_2->Sales_qty2=$request->pur2_qty2[$i];
                    }
                    if ($request->pur2_per_unit[$i]!=null) {
                        $tsales_2->sales_price=$request->pur2_per_unit[$i];
                    }
                    if ($request->weight_per_piece[$i]!=null) {
                        $tsales_2->weight_pc=$request->weight_per_piece[$i];
                    }
                    if ($request->pur2_len[$i]!=null) {
                        $tsales_2->length=$request->pur2_len[$i];
                    }
                    if ($request->pur2_price_date[$i]!=null) {
                        $tsales_2->rat_dat=$request->pur2_price_date[$i];
                    }
                    if ($request->pur2_percentage[$i]!=null) {
                        $tsales_2->discount=$request->pur2_percentage[$i];
                    }
                    
                    $tsales_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $pur2Att = new sale2_att();
                $pur2Att->sale2_id = $pur_2_id['Sal_inv_no'];
                $extension = $file->getClientOriginalExtension();
                $pur2Att->att_path = $this->sale2Doc($file,$extension);
                $pur2Att->save();
            }
        }

        if($request->has('isInduced') && $request->isInduced == 1){
            $tstock_out = new tstock_out();

            $inducedID=$pur_2_id['Sal_inv_no'];
            $prefix=$pur_2_id['prefix'];
            $pur_inv = $prefix.''.$inducedID;
            $tstock_out->pur_inv = $pur_inv;

            tstock_out::where('Sal_inv_no', $inducedID)->update([
                'pur_inv'=>$tstock_out->pur_inv,
            ]);
        }   

        elseif($request->has('isInduced') && $request->isInduced == 2){

        }

        return redirect()->route('all-sale2invoices');
    }

    public function edit($id)
    {
        $items = Item_entry2::all();
        $item_group = Item_Groups::all();
        $coa = AC::all();
        $pur2 = tsales::where('tsales.Sal_inv_no',$id)
        ->select(
            'tsales.Sal_inv_no','tsales.sa_date','tsales.pur_ord_no', 'tsales.company_name','tsales.Sales_Remarks','tsales.pur_against',
            'tsales.ConvanceCharges','tsales.cash_Pur_address','tsales.LaborCharges','tsales.Bill_discount','tsales.prefix','tsales.account_name',
            'tsales.company_name','tsales.Cash_name',
        )
        ->groupby('tsales.Sal_inv_no','tsales.sa_date','tsales.pur_ord_no','tsales.company_name',
        'tsales.Sales_Remarks','tsales.cash_Pur_address','tsales.pur_against','tsales.ConvanceCharges','tsales.account_name',
        'tsales.LaborCharges','tsales.Bill_discount','tsales.prefix','tsales.company_name','tsales.Cash_name')
        ->first();

        $pur2_item = tsales_2::where('tsales_2.sales_inv_cod',$id)->get();

        return view('sale2.edit',compact('pur2','pur2_item','items','coa','item_group'));
    }

    public function update(Request $request){

        $pur2 = tsales::where('Sal_inv_no',$request->pur2_id)->get()->first();

        if ($request->has('sa_date') && $request->sa_date) {
            $pur2->sa_date=$request->sa_date;
        }
        if ($request->has('pur_ord_no') && $request->pur_ord_no) {
            $pur2->pur_ord_no=$request->pur_ord_no;
        }
        if ($request->has('sales_against') && $request->sales_against) {
            $pur2->pur_against=$request->sales_against;
        }
        if ($request->has('account_name') && $request->account_name) {
            $pur2->account_name=$request->account_name;
        }
        if ($request->has('disp_account_name') && $request->disp_account_name) {
            $pur2->company_name=$request->disp_account_name;
        }
        if ($request->has('Cash_pur_name') && $request->Cash_pur_name) {
            $pur2->Cash_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_Pur_address') && $request->cash_Pur_address) {
            $pur2->cash_Pur_address=$request->cash_Pur_address;
        }
        if ($request->has('Sales_Remarks') && $request->Sales_Remarks) {
            $pur2->Sales_Remarks=$request->Sales_Remarks;
        }
        if ($request->has('ConvanceCharges') && $request->ConvanceCharges) {
            $pur2->ConvanceCharges=$request->ConvanceCharges;
        }
        if ($request->has('LaborCharges') && $request->LaborCharges) {
            $pur2->LaborCharges=$request->LaborCharges;
        }
        if ($request->has('Bill_discount') && $request->Bill_discount) {
            $pur2->Bill_discount=$request->Bill_discount;
        }
        $pur2->created_by=1;

        // die(print_r($pur2));
        
        tsales::where('Sal_inv_no', $request->pur2_id)->update([
            'sa_date'=>$pur2->sa_date,
            'pur_ord_no'=>$pur2->pur_ord_no,
            'pur_against'=>$pur2->pur_against,
            'account_name'=>$pur2->account_name,
            'company_name'=>$pur2->company_name,
            'Cash_name'=>$pur2->Cash_name,
            'cash_Pur_address'=>$pur2->cash_Pur_address,
            'Sales_Remarks'=>$pur2->Sales_Remarks,
            'ConvanceCharges'=>$pur2->ConvanceCharges,
            'LaborCharges'=>$pur2->LaborCharges,
            'Bill_discount'=>$pur2->Bill_discount,
            'created_by' => $pur2->created_by,
        ]);

        tsales_2::where('sales_inv_cod', $request->pur2_id)->delete();

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $tsales_2 = new tsales_2();

                    $tsales_2->sales_inv_cod=$request->pur2_id;
                    $tsales_2->item_cod=$request->item_cod[$i];

                    if ($request->remarks[$i]!=null) {
                        $tsales_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur2_qty2[$i]!=null) {
                        $tsales_2->Sales_qty2=$request->pur2_qty2[$i];
                    }
                    if ($request->pur2_per_unit[$i]!=null) {
                        $tsales_2->sales_price=$request->pur2_per_unit[$i];
                    }
                    if ($request->weight_per_piece[$i]!=null) {
                        $tsales_2->weight_pc=$request->weight_per_piece[$i];
                    }
                    if ($request->pur2_len[$i]!=null) {
                        $tsales_2->length=$request->pur2_len[$i];
                    }
                    if ($request->pur2_price_date[$i]!=null) {
                        $tsales_2->rat_dat=$request->pur2_price_date[$i];
                    }
                    if ($request->pur2_percentage[$i]!=null) {
                        $tsales_2->discount=$request->pur2_percentage[$i];
                    }
                    
                    $tsales_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $pur2Att = new sale2_att();
                $pur2Att->sale2_id = $request->pur2_id;
                $extension = $file->getClientOriginalExtension();
                $pur2Att->att_path = $this->sale2Doc($file,$extension);
                $pur2Att->save();
            }
        }

        return redirect()->route('all-sale2invoices');
    }

    public function destroy(Request $request)
    {
        tsales::where('Sale_inv_no', $request->delete_purc2)->update(['status' => '0']);
        return redirect()->route('all-sale2invoices');
    }

    public function show(string $id)
    {
        $pur = tsales::where('Sale_inv_no',$id)
                ->join('ac as acc_name','tsales.account_name','=','acc_name.ac_code')
                ->join('ac as dispt_to','tsales.company_name','=','dispt_to.ac_code')
                ->select('tsales.*','dispt_to.ac_name as disp_to','acc_name.ac_name as ac_name', 
                'acc_name.address as address', 'acc_name.phone_no as phone_no')
                ->first();

        $pur2 = tsales_2::where('sales_inv_cod',$id)
                ->join('item_entry as ie','tsales_2.item_cod','=','ie.it_cod')
                ->select('tsales_2.*','ie.item_name')
                ->get();

        return view('sale2.view',compact('pur','pur2'));
    }

    public function getAttachements(Request $request)
    {
        $sale2_atts = sale2_att::where('sale2_id', $request->id)->get();
        
        return $sale2_atts;
    }

    public function getunclosed()
    {
        $unclosed_inv = tsales::where(function ($query) {
            $query->where('pur_against', '')
                  ->orWhereNull('pur_against');
        })
        ->join('ac', 'ac.ac_code', '=', 'tsales.account_name')
        ->join('ac as dispt_acc', 'dispt_acc.ac_code', '=', 'tsales.company_name')
        ->select('tsales.*', 'ac.ac_name as acc_name','dispt_acc.ac_name as disp_acc')  // Select fields from both tables as needed
        ->get();
        return $unclosed_inv;
    }

    public function getItems($id){

        $pur1= tsales::where('Sale_inv_no',$id)->get()->first();

        $pur2 = tsales_2::where('sales_inv_cod',$id)
        ->join('item_entry as ie','tsales_2.item_cod','=','ie.it_cod')
        ->select('tsales_2.*','ie.item_name')
        ->get();

        return response()->json([
            'pur1' => $pur1,
            'pur2' => $pur2,
        ]);
    }

    public function deleteAtt($id)
    {
        $doc=sale2_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $sale2_att = sale2_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=sale2_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=sale2_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }

    public function showAllPDF($id){

        $purchase = tsales::where('Sal_inv_no',$id)
        ->join('ac','tsales.account_name','=','ac.ac_code')
        ->first();

        $purchase_items = tsales_2::where('sales_inv_cod',$id)
                ->join('item_entry2','tsales_2.item_cod','=','item_entry2.it_cod')
                ->select('tsales_2.*','item_entry2.item_name')
                ->get();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Invoice-'.$purchase['Sal_inv_no']);
        $pdf->SetSubject('Invoice-'.$purchase['Sal_inv_no']);
        $pdf->SetKeywords('Invoice, TCPDF, PDF');
               
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
        $html .= '<td>Invoice No: <span style="text-decoration: underline;">'.$purchase['Sale_inv_no'].'</span></td>';
        $html .= '<td>pur_ord_no: '.$purchase['pur_ord_no'].'</td>';
        $html .= '<td>Date: '.\Carbon\Carbon::parse($purchase['sa_date'])->format('d-m-y').'</td>';
        $html .= '<td>Login: Hamza </td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;">';
        $html .= '<tr>';
        $html .= '<td width="20%" style="border-right:1px dashed #000">Account Name</td>';
        $html .= '<td width="30%">'.$purchase['ac_name'].'</td>';
        $html .= '<td width="20%">Name Of Person</td>';
        $html .= '<td width="30%">'.$purchase['company_name'].'</td>';
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
        $html .= '<td width="80%">'.$purchase['Sales_Remarks'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;text-align:center" >';
        $html .= '<tr>';
        $html .= '<th style="width:10%;">S/R</th>';
        $html .= '<th style="width:21%">Item Name</th>';
        $html .= '<th style="width:24%">Description</th>';
        $html .= '<th style="width:8%">Qty</th>';
        $html .= '<th style="width:11%">Price</th>';
        $html .= '<th style="width:7%">Len</th>';
        $html .= '<th style="width:7%">%</th>';
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
                $item_table .= '<td style="width:21%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:8%;border-right:1px dashed #000">'.$items['Sales_qty2'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty2'];
                $item_table .= '<td style="width:11%;border-right:1px dashed #000">'.$items['sales_price'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['length'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['discount'].'</td>';
                $total_weight=$total_weight+($items['Sales_qty2']*$items['weight_pc']);
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.(($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length'].'</td>';
                $total_amount=$total_amount+((($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length']);
                $item_table .= '</tr>';
            }
            else{
                $item_table .= '<tr>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000;border-left:1px dashed #000">'.$count.'</td>';
                $item_table .= '<td style="width:21%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:8%;border-right:1px dashed #000">'.$items['Sales_qty2'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty2'];
                $item_table .= '<td style="width:11%;border-right:1px dashed #000">'.$items['sales_price'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['length'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['discount'].'</td>';
                $total_weight=$total_weight+($items['Sales_qty2']*$items['weight_pc']);
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.(($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length'].'</td>';
                $total_amount=$total_amount+((($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length']);
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
        $pdf->MultiCell(42, 5,  $total_weight, 1, 'R');
        $pdf->SetXY(45.1, $currentY+16.82);
        $pdf->MultiCell(42, 5, $total_quantity, 1,'R');

        // Column 3
        $pdf->SetXY(120, $currentY+10);
        $pdf->MultiCell(40, 5, 'Total Amount', 1,1);
        $pdf->SetXY(120, $currentY+16.82);
        $pdf->MultiCell(40, 5, 'Labour Charges', 1,1);
        $pdf->SetXY(120, $currentY+23.5);
        $pdf->MultiCell(40, 5, 'Convance Charges', 1,1);
        $pdf->SetXY(120, $currentY+30.18);
        $pdf->MultiCell(40, 5, 'Discount(Rs)', 1,1);
        $pdf->SetXY(120, $currentY+36.86);
        $pdf->MultiCell(40, 5, 'Net Amount', 1,1);
        
        // Column 4
        $pdf->SetXY(160, $currentY+10);
        $pdf->MultiCell(35, 5, $total_amount, 1, 'R');
        $pdf->SetXY(160, $currentY+16.82);
        $pdf->MultiCell(35, 5, $purchase['LaborCharges'], 1, 'R');
        $pdf->SetXY(160, $currentY+23.5);
        $pdf->MultiCell(35, 5, $purchase['ConvanceCharges'], 1, 'R');
        $pdf->SetXY(160, $currentY+30.18);
        $pdf->MultiCell(35, 5, $purchase['Bill_discount'], 1, 'R');
        $pdf->SetXY(160, $currentY+36.86);
        $net_amount=number_format($total_amount+$purchase['LaborCharges']+$purchase['ConvanceCharges']-$purchase['Bill_discount']);
        $pdf->MultiCell(35, 5,  $net_amount, 1, 'R');
        
        // Close and output PDF
        $pdf->Output('invoice_'.$purchase['pur_id'].'.pdf', 'I');

    }

    public function noLengthPDF($id){
        $purchase = tsales::where('Sale_inv_no',$id)
        ->join('ac','tsales.account_name','=','ac.ac_code')
        ->first();

        $purchase_items = tsales_2::where('sales_inv_cod',$id)
                ->join('item_entry2','tsales_2.item_cod','=','item_entry2.it_cod')
                ->select('tsales_2.*','item_entry2.item_name')
                ->get();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Invoice-'.$purchase['Sale_inv_no']);
        $pdf->SetSubject('Invoice-'.$purchase['Sale_inv_no']);
        $pdf->SetKeywords('Invoice, TCPDF, PDF');
               
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
        $html .= '<td>Invoice No: <span style="text-decoration: underline;">'.$purchase['Sale_inv_no'].'</span></td>';
        $html .= '<td>pur_ord_no: '.$purchase['pur_ord_no'].'</td>';
        $html .= '<td>Date: '.\Carbon\Carbon::parse($purchase['sa_date'])->format('d-m-y').'</td>';
        $html .= '<td>Login: Hamza </td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;">';
        $html .= '<tr>';
        $html .= '<td width="20%" style="border-right:1px dashed #000">Account Name</td>';
        $html .= '<td width="30%">'.$purchase['ac_name'].'</td>';
        $html .= '<td width="20%">Name Of Person</td>';
        $html .= '<td width="30%">'.$purchase['company_name'].'</td>';
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
        $html .= '<td width="80%">'.$purchase['Sales_Remarks'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;text-align:center" >';
        $html .= '<tr>';
        $html .= '<th style="width:10%;">S/R</th>';
        $html .= '<th style="width:21%">Item Name</th>';
        $html .= '<th style="width:24%">Description</th>';
        $html .= '<th style="width:8%">Qty</th>';
        $html .= '<th style="width:11%">Price</th>';
        $html .= '<th style="width:7%">Len</th>';
        $html .= '<th style="width:7%">%</th>';
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
                $item_table .= '<td style="width:21%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:8%;border-right:1px dashed #000">'.$items['Sales_qty2'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty2'];
                $item_table .= '<td style="width:11%;border-right:1px dashed #000">'.$items['sales_price'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['length'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['discount'].'</td>';
                $total_weight=$total_weight+($items['Sales_qty2']*$items['weight_pc']);
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.(($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length'].'</td>';
                $total_amount=$total_amount+((($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length']);
                $item_table .= '</tr>';
            }
            else{
                $item_table .= '<tr>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000;border-left:1px dashed #000">'.$count.'</td>';
                $item_table .= '<td style="width:21%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:8%;border-right:1px dashed #000">'.$items['Sales_qty2'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty2'];
                $item_table .= '<td style="width:11%;border-right:1px dashed #000">'.$items['sales_price'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['length'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['discount'].'</td>';
                $total_weight=$total_weight+($items['Sales_qty2']*$items['weight_pc']);
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.(($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length'].'</td>';
                $total_amount=$total_amount+((($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length']);
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
        $pdf->MultiCell(42, 5,  $total_weight, 1, 'R');
        $pdf->SetXY(45.1, $currentY+16.82);
        $pdf->MultiCell(42, 5, $total_quantity, 1,'R');

        // Column 3
        $pdf->SetXY(120, $currentY+10);
        $pdf->MultiCell(40, 5, 'Total Amount', 1,1);
        $pdf->SetXY(120, $currentY+16.82);
        $pdf->MultiCell(40, 5, 'Labour Charges', 1,1);
        $pdf->SetXY(120, $currentY+23.5);
        $pdf->MultiCell(40, 5, 'Convance Charges', 1,1);
        $pdf->SetXY(120, $currentY+30.18);
        $pdf->MultiCell(40, 5, 'Discount(Rs)', 1,1);
        $pdf->SetXY(120, $currentY+36.86);
        $pdf->MultiCell(40, 5, 'Net Amount', 1,1);
        
        // Column 4
        $pdf->SetXY(160, $currentY+10);
        $pdf->MultiCell(35, 5, $total_amount, 1, 'R');
        $pdf->SetXY(160, $currentY+16.82);
        $pdf->MultiCell(35, 5, $purchase['LaborCharges'], 1, 'R');
        $pdf->SetXY(160, $currentY+23.5);
        $pdf->MultiCell(35, 5, $purchase['ConvanceCharges'], 1, 'R');
        $pdf->SetXY(160, $currentY+30.18);
        $pdf->MultiCell(35, 5, $purchase['Bill_discount'], 1, 'R');
        $pdf->SetXY(160, $currentY+36.86);
        $net_amount=round($total_amount+$purchase['LaborCharges']+$purchase['ConvanceCharges']-$purchase['Bill_discount']);
        $pdf->MultiCell(35, 5,  $net_amount, 1, 'R');
        
        // Close and output PDF
        $pdf->Output('invoice_'.$purchase['pur_id'].'.pdf', 'I');
    
    }

    public function onlyPriceQtyPDF($id){
        $purchase = tsales::where('Sale_inv_no',$id)
        ->join('ac','tsales.account_name','=','ac.ac_code')
        ->first();

        $purchase_items = tsales_2::where('sales_inv_cod',$id)
                ->join('item_entry2','tsales_2.item_cod','=','item_entry2.it_cod')
                ->select('tsales_2.*','item_entry2.item_name')
                ->get();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Invoice-'.$purchase['Sale_inv_no']);
        $pdf->SetSubject('Invoice-'.$purchase['Sale_inv_no']);
        $pdf->SetKeywords('Invoice, TCPDF, PDF');
               
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
        $html .= '<td>Invoice No: <span style="text-decoration: underline;">'.$purchase['Sale_inv_no'].'</span></td>';
        $html .= '<td>pur_ord_no: '.$purchase['pur_ord_no'].'</td>';
        $html .= '<td>Date: '.\Carbon\Carbon::parse($purchase['sa_date'])->format('d-m-y').'</td>';
        $html .= '<td>Login: Hamza </td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;">';
        $html .= '<tr>';
        $html .= '<td width="20%" style="border-right:1px dashed #000">Account Name</td>';
        $html .= '<td width="30%">'.$purchase['ac_name'].'</td>';
        $html .= '<td width="20%">Name Of Person</td>';
        $html .= '<td width="30%">'.$purchase['company_name'].'</td>';
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
        $html .= '<td width="80%">'.$purchase['Sales_Remarks'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;text-align:center" >';
        $html .= '<tr>';
        $html .= '<th style="width:10%;">S/R</th>';
        $html .= '<th style="width:21%">Item Name</th>';
        $html .= '<th style="width:24%">Description</th>';
        $html .= '<th style="width:8%">Qty</th>';
        $html .= '<th style="width:11%">Price</th>';
        $html .= '<th style="width:7%">Len</th>';
        $html .= '<th style="width:7%">%</th>';
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
                $item_table .= '<td style="width:21%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:8%;border-right:1px dashed #000">'.$items['Sales_qty2'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty2'];
                $item_table .= '<td style="width:11%;border-right:1px dashed #000">'.$items['sales_price'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['length'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['discount'].'</td>';
                $total_weight=$total_weight+($items['Sales_qty2']*$items['weight_pc']);
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.(($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length'].'</td>';
                $total_amount=$total_amount+((($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length']);
                $item_table .= '</tr>';
            }
            else{
                $item_table .= '<tr>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000;border-left:1px dashed #000">'.$count.'</td>';
                $item_table .= '<td style="width:21%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:8%;border-right:1px dashed #000">'.$items['Sales_qty2'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty2'];
                $item_table .= '<td style="width:11%;border-right:1px dashed #000">'.$items['sales_price'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['length'].'</td>';
                $item_table .= '<td style="width:7%;border-right:1px dashed #000">'.$items['discount'].'</td>';
                $total_weight=$total_weight+($items['Sales_qty2']*$items['weight_pc']);
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.(($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length'].'</td>';
                $total_amount=$total_amount+((($items['Sales_qty2'] * $items['sales_price'])+(($items['Sales_qty2'] * $items['sales_price']) * ($items['discount']/100))) * $items['length']);
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
        $pdf->MultiCell(42, 5,  $total_weight, 1, 'R');
        $pdf->SetXY(45.1, $currentY+16.82);
        $pdf->MultiCell(42, 5, $total_quantity, 1,'R');

        // Column 3
        $pdf->SetXY(120, $currentY+10);
        $pdf->MultiCell(40, 5, 'Total Amount', 1,1);
        $pdf->SetXY(120, $currentY+16.82);
        $pdf->MultiCell(40, 5, 'Labour Charges', 1,1);
        $pdf->SetXY(120, $currentY+23.5);
        $pdf->MultiCell(40, 5, 'Convance Charges', 1,1);
        $pdf->SetXY(120, $currentY+30.18);
        $pdf->MultiCell(40, 5, 'Discount(Rs)', 1,1);
        $pdf->SetXY(120, $currentY+36.86);
        $pdf->MultiCell(40, 5, 'Net Amount', 1,1);
        
        // Column 4
        $pdf->SetXY(160, $currentY+10);
        $pdf->MultiCell(35, 5, $total_amount, 1, 'R');
        $pdf->SetXY(160, $currentY+16.82);
        $pdf->MultiCell(35, 5, $purchase['LaborCharges'], 1, 'R');
        $pdf->SetXY(160, $currentY+23.5);
        $pdf->MultiCell(35, 5, $purchase['ConvanceCharges'], 1, 'R');
        $pdf->SetXY(160, $currentY+30.18);
        $pdf->MultiCell(35, 5, $purchase['Bill_discount'], 1, 'R');
        $pdf->SetXY(160, $currentY+36.86);
        $net_amount=round($total_amount+$purchase['LaborCharges']+$purchase['ConvanceCharges']-$purchase['Bill_discount']);
        $pdf->MultiCell(35, 5,  $net_amount, 1, 'R');
        
        // Close and output PDF
        $pdf->Output('invoice_'.$purchase['pur_id'].'.pdf', 'I');
    }

    public function generatePDF(Request $request)
    {
        if($request->print_type==1){
            $this->showAllPDF($request->print_sale2);
        }
        elseif($request->print_type==2){
            $this->noLengthPDF($request->print_sale2);
        }
        elseif($request->print_type==3){
            $this->onlyPriceQtyPDF($request->print_sale2);
        }
    }
}
