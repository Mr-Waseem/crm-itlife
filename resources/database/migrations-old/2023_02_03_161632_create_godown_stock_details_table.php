<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('godown_stock_details', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->integer('transaction_id');
            $table->integer('product_id');
            $table->integer('inward_gatepass_id');
            $table->date('date');
            $table->string('type')->nullable();
            $table->integer('warehouse_id');
            $table->integer('party_id');
            $table->decimal('qty_in')->nullable();
            $table->decimal('qty_out')->nullable();
            $table->decimal('rate')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->decimal('sale_rate')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('godown_stock_details');
    }
};
