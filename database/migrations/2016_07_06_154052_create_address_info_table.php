<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressInfoTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_info', function (Blueprint $table) {
            $table->integer('address_id')->primary();
            $table->integer('district_id');
            $table->string('longitude', 45);
            $table->string('latitude', 45);
            $table->integer('station_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('address_info');
    }
}
