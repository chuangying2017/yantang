<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWechatInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wechat_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('open_id')->unique();
            $table->string('union_id');
            $table->string('nickname');
            $table->string('language');
            $table->boolean('sex');
            $table->string('province');
            $table->string('city');
            $table->string('country');
            $table->string('headimgurl');
            $table->string('privilge');
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
        Schema::drop('user_wechat_info');
    }
}
