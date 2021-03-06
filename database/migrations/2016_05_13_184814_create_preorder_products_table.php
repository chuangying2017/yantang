<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreorderProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder_skus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('preorder_id')->unsigned();
            $table->tinyInteger('weekday');
            $table->tinyInteger('daytime');
            $table->integer('product_sku_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('name');
            $table->string('cover_image');
            $table->smallInteger('quantity');
            $table->integer('price');
            $table->integer('total_amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('preorder_skus');
    }
}
