<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignIntegralRecordNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sign_integral_record', function (Blueprint $table) {
            $table->integer('repairNum')->default(0)->comment('当天补签次数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sign_integral_record', function (Blueprint $table) {
            //
        });
    }
}
