<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignRulesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_rules', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned()->index();
            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->integer('rule_id')->unsigned()->index();
            $table->foreign('rule_id')->references('id')->on('promotion_rules');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_rules', function (Blueprint $table) {
            $table->dropForeign('campaign_rules_campaign_id_foreign');
        });
        Schema::table('campaign_rules', function (Blueprint $table) {
            $table->dropForeign('campaign_rules_rule_id_foreign');
        });
        Schema::drop('campaign_rules');
    }
}
