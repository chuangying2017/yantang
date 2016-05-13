<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffWeeklyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_weekly', function (Blueprint $table) {
            $table->increments('id');
            $table->string('week_id', 8);
            $table->integer('staff_id')->unsigned();
            $table->string('tue');
            $table->string('thu');
            $table->string('fri');
            $table->string('sat');
            $table->string('sun');
            $table->timestamp();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('staff_weekly');
    }
}
