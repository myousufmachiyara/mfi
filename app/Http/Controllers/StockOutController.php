<?php

namespace App\Http\Controllers;
use TCPDF;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Traits\SaveImage;
use App\Models\AC;
use App\Models\Item_entry;
use App\Models\stock_out;
use App\Models\stock_out_2;
use App\Models\stock_out_att;


class StockOutController extends Controller
{

    use SaveImage;

    public function index()
    {
        $stock_out = stock_out::where('stock_out.status', 1)
        ->leftjoin ('stock_out_2', 'stock_out_2.sales_inv_cod' , '=', 'stock_out.Sal_inv_no')
        ->join('ac','stock_out.account_name','=','ac.ac_code')
        ->select(
            'stock_out.Sal_inv_no','stock_out.sa_date','stock_out.Cash_pur_name','stock_out.Sales_remarks','ac.ac_name',
            'stock_out.pur_inv', 'stock_out.mill_gate', 'stock_out.transporter','stock_out.Cash_pur_address','stock_out.prefix',
            \DB::raw('SUM(stock_out_2.Sales_qty) as qty_sum'),
            \DB::raw('SUM(stock_out_2.weight_pc) as weight_sum'),
        )
        ->groupby('stock_out.Sal_inv_no','stock_out.sa_date','stock_out.Cash_pur_name','stock_out.Sales_remarks','ac.ac_name',
        'stock_out.pur_inv', 'stock_out.mill_gate', 'stock_out.transporter','stock_out.Cash_pur_address','stock_out.prefix' )
        ->get();

        return view('stock_out.index',compact('stock_out'));
    }

    public function destroy(Request $request)
    {
        $stock_out = stock_out::where('Sal_inv_no', $request->invoice_id)->update(['status' => '0']);
        return redirect()->route('all-stock-out');
    }

    public function create(Request $request)
    {
        $items = Item_entry::orderBy('item_name', 'asc')->get();
        $coa = AC::orderBy('ac_name', 'asc')->get();

        return view('stock_out.create',compact('items','coa'));
    }

    public function store(Request $request)
    {
        $userId=1;
        $stock_out = new stock_out();


        // $stock_out->Sal_inv_no;
        if ($request->has('date') && $request->date) {
            $stock_out->sa_date=$request->date;
        }

        if ($request->has('remarks') && $request->remarks OR empty($request->remarks) ) {
            $stock_out->Sales_remarks=$request->remarks;
        }

        if ($request->has('Cash_pur_name') && $request->Cash_pur_name OR empty($request->Cash_pur_name) ) {
            $stock_out->Cash_pur_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_pur_address') && $request->cash_pur_address OR empty($request->cash_pur_address) ) {
            $stock_out->cash_Pur_address=$request->cash_pur_address;
        }
        if ($request->has('account_name') && $request->account_name) {
            $stock_out->account_name=$request->account_name;
        }

        $stock_out->created_by=$userId;
        $stock_out->status=1;

        $stock_out->save();

        $latest_invoice = stock_out::latest()->first();
        $invoice_id = $latest_invoice['Sal_inv_no'];

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_code[$i]))
                {
                    $stock_out_2 = new stock_out_2();
                    $stock_out_2->sales_inv_cod=$invoice_id;
                    $stock_out_2->item_cod=$request->item_code[$i];
                    if ($request->item_remarks[$i]!=null OR empty($request->item_remarks[$i])) {
                        $stock_out_2->remarks=$request->item_remarks[$i];
                    }
                    $stock_out_2->Sales_qty=$request->qty[$i];
                    $stock_out_2->weight_pc=$request->weight[$i];
    
                    $stock_out_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $stock_out_att = new stock_out_att();
                $stock_out_att->stock_out_id = $invoice_id;
                $extension = $file->getClientOriginalExtension();
                $stock_out_att->att_path = $this->StockOutDoc($file,$extension);
                $stock_out_att->save();
            }
        }

        return redirect()->route('all-stock-out');
    }

    // public function show(string $id)
    // {
    //     $stock_out = stock_out::where('Sal_inv_no',$id)
    //                     ->join('ac','stock_out.account_name','=','ac.ac_code')
    //                     ->first();

    //     $stock_out_items = stock_out_2::where('sales_inv_cod',$id)
    //                     ->join('Item_entry','stock_out_2.item_cod','=','Item_entry.it_cod')
    //                     ->get();
    //     return view('stock_out.view',compact('stock_out','stock_out_items'));
    // }

    public function edit($id)
    {
        $tstock_in = stock_out::where('Sal_inv_no',$id)->first();
        $tstock_in_items = stock_out_2::where('sales_inv_cod',$id)->get();

        $items = Item_entry::orderBy('item_name', 'asc')->get();
        $coa = AC::orderBy('ac_name', 'asc')->get();

        return view('stock_out.edit', compact('tstock_in','tstock_in_items','items','coa'));
    }

    public function update(Request $request)
    {
        $stock_out = stock_out::where('Sal_inv_no',$request->invoice_no)->get()->first();

        if ($request->has('date') && $request->date) {
            $stock_out->sa_date=$request->date;
        }

        if ($request->has('remarks') && $request->remarks) {
            $stock_out->Sales_remarks=$request->remarks;
        }

        if ($request->has('Cash_pur_name') && $request->Cash_pur_name) {
            $stock_out->Cash_pur_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_pur_address') && $request->cash_pur_address) {
            $stock_out->cash_Pur_address=$request->cash_pur_address;
        }
        if ($request->has('account_name') && $request->account_name) {
            $stock_out->account_name=$request->account_name;
        }

        stock_out::where('Sal_inv_no', $request->invoice_no)->update([
            'sa_date'=>$stock_out->sa_date,
            'Sales_remarks'=>$stock_out->Sales_remarks,
            'cash_Pur_address'=>$stock_out->cash_Pur_address,
            'Cash_pur_name'=>$stock_out->Cash_pur_name,
            'account_name'=>$stock_out->account_name,
        ]);
        
        stock_out_2::where('sales_inv_cod', $request->invoice_no)->delete();
        
        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_code[$i]))
                {
                    $stock_out_2 = new stock_out_2();
                    $stock_out_2->sales_inv_cod=$request->invoice_no;
                    $stock_out_2->item_cod=$request->item_code[$i];
                    $stock_out_2->remarks=$request->item_remarks[$i];
                    $stock_out_2->Sales_qty=$request->qty[$i];
                    $stock_out_2->weight_pc=$request->weight[$i];
    
                    $stock_out_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $stock_out_att = new stock_out_att();
                $stock_out_att->stock_out_id = $request->invoice_no;
                $extension = $file->getClientOriginalExtension();
                $stock_out_att->att_path = $this->StockOutDoc($file,$extension);
                $stock_out_att->save();
            }
        }
        

        return redirect()->route('all-stock-out');
    }

    public function getAttachements(Request $request)
    {
        $stock_out_att = stock_out_att::where('stock_out_id', $request->id)->get();
        
        return $stock_out_att;
    }

    public function deleteAtt($id)
    {
        $doc=stock_out_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $stock_out_att = stock_out_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=stock_out_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=stock_out_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }
}