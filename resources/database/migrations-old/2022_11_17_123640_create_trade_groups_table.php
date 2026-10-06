<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trade_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trade_groups');
    }
};