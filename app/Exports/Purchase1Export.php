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
            'Column 1',
            'Column 2',
            'Column 3',
            'Column 4',
            'Column 5',
            'Column 6',
            'Column 7',
            'Column 8',
        ];
    }
}
