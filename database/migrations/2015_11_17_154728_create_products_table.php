<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_no')->unique(); // 产品编码
            $table->integer('brand_id')->unsigned(); //商家id
            $table->integer('merchant_id')->unsigned(); //商家id
            $table->string('title'); //商品标题
            $table->string('sub_title'); //商品标题
            $table->string('digest'); //商品描述
            $table->string('cover_image'); //商品图片
            $table->integer('price')->unsigned(); //商品价格 单位:分
            $table->string('status'); //商品状态 上架|售罄|下架
            $table->string('type', 20); //虚拟,实物,产品包,服务
            $table->dateTime('open_time'); //发售时间
            $table->dateTime('end_time')->nullable(); //停售时间时间
            $table->boolean('with_invoice')->default(false);
            $table->boolean('with_care')->default(false);
            $table->softDeletes();
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
