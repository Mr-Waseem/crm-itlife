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
        Schema::create('purchaser_stock_details', function (Blueprint $table) {
            $table->id();
            $table->integer('purchaser_stock_id');
            $table->date('date');
            $table->integer('supplier_id');
            $table->integer('bill_no')->nullable();
            $table->integer('igp_number');
            $table->string('product_code');
            $table->integer('product_id');
            $table->string('product_name');
            $table->string('unit');
            $table->integer('price');
            $table->integer('qty');
            $table->integer('total_amount');
            $table->text('comments')->nullable();
            $table->string('type');
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
        Schema::dropIfExists('purchaser_stock_details');
    }
};
