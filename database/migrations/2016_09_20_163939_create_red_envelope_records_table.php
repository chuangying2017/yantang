<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedEnvelopeRecordsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('red_records', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('user_id');
            $table->string('rule_id');

            $table->string('resource_type', 64);
            $table->integer('resource_id');

            $table->integer('total');
            $table->integer('dispatch');

            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->string('status', 32);

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
        Schema::drop('red_records');
    }
}
