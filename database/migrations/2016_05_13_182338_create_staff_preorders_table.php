<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffPreordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_preorders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('preorder_id')->unsigned();
            $table->integer('station_id')->unsigned();
            $table->integer('staff_id')->unsigned();
            $table->tinyInteger('index');
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
        Schema::drop('staff_preorders');
    }
}
