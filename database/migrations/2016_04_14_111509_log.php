<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Log extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queue_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('queue_name',150);
            $table->string('counter',100);
            $table->dateTime('start');
            $table->dateTime('end');
            $table->enum('status',['ready','begin','ended'])->default('ready');
            $table->integer('current_count')->unsigned();
            $table->integer('max_count')->unsigned();
            $table->integer('owner')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('queue_logs');
    }
}
