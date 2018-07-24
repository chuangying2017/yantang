<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->increments('id');
            $table->char('status',30)->index('status','status')->nullable()->comment('上下架状态');
            $table->char('type',50)->nullable()->comment('公司类型');
            $table->char('name',100)->nullable()->comment('公司名称');
            $table->char('detail',255)->nullable()->comment('公司描述');
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
        Schema::drop('company');
    }
}
