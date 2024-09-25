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
use App\Models\bad_dabs;
use App\Models\bad_dabs_2;
use TCPDF;


class BadDabsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SaveImage;

    public function index()
    {
        $bad_dabs = bad_dabs::where('bad_dabs.status', 1)
        ->leftjoin ('bad_dabs_2', 'bad_dabs_2.bad_dabs_cod' , '=', 'bad_dabs.bad_dabs_id')
        ->select(
            'bad_dabs.bad_dabs_id','bad_dabs.date','bad_dabs.reason',
            \DB::raw('SUM(bad_dabs_2.pc_add) as add_sum'),
            \DB::raw('SUM(bad_dabs_2.pc_less) as less_sum'),
        )
        ->groupby('bad_dabs.bad_dabs_id','bad_dabs.date','bad_dabs.reason')
        ->get();

        return view('bad_dabs.index',compact('bad_dabs'));
    }


    public function create(Request $request)
    {
        $items = Item_entry::orderBy('item_name', 'asc')->get();
        $coa = AC::orderBy('ac_name', 'asc')->get();
        return view('bad_dabs.create',compact('items','coa'));
    }

    public function store(Request $request)
    {
        $userId = 1;
        $bad_dabs = new bad_dabs();
    
        if ($request->has('date') && $request->date) {
            $bad_dabs->date = $request->date;
        }
        if ($request->has('reason') && $request->reason) {
            $bad_dabs->reason = $request->reason; 
        }
    
        $bad_dabs->created_by = $userId;
        $bad_dabs->status = 1;
    
        $bad_dabs->save();
    
        $tbad_id = bad_dabs::latest()->value('bad_dabs_id');
    
        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $bad_dabs_2 = new bad_dabs_2();
                    $bad_dabs_2->bad_dabs_cod = $tbad_id;
                    $bad_dabs_2->item_cod = $request->item_code[$i];
                    if ($request->item_remarks[$i]!=null) {
                        $bad_dabs_2->remarks=$request->item_remarks[$i];
                    }
                    if ($request->qty_add[$i]!=null) {
                        $bad_dabs_2->pc_add=$request->qty_add[$i];
                    }
                    if ($request->qty_less[$i]!=null) {
                        $bad_dabs_2->pc_less=$request->qty_less[$i];
                    }
                    $bad_dabs_2->save();
                }
            }
        }
    
        return redirect()->route('all-bad-dabs');
    }


    public function destroy(Request $request)
    {
        $bad_dabs = bad_dabs::where('bad_dabs_id', $request->delete_bad_dabs_id)->update(['status' => '0']);
        return redirect()->route('all-bad-dabs');
    }

    
    public function edit($id)
    {
        $bad_dabs = bad_dabs::where('bad_dabs_id', $id)->first();
        $bad_dabs_items = bad_dabs_2::where('bad_dabs_cod', $id)->get();
        $bad_dabs_item_count = count($bad_dabs_items);
        $items = Item_entry::all();
    
        // Calculate the total_add and total_less
        $total_add = $bad_dabs_items->sum('pc_add');
        $total_less = $bad_dabs_items->sum('pc_less');
    
        return view('bad_dabs.edit', compact('bad_dabs', 'bad_dabs_items', 'items', 'bad_dabs_item_count', 'total_add', 'total_less'));
    }
    

    public function update(Request $request)
    {
        $bad_dabs = bad_dabs::where('bad_dabs_id',$request->bad_dabs_id)->get()->first();

        if ($request->has('date') && $request->date) {
            $bad_dabs->date=$request->date;
        }
        if ($request->has('reason') && $request->reason OR empty($request->reason)) {
            $bad_dabs->reason=$request->reason;
        }

        bad_dabs::where('bad_dabs_id', $request->bad_dabs_id)->update([
            'reason'=>$bad_dabs->reason,
            'date'=>$bad_dabs->date,
        ]);
        
        bad_dabs_2::where('bad_dabs_cod', $request->bad_dabs_id)->delete();
        
        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {

                if(filled($request->item_code[$i]))
                {
                    $bad_dabs_2 = new bad_dabs_2();
                    $bad_dabs_2->bad_dabs_cod=$request->bad_dabs_id;
                    $bad_dabs_2->item_cod=$request->item_code[$i];
                    if ($request->remarks[$i]!=null OR empty($request->remarks[$i])) {
                        $bad_dabs_2->remarks=$request->remarks[$i];
                    }
                    $bad_dabs_2->pc_add=$request->qty_add[$i];
                    $bad_dabs_2->pc_less=$request->qty_less[$i];
                    $bad_dabs_2->save();
                }
            }
        }

        return redirect()->route('all-bad-dabs');
    }

}