<?php

namespace App\Exports;

use App\Models\all_payments_by_party;
use Maatwebsite\Excel\Concerns\FromCollection;

class VouchersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return all_payments_by_party::all();
    }
}
