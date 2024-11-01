<?php

namespace App\Exports;

use App\Models\activite11_sales_pipe;
use Maatwebsite\Excel\Concerns\FromCollection;

class DailyRegSale2Export implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return activite11_sales_pipe::all();
    }
}
