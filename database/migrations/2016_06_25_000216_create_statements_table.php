<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatementsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statements', function (Blueprint $table) {
            $table->string('statement_no');
            $table->integer('merchant_id')->unsigned();
            $table->tinyInteger('year');
            $table->tinyInteger('month');
            $table->integer('settle_amount');
            $table->integer('service_amount');
            $table->integer('total_amount');
            $table->integer('status')->default(0);
            $table->dateTime('confirm_at')->nullable();
            $table->string('memo')->nullable();

            $table->string('system_operator')->nullable();
            $table->string('system_memo')->nullable();
            $table->string('system_confirm_at')->nullable();
            $table->string('system_status')->default(0);

            $table->tinyInteger('type');

            $table->timestamps();

            $table->primary('statement_no');
            $table->index(['statement_no', 'merchant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('statements');
    }
}
