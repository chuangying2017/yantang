<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductSkuView extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW product_sku_view as SELECT

            product_sku.id AS id,
            product_sku.product_id AS product_id,
            product_sku.sku_no AS sku_no,
            product_sku.stock AS stock,
            product_sku.sales AS sales,
            product_sku.price AS price,
            products.merchant_id AS merchant_id,
            products.title AS title,
            products.member_discount AS member_discount,
            products.category_id AS category_id,
            product_sku.cover_image AS cover_image,
            group_concat(concat('{\"attribute_id\":\"',`attributes`.`id`,'\", \"arrtibute_name\":\"',`attributes`.`name`,'\", \"attribute_value_id\":\"',`attribute_values`.`id`,'\", \"attribute_value_name\":\"',`attribute_values`.`value`,'\"}') separator ',') AS `attributes`
            FROM product_sku
                LEFT JOIN products ON product_sku.product_id = products.id
                LEFT JOIN sku_attribute_value ON product_sku.id = sku_attribute_value.product_sku_id
                LEFT JOIN attribute_values ON sku_attribute_value.attribute_value_id = attribute_values.id
                LEFT JOIN attributes ON attribute_values.attribute_id = attributes.id
            GROUP BY product_sku.id
        ");
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW product_sku_view');
    }
}
