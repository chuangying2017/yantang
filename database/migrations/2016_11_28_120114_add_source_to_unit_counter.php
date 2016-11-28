<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceToUnitCounter extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unit_counters', function (Blueprint $table) {
            $table->string('source_type');
            $table->string('source_id');
            $table->index(['source_type', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unit_counters', function (Blueprint $table) {
            $table->dropColumn('source_type');
            $table->dropColumn('source_id');
        });
    }
}
