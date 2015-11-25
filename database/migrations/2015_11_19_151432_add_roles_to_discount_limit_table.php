<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRolesToDiscountLimitTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discount_limit', function (Blueprint $table) {
            $table->integer('quantity_per_user')->default(1)->after('id');
            $table->string('roles')->nullable()->after('id');
            $table->string('level')->after(0)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discount_limit', function (Blueprint $table) {
            $table->dropColumn('quantity_per_user');
            $table->dropColumn('roles');
            $table->dropColumn('level');
        });
    }
}
