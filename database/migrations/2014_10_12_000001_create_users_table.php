<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',30)->unique();
            $table->string('password', 200);
            $table->string('name', 100);
            $table->string('email',100);
            $table->string('phone', 10);
            $table->string('ip',20);
            $table->integer('role_id')->unsigned();
            $table->string('remember_token',100);
            $table->timestamps();
        });
        Schema::table('users',function(Blueprint $table){
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
