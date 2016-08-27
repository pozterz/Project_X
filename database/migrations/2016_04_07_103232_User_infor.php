<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserInfor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_informations', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDdelete('cascade')->onUpdate('cascade');
            $table->string('name',100);;
            $table->enum('gender',['male','female'])->default('male');
            $table->string('card_id',13)->uniqe();
            $table->string('address',255);
            $table->string('tel',10);
            $table->date('birthday');
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
        Schema::drop('user_information');
    }
}
