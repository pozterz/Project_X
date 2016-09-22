<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MainQueueUserQueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_queue_user_queue', function (Blueprint $table) {
            $table->integer('main_queue_id')->unsigned();
            $table->integer('user_queue_id')->unsigned();
        });
        Schema::table('main_queue_user_queue',function(Blueprint $table){
            $table->foreign('main_queue_id')->references('id')->on('main_queues')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_queue_id')->references('id')->on('user_queues')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('main_queue_user_queue');
    }
}
