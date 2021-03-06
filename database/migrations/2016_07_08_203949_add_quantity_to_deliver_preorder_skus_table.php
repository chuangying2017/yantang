<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuantityToDeliverPreorderSkusTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliver_preorder_skus', function (Blueprint $table) {
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliver_preorder_skus', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
}
