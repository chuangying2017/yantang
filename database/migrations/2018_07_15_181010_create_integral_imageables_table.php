<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralImageablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_imageables', function (Blueprint $table) {
            $table->integer('image_id')->unsigned();
            $table->integer('imageable_id')->unsigned();
            $table->char('imageable_type',200);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('integral_imageables');
    }
}
