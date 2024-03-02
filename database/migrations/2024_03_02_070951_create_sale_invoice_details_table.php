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
            $table->foreign('sales_inv_cod')->references('id')->on('sale_invoice')->onDelete('cascade');
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
