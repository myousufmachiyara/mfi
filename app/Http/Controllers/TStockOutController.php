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
use App\Models\TStock_Out;
use App\Models\TStock_Out_2;
use App\Models\TStock_Out_att;
use TCPDF;






class TStockOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SaveImage;

    public function index()
    {
        $tstock_out = TStock_Out::where('tstock_out.status', 1)
        ->join ('tstock_out_2', 'tstock_out_2.sales_inv_cod' , '=', 'tstock_out.Sal_inv_no')
        ->join('ac','tstock_out.account_name','=','ac.ac_code')
        ->select(
            'tstock_out.Sal_inv_no','tstock_out.sa_date','tstock_out.Cash_pur_name','tstock_out.Sales_remarks','ac.ac_name',
            'tstock_out.pur_inv', 'tstock_out.mill_gate', 'tstock_out.transporter','tstock_out.Cash_pur_address',
            \DB::raw('SUM(tstock_out_2.Sales_qty) as qty_sum'),
            \DB::raw('SUM(tstock_out_2.Sales_qty*tstock_out_2.weight_pc) as weight_sum'),
        )
        ->groupby('tstock_out.Sal_inv_no','tstock_out.sa_date','tstock_out.Cash_pur_name','tstock_out.Sales_remarks','ac.ac_name',
        'tstock_out.pur_inv', 'tstock_out.mill_gate', 'tstock_out.transporter','tstock_out.Cash_pur_address' )
        ->get();

        return view('tstock_out.index',compact('tstock_out'));
    }

    public function destroy(Request $request)
    {
        $tstock_out = TStock_Out::where('Sal_inv_no', $request->invoice_id)->update(['status' => '0']);
        return redirect()->route('all-tstock-out');
    }


    public function create(Request $request)
    {
        $items = Item_entry2::all();
        $coa = AC::all();
        return view('tstock_out.create',compact('items','coa'));
    }


}
