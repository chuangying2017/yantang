<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('merchant_id');
            $table->integer('product_sku_id');
            $table->integer('quantity');
            $table->decimal('price', 11, 0);
            $table->decimal('discount_amount', 11, 0);
            $table->decimal('pay_amount', 11, 0);
            $table->integer('billing_id');
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
