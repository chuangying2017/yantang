<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePreorderBillingToDeliver extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('preorder_billings', 'preorder_deliver');
        Schema::table('preorder_deliver', function (Blueprint $table) {
            $table->dropColumn('billing_no');
            $table->renameColumn('pay_at', 'deliver_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preorder_deliver', function (Blueprint $table) {
            $table->string('billing_no');
            $table->renameColumn('deliver_at', 'pay_at');
        });

        Schema::rename('preorder_deliver', 'preorder_billings');
    }
}
