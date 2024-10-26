<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gd extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the class name
    protected $table = 'gd'; // Change this if needed

    // Specify which attributes are mass assignable
    protected $fillable = [
        'it_cod',
        'quantity',
    ];

    // Method to execute the custom query
    public static function getItemsQuantities($endDate)
    {
        return DB::select(DB::raw("
            SELECT it_cod, qty AS quantity
            FROM gd_pipe_item_ledger1_opp

            UNION ALL

            SELECT item_cod AS it_cod, SUM(Sales_qty) AS quantity
            FROM tstock_in_2
            JOIN tstock_in ON tstock_in.Sal_inv_no = tstock_in_2.sales_inv_cod
            WHERE tstock_in.sa_date < :end_date_2
            GROUP BY tstock_in_2.item_cod

            UNION ALL

            SELECT item_cod AS it_cod, -SUM(sales_qty) AS quantity
            FROM tstock_out_2
            JOIN tstock_out ON tstock_out.Sal_inv_no = tstock_out_2.sales_inv_cod
            WHERE tstock_out.sa_date < :end_date_2  
            GROUP BY tstock_out_2.item_cod

            UNION ALL

            SELECT item_entry2.it_cod AS it_cod, SUM(tbad_dabs_2.pc_add) AS quantity
            FROM item_entry2
            JOIN tbad_dabs_2 ON item_entry2.it_cod = tbad_dabs_2.item_cod
            JOIN tbad_dabs ON tbad_dabs_2.bad_dabs_cod = tbad_dabs.bad_dabs_id
            WHERE tbad_dabs.date < :end_date_2 
            GROUP BY item_entry2.it_cod

            UNION ALL

            SELECT item_entry2.it_cod AS it_cod, SUM(tbad_dabs_2.pc_less * -1) AS quantity
            FROM item_entry2
            JOIN tbad_dabs_2 ON item_entry2.it_cod = tbad_dabs_2.item_cod
            JOIN tbad_dabs ON tbad_dabs_2.bad_dabs_cod = tbad_dabs.bad_dabs_id
            WHERE tbad_dabs.date < :end_date_2  
            GROUP BY item_entry2.it_cod
        "), [
            'end_date_2' => $endDate,
        ]);
    }
}
