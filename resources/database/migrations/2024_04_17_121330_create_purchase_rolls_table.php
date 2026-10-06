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
        Schema::create('purchase_rolls', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->date('date');
            $table->integer('warehouse_id')->nullable();
            $table->integer('igp');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by	')->nullable();
            // $table->integer('ipg_net_weight');
            $table->timestamps();
        });

        Schema::create('purchase_rolls_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('transaction_id');
            $table->date('date');
            $table->unsignedInteger('product_id');
            $table->string('batchNo', 50)->nullable();
            $table->decimal('thickness', 20,2)->nullable();
            $table->decimal('width', 20,2)->nullable();
            $table->integer('color');
            $table->decimal('net_weight', 20,2)->nullable();
            $table->decimal('gross_weight', 20,2)->nullable();
            $table->unsignedInteger('warehouse_id')->nullable();
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
        Schema::dropIfExists('purchase_rolls');
    }
};
