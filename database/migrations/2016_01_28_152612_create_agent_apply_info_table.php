<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentApplyInfoTable extends Migration {

    /**
     * Run the migrations.
     * 输入银行卡号，
     * 开户行信息，
     * 上级代码，
     * 企业名称，
     * 法人姓名，
     * 联系电话，
     * 电子邮件，
     * 上传营业执照照片，
     * 上传法人身份证（正反面），
     * 上传门头照片，
     * 上传租赁合同
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_apply_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('status')->default('pending');
            $table->string('bank_no');
            $table->string('bank_detail');
            $table->string('parent_agent_id');
            $table->string('agent_role');
            $table->string('name');
            $table->string('director_name');
            $table->string('phone');
            $table->string('email');
            $table->string('license_image');
            $table->string('id_image');
            $table->string('office_image');
            $table->string('contract_image');
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
        Schema::drop('agent_apply_info');
    }
}
