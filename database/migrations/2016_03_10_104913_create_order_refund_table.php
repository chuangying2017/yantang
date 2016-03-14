<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderRefundTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_refund', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('user_id');
            $table->integer('child_order_id');
            $table->integer('amount')->unsigned();
            $table->string('status');
            $table->string('company_name');
            $table->string('post_no');
            $table->string('client_memo');
            $table->string('merchant_memo');
            $table->timestamp('deliver_at');
            $table->timestamp('refund_at');
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
        Schema::drop('order_refund');
    }
}
