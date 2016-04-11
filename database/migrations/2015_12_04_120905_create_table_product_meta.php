<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->text('attributes');
            $table->text('detail');
            $table->boolean('is_virtual');
            $table->string('origin_id');
            $table->integer('express_fee');
            $table->boolean('with_invoice');
            $table->boolean('with_care');
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
        Schema::drop('product_meta');
    }
}
