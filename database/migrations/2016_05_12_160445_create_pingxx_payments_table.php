<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePingxxPaymentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pingxx_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('billing_id')->unsigned()->index();
            $table->string('billing_type', 64)->index();
            $table->string('charge_id')->index();
            $table->string('payment_no')->index();
            $table->integer('user_id')->unsigned();
            $table->boolean('livemode')->default(false);
            $table->string('app')->index();
            $table->string('channel', 45);
            $table->string('currency', 45)->default('cny');
            $table->unsignedInteger('amount');
            $table->unsignedInteger('amount_settle');
            $table->dateTime('pay_at')->nullable();
            $table->integer('time_expire')->nullable();
            $table->integer('time_settle')->nullable();
            $table->string('transaction_no');
            $table->string('credential', 512);

            $table->boolean('paid')->default(false);
            $table->boolean('refunded')->default(false);

            $table->string('failure_code', 45)->nullable();
            $table->string('failure_msg')->nullable();
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
        Schema::drop('pingxx_payments');
    }
}
