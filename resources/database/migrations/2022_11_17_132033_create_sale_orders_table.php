<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no')->index();
            $table->date('voucher_date')->index();
            $table->integer('party_id')->index();
            $table->string('remarks', 250)->nullable();
            $table->string('payment_mode', 250)->nullable();
            $table->integer('credit_days')->nullable();
            $table->date('po_date')->nullable();
            $table->string('po_no')->nullable();
            $table->string('shipment_term')->nullable();
            $table->decimal('total_qty')->nullable();
            $table->decimal('total_packing')->nullable();
            $table->decimal('total_order_qty')->nullable();
            $table->decimal('total_sale_rate')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_orders');
    }
};