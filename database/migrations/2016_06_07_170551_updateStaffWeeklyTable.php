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
            $table->integer('preorder_id')->after('week_id');
            $table->text('mon')->nullable()->change();
            $table->text('wed')->nullable()->change();
            $table->text('tue')->nullable()->change();
            $table->text('thu')->nullable()->change();
            $table->text('fri')->nullable()->change();
            $table->text('sat')->nullable()->change();
            $table->text('sun')->nullable()->change();
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
