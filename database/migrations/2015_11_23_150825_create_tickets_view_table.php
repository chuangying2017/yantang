<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsViewTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW tickets_view AS
            SELECT
            `tickets`.`id` as `id`,
            `tickets`.`user_id` as `user_id`,
            `tickets`.`resource_type` as `resource_type`,
            `tickets`.`resource_id` as `resource_id`,
            `tickets`.`ticket_no` as `ticket_no`,
            `tickets`.`status` as `status`,
            `tickets`.`billing_id` as `billing_id`,
            `tickets`.`created_at` as `created_at`,
            `discount_limit`.`roles` as `roles`,
            `discount_limit`.`level` as `level`,
            `discount_limit`.`quantity_per_user` as `quantity_per_user`,
            `discount_limit`.`quantity` as `quantity`,
            `discount_limit`.`amount_limit` as `amount_limit`,
            `discount_limit`.`category_limit` as `category_limit`,
            `discount_limit`.`product_limit` as `product_limit`,
            `discount_limit`.`multi_use` as `multi_use`,
            `discount_limit`.`effect_time` as `effect_time`,
            `discount_limit`.`expire_time` as `expire_time`
            FROM `tickets`
            LEFT JOIN `discount_limit`
            ON `discount_limit`.`resource_type` = `tickets`.`resource_type` and `discount_limit`.`resource_id` = `tickets`.`resource_id`
            WHERE `tickets`.`status` = 'pending' AND `tickets`.`deleted_at` IS null;
        ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW tickets_view");
    }
}
