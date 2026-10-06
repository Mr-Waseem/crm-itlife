<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnTable extends Migration
{
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->date('date');
            $table->string('bill_no', 100);
            $table->string('grn_no', 100);
            $table->string('purchase_type', 20);
            $table->string('due_date', 20)->nullable();
            $table->string('particulars', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_return_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_id')->unsigned();
            $table->integer('party_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('uom_id')->nullable();
            $table->string('quantity', 100);
            $table->string('remaining_quantity', 100);
            $table->string('unit_cost', 100);
            $table->decimal('total_cost', 20, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        //
    }
}
