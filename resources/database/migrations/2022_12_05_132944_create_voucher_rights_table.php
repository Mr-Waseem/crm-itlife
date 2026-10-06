<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('voucher_rights', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->string('voucher_name');
            $table->string('right_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voucher_rights');
    }
};