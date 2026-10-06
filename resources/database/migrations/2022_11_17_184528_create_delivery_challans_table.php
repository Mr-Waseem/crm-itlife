<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_challans', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no')->index();
            $table->date('voucher_date')->index();
            $table->integer('party_id')->index();
            $table->integer('warehouse_id')->index();
            $table->string('remarks', 250)->nullable();
            $table->date('order_date');
            $table->integer('sale_order_no')->nullable();
            $table->decimal('total_qty')->nullable();
            $table->decimal('total_rate')->nullable();
            $table->decimal('total_net_weight')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->date('po_date')->nullable();
            $table->string('po_no')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_challans');
    }
};
