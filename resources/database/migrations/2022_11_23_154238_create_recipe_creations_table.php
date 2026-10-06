<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recipe_creations', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->date('date');
            $table->integer('recipe_code');
            $table->unsignedInteger('product_id');
            $table->integer('warehouse_id');
            $table->string('recipe_name')->nullable();
            $table->unsignedInteger('unit_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('uoms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recipe_creations');
    }
};