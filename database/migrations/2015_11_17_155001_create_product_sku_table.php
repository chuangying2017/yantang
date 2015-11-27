<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sku', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('cover_image');
            $table->integer('product_id');
            $table->string('sku_no')->unique();
            $table->integer('stock')->unsigned();
            $table->integer('sales')->unsigned();
            $table->integer('price')->unsigned();
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
        Schema::drop('product_sku');
    }
}
