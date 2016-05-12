<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSkuTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned()->index();
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('name');
            $table->string('cover_image');
            $table->string('sku_no')->unique()->index();
            $table->string('bar_code', 20)->index();
            $table->integer('stock')->unsigned();
            $table->integer('sales')->unsigned();
            $table->integer('price')->unsigned();
            $table->integer('display_price')->unsigned();
            $table->integer('express_fee')->unsigned()->default(0);
            $table->integer('income_price')->unsigned();
            $table->integer('settle_price')->unsigned();
            $table->string('attr', 1024);
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
        Schema::drop('product_skus');
    }
}
