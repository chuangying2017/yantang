<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('order_no');
            $table->string('title');
            $table->unsignedInteger('total_amount');
            $table->unsignedInteger('product_amount');
            $table->unsignedInteger('express_fee');
            $table->unsignedInteger('discount_amount');
            $table->unsignedInteger('pay_amount');

            $table->string('deliver_type', 20)->default('express');
            $table->string('pay_type', 20)->default('money');
            $table->string('pay_channel', 20)->default('weixin');

            $table->string('status', 20)->default('unpaid');
            $table->string('pay_status', 20)->default('unpaid');
            $table->string('refund_status', 20)->default('none');

            $table->tinyInteger('order_type'); // 0:普通,1:子订单,2:退款订单

            $table->dateTime('pay_at')->nullable();
            $table->dateTime('deliver_at')->nullable();
            $table->dateTime('cancel_at')->nullable();
            $table->dateTime('done_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index([
                'order_no',
                'status',
                'pay_status',
                'refund_status',
                'created_at',
            ]);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_user_id_foreign');
        });
        Schema::drop('orders');
    }
}
