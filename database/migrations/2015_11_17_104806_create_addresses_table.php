<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('phone', 15);
            $table->string('tel', 15);
            $table->string('province', 20);
            $table->string('city', 20);
            $table->string('district', 20);
            $table->string('detail');
            $table->boolean('is_primary');
            $table->tinyInteger('index')->default(1);
            $table->string('display_name', 64);
            $table->string('zip', 10);
            $table->softDeletes();
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
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign('addresses_user_id_foreign');
        });
        Schema::drop('addresses');
    }
}
