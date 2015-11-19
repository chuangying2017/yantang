<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountDistributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_distribute', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id');
            $table->string('resource_type');
            $table->integer('quantity')->default(0);
            $table->string('roles');
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
        Schema::drop('discount_distribute');
    }
}
