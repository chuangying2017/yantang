<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignMonthNew1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sign_month', function (Blueprint $table) {
            $table->string('signArray',250)->nullable()->comment('连续签到关联储存');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sign_month', function (Blueprint $table) {
            //
        });
    }
}
