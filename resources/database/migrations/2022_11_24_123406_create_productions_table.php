<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->date('date');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('recipe_id');
            $table->unsignedInteger('unit_id');
            $table->integer('warehouse_id');
            $table->decimal('total_qty');  //net weight
            $table->decimal('gross_weight');
            $table->decimal('total_rate');
            $table->decimal('total_amount');
            $table->string('remarks', 250)->nullable();
            $table->string('color', 50)->nullable();
            $table->integer('machine_id')->unsigned()->nullable();
            $table->integer('shift_id')->unsigned()->nullable();
            $table->integer('forman_id')->unsigned()->nullable();
            $table->integer('operator_id')->unsigned()->nullable();
            $table->decimal('thickness')->nullable();
            $table->decimal('width')->nullable();
            $table->string('batchNo', 50)->nullable();
            $table->integer('p_status')->nullable();
            $table->string('p_type', 30)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('uoms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productions');
    }
};