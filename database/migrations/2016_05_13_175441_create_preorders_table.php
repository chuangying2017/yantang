<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('order_no');
            $table->string('name');
            $table->string('phone', 11);
            $table->string('address');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('pause_time'); //暂停时间
            $table->dateTime('restart_time');
            $table->tinyInteger('daytime')->default(o);
            $table->string('status', 32);
            $table->string('charge_status', 32);
            $table->timestamp();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('preorders');
    }
}
