<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('client_id');
            $table->integer('group_id');
            $table->string('product_id')->unique();
            $table->string('type');
            $table->integer('stocks')->unsigned();
            $table->string('code');
            $table->string('title');
            $table->integer('price')->unsigned();
            $table->integer('limit')->unsigned()->default(0);
            $table->integer('express_fee')->unsigned();
            $table->integer('member_discount')->unsigned();
            $table->boolean('with_invoice');
            $table->boolean('with_care');
            $table->string('desc');
            $table->text('detail');
            $table->string('status');
            $table->string('open_status');
            $table->softDeletes();
            $table->timestamp('open_time');
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
        Schema::drop('products');
    }
}
