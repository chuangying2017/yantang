<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntegralRecordNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integral_record', function (Blueprint $table) {
            $table->char('type',30)->nullable()->comment('签到类型user或admin');
            $table->char('role_name',50)->nullable()->comment('操作角色名');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integral_record', function (Blueprint $table) {
            //
        });
    }
}
