<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item_entry;
use App\Models\Item_Groups;

class ItemsController extends Controller
{
    
    public function index()
    {
        $items = Item_entry::where('item_entry.status', 1)
                ->join('item_group as ig', 'ig.item_group_cod', '=', 'item_entry.item_group')
                ->get();
        $itemGroups = Item_Groups::where('status', 1)->get();

        return view('items.index',compact('items','itemGroups'));
    }

    public function create()
    {
        $item_groups = Item_Groups::all();
        return view('items.create',compact('item_groups'));
    }

    public function store(Request $request)
    {
        $userId=1;

        if($request->has('items'))
        {
            for($i=0;$i<$request->items;$i++)
            {
                if(filled($request->item_name[$i]))
                {
                    $item_entry = new Item_entry();
                    $item_entry->item_name=$request->item_name[$i];
                    $item_entry->item_group=$request->item_group[$i];
                    $item_entry->item_remark=$request->item_remarks[$i];
                    $item_entry->sales_price=$request->item_s_price[$i];
                    $item_entry->OPP_qty_cost=$request->item_pur_cost[$i];
                    $item_entry->qty=$request->item_stock[$i];
                    $item_entry->opp_qty=$request->item_stock[$i];
                    $item_entry->opp_date=$request->item_date[$i];
                    $item_entry->stock_level=$request->item_stock_level[$i];
                    $item_entry->labourprice=$request->item_l_price[$i];
                    $item_entry->status=1;
                    $item_entry->created_by=$userId;

                    $item_entry->save();
                }
            }
        }

        return redirect()->route('all-items');
    }

    public function destroy(Request $request)
    {
        $item_groups = Item_entry::where('it_cod', $request->item_id)->update(['status' => '0']);
        return redirect()->route('all-items');
    }

    public function update(Request $request)
    {
                
        if ($request->has('item_group') && $request->item_group) {
            $item_group=$request->item_group;
        }
        if ($request->has('item_name') && $request->item_name) {
            $item_name=$request->item_name;
        }
        if ($request->has('item_remark') && $request->item_remark) {
            $item_remark=$request->item_remark;
        }
        if ($request->has('sales_price') && $request->sales_price) {
            $sales_price=$request->sales_price;
        }
        if ($request->has('OPP_qty_cost') && $request->OPP_qty_cost) {
            $OPP_qty_cost=$request->OPP_qty_cost;
        }
        if ($request->has('qty') && $request->qty) {
            $qty=$request->qty;
        }
        if ($request->has('date') && $request->date) {
            $date=$request->date;
        }
        if ($request->has('stock_level') && $request->stock_level) {
            $stock_level=$request->stock_level;
        }
        if ($request->has('labourprice') && $request->labourprice) {
            $labourprice=$request->labourprice;
        }
        if ($request->has('item_name') && $request->item_name) {
            $item_name=$request->item_name;
        }
       
        Item_entry::where('it_cod', $request->it_cod)->update([
            'item_name'=>$item_name,
            'item_group'=>$item_group,
            'item_remark'=>$item_remark,
            'opp_qty'=>$qty,
            'OPP_qty_cost'=>$OPP_qty_cost,
            'sales_price'=>$sales_price,
            'opp_date'=>$date,
            'stock_level'=>$stock_level,
            'labourprice'=>$labourprice,
            'qty'=>$labourprice
        ]);
        
        return redirect()->route('all-items');
    }

    public function getItemDetails(Request $request)
    {
        $item_details = Item_entry::where('it_cod', $request->id)->get();
        return $item_details;
    }
    
}
