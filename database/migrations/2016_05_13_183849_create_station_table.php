<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('district_id');
            $table->integer('desc')->nullable();
            $table->string('address');
            $table->string('tel')->nullable();
            $table->string('director');
            $table->string('phone', 32);
            $table->string('cover_image');
            $table->string('longitude', 45);
            $table->string('latitude', 45);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('active')->default(1);
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
        Schema::drop('stations');
    }
}
