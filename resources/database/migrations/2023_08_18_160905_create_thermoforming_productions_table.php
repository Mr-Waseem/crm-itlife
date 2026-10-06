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
        Schema::create('thermoforming_productions', function (Blueprint $table) {
            $table->id();
            // $table->unsignedInteger('batch_id')->nullable();
            $table->integer('voucher_no');
            $table->date('date');
            $table->string('remarks')->nullable();
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('batch_id')->nullable();
            $table->decimal('dye_units', 20,2)->nullable();
            $table->unsignedInteger('shift_id')->nullable();
            $table->unsignedInteger('operator_id')->nullable();
            $table->unsignedInteger('machine_id')->nullable();
            $table->unsignedInteger('pressman_id')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->decimal('pressman_no', 20,2)->nullable();
            $table->integer('total_sheets')->nullable();
            $table->integer('check_sheets')->nullable();
            $table->decimal('wastage', 20,2)->nullable();
            $table->decimal('net_sheets', 20,2)->nullable();
            $table->decimal('net_sku', 20,2)->nullable();
            $table->decimal('consumed', 20,2)->nullable();
            $table->integer('consumed_product_id')->nullable();
            // $table->decimal('gross', 20,2)->nullable();
            
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
        Schema::dropIfExists('thermoforming_productions');
    }
};
