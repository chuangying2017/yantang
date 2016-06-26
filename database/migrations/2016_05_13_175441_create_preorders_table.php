<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreordersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('order_no');
            $table->string('name');
            $table->string('phone', 32);
            $table->integer('district_id');
            $table->string('address');
            $table->string('status', 32);
            $table->tinyInteger('charge_status')->default(0);
            $table->integer('station_id')->unsigned();
            $table->integer('staff_id')->unsigned();
            $table->mediumInteger('staff_priority')->default(0);
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
        Schema::drop('preorders');
    }
}
