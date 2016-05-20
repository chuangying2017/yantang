<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreorderProductSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder_product_sku', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pre_product_id')->unsigned();
            $table->integer('sku_id')->unsigned();
            $table->integer('count');
            $table->string('sku_name');
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
        Schema::drop('preorder_product_sku');
    }
}
