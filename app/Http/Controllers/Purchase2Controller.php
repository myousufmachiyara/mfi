<?php

namespace App\Http\Controllers;

use TCPDF;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
use App\Models\Item_Groups;
use App\Models\AC;
use App\Models\tpurchase;
use App\Models\tpurchase_2;
use App\Models\pur2_att;
use App\Models\tax_tpurchase_2;
use Illuminate\Support\Facades\File;
use App\Traits\SaveImage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class Purchase2Controller extends Controller
{
    use SaveImage;

    public function index()
    {
        $pur2 = tpurchase::where('tpurchase.status',1)
        ->join ('tpurchase_2', 'tpurchase_2.sales_inv_cod' , '=', 'tpurchase.Sale_inv_no')
        ->join('ac as acc_name', 'acc_name.ac_code', '=', 'tpurchase.account_name')
        ->join('ac as disp_to', 'disp_to.ac_code', '=', 'tpurchase.Cash_pur_name_ac')
        ->leftjoin('tax_tpurchase_2', 'tax_tpurchase_2.sales_inv_cod', '=', 'tpurchase.Sale_inv_no')
        ->leftjoin('item_group', 'item_group.item_group_cod', '=', 'tax_tpurchase_2.item')
        ->select(
            'tpurchase.Sale_inv_no','tpurchase.sa_date','acc_name.ac_name as acc_name','tpurchase.pur_ord_no',
            'disp_to.ac_name as disp_to','tpurchase.Cash_pur_name','tpurchase.Sales_Remarks','tpurchase.sales_against',
            'tpurchase.ConvanceCharges','tpurchase.LaborCharges','tpurchase.Bill_discount','item_group.group_name',
            \DB::raw('SUM(tpurchase_2.weight_pc * tpurchase_2.Sales_qty2) as weight_sum'),
            \DB::raw('SUM(((tpurchase_2.Sales_qty2 * tpurchase_2.sales_price) + ((tpurchase_2.Sales_qty2 * tpurchase_2.sales_price) * (tpurchase_2.discount/100))) * tpurchase_2.length) as total_bill')
        )
        ->groupby('tpurchase.Sale_inv_no','tpurchase.sa_date','acc_name.ac_name','tpurchase.pur_ord_no','item_group.group_name',
            'disp_to.ac_name','tpurchase.Cash_pur_name','tpurchase.Sales_Remarks','tpurchase.sales_against',
            'tpurchase.ConvanceCharges','tpurchase.LaborCharges','tpurchase.Bill_discount')
        ->get();
        // 'item_group.group_name'
        return view('purchase2.index',compact('pur2'));
    }

    public function create(Request $request)
    {
        $items = Item_entry2::all();
        $item_group = Item_Groups::all();
        $coa = AC::all();
        return view('purchase2.create',compact('items','coa','item_group'));
    }

    public function store(Request $request)
    {
        
        $pur2 = new tpurchase();

        if ($request->has('sa_date') && $request->sa_date) {
            $pur2->sa_date=$request->sa_date;
        }
        if ($request->has('pur_ord_no') && $request->pur_ord_no) {
            $pur2->pur_ord_no=$request->pur_ord_no;
        }
        if ($request->has('sales_against') && $request->sales_against) {
            $pur2->sales_against=$request->sales_against;
        }
        if ($request->has('account_name') && $request->account_name) {
            $pur2->account_name=$request->account_name;
        }
        if ($request->has('disp_account_name') && $request->disp_account_name) {
            $pur2->Cash_pur_name_ac=$request->disp_account_name;
        }
        if ($request->has('Cash_pur_name') && $request->Cash_pur_name) {
            $pur2->Cash_pur_name=$request->Cash_pur_name;
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

        $pur2->save();

        $pur_2_id = tpurchase::latest()->first();

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $tpurchase_2 = new tpurchase_2();

                    $tpurchase_2->sales_inv_cod=$pur_2_id['Sale_inv_no'];
                    $tpurchase_2->item_cod=$request->item_cod[$i];

                    if ($request->remarks[$i]!=null) {
                        $tpurchase_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur2_qty2[$i]!=null) {
                        $tpurchase_2->Sales_qty2=$request->pur2_qty2[$i];
                    }
                    if ($request->pur2_per_unit[$i]!=null) {
                        $tpurchase_2->sales_price=$request->pur2_per_unit[$i];
                    }
                    if ($request->weight_per_piece[$i]!=null) {
                        $tpurchase_2->weight_pc=$request->weight_per_piece[$i];
                    }
                    if ($request->pur2_len[$i]!=null) {
                        $tpurchase_2->length=$request->pur2_len[$i];
                    }
                    if ($request->pur2_price_date[$i]!=null) {
                        $tpurchase_2->rat_dat=$request->pur2_price_date[$i];
                    }
                    if ($request->pur2_percentage[$i]!=null) {
                        $tpurchase_2->discount=$request->pur2_percentage[$i];
                    }
                    
                    $tpurchase_2->save();
                }
            }
        }

        $toggleValue = $request->isCommissionForm;
         
        if ($toggleValue === "1") {

            $tax_pur2 = new tax_tpurchase_2();

            $tax_pur2->sales_inv_cod=$pur_2_id['Sale_inv_no'];

            if ($request->has('bamount') && $request->bamount) {
                $tax_pur2->bamount=$request->bamount;
            }
            if ($request->has('disc') && $request->disc) {
                $tax_pur2->disc=$request->disc;
            }
            if ($request->has('cd_disc') && $request->cd_disc) {
                $tax_pur2->cd_disc=$request->cd_disc;
            }
            if ($request->has('comm_disc') && $request->comm_disc) {
                $tax_pur2->comm_disc=$request->comm_disc;
            }
            if ($request->has('comm_amount') && $request->comm_amount) {
                $tax_pur2->comm_amount=$request->comm_amount;
            }
            if ($request->has('tax_item_name') && $request->tax_item_name) {
                $tax_pur2->item=$request->tax_item_name;
            }
            if ($request->has('tax_remarks') && $request->tax_remarks) {
                $tax_pur2->remarks=$request->tax_remarks;
            }

            $tax_pur2->save();
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $pur2Att = new pur2_att();
                $pur2Att->pur2_id = $pur_2_id['Sale_inv_no'];
                $extension = $file->getClientOriginalExtension();
                $pur2Att->att_path = $this->pur2Doc($file,$extension);
                $pur2Att->save();
            }
        }

        return redirect()->route('all-purchases2');
    }

    public function edit($id)
    {
        $items = Item_entry2::all();
        $item_group = Item_Groups::all();
        $coa = AC::all();
        $pur2 = tpurchase::where('tpurchase.Sale_inv_no',$id)
        ->leftjoin('tax_tpurchase_2', 'tax_tpurchase_2.sales_inv_cod', '=', 'tpurchase.Sale_inv_no')
        ->select(
            'tpurchase.Sale_inv_no','tpurchase.sa_date','tpurchase.pur_ord_no', 'tpurchase.Cash_pur_name','tpurchase.Sales_Remarks','tpurchase.sales_against',
            'tpurchase.ConvanceCharges','tpurchase.cash_Pur_address','tpurchase.LaborCharges','tpurchase.Bill_discount','tpurchase.account_name',
            'tpurchase.Cash_pur_name_ac','bamount', 'disc', 'item', 'comm_amount', 'comm_disc', 'cd_disc','tax_id',
            'tax_tpurchase_2.remarks as tax_remarks'
        )
        ->groupby('tpurchase.Sale_inv_no','tpurchase.sa_date','tpurchase.pur_ord_no','tpurchase.Cash_pur_name',
        'tpurchase.Sales_Remarks','tpurchase.cash_Pur_address','tpurchase.sales_against','tpurchase.ConvanceCharges','tpurchase.account_name',
        'tpurchase.LaborCharges','tpurchase.Bill_discount','tpurchase.Cash_pur_name_ac','bamount', 'disc',
        'item', 'comm_amount', 'comm_disc', 'cd_disc','tax_id', 'tax_remarks' )
        ->first();

        $pur2_item = tpurchase_2::where('tpurchase_2.sales_inv_cod',$id)->get();

        return view('purchase2.edit',compact('pur2','pur2_item','items','coa','item_group'));
    }

    public function update(Request $request){

        $pur2 = tpurchase::where('Sale_inv_no',$request->pur2_id)->get()->first();

        if ($request->has('sa_date') && $request->sa_date) {
            $pur2->sa_date=$request->sa_date;
        }
        if ($request->has('pur_ord_no') && $request->pur_ord_no) {
            $pur2->pur_ord_no=$request->pur_ord_no;
        }
        if ($request->has('sales_against') && $request->sales_against) {
            $pur2->sales_against=$request->sales_against;
        }
        if ($request->has('account_name') && $request->account_name) {
            $pur2->account_name=$request->account_name;
        }
        if ($request->has('disp_account_name') && $request->disp_account_name) {
            $pur2->Cash_pur_name_ac=$request->disp_account_name;
        }
        if ($request->has('Cash_pur_name') && $request->Cash_pur_name) {
            $pur2->Cash_pur_name=$request->Cash_pur_name;
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

        tpurchase::where('Sale_inv_no', $request->pur2_id)->update([
            'sa_date'=>$pur2->sa_date,
            'pur_ord_no'=>$pur2->pur_ord_no,
            'sales_against'=>$pur2->sales_against,
            'account_name'=>$pur2->account_name,
            'Cash_pur_name_ac'=>$pur2->Cash_pur_name_ac,
            'Cash_pur_name'=>$pur2->Cash_pur_name,
            'cash_Pur_address'=>$pur2->cash_Pur_address,
            'Sales_Remarks'=>$pur2->Sales_Remarks,
            'ConvanceCharges'=>$pur2->ConvanceCharges,
            'LaborCharges'=>$pur2->LaborCharges,
            'Bill_discount'=>$pur2->Bill_discount,
        ]);

        tpurchase_2::where('sales_inv_cod', $request->pur2_id)->delete();

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $tpurchase_2 = new tpurchase_2();

                    $tpurchase_2->sales_inv_cod=$request->pur2_id;
                    $tpurchase_2->item_cod=$request->item_cod[$i];

                    if ($request->remarks[$i]!=null) {
                        $tpurchase_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur2_qty2[$i]!=null) {
                        $tpurchase_2->Sales_qty2=$request->pur2_qty2[$i];
                    }
                    if ($request->pur2_per_unit[$i]!=null) {
                        $tpurchase_2->sales_price=$request->pur2_per_unit[$i];
                    }
                    if ($request->weight_per_piece[$i]!=null) {
                        $tpurchase_2->weight_pc=$request->weight_per_piece[$i];
                    }
                    if ($request->pur2_len[$i]!=null) {
                        $tpurchase_2->length=$request->pur2_len[$i];
                    }
                    if ($request->pur2_price_date[$i]!=null) {
                        $tpurchase_2->rat_dat=$request->pur2_price_date[$i];
                    }
                    if ($request->pur2_percentage[$i]!=null) {
                        $tpurchase_2->discount=$request->pur2_percentage[$i];
                    }
                    
                    $tpurchase_2->save();
                }
            }
        }

        $toggleValue = $request->isCommissionForm;

        if ($toggleValue === "1" ) {
            
            $tax_pur2 = tax_tpurchase_2::where('sales_inv_cod',$request->pur2_id)->get()->first();

            if(is_null($tax_pur2)){

                $new_tax_pur2 = new tax_tpurchase_2();

                $new_tax_pur2->sales_inv_cod=$request->pur2_id;
    
                if ($request->has('bamount') && $request->bamount) {
                    $new_tax_pur2->bamount=$request->bamount;
                }
                if ($request->has('disc') && $request->disc) {
                    $new_tax_pur2->disc=$request->disc;
                }
                if ($request->has('cd_disc') && $request->cd_disc) {
                    $new_tax_pur2->cd_disc=$request->cd_disc;
                }
                if ($request->has('comm_disc') && $request->comm_disc) {
                    $new_tax_pur2->comm_disc=$request->comm_disc;
                }
                if ($request->has('comm_amount') && $request->comm_amount) {
                    $new_tax_pur2->comm_amount=$request->comm_amount;
                }
                if ($request->has('tax_item_name') && $request->tax_item_name) {
                    $new_tax_pur2->item=$request->tax_item_name;
                }
                if ($request->has('tax_remarks') && $request->tax_remarks) {
                    $new_tax_pur2->remarks=$request->tax_remarks;
                }
    
                $new_tax_pur2->save();
            }

            else{
                if ($request->has('bamount') && $request->bamount) {
                    $tax_pur2->bamount=$request->bamount;
                }
                if ($request->has('disc') && $request->disc) {
                    $tax_pur2->disc=$request->disc;
                }
                if ($request->has('cd_disc') && $request->cd_disc) {
                    $tax_pur2->cd_disc=$request->cd_disc;
                }
                if ($request->has('comm_disc') && $request->comm_disc) {
                    $tax_pur2->comm_disc=$request->comm_disc;
                }
                if ($request->has('comm_amount') && $request->comm_amount) {
                    $tax_pur2->comm_amount=$request->comm_amount;
                }
                if ($request->has('tax_item_name') && $request->tax_item_name) {
                    $tax_pur2->item=$request->tax_item_name;
                }
                if ($request->has('tax_remarks') && $request->tax_remarks) {
                    $tax_pur2->remarks=$request->tax_remarks;
                }

                tax_tpurchase_2::where('sales_inv_cod', $request->pur2_id)->update([
                    'bamount'=>$tax_pur2->bamount,
                    'disc'=>$tax_pur2->disc,
                    'cd_disc'=>$tax_pur2->cd_disc,
                    'comm_disc'=>$tax_pur2->comm_disc,
                    'comm_amount'=>$tax_pur2->comm_amount,
                    'item'=>$tax_pur2->item,
                    'remarks'=>$tax_pur2->remarks,
                ]);
            }
        }


        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $pur2Att = new pur2_att();
                $pur2Att->pur2_id = $request->pur2_id;
                $extension = $file->getClientOriginalExtension();
                $pur2Att->att_path = $this->pur2Doc($file,$extension);
                $pur2Att->save();
            }
        }

        return redirect()->route('all-purchases2');
    }

    public function destroy(Request $request)
    {
        tpurchase::where('Sale_inv_no', $request->delete_purc2)->update(['status' => '0']);
        return redirect()->route('all-purchases2');
    }

    public function show(string $id)
    {
        $pur = tpurchase::where('Sale_inv_no',$id)
                ->join('ac as acc_name','tpurchase.account_name','=','acc_name.ac_code')
                ->join('ac as dispt_to','tpurchase.Cash_pur_name_ac','=','dispt_to.ac_code')
                ->select('tpurchase.*','dispt_to.ac_name as disp_to','acc_name.ac_name as ac_name', 
                'acc_name.address as address', 'acc_name.phone_no as phone_no')
                ->first();

        $pur2 = tpurchase_2::where('sales_inv_cod',$id)
                ->join('item_entry as ie','tpurchase_2.item_cod','=','ie.it_cod')
                ->select('tpurchase_2.*','ie.item_name')
                ->get();

        return view('purchase2.view',compact('pur','pur2'));
    }

    public function getAttachements(Request $request)
    {
        $pur2_atts = pur2_att::where('pur2_id', $request->id)->get();
        
        return $pur2_atts;
    }

    public function deleteAtt($id)
    {
        $doc=pur2_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $pur2_att = pur2_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=pur2_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=pur2_att::where('att_id', $id)->select('att_path')->first();
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

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Invoice-'.$purchase['pur_id']);
        $pdf->SetSubject('Invoice-'.$purchase['pur_id']);
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
        $html .= '<td>Invoice No: <span style="text-decoration: underline;">'.$purchase['pur_id'].'</span></td>';
        $html .= '<td>Date: '.\Carbon\Carbon::parse($purchase['pur_date'])->format('d-m-y').'</td>';
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
        $pdf->MultiCell(35, 5, $purchase['pur_labor_char'], 1, 'R');
        $pdf->SetXY(160, $currentY+23.5);
        $pdf->MultiCell(35, 5, $purchase['pur_convance_char'], 1, 'R');
        $pdf->SetXY(160, $currentY+30.18);
        $pdf->MultiCell(35, 5, $purchase['pur_discount'], 1, 'R');
        $pdf->SetXY(160, $currentY+36.86);
        $net_amount=round($total_amount+$purchase['pur_labor_char']+$purchase['pur_convance_char']-$purchase['pur_discount']);
        $pdf->MultiCell(35, 5,  $net_amount, 1, 'R');
        
        // Close and output PDF
        $pdf->Output('invoice_'.$purchase['pur_id'].'.pdf', 'I');
    }
}
