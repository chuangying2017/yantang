<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResourceTypeToTicketsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->renameColumn('coupon_id', 'resource_id');
            $table->string('resource_type')->after('user_id');
            $table->string('billing_id')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('resource_type');
            $table->dropColumn('billing_id');
            $table->renameColumn('resource_id', 'coupon_id');
        });
    }
}
