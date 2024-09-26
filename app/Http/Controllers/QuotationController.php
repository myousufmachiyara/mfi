<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Traits\SaveImage;
use App\Models\AC;
use App\Models\Item_entry;
use App\Models\quotation;
use App\Models\quotation_2;
use App\Models\quotation_att;
use TCPDF;


class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SaveImage;

    public function index()
    {
        $sales = quotation::where('quotation.status', 1)
        ->leftjoin ('quotation_2', 'quotation_2.sales_inv_cod' , '=', 'quotation.Sal_inv_no')
        ->join('ac','quotation.account_name','=','ac.ac_code')
        ->select(
            'quotation.Sal_inv_no','quotation.sa_date','quotation.Cash_pur_name','quotation.Sales_remarks','ac.ac_name',
            'quotation.pur_ord_no', 'quotation.ConvanceCharges', 'quotation.LaborCharges','quotation.Bill_discount', 'quotation.po', 'quotation.prefix',
            \DB::raw('SUM(quotation_2.Sales_qty) as weight_sum'),
            \DB::raw('SUM(quotation_2.Sales_qty*quotation_2.sales_price) as total_bill'),
        )
        ->groupby('quotation.Sal_inv_no','quotation.sa_date','quotation.Cash_pur_name','quotation.Sales_remarks','ac.ac_name',
        'quotation.pur_ord_no', 'quotation.ConvanceCharges', 'quotation.LaborCharges','quotation.Bill_discount','quotation.po','quotation.prefix' )
        ->get();

        return view('quotation.index',compact('sales'));
    }

    public function create(Request $request)
    {
        $items = Item_entry::orderBy('item_name', 'asc')->get();
        $coa = AC::orderBy('ac_name', 'asc')->get();

        return view('quotation.create',compact('items','coa'));
    }

    public function store(Request $request)
    {
        $userId=1;
        $sales = new quotation();

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
        if ($request->has('po') && $request->po) {
            $sales->po=$request->po;
        }
        if ($request->has('totalAmount') && $request->totalAmount) {
            $sales->sed_sal=$request->totalAmount;
        }

        $sales->created_by=$userId;
        $sales->status=1;

        $sales->save();

        $latest_invoice = quotation::latest()->first();
        $invoice_id = $latest_invoice['Sal_inv_no'];

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_code[$i]))
                {
                    $sales_2 = new quotation_2();
                    $sales_2->sales_inv_cod=$invoice_id;
                    $sales_2->item_cod=$request->item_code[$i];
                    $sales_2->remarks=$request->item_remarks[$i];
                    $sales_2->Sales_qty2=$request->item_qty[$i];
                    $sales_2->sales_price=$request->item_price[$i];
                    $sales_2->Sales_qty=$request->item_weight[$i];
    
                    $sales_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $sale1_att = new quotation_att();
                $sale1_att->sale1_id = $invoice_id;
                $extension = $file->getClientOriginalExtension();
                $sale1_att->att_path = $this->quotDoc($file,$extension);
                $sale1_att->save();
            }
        }
        return redirect()->route('all-quotation');
    }

    public function showNew(string $id)
    {
        $sales = quotation::where('Sal_inv_no',$id)
                        ->join('ac','quotation.account_name','=','ac.ac_code')
                        ->first();
        $sale_items = quotation_2::where('quotation_inv_cod',$id)
                        ->join('item_entry','quotation_2.item_cod','=','item_entry.it_cod')
                        ->select('quotation_2.*','item_entry.item_name')
                        ->get();
        
        return view('quotation.view',compact('quotation','sale_items'));
    }

    public function edit($id)
    {
        $sales = quotation::where('Sal_inv_no',$id)->first();
        $sale_items = quotation_2::where('sales_inv_cod',$id)->get();
        $sale_item_count=count($sale_items);
        $items = Item_entry::all();
        $coa = AC::all();
        return view('quotation.edit', compact('sales','sale_items','items','coa','sale_item_count'));
    }

    public function update(Request $request)
    {
        $sale1 = quotation::where('Sal_inv_no',$request->invoice_no)->get()->first();

        if ($request->has('date') && $request->date) {
            $sale1->sa_date=$request->date;
        }
        if ($request->has('bill_no') && $request->bill_no OR empty($request->bill_no)) {
            $sale1->pur_ord_no=$request->bill_no;
        }
        if ($request->has('remarks') && $request->remarks OR empty($request->remarks)) {
            $sale1->Sales_remarks=$request->remarks;
        }
        if ($request->has('labour_charges') && $request->labour_charges OR $request->labour_charges==0) {
            $sale1->LaborCharges=$request->labour_charges;
        }
        if ($request->has('convance_charges') && $request->convance_charges OR $request->convance_charges==0) {
            $sale1->ConvanceCharges=$request->convance_charges;
        }
        if ($request->has('nop') && $request->nop OR empty($request->nop)) {
            $sale1->Cash_pur_name=$request->nop;
        }
        if ($request->has('address') && $request->address OR empty($request->address))  {
            $sale1->cash_Pur_address=$request->address;
        }
        if ($request->has('cash_pur_phone') && $request->cash_pur_phone OR empty($request->cash_pur_phone)) {
            $sale1->cash_pur_phone=$request->cash_pur_phone;
        }
        if ($request->has('bill_discount') && $request->bill_discount OR $request->bill_discount==0) {
            $sale1->Bill_discount=$request->bill_discount;
        }
        if ($request->has('account_name') && $request->account_name) {
            $sale1->account_name=$request->account_name;
        }
        if ($request->has('po') && $request->po OR empty($request->po)) {
            $sale1->po=$request->po;
        }
        if ($request->has('totalAmount') && $request->totalAmount) {
            $sale1->sed_sal=$request->totalAmount;
        }
        quotation::where('Sal_inv_no', $request->invoice_no)->update([
            'sed_sal'=>$sale1->sed_sal,
            'po'=>$sale1->po,
            'account_name'=>$sale1->account_name,
            'Bill_discount'=>$sale1->Bill_discount,
            'cash_pur_phone'=>$sale1->cash_pur_phone,
            'cash_Pur_address'=>$sale1->cash_Pur_address,
            'Cash_pur_name'=>$sale1->Cash_pur_name,
            'ConvanceCharges'=>$sale1->ConvanceCharges,
            'LaborCharges'=>$sale1->LaborCharges,
            'Sales_remarks'=>$sale1->Sales_remarks,
            'sa_date'=>$sale1->sa_date,
            'pur_ord_no'=>$sale1->pur_ord_no,
        ]);
        
        quotation_2::where('sales_inv_cod', $request->invoice_no)->delete();
        
        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {

                if(filled($request->item_code[$i]))
                {
                    $sales_2 = new quotation_2();
                    $sales_2->sales_inv_cod=$request->invoice_no;
                    $sales_2->item_cod=$request->item_code[$i];
                    if ($request->item_remarks[$i]!=null OR empty($request->item_remarks[$i])) {
                        $sales_2->remarks=$request->item_remarks[$i];
                    }
                    $sales_2->Sales_qty2=$request->item_qty[$i];
                    $sales_2->sales_price=$request->item_price[$i];
                    $sales_2->Sales_qty=$request->item_weight[$i];
                    $sales_2->save();
                }
            }
        }

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $sale1_att = new quotation_att();
                $sale1_att->sale1_id = $request->invoice_no;
                $extension = $file->getClientOriginalExtension();
                $sale1_att->att_path = $this->quotDoc($file,$extension);
                $sale1_att->save();
            }
        }

        return redirect()->route('all-quotation');
    }

    public function destroy(Request $request)
    {
        $sales = quotation::where('Sal_inv_no', $request->invoice_id)->update(['status' => '0']);
        return redirect()->route('all-quotation');
    }

    public function deleteAtt($id)
    {
        $doc=sale1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $sale1_att = sale1_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=sale1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=sale1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }


    public function getAttachements(Request $request)
    {
        $sale1_att = sale1_att::where('sale1_id', $request->id)->get();
        
        return $sale1_att;
    }

}
