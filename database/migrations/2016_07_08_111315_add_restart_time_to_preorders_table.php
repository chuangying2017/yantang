<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRestartTimeToPreordersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->date('pause_time')->nullable();
            $table->date('restart_time')->nullable();
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
            $table->dropColumn('pause_time');
            $table->dropColumn('restart_time');
        });
    }
}
