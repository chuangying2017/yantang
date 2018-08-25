<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrowseIntegralProductSkus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integral_product_sku', function (Blueprint $table) {
            $table->integer('browse_num')->default(0)->comment('产品浏览数量');
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
