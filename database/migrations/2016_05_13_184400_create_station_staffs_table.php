<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('station_id')->unsigned();
            $table->string('staff_no', 64);
            $table->string('name');
            $table->string('phone');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('station_staffs');
    }
}
