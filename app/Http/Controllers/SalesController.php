<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\AC;
use App\Models\Item_entry;
use App\Models\Sales;
use App\Models\Sales_2;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sales::all();
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
        // $userId = Auth::id();
        $userId=1;
        // $valid =  Validator::make($request, [

        // ]);

        $sales = new Sales();

        // $sales->Sal_inv_no;
        $sales->sa_date=$request->date;
        $sales->bill_not=$request->bill_status;
        $sales->account_name=$request->account_name;
        $sales->att=null;
        $sales->Bill_discount=$request->bill_discount;
        $sales->cash_pur_phone=$request->cash_pur_phone;
        $sales->cash_Pur_address=$request->address;
        $sales->Cash_pur_name=$request->nop;
        $sales->ConvanceCharges=$request->convance_charges;
        $sales->created_by=$userId;
        $sales->Gst_sal=$request->gst;
        $sales->LaborCharges=$request->labour_charges;
        $sales->pur_ord_no=$request->bill_no;
        $sales->Sales_remarks=$request->remarks;
        $sales->sed_sal=0;
        $sales->status=1;

        $sales->save();

        $latest_invoice = Sales::latest()->first();
        $invoice_id = $latest_invoice['Sal_inv_no'];

        if($request->has('items'))
        {
            for($i=0;$i<=$request->items;$i++)
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
        return redirect()->route('all-saleinvoices');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
    
    public function printInvoice()
    {
        return view('sales.print');    
    }
}
