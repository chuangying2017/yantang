<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_card', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name',100);
            $table->double('give',8,2)->default('0.00')->comment('积分数');
            $table->char('status',20)->default('show')->comment('是否上架show,hide');
            $table->char('type',20)->default('limits')->comment('limits默认限制loose宽松不限制');
            $table->boolean('mode')->default(false)->comment('默认新用户可以领取');
            $table->char('cover_image',200)->nullable()->comment('首页展示图片');
            $table->integer('issue_num')->default(0)->comment('发布数量');
            $table->integer('remain')->unsigned()->default(0)->comment('剩余量');
            $table->integer('get_member')->default(0)->commnet('会员已领取数量');
            $table->tinyInteger('draw_num')->default(1)->comment('会员限制领取数量');
            $table->dateTime('start_time')->comment('开始时间');
            $table->dateTime('end_time')->comment('结束时间');
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
        Schema::drop('integral_card');
    }
}
