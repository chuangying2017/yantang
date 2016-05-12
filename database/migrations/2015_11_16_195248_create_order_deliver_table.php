<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDeliverTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_deliver', function (Blueprint $table) {
            $table->integer('order_id')->unsigned()->primary();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->string('company_id', 45);
            $table->string('company_name');
            $table->string('post_no');
            $table->timestamp('deliver_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_deliver');
    }
}
