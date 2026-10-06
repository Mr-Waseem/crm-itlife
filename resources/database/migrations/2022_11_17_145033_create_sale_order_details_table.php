<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sale_order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->integer('party_voucher_no');
            $table->date('voucher_date');
            $table->unsignedBigInteger('sale_order_id');
            $table->foreign('sale_order_id')->references('id')->on('sale_orders')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('party_id');
            $table->unsignedInteger('product_id');
            // $table->foreign('product_id')->references('id')->on('customer_products')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('qty');
            $table->decimal('packing');
            $table->decimal('order_qty');
            $table->integer('remaing_qty');
            $table->decimal('sale_rate');
            $table->decimal('excl_value');
            $table->decimal('s_tax');
            $table->decimal('st_value');
            $table->decimal('sale_amount');
            $table->date('delivery_date')->nullable();
            $table->string('remark')->nullable();
            $table->integer('status', 1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_order_details');
    }
};