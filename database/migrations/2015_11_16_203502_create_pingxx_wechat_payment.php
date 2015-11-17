<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePingxxWechatPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pingxx_wechat_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_id');
            $table->integer('user_id');
            $table->string('charge_id');
            $table->string('transaction_no');
            $table->decimal('amount', 11, 0);
            $table->string('channel');
            $table->string('refund_id');
            $table->string('status');
            $table->dateTime('pay_at');
            $table->string('currency')->default('cny');
            $table->string('error_code');
            $table->string('error_msg');
            $table->decimal('amount_refunded', 11, 0);
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
        Schema::drop('pingxx_wechat_payment');
    }
}
