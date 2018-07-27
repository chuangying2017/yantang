<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralUserAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_user_address', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->comment('用户Id');
            $table->char('name',50)->nullable()->comment('用户名称');
            $table->char('tel',20)->nullable()->comment('电话');
            $table->char('phone',20)->nullable()->comment('手机号码');
            $table->char('province',30)->nullable()->comment('省份');
            $table->char('city',30)->nullable()->comment('城市');
            $table->char('district',40)->nullable()->comment('区');
            $table->char('street',100)->nullable()->comment('街道');
            $table->char('detail',255)->nullable()->comment('详细地址');
            $table->char('type',50)->default('integral')->comment('默认积分');
            $table->tinyInteger('precise')->default(0)->comment('是否为默认地址');
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
        Schema::drop('integral_user_address');
    }
}
