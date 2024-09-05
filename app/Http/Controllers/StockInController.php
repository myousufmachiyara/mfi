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
use App\Models\stock_in;
use App\Models\stock_in_2;
use App\Models\stock_in_att;


class StockInController extends Controller
{

    use SaveImage;

    public function index()
    {
        $stock_in = stock_in::where('stock_in.status', 1)
        ->leftjoin ('stock_in_2', 'stock_in_2.sales_inv_cod' , '=', 'stock_in.Sal_inv_no')
        ->join('ac','stock_in.account_name','=','ac.ac_code')
        ->select(
            'stock_in.Sal_inv_no','stock_in.sa_date','stock_in.Cash_pur_name','stock_in.Sales_remarks','ac.ac_name',
            'stock_in.pur_inv', 'stock_in.mill_gate', 'stock_in.transporter','stock_in.Cash_pur_address','stock_in.prefix',
            \DB::raw('SUM(stock_in_2.Sales_qty) as qty_sum'),
            \DB::raw('SUM(stock_in_2.Sales_qty*stock_in_2.weight_pc) as weight_sum'),
        )
        ->groupby('stock_in.Sal_inv_no','stock_in.sa_date','stock_in.Cash_pur_name','stock_in.Sales_remarks','ac.ac_name',
        'stock_in.pur_inv', 'stock_in.mill_gate', 'stock_in.transporter','stock_in.Cash_pur_address','stock_in.prefix' )
        ->get();

        return view('stock_in.index',compact('stock_in'));
    }

    public function destroy(Request $request)
    {
        $stock_in = stock_in::where('Sal_inv_no', $request->invoice_id)->update(['status' => '0']);
        return redirect()->route('all-tstock-in');
    }

    public function create(Request $request)
    {
        $items = Item_entry::orderBy('item_name', 'asc')->get();
        $coa = AC::orderBy('ac_name', 'asc')->get();

        return view('stock_in.create',compact('items','coa'));
    }

    public function store(Request $request)
    {
        $userId=1;
        $stock_in = new stock_in();


        // $stock_in->Sal_inv_no;
        if ($request->has('date') && $request->date) {
            $stock_in->sa_date=$request->date;
        }

        if ($request->has('remarks') && $request->remarks OR empty($request->remarks) ) {
            $stock_in->Sales_remarks=$request->remarks;
        }

        if ($request->has('Cash_pur_name') && $request->Cash_pur_name OR empty($request->Cash_pur_name) ) {
            $stock_in->Cash_pur_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_pur_address') && $request->cash_pur_address OR empty($request->cash_pur_address) ) {
            $stock_in->cash_Pur_address=$request->cash_pur_address;
        }
        if ($request->has('account_name') && $request->account_name) {
            $stock_in->account_name=$request->account_name;
        }

        $stock_in->created_by=$userId;
        $stock_in->status=1;

        $stock_in->save();

        $latest_invoice = stock_in::latest()->first();
        $invoice_id = $latest_invoice['Sal_inv_no'];

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_code[$i]))
                {
                    $stock_in_2 = new stock_in_2();
                    $stock_in_2->sales_inv_cod=$invoice_id;
                    $stock_in_2->item_cod=$request->item_code[$i];
                    if ($request->item_remarks[$i]!=null OR empty($request->item_remarks[$i])) {
                        $stock_in_2->remarks=$request->item_remarks[$i];
                    }
                    $stock_in_2->Sales_qty=$request->qty[$i];
                    $stock_in_2->weight_pc=$request->weight[$i];
    
                    $stock_in_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $stock_in_att = new stock_in_att();
                $stock_in_att->stock_in_id = $invoice_id;
                $extension = $file->getClientOriginalExtension();
                $stock_in_att->att_path = $this->StockInDoc($file,$extension);
                $stock_in_att->save();
            }
        }

        return redirect()->route('all-stock-in');
    }

    // public function show(string $id)
    // {
    //     $stock_in = stock_in::where('Sal_inv_no',$id)
    //                     ->join('ac','stock_in.account_name','=','ac.ac_code')
    //                     ->first();

    //     $stock_in_items = stock_in_2::where('sales_inv_cod',$id)
    //                     ->join('Item_entry','stock_in_2.item_cod','=','Item_entry.it_cod')
    //                     ->get();
    //     return view('stock_in.view',compact('stock_in','stock_in_items'));
    // }

    public function edit($id)
    {
        $tstock_in = stock_in::where('Sal_inv_no',$id)->first();
        $tstock_in_items = stock_in_2::where('sales_inv_cod',$id)->get();

        $items = Item_entry::orderBy('item_name', 'asc')->get();
        $coa = AC::orderBy('ac_name', 'asc')->get();

        return view('stock_in.edit', compact('tstock_in','tstock_in_items','items','coa'));
    }

    public function update(Request $request)
    {
        $stock_in = stock_in::where('Sal_inv_no',$request->invoice_no)->get()->first();

        if ($request->has('date') && $request->date) {
            $stock_in->sa_date=$request->date;
        }

        if ($request->has('remarks') && $request->remarks) {
            $stock_in->Sales_remarks=$request->remarks;
        }

        if ($request->has('Cash_pur_name') && $request->Cash_pur_name) {
            $stock_in->Cash_pur_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_pur_address') && $request->cash_pur_address) {
            $stock_in->cash_Pur_address=$request->cash_pur_address;
        }
        if ($request->has('account_name') && $request->account_name) {
            $stock_in->account_name=$request->account_name;
        }

        stock_in::where('Sal_inv_no', $request->invoice_no)->update([
            'sa_date'=>$stock_in->sa_date,
            'Sales_remarks'=>$stock_in->Sales_remarks,
            'cash_Pur_address'=>$stock_in->cash_Pur_address,
            'Cash_pur_name'=>$stock_in->Cash_pur_name,
            'account_name'=>$stock_in->account_name,
        ]);
        
        stock_in_2::where('sales_inv_cod', $request->invoice_no)->delete();
        
        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_code[$i]))
                {
                    $stock_in_2 = new stock_in_2();
                    $stock_in_2->sales_inv_cod=$request->invoice_no;
                    $stock_in_2->item_cod=$request->item_code[$i];
                    $stock_in_2->remarks=$request->item_remarks[$i];
                    $stock_in_2->Sales_qty=$request->qty[$i];
                    $stock_in_2->weight_pc=$request->weight[$i];
    
                    $stock_in_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $stock_in_att = new stock_in_att();
                $stock_in_att->stock_in_id = $request->invoice_no;
                $extension = $file->getClientOriginalExtension();
                $stock_in_att->att_path = $this->StockInDoc($file,$extension);
                $stock_in_att->save();
            }
        }
        

        return redirect()->route('all-stock-in');
    }

    public function getAttachements(Request $request)
    {
        $stock_in_att = stock_in_att::where('stock_in_id', $request->id)->get();
        
        return $stock_in_att;
    }

    public function deleteAtt($id)
    {
        $doc=stock_in_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $stock_in_att = stock_in_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=stock_in_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=stock_in_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }
}