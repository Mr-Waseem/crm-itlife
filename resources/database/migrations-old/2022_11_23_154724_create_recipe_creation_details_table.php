<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recipe_creation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipe_creation_id');
            $table->unsignedInteger('product_id');
            $table->integer('warehouse_id');
            $table->integer('voucher_no')->nullable();
            $table->unsignedInteger('unit_id');
            $table->decimal('quantity');
            $table->decimal('rate');
            $table->decimal('amount');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();


            $table->foreign('recipe_creation_id')->references('id')->on('recipe_creations')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('uoms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recipe_creation_details');
    }
};