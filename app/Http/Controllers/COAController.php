<?php

namespace App\Http\Controllers;

use App\Traits\SaveImage;
use Illuminate\Http\Request;
use App\Models\AC;
use App\Models\sub_head_of_acc;
use App\Models\ac_group;

class COAController extends Controller
{
    //
    public function index()
    {
        $acc = AC::where('status', 1)->get();
        $sub_head_of_acc = sub_head_of_acc::where('status', 1)->get();
        $ac_group = ac_group::where('status', 1)->get();
        return view('ac.index',compact('acc','sub_head_of_acc','ac_group'));
    }

    public function store(Request $request)
    {

        $acc = new AC();
        $acc->created_by=1;
        if ($request->has('ac_name') && $request->ac_name) {
            $acc->ac_name=$request->ac_name;
        }
        if ($request->has('rec_able') && $request->rec_able OR $request->rec_able==0 ) {
            $acc->rec_able=$request->rec_able;
        }
        if ($request->has('pay_able') && $request->pay_able OR $request->pay_able==0 ) {
            $acc->pay_able=$request->pay_able;
        }
        if ($request->has('opp_date') && $request->opp_date) {
            $acc->opp_date=$request->opp_date;
        }
        if ($request->has('remarks') && $request->remarks) {
            $acc->remarks=$request->remarks;
        }
        if ($request->has('address') && $request->address) {
            $acc->address=$request->address;
        }
        if ($request->has('phone_no') && $request->phone_no) {
            $acc->phone_no=$request->phone_no;
        }
        if ($request->has('group_cod') && $request->group_cod) {
            $acc->group_cod=$request->group_cod;
        }
        if ($request->has('AccountType') && $request->AccountType) {
            $acc->AccountType=$request->AccountType;
        }
        if($request->hasFile('att')){
            $extension = $request->file('att')->getClientOriginalExtension();
            $acc->att = $this->coaDoc($request->file('att'),$extension);
        }
        $acc->save();

        return redirect()->route('all-acc');
    }
    
    public function destroy(Request $request)
    {
        $acc = AC::where('ac_cod', $request->acc_id)->update(['status' => '0']);
        return redirect()->route('all-acc');
    }

    public function update(Request $request)
    {
       
        $acc = AC::where('ac_code', $request->ac_cod)->get();

        if ($request->has('ac_name') && $request->ac_name) {
            $acc->ac_name=$request->ac_name;
        }
        if ($request->has('rec_able') && $request->rec_able) {
            $acc->rec_able=$request->rec_able;
        }
        if ($request->has('pay_able') && $request->pay_able) {
            $acc->pay_able=$request->pay_able;
        }
        if ($request->has('opp_date') && $request->opp_date) {
            $acc->opp_date=$request->opp_date;
        }
        if ($request->has('remarks') && $request->remarks) {
            $acc->remarks=$request->remarks;
        }
        if ($request->has('address') && $request->address) {
            $acc->address=$request->address;
        }
        if ($request->has('phone_no') && $request->phone_no) {
            $acc->phone_no=$request->phone_no;
        }
        if ($request->has('group_cod') && $request->group_cod) {
            $acc->group_cod=$request->group_cod;
        }
        if ($request->has('AccountType') && $request->AccountType) {
            $acc->AccountType=$request->AccountType;
        }
        if ($request->has('att') && $request->att) {
            $acc->att=$request->att;
        }
       
        AC::where('ac_code', $request->ac_cod)->update([
            'ac_name'=>$acc->ac_name,
            'rec_able'=>$acc->rec_able,
            'pay_able'=>$acc->pay_able,
            'opp_date'=>$acc->opp_date,
            'remarks'=>$acc->remarks,
            'address'=>$acc->address,
            'phone_no'=>$acc->phone_no,
            'group_cod'=>$acc->group_cod,
            'AccountType'=>$acc->AccountType,
            'att'=>$acc->att,
        ]);

        return redirect()->route('all-acc');
    }

    public function getAccountDetails(Request $request)
    {
        $acc_details = AC::where('ac_code', $request->id)->get()->first();
        return $acc_details;
    }
}
