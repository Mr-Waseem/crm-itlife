<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quotation_milestones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quotation_id')->unsigned();
            $table->string('module_name', 255);
            $table->integer('payment_percent')->default(0);
            $table->integer('timeframe_days')->default(0);
            $table->timestamps();

            $table->index('quotation_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotation_milestones');
    }
};
