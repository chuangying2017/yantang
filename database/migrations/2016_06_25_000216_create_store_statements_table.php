<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreStatementsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_statements', function (Blueprint $table) {
            $table->string('statement_no');
            $table->integer('store_id')->unsigned();
            $table->integer('settle_amount');
            $table->integer('service_amount');
            $table->integer('total_amount');
            $table->integer('status')->default(0);
            $table->dateTime('confirm_at')->nullable();
            $table->string('memo')->nullable();

            $table->string('system_operator')->nullable();
            $table->string('system_memo')->nullable();
            $table->string('system_confirm_at')->nullable();
            $table->string('system_statue')->default(0);

            $table->timestamps();

            $table->primary('statement_no');
            $table->index(['statement_no', 'store_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('store_statements');
    }
}
