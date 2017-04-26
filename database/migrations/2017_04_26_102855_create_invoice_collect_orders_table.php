<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceCollectOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_collect_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_no');

            $table->integer('collect_order_id');
            $table->string('order_id');
            $table->string('order_no');

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

            $table->string('detail', 1024);

            $table->timestamp('pay_at');
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
        Schema::drop('invoice_collect_orders');
    }
}
