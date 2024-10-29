<?php

namespace App\Exports;

use App\Models\pipe_sale_by_account;
use Maatwebsite\Excel\Concerns\FromCollection;

class Sale2Export implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return pipe_sale_by_account::all();
    }
}
