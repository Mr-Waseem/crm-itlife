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
        Schema::create('rate_list_details', function (Blueprint $table) {
            $table->id();
            $table->integer('rate_list_id');
            $table->integer('product_id');
            $table->decimal('previous_rate');
            $table->decimal('new_rate');
            $table->decimal('packing')->nullable();
            $table->text('remarks')->nullable();
            $table->date('voucher_date')->nullable();
            $table->decimal('tax_rate')->nullable();
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
        Schema::dropIfExists('rate_list_details');
    }
};
