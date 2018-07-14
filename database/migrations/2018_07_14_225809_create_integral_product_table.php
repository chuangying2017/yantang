<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_no')->unique(); // 产品编码
            $table->integer('category_id')->default(0)->comment('integral of product');
            $table->char('title',100);
            $table->double('price',6,2)->comment('描述商品原价格');
            $table->integer('sort_type');
            $table->double('integral',10,2)->default(0)->comment('商品积分价');
            $table->char('cover_image',255)->nullable()->comment('show page image');
            $table->char('digest',255)->nullable()->comment('商品描述');
            $table->char('advertising',200)->nullable()->comment('广告语');
            $table->char('status',50)->nullable()->comment('商品状态 上架|售罄|下架');
            $table->char('type',50)->nullable()->comment('虚拟,实物,产品包,服务');
            $table->integer('priority')->default(0);
            $table->dateTime('open_time')->nullable()->comment('产品兑换时间');
            $table->dateTime('end_time')->nullable()->comment('产品停止兑换时间');
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
        Schema::drop('integral_product');
    }
}
