<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_id')->unsigned();
            $table->integer('party_id')->unsigned();
            $table->string('date', 30)->nullable();
            $table->string('particulars', 500)->nullable();
            $table->string('bill_no', 30)->nullable();
            $table->string('debit', 30)->nullable();
            $table->string('credit', 30)->nullable();
            $table->timestamps();
        });

        Schema::create('supplier_ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id')->unsigned();
            $table->string('date', 30)->nullable();
            $table->string('particulars', 500)->nullable();
            $table->string('bill_no', 30)->nullable();
            $table->string('debit', 30)->nullable();
            $table->string('credit', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        //
    }
}
