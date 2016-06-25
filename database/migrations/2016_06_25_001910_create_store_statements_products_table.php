<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreStatementsProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_statement_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('statement_no');
            $table->integer('store_id');
            $table->integer('product_id');
            $table->integer('product_sku_id');
            $table->integer('price');
            $table->integer('service_fee');
            $table->integer('quantity');
            $table->integer('total_amount');
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
        Schema::drop('store_statement_products');
    }
}
