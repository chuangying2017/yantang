<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderIdToPreorderSkusTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preorder_skus', function (Blueprint $table) {
            $table->integer('order_sku_id')->index();
            $table->integer('order_id')->index();
            $table->integer('total');
            $table->integer('remain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preorder_skus', function (Blueprint $table) {
            $table->dropColumn('order_sku_id');
            $table->dropColumn('order_id');
            $table->dropColumn('total');
            $table->dropColumn('remain');
        });
    }
}
