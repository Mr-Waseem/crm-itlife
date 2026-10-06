<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('request_generate_details', function (Blueprint $table) {
            $table->id();
            $table->integer('request_generate_id');
            $table->integer('bill_no');
            $table->date('date');
            $table->integer('supplier_id')->default(0);
            $table->integer('purchaser_id')->default(0);
            $table->integer('warehouse_id')->nullable();
            $table->string('product_code');
            $table->integer('product_id');
            $table->decimal('qty')->default(0);
            $table->string('unit')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('status')->nullable();
            $table->string('type', 100);
            $table->integer('po')->default(0);
            $table->date('po_date')->nullable();
            $table->decimal('provided_qty')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_generate_details');
    }
};