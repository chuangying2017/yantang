<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeExpireToPingxxTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pingxx_payments', function (Blueprint $table) {
            $table->integer('time_expire');
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
            $table->dropColumn('expire_time');
        });
    }
}
