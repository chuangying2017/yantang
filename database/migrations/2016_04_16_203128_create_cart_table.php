<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('product_sku_id')->unsigned();
            $table->foreign('product_sku_id')->references('id')->on('product_skus');
            $table->integer('quantity');
            $table->tinyInteger('status')->default(0);
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
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('carts_user_id_foreign');
        });
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('carts_product_sku_id_foreign');
        });

        Schema::drop('carts');
    }
}
