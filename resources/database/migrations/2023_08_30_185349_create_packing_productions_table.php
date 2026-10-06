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
        Schema::create('packing_productions', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->date('date');
            $table->unsignedInteger('batch_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('warehouse_id');
            $table->string('remarks')->nullable();
            $table->unsignedInteger('prepared_id')->nullable();
            $table->unsignedInteger('updated_id')->nullable();
            $table->timestamps();
        });

        Schema::create('packing_production_details', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->date('date');
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('warehouse_id');
            $table->unsignedInteger('employee_id');
            $table->decimal('qty', 20,2)->nullable();
            $table->decimal('pcs', 20,2)->nullable();
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
        Schema::dropIfExists('packing_productions');
    }
};
