<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameBillingPreorderSkuToDeliver extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('billing_preorder_skus', 'deliver_preorder_skus');
        Schema::table('deliver_preorder_skus', function (Blueprint $table) {
            $table->renameColumn('preorder_billing_id', 'preorder_deliver_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliver_preorder_skus', function (Blueprint $table) {
            $table->renameColumn('preorder_deliver_id', 'preorder_billing_id');
        });
        Schema::rename('deliver_preorder_skus', 'billing_preorder_skus');
    }
}
