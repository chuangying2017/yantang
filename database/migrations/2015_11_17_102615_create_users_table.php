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
            $table->string('user_id')->unique();
            $table->string('login_account')->unique();
            $table->string('name');
            $table->string('user_name')->unique();
            $table->string('avatar');
            $table->string('sex');
            $table->string('mobile')->unique();
            $table->string('tel');
            $table->string('email')->unique();
            $table->integer('user_grade');
            $table->string('gift_ticket');
            $table->softDeletes();
            $table->timestamp('birthday');
            $table->timestamp('createtime');
            $table->timestamp('modifiled_time');
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
        Schema::drop('users');
    }
}
