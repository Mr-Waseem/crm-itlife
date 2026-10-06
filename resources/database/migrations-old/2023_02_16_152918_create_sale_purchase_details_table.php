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
        Schema::create('sale_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->date('date');
            $table->bigInteger('sale_purchase_id')->unsigned()->index()->nullable();
            $table->foreign('sale_purchase_id')->references('id')->on('sale_purchases')->onDelete('cascade');
            $table->integer('product_id');
            $table->integer('warehouse_id');
            $table->integer('party_id');
            $table->decimal('demandQty');
            $table->decimal('qty');
            $table->decimal('rate');
            $table->decimal('excl_val');
            $table->decimal('st_rate');
            $table->decimal('sale_tax');
            $table->decimal('total');
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
        Schema::dropIfExists('sale_purchase_details');
    }
};
