<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaser_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('bill_no');
            $table->date('date');
            $table->integer('supplier_id');
            $table->integer('igp_number');
            $table->string('type');
            $table->decimal('total_amount')->nullable();
            $table->decimal('total_qty')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('purchaser_stocks');
    }
};
