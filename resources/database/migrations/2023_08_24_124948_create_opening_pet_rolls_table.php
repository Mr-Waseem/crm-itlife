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
        Schema::create('opening_pet_rolls', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id');
            $table->date('date');
            $table->unsignedInteger('product_id');
            $table->string('batchNo', 50)->nullable();
            $table->decimal('thickness', 20,2)->nullable();
            $table->decimal('width', 20,2)->nullable();
            $table->string('color', 30)->nullable();
            $table->decimal('net_weight', 20,2)->nullable();
            $table->decimal('gross_weight', 20,2)->nullable();
            $table->integer('warehouse_id')->nullable();
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
        Schema::dropIfExists('opening_pet_rolls');
    }
};
