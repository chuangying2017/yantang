<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntegralOrderSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integral_orders_sku', function (Blueprint $table) {
            $table->decimal('total_integral',10,2)->nullable()->comment('购买总积分');
            $table->decimal('single_integral',8,2)->nullable()->comment('单个购买积分');
            $table->char('product_name',100)->nullable()->comment('商品名称');
            $table->dateTime('reject_date')->nullable()->comment('拒绝时间');
            $table->char('reject_detail',255)->nullable()->comment('拒绝原因描述');
            $table->char('express',100)->nullable()->comment('快递公司');
            $table->char('expressOrder',200)->nullable()->comment('快递单号');
            $table->char('specification',255)->nullable()->comment('兑换规格');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integral_orders_sku', function (Blueprint $table) {
            //
        });
    }
}
