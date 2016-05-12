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
            $table->string('payment_no');
            $table->string('refund_id');
            $table->string('charge_id');
            $table->integer('pingxx_payment_id')->unsigned();
            $table->string('transaction_no')->nullable();
            $table->unsignedInteger('amount');
            $table->string('status');
            $table->integer('time_succeed')->nullable();
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
