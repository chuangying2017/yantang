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
        //todo@bryant change role to is_primary
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('mobile');
            $table->string('tel');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('detail');
            $table->boolean('is_primary');
            $table->tinyInteger('role')->default(4);
            $table->string('display_name');
            $table->string('zip');
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
        Schema::drop('addresses');
    }
}
