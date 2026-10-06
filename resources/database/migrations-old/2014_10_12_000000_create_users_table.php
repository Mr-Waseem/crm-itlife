<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image', 250)->nullable();
            $table->integer('shop_id')->unsigned()->nullable();
            $table->integer('location_id')->unsigned()->nullable();
            $table->integer('biller_id')->unsigned();
            $table->integer('department_id')->nullable();
            $table->string('department_name')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address', 300)->nullable();
            $table->string('role')->nullable();
            $table->integer('status')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('party_id')->nullable();
            $table->string('type')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}