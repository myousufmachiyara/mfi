<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item_entry;

class ItemsController extends Controller
{
    //
    public function getItemDetails(Request $request)
    {
        $item_details = Item_entry::where('it_cod', $request->id)->get();
        return $item_details;
    }
}
