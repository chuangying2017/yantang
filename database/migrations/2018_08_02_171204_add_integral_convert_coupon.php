<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntegralConvertCoupon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integral_convert_coupon', function (Blueprint $table) {
            $table->char('cover_image',255)->nullable()->comment('首图');
            $table->boolean('member_type')->default(true)->comment('限制新旧会员');
            $table->tinyInteger('limit_num')->nullable()->comment('限制会员领取数量');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integral_convert_coupon', function (Blueprint $table) {
            //
        });
    }
}
