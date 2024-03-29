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
        if($request->hasFile('att')){
            $extension = $request->file('att')->getClientOriginalExtension();
            $sales->att = $this->salesDoc($request->file('att'),$extension);
        }
        $sales->created_by=$userId;
        $sales->sed_sal=0;
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
        //
    }

    public function edit($id)
    {
        $sales = Sales::find($id);
        $sale_items = Sales_2::where('sales_inv_cod',$id)->get();
        $items = Item_entry::all();
        $coa = AC::all();
        return view('sales.edit', compact('sales','sale_items','items','coa'));
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Request $request)
    {
        $sales = Sales::where('Sal_inv_no', $request->invoice_id)->update(['status' => '0']);
        return redirect()->route('all-saleinvoices');
    }
    
    public function viewInvoice()
    {
        return view('sales.view');    
    }
}
