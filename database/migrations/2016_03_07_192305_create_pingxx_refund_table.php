<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePingxxRefundTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pingxx_refund', function (Blueprint $table) {
            $table->increments('id');
            $table->string('refund_id');
            $table->integer('pingxx_payment_id');
            $table->string('charge_id');
            $table->string('payment_no');
            $table->string('transaction_no');
            $table->integer('order_id');
            $table->unsignedInteger('amount');
            $table->string('status');
            $table->integer('time_succeed');
            $table->string('failure_code');
            $table->string('failure_msg');
            $table->string('description');
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
        Schema::drop('pingxx_refund');
    }
}
