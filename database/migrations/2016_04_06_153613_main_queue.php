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
            $table->increments('id');
            $table->string('name',150);
            $table->integer('queuetype_id')->unsigned()->index();
            $table->integer('counter')->unsigned();
            $table->dateTime('service_start');
            $table->dateTime('service_end');
            $table->integer('max_minutes')->unsigned()->index();
            $table->dateTime('open');
            $table->dateTime('close');
            $table->integer('max')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('main_queues',function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('counter')->references('counter_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('queuetype_id')->references('id')->on('queue_types')->onDelete('cascade')->onUpdate('cascade');
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
