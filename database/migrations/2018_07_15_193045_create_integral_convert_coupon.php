<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralConvertCoupon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_convert_coupon', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->increments('id');
            $table->integer('cost_integral')->comment('消耗积分');
            $table->integer('promotions_id')->comment('优惠券id');
            $table->dateTime('valid_time')->comment('有效时间');
            $table->dateTime('deadline_time')->comment('截止时间');
            $table->boolean('status')->default(false)->comment('发布状态true上架false下架');
            $table->char('type',30)->default('interval')->comment('interval间隔时间perpetual永久有效');
            $table->integer('issue_num')->comment('发布数量');
            $table->integer('draw_num')->comment('已领取数量');
            $table->integer('remain_num')->comment('剩余数量');
            $table->integer('delayed')->nullable()->comment('延时 时间');
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
        Schema::drop('integral_convert_coupon');
    }
}
