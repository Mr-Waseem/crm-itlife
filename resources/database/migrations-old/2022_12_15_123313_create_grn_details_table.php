<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grn_details', function (Blueprint $table) {
            $table->id();
            $table->integer('grn_id');
            $table->integer('inward_gatepass_id');
            $table->integer('warehouse_id');
            $table->integer('voucher_no');
            $table->date('voucher_date');
            $table->string('product_code');
            $table->integer('product_id');
            $table->string('product_name');
            $table->string('unit');
            $table->decimal('price');
            $table->decimal('qty');
            $table->decimal('total_amount');
            $table->string('comments', 500)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grn_details');
    }
};