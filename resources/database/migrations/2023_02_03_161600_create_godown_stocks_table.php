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
        Schema::create('godown_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->integer('inward_gatepass_id');
            $table->date('date');
            $table->string('type')->nullable();
            $table->integer('from_warehouse_id');
            $table->integer('to_warehouse_id');
            $table->integer('party_id');
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('godown_stocks');
    }
};
