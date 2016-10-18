<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMarkTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('mark_type', 32);
            $table->string('mark_content', 1024);
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
        Schema::drop('order_marks');
    }
}
