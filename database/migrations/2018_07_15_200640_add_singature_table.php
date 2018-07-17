<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSingatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sing_in', function (Blueprint $table) {//签到记录表
            $table->integer('integral')->comment('签到积分');
            $table->integer('continue_day')->comment('连续签到天数');
            $table->char('type',50)->default('weekdays')->comment('Weekdays|平日,continuous|连续');
            $table->char('message',255)->nullable()->comment('备注信息');
            $table->softDeletes();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sing_in', function (Blueprint $table) {
            //
        });
    }
}
