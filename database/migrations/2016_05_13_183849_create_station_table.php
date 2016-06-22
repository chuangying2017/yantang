<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('user_id')->nullable();
            $table->integer('desc');
            $table->string('address');
            $table->string('tel')->nullable();
            $table->string('director');
            $table->string('phone', 32)->nullable();
            $table->string('cover_image');
            $table->string('longitude', 45);
            $table->string('latitude', 45);
            $table->string('status', 45);
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
        Schema::drop('station');
    }
}
