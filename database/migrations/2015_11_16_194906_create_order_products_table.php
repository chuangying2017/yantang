<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('origin_order_id')->unsigned()->index();
            $table->integer('order_id')->unsigned()->index();
            $table->foreign(['order_id', 'origin_order_id'])->references('id')->on('orders');
            $table->integer('merchant_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('product_sku_id')->unsigned();
            $table->smallInteger('quantity')->unsigned();
            $table->unsignedInteger('price');
            $table->unsignedInteger('discount_amount');
            $table->unsignedInteger('pay_amount');
            $table->string('title');
            $table->string('cover_image');
            $table->string('attr', 1024);
            $table->smallInteger('return_quantity')->default(0);
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
        Schema::drop('order_products');
    }
}
