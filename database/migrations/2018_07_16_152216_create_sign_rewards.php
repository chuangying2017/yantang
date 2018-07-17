<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignRewards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('status')->default(true)->comment('默认生效');
            $table->enum('award',['auto','hand_operation'])->default('hand_operation')->comment('自动或手动');
            $table->integer('interval_time')->comment('连续签到天数');
            $table->integer('integral_award')->comment('积分奖励');
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
        Schema::drop('sign_rewards');
    }
}
