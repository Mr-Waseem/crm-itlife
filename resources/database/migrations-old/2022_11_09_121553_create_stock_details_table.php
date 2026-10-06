<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_details', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->integer('stock_id');
            $table->date('date');
            $table->string('type', 50);
            $table->string('transaction_type', 50);
            $table->integer('dcn_no')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('supplier_id')->default(0);
            $table->integer('purchaser_id')->default(0);
            $table->integer('department_id')->nullable();
            $table->integer('party_id');
            $table->integer('product_id');
            $table->integer('unit_id');
            $table->integer('discount_id')->nullable();
            $table->decimal('qty_in');
            $table->decimal('qty_out');
            $table->decimal('product_cost');
            $table->decimal('cost_amount');
            $table->decimal('sale_rate');
            $table->decimal('sale_amount');
            $table->string('packing')->nullable();
            $table->decimal('net_weight')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_details');
    }
};