<?php

namespace App\Exports;

use App\Models\activites9_gen_acas;
use Maatwebsite\Excel\Concerns\FromCollection;

class DailyRegJV2Export implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return activites9_gen_acas::all();
    }
}
