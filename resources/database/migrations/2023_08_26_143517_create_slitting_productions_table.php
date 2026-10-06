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
        Schema::create('slitting_productions', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->date('date');
            $table->unsignedInteger('consume_product_id');
            $table->string('remarks')->nullable();
            $table->integer('warehouse_id');
            $table->integer('prepared_id');
            $table->integer('updated_id');
            $table->timestamps();
        });
        Schema::create('slitting_production_details', function (Blueprint $table) {
            $table->id();
            $table->integer('slitting_production_id');
            $table->date('date');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('warehouse_id');
            $table->integer('thickness')->nullable();
            $table->integer('width')->nullable();
            $table->integer('length')->nullable();
            $table->decimal('qty', 20, 2)->nullable();
            $table->integer('packing')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('status')->nullable();
            $table->unsignedInteger('godownID_for_edit');
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
        Schema::dropIfExists('slitting_productions');
    }
};
