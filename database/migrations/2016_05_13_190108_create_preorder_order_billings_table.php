<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreorderOrderBillingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder_billings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('station_id')->unsigned();
            $table->integer('staff_id')->unsigned();
            $table->integer('preorder_id')->unsigned();
            $table->string('billing_no');
            $table->integer('amount');
            $table->string('status', 45)->default('unpaid');
            $table->dateTime('pay_at');
            $table->timestamps();

            $table->index(['user_id', 'station_id','staff_id', 'status', 'billing_no']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('preorder_billings');
    }
}
