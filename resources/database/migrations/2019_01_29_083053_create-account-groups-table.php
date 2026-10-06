<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('account_groups', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('code', 100);
            $table->string('name', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        //
    }
}
