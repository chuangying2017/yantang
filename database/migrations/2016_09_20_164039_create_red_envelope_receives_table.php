<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedEnvelopeReceivesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('red_receives', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('record_id');
            
            $table->integer('user_id');
            $table->integer('nickname');
            $table->integer('avatar');

            $table->integer('coupon_id');
            $table->string('content');
            $table->integer('ticket_id');

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
        Schema::drop('red_receives');
    }
}
