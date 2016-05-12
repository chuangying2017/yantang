<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponRulesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_rules', function (Blueprint $table) {
            $table->integer('coupon_id')->unsigned()->index();
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->integer('rule_id')->unsigned()->index();
            $table->foreign('rule_id')->references('id')->on('promotion_rules');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_rules', function (Blueprint $table) {
            $table->dropForeign('coupon_rules_coupon_id_foreign');
        });
        Schema::table('coupon_rules', function (Blueprint $table) {
            $table->dropForeign('coupon_rules_rule_id_foreign');
        });
        Schema::drop('coupon_rules');
    }
}
