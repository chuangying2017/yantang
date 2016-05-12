<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionQuaValue extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_qua_value', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rule_id')->unsigned()->index();
            $table->foreign('rule_id')->references('id')->on('promotion_rules');
            $table->string('value', 45)->index(); //all:全体用户; 指定ID
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
        Schema::table('promotion_qua_value', function (Blueprint $table) {
            $table->dropForeign('promotion_qua_value_rule_id_foreign');
        });
        Schema::drop('promotion_qua_value');
    }
}
