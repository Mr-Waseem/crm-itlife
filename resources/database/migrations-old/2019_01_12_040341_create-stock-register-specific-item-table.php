<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockRegisterSpecificItemTable extends Migration
{
      public function up()
      {
            Schema::create('stock_register_specific_items', function (Blueprint $table) {
                  $table->increments('id');
                  $table->integer('purchase_id')->nullable()->unsigned();
                  $table->integer('purchase_ret_id')->nullable()->unsigned();
                  $table->integer('sale_id')->nullable()->unsigned();
                  $table->integer('sale_ret_id')->nullable()->unsigned();
                  $table->integer('dc_id')->nullable()->unsigned();
                  $table->integer('purchasetax_id')->nullable()->unsigned();
                  $table->integer('grn_id')->nullable()->unsigned();
                  $table->integer('production_id')->nullable()->unsigned();
                  $table->integer('stock_transferID')->nullable()->unsigned();
                  $table->integer('direct_transferID')->nullable()->unsigned();
                  $table->integer('purchase_milk_id')->nullable()->unsigned();
                  $table->integer('sample_id')->nullable()->unsigned();
                  $table->date('date');
                  $table->string('code', 100)->nullable();
                  $table->integer('party_id')->nullable()->unsigned();
                  $table->integer('product_id')->unsigned();
                  $table->integer('recipe_id')->nullable()->unsigned();
                  $table->string('voucher_type', 100);
                  $table->integer('warehouse_id')->nullable()->unsigned();
                  $table->integer('uom_id')->nullable()->unsigned();
                  $table->string('purchase_quantity', 20)->nullable();
                  $table->string('pur_ret_quantity', 20)->nullable();
                  $table->string('sale_quantity', 500)->nullable();
                  $table->string('sale_ret_quantity', 20)->nullable();
                  $table->string('cost_rate')->nullable();
                  $table->timestamps();
            });
      }

      public function down()
      {
            //
      }
}
