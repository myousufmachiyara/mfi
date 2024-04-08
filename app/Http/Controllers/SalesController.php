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
                    $sales_2->Sales_qty=$request->item_weight[$i];
                    $sales_2->sales_price=$request->item_price[$i];
                    $sales_2->Sales_qty2=$request->item_qty[$i];
    
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
        $sales = new Sales();

        if ($request->has('date') && $request->date) {
            $sales['sa_date']=$request->date;
        }
        if ($request->has('bill_no') && $request->bill_no) {
            $sales['pur_ord_no']=$request->bill_no;
        }
        if ($request->has('remarks') && $request->remarks) {
            $sales['Sales_remarks']=$request->remarks;
        }
        if ($request->has('labour_charges') && $request->labour_charges) {
            $sales['LaborCharges']=$request->labour_charges;
        }
        if ($request->has('gst') && $request->gst) {
            $sales['Gst_sal']=$request->gst;
        }
        if ($request->has('convance_charges') && $request->convance_charges) {
            $sales['ConvanceCharges']=$request->convance_charges;
        }
        if ($request->has('nop') && $request->nop) {
            $sales['Cash_pur_name']=$request->nop;
        }
        if ($request->has('address') && $request->address) {
            $sales['cash_Pur_address']=$request->address;
        }
        if ($request->has('cash_pur_phone') && $request->cash_pur_phone) {
            $sales['cash_pur_phone']=$request->cash_pur_phone;
        }
        if ($request->has('bill_discount') && $request->bill_discount) {
            $sales['Bill_discount']=$request->bill_discount;
        }
        if ($request->has('account_name') && $request->account_name) {
            $sales['account_name']=$request->account_name;
        }
        if ($request->has('bill_status') && $request->bill_status) {
            $sales['bill_not']=$request->bill_status;
        }
        if ($request->has('totalAmount') && $request->totalAmount) {
            $sales['sed_sal']=$request->totalAmount;
        }
        if($request->hasFile('att')){
            $extension = $request->file('att')->getClientOriginalExtension();
            $sales['att'] = $this->salesDoc($request->file('att'),$extension);
        }
        // Sales::where('Sal_inv_no', $id)->update($sales);
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
                    $sales_2->Sales_qty=$request->item_weight[$i];
                    $sales_2->sales_price=$request->item_price[$i];
                    $sales_2->Sales_qty2=$request->item_qty[$i];
    
                    $sales_2->save();
                }
            }
        }
    }

    public function destroy(Request $request)
    {
        $sales = Sales::where('Sal_inv_no', $request->invoice_id)->update(['status' => '0']);
        return redirect()->route('all-saleinvoices');
    }

    public function generatePDF()
    {
        $pdf = new TCPDF();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Invoice Title');
        $pdf->SetSubject('Invoice');
        $pdf->SetKeywords('Invoice, TCPDF, PDF');
                
        // Set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                
        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Add a page
        $pdf->AddPage();
        
        // Set some content to display
        // Set some content to display with styling
        $html = '
            <h1 style="color: #FF0000; text-align: center;">Styled PDF Example</h1>
            <p style="font-weight: bold;">This is a paragraph with bold text.</p>
            <p style="font-style: italic;">This is a paragraph with italic text.</p>
            <p style="text-decoration: underline;">This is a paragraph with underlined text.</p>
            <p style="text-decoration: line-through;">This is a paragraph with strike-through text.</p>
            <p style="color: blue;">This is a paragraph with blue text.</p>
            <div style="background-color: #FFFF00; padding: 10px;">
                <p>This is a paragraph with yellow background color and padding.</p>
            </div>
            <table border="1" style="border-collapse: collapse;">
                <tr>
                    <th style="border: 1px solid #000000; padding: 5px;">Header 1</th>
                    <th style="border: 1px solid #000000; padding: 5px;">Header 2</th>
                </tr>
                <tr>
                    <td style="border: 1px solid #000000; padding: 5px;">Cell 1</td>
                    <td style="border: 1px solid #000000; padding: 5px;">Cell 2</td>
                </tr>
            </table>
        ';

        // Output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF
        $pdf->Output('invoice.pdf', 'I');
    }

    public function downloadPDF()
    {
        $pdf = new TCPDF();

        $pdf->SetCreator('Your Company');
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Invoice');
        $pdf->SetSubject('Invoice');
        $pdf->SetKeywords('Invoice, PDF');

        // Add a page
        $pdf->AddPage();

        // Set some content to display
        $content = '<table><tr><th>Company</th><th>Contact</th><th>Country</th></tr><tr><td>Alfreds Futterkiste</td><td>Maria Anders</td><td>Germany</td></tr></table>';

        // Output the content
        $pdf->writeHTML($content, true, false, true, false, '');

        // Close and output PDF
        $pdf->Output('invoice.pdf', 'D');
    }
    
}
