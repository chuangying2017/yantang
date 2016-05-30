<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingPaymetns extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_payments', function (Blueprint $table) {
            $table->integer('billing_id')->unsigned()->index();
            $table->string('billing_type', 64)->index();
            $table->integer('pingxx_payment_id')->unsigned()->index();
            $table->boolean('paid')->default(false);
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
        Schema::drop('billing_payments');
    }
}
