<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeekdayTypeToPreordersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->string('weekday_type');
            $table->tinyInteger('daytime');
            $table->integer('order_id');
            $table->dropColumn('charge_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->dropColumn('weekday_type');
            $table->dropColumn('daytime');
            $table->dropColumn('order_id');
            $table->tinyInteger('charge_status');
        });
    }
}
