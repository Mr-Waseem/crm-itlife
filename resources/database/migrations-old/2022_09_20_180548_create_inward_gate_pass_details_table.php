<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inward_gate_pass_details', function (Blueprint $table) {
            $table->id();
            $table->integer('inward_gatepass_id');
            $table->integer('bill_no')->nullable();
            $table->date('date');
            $table->integer('supplier_id');
            $table->integer('purchaser_id');
            $table->integer('warehouse_id');
            $table->string('product_code');
            $table->integer('product_id');
            $table->string('product_name');
            $table->string('unit');
            $table->integer('price');
            $table->decimal('qty');
            $table->decimal('qtyshow');
            $table->integer('total_amount');
            $table->text('comments')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('inward_gate_pass_details');
    }
};