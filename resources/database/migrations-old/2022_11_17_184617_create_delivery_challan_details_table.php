<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_challan_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('challan_id');
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('customer_product_id')->nullable();
            $table->unsignedInteger('party_id')->nullable();
            $table->unsignedInteger('uom_id')->nullable();
            $table->integer('voucher_no')->index();
            $table->string('type');
            $table->date('voucher_date')->index();
            $table->integer('warehouse_id');
            $table->integer('demandqty');
            $table->integer('quantity');
            $table->integer('sale_qty');
            $table->integer('remaining_qty');
            $table->string('comments');
            $table->decimal('sale_rate');
            $table->decimal('tax_rate');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('challan_id')->references('id')->on('delivery_challans')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_challan_details');
    }
};
