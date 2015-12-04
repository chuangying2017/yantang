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
        Schema::create('credits_wallet_record', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('amount')->unsigned();
            $table->smallInteger('income')->unsigned();
            $table->string('type');
            $table->string('status');
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
        Schema::drop('credits_wallet_record');
    }
}
