<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderBillingTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_billing', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->index();
            $table->string('billing_no')->index();
            $table->integer('user_id')->unsigned();
            $table->unsignedInteger('amount');
            $table->string('pay_type', 45)->default('money'); //
            $table->string('pay_channel', 45); //weixin,alipay,credits
            $table->string('status', 45)->default('unpaid'); //paid,unpaid
            $table->unsignedInteger('return_amount')->default(0);
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
        Schema::drop('order_billing');
    }
}
