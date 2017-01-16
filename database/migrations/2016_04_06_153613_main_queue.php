<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MainQueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_queues', function (Blueprint $table) {
            $table->integer('id')->unsigned()->index();
            $table->string('name',150);
            $table->string('counter',100);
            $table->dateTime('workingtime');
            $table->integer('workmin')->unsigned();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('description',500);
            $table->integer('max')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('main_queues',function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('main_queues');
    }
}
