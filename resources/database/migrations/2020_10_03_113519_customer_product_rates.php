<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerProductRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_rates', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('vr_no');
            $table->date('date');
            $table->string('biller', 100);
            $table->timestamps();
        });

        Schema::create('product_rate_details', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('productrate_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('product_code', 20);
            $table->decimal('rate', 20, 2);
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
