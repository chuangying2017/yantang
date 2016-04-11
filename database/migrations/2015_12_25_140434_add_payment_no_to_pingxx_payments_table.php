<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentNoToPingxxPaymentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pingxx_payments', function (Blueprint $table) {
            $table->renameColumn('payment_id', 'payment_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pingxx_payments', function (Blueprint $table) {
            $table->renameColumn('payment_no', 'payment_id');
        });
    }
}
