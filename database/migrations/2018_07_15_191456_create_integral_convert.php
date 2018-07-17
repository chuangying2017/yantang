<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralConvert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_convert', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->increments('id');
            $table->integer('integral')->unsigned()->comment('兑换积分数量');
            $table->char('convert_code')->comment('兑换码');
            $table->boolean('status')->default(true)->comment('兑换状态');
            $table->char('type',50)->default('integral')->comment('默认兑换积分');
            $table->dateTime('start_time')->comment('start convert time');
            $table->dateTime('end_time')->comment('end convert time');
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
        Schema::drop('integral_convert');
    }
}
