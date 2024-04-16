<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Traits\SaveImage;
use App\Models\AC;
use App\Models\Item_entry;
use App\Models\Sales;
use App\Models\Sales_2;
use TCPDF;


class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SaveImage;

    public function index()
    {
        $sales = Sales::where('sales.status', 1)
                        ->join('ac','sales.account_name','=','ac.ac_code')
                        ->get();
        return view('sales.index',compact('sales'));
    }

    public function create(Request $request)
    {
        $items = Item_entry::all();
        $coa = AC::all();
        return view('sales.create',compact('items','coa'));
    }

    public function store(Request $request)
    {
        $userId=1;
        $sales = new Sales();

        // $sales->Sal_inv_no;
        if ($request->has('date') && $request->date) {
            $sales->sa_date=$request->date;
        }
        if ($request->has('bill_no') && $request->bill_no) {
            $sales->pur_ord_no=$request->bill_no;
        }
        if ($request->has('remarks') && $request->remarks) {
            $sales->Sales_remarks=$request->remarks;
        }
        if ($request->has('labour_charges') && $request->labour_charges) {
            $sales->LaborCharges=$request->labour_charges;
        }
        if ($request->has('gst') && $request->gst) {
            $sales->Gst_sal=$request->gst;
        }
        if ($request->has('convance_charges') && $request->convance_charges) {
            $sales->ConvanceCharges=$request->convance_charges;
        }
        if ($request->has('nop') && $request->nop) {
            $sales->Cash_pur_name=$request->nop;
        }
        if ($request->has('address') && $request->address) {
            $sales->cash_Pur_address=$request->address;
        }
        if ($request->has('cash_pur_phone') && $request->cash_pur_phone) {
            $sales->cash_pur_phone=$request->cash_pur_phone;
        }
        if ($request->has('bill_discount') && $request->bill_discount) {
            $sales->Bill_discount=$request->bill_discount;
        }
        if ($request->has('account_name') && $request->account_name) {
            $sales->account_name=$request->account_name;
        }
        if ($request->has('bill_status') && $request->bill_status) {
            $sales->bill_not=$request->bill_status;
        }
        if ($request->has('totalAmount') && $request->totalAmount) {
            $sales->sed_sal=$request->totalAmount;
        }
        if($request->hasFile('att')){
            $extension = $request->file('att')->getClientOriginalExtension();
            $sales->att = $this->salesDoc($request->file('att'),$extension);
        }
        $sales->created_by=$userId;
        $sales->status=1;

        $sales->save();

        $latest_invoice = Sales::latest()->first();
        $invoice_id = $latest_invoice['Sal_inv_no'];

        if($request->has('items'))
        {
            for($i=0;$i<=$request->items;$i++)
            {
                if(filled($request->item_code[$i]))
                {
                    $sales_2 = new Sales_2();
                    $sales_2->sales_inv_cod=$invoice_id;
                    $sales_2->item_cod=$request->item_code[$i];
                    $sales_2->remarks=$request->item_remarks[$i];
                    $sales_2->Sales_qty=$request->item_qty[$i];
                    $sales_2->sales_price=$request->item_price[$i];
                    $sales_2->Sales_qty2=$request->item_weight[$i];
    
                    $sales_2->save();
                }
            }
        }
        return redirect()->route('all-saleinvoices');
    }

    public function show(string $id)
    {
        $sales = Sales::where('Sal_inv_no',$id)
                        ->join('ac','sales.account_name','=','ac.ac_code')
                        ->first();
        $sale_items = Sales_2::where('sales_inv_cod',$id)
                        ->join('item_entry','sales_2.item_cod','=','item_entry.it_cod')
                        ->get();
        return view('sales.view',compact('sales','sale_items'));
    }

    public function edit($id)
    {
        $sales = Sales::where('Sal_inv_no',$id)->first();
        $sale_items = Sales_2::where('sales_inv_cod',$id)->get();
        $sale_item_count=count($sale_items);
        $items = Item_entry::all();
        $coa = AC::all();
        return view('sales.edit', compact('sales','sale_items','items','coa','sale_item_count'));
    }

    public function update($id, Request $request)
    {
        $sales_update = new Sales();
        $sa_date= null;
        $pur_ord_no=null;
        $Sales_remarks=null; 
        $LaborCharges=null; 
        $Gst_sal=null; 
        $ConvanceCharges=null; 
        $Cash_pur_name=null; 
        $cash_Pur_address=null;
        $cash_pur_phone=null; 
        $Bill_discount=null; 
        $account_name=null; 
        $bill_not=null;
        $att=null;
        $sed_sal=null;

        if ($request->has('date') && $request->date) {
            $sa_date=$request->date;
        }
        if ($request->has('bill_no') && $request->bill_no) {
            $pur_ord_no=$request->bill_no;
        }
        if ($request->has('remarks') && $request->remarks) {
            $Sales_remarks=$request->remarks;
        }
        if ($request->has('labour_charges') && $request->labour_charges) {
            $LaborCharges=$request->labour_charges;
        }
        if ($request->has('gst') && $request->gst) {
            $Gst_sal=$request->gst;
        }
        if ($request->has('convance_charges') && $request->convance_charges) {
            $ConvanceCharges=$request->convance_charges;
        }
        if ($request->has('nop') && $request->nop) {
            $Cash_pur_name=$request->nop;
        }
        if ($request->has('address') && $request->address) {
            $cash_Pur_address=$request->address;
        }
        if ($request->has('cash_pur_phone') && $request->cash_pur_phone) {
            $cash_pur_phone=$request->cash_pur_phone;
        }
        if ($request->has('bill_discount') && $request->bill_discount) {
            $Bill_discount=$request->bill_discount;
        }
        if ($request->has('account_name') && $request->account_name) {
            $account_name=$request->account_name;
        }
        if ($request->has('bill_status') && $request->bill_status) {
            $bill_not=$request->bill_status;
        }
        if ($request->has('totalAmount') && $request->totalAmount) {
            $sed_sal= $request->totalAmount;
        }
        if($request->hasFile('att')){
            $extension = $request->file('att')->getClientOriginalExtension();
            $att = $this->salesDoc($request->file('att'),$extension);
        }
        Sales::where('Sal_inv_no', $id)->update([
            'sed_sal'=>$sed_sal,
            'bill_not'=>$bill_not,
            'account_name'=>$account_name,
            'Bill_discount'=>$Bill_discount,
            'cash_pur_phone'=>$cash_pur_phone,
            'cash_Pur_address'=>$cash_Pur_address,
            'Cash_pur_name'=>$Cash_pur_name,
            'ConvanceCharges'=>$ConvanceCharges,
            'Gst_sal'=>$Gst_sal,
            'LaborCharges'=>$LaborCharges,
            'Sales_remarks'=>$Sales_remarks,
            'sa_date'=>$sa_date,
            'pur_ord_no'=>$pur_ord_no,
            'att'=>$att
        ]);
        
        Sales_2::where('sales_inv_cod', $id)->delete();
        
        if($request->has('items'))
        {
            for($i=0;$i<=$request->items;$i++)
            {

                if(filled($request->item_code[$i]))
                {
                    $sales_2 = new Sales_2();
                    $sales_2->sales_inv_cod=$id;
                    $sales_2->item_cod=$request->item_code[$i];
                    $sales_2->remarks=$request->item_remarks[$i];
                    $sales_2->Sales_qty=$request->item_qty[$i];
                    $sales_2->sales_price=$request->item_price[$i];
                    $sales_2->Sales_qty2=$request->item_weight[$i];
    
                    $sales_2->save();
                }
            }
        }
        return redirect()->route('all-saleinvoices');
    }

    public function destroy(Request $request)
    {
        $sales = Sales::where('Sal_inv_no', $request->invoice_id)->update(['status' => '0']);
        return redirect()->route('all-saleinvoices');
    }

    public function generatePDF($id)
    {
        $sales = Sales::where('Sal_inv_no',$id)
        ->join('ac','sales.account_name','=','ac.ac_code')
        ->first();

        $sale_items = Sales_2::where('sales_inv_cod',$id)
                ->join('item_entry','sales_2.item_cod','=','item_entry.it_cod')
                ->get();

        $pdf = new TCPDF();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Invoice-'.$sales['Sal_inv_no']);
        $pdf->SetSubject('Invoice-'.$sales['Sal_inv_no']);
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

        $heading='<h1 style="text-align:center">Sales Invoice</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');


        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td>Invoice No: <span style="text-decoration: underline;">'.$sales['Sal_inv_no'].'</span></td>';
        $html .= '<td>Date: '.$sales['sa_date'].'</td>';
        $html .= '<td>pur_ord_no: '.$sales['pur_ord_no'].'</td>';
        $html .= '<td>Login: Hamza</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;">';
        $html .= '<tr>';
        $html .= '<td width="20%" style="border-right:1px dashed #000">Account Name </td>';
        $html .= '<td width="30%">'.$sales['ac_name'].'</td>';
        $html .= '<td width="20%">Name Of Person</td>';
        $html .= '<td width="30%">'.$sales['Cash_pur_name'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" >Address </td>';
        $html .= '<td width="30%">'.$sales['address'].'</td>';
        $html .= "<td width='20%'>Person's Address</td>";
        $html .= '<td width="30%">'.$sales['cash_Pur_address'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" >Phone </td>';
        $html .= '<td width="30%">'.$sales['phone_no'].'</td>';
        $html .= "<td width='20%'>Person's Phone</td>";
        $html .= '<td width="30%">'.$sales['cash_pur_phone'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" >Remarks </td>';
        $html .= '<td width="30%">'.$sales['remarks'].'</td>';
        $html .= "<td width='20%'>Previous Balance</td>";
        $html .= '<td width="30%"></td>';
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

        foreach ($sale_items as $items) {

            if($count%2==0)
            {
                $item_table .= '<tr style="background-color:#f1f1f1">';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000;border-left:1px dashed #000">'.$count.'</td>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000">'.$items['Sales_qty'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty'];
                $item_table .= '<td style="width:20%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['sales_price'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['Sales_qty2'].'</td>';
                $total_weight=$total_weight+$items['Sales_qty2'];
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['Sales_qty2']*$items['sales_price'].'</td>';
                $total_amount=$total_amount+($items['Sales_qty2']*$items['sales_price']);
                $item_table .= '</tr>';
            }
            else{
                $item_table .= '<tr>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000;border-left:1px dashed #000">'.$count.'</td>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000">'.$items['Sales_qty'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty'];
                $item_table .= '<td style="width:20%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['sales_price'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['Sales_qty2'].'</td>';
                $total_weight=$total_weight+$items['Sales_qty2'];
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['Sales_qty2']*$items['sales_price'].'</td>';
                $total_amount=$total_amount+($items['Sales_qty2']*$items['sales_price']);
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
        $pdf->MultiCell(40, 5, $sales['LaborCharges'], 1, 'R');
        $pdf->SetXY(240, $currentY+23.5);
        $pdf->MultiCell(40, 5, $sales['ConvanceCharges'], 1, 'R');
        $pdf->SetXY(240, $currentY+30.18);
        $pdf->MultiCell(40, 5, $sales['Bill_discount'], 1, 'R');
        $pdf->SetXY(240, $currentY+36.86);
        $net_amount=$total_amount+$sales['LaborCharges']+$sales['ConvanceCharges']-$sales['Bill_discount'];
        $pdf->MultiCell(40, 5,  $net_amount, 1, 'R');
        
        // Close and output PDF
        $pdf->Output('invoice_'.$sales['Sal_inv_no'].'.pdf', 'I');
    }

    public function downloadPDF($id)
    {
        $sales = Sales::where('Sal_inv_no',$id)
        ->join('ac','sales.account_name','=','ac.ac_code')
        ->first();

        $sale_items = Sales_2::where('sales_inv_cod',$id)
                ->join('item_entry','sales_2.item_cod','=','item_entry.it_cod')
                ->get();

        $pdf = new TCPDF();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Invoice-'.$sales['Sal_inv_no']);
        $pdf->SetSubject('Invoice-'.$sales['Sal_inv_no']);
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

        $heading='<h1 style="text-align:center">Sales Invoice</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');


        $html = '<table>';
        $html .= '<tr>';
        $html .= '<td>Invoice No: <span style="text-decoration: underline;">'.$sales['Sal_inv_no'].'</span></td>';
        $html .= '<td>Date: '.$sales['sa_date'].'</td>';
        $html .= '<td>pur_ord_no: '.$sales['pur_ord_no'].'</td>';
        $html .= '<td>Login: Hamza</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '<table border="1" style="border-collapse: collapse;">';
        $html .= '<tr>';
        $html .= '<td width="20%" style="border-right:1px dashed #000">Account Name </td>';
        $html .= '<td width="30%">'.$sales['ac_name'].'</td>';
        $html .= '<td width="20%">Name Of Person</td>';
        $html .= '<td width="30%">'.$sales['Cash_pur_name'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" >Address </td>';
        $html .= '<td width="30%">'.$sales['address'].'</td>';
        $html .= "<td width='20%'>Person's Address</td>";
        $html .= '<td width="30%">'.$sales['cash_Pur_address'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" >Phone </td>';
        $html .= '<td width="30%">'.$sales['phone_no'].'</td>';
        $html .= "<td width='20%'>Person's Phone</td>";
        $html .= '<td width="30%">'.$sales['cash_pur_phone'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" >Remarks </td>';
        $html .= '<td width="30%">'.$sales['remarks'].'</td>';
        $html .= "<td width='20%'>Previous Balance</td>";
        $html .= '<td width="30%"></td>';
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

        foreach ($sale_items as $items) {

            if($count%2==0)
            {
                $item_table .= '<tr style="background-color:#f1f1f1">';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000;border-left:1px dashed #000">'.$count.'</td>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000">'.$items['Sales_qty'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty'];
                $item_table .= '<td style="width:20%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['sales_price'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['Sales_qty2'].'</td>';
                $total_weight=$total_weight+$items['Sales_qty2'];
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['Sales_qty2']*$items['sales_price'].'</td>';
                $total_amount=$total_amount+($items['Sales_qty2']*$items['sales_price']);
                $item_table .= '</tr>';
            }
            else{
                $item_table .= '<tr>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000;border-left:1px dashed #000">'.$count.'</td>';
                $item_table .= '<td style="width:10%;border-right:1px dashed #000">'.$items['Sales_qty'].'</td>';
                $total_quantity=$total_quantity+$items['Sales_qty'];
                $item_table .= '<td style="width:20%;border-right:1px dashed #000">'.$items['item_name'].'</td>';
                $item_table .= '<td style="width:24%;border-right:1px dashed #000">'.$items['remarks'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['sales_price'].'</td>';
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['Sales_qty2'].'</td>';
                $total_weight=$total_weight+$items['Sales_qty2'];
                $item_table .= '<td style="width:12%;border-right:1px dashed #000">'.$items['Sales_qty2']*$items['sales_price'].'</td>';
                $total_amount=$total_amount+($items['Sales_qty2']*$items['sales_price']);
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
        $pdf->MultiCell(40, 5, $sales['LaborCharges'], 1, 'R');
        $pdf->SetXY(240, $currentY+23.5);
        $pdf->MultiCell(40, 5, $sales['ConvanceCharges'], 1, 'R');
        $pdf->SetXY(240, $currentY+30.18);
        $pdf->MultiCell(40, 5, $sales['Bill_discount'], 1, 'R');
        $pdf->SetXY(240, $currentY+36.86);
        $net_amount=$total_amount+$sales['LaborCharges']+$sales['ConvanceCharges']-$sales['Bill_discount'];
        $pdf->MultiCell(40, 5,  $net_amount, 1, 'R');

        // Close and output PDF
        $pdf->Output('invoice_'.$sales['Sal_inv_no'].'.pdf', 'D');
    }
    
}
