<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_id');
            $table->integer('order_id');
            $table->string('order_no');
            $table->integer('total_amount');
            $table->integer('discount_fee')->default(0);
            $table->integer('pay_amount');
            $table->integer('deliver_id');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('child_orders');
    }
}
