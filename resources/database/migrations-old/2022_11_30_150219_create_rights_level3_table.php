<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rights_level3', function (Blueprint $table) {
            $table->id();
            $table->integer('code');
            $table->string('title');
            $table->string('url')->nullable();
            $table->integer('right_level2_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rights_level3');
    }
};