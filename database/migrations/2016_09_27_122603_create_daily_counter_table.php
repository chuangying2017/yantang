<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyCounterTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_counters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('counter_id');
            $table->string('type', 32);
            $table->string('time', 32);
            $table->integer('quantity')->default(0);
            $table->integer('amount')->default(0);
            $table->timestamps();

            $table->index('counter_id');
            $table->index(['type', 'time']);
            $table->index('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('unit_counters');
    }
}
