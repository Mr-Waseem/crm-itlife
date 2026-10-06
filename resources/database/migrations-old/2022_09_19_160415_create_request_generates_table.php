<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('request_generates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('bill_no');
            $table->integer('supplier_id');
            $table->integer('purchaser_id')->default(0);
            $table->integer('warehouse_id')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->boolean('status')->nullable();
            $table->string('type', 100);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_generates');
    }
};