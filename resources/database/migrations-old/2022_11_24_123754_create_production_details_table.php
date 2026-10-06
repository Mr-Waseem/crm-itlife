<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('production_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('unit_id');
            $table->integer('voucher_no')->nullable();
            $table->integer('warehouse_id');
            $table->decimal('quantity');
            $table->decimal('rate');
            $table->decimal('amount');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();


            $table->foreign('production_id')->references('id')->on('productions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('uoms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_details');
    }
};