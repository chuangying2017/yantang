<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShareImageToActivityTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity', function (Blueprint $table) {
            $table->string('share_image');
            $table->string('share_friend_title');
            $table->string('share_desc');
            $table->string('share_moment_title');
            $table->tinyInteger('can_share')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity', function (Blueprint $table) {
            $table->dropColumn('share_image');
            $table->dropColumn('share_friend_title');
            $table->dropColumn('share_desc');
            $table->dropColumn('share_moment_title');
            $table->dropColumn('can_share');
        });
    }
}
