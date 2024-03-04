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
        Schema::create('sale_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->integer('sales_inv_cod');
            $table->integer('item_cod');
            $table->String('remarks');
            $table->integer('Sales_qty');
            $table->integer('sales_price');
            $table->integer('Sales_qty2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoice_details');
    }
};
