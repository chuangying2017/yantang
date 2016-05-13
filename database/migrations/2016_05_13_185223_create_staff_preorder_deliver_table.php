<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffPreorderDeliverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_preorder_deliver', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('preorder_id')->unsigned();
            $table->integer('preorder_order_id')->unsigned();
            $table->integer('staff_id')->unsigned();
            $table->tinyInteger('status');
            $table->tinyInteger('daytime');
            $table->integer('station_id');
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
        Schema::drop('staff_preorder_deliver');
    }
}
