<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTicketsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->index();
            $table->integer('user_id')->index();
            $table->string('ticket_no', 45)->index();
            $table->string('status', 45)->index();
            $table->dateTime('exchange_at')->nullable();
            $table->integer('store_id')->default(0)->index();
            $table->integer('settle_amount')->default(0);
            $table->tinyInteger('checkout')->default(0);
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
        Schema::drop('order_tickets');
    }
}
