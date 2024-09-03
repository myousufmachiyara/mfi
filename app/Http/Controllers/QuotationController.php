<?php
namespace App\Http\Controllers;
use TCPDF;

use Illuminate\Http\Request;
use App\Models\Item_entry2;
use App\Models\Item_Groups;
use App\Models\AC;
use App\Models\quotation;
use App\Models\quotation_2;
use App\Models\quotation_att;
use Illuminate\Support\Facades\File;
use App\Traits\SaveImage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class QuotationController extends Controller
{
    use SaveImage;

    public function index()
    {
        $quot2 = quotation::where('quotation.status', 1)
            ->leftjoin('quotation_2', 'quotation_2.sales_inv_cod', '=', 'quotation.Sale_inv_no')
            ->join('ac as acc_name', 'acc_name.ac_code', '=', 'quotation.account_name')
            ->join('ac as disp_to', 'disp_to.ac_code', '=', 'quotation.Cash_pur_name_ac')
            ->select(
                'quotation.Sale_inv_no', 'quotation.sa_date', 'acc_name.ac_name as acc_name', 'quotation.pur_ord_no',
                'disp_to.ac_name as disp_to', 'quotation.Cash_pur_name', 'quotation.Sales_Remarks', 'quotation.sales_against', 'quotation.prefix',
                'quotation.ConvanceCharges', 'quotation.LaborCharges', 'quotation.Bill_discount',
                \DB::raw('SUM(quotation_2.weight_pc * quotation_2.Sales_qty2) as weight_sum'),
                \DB::raw('SUM(((quotation_2.Sales_qty2 * quotation_2.sales_price) + ((quotation_2.Sales_qty2 * quotation_2.sales_price) * (quotation_2.discount / 100))) * quotation_2.length) as total_bill')
            )
            ->groupby(
                'quotation.Sale_inv_no', 'quotation.sa_date', 'acc_name.ac_name', 'quotation.pur_ord_no',
                'disp_to.ac_name', 'quotation.Cash_pur_name', 'quotation.Sales_Remarks', 'quotation.sales_against', 'quotation.prefix',
                'quotation.ConvanceCharges', 'quotation.LaborCharges', 'quotation.Bill_discount'
            )
            ->get();
    
        return view('quotation.index', compact('quot2'));
    }
    

    public function create(Request $request)
    {
        $items = Item_entry2::all();
        $coa = AC::all();
        return view('quotation.create',compact('items','coa'));
    }

    public function store(Request $request)
    {
        
        $quot2 = new quotation();

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
        if ($request->has('ConvanceCharges') && $request->ConvanceCharges) {
            $quot2->ConvanceCharges=$request->ConvanceCharges;
        }
        if ($request->has('LaborCharges') && $request->LaborCharges) {
            $quot2->LaborCharges=$request->LaborCharges;
        }
        if ($request->has('Bill_discount') && $request->Bill_discount) {
            $quot2->Bill_discount=$request->Bill_discount;
        }

        $quot2->save();

        $pur_2_id = quotation::latest()->first();

        if($request->has('items'))
         {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $quotation_2 = new quotation_2();

                    $quotation_2->sales_inv_cod=$pur_2_id['Sale_inv_no'];
                    $quotation_2->item_cod=$request->item_cod[$i];

                    if ($request->remarks[$i]!=null) {
                        $quotation_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur2_qty2[$i]!=null) {
                        $quotation_2->Sales_qty2=$request->pur2_qty2[$i];
                    }
                    if ($request->pur2_per_unit[$i]!=null) {
                        $quotation_2->sales_price=$request->pur2_per_unit[$i];
                    }
                    if ($request->weight_per_piece[$i]!=null) {
                        $quotation_2->weight_pc=$request->weight_per_piece[$i];
                    }
                    if ($request->pur2_len[$i]!=null) {
                        $quotation_2->length=$request->pur2_len[$i];
                    }
                    if ($request->pur2_price_date[$i]!=null) {
                        $quotation_2->rat_dat=$request->pur2_price_date[$i];
                    }
                    if ($request->pur2_percentage[$i]!=null) {
                        $quotation_2->discount=$request->pur2_percentage[$i];
                    }
                    
                    $quotation_2->save();
                }
            }
         }     

         if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $pur2Att = new quotation_att();
                $pur2Att->pur2_id = $pur_2_id['Sale_inv_no'];
                $extension = $file->getClientOriginalExtension();
                $pur2Att->att_path = $this->quotDoc($file,$extension);
                $pur2Att->save();
            }
        }

        return redirect()->route('all-quotation');
    }

