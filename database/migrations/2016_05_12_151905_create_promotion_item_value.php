<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionItemValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_item_value', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rule_id')->unsigned()->index();
            $table->string('value', 45)->index(); //all:全体商品; 指定商品,分类,品牌ID
            $table->smallInteger('count')->default(1); //0:不限次数
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
        Schema::drop('promotion_item_value');
    }
}
