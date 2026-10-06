<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RawMaterialStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_material_stocks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('purchase_id')->unsigned()->nullable();
            $table->integer('sale_id')->unsigned()->nullable();
            $table->integer('purchase_return_id')->unsigned()->nullable();
            $table->integer('sale_return_id')->unsigned()->nullable();
            $table->integer('production_id')->unsigned()->nullable();
            $table->integer('direct_transferID')->unsigned()->nullable();
            $table->integer('purchase_milk_id')->unsigned()->nullable();
            $table->integer('warehouse_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('uom_id')->unsigned();
            $table->date('date')->nullable();
            $table->string('voucher_no', 20)->nullable();
            $table->decimal('cost_amount', 20, 2);
            $table->decimal('stockin', 20, 2)->nullable();
            $table->decimal('stockout', 20, 2)->nullable();
            $table->timestamps();
        
        });

        Schema::create('production_stocks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('production_id')->unsigned()->nullable();
            $table->integer('pro_transfer_id')->unsigned()->nullable();
            $table->integer('warehouse_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('uom_id')->unsigned();
            $table->date('date')->nullable();
            $table->string('voucher_no', 20)->nullable();
            $table->decimal('cost_amount', 20, 2);
            $table->decimal('stockin', 20, 2)->nullable();
            $table->decimal('stockout', 20, 2)->nullable();
            $table->timestamps();
        
        });

        Schema::create('salepoint_stocks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('sale_id')->unsigned()->nullable();
            $table->integer('wastage_id')->unsigned()->nullable();
            $table->integer('pro_transfer_id')->unsigned()->nullable();
            $table->integer('direct_transferID')->unsigned()->nullable();
            $table->integer('warehouse_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('uom_id')->unsigned();
            $table->string('vr_no', 10);
            $table->date('date')->nulled();
            $table->integer('biller')->unsigned()->nullable();
            $table->decimal('cost_amount', 20, 2);
            $table->decimal('stockin', 20, 2)->nullable();
            $table->decimal('stockout', 20, 2)->nullable();
            $table->timestamps();
        
        });

        Schema::create('all_stocks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('raw_material_id')->unsigned()->nullable();
            $table->integer('production_stock_id')->unsigned()->nullable();
            $table->integer('sale_point_id')->unsigned()->nullable();
            $table->integer('warehouse_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('uom_id')->unsigned();
            $table->decimal('cost_amount', 20, 2);
            $table->decimal('stockin', 20, 2)->nullable();
            $table->decimal('stockout', 20, 2)->nullable();
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
