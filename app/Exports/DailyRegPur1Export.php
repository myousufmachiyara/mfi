<?php

namespace App\Exports;

use App\Models\activite7_pur;
use Maatwebsite\Excel\Concerns\FromCollection;

class DailyRegPur1Export implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return activite7_pur::all();
    }
}
