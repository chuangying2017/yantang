<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefundToOrderProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->integer('refund_amount')->default(0);
            $table->integer('return_quantity')->default(0);
            $table->string('refund_status')->nullable();
            $table->timestamp('refund_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn('refund_amount');
            $table->dropColumn('return_quantity');
            $table->dropColumn('refund_status');
            $table->dropColumn('refund_at');
        });
    }
}
