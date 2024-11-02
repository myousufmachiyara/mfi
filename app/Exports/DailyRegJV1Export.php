<?php

namespace App\Exports;

use App\Models\activites10_gen_ac;
use Maatwebsite\Excel\Concerns\FromCollection;

class DailyRegJV1Export implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return activites10_gen_ac::all();
    }
}
