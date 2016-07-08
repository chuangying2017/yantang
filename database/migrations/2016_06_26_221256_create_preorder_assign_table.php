<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreorderAssignTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder_assign', function (Blueprint $table) {
            $table->integer('preorder_id')->unsigned();
            $table->integer('station_id')->unsigned();
            $table->integer('staff_id')->unsigned();
            $table->string('status', 32);
            $table->dateTime('time_before');
            $table->dateTime('confirm_at')->nullable();
            $table->string('memo')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['preorder_id', 'time_before']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('preorder_assign');
    }
}
