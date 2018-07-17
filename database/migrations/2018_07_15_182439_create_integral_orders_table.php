<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->char('order_no',255)->nullable()->comment('订单号');
            $table->integer('user_id')->unsigned();
            $table->char('status',100)->comment('下单状态 代发货|待收货|已完成');
            $table->char('tracking',100)->nullable()->comment('快递单号');
            $table->decimal('cost_integral',10,2)->default(0)->comment('耗费积分');
            $table->char('pay_channel',100)->default('integral')->comment('支付方式 默认积分');
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
        Schema::drop('integral_orders');
    }
}
