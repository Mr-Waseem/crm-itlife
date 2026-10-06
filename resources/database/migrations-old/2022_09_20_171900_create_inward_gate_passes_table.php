<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inward_gate_passes', function (Blueprint $table) {
            $table->id();
            $table->integer('bill_no');
             $table->integer('req_gen_id');
            $table->date('date');
            $table->integer('supplier_id');
            $table->integer('purchaser_id');
            $table->integer('warehouse_id');
            $table->string('vehicle_no');
            $table->string('driver_name');
            $table->string('driver_phoneno');
            $table->string('transport_company');
            $table->string('builty_no');
            $table->boolean('status');
            $table->decimal('total_amount')->nullable();
            $table->decimal('total_qty')->nullable();
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
        Schema::dropIfExists('inward_gate_passes');
    }
};