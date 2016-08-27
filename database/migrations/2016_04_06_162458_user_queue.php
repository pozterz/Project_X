<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserQueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_queues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('queue_id')->unsigned()->index();
            $table->integer('user_id')->unsigned();
            $table->string('queue_captcha',40);
            $table->dateTime('queue_time');
            $table->string('ip',20);
            $table->timestamps();
        });
         Schema::table('user_queues',function(Blueprint $table){
            $table->foreign('queue_id')->references('queue_id')->on('main_queues')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('user_queues');
    }
}
