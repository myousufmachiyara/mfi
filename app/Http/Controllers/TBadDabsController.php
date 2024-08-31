<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Traits\SaveImage;
use App\Models\AC;
use App\Models\Item_entry2;
use App\Models\TBadDabs;
use App\Models\TBadDabs2;
use TCPDF;


class TBadDabsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SaveImage;

    public function index()
    {
        $tbad_dabs = TBadDabs::where('tbad_dabs.status', 1)
        ->leftjoin ('tbad_dabs_2', 'tbad_dabs_2.bad_dabs_cod' , '=', 'tbad_dabs.bad_dabs_id')
        ->select(
            'tbad_dabs.bad_dabs_id','tbad_dabs.date','tbad_dabs.reason',
            \DB::raw('SUM(tbad_dabs_2.pc_add) as add_sum'),
            \DB::raw('SUM(tbad_dabs_2.pc_less) as less_sum'),
        )
        ->groupby('tbad_dabs.bad_dabs_id','tbad_dabs.date','tbad_dabs.reason')
        ->get();

        return view('tbad_dabs.index',compact('tbad_dabs'));
    }


    public function create(Request $request)
    {
        $items = Item_entry2::all();
        $coa = AC::all();
        return view('tbad_dabs.create',compact('items','coa'));
    }

    public function store(Request $request)
    {
        $userId = 1;
        $tbad_dabs = new TBadDabs();
    
        if ($request->has('date') && $request->date) {
            $tbad_dabs->date = $request->date;
        }
        if ($request->has('reason') && $request->reason) {
            $tbad_dabs->reason = $request->reason; 
        }
    
        $tbad_dabs->created_by = $userId;
        $tbad_dabs->status = 1;
    
        $tbad_dabs->save();
    
        $tbad_id = TBadDabs::latest()->value('bad_dabs_id');
    
        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $tbad_dabs_2 = new TBadDabs2();
                    $tbad_dabs_2->bad_dabs_cod = $tbad_id;
                    $tbad_dabs_2->item_cod = $request->item_code[$i];
                    if ($request->item_remarks[$i]!=null) {
                        $tbad_dabs_2->remarks=$request->item_remarks[$i];
                    }
                    if ($request->qty_add[$i]!=null) {
                        $tbad_dabs_2->pc_add=$request->qty_add[$i];
                    }
                    if ($request->qty_less[$i]!=null) {
                        $tbad_dabs_2->pc_less=$request->qty_less[$i];
                    }
                    $tbad_dabs_2->save();
                }
            }
        }
    
        return redirect()->route('all-tbad-dabs');
    }


    public function destroy(Request $request)
    {
        $tbad_dabs = TBadDabs::where('bad_dabs_id', $request->delete_tbad_dabs_id)->update(['status' => '0']);
        return redirect()->route('all-tbad-dabs');
    }

    
    public function edit($id)
    {
        $tbad_dabs = TBadDabs::where('bad_dabs_id', $id)->first();
        $tbad_dabs_items = TBadDabs2::where('bad_dabs_cod', $id)->get();
        $tbad_dabs_item_count = count($tbad_dabs_items);
        $items = Item_entry2::all();
    
        // Calculate the total_add and total_less
        $total_add = $tbad_dabs_items->sum('pc_add');
        $total_less = $tbad_dabs_items->sum('pc_less');
    
        return view('tbad_dabs.edit', compact('tbad_dabs', 'tbad_dabs_items', 'items', 'tbad_dabs_item_count', 'total_add', 'total_less'));
    }
    

    public function update(Request $request)
    {
        $tbad_dabs = TBadDabs::where('bad_dabs_id',$request->bad_dabs_id)->get()->first();

        if ($request->has('date') && $request->date) {
            $tbad_dabs->date=$request->date;
        }
        if ($request->has('reason') && $request->reason) {
            $tbad_dabs->reason=$request->reason;
        }

        TBadDabs::where('bad_dabs_id', $request->bad_dabs_id)->update([
            'reason'=>$tbad_dabs->reason,
            'date'=>$tbad_dabs->date,
        ]);
        
        TBadDabs2::where('bad_dabs_cod', $request->bad_dabs_id)->delete();
        
        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {

                if(filled($request->item_code[$i]))
                {
                    $tbad_dabs_2 = new TBadDabs2();
                    $tbad_dabs_2->bad_dabs_cod=$request->bad_dabs_id;
                    $tbad_dabs_2->item_cod=$request->item_code[$i];
                    $tbad_dabs_2->remarks=$request->remarks[$i];
                    $tbad_dabs_2->pc_add=$request->qty_add[$i];
                    $tbad_dabs_2->pc_less=$request->qty_less[$i];
                    $tbad_dabs_2->save();
                }
            }
        }

        return redirect()->route('all-tbad-dabs');
    }



}