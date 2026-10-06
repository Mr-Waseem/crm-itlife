<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('voucher_no');
            $table->date('date');
            $table->integer('product_id')->unsigned();
            $table->integer('recipe_id')->unsigned()->nullable();
            $table->integer('unit_id')->unsigned()->nullable();
            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->integer('from_warehouse_id')->unsigned()->nullable();
            $table->integer('to_warehouse_id')->unsigned()->nullable();
            $table->integer('transaction_id')->unsigned()->nullable();
            $table->decimal('total_qty', 20,2)->nullable();
            $table->decimal('out_qty', 20,2)->nullable();
            $table->decimal('gross_weight', 20,2)->nullable();
            $table->decimal('total_rate', 20,2)->nullable();
            $table->decimal('total_amount', 20,2)->nullable();
            $table->string('remarks', 250)->nullable();
            $table->integer('color_id')->unsigned()->nullable();
            $table->integer('machine_id')->unsigned()->nullable();
            $table->integer('shift_id')->unsigned()->nullable();
            $table->integer('forman_id')->unsigned()->nullable();
            $table->integer('operator_id')->unsigned()->nullable();
            $table->decimal('thickness', 10,2)->nullable();
            $table->decimal('width', 10,2)->nullable();
            $table->string('batchNo', 50)->nullable();
            $table->integer('p_status')->nullable();
            $table->string('p_type', 30)->nullable();
            $table->decimal('balance_weight', 20,2)->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('uoms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_stocks');
    }
};
