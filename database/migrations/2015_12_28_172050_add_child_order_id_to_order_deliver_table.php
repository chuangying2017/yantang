<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChildOrderIdToOrderDeliverTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_deliver', function (Blueprint $table) {
            $table->integer('child_order_id')->after('order_id');
            $table->string('company_no')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_deliver', function (Blueprint $table) {
            $table->dropColumn('child_order_id');
            $table->dropColumn('company_no');

        });
    }
}
