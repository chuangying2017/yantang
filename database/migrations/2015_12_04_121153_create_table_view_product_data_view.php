<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableViewProductDataView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE VIEW product_data_view as SELECT
            SUM(product_sku.sales) AS salse,
            COUNT(user_product_favs.product_id) AS favs
            FROM products
                LEFT JOIN user_product_favs ON products.id = user_product_favs.product_id
                LEFT JOIN product_sku ON products.id = product_sku.product_id
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW product_data_view');
    }
}
