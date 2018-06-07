<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labelset', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('0')->comment('label comment');
            $table->integer('star_level')->default('0')->commnet('label star level 1 is one_level 2 is two_level');
            $table->softDeletes();
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
        Schema::drop('labelset');
    }
}
