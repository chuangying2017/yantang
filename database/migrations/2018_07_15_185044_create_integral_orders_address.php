<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralOrdersAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_orders_address', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->integer('order_id')->unsigned();
            $table->char('tel',15)->nullable()->comment('联系电话');
            $table->char('name',50)->nullable();
            $table->char('phone',15)->nullable();
            $table->char('province',50)->nullable();
            $table->char('city',50)->nullable();
            $table->char('district',100)->nullable();
            $table->char('detail',255)->nullable();
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
        Schema::drop('integral_orders_address');
    }
}
