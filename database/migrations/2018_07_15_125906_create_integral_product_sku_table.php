<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralProductSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_product_sku', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->increments('id');
            $table->integer('product_id')->unsigned()->index('product_id','product_id');
            $table->char('unit',100)->nullable()->comment('产品单位 瓶| 箱 | 杯 | 盒');
            $table->integer('quantity')->unsigned()->nullable()->comment('发布数量/库存');
            $table->integer('sales')->nullable()->comment('兑换量');
            $table->decimal('postage')->default(0)->comment('邮费');
            $table->char('name',100)->default(0);
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
        Schema::drop('integral_product_sku');
    }
}
