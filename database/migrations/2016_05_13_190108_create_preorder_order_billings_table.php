<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreorderOrderBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder_order_billings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('preorder_order_id')->unsigned();
            $table->string('billing_no');
            $table->integer('amount');
            $table->string('status', 45);
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
        Schema::drop('preorder_order_billings');
    }
}
