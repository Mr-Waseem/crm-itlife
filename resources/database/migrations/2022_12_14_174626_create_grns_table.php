<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grn', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no')->index();
            $table->date('voucher_date')->index();
            $table->integer('inward_gatepass_id');
            $table->integer('warehouse_id');
            $table->decimal('total_qty')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grn');
    }
};