<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntegralProductSkuOnemore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integral_product_sku', function (Blueprint $table) {
            $table->char('bar_code',100)->nullable()->comment('商品编码');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integral_product_sku', function (Blueprint $table) {
            //
        });
    }
}
