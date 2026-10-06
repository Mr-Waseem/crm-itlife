<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('right_names', function (Blueprint $table) {
            $table->id();
            $table->integer('code');
            $table->string('name')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('right_names');
    }
};