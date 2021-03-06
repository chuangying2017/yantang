<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collect_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_id')->unsigned();
            $table->integer('address_id')->unsigned();
            $table->integer('sku_id')->unsigned();
            $table->integer('quantity')->unsigned()->default(1);
            $table->integer('order_id')->nullable();
            $table->timestamp('pay_at')->nullable();
            $table->boolean('invoice')->default(0);
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
        Schema::drop('collect_orders');
    }
}
