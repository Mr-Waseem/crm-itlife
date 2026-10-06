<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseTable extends Migration
{
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('phone', 50);
            $table->string('email', 100);
            $table->string('address', 200);
            $table->integer('purchase_account_id')->unsigned()->nullable();
            $table->integer('sale_account_id')->unsigned()->nullable();
            $table->integer('purchasetax_account_id')->unsigned()->nullable();
            $table->integer('saletax_account_id')->unsigned()->nullable();
            $table->integer('tax_account_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        //
    }
}
