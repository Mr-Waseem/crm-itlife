<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTables extends Migration
{
    public function up()
    {
        Schema::create('account_heads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_group', 100);
            $table->string('title', 100);
            $table->string('account_no', 100);
            $table->timestamps();
        });

        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned()->nullable();
            $table->integer('shop_id')->unsigned()->nullable();
            $table->string('voucher_no', 100);
            $table->date('voucher_date');
            $table->string('v_type', 30);
            $table->decimal('total_debit')->default(0);
            $table->decimal('total_credit')->default(0);
            $table->string('biller', 30);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

        Schema::create('general_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_head_id')->unsigned();
            $table->integer('other_head_id')->nullable()->unsigned();
            $table->integer('voucher_id')->unsigned()->nullable();
            $table->integer('bank_id')->unsigned()->nullable();
            $table->integer('warehouse_id')->nullable()->unsigned();
            $table->date('date');
            $table->string('voucher_no', 20);
            $table->string('cheque_no', 50)->nullable();
            $table->string('cheque_date', 50)->nullable();
            $table->string('v_type', 30);
            $table->string('narration', 300)->nullable();
            $table->decimal('debit', 20)->default(0);
            $table->decimal('credit', 20)->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

        Schema::create('bank_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_head_id')->unsigned();
            $table->date('date');
            $table->string('voucher_no', 100);
            $table->string('debit', 20)->nullable();
            $table->string('credit', 20)->nullable();
            $table->timestamps();
        });
        Schema::create('cash_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_head_id')->unsigned();
            $table->date('date');
            $table->string('voucher_no', 100);
            $table->decimal('amount', 20, 2);
            $table->timestamps();
        });

        Schema::create('cash_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_head_id')->unsigned();
            $table->date('date');
            $table->string('voucher_no', 100);
            $table->decimal('amount', 20, 2);
            $table->timestamps();
        });

        Schema::create('cheque_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_head_id')->unsigned();
            $table->date('date');
            $table->string('voucher_no', 100);
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