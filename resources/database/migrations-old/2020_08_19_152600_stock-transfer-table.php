<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StockTransferTable extends Migration
{
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('bill_no');
            $table->integer('from_warehouse_id')->unsigned();
            $table->integer('to_warehouse_id')->unsigned();
            $table->decimal('total_qty')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->string('type');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_transfer_details', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('bill_no');
            $table->integer('transfer_id');
            $table->integer('product_id');
            $table->integer('uom_id');
            $table->integer('warehouse_id');
            $table->string('qty_in', 100)->default(0);
            $table->string('qty_out', 100)->default(0);
            $table->decimal('sale_rate', 20, 2)->default(0);
            $table->decimal('sale_amount', 20, 2)->default(0);
            $table->string('type');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('rawmaterial_to_salepoint', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('invoice_no');
            $table->integer('from_warehouse_id')->unsigned();
            $table->integer('to_warehouse_id')->unsigned();
            $table->string('biller', 100);
            $table->timestamps();
        });

        Schema::create('rawmaterial_to_salepoint_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transfer_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('uom_id')->unsigned();
            $table->integer('from_warehouse_id')->unsigned();
            $table->integer('to_warehouse_id')->unsigned();
            $table->string('quantity', 100);
            $table->decimal('sale_rate', 20, 2);
            $table->decimal('sale_amount', 20, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        //
    }
}