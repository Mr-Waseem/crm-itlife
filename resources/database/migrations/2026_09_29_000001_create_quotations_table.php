<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('voucher_no')->unsigned();
            $table->date('date');
            $table->date('valid_to')->nullable();
            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->integer('party_id')->unsigned()->nullable();
            $table->string('atten', 150)->nullable();
            $table->string('subject', 255)->nullable();
            $table->text('features')->nullable();
            $table->integer('deadline_days')->nullable();
            $table->integer('warranty_months')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();

            $table->index(['warehouse_id', 'voucher_no']);
            $table->index('party_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotations');
    }
};
