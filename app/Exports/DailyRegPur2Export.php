<?php

namespace App\Exports;

use App\Models\activite13_pur_pipe;
use Maatwebsite\Excel\Concerns\FromCollection;

class DailyRegPur2Export implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return activite13_pur_pipe::all();
    }
}
