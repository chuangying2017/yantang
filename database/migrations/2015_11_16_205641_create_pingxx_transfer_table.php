<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePingxxTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pingxx_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('openid');
            $table->string('transfer_id');
            $table->string('payment_id');
            $table->decimal('amount', 11, 0);
            $table->string('currency')->defualt('cny');
            $table->string('recipient');
            $table->string('transaction_no');
            $table->string('type');
            $table->string('status');
            $table->string('error_code');
            $table->string('error_msg');
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
        Schema::drop('pingxx_transfer');
    }
}
