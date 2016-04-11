<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMemoToAgentApplyInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_apply_info', function (Blueprint $table) {
            $table->string('memo', 1000)->after('contract_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_apply_info', function (Blueprint $table) {
            $table->dropColumn('memo');
        });
    }
}
