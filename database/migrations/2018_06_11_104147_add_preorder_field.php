<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreorderField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preorders', function (Blueprint $table) {
            //
            $table->tinyInteger('comment_identify')->default('0')->comment('new addition uses comment identify');
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
            //

        });
    }
}
