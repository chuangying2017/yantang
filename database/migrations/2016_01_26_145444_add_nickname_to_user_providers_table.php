<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNicknameToUserProvidersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_providers', function (Blueprint $table) {
            $table->string('nickname')->after('id');
            $table->string('union_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_providers', function (Blueprint $table) {
            $table->dropColumn('nickname');
            $table->dropColumn('union_id');
        });
    }
}
