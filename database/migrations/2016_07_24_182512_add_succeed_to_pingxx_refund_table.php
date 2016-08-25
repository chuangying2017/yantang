<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSucceedToPingxxRefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pingxx_refund', function (Blueprint $table) {
            $table->integer('billing_id')->unsigned();
            $table->string('billing_type', 64);
            $table->boolean('succeed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pingxx_refund', function (Blueprint $table) {
            $table->dropColumn('billing_id');
            $table->dropColumn('billing_type');
            $table->dropColumn('succeed');
        });
    }
}
