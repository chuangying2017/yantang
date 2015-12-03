<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_billing', function (Blueprint $table) {
            $table->increments('id');
            $table->string('billing_no');
            $table->integer('order_id');
            $table->integer('user_id');
            $table->unsignedInteger('amount');
            $table->string('resource_type');
            $table->integer('resource_id');
            $table->string('type');
            $table->string('status');
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
        Schema::drop('order_billing');
    }
}
