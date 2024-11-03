<?php

namespace App\Exports;

use App\Models\gd_pipe_item_ledger5_opp;
use App\Models\gd_pipe_item_ledger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GoDownByItemNameILExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $op_qty;

    public function __construct($data, $op_qty)
    {
        $this->data = $data;
        $this->$op_qty = $op_qty;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Voucher No.',
            'Date',
            'Entry Of',
            'Account Name',
            'Remarks',
            'Add',
            'Less',
            'Balance (Opening Bal:'.$this->op_qty.')',
        ];
    }
}
