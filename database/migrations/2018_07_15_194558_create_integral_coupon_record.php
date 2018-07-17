<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralCouponRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_coupon_record', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->increments('id');
            $table->integer('icc_id')->comment('integral_convert_coupon表id');
            $table->integer('user_id')->index('user_id','user_id');
            $table->char('status',20)->comment('待使用,已使用,已过期');
            $table->dateTime('valid_time')->nullable()->comment('有效时间');
            $table->dateTime('expiration_time')->nullable()->comment('过期时间');
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
        Schema::drop('integral_coupon_record');
    }
}
