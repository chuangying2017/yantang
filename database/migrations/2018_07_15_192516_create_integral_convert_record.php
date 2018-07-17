<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralConvertRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_convert_record', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index('user_id','user_id')->comment('兑换用户');
            $table->integer('convert_id')->comment('兑换id');
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
        Schema::drop('integral_convert_record');
    }
}
