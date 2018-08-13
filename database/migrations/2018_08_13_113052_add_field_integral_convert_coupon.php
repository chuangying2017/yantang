<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldIntegralConvertCoupon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integral_convert_coupon', function (Blueprint $table) {
            $table->integer('used_num')->default(0)->comment('使用数量');
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
