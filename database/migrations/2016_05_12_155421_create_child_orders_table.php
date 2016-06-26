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
        Schema::drop('child_orders');
    }
}
