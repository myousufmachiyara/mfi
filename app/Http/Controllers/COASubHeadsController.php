<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sub_head_of_acc;

class COASubHeadsController extends Controller
{
    //
    public function index()
    {
        $subheads = sub_head_of_acc::where('status', 1)
        ->join('head_of_acc as hoa', 'hoa.id', '=', 'sub_head_of_acc.main')
        ->get();
        return view('ac.subheads',compact('subheads'));
    }

    public function store(Request $request)
    {
        $item_group = new Item_Groups();

        if ($request->has('group_name') && $request->group_name) {
            $item_group->group_name=$request->group_name;
        }
        if ($request->has('group_remarks') && $request->group_remarks) {
            $item_group->group_remarks=$request->group_remarks;
        }
        $item_group->save();
        return redirect()->route('all-item-groups');
    }
    
    public function destroy(Request $request)
    {
        $item_group = Item_Groups::where('item_group_cod', $request->item_group_cod)->update(['status' => '0']);
        return redirect()->route('all-item-groups');
    }

    public function update(Request $request)
    {
        $group_name= null;
        $group_remarks=null;
       
        if ($request->has('group_name') && $request->group_name) {
            $group_name=$request->group_name;
        }
        if ($request->has('group_remarks') && $request->group_remarks) {
            $group_remarks=$request->group_remarks;
        }
       
        Item_Groups::where('item_group_cod', $request->item_group_cod)->update([
            'group_name'=>$group_name,
            'group_remarks'=>$group_remarks
        ]);
        
        return redirect()->route('all-item-groups');
    }
    public function getAccountDetails(Request $request)
    {
        $acc_details = AC::where('ac_code', $request->id)->get();
        return $acc_details;
    }
}
