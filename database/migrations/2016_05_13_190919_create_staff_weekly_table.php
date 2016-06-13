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
        Schema::create('staff_weekly', function (Blueprint $table) {
            $table->increments('id');
            $table->string('week_id', 8);
            $table->integer('staff_id')->unsigned();
            $table->integer('station_id')->unsigned();
            $table->string('mon')->nullable();
            $table->string('wed')->nullable();
            $table->string('tue')->nullable();
            $table->string('thu')->nullable();
            $table->string('fri')->nullable();
            $table->string('sat')->nullable();
            $table->string('sun')->nullable();
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
        Schema::drop('staff_weekly');
    }
}
