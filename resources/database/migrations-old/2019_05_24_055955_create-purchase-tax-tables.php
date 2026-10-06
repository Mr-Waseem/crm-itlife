<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseTaxTables extends Migration
{
    public function up()
    {
        Schema::create('purchase_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('party_id')->unsigned();
            $table->string('voucher_no', 100);
            $table->string('invoice_no', 100);
            $table->date('date');
            $table->string('purchase_type', 20);
            $table->string('remarks', 500)->nullable();
            $table->string('biller', 100);
            $table->timestamps();
        });

        Schema::create('purchase_tax_details', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('purchase_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('party_id')->unsigned();
            $table->integer('uom_id')->unsigned();
            $table->string('status', 20);
            $table->string('quantity', 100);
            $table->decimal('rate', 20, 2);
            $table->string('stvalue', 100);
            $table->string('taxvalue', 100);
            $table->string('extratax', 100);
            $table->string('extraTaxValue', 100);
            $table->decimal('price', 20, 2);
            $table->decimal('total', 20, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        //
    }
}
