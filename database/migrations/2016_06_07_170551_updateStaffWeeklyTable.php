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
            $table->integer('week_of_year')->after('id');
            $table->integer('preorder_id')->after('week_of_year');
            $table->string('mon', 2000)->nullable()->change();
            $table->string('wed', 2000)->nullable()->change();
            $table->string('tue', 2000)->nullable()->change();
            $table->string('thu', 2000)->nullable()->change();
            $table->string('fri', 2000)->nullable()->change();
            $table->string('sat', 2000)->nullable()->change();
            $table->string('sun', 2000)->nullable()->change();
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
