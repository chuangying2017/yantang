<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreorderOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preorder_order_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('preorder_order_id')->unsigned();
            $table->integer('sku_id')->unsigned();
            $table->string('name');
            $table->integer('price');
            $table->timestamp();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('preorder_order_products');
    }
}
