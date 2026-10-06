<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WastageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wastages', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('invoice_no');
            $table->string('localExport', 100)->nullable();
            $table->string('biller', 100);
            $table->string('sale_type', 20);
            $table->string('sale_list', 20);
            $table->string('dcn_no', 20)->nullable();
            $table->integer('warehouse_id')->unsigned();
            $table->integer('party_id')->unsigned();
            $table->string('due_date', 20)->nullable();
            $table->string('particulars', 100)->nullable();
            $table->timestamps();

            // $table->foreign('party_id')
            //       ->references('id')
            //       ->on('parties')
            //       ->onDelete('cascade');
        });

        Schema::create('wastage_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wastage_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('party_id')->unsigned();
            $table->integer('uom_id')->unsigned();
            $table->integer('discount_id')->unsigned();
            $table->integer('biller')->unsigned();
            $table->integer('warehouse_id')->unsigned();
            $table->string('quantity', 100);
            $table->string('product_cost', 30)->nullable();
            $table->string('cost_amount', 30)->nullable();
            $table->decimal('sale_rate', 20, 2);
            $table->decimal('sale_amount', 20, 2);
            $table->timestamps();

            // $table->foreign('wastage_id')
            //       ->references('id')
            //       ->on('wastages')
            //       ->onDelete('cascade');

            // $table->foreign('product_id')
            //       ->references('id')
            //       ->on('products')
            //       ->onDelete('cascade');      

            // $table->foreign('party_id')
            //       ->references('id')
            //       ->on('parties')
            //       ->onDelete('cascade');
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
