<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsWalletRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('amount')->unsigned();
            $table->tinyInteger('income')->unsigned();// 0,1
            $table->string('resource_type');
            $table->integer('resource_id')->unsigned();
            $table->string('type', 10);
            $table->tinyInteger('status');
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
        Schema::table('credits_records', function (Blueprint $table) {
            $table->dropForeign('credits_records_user_id_foreign');
        });
        Schema::drop('credits_records');
    }
}
