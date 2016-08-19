<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContentToOrderPromotionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_promotions', function (Blueprint $table) {
            $table->string('content', 2048);
            $table->integer('ticket_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_promotions', function (Blueprint $table) {
            $table->string('content', 2048);
            $table->dropColumn('ticket_id');
        });
    }
}
