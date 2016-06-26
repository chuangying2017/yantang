<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProvidersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_providers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
            $table->string('nickname');
            $table->string('union_id')->nullable();
			$table->string('provider');
			$table->string('provider_id');
			$table->string('avatar')->nullable();
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
		Schema::drop('user_providers');
	}

}
