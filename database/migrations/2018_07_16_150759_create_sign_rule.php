<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_rule', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('status')->default(true)->comment('默认开始签到');
            $table->boolean('retroactive')->default(true)->comment('默认开启补签');
            $table->tinyInteger('everyday')->default(1)->comment('默认每天可补签一次');
            $table->integer('integral')->default(0)->comment('补签扣除积分');
            $table->string('state',2048)->comment('说明');
            $table->string('reminder',255)->comment('补签提示');
            $table->string('extend_rule',1000)->nullable();
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
        Schema::drop('sign_rule');
    }
}
