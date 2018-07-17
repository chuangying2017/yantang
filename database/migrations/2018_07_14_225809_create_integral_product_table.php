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
            $table->engine='InnoDB';
            $table->increments('id');
            $table->string('product_no')->unique()->comment('产品编码');
            $table->integer('category_id')->default(0)->comment('分类id');
            $table->char('title',100)->comment('产品标题');
            $table->double('price',6,2)->comment('描述商品原价格');
            $table->integer('sort_type')->nullable()->comment('类型排序');
            $table->double('integral',10,2)->default(0)->comment('商品积分价');
            $table->char('cover_image',255)->nullable()->comment('首页小图');
            $table->char('digest',255)->nullable()->comment('商品描述');
            $table->char('advertising',200)->nullable()->comment('广告语');
            $table->char('status',50)->nullable()->comment('商品状态 上架|售罄|下架');
            $table->integer('priority')->default(0)->comment('优先权限');
            $table->dateTime('open_time')->nullable()->comment('产品兑换时间');
            $table->dateTime('end_time')->nullable()->comment('产品停止兑换时间');
            $table->boolean('recommend')->default(false)->comment('是否为推荐商品');
            $table->text('detail')->nullable()->comment('商品详情');
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
