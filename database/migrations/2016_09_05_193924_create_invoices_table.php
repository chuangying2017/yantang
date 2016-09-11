<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->string('invoice_no')->primary();
            $table->date('invoice_date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('merchant_id');
            $table->string('merchant_name');

            $table->tinyInteger('type');

            $table->string('status', 32);
            $table->string('memo');

            $table->integer('total_count');
            $table->integer('total_amount');
            $table->integer('discount_amount');
            $table->integer('pay_amount');
            $table->integer('service_amount');
            $table->integer('receive_amount');

            $table->timestamps();

            $table->index('merchant_id', 'invoice_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoices');
    }
}
