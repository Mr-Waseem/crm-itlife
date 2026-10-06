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
        Schema::create('sale_purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->date('date');
            $table->string('type');
            $table->integer('grn_dc_id');
            $table->integer('party_id');
            $table->integer('warehouse_id');
            $table->string('remarks')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('sale_purchases');
    }
};
