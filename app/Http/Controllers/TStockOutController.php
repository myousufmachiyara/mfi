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
use App\Models\Item_entry2;
use App\Models\tstock_out;
use App\Models\tstock_out_2;
use App\Models\tstock_out_att;



class TStockOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SaveImage;

    public function index()
    {
        $tstock_out = tstock_out::where('tstock_out.status', 1)
        ->join ('tstock_out_2', 'tstock_out_2.sales_inv_cod' , '=', 'tstock_out.Sal_inv_no')
        ->join('ac','tstock_out.account_name','=','ac.ac_code')
        ->select(
            'tstock_out.Sal_inv_no','tstock_out.sa_date','tstock_out.cash_pur_name','tstock_out.Sales_remarks','ac.ac_name',
            'tstock_out.pur_inv', 'tstock_out.mill_gate', 'tstock_out.transporter','tstock_out.Cash_pur_address',
            \DB::raw('SUM(tstock_out_2.Sales_qty) as qty_sum'),
            \DB::raw('SUM(tstock_out_2.Sales_qty*tstock_out_2.weight_pc) as weight_sum'),
        )
        ->groupby('tstock_out.Sal_inv_no','tstock_out.sa_date','tstock_out.cash_pur_name','tstock_out.Sales_remarks','ac.ac_name',
        'tstock_out.pur_inv', 'tstock_out.mill_gate', 'tstock_out.transporter','tstock_out.Cash_pur_address' )
        ->get();

        return view('tstock_out.index',compact('tstock_out'));
    }

    public function destroy(Request $request)
    {
        $tstock_out = tstock_out::where('Sal_inv_no', $request->invoice_id)->update(['status' => '0']);
        return redirect()->route('all-tstock-out');
    }

    public function create(Request $request)
    {
        $items = Item_entry2::all();
        $coa = AC::all();
        return view('tstock_out.create',compact('items','coa'));
    }



    public function store(Request $request)
    {
        $userId=1;
        $tstock_out = new tstock_out();


        // $tstock_out->Sal_inv_no;
        if ($request->has('date') && $request->date) {
            $tstock_out->sa_date=$request->date;
        }
        if ($request->has('pur_inv') && $request->pur_inv) {
            $tstock_out->pur_inv=$request->pur_inv;
        }
        if ($request->has('remarks') && $request->remarks) {
            $tstock_out->Sales_remarks=$request->remarks;
        }
        if ($request->has('mill_gate') && $request->mill_gate) {
            $tstock_out->mill_gate=$request->mill_gate;
        }
        if ($request->has('cash_pur_name') && $request->cash_pur_name) {
            $tstock_out->cash_pur_name=$request->cash_pur_name;
        }
        if ($request->has('cash_pur_address') && $request->cash_pur_address) {
            $tstock_out->cash_Pur_address=$request->cash_pur_address;
        }
        if ($request->has('transporter') && $request->transporter) {
            $tstock_out->transporter=$request->transporter;
        }
        if ($request->has('account_name') && $request->account_name) {
            $tstock_out->account_name=$request->account_name;
        }

        $tstock_out->created_by=$userId;
        $tstock_out->status=1;

        $tstock_out->save();

        $latest_invoice = tstock_out::latest()->first();
        $invoice_id = $latest_invoice['Sal_inv_no'];

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_code[$i]))
                {
                    $tstock_out_2 = new tstock_out_2();
                    $tstock_out_2->sales_inv_cod=$invoice_id;
                    $tstock_out_2->item_cod=$request->item_code[$i];
                    $tstock_out_2->remarks=$request->item_remarks[$i];
                    $tstock_out_2->Sales_qty=$request->qty[$i];
                    $tstock_out_2->weight_pc=$request->weight[$i];
    
                    $tstock_out_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $tstock_out_att = new tstock_out_att();
                $tstock_out_att->tstock_out_id = $invoice_id;
                $extension = $file->getClientOriginalExtension();
                $tstock_out_att->att_path = $this->tStockOutDoc($file,$extension);
                $tstock_out_att->save();
            }
        }

        return redirect()->route('all-tstock-out');
    }

    public function show(string $id)
    {
        $tstock_out = tstock_out::where('Sal_inv_no',$id)
                        ->join('ac','tstock_out.account_name','=','ac.ac_code')
                        ->first();

        $tstock_out_items = tstock_out_2::where('sales_inv_cod',$id)
                        ->join('item_entry2','tstock_out_2.item_cod','=','item_entry2.it_cod')
                        ->get();
        return view('tstock_out.view',compact('tstock_out','tstock_out_items'));
    }

    public function edit($id)
    {
        $tstock_out = tstock_out::where('Sal_inv_no',$id)->first();
        $tstock_out_items = tstock_out_2::where('sales_inv_cod',$id)->get();
        $items = Item_entry2::all();
        $coa = AC::all();
        return view('tstock_out.edit', compact('tstock_out','tstock_out_items','items','coa'));
    }

    public function update(Request $request)
    {
        $tstock_out = tstock_out::where('Sal_inv_no',$request->invoice_no)->get()->first();

        if ($request->has('date') && $request->date) {
            $tstock_out->sa_date=$request->date;
        }
        if ($request->has('pur_inv') && $request->pur_inv) {
            $tstock_out->pur_inv=$request->pur_inv;
        }
        if ($request->has('remarks') && $request->remarks) {
            $tstock_out->Sales_remarks=$request->remarks;
        }
        if ($request->has('mill_gate') && $request->mill_gate) {
            $tstock_out->mill_gate=$request->mill_gate;
        }
        if ($request->has('cash_pur_name') && $request->cash_pur_name) {
            $tstock_out->cash_pur_name=$request->cash_pur_name;
        }
        if ($request->has('cash_pur_address') && $request->cash_pur_address) {
            $tstock_out->cash_Pur_address=$request->cash_pur_address;
        }
        if ($request->has('transporter') && $request->transporter) {
            $tstock_out->transporter=$request->transporter;
        }
        if ($request->has('account_name') && $request->account_name) {
            $tstock_out->account_name=$request->account_name;
        }

        tstock_out::where('Sal_inv_no', $request->invoice_no)->update([
            'sa_date'=>$tstock_out->sa_date,
            'pur_inv'=>$tstock_out->pur_inv,
            'mill_gate'=>$tstock_out->mill_gate,
            'Sales_remarks'=>$tstock_out->Sales_remarks,
            'cash_Pur_address'=>$tstock_out->cash_Pur_address,
            'cash_pur_name'=>$tstock_out->cash_pur_name,
            'transporter'=>$tstock_out->transporter,
            'account_name'=>$tstock_out->account_name,
    
        ]);
        
        tstock_out_2::where('sales_inv_cod', $request->invoice_no)->delete();
        
        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_code[$i]))
                {
                    $tstock_out_2 = new tstock_out_2();
                    $tstock_out_2->sales_inv_cod=$request->invoice_no;
                    $tstock_out_2->item_cod=$request->item_code[$i];
                    $tstock_out_2->remarks=$request->item_remarks[$i];
                    $tstock_out_2->Sales_qty=$request->qty[$i];
                    $tstock_out_2->weight_pc=$request->weight[$i];
    
                    $tstock_out_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $tstock_out_att = new tstock_out_att();
                $tstock_out_att->tstock_out_id = $request->invoice_no;
                $extension = $file->getClientOriginalExtension();
                $tstock_out_att->att_path = $this->tStockInDoc($file,$extension);
                $tstock_out_att->save();
            }
        }
        

        return redirect()->route('all-tstock-out');
    }

    public function getAttachements(Request $request)
    {
        $tstock_out_att = tstock_out_att::where('tstock_out_id', $request->id)->get();
        return $tstock_out_att;
    }

    public function deleteAtt($id)
    {
        $doc=tstock_out_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $tstock_out_att = tstock_out_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=tstock_out_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=tstock_out_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }
}
