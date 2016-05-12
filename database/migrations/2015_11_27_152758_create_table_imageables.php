<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableImageables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imageables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id')->unsigned()->index();
            $table->foreign('image_id')->references('id')->on('images');
            $table->integer('imageable_id')->unsigned()->index();
            $table->string('imageable_type')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imageables', function (Blueprint $table) {
            $table->dropForeign('imageables_image_id_foreign');
        });
        Schema::drop('imageables');
    }
}
