<?php

namespace App\Exports;

use App\Models\gd_pipe_item_ledger5_opp;
use Maatwebsite\Excel\Concerns\FromCollection;

class GoDownByItemNameILExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return gd_pipe_item_ledger5_opp::all();
    }
}
