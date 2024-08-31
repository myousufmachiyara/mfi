<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Traits\SaveImage;
use App\Models\AC;
use App\Models\complains;
use App\Models\complains_att;
use TCPDF;

class ComplainsController extends Controller
{
    //
    use SaveImage;
    public function index()
    {
        $complains = complains::where('complains.status', 1)
            ->leftjoin('ac as acc_name', 'acc_name.ac_code', '=', 'complains.company_name')
            ->join('ac as disp_to', 'disp_to.ac_code', '=', 'complains.party_name')
            ->select(
                'complains.id', 
                'complains.inv_dat', 
                'complains.mfi_pur_number', 
                'complains.mill_pur_number', 
                'complains.company_name',
                'complains.party_name', 
                'complains.complain_detail', 
                'complains.resolve_date', 
                'complains.resolve_remarks', 
                'complains.clear',
                'acc_name.ac_name as company_name_display', // example field from the first join
                'disp_to.ac_name as party_name_display' // example field from the second join
            )
            ->get();

            $acc = AC::where('status', 1)->orderBy('ac_name', 'asc')->get();

        return view('complains.index', compact('complains','acc'));
    }

    public function store(Request $request)
    {
        $complains = new complains();

        if ($request->has('inv_dat') && $request->inv_date) {
            $complains->complains=$request->complains;
        }
        if ($request->has('mfi_pur_number') && $request->mfi_pur_number) {
            $complains->mfi_pur_number = $request->mfi_pur_number;
        }

        if ($request->has('mill_pur_number') && $request->mill_pur_number) {
            $complains->mill_pur_number = $request->mill_pur_number;
        }

        if ($request->has('company_name') && $request->company_name) {
            $complains->company_name = $request->company_name;
        }

        if ($request->has('party_name') && $request->party_name) {
            $complains->party_name = $request->party_name;
        }

        if ($request->has('complain_detail') && $request->complain_detail) {
            $complains->complain_detail = $request->complain_detail;
        }

        if ($request->has('resolve_date') && $request->resolve_date) {
            $complains->resolve_date = $request->resolve_date;
        }

        if ($request->has('resolve_remarks') && $request->resolve_remarks) {
            $complains->resolve_remarks = $request->resolve_remarks;
        }

        if ($request->has('clear') && $request->clear) {
            $complains->clear = $request->clear;
        }

        $complains->save();

        $comp_id = complains::latest()->first();


        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $compAtt = new complains_att();
                $compAtt->complain_id = $comp_id['id'];
                $extension = $file->getClientOriginalExtension();
                $compAtt->att_path = $this->compDoc($file,$extension);
                $compAtt->save();
            }
        }
        return redirect()->route('all-complains');
    }


    public function destroy(Request $request)
    {
        $complains = complains::where('id', $request->complain_id)->update(['status' => '0']);
        return redirect()->route('all-complains');
    }

    public function update(Request $request)
    {
        

        // Find the existing complain
        $complains = complains::where('id', $request->update_id)->get()->first();
        

        // Update fields if present
        if ($request->has('update_inv_dat')) {
            $complains->inv_dat = $request->update_inv_dat;
        }

        if ($request->has('update_mfi_pur_number')) {
            $complains->mfi_pur_number = $request->update_mfi_pur_number;
        }

        if ($request->has('update_mill_pur_number')) {
            $complains->mill_pur_number = $request->update_mill_pur_number;
        }

        if ($request->has('update_company_name')) {
            $complains->company_name = $request->update_company_name;
        }

        if ($request->has('update_party_name')) {
            $complains->party_name = $request->update_party_name;
        }

        if ($request->has('update_complain_detail')) {
            $complains->complain_detail = $request->update_complain_detail;
        }

        if ($request->has('update_resolve_date')) {
            $complains->resolve_date = $request->update_resolve_date;
        }

        if ($request->has('update_resolve_remarks')) {
            $complains->resolve_remarks = $request->update_resolve_remarks;
        }

        if ($request->has('update_complain_status')) {
            $complains->clear = $request->update_complain_status;
        }


        // Save the updated complain
        $complains->save();

        if($request->hasFile('att')){
            $files = $request->file('att');
            foreach ($files as $file)
            {
                $compAtt = new complains_att();
                $compAtt->complain_id = $request->update_id;
                $extension = $file->getClientOriginalExtension();
                $compAtt->att_path = $this->compDoc($file,$extension);
                $compAtt->save();
            }
        }

        // Redirect to the desired route
        return redirect()->route('all-complains')->with('success', 'Complain updated successfully.');
    }

    public function getComplainsDetails(Request $request)
    {
        $complains = complains::where('id', $request->id)->get()->first();
        return $complains;
    } 

    public function getAttachements(Request $request)
    {
        $complains_att = complains_att::where('complain_id', $request->id)->get();
        return $complains_att;
    }

    public function deleteAtt($id)
    {
        $doc=complains_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $complains_att = complains_att::where('att_id', $id)->delete();
            return response()->json(['message' => 'File deleted successfully.']);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }	
    }

    public function view($id)
    {
        $doc=complains_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::file($filePath);
        } 
    }

    public function downloadAtt($id)
    {
        $doc=complains_att::where('att_id', $id)->select('att_path')->first();
        $filePath = public_path($doc['att_path']);
        if (file_exists($filePath)) {
            return Response::download($filePath);
        } 
    }


}
