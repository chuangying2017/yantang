<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatementNoToPreorderBillingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preorder_billings', function (Blueprint $table) {
            $table->string('statement_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preorder_billings', function (Blueprint $table) {
            $table->dropColumn('statement_no');
        });
    }
}
