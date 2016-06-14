<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatementsProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statements_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('statements_id')->unsigned();
            $table->string('name');
            $table->integer('settle_price');
            $table->integer('service_fee');
            $table->integer('quantity');
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
        Schema::drop('statements_product');
    }
}
