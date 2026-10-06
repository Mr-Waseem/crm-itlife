<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CashBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_books', function(Blueprint $table)
        {
            $table->increments('id');
            $table->date('date', 50);
            $table->string('vr_no', 10);
            $table->string('vr_type', 50);
            $table->integer('biller_id')->unsigned()->nullable();
            $table->integer('shop_id')->unsigned()->nullable();
            $table->integer('party_id')->unsigned()->nullable();
            $table->integer('purchase_id')->unsigned()->nullable();
            $table->integer('sale_id')->unsigned()->nullable();
            $table->integer('cash_receipt_id')->unsigned()->nullable();
            $table->integer('cash_payment_id')->unsigned()->nullable();
            $table->integer('bank_receipt_id')->unsigned()->nullable();
            $table->integer('bank_payment_id')->unsigned()->nullable();
            $table->integer('jv_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->decimal('in', 20, 2)->nullable();
            $table->decimal('out', 20, 2)->nullable();
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
        //
    }
}
