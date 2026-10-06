<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->date('date');
            $table->string('type', 50);
            $table->string('transaction_type', 50);
            $table->integer('dcn_no')->nullable();
            $table->integer('supplier_id')->default(0);
            $table->integer('purchaser_id')->default(0);
            $table->integer('warehouse_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('party_id');
            $table->text('remarks')->nullable();
            $table->decimal('total_qty')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->decimal('total_sale_rate')->nullable();
            $table->decimal('total_net_weight')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};