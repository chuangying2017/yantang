<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedEnvelopeRulesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('red_rules', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('desc');
            $table->string('type', 32);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('coupons', 1024);
            $table->integer('quantity');
            $table->integer('effect_days');
            $table->integer('dispatch')->default(0);
            $table->integer('total')->default(0);
            $table->string('content');
            $table->string('status', 32);

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
        Schema::drop('red_rules');
    }
}
