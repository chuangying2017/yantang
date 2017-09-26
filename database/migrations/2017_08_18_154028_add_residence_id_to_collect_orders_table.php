<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResidenceIdToCollectOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collect_orders', function (Blueprint $table) {
            $table->integer('residence_id')->nullable()->after('address_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collect_orders', function (Blueprint $table) {
            $table->dropColumn('residence_id');
        });
    }
}
