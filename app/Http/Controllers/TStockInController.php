<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Traits\SaveImage;
use App\Models\AC;
use App\Models\Item_entry2;
use App\Models\TStock_In;
use App\Models\TStock_In_2;
use App\Models\TStock_In_att;
use TCPDF;


class TStockInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SaveImage;

    public function index()
    {
        $tstock_in = tstock_in::where('tstock_in.status', 1)
        ->join ('tstock_in_2', 'tstock_in_2.sales_inv_cod' , '=', 'tstock_in.Sal_inv_no')
        ->join('ac','tstock_in.account_name','=','ac.ac_code')
        ->select(
            'tstock_in.Sal_inv_no','tstock_in.sa_date','tstock_in.Cash_pur_name','tstock_in.Sales_remarks','ac.ac_name',
            'tstock_in.pur_inv','tstock_in.mill_gate','tstock_in.transporter',
            \DB::raw('SUM(tstock_in_2.sales_qty) as qty_sum'),
        )
        ->groupby('tstock_in.Sal_inv_no','tstock_in.sa_date','tstock_in.Cash_pur_name','tstock_in.Sales_remarks','ac.ac_name',
        'tstock_in.pur_inv','tstock_in.mill_gate','tstock_in.transporter', )
        ->get();

        return view('tstock_in.index',compact('tstock_in'));
    }

    public function create(Request $request)
    {
        $items = Item_entry2::all();
        $coa = AC::all();
        return view('tstock_in.create',compact('items','coa'));
    }

    public function store(Request $request)
    {
        $userId=1;
        $tstock_in = new tstock_in();

        // $tstock_in->Sal_inv_no;
        if ($request->has('date') && $request->date) {
            $tstock_in->sa_date=$request->date;
        }
        if ($request->has('pur_inv') && $request->pur_inv) {
            $tstock_in->pur_inv=$request->pur_inv;
        }
        if ($request->has('remarks') && $request->remarks) {
            $tstock_in->Sales_remarks=$request->remarks;
        }
        if ($request->has('nop') && $request->nop) {
            $tstock_in->Cash_pur_name=$request->nop;
        }
        if ($request->has('address') && $request->address) {
            $tstock_in->cash_Pur_address=$request->address;
        }
        if ($request->has('account_name') && $request->account_name) {
            $tstock_in->account_name=$request->account_name;
        }
   

        $tstock_in->created_by=$userId;
        $tstock_in->status=1;

        $tstock_in->save();

        $latest_invoice = tstock_in::latest()->first();
        $invoice_id = $latest_invoice['Sal_inv_no'];

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_code[$i]))
                {
                    $tstock_in_2 = new tstock_in_2();
                    $tstock_in_2->sales_inv_cod=$invoice_id;
                    $tstock_in_2->item_cod=$request->item_code[$i];
                    $tstock_in_2->remarks=$request->item_remarks[$i];
                    $tstock_in->Sales_qty2=$request->item_qty[$i];
    
                    $tstock_in_2->save();
                }
            }
        }
    }

    public function show(string $id)
    {
        $tstock_in = tstock_in::where('Sal_inv_no',$id)
                        ->join('ac','tstock_in.account_name','=','ac.ac_code')
                        ->first();

        $tstock_in_items = tstock_in_2::where('sales_inv_cod',$id)
                        ->join('item_entry2','tstock_in_2.item_cod','=','item_entry2.it_cod')
                        ->get();
        return view('tstock_in.view',compact('tstock_in','sale_items'));
    }

    public function edit($id)
    {
        $tstock_in = tstock_in::where('Sal_inv_no',$id)->first();
        $tstock_in_items = tstock_in_2::where('sales_inv_cod',$id)->get();
        $tstock_in_item_count=count($sale_items);
        $items = Item_entry2::all();
        $coa = AC::all();
        return view('tstock_in.edit', compact('tstock_in','tstock_in_items','items','coa','tstock_in_item_count'));
    }

    public function update(Request $request)
    {
        $tstock_in1 = tstock_in1::where('Sal_inv_no',$request->invoice_no)->get()->first();

        if ($request->has('date') && $request->date) {
            $tstock_in1->sa_date=$request->date;
        }
        if ($request->has('mill_gate') && $request->mill_gate) {
            $tstock_in1->mill_gate=$request->mill_gate;
        }
        if ($request->has('pur_inv') && $request->pur_inv) {
            $tstock_in1->pur_inv=$request->pur_inv;
        }
        if ($request->has('remarks') && $request->remarks) {
            $tstock_in1->Sales_remarks=$request->remarks;
        }
        if ($request->has('transporter') && $request->transporter) {
            $tstock_in1->transporter=$request->transporter;
        }
        if ($request->has('cash_pur_name') && $request->nop) {
            $tstock_in1->Cash_pur_name=$request->Cash_pur_name;
        }
        if ($request->has('address') && $request->address) {
            $tstock_in1->cash_Pur_address=$request->address;
        }
        if ($request->has('account_name') && $request->account_name) {
            $tstock_in1->account_name=$request->account_name;
        }

        tstock_in::where('Sal_inv_no', $request->invoice_no)->update([
            'account_name'=>$tstock_in1->account_name,
            'cash_Pur_address'=>$tstock_in1->cash_Pur_address,
            'Cash_pur_name'=>$tstock_in1->Cash_pur_name,
            'Sales_remarks'=>$tstock_in1->Sales_remarks,
            'sa_date'=>$tstock_in1->sa_date,
            'pur_inv'=>$tstock_in1->pur_inv,
            'mill_gate'=>$tstock_in1->mill_gate,
            'transporter'=>$tstock_in1->transporter,
        ]);
        
        tstock_in_2::where('sales_inv_cod', $request->invoice_no)->delete();
        
        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {

                if(filled($request->item_code[$i]))
                {
                    $tstock_in_2 = new tstock_in_2();
                    $tstock_in_2->sales_inv_cod=$request->invoice_no;
                    $tstock_in_2->item_cod=$request->item_code[$i];
                    $tstock_in_2->remarks=$request->item_remarks[$i];
                    $tstock_in_2->Sales_qty2=$request->item_qty[$i];
                    $tstock_in_2->sales_price=$request->item_price[$i];
                    $tstock_in_2->Sales_qty=$request->item_weight[$i];
                    $tstock_in_2->save();
                }
            }
        }



        return redirect()->route('all-tstock-in');
    }

    public function destroy(Request $request)
    {
        $tstock_in = tstock_in::where('Sal_inv_no', $request->invoice_id)->update(['status' => '0']);
        return redirect()->route('all-tstock-in');
    }




   }

