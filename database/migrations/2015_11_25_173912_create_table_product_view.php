<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductView extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW product_detail as SELECT

            products.client_id AS client_id,
            products.type AS type,
            products.stocks AS stocks,
            products.origin_id AS origin_id,
            products.title AS title,
            products.price AS price,
            products.limit AS limit,
            products.express_fee AS express_fee,
            products.member_discount AS member_discount,
            products.with_care AS with_care,
            products.with_invoice AS with_invoice,
            products.desc AS desc,
            products.detail AS detail,
            products.status AS status,
            products.open_status AS open_status,
            products.open_time AS open_time,
            products.category_id AS category_id,
            products.cover_image AS cover_image,
            group_concat() AS images,
            group_concat() AS images,
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
