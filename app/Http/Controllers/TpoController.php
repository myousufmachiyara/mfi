<?php
namespace App\Http\Controllers;
use TCPDF;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
use App\Models\Item_Groups;
use App\Models\AC;
use App\Models\tpo;
use App\Models\tpo_2;
use App\Models\tpo_att;
use App\Models\gd_pipe_item_stock9_much;
use Illuminate\Support\Facades\File;
use App\Traits\SaveImage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class TpoController extends Controller
{
    use SaveImage;

    public function index()
    {
        $tpo2 = tpo::where('tpo.status', 1)
            ->leftjoin('tpo_2', 'tpo_2.sales_inv_cod', '=', 'tpo.Sale_inv_no')
            ->join('ac as acc_name', 'acc_name.ac_code', '=', 'tpo.account_name')
            ->select(
                'tpo.Sale_inv_no', 'tpo.sa_date', 'acc_name.ac_name as acc_name',
                 'tpo.Cash_pur_name', 'tpo.Sales_Remarks', 'tpo.sales_against', 'tpo.prefix',
                'tpo.ConvanceCharges', 'tpo.LaborCharges', 'tpo.Bill_discount',
                \DB::raw('SUM(tpo_2.weight_pc * tpo_2.Sales_qty2) as weight_sum'),
                \DB::raw('SUM(((tpo_2.Sales_qty2 * tpo_2.sales_price) + ((tpo_2.Sales_qty2 * tpo_2.sales_price) * (tpo_2.discount / 100))) * tpo_2.length) as total_bill')
            )
            ->groupby(
                'tpo.Sale_inv_no', 'tpo.sa_date', 'acc_name.ac_name',
                 'tpo.Cash_pur_name', 'tpo.Sales_Remarks', 'tpo.sales_against', 'tpo.prefix',
                'tpo.ConvanceCharges', 'tpo.LaborCharges', 'tpo.Bill_discount'
            )
            ->get();
    
        return view('tpo.index', compact('tpo2'));
    }
    

    public function create(Request $request)
    {
        $items = Item_entry2::all();
        $coa = AC::all();
        return view('tpo.create',compact('items','coa'));
    }

    public function store(Request $request)
    {
        
        $tpo2 = new tpo();

        if ($request->has('sa_date') && $request->sa_date) {
            $tpo2->sa_date=$request->sa_date;
        }
        if ($request->has('sales_against') && $request->sales_against) {
            $tpo2->sales_against=$request->sales_against;
        }
        if ($request->has('account_name') && $request->account_name) {
            $tpo2->account_name=$request->account_name;
        }
        if ($request->has('Cash_pur_name') && $request->Cash_pur_name) {
            $tpo2->Cash_pur_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_Pur_address') && $request->cash_Pur_address) {
            $tpo2->cash_Pur_address=$request->cash_Pur_address;
        }
        if ($request->has('Sales_Remarks') && $request->Sales_Remarks) {
            $tpo2->Sales_Remarks=$request->Sales_Remarks;
        }
        if ($request->has('ConvanceCharges') && $request->ConvanceCharges) {
            $tpo2->ConvanceCharges=$request->ConvanceCharges;
        }
        if ($request->has('LaborCharges') && $request->LaborCharges) {
            $tpo2->LaborCharges=$request->LaborCharges;
        }
        if ($request->has('Bill_discount') && $request->Bill_discount) {
            $tpo2->Bill_discount=$request->Bill_discount;
        }

        $tpo2->save();

        $pur_2_id = tpo::latest()->first();

        if($request->has('items'))
         {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $tpo_2 = new tpo_2();

                    $tpo_2->sales_inv_cod=$pur_2_id['Sale_inv_no'];
                    $tpo_2->item_cod=$request->item_cod[$i];

                    if ($request->remarks[$i]!=null) {
                        $tpo_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur2_qty2[$i]!=null) {
                        $tpo_2->Sales_qty2=$request->pur2_qty2[$i];
                    }
                    if ($request->pur2_per_unit[$i]!=null) {
                        $tpo_2->sales_price=$request->pur2_per_unit[$i];
                    }
                    if ($request->weight_per_piece[$i]!=null) {
                        $tpo_2->weight_pc=$request->weight_per_piece[$i];
                    }
                    if ($request->pur2_len[$i]!=null) {
                        $tpo_2->length=$request->pur2_len[$i];
                    }
                    if ($request->pur2_price_date[$i]!=null) {
                        $tpo_2->rat_dat=$request->pur2_price_date[$i];
                    }
                    if ($request->dispatchto[$i]!=null) {
                        $tpo_2->dispatch_to=$request->dispatchto[$i];
                    }
                    if ($request->pur2_percentage[$i]!=null) {
                        $tpo_2->discount=$request->pur2_percentage[$i];
                    }
                    
                    $tpo_2->save();
                }
            }
         }     

         if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $pur2Att = new tpo_att();
                $pur2Att->pur2_id = $pur_2_id['Sale_inv_no'];
                $extension = $file->getClientOriginalExtension();
                $pur2Att->att_path = $this->tpoDoc($file,$extension);
                $pur2Att->save();
            }
        }

        return redirect()->route('all-tpo');
    }

