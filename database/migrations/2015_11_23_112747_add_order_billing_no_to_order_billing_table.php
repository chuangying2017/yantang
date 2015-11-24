<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderBillingNoToOrderBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_billing', function (Blueprint $table) {
            $table->string('billing_no')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_billing', function (Blueprint $table) {
            $table->dropColumn('billing_no');
        });
    }
}
