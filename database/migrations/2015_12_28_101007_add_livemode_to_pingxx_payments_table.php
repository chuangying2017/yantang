<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLivemodeToPingxxPaymentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pingxx_payments', function (Blueprint $table) {
            $table->string('app');
            $table->boolean('livemode')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pingxx_payments', function (Blueprint $table) {
            $table->dropColumn('app');
            $table->dropColumn('livemode');
        });
    }
}
