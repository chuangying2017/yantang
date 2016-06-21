<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreorderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('preorder_id')->unsigned();
            $table->tinyInteger('weekday');
            $table->tinyInteger('daytime');
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
        Schema::drop('preorder_products');
    }
}