public function edit($id)
    {
        $items = Item_entry2::orderBy('item_name', 'asc')->get();
        $coa = AC::orderBy('ac_name', 'asc')->get();

        $pur2 = tpo::where('tpo.Sale_inv_no',$id)->first();
        $pur2_item = tpo_2::where('tpo_2.sales_inv_cod',$id)->get();

        return view('tpo.edit',compact('pur2','pur2_item','items','coa'));
    }


    public function update(Request $request)
    {

        $pur2 = tpo::where('Sale_inv_no',$request->pur2_id)->get()->first();

        if ($request->has('sa_date') && $request->sa_date) {
            $pur2->sa_date=$request->sa_date;
        }
        if ($request->has('sales_against') && $request->sales_against OR empty($request->sales_against)) {
            $pur2->sales_against=$request->sales_against;
        }
        if ($request->has('account_name') && $request->account_name) {
            $pur2->account_name=$request->account_name;
        }
        if ($request->has('Cash_pur_name') && $request->Cash_pur_name OR empty($request->Cash_pur_name)) {
            $pur2->Cash_pur_name=$request->Cash_pur_name;
        }
        if ($request->has('cash_Pur_address') && $request->cash_Pur_address OR empty($request->cash_Pur_address)) {
            $pur2->cash_Pur_address=$request->cash_Pur_address;
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

        tpo::where('Sale_inv_no', $request->pur2_id)->update([
            'sa_date'=>$pur2->sa_date,
            'sales_against'=>$pur2->sales_against,
            'account_name'=>$pur2->account_name,
            'Cash_pur_name'=>$pur2->Cash_pur_name,
            'cash_Pur_address'=>$pur2->cash_Pur_address,
            'Sales_Remarks'=>$pur2->Sales_Remarks,
            'ConvanceCharges'=>$pur2->ConvanceCharges,
            'LaborCharges'=>$pur2->LaborCharges,
            'Bill_discount'=>$pur2->Bill_discount,
        ]);

        tpo_2::where('sales_inv_cod', $request->pur2_id)->delete();

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $tpo_2 = new tpo_2();

                    $tpo_2->sales_inv_cod=$request->pur2_id;
                    $tpo_2->item_cod=$request->item_cod[$i];

                    if ($request->remarks[$i]!=null OR empty($request->remarks[$i])) {
                        $tpo_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur2_qty2[$i]!=null) {
                        $tpo_2->Sales_qty2=$request->pur2_qty2[$i];
                    }
                    if ($request->pur2_per_unit[$i]!=null) {
                        $tpo_2->sales_price=$request->pur2_per_unit[$i];
                    }
                    if ($request->weight_per_piece[$i]!=null) {
                        $tpo_2->weight_pc=$request->weight_per_piece[$i];
                    }
                    if ($request->pur2_len[$i]!=null) {
                        $tpo_2->length=$request->pur2_len[$i];
                    }
                    if ($request->pur2_price_date[$i]!=null OR empty($request->pur2_price_date[$i])) {
                        $tpo_2->rat_dat=$request->pur2_price_date[$i];
                    }
                    
                    if ($request->dispatchto[$i]!=null OR empty($request->dispatchto[$i])) {
                        $tpo_2->dispatch_to=$request->dispatchto[$i];
                    }
                        
                    if ($request->pur2_percentage[$i]!=null) {
                        $tpo_2->discount=$request->pur2_percentage[$i];
                    }
                    
                    $tpo_2->save();
                }
            }
        }

       

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $pur2Att = new tpo_att();
                $pur2Att->pur2_id = $request->pur2_id;
                $extension = $file->getClientOriginalExtension();
                $pur2Att->att_path = $this->tpoDoc($file,$extension);
                $pur2Att->save();
            }
        }

        return redirect()->route('all-tpo');
    }


    public function destroy(Request $request)
    {
        tpo::where('Sale_inv_no', $request->delete_tpo2)->update(['status' => '0']);
        return redirect()->route('all-tpo');
    }

    public function show(string $id)
    {
        $pur = tpo::where('Sale_inv_no',$id)
                ->join('ac as acc_name','tpo.account_name','=','acc_name.ac_code')
                ->select('tpo.*','acc_name.address as address', 'acc_name.phone_no as phone_no')
                ->first();

        $pur2 = tpo_2::where('sales_inv_cod',$id)
                ->join('item_entry as ie','tpo_2.item_cod','=','ie.it_cod')
                ->select('tpo_2.*','ie.item_name')
                ->get();

        return view('tpo.view',compact('pur','pur2'));
    }

    public function getAttachements(Request $request)
    {
        $pur2_atts = tpo_att::where('pur2_id', $request->id)->get();
        
        return $pur2_atts;
    }

    

    public function getItems($id){

        $tpo1= tpo::where('Sale_inv_no',$id)->get()->first();

        $tpo2 = tpo_2::where('sales_inv_cod',$id)
        ->join('item_entry as ie','tpo_2.item_cod','=','ie.it_cod')
        ->select('tpo_2.*','ie.item_name')
        ->get();

        return response()->json([
            'pur1' => $tpo1,
            'pur2' => $tpo2,
        ]);
    }

    public function deleteAtt($id)
    {
        $doc=tpo_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $pur2_att = tpo_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=tpo_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=tpo_att::where('att_id', $id)->select('att_path')->first();
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

