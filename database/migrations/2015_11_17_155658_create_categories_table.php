<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 11)->index(); // 类型:主,品牌,促销...
            $table->string('name');
            $table->smallInteger('priority')->default(0);
            $table->integer('pid')->nullable(); //parent id
            $table->integer('lid')->nullable();
            $table->integer('rid')->nullable();
            $table->integer('depth')->nullable();
            $table->string('cover_image'); //分类封面
            $table->string('desc')->nullable(); //分类描述
            $table->softDeletes();
            $table->mediumInteger('item_count')->default(0);
            $table->mediumInteger('sub_cat_count')->default(0);
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
        Schema::drop('categories');
    }
}
