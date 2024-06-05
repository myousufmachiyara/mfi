<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\jvsingel;
use App\Models\jv1_att;
use App\Models\AC;
use App\Traits\SaveImage;
use TCPDF;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class JV1Controller extends Controller
{
    use SaveImage;

    public function index()
    {
        $jv1 = jvsingel::where('jvsingel.status', 1)
                ->join('ac as d_ac', 'd_ac.ac_code', '=', 'jvsingel.ac_dr_sid')
                ->join('ac as c_ac', 'c_ac.ac_code', '=', 'jvsingel.ac_cr_sid')
                ->select('jvsingel.*', 
                'd_ac.ac_name as debit_account', 
                'c_ac.ac_name as credit_account')
                ->get();
        $acc = AC::where('status', 1)->get();

        return view('vouchers.jv1',compact('jv1','acc'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ac_dr_sid' => 'required',
            'ac_cr_sid' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jv1 = new jvsingel();

        if ($request->has('ac_dr_sid') && $request->ac_dr_sid) {
            $jv1->ac_dr_sid=$request->ac_dr_sid;
        }
        if ($request->has('ac_cr_sid') && $request->ac_cr_sid) {
            $jv1->ac_cr_sid=$request->ac_cr_sid;
        }
        if ($request->has('amount') && $request->amount OR $request->amount==0 ) {
            $jv1->amount=$request->amount;
        }
        if ($request->has('date') && $request->date) {
            $jv1->date=$request->date;
        }
        if ($request->has('remarks') && $request->remarks) {
            $jv1->remarks=$request->remarks;
        }
        $jv1->save();

        $latest_jv1 = jvsingel::latest()->first();

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $jv1_att = new jv1_att();
                $jv1_att->jv1_id = $latest_jv1['auto_lager'];
                $extension = $file->getClientOriginalExtension();
                $jv1_att->att_path = $this->jv1Doc($file,$extension);
                $jv1_att->save();
            }
        }
        return redirect()->route('all-jv1');
    }
    
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'update_ac_dr_sid' => 'required',
            'update_ac_cr_sid' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jv1 = jvsingel::where('auto_lager', $request->update_auto_lager)->get()->first();
        
        if ($request->has('update_ac_dr_sid') && $request->update_ac_dr_sid) {
            $jv1->ac_dr_sid=$request->update_ac_dr_sid;
        }
        if ($request->has('update_ac_cr_sid') && $request->update_ac_cr_sid) {
            $jv1->ac_cr_sid=$request->update_ac_cr_sid;
        }
        if ($request->has('update_amount') && $request->update_amount OR $request->update_amount==0 ) {
            $jv1->amount=$request->update_amount;
        }
        if ($request->has('update_date') && $request->update_date) {
            $jv1->date=$request->update_date;
        }
        if ($request->has('update_remarks') && $request->update_remarks) {
            $jv1->remarks=$request->update_remarks;
        }
    
        jvsingel::where('auto_lager', $request->update_auto_lager)->update([
            'ac_dr_sid'=>$jv1->ac_dr_sid,
            'ac_cr_sid'=>$jv1->ac_cr_sid,
            'amount'=>$jv1->amount,
            'date'=>$jv1->date,
            'remarks'=>$jv1->remarks,
        ]);

        $latest_jv1 = jvsingel::latest()->first();
        if($request->hasFile('update_att')){
            
            jv1_att::where('jv1_id', $request->update_auto_lager)->delete();
            $files = $request->file('update_att');
            foreach ($files as $file)
            {
                $jv1_att = new jv1_att();
                $jv1_att->jv1_id = $latest_jv1['auto_lager'];
                $extension = $file->getClientOriginalExtension();
                $jv1_att->att_path = $this->jv1Doc($file,$extension);
                $jv1_att->save();
            }
        }

        return redirect()->route('all-jv1');
    }

    public function destroy(Request $request)
    {
        $jv1 = jvsingel::where('auto_lager', $request->delete_auto_lager)->update(['status' => '0']);
        return redirect()->route('all-jv1');
    }

    public function getAttachements(Request $request)
    {
        $jv1_atts = jv1_att::where('jv1_id', $request->id)->get();
        return $jv1_atts;
    }

    public function getJVDetails(Request $request)
    {
        $jv1_details = jvsingel::where('auto_lager', $request->id)->get()->first();
        return $jv1_details;
    }

    public function view($id)
    {
        $doc=jv1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=jv1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }

    public function deleteAtt($id)
    {
        $doc=jv1_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);

        if (File::exists($filePath)) {
            File::delete($filePath);
            $jv1_att = jv1_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }
    }

}
