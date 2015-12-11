<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsViewTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('

        CREATE VIEW order_products_view AS
            SELECT
            `product_sku_view`.`id` as `id`,
            `product_sku_view`.`price` as `price`,
            `product_sku_view`.`merchant_id` as `merchant_id`,
            `product_sku_view`.`title` as `title`,
            `product_sku_view`.`cover_image` as `image`,
            `order_products`.`quantity` as `quantity`,
            `order_products`.`order_id` as `order_id`,
            `order_products`.`pay_amount` as `pay_amount`,
            `order_products`.`id` as `order_product_id`,
            `product_sku_view`.`attributes` as `attributes`
            FROM
            `order_products`
            join `product_sku_view`
            on `product_sku_view`.`id` = `order_products`.`product_sku_id`
            GROUP BY `order_products`.`id`
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW order_products_view');
    }
}
