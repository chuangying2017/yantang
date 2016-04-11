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
        Schema::create('pingxx_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_id');
            $table->integer('order_id');
            $table->integer('billing_id');
            $table->integer('user_id');
            $table->string('charge_id');
            $table->string('transaction_no');
            $table->unsignedInteger('amount');
            $table->string('channel');
            $table->string('refund_id');
            $table->string('status');
            $table->dateTime('pay_at');
            $table->string('currency')->default('cny');
            $table->string('error_code');
            $table->string('error_msg');
            $table->unsignedInteger('amount_refunded');
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
        Schema::drop('pingxx_payments');
    }
}
