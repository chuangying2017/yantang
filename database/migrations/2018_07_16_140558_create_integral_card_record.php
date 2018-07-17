<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralCardRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_card_record', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id',false,true)->index('user_id','user_id');
            $table->integer('card_id')->comment('积分卡id');
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
        Schema::drop('integral_card_record');
    }
}
