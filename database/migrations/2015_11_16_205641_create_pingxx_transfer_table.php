<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePingxxTransferTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pingxx_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_no')->index();
            $table->integer('user_id')->unsigned();
            $table->string('transfer_id');
            $table->unsignedInteger('amount');
            $table->unsignedInteger('amount_settle');
            $table->string('currency')->defualt('cny');
            $table->string('recipient'); //openid
            $table->string('transaction_no')->nullable();
            $table->string('type', 10);
            $table->string('status');
            $table->string('description');
            $table->string('app');
            $table->boolean('livemode')->default(false);
            $table->string('failure_code');
            $table->string('failure_msg');
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
