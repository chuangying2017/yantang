<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePreorderProductSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preorder_product_sku', function (Blueprint $table) {
            $table->integer('price')->after('sku_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preorder_product_sku', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
}
