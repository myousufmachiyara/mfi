<?php
namespace App\Http\Controllers;
use TCPDF;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
use App\Models\Item_Groups;
use App\Models\AC;
use App\Models\tquotation;
use App\Models\tquotation_2;
use App\Models\tquotation_att;
use App\Models\gd_pipe_item_stock9_much;
use Illuminate\Support\Facades\File;
use App\Traits\SaveImage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class TquotationController extends Controller
{
    use SaveImage;

    public function index()
    {
        $quot2 = tquotation::where('tquotation.status', 1)
            ->leftjoin('tquotation_2', 'tquotation_2.sales_inv_cod', '=', 'tquotation.Sale_inv_no')
            ->join('ac as acc_name', 'acc_name.ac_code', '=', 'tquotation.account_name')
            ->join('ac as disp_to', 'disp_to.ac_code', '=', 'tquotation.Cash_pur_name_ac')
            ->select(
                'tquotation.Sale_inv_no', 'tquotation.sa_date', 'acc_name.ac_name as acc_name', 'tquotation.pur_ord_no',
                'disp_to.ac_name as disp_to', 'tquotation.Cash_pur_name', 'tquotation.Sales_Remarks', 'tquotation.sales_against', 'tquotation.prefix', 'tquotation.tc',
                'tquotation.ConvanceCharges', 'tquotation.LaborCharges', 'tquotation.Bill_discount',
                \DB::raw('SUM(tquotation_2.weight_pc * tquotation_2.Sales_qty2) as weight_sum'),
                \DB::raw('SUM(((tquotation_2.Sales_qty2 * tquotation_2.sales_price) + ((tquotation_2.Sales_qty2 * tquotation_2.sales_price) * (tquotation_2.discount / 100))) * tquotation_2.length) as total_bill')
            )
            ->groupby(
                'tquotation.Sale_inv_no', 'tquotation.sa_date', 'acc_name.ac_name', 'tquotation.pur_ord_no',
                'disp_to.ac_name', 'tquotation.Cash_pur_name', 'tquotation.Sales_Remarks', 'tquotation.sales_against', 'tquotation.prefix', 'tquotation.tc',
                'tquotation.ConvanceCharges', 'tquotation.LaborCharges', 'tquotation.Bill_discount'
            )
            ->get();
    
        return view('tquotation.index', compact('quot2'));
    }
    
    public function create(Request $request)
    {
        $items = Item_entry2::all();
        $coa = AC::all();
        return view('tquotation.create',compact('items','coa'));
    }

    public function store(Request $request)
    {
        
        $quot2 = new tquotation();

        if ($request->has('sa_date') && $request->sa_date) {
            $quot2->sa_date=$request->sa_date;
        }
        if ($request->has('pur_ord_no') && $request->pur_ord_no) {
            $quot2->pur_ord_no=$request->pur_ord_no;
        }
        if ($request->has('isInduced') && $request->isInduced==1) {
            $quot2->sale_against=$request->sale_against;
        }
        if ($request->has('sales_against') && $request->sales_against) {
            $quot2->sales_against=$request->sales_against;
        }
        if ($request->has('account_name') && $request->account_name) {
            $quot2->account_name=$request->account_name;
        }
        if ($request->has('disp_account_name') && $request->disp_account_name) {
            $quot2->Cash_pur_name_ac=$request->disp_account_name;
        }
        if ($request->has('Cash_pur_name') && $request->Cash_pur_name) {
            $quot2->Cash_pur_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_Pur_address') && $request->cash_Pur_address) {
            $quot2->cash_Pur_address=$request->cash_Pur_address;
        }
        if ($request->has('Sales_Remarks') && $request->Sales_Remarks) {
            $quot2->Sales_Remarks=$request->Sales_Remarks;
        }
        if ($request->has('tc') && $request->tc) {
            $quot2->tc=$request->tc;
        }
        if ($request->has('ConvanceCharges') && $request->ConvanceCharges) {
            $quot2->ConvanceCharges=$request->ConvanceCharges;
        }
        if ($request->has('LaborCharges') && $request->LaborCharges) {
            $quot2->LaborCharges=$request->LaborCharges;
        }
        if ($request->has('Bill_discount') && $request->Bill_discount) {
            $quot2->Bill_discount=$request->Bill_discount;
        }

        $quot2->created_by = session('user_id');

        $quot2->save();

        $pur_2_id = tquotation::latest()->first();

        if($request->has('items'))
         {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $tquotation_2 = new tquotation_2();

                    $tquotation_2->sales_inv_cod=$pur_2_id['Sale_inv_no'];
                    $tquotation_2->item_cod=$request->item_cod[$i];

                    if ($request->remarks[$i]!=null) {
                        $tquotation_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur2_qty2[$i]!=null) {
                        $tquotation_2->Sales_qty2=$request->pur2_qty2[$i];
                    }
                    if ($request->pur2_per_unit[$i]!=null) {
                        $tquotation_2->sales_price=$request->pur2_per_unit[$i];
                    }
                    if ($request->weight_per_piece[$i]!=null) {
                        $tquotation_2->weight_pc=$request->weight_per_piece[$i];
                    }
                    if ($request->pur2_len[$i]!=null) {
                        $tquotation_2->length=$request->pur2_len[$i];
                    }
                    if ($request->pur2_price_date[$i]!=null) {
                        $tquotation_2->rat_dat=$request->pur2_price_date[$i];
                    }
                    if ($request->pur2_percentage[$i]!=null) {
                        $tquotation_2->discount=$request->pur2_percentage[$i];
                    }
                    
                    $tquotation_2->save();
                }
            }
         }     

         if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $pur2Att = new tquotation_att();
                $pur2Att->pur2_id = $pur_2_id['Sale_inv_no'];
                $extension = $file->getClientOriginalExtension();
                $pur2Att->att_path = $this->tquotDoc($file,$extension);
                $pur2Att->save();
            }
        }

        return redirect()->route('all-tquotation');
    }

    public function edit($id)
    {
        $items = Item_entry2::orderBy('item_name', 'asc')->get();
        $coa = AC::orderBy('ac_name', 'asc')->get();

        $pur2 = tquotation::where('tquotation.Sale_inv_no',$id)->first();
        $pur2_item = tquotation_2::where('tquotation_2.sales_inv_cod',$id)->get();

        return view('tquotation.edit',compact('pur2','pur2_item','items','coa'));
    }

    public function update(Request $request)
    {

        $pur2 = tquotation::where('Sale_inv_no',$request->pur2_id)->get()->first();

        if ($request->has('sa_date') && $request->sa_date) {
            $pur2->sa_date=$request->sa_date;
        }
        if ($request->has('pur_ord_no') && $request->pur_ord_no OR empty($request->pur_ord_no)) {
            $pur2->pur_ord_no=$request->pur_ord_no;
        }
        if ($request->has('sales_against') && $request->sales_against OR empty($request->sales_against)) {
            $pur2->sales_against=$request->sales_against;
        }
        if ($request->has('account_name') && $request->account_name) {
            $pur2->account_name=$request->account_name;
        }
        if ($request->has('disp_account_name') && $request->disp_account_name) {
            $pur2->Cash_pur_name_ac=$request->disp_account_name;
        }
        if ($request->has('Cash_pur_name') && $request->Cash_pur_name OR empty($request->Cash_pur_name)) {
            $pur2->Cash_pur_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_Pur_address') && $request->cash_Pur_address OR empty($request->cash_Pur_address)) {
            $pur2->cash_Pur_address=$request->cash_Pur_address;
        }
        if ($request->has('tc') && $request->tc OR empty($request->tc)) {
            $pur2->tc=$request->tc;
        }
        if ($request->has('Sales_Remarks') && $request->Sales_Remarks OR empty($request->Sales_Remarks)) {
            $pur2->Sales_Remarks=$request->Sales_Remarks;
        }
        if ($request->has('ConvanceCharges') && $request->ConvanceCharges OR $request->ConvanceCharges==0) {
            $pur2->ConvanceCharges=$request->ConvanceCharges;
        }
        if ($request->has('LaborCharges') && $request->LaborCharges OR $request->LaborCharges==0) {
            $pur2->LaborCharges=$request->LaborCharges;
        }
        if ($request->has('Bill_discount') && $request->Bill_discount OR $request->Bill_discount==0) {
            $pur2->Bill_discount=$request->Bill_discount;
        }

        tquotation::where('Sale_inv_no', $request->pur2_id)->update([
            'sa_date'=>$pur2->sa_date,
            'pur_ord_no'=>$pur2->pur_ord_no,
            'sales_against'=>$pur2->sales_against,
            'account_name'=>$pur2->account_name,
            'Cash_pur_name_ac'=>$pur2->Cash_pur_name_ac,
            'Cash_pur_name'=>$pur2->Cash_pur_name,
            'cash_Pur_address'=>$pur2->cash_Pur_address,
            'tc'=>$pur2->tc,
            'Sales_Remarks'=>$pur2->Sales_Remarks,
            'ConvanceCharges'=>$pur2->ConvanceCharges,
            'LaborCharges'=>$pur2->LaborCharges,
            'Bill_discount'=>$pur2->Bill_discount,
            'updated_by' => session('user_id'),
        ]);

        tquotation_2::where('sales_inv_cod', $request->pur2_id)->delete();

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $tquotation_2 = new tquotation_2();

                    $tquotation_2->sales_inv_cod=$request->pur2_id;
                    $tquotation_2->item_cod=$request->item_cod[$i];

                    if ($request->remarks[$i]!=null OR empty($request->remarks[$i])) {
                        $tquotation_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur2_qty2[$i]!=null) {
                        $tquotation_2->Sales_qty2=$request->pur2_qty2[$i];
                    }
                    if ($request->pur2_per_unit[$i]!=null) {
                        $tquotation_2->sales_price=$request->pur2_per_unit[$i];
                    }
                    if ($request->weight_per_piece[$i]!=null) {
                        $tquotation_2->weight_pc=$request->weight_per_piece[$i];
                    }
                    if ($request->pur2_len[$i]!=null) {
                        $tquotation_2->length=$request->pur2_len[$i];
                    }
                    if ($request->pur2_price_date[$i]!=null OR empty($request->pur2_price_date[$i])) {
                        $tquotation_2->rat_dat=$request->pur2_price_date[$i];
                    }
                    if ($request->pur2_percentage[$i]!=null) {
                        $tquotation_2->discount=$request->pur2_percentage[$i];
                    }
                    
                    $tquotation_2->save();
                }
            }
        }

       

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $pur2Att = new tquotation_att();
                $pur2Att->pur2_id = $request->pur2_id;
                $extension = $file->getClientOriginalExtension();
                $pur2Att->att_path = $this->tquotDoc($file,$extension);
                $pur2Att->save();
            }
        }

        return redirect()->route('all-tquotation');
    }

    public function destroy(Request $request)
    {
        tquotation::where('Sale_inv_no', $request->delete_quot2)->update([
            'status' => '0',
            'updated_by' => session('user_id'),
        ]);
        return redirect()->route('all-tquotation');
    }

    public function show(string $id)
    {
        $pur = tquotation::where('Sale_inv_no',$id)
                ->join('ac as acc_name','tquotation.account_name','=','acc_name.ac_code')
                ->join('ac as dispt_to','tquotation.Cash_pur_name_ac','=','dispt_to.ac_code')
                ->select('tquotation.*','dispt_to.ac_name as disp_to','acc_name.ac_name as ac_name', 
                'acc_name.address as address', 'acc_name.phone_no as phone_no')
                ->first();

        $pur2 = tquotation_2::where('sales_inv_cod',$id)
                ->join('item_entry as ie','tquotation_2.item_cod','=','ie.it_cod')
                ->select('tquotation_2.*','ie.item_name')
                ->get();

        return view('tquotation.view',compact('pur','pur2'));
    }

    public function getAttachements(Request $request)
    {
        $pur2_atts = tquotation_att::where('pur2_id', $request->id)->get();
        
        return $pur2_atts;
    }

    public function getItems($id){

        $quot1= tquotation::where('Sale_inv_no',$id)->get()->first();

        $quot2 = tquotation_2::where('sales_inv_cod',$id)
        ->join('item_entry as ie','tquotation_2.item_cod','=','ie.it_cod')
        ->select('tquotation_2.*','ie.item_name')
        ->get();

        return response()->json([
            'pur1' => $quot1,
            'pur2' => $quot2,
        ]);
    }

    public function deleteAtt($id)
    {
        $doc=tquotation_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $pur2_att = tquotation_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=tquotation_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=tquotation_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }
    
    public function getavailablestock($id) {
        $result = gd_pipe_item_stock9_much::where('it_cod', $id)->select('opp_bal')->get();
        return $result;
    }
    
 }

