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
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password', 60)->nullable();
            $table->string('confirmation_code')->nullable();;
            $table->boolean('confirmed')->default(config('access.users.confirm_email') ? false : true);
            $table->tinyInteger('status');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->index('phone');
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
