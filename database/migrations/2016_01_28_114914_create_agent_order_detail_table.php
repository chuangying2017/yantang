<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentOrderDetailTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_order_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_order_id');
            $table->integer('agent_id');
            $table->integer('agent_level');
            $table->string('order_no');
            $table->tinyInteger('status')->default(0);
            $table->integer('amount');
            $table->integer('award_amount');
            $table->integer('rate');
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
        Schema::drop('agent_order_detail');
    }
}
