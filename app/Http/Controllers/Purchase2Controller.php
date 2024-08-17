<?php

namespace App\Http\Controllers;

use TCPDF;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
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
    public function index()
    {
        $pur1 = tpurchase::where('tpurchase.status',1)
        ->join ('tpurchase_2', 'tpurchase_2.sales_inv_cod' , '=', 'tpurchase.Sale_inv_no')
        ->join('ac', 'ac.ac_code', '=', 'tpurchase.account_name')
        ->select(
            'tpurchase.Sale_inv_no','tpurchase.sa_date','tpurchase.Cash_pur_name','tpurchase.Sales_Remarks','ac.ac_name',
            'pur_ord_no', 'tpurchase.ConvanceCharges', 'tpurchase.LaborCharges','tpurchase.Bill_discount',
            \DB::raw('SUM(tpurchase_2.Sales_qty) as weight_sum'),
            \DB::raw('SUM(tpurchase_2.Sales_qty*tpurchase_2.sales_price) as total_bill'),
        )
        ->groupby('tpurchase.Sale_inv_no','tpurchase.sa_date','tpurchase.Cash_pur_name','tpurchase.Sales_Remarks','ac.ac_name',
            'pur_ord_no', 'tpurchase.ConvanceCharges', 'tpurchase.LaborCharges','tpurchase.Bill_discount')
        ->get();
        
        return view('purchase2.index',compact('pur1'));
    }

    public function create(Request $request)
    {
        $items = Item_entry2::all();
        $coa = AC::all();
        return view('purchase2.create',compact('items','coa'));
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
        if ($request->has('account_name') && $request->account_name) {
            $pur2->Cash_pur_name_ac=$request->account_name;
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
                    if ($request->pur2_qty[$i]!=null) {
                        $tpurchase_2->weight_pc=$request->pur2_qty[$i];
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
                    
                    $purchase_2->save();
                }
            }
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

    
}
