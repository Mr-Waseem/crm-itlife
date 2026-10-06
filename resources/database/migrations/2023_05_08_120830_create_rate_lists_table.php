<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rate_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->date('voucher_date');
            $table->decimal('tax_rate')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rate_lists');
    }
};
