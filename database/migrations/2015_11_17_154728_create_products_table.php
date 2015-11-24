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
            $table->string('product_id')->unique(); // 产品编码
            $table->integer('client_id'); //商家id
            $table->string('type'); //商品类型: 虚拟/实物
            $table->integer('stocks')->unsigned(); //商品库存
            $table->string('origin_id'); //商家编码
            $table->string('title'); //商品标题
            $table->integer('price')->unsigned(); //商品价格 单位:分
            $table->integer('limit')->unsigned()->default(0); //每人限购 0为不限
            $table->integer('express_fee')->unsigned(); //邮费
            $table->integer('member_discount')->unsigned(); //会员折扣 分
            $table->boolean('with_invoice'); // 是否有发票
            $table->boolean('with_care'); // 是否有保修
            $table->string('digest'); //商品简介
            $table->text('detail'); //商品详情
            $table->string('status'); //商品状态 上架|售罄|下架
            $table->string('open_status'); //商品开售类型 马上发售|定时发售
            $table->softDeletes();
            $table->timestamp('open_time'); //发售时间
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
