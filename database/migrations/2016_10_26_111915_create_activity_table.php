
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->increments('id');
            $table->string('activity_no');
            $table->string('name');
            $table->string('priority', 32);
            $table->string('desc', 5120);
            $table->string('cover_image');
            $table->string('background_color');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('coupons');
            $table->string('status', 32);
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
        Schema::drop('activity');
    }
}
