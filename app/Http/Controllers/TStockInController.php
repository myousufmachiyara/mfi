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
use App\Models\tstock_in;
use App\Models\tstock_in_2;
use App\Models\tstock_in_att;
use App\Models\tpurchase;


class TStockInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SaveImage;

    public function index()
    {
        $tstock_in = tstock_in::where('tstock_in.status', 1)
        ->leftjoin ('tstock_in_2', 'tstock_in_2.sales_inv_cod' , '=', 'tstock_in.Sal_inv_no')
        ->join('ac','tstock_in.account_name','=','ac.ac_code')
        ->select(
            'tstock_in.Sal_inv_no','tstock_in.sa_date','tstock_in.Cash_pur_name','tstock_in.Sales_remarks','ac.ac_name',
            'tstock_in.pur_inv', 'tstock_in.mill_gate', 'tstock_in.transporter','tstock_in.Cash_pur_address','tstock_in.prefix','tstock_in.item_type',
            \DB::raw('SUM(tstock_in_2.Sales_qty) as qty_sum'),
            \DB::raw('SUM(tstock_in_2.Sales_qty*tstock_in_2.weight_pc) as weight_sum'),
        )
        ->groupby('tstock_in.Sal_inv_no','tstock_in.sa_date','tstock_in.Cash_pur_name','tstock_in.Sales_remarks','ac.ac_name',
        'tstock_in.pur_inv', 'tstock_in.mill_gate', 'tstock_in.transporter','tstock_in.Cash_pur_address','tstock_in.prefix','tstock_in.item_type' )
        ->get();

        return view('tstock_in.index',compact('tstock_in'));
    }

    public function destroy(Request $request)
    {
        $tstock_in = tstock_in::where('Sal_inv_no', $request->invoice_id)->update(['status' => '0']);
        return redirect()->route('all-tstock-in');
    }

    public function create(Request $request)
    {
        $items = Item_entry2::orderBy('item_name', 'asc')->get();
        $coa = AC::orderBy('ac_name', 'asc')->get();

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
        if ($request->has('pur_inv') && $request->pur_inv OR empty($request->pur_inv) ) {
            $tstock_in->pur_inv=$request->pur_inv;
        }
        if ($request->has('remarks') && $request->remarks OR empty($request->remarks) ) {
            $tstock_in->Sales_remarks=$request->remarks;
        }
        if ($request->has('mill_gate') && $request->mill_gate OR empty($request->mill_gate) ) {
            $tstock_in->mill_gate=$request->mill_gate;
        }
        if ($request->has('Cash_pur_name') && $request->Cash_pur_name OR empty($request->Cash_pur_name) ) {
            $tstock_in->Cash_pur_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_pur_address') && $request->cash_pur_address OR empty($request->cash_pur_address) ) {
            $tstock_in->cash_Pur_address=$request->cash_pur_address;
        }
        if ($request->has('transporter') && $request->transporter OR empty($request->transporter) ) {
            $tstock_in->transporter=$request->transporter;
        }
        
        if ($request->has('item_type') && $request->item_type OR empty($request->item_type) ) {
            $tstock_in->item_type=$request->item_type;
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
                    if ($request->item_remarks[$i]!=null OR empty($request->item_remarks[$i])) {
                        $tstock_in_2->remarks=$request->item_remarks[$i];
                    }
                    $tstock_in_2->Sales_qty=$request->qty[$i];
                    $tstock_in_2->weight_pc=$request->weight[$i];
    
                    $tstock_in_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $tstock_in_att = new tstock_in_att();
                $tstock_in_att->tstock_in_id = $invoice_id;
                $extension = $file->getClientOriginalExtension();
                $tstock_in_att->att_path = $this->tStockInDoc($file,$extension);
                $tstock_in_att->save();
            }
        }

        if($request->has('isInduced') && $request->isInduced == 1){

            $tpurchase = new tpurchase();
            $SalinducedID=$latest_invoice['Sal_inv_no'];
            $prefix=$latest_invoice['prefix'];
            $pur_inv = $prefix.''.$SalinducedID;
            $tpurchase->sales_against = $pur_inv;
            tpurchase::where('Sale_inv_no', $request->sale_against)->update([
                'sales_against'=>$tpurchase->sales_against,
            ]);
        } 

        return redirect()->route('all-tstock-in');
    }

    public function show(string $id)
    {
        $tstock_in = tstock_in::where('Sal_inv_no',$id)
                        ->join('ac','tstock_in.account_name','=','ac.ac_code')
                        ->first();

        $tstock_in_items = tstock_in_2::where('sales_inv_cod',$id)
                        ->join('item_entry2','tstock_in_2.item_cod','=','item_entry2.it_cod')
                        ->get();
        return view('tstock_in.view',compact('tstock_in','tstock_in_items'));
    }

    public function edit($id)
    {
        $tstock_in = tstock_in::where('Sal_inv_no',$id)->first();
        $tstock_in_items = tstock_in_2::where('sales_inv_cod',$id)->get();

        $items = Item_entry2::orderBy('item_name', 'asc')->get();
        $coa = AC::orderBy('ac_name', 'asc')->get();

        return view('tstock_in.edit', compact('tstock_in','tstock_in_items','items','coa'));
    }

    public function update(Request $request)
    {
        $tstock_in = tstock_in::where('Sal_inv_no',$request->invoice_no)->get()->first();

        if ($request->has('date') && $request->date) {
            $tstock_in->sa_date=$request->date;
        }
        if ($request->has('pur_inv') && $request->pur_inv) {
            $tstock_in->pur_inv=$request->pur_inv;
        }
        if ($request->has('remarks') && $request->remarks) {
            $tstock_in->Sales_remarks=$request->remarks;
        }
        if ($request->has('mill_gate') && $request->mill_gate) {
            $tstock_in->mill_gate=$request->mill_gate;
        }
        if ($request->has('Cash_pur_name') && $request->Cash_pur_name) {
            $tstock_in->Cash_pur_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_pur_address') && $request->cash_pur_address) {
            $tstock_in->cash_Pur_address=$request->cash_pur_address;
        }
        if ($request->has('transporter') && $request->transporter) {
            $tstock_in->transporter=$request->transporter;
        }
        if ($request->has('item_type') && $request->item_type) {
            $tstock_in->item_type=$request->item_type;
        }
        
        if ($request->has('account_name') && $request->account_name) {
            $tstock_in->account_name=$request->account_name;
        }

        tstock_in::where('Sal_inv_no', $request->invoice_no)->update([
            'sa_date'=>$tstock_in->sa_date,
            'pur_inv'=>$tstock_in->pur_inv,
            'mill_gate'=>$tstock_in->mill_gate,
            'Sales_remarks'=>$tstock_in->Sales_remarks,
            'cash_Pur_address'=>$tstock_in->cash_Pur_address,
            'Cash_pur_name'=>$tstock_in->Cash_pur_name,
            'transporter'=>$tstock_in->transporter,
            'account_name'=>$tstock_in->account_name,
            'item_type'=>$tstock_in->item_type,
    
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
                    $tstock_in_2->Sales_qty=$request->qty[$i];
                    $tstock_in_2->weight_pc=$request->weight[$i];
    
                    $tstock_in_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $tstock_in_att = new tstock_in_att();
                $tstock_in_att->tstock_in_id = $request->invoice_no;
                $extension = $file->getClientOriginalExtension();
                $tstock_in_att->att_path = $this->tStockInDoc($file,$extension);
                $tstock_in_att->save();
            }
        }
        

        return redirect()->route('all-tstock-in');
    }

    public function getAttachements(Request $request)
    {
        $tstock_in_att = tstock_in_att::where('tstock_in_id', $request->id)->get();
        
        return $tstock_in_att;
    }

    public function deleteAtt($id)
    {
        $doc=tstock_in_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $tstock_in_att = tstock_in_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=tstock_in_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=tstock_in_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }
}

