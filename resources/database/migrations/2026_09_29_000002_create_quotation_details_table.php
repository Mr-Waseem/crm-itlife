<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quotation_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quotation_id')->unsigned();
            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->integer('party_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->string('product_name', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->date('line_date')->nullable();
            $table->timestamps();

            $table->index('quotation_id');
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotation_details');
    }
};
