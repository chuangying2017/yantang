<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponCounterTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_counter', function (Blueprint $table) {
            $table->integer('coupon_id')->unsigned()->index();
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->integer('total')->unsigned();
            $table->integer('dispatch')->unsigned()->default(0);
            $table->integer('used')->unsigned()->default(0);
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
        Schema::table('coupon_counter', function (Blueprint $table) {
            $table->dropForeign('coupon_counter_coupon_id_foreign');
        });
        Schema::drop('coupon_counter');
    }
}
