<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAddressTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_address', function (Blueprint $table) {
            $table->integer('order_id')->unsigned()->index();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->string('name');
            $table->string('phone', 15);
            $table->string('tel', 15);
            $table->string('province', 20);
            $table->string('city', 20);
            $table->string('district', 20);
            $table->string('detail');
            $table->string('zip', 10);
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
        Schema::table('order_address', function (Blueprint $table) {
            $table->dropForeign('order_address_order_id_foreign');
        });
        Schema::drop('order_address');
    }
}
