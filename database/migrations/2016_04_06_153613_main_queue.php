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
            $table->integer('queue_id')->unsigned()->index();
            $table->string('queue_name',150);
            $table->string('counter',100);
            $table->dateTime('opentime');
            $table->integer('service_time')->unsigned();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->enum('status',['ready','begin','ended'])->default('ready');
            $table->integer('current_count')->unsigned();
            $table->integer('max_count')->unsigned();
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
