<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentablesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commentables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comment_id');
            $table->integer('commentable_id')->unsigned()->index();
            $table->string('commentable_type')->index();
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
        Schema::drop('commentables');
    }
}
