<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntegralProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integral_product', function (Blueprint $table) {
            $table->char('type',100)->default('not_limit')->comment('默认不限制not limit,on limit 限制');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integral_product', function (Blueprint $table) {
            //
        });
    }
}
