<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssignStatusToPreordersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->dateTime('pay_at')->nullable();
            $table->dateTime('confirm_at')->nullable();
            $table->dateTime('deliver_at')->nullable();
            $table->dateTime('done_at')->nullable();
            $table->dateTime('cancel_at')->nullable();
            $table->tinyInteger('invoice')->default(0);
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
            $table->dropColumn('pay_at');
            $table->dropColumn('confirm_at');
            $table->dropColumn('deliver_at');
            $table->dropColumn('done_at');
            $table->dropColumn('cancel_at');
            $table->dropColumn('invoice');
        });
    }
}
