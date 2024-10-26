<?php

namespace App\Exports;

use App\Models\pur_by_account;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Purchase1Export implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Sales Date',
            'Inv No.',
            'Mill No.',
            'Dispatch To Party',
            'Sale Inv',
            'Remarks',
            'Amount',
        ];
    }
}
