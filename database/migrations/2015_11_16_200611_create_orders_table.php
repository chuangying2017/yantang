<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('order_no');
            $table->unsignedInteger('total_amount');
            $table->unsignedInteger('post_fee');
            $table->unsignedInteger('discount_amount');
            $table->unsignedInteger('pay_amount');
            $table->string('memo');
            $table->string('status');
            $table->timestamp('pay_at')->nullAble();
            $table->timestamp('deliver_at')->nullAble();
            $table->timestamp('cancel_at')->nullAble();
            $table->timestamp('done_at')->nullAble();
            $table->string('deliver_type')->default('post');
            $table->string('pay_type')->default('cash');
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
        Schema::drop('orders');
    }
}
