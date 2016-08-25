<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionCounterTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_counter', function (Blueprint $table) {
            $table->integer('promotion_id')->unsigned()->index();
            $table->integer('effect_days')->unsigned()->default(0);
            $table->integer('total')->unsigned();
            $table->integer('dispatch')->unsigned()->default(0);
            $table->integer('used')->unsigned()->default(0);
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
        Schema::drop('promotion_counter');
    }
}
