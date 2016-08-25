<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChargeOrderNoToPingxxRefund extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pingxx_refund', function (Blueprint $table) {
            $table->string('charge_order_no');
            $table->index('charge_id', 'refund_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pingxx_refund', function (Blueprint $table) {
            $table->dropColumn('charge_order_no');
            $table->dropIndex('charge_id');
            $table->dropIndex('refund_id');
        });
    }
}
