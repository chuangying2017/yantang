<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionRulesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('qua_quantity');//次数
            $table->string('qua_type', 45);//参与要求:新用户,会员,指定用户
            $table->string('qua_content');
            $table->string('item_type'); //优惠对象:商品
            $table->string('range_type', 45); //数量,金额
            $table->integer('range_max')->nullable(); //
            $table->integer('range_min');
            $table->string('discount_resource', 45); //优惠类型: 邮费,赠品,满减,满折
            $table->string('discount_mode', 45); //金额,折扣,特价
            $table->string('discount_content');
            $table->smallInteger('weight'); //权重,数字越大优先级越高
            $table->tinyInteger('multi_able')->default(0);//可叠加使用
            $table->string('memo');//备注
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
        Schema::drop('promotion_rules');
    }
}
