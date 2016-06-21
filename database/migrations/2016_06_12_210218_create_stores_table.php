<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('store_no')->nullable();
            $table->string('name');
            $table->string('address');
            $table->string('cover_image');
            $table->string('director', 45);
            $table->string('phone', 15);
            $table->string('tel', 15);
            $table->string('longitude', 30);
            $table->string('latitude', 30);
            $table->tinyInteger('active')->default(1);
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
        Schema::drop('stores');
    }
}
