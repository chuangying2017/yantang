<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildOrdersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_orders', function (Blueprint $table) {
            $table->integer('order_id')->unsigned()->index();
            $table->integer('child_order_id')->unsigned()->index();
            $table->integer('merchant_id')->unsigned()->index();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('child_order_id')->references('id')->on('orders');
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
        Schema::table('child_orders', function (Blueprint $table) {
            $table->dropForeign('child_orders_order_id_foreign');
        });
        Schema::table('child_orders', function (Blueprint $table) {
            $table->dropForeign('child_orders_child_order_id_foreign');
        });
        Schema::drop('child_orders');
    }
}
