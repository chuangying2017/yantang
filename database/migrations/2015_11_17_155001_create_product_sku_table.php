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
            $table->integer('subscribe_price')->unsigned();
            $table->integer('service_fee')->unsigned();
            $table->string('attr', 1024);
            $table->string('type')->default('entity');
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
