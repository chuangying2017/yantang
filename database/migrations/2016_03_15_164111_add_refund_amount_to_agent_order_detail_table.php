<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefundAmountToAgentOrderDetailTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_order_detail', function (Blueprint $table) {
            $table->integer('refund_amount')->default(0);
            $table->timestamp('effect_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_order_detail', function (Blueprint $table) {
            $table->dropColumn('refund_amount');
            $table->dropColumn('effect_at');
        });
    }
}
