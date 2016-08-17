<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitToProductSkusTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->string('unit', '32')->default('份');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }
}
