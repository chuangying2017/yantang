<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicePreorderTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id');
            
            $table->integer('order_id');
            $table->integer('preorder_id');
            $table->string('order_no');

            $table->string('status', 32);

            $table->string('name');
            $table->string('phone');
            $table->string('address');

            $table->integer('station_id');
            $table->string('station_name');

            $table->integer('staff_id');
            $table->string('staff_name');

            $table->integer('total_amount');
            $table->integer('discount_amount');
            $table->integer('pay_amount');
            $table->integer('service_amount');
            $table->integer('receive_amount');

            $table->dateTime('confirm_at');
            $table->dateTime('order_at');

            $table->string('detail', 1024);

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
        Schema::drop('invoice_orders');
    }
}
