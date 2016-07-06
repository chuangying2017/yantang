<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreorderSkusCounter extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder_sku_counter', function (Blueprint $table) {
            $table->integer('order_sku_id')->index();
            $table->integer('preorder_id')->index();
            $table->integer('order_id');
            $table->integer('product_id');
            $table->integer('product_sku_id')->index();
            $table->integer('total');
            $table->integer('remain');
            $table->tinyInteger('per_day');
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
        Schema::drop('preorder_sku_counter');
    }
}
