<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntegralProductSku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integral_product_sku', function (Blueprint $table) {
            $table->integer('convert_num')->comment('会员兑换量次');
            $table->integer('convert_unit')->comment('兑换单位');
            $table->tinyInteger('convert_day',false,true)->comment('距离兑换天数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integral_product_sku', function (Blueprint $table) {
            //
        });
    }
}
