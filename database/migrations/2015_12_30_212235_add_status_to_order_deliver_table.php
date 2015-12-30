<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToOrderDeliverTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_deliver', function (Blueprint $table) {
            $table->string('status')->after('post_no');
            $table->dropColumn('company_no');
            $table->dropColumn('order_id');
            $table->dropColumn('child_order_id');
            $table->dropColumn('company_id');
            $table->dropColumn('deliver_at');
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
            $table->dropColumn('status');
            $table->string('company_no');
            $table->string('order_id');
            $table->string('child_order_id');
            $table->string('company_id');
            $table->timestamp('deliver_at');
        });
    }
}
