<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignTableNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_month', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->char('seasons',20)->comment('签到季度');
            $table->integer('total')->default(0)->comment('总签到天数');
            $table->integer('continuousSign')->default(0)->comment('连续签到天数');
            $table->integer('total_integral')->default(0)->comment('当月总领取积分');
            $table->tinyInteger('fetchNum')->default(0)->comment('连续签到获取次数');
            $table->integer('monthDaysNum')->default(0)->comment('当月的总天数');
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
        Schema::drop('signmonth');
    }
}
