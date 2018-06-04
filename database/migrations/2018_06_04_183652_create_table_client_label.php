<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClientLabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientlabel', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->default('0')->comment('comment user addition label title');
            $table->integer('star_level')->default('0')->comment('accounts in corresponding level proceed edit label');
            $table->string('comment_label')->default('0')->comment('accounts setting comment label');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clientlabel');
    }
}
