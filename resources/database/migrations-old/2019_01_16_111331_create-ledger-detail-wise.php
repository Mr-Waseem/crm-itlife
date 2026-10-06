<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgerDetailWise extends Migration
{
      public function up()
      {
            Schema::create('ledger_detail_wise', function (Blueprint $table) {
                  $table->increments('id');
                  $table->integer('purchase_id')->nullable()->unsigned();
                  $table->integer('purchase_ret_id')->nullable()->unsigned();
                  $table->integer('sale_id')->nullable()->unsigned();
                  $table->integer('sale_ret_id')->nullable()->unsigned();
                  $table->integer('dc_id')->nullable()->unsigned();
                  $table->integer('purchasetax_id')->nullable()->unsigned();
                  $table->integer('grn_id')->nullable()->unsigned();
                  $table->integer('lc_id')->nullable()->unsigned();
                  $table->integer('product_id')->unsigned()->nullable();
                  $table->integer('party_id')->unsigned();
                  $table->integer('other_head_id')->nullable()->unsigned();
                  $table->integer('voucher_id')->nullable()->unsigned();
                  $table->integer('bank_id')->nullable()->unsigned();
                  $table->integer('attendance_id')->nullable()->unsigned();
                  $table->integer('purchase_milk_id')->nullable()->unsigned();
                  $table->integer('warehouse_id')->nullable()->unsigned();
                  $table->string('invoice_no', 100)->nullable();
                  $table->string('voucher_no', 100);
                  $table->string('cheque_no', 30)->nullable();
                  $table->string('voucher_type', 100);
                  $table->date('date');
                  $table->string('quantity', 20)->nullable();
                  $table->decimal('rate', 20, 2)->nullable();
                  $table->string('other', 200)->nullable();
                  $table->string('debit', 20)->nullable();
                  $table->string('credit', 20)->nullable();
                  $table->timestamps();
            });
      }

      public function down()
      {
            //
      }
}
