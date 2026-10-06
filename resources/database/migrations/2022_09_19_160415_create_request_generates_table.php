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
            $table->string('type', 100)->nullable();
            $table->integer('request_no')->nullable()->default(0);
            $table->integer('po')->nullable()->default(0);
            $table->date('po_date')->nullable();
            $table->string('remarks', 200)->nullable();
            $table->decimal('tax_with_holding', 20, 2)->nullable();
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