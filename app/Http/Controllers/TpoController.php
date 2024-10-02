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
                 'tpo.Cash_pur_name', 'tpo.Sales_Remarks', 'tpo.sales_against', 'tpo.prefix','tpo.tc',
                'tpo.ConvanceCharges', 'tpo.LaborCharges', 'tpo.Bill_discount',
                \DB::raw('SUM(tpo_2.weight_pc * tpo_2.Sales_qty2) as weight_sum'),
                \DB::raw('SUM(((tpo_2.Sales_qty2 * tpo_2.sales_price) + ((tpo_2.Sales_qty2 * tpo_2.sales_price) * (tpo_2.discount / 100))) * tpo_2.length) as total_bill')
            )
            ->groupby(
                'tpo.Sale_inv_no', 'tpo.sa_date', 'acc_name.ac_name',
                 'tpo.Cash_pur_name', 'tpo.Sales_Remarks', 'tpo.sales_against', 'tpo.prefix','tpo.tc',
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
        if ($request->has('tc') && $request->tc) {
            $tpo2->tc=$request->tc;
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
        $tpo2->created_by = session('user_id');
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
        if ($request->has('hidden_sales_against') && $request->hidden_sales_against OR empty($request->hidden_sales_against)) {
            $pur2->sales_against=$request->hidden_sales_against;
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
        if ($request->has('tc') && $request->tc OR empty($request->tc)) {
            $pur2->tc=$request->tc;
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
            'tc'=>$pur2->tc,
            'ConvanceCharges'=>$pur2->ConvanceCharges,
            'LaborCharges'=>$pur2->LaborCharges,
            'Bill_discount'=>$pur2->Bill_discount,
            'updated_by' => session('user_id'),
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
        tpo::where('Sale_inv_no', $request->delete_tpo2)->update([
            'status' => '0',
            'updated_by' => session('user_id'),
        ]);
        return redirect()->route('all-tpo');
    }

    public function show(string $id)
    {
        $pur = tpo::where('Sale_inv_no',$id)
        ->join('ac as acc_name','tpo.account_name','=','acc_name.ac_code')
        ->select('tpo.*','acc_name.ac_name as ac_name', 'acc_name.address as address', 'acc_name.phone_no as phone_no')
        ->first();

        $pur2 = tpo_2::where('sales_inv_cod',$id)
                ->join('item_entry2 as ie','tpo_2.item_cod','=','ie.it_cod')
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
    

    public function showAllPDF($id)

    {
        $purchase = tsales::where('Sal_inv_no', $id)
        ->leftJoin('ac as account', 'account.ac_code', '=', 'tsales.account_name')
        ->leftJoin('ac as company', 'company.ac_code', '=', 'tsales.company_name')
        ->select('tsales.*', 'account.ac_name as ac_name', 'account.address as ac_add' , 'account.phone_no as ac_phone_no' ,'company.ac_name as company_name')
        ->first();


        $purchase_items = tsales_2::where('sales_inv_cod',$id)
                ->join('item_entry2','tsales_2.item_cod','=','item_entry2.it_cod')
                ->select('tsales_2.*','item_entry2.item_name')
                ->get();
                
        $pdf = new MyPDF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Sale Invoice-'.$purchase['prefix'].$purchase['Sal_inv_no']);
        $pdf->SetSubject('Sale Invoice-'.$purchase['prefix'].$purchase['Sal_inv_no']);
        $pdf->SetKeywords('Sale Invoice, TCPDF, PDF');
                   
        // Add a page
        $pdf->AddPage();
           
        $pdf->setCellPadding(1.2); // Set padding for all cells in the table

        // margin top
        $margin_top = '.margin-top {
            margin-top: 10px;
        }';
        // $pdf->writeHTML('<style>' . $margin_top . '</style>', true, false, true, false, '');

        // margin bottom
        $margin_bottom = '.margin-bottom {
            margin-bottom: 4px;
        }';

        // $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $heading='<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Sale Invoice</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table style="margin-bottom:1rem">';
        $html .= '<tr>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Invoice No: &nbsp;<span style="text-decoration: underline;color:#000">'.$purchase['prefix'].$purchase['Sal_inv_no'].'</span></td>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Date: &nbsp;<span style="color:#000">'.\Carbon\Carbon::parse($purchase['sa_date'])->format('d-m-y').'</span></td>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Mill Inv No: <span style="text-decoration: underline;color:#000">'.$purchase['pur_ord_no'].'</span></td>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Login: &nbsp; <span style="text-decoration: underline;color:#000">Hamza</span></td>';
        $html .= '</tr>';
        $html .= '</table>';

        // $pdf->writeHTML($html, true, false, true, false, '');

        $html .= '<table border="0.1px" style="border-collapse: collapse;">';
        $html .= '<tr>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Account Name </td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['ac_name'].'</td>';
        $html .= '<td width="20%" width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Name Of Person</td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['Cash_name'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D" >Address </td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['ac_add'].'</td>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Company Name</td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['company_name'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Phone </td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['ac_phone_no'].'</td>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Purchase Invoice#</td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['pur_against'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Remarks </td>';
        $html .= '<td width="80%" style="font-size:10px;font-family:poppins;">'.$purchase['Sales_Remarks'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
    
        $html = '<table border="0.3" style="text-align:center;margin-top:10px">';
        $html .= '<tr>';
        $html .= '<th style="width:6%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">S/R</th>';
        $html .= '<th style="width:26%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Item Name</th>';
        $html .= '<th style="width:20%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Description</th>';
        $html .= '<th style="width:10%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Qty</th>';
        $html .= '<th style="width:11%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Price/Unit</th>';
        $html .= '<th style="width:7%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Len</th>';
        $html .= '<th style="width:7%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">%</th>';
        $html .= '<th style="width:13%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Amount</th>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->setTableHtml($html);

        $count = 1;
        $total_weight = 0;
        $total_quantity = 0;
        $total_amount = 0;

        $html .= '<table cellspacing="0" cellpadding="5">';
        foreach ($purchase_items as $items) {
            // Determine background color based on odd/even rows
            $bg_color = ($count % 2 == 0) ? 'background-color:#f1f1f1' : '';

            $html .= '<tr style="' . $bg_color . '">';
            $html .= '<td style="width:6%;border-right:1px dashed #000;border-left:1px dashed #000; text-align:center">' . $count . '</td>';
            $html .= '<td style="width:26%;border-right:1px dashed #000">' . $items['item_name'] . '</td>';
            $html .= '<td style="width:20%;border-right:1px dashed #000">' . $items['remarks'] . '</td>';
            $html .= '<td style="width:10%;border-right:1px dashed #000; text-align:center">' . $items['Sales_qty2'] . '</td>';
            $total_quantity += $items['Sales_qty2'];
            $html .= '<td style="width:11%;border-right:1px dashed #000; text-align:center">' . $items['sales_price'] . '</td>';
            $html .= '<td style="width:7%;border-right:1px dashed #000; text-align:center">' . $items['length'] . '</td>';
            $html .= '<td style="width:7%;border-right:1px dashed #000; text-align:center">' . $items['discount'] . '</td>';

            // Calculate the total weight and amount
            $total_weight += $items['Sales_qty2'] * $items['weight_pc'];
            $amount = (($items['Sales_qty2'] * $items['sales_price']) + (($items['Sales_qty2'] * $items['sales_price']) * ($items['discount'] / 100))) * $items['length'];
            $html .= '<td style="width:13%;border-right:1px dashed #000; text-align:center">' . $amount . '</td>';
            $total_amount += $amount;

            $html .= '</tr>';
            $count++;
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $currentY = $pdf->GetY();
            
        if(($pdf->getPageHeight()-$pdf->GetY())<57){
            $pdf->AddPage();
            $currentY = $pdf->GetY()+15;
        }

        $pdf->SetFont('helvetica','B', 10);
        $pdf->SetTextColor(23, 54, 93);

        $pdf->SetXY(10, $currentY);
        $pdf->Cell(40, 5, 'Total Weight(kg)', 1,1);
        $pdf->Cell(40, 5, 'Total Quantity', 1,1);

        // // Column 2
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(50, $currentY);
        $pdf->Cell(42, 5,  $total_weight, 1, 'R');
        $pdf->SetXY(50, $currentY+6.8);
        $pdf->SetFont('helvetica','', 10);

        $pdf->Cell(42, 5, $total_quantity, 1,'R');

        $roundedTotal= round($total_amount+$purchase['LaborCharges']+$purchase['ConvanceCharges']-$purchase['Bill_discount']);
        $num_to_words=$pdf->convertCurrencyToWords($roundedTotal);
       

        // Column 3
        $pdf->SetFont('helvetica','B', 10);
        $pdf->SetTextColor(23, 54, 93);

        $pdf->SetXY(120, $currentY);
        $pdf->Cell(45, 5, 'Total Amount', 1,1);
        $pdf->SetXY(120, $currentY+6.8);
        $pdf->Cell(45, 5, 'Labour Charges', 1,1);
        $pdf->SetXY(120, $currentY+13.7);
        $pdf->Cell(45, 5, 'Convance Charges', 1,1);
        $pdf->SetXY(120, $currentY+20.5);
        $pdf->Cell(45, 5, 'Discount(Rs)', 1,1);
        // $pdf->SetXY(120, $currentY+27.3);
        // $pdf->Cell(45, 5, 'Net Amount', 1,1);
        // Change font size to 12 for "Net Amount"
        $pdf->SetFont('helvetica', 'B', 12);  
        $pdf->SetXY(120, $currentY+27.3);
        $pdf->Cell(45, 5, 'Net Amount', 1, 1);
        
        // // Column 4
        $pdf->SetFont('helvetica','', 10);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetXY(165, $currentY);
        $pdf->Cell(35, 5, $total_amount, 1, 'R');
        $pdf->SetXY(165, $currentY+6.8);
        $pdf->Cell(35, 5, $purchase['LaborCharges'], 1, 'R');
        $pdf->SetXY(165, $currentY+13.7);
        $pdf->Cell(35, 5, $purchase['ConvanceCharges'], 1, 'R');
        $pdf->SetXY(165, $currentY+20.5);
        $pdf->Cell(35, 5, $purchase['Bill_discount'], 1, 'R');
        $pdf->SetXY(165, $currentY+27.3);
        $net_amount=number_format(round($total_amount+$purchase['LaborCharges']+$purchase['ConvanceCharges']-$purchase['Bill_discount']));
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(35, 5,  $net_amount, 1, 'R');
        
        $pdf->SetFont('helvetica','BIU', 14);
        $pdf->SetTextColor(23, 54, 93);

        $pdf->SetXY(10, $currentY+20);
        $width = 100;
        $pdf->MultiCell($width, 10, $num_to_words, 0, 'L', 0, 1, '', '', true);
        $pdf->SetFont('helvetica','', 10);
        
        // Close and output PDF
        $pdf->Output('Sale Invoice_'.$purchase['prefix'].$purchase['Sal_inv_no'].'.pdf', 'I');
    }
    

    public function noLengthPDF($id)
    {
        $purchase = tsales::where('Sal_inv_no', $id)
        ->leftJoin('ac as account', 'account.ac_code', '=', 'tsales.account_name')
        ->leftJoin('ac as company', 'company.ac_code', '=', 'tsales.company_name')
        ->select('tsales.*', 'account.ac_name as ac_name', 'account.address as ac_add' , 'account.phone_no as ac_phone_no' ,'company.ac_name as company_name')
        ->first();


        $purchase_items = tsales_2::where('sales_inv_cod',$id)
                ->join('item_entry2','tsales_2.item_cod','=','item_entry2.it_cod')
                ->select('tsales_2.*','item_entry2.item_name')
                ->get();
                
        $pdf = new MyPDF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Sale Invoice-'.$purchase['prefix'].$purchase['Sal_inv_no']);
        $pdf->SetSubject('Sale Invoice-'.$purchase['prefix'].$purchase['Sal_inv_no']);
        $pdf->SetKeywords('Sale Invoice, TCPDF, PDF');
                   
        // Add a page
        $pdf->AddPage();
           
        $pdf->setCellPadding(1.2); // Set padding for all cells in the table

        // margin top
        $margin_top = '.margin-top {
            margin-top: 10px;
        }';
        // $pdf->writeHTML('<style>' . $margin_top . '</style>', true, false, true, false, '');

        // margin bottom
        $margin_bottom = '.margin-bottom {
            margin-bottom: 4px;
        }';

        // $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $heading='<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Sale Invoice</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table style="margin-bottom:1rem">';
        $html .= '<tr>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Invoice No: &nbsp;<span style="text-decoration: underline;color:#000">'.$purchase['prefix'].$purchase['Sal_inv_no'].'</span></td>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Date: &nbsp;<span style="color:#000">'.\Carbon\Carbon::parse($purchase['sa_date'])->format('d-m-y').'</span></td>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Mill Inv No: <span style="text-decoration: underline;color:#000">'.$purchase['pur_ord_no'].'</span></td>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Login: &nbsp; <span style="text-decoration: underline;color:#000">Hamza</span></td>';
        $html .= '</tr>';
        $html .= '</table>';

        // $pdf->writeHTML($html, true, false, true, false, '');

        $html .= '<table border="0.1px" style="border-collapse: collapse;">';
        $html .= '<tr>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Account Name </td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['ac_name'].'</td>';
        $html .= '<td width="20%" width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Name Of Person</td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['Cash_name'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D" >Address </td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['ac_add'].'</td>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Company Name</td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['company_name'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Phone </td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['ac_phone_no'].'</td>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Purchase Invoice#</td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['pur_against'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Remarks </td>';
        $html .= '<td width="80%" style="font-size:10px;font-family:poppins;">'.$purchase['Sales_Remarks'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
    
        $html = '<table border="0.3" style="text-align:center;margin-top:10px">';
        $html .= '<tr>';
        $html .= '<th style="width:6%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">S/R</th>';
        $html .= '<th style="width:28%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Item Name</th>';
        $html .= '<th style="width:21%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Description</th>';
        $html .= '<th style="width:10%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Qty</th>';
        $html .= '<th style="width:13%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">List Rate</th>';
        $html .= '<th style="width:7%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">%</th>';
        $html .= '<th style="width:15%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Amount</th>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->setTableHtml($html);

        $count = 1;
        $total_weight = 0;
        $total_quantity = 0;
        $total_amount = 0;

        $html .= '<table cellspacing="0" cellpadding="5">';
        foreach ($purchase_items as $items) {
            // Determine background color based on odd/even rows
            $bg_color = ($count % 2 == 0) ? 'background-color:#f1f1f1' : '';

            $html .= '<tr style="' . $bg_color . '">';
            $html .= '<td style="width:6%;border-right:1px dashed #000;border-left:1px dashed #000; text-align:center">' . $count . '</td>';
            $html .= '<td style="width:28%;border-right:1px dashed #000">' . $items['item_name'] . '</td>';
            $html .= '<td style="width:21%;border-right:1px dashed #000">' . $items['remarks'] . '</td>';
            $html .= '<td style="width:10%;border-right:1px dashed #000; text-align:center">' . $items['Sales_qty2'] . '</td>';
            $total_quantity += $items['Sales_qty2'];
             // Calculate the list price
             $price = $items['sales_price'] * $items['length'];
            $html .= '<td style="width:13%;border-right:1px dashed #000; text-align:center">' . $price . '</td>';
            $html .= '<td style="width:7%;border-right:1px dashed #000; text-align:center">' . $items['discount'] . '</td>';

            // Calculate the total weight and amount
            $total_weight += $items['Sales_qty2'] * $items['weight_pc'];
            $amount = (($items['Sales_qty2'] * $items['sales_price']) + (($items['Sales_qty2'] * $items['sales_price']) * ($items['discount'] / 100))) * $items['length'];
            $html .= '<td style="width:15%;border-right:1px dashed #000; text-align:center">' . $amount . '</td>';
            $total_amount += $amount;

            $html .= '</tr>';
            $count++;
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $currentY = $pdf->GetY();
            
        if(($pdf->getPageHeight()-$pdf->GetY())<57){
            $pdf->AddPage();
            $currentY = $pdf->GetY()+15;
        }

        $pdf->SetFont('helvetica','B', 10);
        $pdf->SetTextColor(23, 54, 93);

        $pdf->SetXY(10, $currentY);
        $pdf->Cell(40, 5, 'Total Weight(kg)', 1,1);
        $pdf->Cell(40, 5, 'Total Quantity', 1,1);

        // // Column 2
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(50, $currentY);
        $pdf->Cell(42, 5,  $total_weight, 1, 'R');
        $pdf->SetXY(50, $currentY+6.8);
        $pdf->SetFont('helvetica','', 10);

        $pdf->Cell(42, 5, $total_quantity, 1,'R');

        $roundedTotal= round($total_amount+$purchase['LaborCharges']+$purchase['ConvanceCharges']-$purchase['Bill_discount']);
        $num_to_words=$pdf->convertCurrencyToWords($roundedTotal);
       

        // Column 3
        $pdf->SetFont('helvetica','B', 10);
        $pdf->SetTextColor(23, 54, 93);

        $pdf->SetXY(120, $currentY);
        $pdf->Cell(45, 5, 'Total Amount', 1,1);
        $pdf->SetXY(120, $currentY+6.8);
        $pdf->Cell(45, 5, 'Labour Charges', 1,1);
        $pdf->SetXY(120, $currentY+13.7);
        $pdf->Cell(45, 5, 'Convance Charges', 1,1);
        $pdf->SetXY(120, $currentY+20.5);
        $pdf->Cell(45, 5, 'Discount(Rs)', 1,1);
        // $pdf->SetXY(120, $currentY+27.3);
        // $pdf->Cell(45, 5, 'Net Amount', 1,1);
        // Change font size to 12 for "Net Amount"
        $pdf->SetFont('helvetica', 'B', 12);  
        $pdf->SetXY(120, $currentY+27.3);
        $pdf->Cell(45, 5, 'Net Amount', 1, 1);
        
        // // Column 4
        $pdf->SetFont('helvetica','', 10);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetXY(165, $currentY);
        $pdf->Cell(35, 5, $total_amount, 1, 'R');
        $pdf->SetXY(165, $currentY+6.8);
        $pdf->Cell(35, 5, $purchase['LaborCharges'], 1, 'R');
        $pdf->SetXY(165, $currentY+13.7);
        $pdf->Cell(35, 5, $purchase['ConvanceCharges'], 1, 'R');
        $pdf->SetXY(165, $currentY+20.5);
        $pdf->Cell(35, 5, $purchase['Bill_discount'], 1, 'R');
        $pdf->SetXY(165, $currentY+27.3);
        $net_amount=number_format(round($total_amount+$purchase['LaborCharges']+$purchase['ConvanceCharges']-$purchase['Bill_discount']));
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(35, 5,  $net_amount, 1, 'R');
        
        $pdf->SetFont('helvetica','BIU', 14);
        $pdf->SetTextColor(23, 54, 93);

        $pdf->SetXY(10, $currentY+20);
        $width = 100;
        $pdf->MultiCell($width, 10, $num_to_words, 0, 'L', 0, 1, '', '', true);
        $pdf->SetFont('helvetica','', 10);
        
        // Close and output PDF
        $pdf->Output('Sale Invoice_'.$purchase['prefix'].$purchase['Sal_inv_no'].'.pdf', 'I');
    }

    public function onlyPriceQtyPDF($id)
    {
        $purchase = tsales::where('Sal_inv_no', $id)
        ->leftJoin('ac as account', 'account.ac_code', '=', 'tsales.account_name')
        ->leftJoin('ac as company', 'company.ac_code', '=', 'tsales.company_name')
        ->select('tsales.*', 'account.ac_name as ac_name', 'account.address as ac_add' , 'account.phone_no as ac_phone_no' ,'company.ac_name as company_name')
        ->first();


        $purchase_items = tsales_2::where('sales_inv_cod',$id)
                ->join('item_entry2','tsales_2.item_cod','=','item_entry2.it_cod')
                ->select('tsales_2.*','item_entry2.item_name')
                ->get();
                
        $pdf = new MyPDF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MFI');
        $pdf->SetTitle('Sale Invoice-'.$purchase['prefix'].$purchase['Sal_inv_no']);
        $pdf->SetSubject('Sale Invoice-'.$purchase['prefix'].$purchase['Sal_inv_no']);
        $pdf->SetKeywords('Sale Invoice, TCPDF, PDF');
                   
        // Add a page
        $pdf->AddPage();
           
        $pdf->setCellPadding(1.2); // Set padding for all cells in the table

        // margin top
        $margin_top = '.margin-top {
            margin-top: 10px;
        }';
        // $pdf->writeHTML('<style>' . $margin_top . '</style>', true, false, true, false, '');

        // margin bottom
        $margin_bottom = '.margin-bottom {
            margin-bottom: 4px;
        }';

        // $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $heading='<h1 style="font-size:20px;text-align:center;font-style:italic;text-decoration:underline;color:#17365D">Sale Invoice</h1>';
        $pdf->writeHTML($heading, true, false, true, false, '');
        $pdf->writeHTML('<style>' . $margin_bottom . '</style>', true, false, true, false, '');

        $html = '<table style="margin-bottom:1rem">';
        $html .= '<tr>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Invoice No: &nbsp;<span style="text-decoration: underline;color:#000">'.$purchase['prefix'].$purchase['Sal_inv_no'].'</span></td>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Date: &nbsp;<span style="color:#000">'.\Carbon\Carbon::parse($purchase['sa_date'])->format('d-m-y').'</span></td>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Mill Inv No: <span style="text-decoration: underline;color:#000">'.$purchase['pur_ord_no'].'</span></td>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Login: &nbsp; <span style="text-decoration: underline;color:#000">Hamza</span></td>';
        $html .= '</tr>';
        $html .= '</table>';

        // $pdf->writeHTML($html, true, false, true, false, '');

        $html .= '<table border="0.1px" style="border-collapse: collapse;">';
        $html .= '<tr>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Account Name </td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['ac_name'].'</td>';
        $html .= '<td width="20%" width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Name Of Person</td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['Cash_name'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D" >Address </td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['ac_add'].'</td>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Company Name</td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['company_name'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Phone </td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['ac_phone_no'].'</td>';
        $html .= '<td width="20%" style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Purchase Invoice#</td>';
        $html .= '<td width="30%" style="font-size:10px;font-family:poppins;">'.$purchase['pur_against'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Remarks </td>';
        $html .= '<td width="80%" style="font-size:10px;font-family:poppins;">'.$purchase['Sales_Remarks'].'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
    
        $html = '<table border="0.3" style="text-align:center;margin-top:10px">';
        $html .= '<tr>';
        $html .= '<th style="width:6%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">S/R</th>';
        $html .= '<th style="width:29%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Item Name</th>';
        $html .= '<th style="width:25%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Description</th>';
        $html .= '<th style="width:10%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Qty</th>';
        $html .= '<th style="width:14%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">List Rate</th>';
        $html .= '<th style="width:16%;font-size:10px;font-weight:bold;font-family:poppins;color:#17365D">Amount</th>';
        $html .= '</tr>';
        $html .= '</table>';

        $pdf->setTableHtml($html);

        $count = 1;
        $total_weight = 0;
        $total_quantity = 0;
        $total_amount = 0;

        $html .= '<table cellspacing="0" cellpadding="5">';
        foreach ($purchase_items as $items) {
            // Determine background color based on odd/even rows
            $bg_color = ($count % 2 == 0) ? 'background-color:#f1f1f1' : '';

            $html .= '<tr style="' . $bg_color . '">';
            $html .= '<td style="width:6%;border-right:1px dashed #000;border-left:1px dashed #000; text-align:center">' . $count . '</td>';
            $html .= '<td style="width:29%;border-right:1px dashed #000">' . $items['item_name'] . '</td>';
            $html .= '<td style="width:25%;border-right:1px dashed #000">' . $items['remarks'] . '</td>';
            $html .= '<td style="width:10%;border-right:1px dashed #000; text-align:center">' . $items['Sales_qty2'] . '</td>';
            $total_quantity += $items['Sales_qty2'];
             // Calculate the list price
             $price = (($items['sales_price']) + (( $items['sales_price']) * ($items['discount'] / 100))) * $items['length'];
            $html .= '<td style="width:14%;border-right:1px dashed #000; text-align:center">' . $price . '</td>';

            // Calculate the total weight and amount
            $total_weight += $items['Sales_qty2'] * $items['weight_pc'];
            $amount = (($items['Sales_qty2'] * $items['sales_price']) + (($items['Sales_qty2'] * $items['sales_price']) * ($items['discount'] / 100))) * $items['length'];
            $html .= '<td style="width:16%;border-right:1px dashed #000; text-align:center">' . $amount . '</td>';
            $total_amount += $amount;

            $html .= '</tr>';
            $count++;
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $currentY = $pdf->GetY();
            
        if(($pdf->getPageHeight()-$pdf->GetY())<57){
            $pdf->AddPage();
            $currentY = $pdf->GetY()+15;
        }

        $pdf->SetFont('helvetica','B', 10);
        $pdf->SetTextColor(23, 54, 93);

        $pdf->SetXY(10, $currentY);
        $pdf->Cell(40, 5, 'Total Weight(kg)', 1,1);
        $pdf->Cell(40, 5, 'Total Quantity', 1,1);

        // // Column 2
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(50, $currentY);
        $pdf->Cell(42, 5,  $total_weight, 1, 'R');
        $pdf->SetXY(50, $currentY+6.8);
        $pdf->SetFont('helvetica','', 10);

        $pdf->Cell(42, 5, $total_quantity, 1,'R');

        $roundedTotal= round($total_amount+$purchase['LaborCharges']+$purchase['ConvanceCharges']-$purchase['Bill_discount']);
        $num_to_words=$pdf->convertCurrencyToWords($roundedTotal);
       

        // Column 3
        $pdf->SetFont('helvetica','B', 10);
        $pdf->SetTextColor(23, 54, 93);

        $pdf->SetXY(120, $currentY);
        $pdf->Cell(45, 5, 'Total Amount', 1,1);
        $pdf->SetXY(120, $currentY+6.8);
        $pdf->Cell(45, 5, 'Labour Charges', 1,1);
        $pdf->SetXY(120, $currentY+13.7);
        $pdf->Cell(45, 5, 'Convance Charges', 1,1);
        $pdf->SetXY(120, $currentY+20.5);
        $pdf->Cell(45, 5, 'Discount(Rs)', 1,1);
        // $pdf->SetXY(120, $currentY+27.3);
        // $pdf->Cell(45, 5, 'Net Amount', 1,1);
        // Change font size to 12 for "Net Amount"
        $pdf->SetFont('helvetica', 'B', 12);  
        $pdf->SetXY(120, $currentY+27.3);
        $pdf->Cell(45, 5, 'Net Amount', 1, 1);
        
        // // Column 4
        $pdf->SetFont('helvetica','', 10);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetXY(165, $currentY);
        $pdf->Cell(35, 5, $total_amount, 1, 'R');
        $pdf->SetXY(165, $currentY+6.8);
        $pdf->Cell(35, 5, $purchase['LaborCharges'], 1, 'R');
        $pdf->SetXY(165, $currentY+13.7);
        $pdf->Cell(35, 5, $purchase['ConvanceCharges'], 1, 'R');
        $pdf->SetXY(165, $currentY+20.5);
        $pdf->Cell(35, 5, $purchase['Bill_discount'], 1, 'R');
        $pdf->SetXY(165, $currentY+27.3);
        $net_amount=number_format(round($total_amount+$purchase['LaborCharges']+$purchase['ConvanceCharges']-$purchase['Bill_discount']));
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(35, 5,  $net_amount, 1, 'R');
        
        $pdf->SetFont('helvetica','BIU', 14);
        $pdf->SetTextColor(23, 54, 93);

        $pdf->SetXY(10, $currentY+20);
        $width = 100;
        $pdf->MultiCell($width, 10, $num_to_words, 0, 'L', 0, 1, '', '', true);
        $pdf->SetFont('helvetica','', 10);
        
        // Close and output PDF
        $pdf->Output('Sale Invoice_'.$purchase['prefix'].$purchase['Sal_inv_no'].'.pdf', 'I');
    }

    public function generatePDF(Request $request)
    {
        if($request->print_type==1){
            $this->showAllPDF($request->print_tpo);
        }
        elseif($request->print_type==2){
            $this->noLengthPDF($request->print_tpo);
        }
        elseif($request->print_type==3){
            $this->onlyPriceQtyPDF($request->print_tpo);
        }
    }

 }

