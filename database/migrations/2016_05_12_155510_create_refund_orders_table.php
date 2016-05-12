<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundOrdersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_orders', function (Blueprint $table) {
            $table->integer('order_id')->unsigned()->index();
            $table->integer('return_order_id')->unsigned()->index();
            $table->string('operator', 45);
            $table->foreign(['return_order_id', 'order_id'])->references('id')->on('orders');
            $table->timestamps();
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
        Schema::drop('refund_orders');
    }
}
