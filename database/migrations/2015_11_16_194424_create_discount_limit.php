<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountLimit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_limit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->unsignedInteger('amount_limit');
            $table->string('category_limit');
            $table->string('product_limit');
            $table->tinyInteger('multi_use')->default(0);
            $table->string('resource_type');
            $table->integer('resource_id');
            $table->timestamp('effect_time')->nullable();
            $table->timestamp('expire_time')->nullable();
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
        Schema::drop('discount_limit');
    }
}
