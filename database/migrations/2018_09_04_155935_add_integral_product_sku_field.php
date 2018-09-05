<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntegralProductSkuField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integral_product_sku', function (Blueprint $table) {
            $table->string('specification',2000)->nullable()->comment('规格');
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
