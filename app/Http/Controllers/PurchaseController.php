<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Item_entry;
use App\Models\AC;
use App\Models\purchase;
use App\Models\purchase_2;
use App\Models\pur1_att;
use Illuminate\Support\Facades\File;
use App\Traits\SaveImage;

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
            $pur1->pur_sale_inv=$request->pur_sale_inv;
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
        if ($request->has('pur_discount') && $request->pur_discount) {
            $pur1->pur_discount=$request->pur_discount;
        }
        if ($request->has('total_weight') && $request->total_weight) {
            $pur1->total_weight=$request->total_weight;
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

    public function destroy(Request $request)
    {
        $purc1 = purchase::where('pur_id', $request->delete_purc1)->update(['status' => '0']);
        return redirect()->route('all-purchases1');
    }

    public function getAttachements(Request $request)
    {
        $pur1_atts = pur1_att::where('pur1_id', $request->id)->get();
        return $pur1_atts;
    }
}
