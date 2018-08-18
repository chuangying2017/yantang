<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignIntegralRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_integral_record', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sign_month_id');
            $table->integer('days')->default(0)->comment('当天是多少号');
            $table->integer('everyday_integral')->comment('每天积分');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sign_integral_record');
    }
}