public function edit($id)
    {
        $items = Item_entry2::orderBy('item_name', 'asc')->get();
        $coa = AC::orderBy('ac_name', 'asc')->get();

        $pur2 = quotation::where('quotation.Sale_inv_no',$id)->first();
        $pur2_item = quotation_2::where('quotation_2.sales_inv_cod',$id)->get();

        return view('quotation.edit',compact('pur2','pur2_item','items','coa'));
    }


    public function update(Request $request)
    {

        $pur2 = quotation::where('Sale_inv_no',$request->pur2_id)->get()->first();

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
        if ($request->has('disp_account_name') && $request->disp_account_name) {
            $pur2->Cash_pur_name_ac=$request->disp_account_name;
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

        quotation::where('Sale_inv_no', $request->pur2_id)->update([
            'sa_date'=>$pur2->sa_date,
            'pur_ord_no'=>$pur2->pur_ord_no,
            'sales_against'=>$pur2->sales_against,
            'account_name'=>$pur2->account_name,
            'Cash_pur_name_ac'=>$pur2->Cash_pur_name_ac,
            'Cash_pur_name'=>$pur2->Cash_pur_name,
            'cash_Pur_address'=>$pur2->cash_Pur_address,
            'Sales_Remarks'=>$pur2->Sales_Remarks,
            'ConvanceCharges'=>$pur2->ConvanceCharges,
            'LaborCharges'=>$pur2->LaborCharges,
            'Bill_discount'=>$pur2->Bill_discount,
        ]);

        quotation_2::where('sales_inv_cod', $request->pur2_id)->delete();

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $quotation_2 = new quotation_2();

                    $quotation_2->sales_inv_cod=$request->pur2_id;
                    $quotation_2->item_cod=$request->item_cod[$i];

                    if ($request->remarks[$i]!=null) {
                        $quotation_2->remarks=$request->remarks[$i];
                    }
                    if ($request->pur2_qty2[$i]!=null) {
                        $quotation_2->Sales_qty2=$request->pur2_qty2[$i];
                    }
                    if ($request->pur2_per_unit[$i]!=null) {
                        $quotation_2->sales_price=$request->pur2_per_unit[$i];
                    }
                    if ($request->weight_per_piece[$i]!=null) {
                        $quotation_2->weight_pc=$request->weight_per_piece[$i];
                    }
                    if ($request->pur2_len[$i]!=null) {
                        $quotation_2->length=$request->pur2_len[$i];
                    }
                    if ($request->pur2_price_date[$i]!=null) {
                        $quotation_2->rat_dat=$request->pur2_price_date[$i];
                    }
                    if ($request->pur2_percentage[$i]!=null) {
                        $quotation_2->discount=$request->pur2_percentage[$i];
                    }
                    
                    $quotation_2->save();
                }
            }
        }

       

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $pur2Att = new quotation_att();
                $pur2Att->pur2_id = $request->pur2_id;
                $extension = $file->getClientOriginalExtension();
                $pur2Att->att_path = $this->quotDoc($file,$extension);
                $pur2Att->save();
            }
        }

        return redirect()->route('all-quotation');
    }


    public function destroy(Request $request)
    {
        quotation::where('Sale_inv_no', $request->delete_quot2)->update(['status' => '0']);
        return redirect()->route('all-quotation');
    }

    public function show(string $id)
    {
        $pur = quotation::where('Sale_inv_no',$id)
                ->join('ac as acc_name','quotation.account_name','=','acc_name.ac_code')
                ->join('ac as dispt_to','quotation.Cash_pur_name_ac','=','dispt_to.ac_code')
                ->select('quotation.*','dispt_to.ac_name as disp_to','acc_name.ac_name as ac_name', 
                'acc_name.address as address', 'acc_name.phone_no as phone_no')
                ->first();

        $pur2 = quotation_2::where('sales_inv_cod',$id)
                ->join('item_entry as ie','quotation_2.item_cod','=','ie.it_cod')
                ->select('quotation_2.*','ie.item_name')
                ->get();

        return view('quotation.view',compact('pur','pur2'));
    }

    public function getAttachements(Request $request)
    {
        $pur2_atts = quotation_att::where('pur2_id', $request->id)->get();
        
        return $pur2_atts;
    }

    

    public function getItems($id){

        $quot1= quotation::where('Sale_inv_no',$id)->get()->first();

        $quot2 = quotation_2::where('sales_inv_cod',$id)
        ->join('item_entry as ie','quotation_2.item_cod','=','ie.it_cod')
        ->select('quotation_2.*','ie.item_name')
        ->get();

        return response()->json([
            'pur1' => $quot1,
            'pur2' => $quot2,
        ]);
    }

    public function deleteAtt($id)
    {
        $doc=quotation_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $pur2_att = quotation_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=quotation_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=quotation_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }


 }

