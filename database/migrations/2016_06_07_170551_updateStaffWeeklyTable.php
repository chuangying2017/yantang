<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStaffWeeklyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_weekly', function (Blueprint $table) {
            $table->integer('preorder_id')->after('week_of_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_weekly', function (Blueprint $table) {
            $table->dropColumn('preorder_id');
        });
    }
}
