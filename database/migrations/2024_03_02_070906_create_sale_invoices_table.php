<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('Sal_inv_no');
            $table->string('sa_date');
            $table->integer('account_name');
            $table->string('Sales_remarks');
            $table->string('Cash_pur_name');
            $table->string('cash_Pur_address');
            $table->integer('Bill_discount');
            $table->integer('pur_ord_no');
            $table->integer('Gst_sal');
            $table->integer('sed_sal');
            $table->integer('ConvanceCharges');
            $table->integer('LaborCharges');
            $table->string('cash-pur_phone');
            $table->integer('bill_not');
            $table->string('att');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoices');
    }
};
